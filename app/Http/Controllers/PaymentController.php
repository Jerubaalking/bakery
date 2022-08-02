<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
class PaymentController extends Controller
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

    public function apiPayment(){

        $product=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->join('receive_sales','receive_sales.task_id','=','task.id')
        ->select('receive_sales.*','task.amount_due','task.task_number','employee.employee_number'
        ,'employee.first_name','employee.last_name')
        ->get();
       
        return Datatables::of($product)
            // ->addColumn('action', function($product){
            //     return 
            //         '<a onclick="editForm('. $product->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
            //         '<a onclick="deleteData('. $product->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            // })
            // ->rawColumns(['category_name','show_photo','action'])
            ->make(true);

    }

  public function  export_pay(Request $request){
    $from=$request->from;
    $to=$request->to;
    $export=DB::table('employee')
    ->join('task','task.empoyee_id','=','employee.id')
    ->join('receive_sales','receive_sales.task_id','=','task.id')
    ->select('receive_sales.*','task.amount_due','task.task_number','employee.employee_number','employee.first_name'
    ,'employee.last_name')
    ->whereBetween('receive_sales.created_at',array($request->from,$request->to))
    ->get();
    $sum_amount=DB::table('employee')
    ->join('task','task.empoyee_id','=','employee.id')
    ->join('receive_sales','receive_sales.task_id','=','task.id')
    ->select('receive_sales.*','task.amount_due','task.task_number','employee.employee_number')
    ->whereBetween('receive_sales.created_at',array($request->from,$request->to))
    ->sum('amount');
    $pdf = PDF::loadView('receive.report',compact('export','from','to','sum_amount'));
    return $pdf->stream('payment.pdf');

    }
}
