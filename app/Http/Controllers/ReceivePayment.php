<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Yajra\DataTables\DataTables;

class ReceivePayment extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('receive.index');
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
        $this->validate($request ,[
            'amount'=> 'required|string',
            'payment_methode' => 'required',
       
            
        ]);
        
        $date=Carbon::now()->format('Y-m-d');
        $task_id=$request->task_id;
        $amount_paid=$request->amount;
        $payment_form=array(
            'task_id'=>$request->task_id,
            'amount'=>$request->amount,
            'payment_methode'=>$request->payment_methode,
            'created_at'=>$request->payment_date,
        
       );
    //    return $request->account_id;
     
         DB::table('receive_sales')->insert($payment_form);

         DB::table('task')
         ->where('id', $task_id)
         ->update([
         'amount_paid' => DB::raw('amount_paid + '.$amount_paid),
         'amount_due' => DB::raw('amount_due -'. $amount_paid)
         
          ]);

         return response()->json([
            'success'    => true,
            'message'    => 'Information successfuly added'
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
