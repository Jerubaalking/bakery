<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\ExpensiveNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\MaterialCategoryModel;
use App\Models\MaterialModel;
use Auth;
use PDF;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $materials=DB::table('materials')
        ->get();

        $measurements=DB::table('measurements')
        ->get();

        $material_categories=DB::table('material_categories')
        ->get();
        return view('materials.index',compact('materials', 'measurements', 'material_categories'));
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
            
                'name' => 'required',
                'category_id' => 'required',
                'measurement_id' => 'required',
                'unit_cost' => 'required',
            
            ]);

        
                $form_data1 = array(
                    'name'=>$request->name,
                    'material_category_id'=>$request->category_id,
                    'measurement_id'=>$request->measurement_id,
                    'unit_cost'=>$request->unit_cost,
                    'created_at'=> Carbon::now(),
                    'updated_at'=> Carbon::now()
                );
            
    
            MaterialModel::create($form_data1);
            return response()->json([
                'success' => true,
                'message' => 'Material Created!',
            ]);
        }
        catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' =>json_encode($th),
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
            $data =DB::table('materials')->where('materials.id','=',$id)
            ->join('measurements','measurements.id','=','materials.measurement_id')
            ->join('material_categories','material_categories.id','=','materials.material_category_id')
            ->select('materials.*', 'measurements.measurement', 'material_categories.category_name','material_categories.type')
            ->get();
            return response()->json($data);   
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
                
                    'name' => 'required',
                    'type' => 'required',
                
                ]);
    
            
                    $form_data1 = array(
                        'name'=>$request->name,
                        'material_category_id'=>$request->category_id,
                        'measurement_id'=>$request->measurement_id,
                        'unit_cost'=>$request->unit_cost,
                        'updated_at'=> Carbon::now()
                    );
                
                    

                DB::table('materials')->where('id','=',$id)->update($form_data1);
                return response()->json([
                    'success' => true,
                    'message' => 'Material Category updated!',
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
            $exp_delete = MaterialModel::find($id);
            $exp_delete->delete();
            return response()->json([
                'success' => true,
                'message' => 'Material Category  Deleted'
            ]);
        }catch(\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => "".$th
            ]);
        }
    }
    public function apiMaterial(){
        $materialz=DB::table('materials')
        ->join('measurements','measurements.id','=','materials.measurement_id')
        ->join('material_categories','material_categories.id','=','materials.material_category_id')
        ->select('materials.*', 'measurements.measurement', 'material_categories.category_name','material_categories.type')
        ->orderBy('created_at', 'DESC')
        ->get();
        
        // info(json_encode($materialz));
        // for ($i=0; $i <sizeof($materials) ; $i++) { 
        //     # code...
        //     var_dump($materials[$i]);
        // }
        $materials = array();
        foreach ($materialz as $value) {
            // info(json_encode($value->id));
            $into_store = DB::table('into_store')
            ->where('material_id', $value->id)
            ->where('status', '=','in')
            ->selectRaw('sum(qty) as intoT')
            ->get();

            // info(json_encode($into_store));
            // $value['available'] = $into_store->inT;
            $out_store = DB::table('into_store')
            ->where('material_id', $value->id)
            ->where('status', '!=', 'in')
            ->selectRaw('sum(qty) as outT')
            ->get();
            $inTotal = 0;
            $outTotal = 0;
            foreach ($into_store as $ans) {
                $inTotal += $ans->intoT;
            }
            foreach ($out_store as $ans) {
                $outTotal +=$ans->outT;
            }
            // $c =json_encode($into_store[0]);
            // $d =json_encode($out_store[0]);
            // info($c);info($d);
            // if($c->has('outT')){
            //     $c->intoT=0;
            // }
            // if(!isset($d)){
            //     $d->outT=0;
            // }
            

            $value->available = $inTotal - $outTotal;
            array_push($materials, $value);
        }
            info($materials);
            
        //     $inTo =$into_store->inT;
        //     $outTo =$out_store->outT;
        //     $value->avaliable = $into - $outTo;
        //     array_push($materialFull, "value");
        // }
        if(Auth::user()->role=="Superadministrator"){
        return Datatables::of($materials)
            ->addColumn('action', function($materials){
                return '
                <div class="btn-group" style="width:100%">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Action <span class="caret"></span>
                   </button>
                   <ul class="dropdown-menu">
                   <li>
                   <li><a onclick="editForm('. $materials->id .')" class="btn btn-info btn-xs" style="color:white"><i class="glyphicon glyphicon-edit" style="color:white"></i> edit</a></li>
                      <li><a onclick="deleteData('. $materials->id .')" class="btn btn-danger btn-xs" style="color:white"><i class="glyphicon glyphicon-trash" style="color:white"></i> Delete</a></li>
                       <li><a href="/sales_info/'.$materials->id .'" class="btn btn-success btn-xs more_details" style="color:white" ><i class="glyphicon glyphicon-eye-open" style="color:white"></i>More Details</a></li>
                   </ul>
               </div> ';
            })
            ->rawColumns(['name','type','material_category_id','measurements','unit_cost','created_at','action'])->make(true);
        }else{
            return Datatables::of($materials)
            ->addColumn('action', function($materials){
                return '
                <div class="btn-group" style="width:100%">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Action <span class="caret"></span>
                   </button>
                   <ul class="dropdown-menu">
                   <li>
                       <li><a href="/sales_info/'.$materials->id .'" class="btn btn-success btn-xs more_details" style="color:white" ><i class="glyphicon glyphicon-eye-open" style="color:white"></i>More Details</a></li>
                   </ul>
               </div> ';
            })
            ->rawColumns(['name','type','material_category_id','measurements','unit_cost','created_at','action'])->make(true);
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
