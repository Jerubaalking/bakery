<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\ExpensiveNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\ExpensesModel;
use Auth;
use PDF;

class ExpensiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $account=DB::table('account')
        ->where('account_group','=','Income')
        ->get();
        $expenses=DB::table('account')
        ->where('account_group','=','Expenses')
        ->get();
        return view('expensive.index',compact('account','expenses'));
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
      
        $this->validate($request, [
           
            'description' => 'required',
            'amount' => 'required',
            'expensive_date'=> 'required',
         
        ]);

       
            $form_data1 = array(
                'expensive_date'=>$request->expensive_date,
                'account_id'=>$request->expenses_id,
                'description'=>$request->description,
                'amount' =>$request->amount, 
            );
           
   
        ExpensesModel::create($form_data1);
        return response()->json([
            'success' => true,
            'message' => 'Expensive Created',
        ]);
    
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
            $data =DB::table('account')->find($id);
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
         //
         $this->validate($request, [
            'account_name' => 'required',
            'account_group' => 'required',
            'account_type' => 'required', 
        ]);

            $updated_at= Carbon::now();
    
            $form_data = array(
                'account_name'=>$request->account_name,
                'account_group'=>$request->account_group,
                'account_type' =>$request->account_type,
               'updated_at'=>$updated_at,
          
            );

        DB::table('account')->where('id','=',$id)->update($form_data);

		return response()->json([
			'success' => true,
			'message' => 'Account Updated',
		]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    
            $exp_delete = ExpensesModel::find($id);
            $exp_delete->delete();
            return response()->json([
                'success' => true,
                'message' => 'Expensive  Deleted'
            ]);
    }
    public function ExpensiveApi() {
        $account = DB::table('account')
         ->join('expensive','account.id','=','expensive.account_id')
         ->select('expensive.*','account.account_name')
         ->get();
         if(Auth::user()->role=="Superadministrator"){
        return Datatables::of($account)
            ->addColumn('action', function ($account) {
                return  //'<a href="#" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-eye-open"></i> Show</a> ' .
                // '<a onclick="editForm(' . $account->id . ')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
                '<a onclick="deleteData(' . $account->id . ')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->rawColumns(['action'])->make(true);
        }else{
            return Datatables::of($account)
            ->addColumn('action', function ($account) {
                return  //'<a href="#" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-eye-open"></i> Show</a> ' .
                // '<a onclick="editForm(' . $account->id . ')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
                '<a onclick="" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->rawColumns(['action'])->make(true); 
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
