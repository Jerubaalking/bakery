<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use Auth;
use Illuminate\Support\Facades\DB;
class CashFlow extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $expenses_sum=DB::table('expensive')->sum('amount');
        $capital_sum=DB::table('account')
                   ->where('account_group','=','Income')
                   ->sum('account_balance');
         $income_sum=DB::table('task')
                   ->sum('amount_paid');

        $balance=($capital_sum+$income_sum)-$expenses_sum;
        return view('cash_flow.index',compact('expenses_sum','capital_sum','income_sum','balance'));
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

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function export_flow(Request $request)
    {
            
        $from=$request->from;
        $to=$request->to;

        $capital_sum=DB::table('account')
            ->where('account_group','=','Income')
           ->sum('account_balance');

        $expense_sum=DB::table('expensive')
        ->whereBetween('created_at',array($request->from,$request->to))
        ->sum('amount');

        $income_sum=DB::table('task')
        ->whereBetween('created_at',array($request->from,$request->to))
        ->sum('amount_paid');
        $balance=($capital_sum+$income_sum)-$expense_sum;

        $pdf = PDF::loadView('cash_flow.report',compact('capital_sum','from','to','expense_sum','income_sum','balance'));
        return $pdf->download('cash_flow.pdf');
    }
}
