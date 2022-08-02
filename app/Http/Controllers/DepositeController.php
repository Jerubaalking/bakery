<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;

class DepositeController extends Controller
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
        
            return view('deposite.index',compact('account'));
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
                'amount' => 'required',
                'account_id' => 'required',
                // 'from_where' => 'required',
                'deposite_date' => 'required',
             
            ]);
                $created_at=Carbon::now()->format('Y-m-d');
                $form_data = array(
                    'amount'=>$request->amount,
                    'account_id'=>$request->account_id,
                    'deposite_date'=>$request->deposite_date,
                    'created_at'=>$created_at,
                    'memo'=>$request->memo,
                    'check_number'=>$request->check_number,
                );
           $get_balance=DB::table('account')->where('id','=',$request->account_id)->get();
           $balance=$get_balance[0]->account_balance;
           $sum_amount=$balance+$request->amount;
            $form_update=array(
                'account_balance'=>$sum_amount
            );
            $get_balance=DB::table('account')->where('id','=',$request->account_id)->update($form_update);
            DB::table('deposite')->insert($form_data);
            return response()->json([
                'success' => true,
                'message' => 'Ammont Deposte Successful',
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
              
            ]);
    
                $updated_at= Carbon::now();
        
                $form_data = array(
    
                    'account_name'=>$request->account_name,
                    'account_group'=>$request->account_group,
                     'updated_at'=>$updated_at,
                     'status'=>'active',
              
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
            // //]
            // $get_deposite=DB::table('deposite')->where('id','=',$id);
            // $account_id=$get_deposite[0]->account_id;
    
            // $get_balance=DB::table('account')->where('id','=',$account_id)
            //              ->get();
            //  $account_balance=$get_balance[0]->account_balance;
            //  $deposite_amount=$get_deposite[0]->amount;
    
            //  $normal_balance=$account_balance-$deposite_amount;
    
            //    $form_update=array(
            //      'account_balance'=>$normal_balance,
            //    );
            //   DB::table('account')->where('id', '=',   $account_id)->update($form_update);
    
              DB::table('deposite')->where('id', '=', $id)->delete();
              return response()->json([
                'success'    => true,
                 'message'    => 'Deposite Deleted'
             ]);
        }
          /**
         * Remove the specified resource from storage.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function check_account($id)
        {
            //
              if(request()->ajax()){
                $data =DB::table('account')
                ->where('id','=',$id)
                ->get();
                $check=$data[0]->account_group;
                if($check=="Income"){
                    return response()->json(['data' => $check]); 
                }
                //  
               }
        }
          /**
         * Remove the specified resource from storage.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function  check_balance($id)
        {
            //
              if(request()->ajax()){
                $data =DB::table('account')
                ->where('id','=',$id)
                ->get();
                return response()->json(['data' => $data]); 
             
                //  
               }
        }
        public function DepositeApi(){
            $pay_hist =DB::table('deposite')
            ->join('account','account.id','=','deposite.account_id')
            ->select('deposite.*','account.account_name','account.account_group')
            ->where('account.status','=','active')
            ->get();
             return Datatables::of($pay_hist)
                ->addColumn('action', function($pay_hist){
                    return '<a onclick="deleteData('.$pay_hist->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
                       
                })
                ->rawColumns(['category_name','show_photo','action'])->make(true);
    
    }
     
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportdeposite(Request $request)
    {
        //

          $from=$request->from;
          $to=$request->to;
        
         $dep= DB::table('deposite')
                ->join('account','deposite.account_id','=','account.id')
                ->whereBetween('deposite.created_at',array($request->from,$request->to))
                ->get();
          $sum_amount= DB::table('deposite')->whereBetween('created_at',array($request->from,$request->to))
                   ->sum('amount');
         $pdf = PDF::loadView('deposite.report',compact('from','to','dep','sum_amount'));
            return $pdf->download('report.pdf');
    
      
    }
     
    }
    