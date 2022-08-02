<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\ExpensiveNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\MeasurementModel;
use Auth;
use PDF;

class MeasurementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $measurements=DB::table('measurements')
        ->get();
        return view('measurements.index',compact('measurements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try {
            //code...
            $this->validate($request, [
            
                'measurement' => 'required',
                'symbol' => 'required',
                'type' => 'required',
            
            ]);

        
                $form_data1 = array(
                    'measurement'=>$request->measurement,
                    'symbol'=>$request->symbol,
                    'type' =>$request->type,
                    'description'=>$request->description, 
                    'created_at'=> Carbon::now(),
                    'updated_at'=> Carbon::now()
                );
            
    
            MeasurementModel::create($form_data1);
            return response()->json([
                'success' => true,
                'message' => 'Measurement Created',
            ]);
        }
        catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => json_encode($th),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
          //
           //
           if(request()->ajax()){
            $data =DB::table('measurements')->find($id);
            return response()->json(['data' => $data]);   
           }
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
            //
            try {
                //code...
                $this->validate($request, [
                
                    'measurement' => 'required',
                    'symbol' => 'required',
                
                ]);
    
            
                    $form_data1 = array(
                        'measurement'=>$request->measurement,
                        'symbol'=>$request->symbol,
                        'type' =>$request->type,
                        'description'=>$request->description, 
                        'updated_at'=> Carbon::now()
                    );
                
                    

                DB::table('measurements')->where('id','=',$id)->update($form_data1);
                return response()->json([
                    'success' => true,
                    'message' => 'Measurement updated!',
                ]);
            }
            catch (\Throwable $th) {
                //throw $th;
                return response()->json([
                    'success' => false,
                    'message' => $th,
                ]);
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $exp_delete = MeasurementModel::find($id);
            $exp_delete->delete();
            return response()->json([
                'success' => true,
                'message' => 'Measurement  Deleted'
            ]);
        }catch(\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => "".$th
            ]);
        }
    }
    public function apiMeasurements(){
        $measurement=DB::table('measurements')
        ->orderBy('created_at', 'DESC')
        ->get();
        if(Auth::user()->role=="Superadministrator"){
        return Datatables::of($measurement)
            ->addColumn('action', function($measurement){
                return '
                <div class="btn-group" style="width:100%">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Action <span class="caret"></span>
                   </button>
                   <ul class="dropdown-menu">
                   <li>
                   <li><a onclick="editForm('. $measurement->id .')" class="btn btn-info btn-xs" style="color:white"><i class="glyphicon glyphicon-edit" style="color:white"></i> edit</a></li>
                      <li><a onclick="deleteData('. $measurement->id .')" class="btn btn-danger btn-xs" style="color:white"><i class="glyphicon glyphicon-trash" style="color:white"></i> Delete</a></li>
                       <li><a href="/sales_info/'.$measurement->id .'" class="btn btn-success btn-xs more_details" style="color:white" ><i class="glyphicon glyphicon-eye-open" style="color:white"></i>More Details</a></li>
                   </ul>
               </div> ';
            })
            ->rawColumns(['name','symbol','action'])->make(true);
        }else{
            return Datatables::of($measurement)
            ->addColumn('action', function($measurement){
                return '
                <div class="btn-group" style="width:100%">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Action <span class="caret"></span>
                   </button>
                   <ul class="dropdown-menu">
                   <li>
                       <li><a href="/sales_info/'.$measurement->id .'" class="btn btn-success btn-xs more_details" style="color:white" ><i class="glyphicon glyphicon-eye-open" style="color:white"></i>More Details</a></li>
                   </ul>
               </div> ';
            })
            ->rawColumns(['name','symbol','action'])->make(true);
        }

    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportexpenses(Request $request)
    {
        //

          $from=$request->from;
          $to=$request->to;
          $account_id=$request->expenses_id;
            
          if($request->expenses_id=="all"){ 
         
            $exp= DB::table('account')
               ->join('expensive','account.id','expensive.account_id')
               ->whereBetween('expensive.expensive_date',array($request->from,$request->to))
               ->select('expensive.*','account.account_name')
               ->get();  
              $sum_amount=
              DB::table('account')
              ->join('expensive','account.id','expensive.account_id')
             ->whereBetween('expensive.expensive_date',array($request->from,$request->to))
             ->where('account.status','=','active')
             ->sum('expensive.amount');

             $pdf = PDF::loadView('expensive.report',compact('from','to','exp','sum_amount'));
               return $pdf->stream('report.pdf');
               }
  
            else{
             $exp= DB::table('account')
             ->join('expensive','account.id','expensive.account_id')
            ->where('account.id',$account_id)
            ->where('account.status','=','active')
            ->whereBetween('expensive.expensive_date',array($request->from,$request->to))
            ->select('expensive.*','account.account_name')
            ->get();  
         $sum_amount=
         DB::table('account')
             ->join('expensive','account.id','expensive.account_id')
            ->where('account.id',$account_id)
             ->whereBetween('expensive.expensive_date',array($request->from,$request->to))
      
            ->where('account.id',$account_id)
             ->sum('expensive.amount');
        $pdf = PDF::loadView('expensive.report',compact('from','to','exp','sum_amount'));
           return $pdf->stream('report.pdf');
            }
         
     
        //  $exp= DB::table('expensive')
        //         ->whereBetween('created_at',array($request->from,$request->to))
        //         ->get();
        //   $sum_amount= DB::table('expensive')->whereBetween('created_at',array($request->from,$request->to))
        //            ->sum('amount');
        //  $pdf = PDF::loadView('expensive.report',compact('from','to','exp','sum_amount'));
        //     return $pdf->stream('report.pdf');
    
      
    }


}
