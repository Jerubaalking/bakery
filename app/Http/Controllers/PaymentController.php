<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use Auth;
use Carbon\Carbon;
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
        $from = Carbon::parse($request->from)->startOfDay();
        $to = Carbon::parse($request->to)->endOfDay();
    
        $salesData = DB::table('employee')
            ->join('task', 'task.empoyee_id', '=', 'employee.id')
            ->join('receive_sales', 'receive_sales.task_id', '=', 'task.id')
            ->select(
                'employee.id as employee_id',
                DB::raw('CONCAT(employee.first_name, " ", employee.last_name) as name'),
                'receive_sales.amount',
                DB::raw('DATE(receive_sales.created_at) as date') // Group by date
            )
            ->whereBetween('receive_sales.created_at', [$from, $to])
            ->get();
    
        // Structure the data
        $structuredData = $salesData->groupBy('employee_id')->map(function ($sales, $key) {
            // Group sales by date and sum the amounts
            $groupedSales = $sales->groupBy('date')->map(function ($salesOnDate) {
                return [
                    'amount' => $salesOnDate->sum('amount'), // Sum the amounts for the same date
                    'created_at' => $salesOnDate->first()->date, // Keep the date
                ];
            });
    
            return [
                'id' => $key,
                'name' => $sales->first()->name, // Get the employee's name
                'receive_sales' => $groupedSales->values()->toArray(), // Convert to array and reset keys
            ];
        })->values(); // Reset keys to 0, 1, 2, ...
    
        $loggedInUser = Auth::User();
        
        // Return the structured data to the view or generate PDF as needed
        $pdf = PDF::loadView('receive.report', compact('structuredData', 'loggedInUser', 'from', 'to'));
        return $pdf->setPaper('A4', 'landscape')->stream('payment.pdf');
    }
    
}
