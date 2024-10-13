<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
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

    public function apiPayment()
    {

        $product = DB::table('employee')
            ->join('task', 'task.empoyee_id', '=', 'employee.id')
            ->join('receive_sales', 'receive_sales.task_id', '=', 'task.id')
            ->select(
                'receive_sales.*',
                'task.amount_due',
                'task.task_number',
                'employee.employee_number',
                'employee.first_name',
                'employee.last_name'
            )
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

    public function export_pay(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
    
        // Fetch employee sales within the date range
        $salesData = DB::table('employee')
            ->join('task', 'task.empoyee_id', '=', 'employee.id')
            ->join('receive_sales', 'receive_sales.task_id', '=', 'task.id')
            ->select(
                'employee.id as employee_id',
                DB::raw('CONCAT(employee.first_name, " ", employee.last_name) as name'),
                'receive_sales.amount',
                'receive_sales.created_at'
            )
            ->whereBetween('receive_sales.created_at', [$from, $to])
            ->get();
    info('info of slaes --->>'.$salesData);
        // Structure the data
        $structuredData = $salesData->groupBy('employee_id')->map(function ($sales, $key) {
            return [
                'id' => $key,
                'name' => $sales->first()->name, // Get the employee's name
                'receive_sales' => $sales->map(function ($sale) {
                    return [
                        'amount' => $sale->amount,
                        'created_at' => $sale->created_at,
                    ];
                })->toArray(),
            ];
        })->values(); // Reset keys to 0, 1, 2, ...
    
        info('info of structured Date --->>'.$structuredData);
        $loggedInUser = Auth::User();
        // Return the structured data to the view or generate PDF as needed
        $pdf = PDF::loadView('receive.report', compact('structuredData','loggedInUser', 'from', 'to'));
        return $pdf->setPaper('A4', 'landscape')->stream('payment.pdf');
    }
    
}
