<?php

namespace App\Http\Controllers;

use App\Exports\ExportSuppliers;
use App\Imports\SuppliersImport;
use App\Supplier;
use Excel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use PDF;
use Auth;
use Yajra\DataTables\DataTables;
use App\Models\SalesModel;


class TaskController extends Controller
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
        $empo = DB::table('employee')
            ->get();

        $product = DB::table('products')
            //->whereNotIn('stock',[0])
            ->get();

        $account = DB::table('account')
            ->where('account_group', '=', 'shops')
            ->get();
        $employees = DB::table('employee')->get();
        $close_task = DB::table('employee')
            ->join('task', 'task.empoyee_id', '=', 'employee.id')
            ->select('task.*', 'employee.first_name', 'employee.employee_number')
            ->where('amount_due', '=', '0')
            ->get();

        return view('task.index', compact('empo', 'product', 'employees', 'account', 'close_task'));
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
        // Validation
        $this->validate($request, [
            "price.*" => 'required|integer|min:1',
        ]);

        $incomingTask = $request->all();

        // Extracting input values
        $date = Carbon::now()->format('Y-m-d');
        $item_name = $request->item_name;
        $date_in = $request->date_in;
        $qty = $request->qty;
        $price = $request->price;
        $retail_price = $request->retail_price;
        $retail = 0;
        $subtotal = $request->sub_total;
        $account_id = $request->supplier_id;
        $product_id = $request->product_id;
        $return_qty = 0;
        $return_price = 0;
        $return_amt = 0;

        // Fetch supplier and employee details
        $supplier = DB::table('account')
        ->where('id', '=', $request->supplier_id)
        ->join('employee', 'employee.account_id', 'account.id')
        ->select('account.*', 'employee.employee_number as employee_number', 'employee.id as employee_id')
        ->first();

        // info(json_encode($employee));
        // Check for existing task
        $recordDate = DB::table('task')
            ->where('empoyee_id', '=', $supplier->employee_id)
            ->where('account_id', '=', $account_id)
            ->where('created_at', '=', $date_in)
            ->orderBy('id', 'DESC')
            ->first();

        // Start database transaction for data consistency
        DB::beginTransaction();
        try {
            // Task creation or updating logic
            if (!$recordDate) {
                // New task: Generate the next task number
                $lastTask = DB::table('task')->orderBy('id', 'DESC')->first();

                // Ensure the task number is properly formatted and exists
                if ($lastTask && isset($lastTask->task_number) && strpos($lastTask->task_number, '-') !== false) {
                    // Split the task number and increment the numeric part
                    $parts = explode('-', $lastTask->task_number);
                    $numericPart = intval($parts[1]); // Ensure this is an integer
                    $nextTask = 'TASK-' . sprintf("%04d", $numericPart + 1);
                } else {
                    // No valid previous task, or the task number format is incorrect
                    $nextTask = 'TASK-0001';
                }

                // Create the new task with the generated task number
                $form_data = [
                    'sub_total' => round($subtotal, 2),
                    'empoyee_id' => $employee->id, // Assuming you meant 'employee_id' (correct typo from 'empoyee_id')
                    'account_id' => $supplier->id,
                    'created_at' => $date,
                    'amount_paid' => 0,
                    'amount_due' => $subtotal,
                    'task_number' => $nextTask,
                    'returned' => $return_amt
                ];

                // Insert the task and get its ID
                $get_id = DB::table('task')->insertGetId($form_data);
            } else {
                // Existing task: Update task data
                $updated_data = [
                    'sub_total' => round($recordDate->sub_total + $subtotal, 2),
                    'amount_paid' => $recordDate->amount_paid,
                    'amount_due' => $recordDate->amount_due + $subtotal,
                    'returned' => $recordDate->returned + $return_amt,
                ];

                DB::table('task')
                    ->where('id', $recordDate->id)
                    ->update($updated_data);

                $get_id = $recordDate->id;
            }
            info('get_id ===>' . $return_qty);
            $currentSales = DB::table('sales')->where('sales.task_id', '=', $recordDate);
            // Inserting sales entries
            if ($qty) {
                $sales_data = [];

                for ($i = 0; $i < sizeof($qty); $i++) {
                    $sales_data[] = [
                        'task_id' => $get_id,
                        'product_id' => $product_id[$i],
                        'qty'  => $qty[$i],
                        'price'  => round($price[$i], 2),
                        'retail_price'  => round($retail_price[$i], 2),
                        'bulk' => $qty[$i],
                        'retail' => 0,
                        'amt' => round($price[$i] * $qty[$i], 2),
                        'retail_amt' => 0,
                        'return_qty'  => 0,
                        'return_price'  => 0,
                        'return_amt'  => 0,
                        'created_at'  => $date_in,
                    ];

                    // Optional: Update product stock after each sale
                    // DB::table('products')->where('id', $product_id[$i])->decrement('stock', $qty[$i]);
                }

                // Batch insert sales data
                DB::table('sales')->insert($sales_data);
            }

            // Commit the transaction after everything is successful
            DB::commit();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Information successfully added'
            ]);
        } catch (\Exception $e) {
            // Rollback transaction if something goes wrong
            DB::rollBack();

            // Log the error
            \Log::error($e->getMessage());

            // Return failure response
            return response()->json([
                'success' => false,
                'message' => 'Information not added due to an error'
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
        if (request()->ajax()) {
            $data = DB::table('employee')
                ->join('task', 'task.empoyee_id', '=', 'employee.id')
                ->join('sales', 'task.id', '=', 'sales.task_id')
                ->join('products', 'sales.product_id', '=', 'products.id')
                ->select(
                    'task.*',
                    'employee.first_name',
                    'employee.employee_number',
                    'sales.qty',
                    'sales.amt',
                    'products.product_name',
                    'sales.price',
                    'products.stock',
                    'sales.product_id'
                )
                ->where('task.id', '=', $id)
                ->get();
            if ($data) {
                return response()->json(['data' => $data]);
            }
            return view('casual.staffing.index', comapact('data'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        //
        $date = Carbon::now()->format('Y-m-d');
        $item_name = $request->item_name;
        $date_in = $request->date_in;
        $qty = $request->qty;
        $price = $request->price;
        $id = $request->id;
        $amt = $request->amt;
        $subtotal = $request->sub_total;
        $supplier_id = $request->supplier_id;
        $product_id = $request->product_id;

        //  $prove="Not approved";

        $form_datas = array(
            'sub_total' => round($subtotal, 2),
            'empoyee_id' => $supplier_id,
            'created_at' => $date,
            'amount_paid' => '0',
            'amount_due' => '0'

        );

        DB::table('task')
            ->where('id', '=', $id)->update($form_datas);

        $x = DB::table('sales')
            ->where('task_id', '=', $id)
            ->select('product_id', 'qty')
            ->get()
            ->toArray();

        foreach ($x as $x) {
            $ids[] = $x->product_id;
            $qtys[] = $x->qty;
            for ($count = 0; $count < count($ids); $count++) {
                $xx = DB::table('products')->whereIn('id', [$ids[$count]])
                    ->increment('stock', $qtys[$count]);
            }
        }

        for ($count = 0; $count < count($qty); $count++) {
            $form_data[] = array(
                'task_id' => $id,
                'product_id' => $product_id[$count],
                'qty'  => $qty[$count],
                'price'  => round($price[$count], 2),
                'amt' => round($price[$count] * $qty[$count], 2),
                'created_at'  => $date_in,

            );
            DB::table('products')->where('id', $product_id[$count])->decrement('stock', $qty[$count]);
        }


        DB::table('sales')->where('task_id', '=', $id)->delete();

        DB::table('sales')->insert($form_data);
        return response()->json([
            'success'    => true,
            'message'    => 'Information Updated'
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
        //


        $x = DB::table('sales')
            ->where('task_id', '=', $id)
            ->select('product_id', 'qty')
            ->get()
            ->toArray();
        foreach ($x as $x) {
            $ids[] = $x->product_id;
            $qtys[] = $x->qty;
        }

        $task =  DB::table('task')
            ->where('id', '=', $id)->delete();
        if ($task) {
            for ($count = 0; $count < count($ids); $count++) {
                $xx = DB::table('products')->whereIn('id', [$ids[$count]])
                    ->increment('stock', $qtys[$count]);
            }
        }
    }
    public function apiTask($start, $end, $empId)
    {
        if (request()->ajax()) {

            info($empId);
            info($end);
            $task = null;
            if ($empId == 'all') {
                $task = DB::table('task')
                    ->whereDate('task.created_at', '>=', $start)
                    ->whereDate('task.created_at', '<=', $end)
                    ->join('employee', 'employee.id', '=', 'task.empoyee_id')
                    ->join('sales', 'task.id', '=', 'sales.task_id')
                    ->where('task.amount_due', '>', 0)
                    ->select(
                        'task.id',
                        'task.empoyee_id',
                        'task.account_id',
                        'task.returned',
                        'task.demage_cost',
                        'task.created_at',
                        'task.task_number',
                        'task.amount_paid',
                        'task.amount_due',
                        'task.sub_total',
                        DB::raw('SUM(sales.bulk*sales.price + sales.retail*sales.retail_price) as expected_amount'),
                        'employee.first_name',
                        'employee.last_name'
                    )
                    ->groupBy(
                        'task.id',
                        'task.empoyee_id',
                        'task.account_id',
                        'task.returned',
                        'task.demage_cost',
                        'task.created_at',
                        'task.task_number',
                        'task.amount_paid',
                        'task.amount_due',
                        'task.sub_total',
                        'employee.id',
                        'employee.first_name',
                        'employee.last_name'
                    ) // To aggregate results properly
                    // ->havingRaw('task.amount_paid < SUM(sales.bulk * sales.price + sales.retail * sales.retail_price)')
                    ->orderBy('task.created_at', 'DESC')
                    ->get();
            } else {
                $task = DB::table('task')
                    ->whereDate('task.created_at', '>=', $start)
                    ->whereDate('task.created_at', '<=', $end)
                    ->where('empoyee_id', '=', $empId)
                    ->join('employee', 'employee.id', '=', 'task.empoyee_id')
                    ->join('sales', 'task.id', '=', 'sales.task_id')
                    ->where('task.amount_due', '>', 0)
                    ->select(
                        'task.id',
                        'task.empoyee_id',
                        'task.account_id',
                        'task.returned',
                        'task.demage_cost',
                        'task.created_at',
                        'task.task_number',
                        'task.amount_paid',
                        'task.amount_due',
                        'task.sub_total',
                        DB::raw('SUM(sales.bulk*sales.price + sales.retail*sales.retail_price) as expected_amount'),
                        'employee.first_name',
                        'employee.last_name'
                    )
                    ->groupBy(
                        'task.id',
                        'task.empoyee_id',
                        'task.account_id',
                        'task.returned',
                        'task.demage_cost',
                        'task.created_at',
                        'task.task_number',
                        'task.amount_paid',
                        'task.amount_due',
                        'task.sub_total',
                        'employee.id',
                        'employee.first_name',
                        'employee.last_name'
                    ) // To aggregate results properly
                    // ->havingRaw('task.amount_paid < SUM(sales.bulk * sales.price + sales.retail * sales.retail_price)')
                    ->orderBy('task.created_at', 'DESC')
                    ->get();
            }

            // You have to create a link option to view account
            if (Auth::user()->role == "Superadministrator") {
                return Datatables::of($task)
                    ->addColumn('action', function ($task) {
                        return '
                    <div class="dropdown" style="width:100%">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-cog"></i> Actions <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#" class="pays" id="' . $task->empoyee_id . '" test="' . $task->id . '">
                                <i class="fa fa-money"></i> Receive Payment
                            </a></li>
                            <li><a href="#" id="' . $task->id . '" class="demageForm">
                                <i class="glyphicon glyphicon-plus"></i> Add Damaged
                            </a></li>
                            <li><a href="javascript:void(0)" onclick="deleteData(' . $task->id . ')" id="' . $task->id . '">
                                <i class="glyphicon glyphicon-trash"></i> Delete
                            </a></li>
                            <li><a href="task_info/' . $task->id . '" class="more_details">
                                <i class="glyphicon glyphicon-eye-open"></i> More Details
                            </a></li>
                        </ul>
                    </div>';
                    })
                    ->editColumn('created_at', function ($task) {
                        return '<div class="text-warning">' . $task->created_at . '</div>';
                    })
                    ->editColumn('amount_due', function ($task) {
                        return '<div class="text-danger">' . number_format((intval($task->sub_total) - intval($task->demage_cost)) - intval($task->amount_paid), 2) . '</div>';
                    })
                    ->editColumn('amount_paid', function ($task) {
                        return '<div class="text-success">' . number_format($task->amount_paid, 2) . '</div>';
                    })
                    // ->editColumn('returned', function ($task) {
                    //     return '<div class="text-primary">' . number_format($task->returned, 2) . '</div>';
                    // })
                    ->editColumn('sub_total', function ($task) {
                        return '<div class="text-primary">' . number_format($task->sub_total, 2) . '</div>';
                    })
                    ->escapeColumns([])
                    ->rawColumns(['action'])
                    ->make(true);
            } else {
                return Datatables::of($task)
                    ->addColumn('action', function ($task) {
                        return '
                       <div class="btn-group" style="width: 100%;">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#" class="btn btn-warning btn-xs pays" data-task-id="{{ task.id }}" data-employee-id="{{ task.employee_id }}">
                                    <i class="fa fa-money"></i> Receive Payment
                                </a>
                            </li>
                            <li>
                                <a href="#" class="btn btn-warning btn-xs damageForm" data-task-id="{{ task.id }}">
                                    <i class="glyphicon glyphicon-plus"></i> download form
                                </a>
                            </li>
                            <li>
                                <a href="task_info/{{ task.id }}" class="btn btn-success btn-xs more_details">
                                    <i class="glyphicon glyphicon-eye-open"></i> More Details
                                </a>
                            </li>
                        </ul>
                        </div>';
                    })
                    ->editColumn('created_at', function ($task) {
                        return '<div class="text-warning">' . number_format($task->created_at, 2) . '</div>';
                    })
                    ->editColumn('amount_due', function ($task) {
                        return '<div class="text-danger">' . number_format((intval($task->sub_total) - intval($task->demage_cost)) - intval($task->amount_paid), 2) . '</div>';
                    })
                    ->editColumn('amount_paid', function ($task) {
                        return '<div class="text-success">' . number_format($task->amount_paid, 2) . '</div>';
                    })
                    // ->editColumn('returned', function ($task) {
                    //     return '<div class="text-primary">' . number_format($task->returned, 2) . '</div>';
                    // })
                    ->editColumn('sub_total', function ($task) {
                        return '<div class="text-primary">' . number_format($task->sub_total, 2) . '</div>';
                    })
                    ->escapeColumns([])
                    ->rawColumns(['action'])
                    ->make(true);
            }
        }
    }
    public function account(Request $request, $id, $start, $end)
    {
        info($id);
        $tasks = DB::table('employee')
            ->where('empoyee_id', '=', $id)
            ->join('task', 'employee.id', '=', 'task.empoyee_id')
            ->whereDate('task.created_at', '>=', $start)
            ->whereDate('task.created_at', '<=', $end)
            ->get();
        return json_encode($tasks);
        // return view('task.details',compact('tasks'));
    }
    public function apiAccountsTask(Request $request, $start, $end, $empId)
    {
        $tasks = null;
        $dates = null;
        $employees = null;
        if ($empId == 'all') {
            $tasks = DB::table('task')
                ->whereDate('task.created_at', '>=', $start)
                ->whereDate('task.created_at', '<=', $end)
                ->join('employee', 'employee.id', '=', 'task.empoyee_id')
                ->select('task.*', 'employee.first_name', 'employee.last_name')
                ->get();

            $dates = DB::table('task')
                ->groupBy('created_at')
                ->whereNotIn('amount_due', ['0'])
                ->select('created_at')
                ->get();

            $employees = DB::table('employee')
                ->get();
        } else {
            $tasks = DB::table('task')
                ->whereDate('task.created_at', '>=', $start)
                ->whereDate('task.created_at', '<=', $end)
                ->join('employee', 'employee.id', '=', 'task.empoyee_id')
                ->select('task.*', 'employee.first_name', 'employee.last_name')
                ->where('employee.id', '=', $empId)
                ->get();

            $dates = DB::table('task')
                ->where('task.empoyee_id', '=', $empId)
                ->groupBy('created_at')
                ->whereNotIn('amount_due', ['0'])
                ->select('created_at')
                ->get();

            $employees = DB::table('employee')
                ->where('id', '=', $empId)
                ->get();
        }
        $task = array();;
        // info($employees);
        // info($dates);
        foreach ($employees as $employeekey => $employee) {

            $ttask = array();
            foreach ($tasks as $taskKey => $task1) {
                if ($task1->empoyee_id == $employee->id) {
                    if (sizeof($ttask) <= 0) {
                        $task1->first_name = $task1->first_name . ' ' . $task1->last_name;
                        array_push($ttask, $task1);
                    } else {
                        // info($ttask);
                        if ($ttask[0]->demage_cost == NULL) {
                            $ttask[0]->id = $task1->id;
                            $ttask[0]->first_name = $task1->first_name . ' ' . $task1->last_name;
                            $ttask[0]->returned = intVal($task1->returned);
                            $ttask[0]->demage_cost = intVal($task1->demage_cost);
                            $ttask[0]->amount_due += intVal($task1->amount_due);
                            $ttask[0]->amount_paid += intVal($task1->amount_paid);
                            $ttask[0]->sub_total += intVal($task1->sub_total);
                            $ttask[0]->created_at = $task1->created_at;
                        } else {
                            $ttask[0]->id = $task1->id;
                            $ttask[0]->created_at = $task1->created_at;
                            $ttask[0]->first_name = $task1->first_name . ' ' . $task1->last_name;
                            $ttask[0]->returned = intVal($task1->returned);
                            $ttask[0]->demage_cost += intVal($task1->demage_cost);
                            $ttask[0]->amount_due += intVal($task1->amount_due);
                            $ttask[0]->amount_paid += intVal($task1->amount_paid);
                            $ttask[0]->sub_total += intVal($task1->sub_total);
                            $ttask[0]->created_at = $task1->created_at;
                        }
                    }
                }
            }
            foreach ($ttask as $tt) {
                $payments = DB::table('receive_sales')
                    ->where('receive_sales.employee_id', $tt->empoyee_id)
                    ->select('receive_sales.id', 'receive_sales.task_id', 'receive_sales.created_at', 'receive_sales.amount')
                    ->get();

                // dump($tt->empoyee_id);
                // foreach ($payments as $v) {
                //     # code...
                //     // dump($v);
                //     if($v->task_id == $tt->id){
                //      break;   
                //     }else{
                //         $tt->amount_paid += $v->amount;
                //     }

                // }



                if ($tt->amount_paid > $tt->sub_total) {
                    $tt->amount_due = 0;
                } else {
                    $tt->amount_due = intVal($tt->sub_total - ($tt->amount_paid + $tt->demage_cost + $tt->returned));
                }
                if ($tt->amount_due < 0) {
                    $tt->amount_paid += $tt->amount_due;
                    $tt->amount_due = 0;
                }
                array_push($task, $tt);
            }
            // info($task);

        }
        // You have to create a link option to view account
        // info($task);
        if (Auth::user()->role == "Superadministrator") {
            return Datatables::of($task)
                ->addColumn('action', function ($task) {
                    return '
                <div class="btn-group" style="width:100%">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Action <span class="caret"></span>
                   </button>
                   <ul class="dropdown-menu">
                   <!--<li><a href="#" class="btn btn-warning btn-xs pays" style="color:white" task="' . $task->id . '" id="' . $task->empoyee_id . '" ><i class="fa fa-money" style="color:white"></i>Receive Payment</a></li>-->
                       <li><a href="task_info/' . $task->id . '" class="btn btn-success btn-xs more_details" style="color:white" ><i class="glyphicon glyphicon-eye-open" style="color:white"></i>More</a></li>
                       <li><a href="#" class="btn btn-warning btn-xs block" style="color:white" id="' . $task->empoyee_id . '"><i class="fa fa-money" style="color:white"></i>Block Account</a></li>
                   </ul>
               </div> ';
                })
                ->editColumn('demage_cost', function ($task) {

                    return '<div class="text-warning">' . number_format($task->demage_cost, 2) . '</div>';
                })
                ->editColumn('amount_due', function ($task) {

                    return '<div class="text-danger">' . number_format($task->amount_due, 2) . '</div>';
                })
                ->editColumn('amount_paid', function ($task) {

                    return '<div class="text-success">' . number_format($task->amount_paid, 2) . '</div>';
                })
                ->editColumn('returned', function ($task) {

                    return '<div class="text-primary">' . number_format($task->returned, 2) . '</div>';
                })
                ->editColumn('sub_total', function ($task) {

                    return '<div class="text-primary">' . number_format($task->sub_total, 2) . '</div>';
                })
                ->escapeColumns([])
                ->rawColumns(['action'])->make(true);
        } else {
            return Datatables::of($task)
                ->addColumn('action', function ($task) {
                    return '
                <div class="btn-group" style="width:100%">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Action <span class="caret"></span>
                   </button>
                   <ul class="dropdown-menu">
                   <li><a href="#" class="btn btn-warning btn-xs pays" style="color:white" task="' . $task->id . '"  id="' . $task->empoyee_id . '"><i class="fa fa-money" style="color:white"></i>Receive Payment</a></li>

                       <li><a href="task_info/' . $task->id . '" class="btn btn-success btn-xs more_details" style="color:white" ><i class="glyphicon glyphicon-eye-open" style="color:white"></i>More Details</a></li>
                   </ul>
               </div> ';
                })
                ->editColumn('demage_cost', function ($task) {

                    return '<div class="text-warning">' . number_format($task->demage_cost, 2) . '</div>';
                })
                ->editColumn('amount_due', function ($task) {

                    return '<div class="text-danger">' . number_format($task->amount_due, 2) . '</div>';
                })
                ->editColumn('amount_paid', function ($task) {

                    return '<div class="text-success">' . number_format($task->amount_paid, 2) . '</div>';
                })
                ->editColumn('returned', function ($task) {

                    return '<div class="text-primary">' . number_format($task->returned, 2) . '</div>';
                })
                ->editColumn('sub_total', function ($task) {

                    return '<div class="text-primary">' . number_format($task->sub_total, 2) . '</div>';
                })
                ->escapeColumns([])
                ->rawColumns(['action'])->make(true);
        }
    }
    public function apiClosedTask($start, $end, $empId)
    {
        $task;
        if ($empId == 'all') {
            $task = DB::table('task')
                ->join('employee', 'task.empoyee_id', '=', 'employee.id')
                ->whereDate('task.created_at', '>=', $start)
                ->whereDate('task.created_at', '<=', $end)
                ->select('task.*', 'employee.first_name', 'employee.employee_number')
                // ->where('task.amount_paid','=','task.sub_total')
                ->where('amount_due', '=', '0')
                ->get();
        } else {
            $task = DB::table('task')
                ->join('employee', 'task.empoyee_id', '=', 'employee.id')
                ->whereDate('task.created_at', '>=', $start)
                ->whereDate('task.created_at', '<=', $end)
                ->where('employee.id', '=', $empId)
                ->select('task.*', 'employee.first_name', 'employee.employee_number')
                // ->where('task.amount_paid','=','task.sub_total')
                ->where('amount_due', '=', '0')
                ->get();
        }


        return Datatables::of($task)
            ->addColumn('action', function ($task) {
                return '
                <div class="btn-group" style="width:100%">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Action <span class="caret"></span>
                   </button>
                   <ul class="dropdown-menu">
                   <li>
                       <li><a href="task_info/' . $task->id . '" class="btn btn-success btn-xs more_details" style="color:white" ><i class="glyphicon glyphicon-eye-open" style="color:white"></i>More Details</a></li>
                   </ul>
               </div> ';
            })
            ->editColumn('demage_cost', function ($task) {

                return '<div class="text-warning">' . number_format($task->demage_cost, 2) . '</div>';
            })
            ->editColumn('amount_due', function ($task) {

                return '<div class="text-danger">' . number_format($task->amount_due, 2) . '</div>';
            })
            ->editColumn('amount_paid', function ($task) {

                return '<div class="text-success">' . number_format($task->amount_paid, 2) . '</div>';
            })
            ->editColumn('returned', function ($task) {

                return '<div class="text-primary">' . number_format($task->returned, 2) . '</div>';
            })
            ->editColumn('sub_total', function ($task) {

                return '<div class="text-primary">' . number_format($task->sub_total, 2) . '</div>';
            })
            ->escapeColumns([])
            ->rawColumns(['action'])->make(true);
    }
    public function apiDamagedTask($start, $end, $empId)
    {

        $task;
        if ($empId == 'all') {
            $task = DB::table('task')
                ->join('employee', 'task.empoyee_id', '=', 'employee.id')
                ->whereDate('task.created_at', '>=', $start)
                ->whereDate('task.created_at', '<=', $end)
                ->select('task.*', 'employee.first_name', 'employee.employee_number')
                ->whereNotIn('demage_cost', ['0'])
                ->get();
        } else {
            $task = DB::table('task')
                ->join('employee', 'task.empoyee_id', '=', 'employee.id')
                ->whereDate('task.created_at', '>=', $start)
                ->whereDate('task.created_at', '<=', $end)
                ->where('employee.id', '=', $empId)
                ->select('task.*', 'employee.first_name', 'employee.employee_number')
                ->whereNotIn('demage_cost', ['0'])
                ->get();
        }

        info($task);

        return Datatables::of($task)
            ->addColumn('action', function ($task) {
                return '
                <div class="btn-group" style="width:100%">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Action <span class="caret"></span>
                   </button>
                   <ul class="dropdown-menu">
                   <li>

                       <li><a href="task_info/' . $task->id . '" class="btn btn-success btn-xs more_details" style="color:white" ><i class="glyphicon glyphicon-eye-open" style="color:white"></i>More Details</a></li>
                   </ul>
               </div> ';
            })
            ->editColumn('demage_cost', function ($task) {

                return '<div class="text-warning">' . number_format($task->demage_cost, 2) . '</div>';
            })
            ->editColumn('amount_due', function ($task) {

                return '<div class="text-danger">' . number_format($task->amount_due, 2) . '</div>';
            })
            ->editColumn('amount_paid', function ($task) {

                return '<div class="text-success">' . number_format($task->amount_paid, 2) . '</div>';
            })
            ->editColumn('returned', function ($task) {

                return '<div class="text-primary">' . number_format($task->returned, 2) . '</div>';
            })
            ->editColumn('sub_total', function ($task) {

                return '<div class="text-primary">' . number_format(intVal($task->sub_total), 2) . '</div>';
            })
            ->escapeColumns([])
            ->rawColumns(['action'])->make(true);
    }

    //                       <li><a href="#" class="btn btn-info btn-xs view" style="color:white" id="'.$task->id.'" ><i class="glyphicon glyphicon-eye-open" style="color:white"></i> Show</a></li>
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function stockReturn($id)
    {
        //
        // $datas = DB::table('sales')
        // ->where('task_id', '=', $id)
        // ->get();

        if (request()->ajax()) {
            $datas = DB::table('employee')
                ->join('task', 'task.empoyee_id', '=', 'employee.id')
                ->join('sales', 'task.id', '=', 'sales.task_id')
                ->join('products', 'sales.product_id', '=', 'products.id')
                ->select(
                    'sales.*',
                    'employee.first_name',
                    'employee.employee_number',
                    'employee.phone',
                    'employee.last_name',
                    'sales.qty',
                    'task.empoyee_id',
                    'sales.amt',
                    'products.product_name',
                    'sales.price',
                    'products.stock',
                    'sales.product_id',
                    'task.amount_paid'
                )
                ->where('task.id', '=', $id)
                ->get();
            $data = array();
            // $dataz = array($datas);
            foreach ($datas as $key => $value) {
                # code...
                $damages = DB::table('product_demage')
                    ->where('task_id', $value->task_id)
                    ->get();
                $value->damage_qty = 0;
                foreach ($damages as $key1 => $value1) {
                    # code...
                    if ($value1->sales_id == $value->id) {
                        $value->damage_qty += intVal($value1->qty);
                    }
                }
                $prevQty = ($value->amount_paid / $value->price);
                if ($prevQty >= $value->qty) {
                    $value->qty -= 0;
                } else {

                    $value->qty -= ($value->amount_paid / $value->price);
                }
                array_push($data, $value);

                // dump($value);
            }
            // ->join('task','task.empoyee_id','=','employee.id')
            // ->join('sales','task.id','=','sales.task_id')
            // ->join('product_demage','task.id','=','product_demage.task_id')
            // ->join('products','sales.product_id','=','products.id')
            // ->select('product_demage.*','employee.first_name','employee.employee_number','employee.phone','employee.last_name','task.empoyee_id',
            // 'products.product_name', 'sales.id as sales_id','task.created_at')
            // ->where('task.id','=',$id)
            info($data);
            return response()->json(['data' => $data]);

            // return view('casual.staffing.index', compact('data'));
            // }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function amount_due($id)
    {
        if (request()->ajax()) {
            try {
                // Fetch the task data
                $data = DB::table('task')
                    ->join('employee', 'employee.id', '=', 'task.empoyee_id')
                    ->join('account', 'account.id', '=', 'task.account_id')
                    ->join('sales', 'sales.task_id', '=', 'task.id')
                    ->where('task.empoyee_id', '=', $id)
                    ->where('task.amount_due', '>', '0')
                    ->selectRaw('
                    task.id, 
                    task.account_id,
                    account.account_name,
                    employee.employee_number,
                    CONCAT(employee.first_name, " ", employee.last_name) as employee_name,
                    SUM(sales.amt+sales.retail_amt) as sub_total,
                    SUM(task.amount_paid) as amount_paid,
                    SUM(task.returned) as returned,
                    SUM(task.demage_cost) as demage_cost
                ')
                    ->groupBy('task.id', 'task.empoyee_id', 'task.account_id', 'task.sub_total', 'account.account_name', 'employee.employee_number', 'employee.first_name', 'employee.last_name')
                    ->first();
                // $data = DB::table('task')
                // ->where('task.empoyee_id', '=', $id)
                // ->get();
                // Check if task data exists
                if (!$data) {
                    return response()->json(['error' => 'No data found'], 404);
                }

                $paid = DB::table('receive_sales')
                    ->where('receive_sales.task_id', '=', $data->id)
                    ->selectRaw('
                        SUM(receive_sales.amount) as total_paid_amount,
                        MAX(receive_sales.updated_at) as latest_payment_date
                    ')
                    ->first(); // To fetch a single result

                $data->paid_amount = $paid->total_paid_amount;
                $data->last_paid = $paid->latest_payment_date;
                info('task id ==>' . json_encode($data));
                // Calculate the amount due
                $subTotal = intval($data->sub_total);
                $amountPaid = intval($data->paid_amount);
                $demageCost = intval($data->demage_cost);
                $returned = intval($data->returned);

                $data->amount_due = $subTotal - ($amountPaid + $demageCost + $returned);

                // Fetch the associated sales data
                info('this is data: ');
                $salesData = DB::table('sales') // Or 'sale' if the table is named singular
                    ->where('sales.task_id', '=', $data->id)  // Ensure $id refers to a valid task_id
                    ->join('products', 'sales.product_id', '=', 'products.id')  // Join products on product_id
                    ->join('task', 'sales.task_id', '=', 'task.id')  // Join tasks on task_id
                    ->select(
                        'sales.id',  // Fetch all columns from sales
                        'sales.price',
                        'sales.retail_price',
                        'sales.product_id',
                        'sales.qty',  // Fetch all columns from sales
                        'sales.retail',  // Fetch all columns from sales
                        'sales.bulk',  // Fetch all columns from sales
                        'sales.amt',  // Fetch all columns from sales
                        'sales.retail_amt',  // Fetch all columns from sales
                        'sales.created_at',  // Fetch all columns from sales
                        'task.task_number',  // Fetch the task number
                        'task.empoyee_id',  // Fetch the employee id
                        'products.product_name',  // Fetch product name
                        'task.amount_paid'  // Fetch the amount paid from task
                    )
                    ->get();


                // Add the sales data to the result
                $data->sales = $salesData->toArray();  // Sales data as an array

                // Return the result as JSON
                return response()->json(['success' => true, 'data' => $data]);
            } catch (\Exception $e) {
                \Log::error('Error fetching task and sales data:', ['error' => $e->getMessage()]);
                return response()->json(['error' => 'An unexpected error occurred.'], 500);
            }
        }
    }




    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function task_info($id)
    {
        // Fetching task-related data
        $taskData = $this->getTaskData($id);
        $pay = $this->getPayData($id);
        $returnTask = $this->getReturnTaskData($id);
        $damageProducts = $this->getDamageProductData($id);
        $employeeInfo = $this->getEmployeeInfo($id);

        // Extracting employee-related details
        $empId = $employeeInfo->id;
        $empNumber = $employeeInfo->employee_number;
        $created = $employeeInfo->created_at;

        // Fetching previous and latest tasks
        $previousTasks = $this->getPreviousTasks($empId, $created);
        $latestTasks = $this->getLatestTasks($empId, $created);

        return view('task.task_info', compact('taskData', 'previousTasks', 'pay', 'employeeInfo', 'returnTask', 'latestTasks', 'damageProducts', 'id'));
    }

    private function getTaskData($id)
    {
        return DB::table('employee')
            ->join('task', 'task.empoyee_id', '=', 'employee.id')
            ->join('sales', 'task.id', '=', 'sales.task_id')
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->select(
                'task.*',
                'employee.first_name',
                'employee.employee_number',
                'sales.return_qty',
                'sales.return_price',
                'sales.return_amt',
                'products.product_name',
                'sales.price',
                'products.stock',
                'employee.last_name'
            )
            ->where('task.id', '=', $id)
            ->get();
    }

    private function getPayData($id)
    {
        return DB::table('employee')
            ->join('task', 'task.empoyee_id', '=', 'employee.id')
            ->join('receive_sales', 'receive_sales.task_id', '=', 'task.id')
            ->select(
                'receive_sales.*',
                'employee.first_name',
                'employee.employee_number',
                'task.task_number'
            )
            ->where('task.id', '=', $id)
            ->get();
    }

    private function getReturnTaskData($id)
    {
        return DB::table('employee')
            ->join('task', 'task.empoyee_id', '=', 'employee.id')
            ->join('product_demage', 'task.id', '=', 'product_demage.task_id')
            ->join('products', 'products.id', '=', 'product_demage.product_id')
            ->select(
                'product_demage.*',
                'employee.first_name',
                'employee.employee_number',
                'products.product_name',
                'products.stock',
                'task.task_number'
            )
            ->where('product_demage.task_id', '=', $id)
            ->get();
    }

    private function getDamageProductData($id)
    {
        return DB::table('employee')
            ->join('task', 'task.empoyee_id', '=', 'employee.id')
            ->join('sales', 'sales.task_id', '=', 'task.id')
            ->join('products', 'products.id', '=', 'sales.product_id')
            ->join('product_demage', 'products.id', '=', 'product_demage.product_id')
            ->select(
                'product_demage.*',
                'employee.first_name',
                'employee.employee_number',
                'products.product_name',
                'products.stock',
                'task.task_number'
            )
            ->where('product_demage.task_id', '=', $id)
            ->get();
    }

    private function getEmployeeInfo($id)
    {
        return DB::table('employee')
            ->join('task', 'task.empoyee_id', '=', 'employee.id')
            ->where('task.id', '=', $id)
            ->select('employee.id', 'task.created_at', 'employee.employee_number')
            ->first();
    }

    private function getPreviousTasks($empId, $created)
    {
        return DB::table('employee')
            ->where('employee.id', '=', $empId)
            ->join('task', 'task.empoyee_id', '=', 'employee.id')
            ->whereDate('task.created_at', '<=', $created)
            ->orderBy('task.created_at', 'DESC')
            ->get();
    }

    private function getLatestTasks($empId, $created)
    {
        return DB::table('employee')
            ->join('task', 'task.empoyee_id', '=', 'employee.id')
            ->where('employee.id', '=', $empId)
            ->whereDate('task.created_at', '>=', $created)
            ->orderBy('task.created_at', 'DESC')
            ->get();
    }


    public function account_info($id)
    {
        $data = DB::table('employee')
            ->join('task', 'task.empoyee_id', '=', 'employee.id')
            ->join('sales', 'task.id', '=', 'sales.task_id')
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->select(
                'task.*',
                'employee.first_name',
                'employee.employee_number',
                'sales.qty',
                'sales.amt',
                'products.product_name',
                'sales.price',
                'products.stock',
                'employee.last_name',
                'sales.product_id',
                'return_qty',
                'return_price',
                'return_amt'
            )
            ->where('employee.id', '=', $id)
            ->get();
        $pay = DB::table('employee')
            ->join('task', 'task.empoyee_id', '=', 'employee.id')
            ->join('receive_sales', 'receive_sales.task_id', '=', 'task.id')
            ->join('sales', 'sales.task_id', '=', 'task.id')
            ->select(
                'receive_sales.*',
                'employee.first_name',
                'employee.employee_number',
                'task.task_number',
                'sales.qty'
            )
            ->where('employee.id', '=', $id)
            ->get();
        $return_task = DB::table('employee')
            ->join('task', 'task.empoyee_id', '=', 'employee.id')
            ->join('product_demage', 'task.id', '=', 'product_demage.task_id')
            ->join('products', 'products.id', '=', 'product_demage.product_id')
            ->select(
                'product_demage.*',
                'employee.first_name',
                'employee.employee_number',
                'products.product_name',
                'products.stock',
                'task.task_number'
            )
            ->where('product_demage.employee_id', '=', $id)
            ->get();
        $demage_product = DB::table('employee')
            ->join('task', 'task.empoyee_id', '=', 'employee.id')
            ->join('products', 'products.id', '=', 'stock_return.product_id')
            ->select(
                'employee.first_name',
                'employee.employee_number',
                'products.product_name',
                'products.stock',
                'task.task_number'
            )
            ->where('employee.id', '=', $id)
            ->get();

        $empo = DB::table('employee')
            ->join('task', 'task.empoyee_id', '=', 'employee.id')
            ->where('employee.id', '=', $id)
            ->get();
        $emp_number = $empo[0]->employee_number;

        $close_task = DB::table('employee')
            ->join('task', 'task.empoyee_id', '=', 'employee.id')
            ->where('employee.employee_number', '=', $emp_number)
            ->where('task.amount_due', '=', '0')
            ->get();

        return view('task.task_info', compact('data', 'pay', 'empo', 'return_task', 'close_task', 'demage_product', 'id'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportTask(Request $request)
    {
        $datesArray = explode('-', $request->date_range);
        $status = $request->status;
        $empId = $request->employeeId;
        info($empId);
        $from = Carbon::create(intVal(explode('/', $datesArray[0])[2]), intVal(explode('/', $datesArray[0])[0]), intVal(explode('/', $datesArray[0])[1]), 0, 0, 0);
        $to = Carbon::create(intVal(explode('/', $datesArray[1])[2]), intVal(explode('/', $datesArray[1])[0]), intVal(explode('/', $datesArray[1])[1]), 23, 59, 59);
        info($from);
        if ($empId == 'all') {
            $product_outs = DB::table('task')
                ->join('sales', 'sales.task_id', '=', 'task.id')
                ->join('products', 'sales.product_id', '=', 'products.id')
                ->join('employee', 'task.empoyee_id', '=', 'employee.id')
                ->whereDate('task.created_at', '>=', $from)
                ->whereDate('task.created_at', '<=', $to)
                ->select(
                    'sales.*',
                    'task.demage_cost',
                    'task.returned',
                    'products.product_name',
                    'task.created_at',
                    'employee.employee_number',
                    'employee.first_name',
                    'employee.phone',
                    'employee.last_name'
                )
                ->get();
            $product_out = array();
            foreach ($product_outs as $key => $value) {
                # code...

                $demages = DB::table('product_demage')
                    ->where('product_id', '=', $value->product_id)
                    ->where('task_id', '=', $value->task_id)
                    ->select('product_demage.*')
                    ->get();
                $value->demages = 0;
                foreach ($demages as $key1 => $value1) {
                    $value->demage_qty = $value1->qty;
                    $value->demages = $value1->amt;
                }

                $value->demage_qty = $value->return_qty;
                $value->demages = $value->return_amt;
                array_push($product_out, $value);
            }
            info($product_out);

            $employee = $empId;
            $count = DB::table('sales')
                ->join('task', 'sales.task_id', '=', 'task.id')
                ->join('products', 'sales.product_id', '=', 'products.id')
                ->join('employee', 'task.empoyee_id', '=', 'employee.id')
                ->whereDate('task.created_at', '>=', $from)
                ->whereDate('task.created_at', '<=', $to)
                ->select(
                    'sales.*',
                    'products.product_name',
                    'task.created_at',
                    'employee.employee_number',
                    'employee.first_name',
                    'employee.phone'
                )
                ->count();
            $sum_qty = DB::table('sales')
                ->join('task', 'sales.task_id', '=', 'task.id')
                ->join('products', 'sales.product_id', '=', 'products.id')
                ->join('employee', 'task.empoyee_id', '=', 'employee.id')
                ->whereDate('task.created_at', '>=', $from)
                ->whereDate('task.created_at', '<=', $to)
                ->sum('sales.qty');

            $sum_amt = DB::table('sales')
                ->join('task', 'sales.task_id', '=', 'task.id')
                ->join('products', 'sales.product_id', '=', 'products.id')
                ->join('employee', 'task.empoyee_id', '=', 'employee.id')
                ->whereDate('task.created_at', '>=', $from)
                ->whereDate('task.created_at', '<=', $to)
                ->sum('sales.amt');

            $sum_return_qty = $sum_demage = DB::table('product_demage')
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->sum('qty');

            $sum_return_amt = DB::table('sales')
                ->whereDate('sales.created_at', '>=', $from)
                ->whereDate('sales.created_at', '<=', $to)
                ->sum('sales.return_amt');


            $sum_return = DB::table('sales')
                ->whereDate('sales.created_at', '>=', $from)
                ->whereDate('sales.created_at', '<=', $to)
                ->sum('sales.return_amt');

            $sum_demage = DB::table('product_demage')
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->sum('amt');


            $sum_sub1 = DB::table('sales')
                ->whereDate('sales.created_at', '>=', $from)
                ->whereDate('sales.created_at', '<=', $to)
                ->sum('sales.amt');
            $sum_sub = $sum_sub1;


            $sum_recive = DB::table('receive_sales')
                ->whereDate('receive_sales.created_at', '>=', $from)
                ->whereDate('receive_sales.created_at', '<=', $to)
                ->sum('receive_sales.amount');

            $sum_due = $sum_sub - ($sum_recive + $sum_demage + $sum_return);
            if ($sum_due <= 0) {
                $sum_due = 0;
            }
            if ($sum_sub < $sum_recive) {
                $sum_recive = $sum_sub - $sum_return;
            }

            $pdf = PDF::loadView('task.export_task', compact(
                'employee',
                'count',
                'product_out',
                'from',
                'to',
                'sum_qty',
                'sum_amt',
                'sum_return_qty',
                'sum_return_amt',
                'sum_return',
                'sum_due',
                'sum_recive',
                'sum_sub',
                'sum_due',
                'sum_demage'
            ));

            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('supplier.pdf');
        } else {

            $emply = DB::table('employee')
                ->where('id', $empId)
                ->select('first_name', 'last_name')
                ->get();
            $employee = $emply[0]->first_name . ' ' . $emply[0]->last_name;

            $product_outs = DB::table('employee')
                ->where('employee.id', '=', $empId)
                ->join('task', 'task.empoyee_id', '=', 'employee.id')
                ->join('sales', 'task.id', '=', 'sales.task_id')
                ->join('products', 'sales.product_id', '=', 'products.id')
                ->whereDate('task.created_at', '>=', $from)
                ->whereDate('task.created_at', '<=', $to)
                ->select(
                    'sales.*',
                    'task.demage_cost',
                    'task.returned',
                    'products.product_name',
                    'task.created_at',
                    'employee.employee_number',
                    'employee.first_name',
                    'employee.phone',
                    'employee.last_name'
                )
                ->get();
            $product_out = array();
            foreach ($product_outs as $key => $value) {
                # code...

                // $demages = DB::table('product_demage')
                // ->where('product_id', '=', $value->product_id)
                // ->where('task_id', '=', $value->task_id)
                // ->get();
                // $value->demages =0;
                // if(sizeof($demages)>0){
                //     foreach($demages as $key1 => $value1){
                //         $value->demage_qty = $value->return_qty;
                //         $value->demages += $value->return_amt;
                //     }    
                // }else{
                //     $value->demage_qty = 0;
                //     $value->demages += 0;
                // }

                $value->demage_qty = $value->return_qty;
                $value->demages += $value->return_amt;
                array_push($product_out, $value);
            }
            info($product_out);
            $count = DB::table('employee')
                ->where('employee.id', '=', $empId)
                ->join('task', 'task.empoyee_id', '=', 'employee.id')
                ->join('sales', 'task.id', '=', 'sales.task_id')
                ->join('products', 'sales.product_id', '=', 'products.id')
                ->select('sales.*')
                ->whereDate('task.created_at', '>=', $from)
                ->whereDate('task.created_at', '<=', $to)
                ->count();
            $sum_qty = DB::table('employee')
                ->where('employee.id', '=', $empId)
                ->join('task', 'task.empoyee_id', '=', 'employee.id')
                ->join('sales', 'task.id', '=', 'sales.task_id')
                ->whereDate('task.created_at', '>=', $from)
                ->whereDate('task.created_at', '<=', $to)
                ->sum('sales.qty');

            $sum_amt = DB::table('employee')
                ->where('employee.id', '=', $empId)
                ->join('task', 'task.empoyee_id', '=', 'employee.id')
                ->join('sales', 'task.id', '=', 'sales.task_id')
                ->whereDate('task.created_at', '>=', $from)
                ->whereDate('task.created_at', '<=', $to)
                ->sum('sales.amt');

            $sum_return_qty = DB::table('employee')
                ->where('employee.id', '=', $empId)
                ->join('task', 'task.empoyee_id', '=', 'employee.id')
                ->join('sales', 'task.id', '=', 'sales.task_id')
                ->whereDate('task.created_at', '>=', $from)
                ->whereDate('task.created_at', '<=', $to)
                ->sum('sales.return_qty');

            $sum_return_amt = DB::table('employee')
                ->where('employee.id', '=', $empId)
                ->join('task', 'task.empoyee_id', '=', 'employee.id')
                ->join('sales', 'task.id', '=', 'sales.task_id')
                ->join('products', 'sales.product_id', '=', 'products.id')
                ->whereDate('task.created_at', '>=', $from)
                ->whereDate('task.created_at', '<=', $to)
                ->select(
                    'sales.*',
                    'products.product_name',
                    'task.created_at',
                    'employee.employee_number',
                    'employee.first_name',
                    'employee.phone'
                )
                ->sum('sales.return_amt');


            $sum_return = DB::table('employee')
                ->where('employee.id', '=', $empId)
                ->join('task', 'task.empoyee_id', '=', 'employee.id')
                ->join('sales', 'task.id', '=', 'sales.task_id')
                ->whereDate('task.created_at', '>=', $from)
                ->whereDate('task.created_at', '<=', $to)
                ->sum('task.returned');

            $sum_demage = DB::table('employee')
                ->where('employee.id', '=', $empId)
                ->join('task', 'task.empoyee_id', '=', 'employee.id')
                ->join('product_demage', 'task.id', '=', 'product_demage.task_id')
                ->whereDate('task.created_at', '>=', $from)
                ->whereDate('task.created_at', '<=', $to)
                ->sum('product_demage.amt');


            $sum_sub1 = DB::table('employee')
                ->where('employee.id', '=', $empId)
                ->join('task', 'task.empoyee_id', '=', 'employee.id')
                ->join('sales', 'task.id', '=', 'sales.task_id')
                ->whereDate('task.created_at', '>=', $from)
                ->whereDate('task.created_at', '<=', $to)
                ->sum('sales.amt');
            $sum_sub = $sum_sub1;



            $sum_recive = DB::table('receive_sales')
                ->join('task', 'task.id', '=', 'receive_sales.task_id')
                ->join('employee', 'task.empoyee_id', '=', 'employee.id')
                ->where('employee.id', '=', $empId)
                ->whereDate('receive_sales.created_at', '>=', $from)
                ->whereDate('receive_sales.created_at', '<=', $to)
                ->sum('receive_sales.amount');
            $sum_due = $sum_sub - ($sum_recive + $sum_demage + $sum_return);


            info($product_out);
            $pdf = PDF::loadView('task.export_task', compact(
                'employee',
                'count',
                'product_out',
                'from',
                'to',
                'sum_qty',
                'sum_amt',
                'sum_return_qty',
                'sum_return_amt',
                'sum_return',
                'sum_due',
                'sum_recive',
                'sum_sub',
                'sum_due',
                'sum_demage'
            ));

            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('supplier.pdf');
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function single_report($id)
    {
        // Fetch all necessary data in one query with joins to minimize repeated DB calls
        $product_outs = DB::table('sales')
            ->join('task', 'task.id', '=', 'sales.task_id')
            ->join('employee', 'employee.id', '=', 'task.empoyee_id')
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->where('task.id', '=', $id)
            ->select(
                'sales.price',
                'sales.qty',
                'sales.amt',
                'sales.product_id',
                'task.sub_total',
                'sales.task_id',
                'products.product_name',
                'task.created_at',
                'employee.employee_number',
                'employee.first_name',
                'employee.last_name',
                'employee.phone'
            )
            ->orderBy('sales.created_at', 'ASC')
            ->get();

        // Get total payments in one query
        $payments = DB::table('receive_sales')
            ->where('task_id', '=', $id)
            ->sum('receive_sales.amount');

        // Preload all demages in one query to avoid repeated queries in the loop
        $demagesByProduct = DB::table('product_demage')
            ->whereIn('product_id', $product_outs->pluck('product_id'))
            ->where('task_id', $id)
            ->get()
            ->groupBy('product_id');

        // Prepare results, avoid querying in loop
        $product_out = [];
        $pded = 0;
        $start = null;
        $end = null;

        foreach ($product_outs as $key => $value) {
            $ppay = $payments - $pded;

            // Set default values
            $value->demages = 0;
            $value->amount_paid = 0;

            // Track start and end dates
            if ($key == 0) $start = $value->created_at;
            if ($key == sizeof($product_outs) - 1) $end = $value->created_at;

            // Check if there are any demages for the product
            $demages = $demagesByProduct[$value->product_id] ?? collect();

            if ($demages->isNotEmpty()) {
                $demage = $demages->first();  // Assume one demage per product
                $value->amt += ($value->sub_total - $value->amt);

                if ($ppay > $value->amt) {
                    $value->amount_paid = $value->amt;
                    $value->demage_qty = $demage->qty;
                    $value->demages += $demage->amt;
                    $pded += $value->amt;
                } else {
                    $value->amount_paid = max($ppay, 0);
                    $pded += $value->amt;
                }
            } else {
                // Handle case when no demages exist
                $value->amount_paid = ($ppay > $value->amt) ? $value->amt : max($ppay, 0);
                $pded += $value->amt;
            }

            // Add to final output
            $product_out[] = $value;
        }

        // Fetch all related data in one go using appropriate joins and queries
        $x = DB::table('receive_sales')
            ->join('task', 'receive_sales.task_id', '=', 'task.id')
            ->join('employee', 'task.empoyee_id', '=', 'employee.id')
            ->where('task.id', '=', $id)
            ->select(
                'receive_sales.*',
                'task.task_number',
                'employee.employee_number',
                'employee.first_name',
                'employee.last_name',
                'employee.phone'
            )
            ->get();

        $demage = DB::table('product_demage')
            ->join('task', 'task.id', '=', 'product_demage.task_id')
            ->join('employee', 'task.empoyee_id', '=', 'employee.id')
            ->join('products', 'product_demage.product_id', '=', 'products.id')
            ->where('task.id', '=', $id)
            ->select(
                'product_demage.*',
                'products.product_name',
                'task.created_at',
                'employee.employee_number',
                'employee.first_name',
                'employee.last_name',
                'employee.phone'
            )
            ->get();

        // Combine sum queries to minimize DB queries
        $sums = DB::table('task')
            ->leftJoin('sales', 'sales.task_id', '=', 'task.id')
            ->leftJoin('stock_return', 'task.id', '=', 'stock_return.task_id')
            ->leftJoin('product_demage', 'task.id', '=', 'product_demage.task_id')
            ->leftJoin('receive_sales', 'receive_sales.task_id', '=', 'task.id')
            ->where('task.id', '=', $id)
            ->selectRaw('
                SUM(sales.qty) as sum_qty,
                SUM(sales.amt) as sum_amt,
                SUM(stock_return.qty) as sum_return_qty,
                SUM(stock_return.amt) as sum_return_amt,
                SUM(task.returned) as sum_return,
                SUM(task.amount_due) as sum_due,
                SUM(product_demage.qty) as sum_demage_qty,
                SUM(product_demage.amt) as sum_demage_amt,
                SUM(receive_sales.amount) as sum_recive
            ')
            ->first();
        info($sums->sum_recive);
        // Generate the PDF using fetched data
        $pdf = PDF::loadView('task.single_report', [
            'dates' => $start,
            'count' => count($product_out),
            'product_out' => $product_out,
            'sum_qty' => $sums->sum_qty,
            'sum_amt' => $sums->sum_amt,
            'sum_return_qty' => $sums->sum_return_qty,
            'sum_return_amt' => $sums->sum_return_amt,
            'sum_return' => $sums->sum_return,
            'sum_due' => $sums->sum_due,
            'demage' => $demage,
            'sum_recive' => $payments,
            'x' => $x,
            'sum_demage_qty' => $sums->sum_demage_qty,
            'sum_demage_amt' => $sums->sum_demage_amt
        ]);

        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('supplier.pdf');
    }




    public function exportProductKeluar($id)
    {
        $product_keluar = Product_Keluar::findOrFail($id);
        $pdf = PDF::loadView('product_keluar.productKeluarPDF', compact('product_keluar'));
        return $pdf->download($product_keluar->id . '_product_keluar.pdf');
    }

    public function infoApi($id)
    {
        // Fetch all task-related data in a single query
        $task = DB::table('task')
            ->join('employee', 'task.empoyee_id', '=', 'employee.id')
            ->join('sales', 'task.id', '=', 'sales.task_id')
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->leftJoin('returns', 'sales.id', '=', 'returns.sales_id')  // assuming you have a returns table for return_qty, return_price, return_amt
            ->select(
                'task.*',
                'employee.first_name',
                'employee.last_name',
                'employee.employee_number',
                'sales.id as sales_id',
                'sales.qty',
                'sales.amt',
                'products.product_name',
                'sales.price',
                'products.stock',
                'sales.product_id',
                'returns.return_qty', // Assuming returns are related to sales
                'returns.return_price',
                'returns.return_amt'
            )
            ->where('task.id', '=', $id)
            ->get();

        return Datatables::of($task)
            // Adding action buttons
            ->addColumn('action', function ($task) {
                return '
                <div class="btn-group" style="width:100%">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a onclick="returnForm(' . $task->sales_id . ')" class="btn btn-danger btn-xs" style="color:white">
                                <i class="fa fa-undo" style="color:white"></i> Return Stock
                            </a>
                        </li>
                        <li>
                            <a onclick="demageForm(' . $task->sales_id . ')" class="btn btn-primary btn-xs" style="color:white">
                                <i class="fa fa-undo" style="color:white"></i> Demage Products
                            </a>
                        </li>
                    </ul>
                </div>';
            })

            // Formatting demage_cost column
            ->editColumn('demage_cost', function ($task) {
                return '<div class="text-warning">' . number_format($task->demage_cost, 2) . '</div>';
            })

            // Formatting amount_due column
            ->editColumn('amount_due', function ($task) {
                return '<div class="text-danger">' . number_format($task->amount_due, 2) . '</div>';
            })

            // Formatting amount_paid column
            ->editColumn('amount_paid', function ($task) {
                return '<div class="text-success">' . number_format($task->amount_paid, 2) . '</div>';
            })

            // Formatting returned column
            ->editColumn('returned', function ($task) {
                return '<div class="text-primary">' . number_format($task->returned, 2) . '</div>';
            })

            // Formatting sub_total column
            ->editColumn('sub_total', function ($task) {
                return '<div class="text-primary">' . number_format($task->sub_total, 2) . '</div>';
            })

            // Avoid escaping columns
            ->escapeColumns([])

            // Include raw columns for action buttons and custom HTML content
            ->rawColumns(['action'])
            ->make(true);
    }


    public function demageApi($id)
    {
        $demage_product = DB::table('employee')
            ->join('task', 'task.empoyee_id', '=', 'employee.id')
            ->join('product_demage', 'task.id', '=', 'product_demage.task_id')
            ->join('products', 'products.id', '=', 'product_demage.product_id')
            ->select(
                'product_demage.*',
                'employee.first_name',
                'employee.employee_number',
                'products.product_name',
                'products.stock',
                'task.task_number'
            )
            ->where('product_demage.task_id', '=', $id)
            ->get();
        return Datatables::of($demage_product)
            ->addColumn('action', function ($demage_product) {
                return '
        <div class="btn-group" style="width:100%">
           <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               Action <span class="caret"></span>
           </button>
           <ul class="dropdown-menu">
       
           <li><a onclick="returnForm(' . $demage_product->id . ')" class="btn btn-danger btn-xs" style="color:white"><i class="fa fa-undo"  style="color:white"></i>Return Stock</a></li>
                <li><a onclick="demageForm(' . $demage_product->id . ')" class="btn btn-primary btn-xs" style="color:white"><i class="fa fa-undo"  style="color:white"></i>Demage Products</a></li>
               <li><a href="#" class="btn btn-warning btn-xs pays" style="color:white" id="' . $demage_product->id . '"><i class="fa fa-money" style="color:white"></i>Receive Payment</a></li>


           </ul>
       </div> ';
            })

            ->escapeColumns([])
            ->rawColumns(['action'])->make(true);
    }

    public function returnApi($id)
    {
        $return_task = DB::table('employee')
            ->join('task', 'task.empoyee_id', '=', 'employee.id')
            ->join('stock_return', 'task.id', '=', 'stock_return.task_id')
            ->join('products', 'products.id', '=', 'stock_return.product_id')
            ->select(
                'stock_return.*',
                'employee.first_name',
                'employee.employee_number',
                'products.product_name',
                'products.stock',
                'task.task_number'
            )
            ->where('stock_return.task_id', '=', $id)
            ->get();
        return Datatables::of($return_task)
            // ->addColumn('action', function($return_task){
            //     return '
            //     <div class="btn-group" style="width:100%">
            //        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            //            Action <span class="caret"></span>
            //        </button>
            //        <ul class="dropdown-menu">

            //        <li><a onclick="returnForm('. $return_task->id .')" class="btn btn-danger btn-xs" style="color:white"><i class="fa fa-undo"  style="color:white"></i>Return Stock</a></li>
            //             <li><a onclick="demageForm('.$return_task->id .')" class="btn btn-primary btn-xs" style="color:white"><i class="fa fa-undo"  style="color:white"></i>Demage Products</a></li>
            //            <li><a href="#" class="btn btn-warning btn-xs pays" style="color:white" id="'.$return_task->id.'"><i class="fa fa-money" style="color:white"></i>Receive Payment</a></li>


            //        </ul>
            //    </div> ';
            // })

            ->escapeColumns([])
            ->rawColumns(['action'])->make(true);
    }
}
//        <li><a href="exportProforma/'.$task->id .'" class="btn btn-warning btn-xs pro_invoice" style="color:white" ><i class="fas fa-file-invoice" style="color:white"></i>Proforma Invoice</a></li>
//        <li><a onclick=" editForm('. $task->id .')" class="btn btn-success btn-xs" style="color:white"><i class="glyphicon glyphicon-pencil" style="color:white"></i> Edit</a></li>