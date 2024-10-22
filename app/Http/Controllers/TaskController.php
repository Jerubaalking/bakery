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
use App\Traits\LogsActivity;



class TaskController extends Controller
{

    use LogsActivity;
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
        $session_id = Auth::User()->session_id;
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
            ->where('account.id', '=', $request->supplier_id) // Specify the table for the 'id' column
            ->join('employee', 'employee.account_id', 'account.id')
            ->select('account.*', 'employee.employee_number as employee_number', 'employee.id as employee_id')
            ->first();

        // info(json_encode($employee));
        // Check for existing task
        $recordDate = DB::table('task')
            ->where('empoyee_id', '=', $supplier->employee_id)
            ->where('account_id', '=', $account_id)
            ->where('created_at', '=', $date_in)
            ->where('session_id', '=', $session_id)
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
                    $nextTask = 'DISPATCH-' . sprintf("%04d", $numericPart + 1);
                } else {
                    // No valid previous task, or the task number format is incorrect
                    $nextTask = 'DISPATCH-0001';
                }

                // Create the new task with the generated task number
                $form_data = [
                    'sub_total' => round($subtotal, 2),
                    'empoyee_id' => $supplier->employee_id, // Assuming you meant 'employee_id' (correct typo from 'empoyee_id')
                    'account_id' => $supplier->id,
                    'created_at' => $date,
                    'amount_paid' => 0,
                    'amount_due' => $subtotal,
                    'task_number' => $nextTask,
                    'returned' => $return_amt,
                    'session_id' => $session_id,
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
                        'session_id' => $session_id,
                    ];

                    // Optional: Update product stock after each sale
                    // DB::table('products')->where('id', $product_id[$i])->decrement('stock', $qty[$i]);
                }

                // Batch insert sales data
                DB::table('sales')->insert($sales_data);
            }

            // Commit the transaction after everything is successful
            DB::commit();

            // Log success completion
            $this->logActivity('Dispatch creation', 'success', json_encode($sales_data));

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Information successfully added'
            ]);
        } catch (\Exception $e) {
            // Rollback transaction if something goes wrong
            DB::rollBack();

            // Log the error with exception details
            $this->logActivity('Dispatch creation', 'failed', $e->getMessage());

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
            return view('casual.staffing.index', compact('data'));
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
        // Validation
        $this->validate($request, [
            "price.*" => 'required|integer|min:1',
        ]);

        // Extracting input values
        $date = Carbon::now()->format('Y-m-d');
        $item_name = $request->item_name;
        $date_in = $request->date_in;
        $qty = $request->qty;
        $price = $request->price;
        $retail_price = $request->retail_price;
        $subtotal = $request->sub_total;
        $account_id = $request->supplier_id;
        $product_id = $request->product_id;
        $return_qty = 0;
        $return_price = 0;
        $return_amt = 0;

        // Fetch supplier and employee details
        $supplier = DB::table('account')
            ->where('account.id', '=', $request->supplier_id)
            ->join('employee', 'employee.account_id', 'account.id')
            ->select('account.*', 'employee.employee_number as employee_number', 'employee.id as employee_id')
            ->first();

        // Fetch the existing task record
        $existingTask = DB::table('task')
            ->where('id', '=', $id)
            ->first();

        // Start transaction to ensure data consistency
        DB::beginTransaction();
        try {
            // Check if the task exists
            if ($existingTask) {
                // Updating the existing task
                $updated_data = [
                    'sub_total' => round($existingTask->sub_total + $subtotal, 2),
                    'amount_paid' => $existingTask->amount_paid,
                    'amount_due' => $existingTask->amount_due + $subtotal,
                    'returned' => $existingTask->returned + $return_amt,
                    'updated_at' => $date,
                ];

                // Update the task with new data
                DB::table('task')
                    ->where('id', $id)
                    ->update($updated_data);
            } else {
                // If task doesn't exist, return a failure response
                return response()->json([
                    'success' => false,
                    'message' => 'Task not found',
                ], 404);
            }

            // Update the sales entries associated with the task
            if ($qty) {
                $sales_data = [];

                for ($i = 0; $i < sizeof($qty); $i++) {
                    $sales_data[] = [
                        'task_id' => $id,
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

                // First delete the existing sales data for the task
                DB::table('sales')->where('task_id', '=', $id)->delete();

                // Batch insert updated sales data
                DB::table('sales')->insert($sales_data);
            }

            // Commit the transaction after everything is successful
            DB::commit();

            // Log success completion
            $this->logActivity('Task update', 'success', json_encode($sales_data));

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Task successfully updated'
            ]);
        } catch (\Exception $e) {
            // Rollback transaction if something goes wrong
            DB::rollBack();

            // Log the error with exception details
            $this->logActivity('Task update', 'failed', $e->getMessage());

            // Return failure response
            return response()->json([
                'success' => false,
                'message' => 'Task update failed due to an error'
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
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Fetch the task to be deleted
            $task = DB::table('task')->where('id', '=', $id)->first();

            // If the task does not exist, return an error response
            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dispatch not found'
                ], 404);
            }

            // Check if there are any entries in the receive_sales table for this task
            $receiveSalesEntries = DB::table('receive_sales')
                ->where('task_id', '=', $id)
                ->count();

            // If there are receive_sales entries, notify the user that they need to be deleted first
            if ($receiveSalesEntries > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dispatch cannot be deleted dispatch has received payments!'
                ], 400);
            }

            // If no receive_sales entries exist, proceed to delete the associated records

            // Delete sales associated with the task
            DB::table('sales')->where('task_id', '=', $id)->delete();

            // Optionally, delete any other related data in other tables if required (e.g. logs, product stock adjustments, etc.)
            // Example: DB::table('other_related_table')->where('task_id', '=', $id)->delete();

            // Delete the task itself
            DB::table('task')->where('id', '=', $id)->delete();

            // Commit the transaction after successful deletion
            DB::commit();

            // Log the task deletion activity
            $this->logActivity('Dispatch deletion', 'success', 'Dispatch ID: ' . $id . ' and its associated records were deleted');

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Dispatch and its associated records successfully deleted'
            ]);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            // Log the error
            $this->logActivity('Dispatch deletion', 'failed', $e->getMessage());

            // Return error response
            return response()->json([
                'success' => false,
                'message' => 'Dispatch deletion failed due to an error: ' . $e->getMessage()
            ], 500);
        }
    }
    public function apiTask($start, $end, $empId)
    {
        $session_id = Auth::User()->session_id;

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
                    ->where('task.session_id', '=', $session_id)
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
                    ->where('task.empoyee_id', '=', $empId) // Corrected 'empoyee_id' to 'task.empoyee_id'
                    ->where('task.session_id', '=', $session_id)
                    ->join('employee', 'employee.id', '=', 'task.empoyee_id') // Corrected join condition
                    ->join('sales', 'task.id', '=', 'sales.task_id')
                    ->where('task.amount_due', '>', 0)
                    ->select(
                        'task.id',
                        'task.empoyee_id',
                        'task.account_id',
                        'task.returned',
                        'task.demage_cost', // Corrected 'demage_cost' to 'damage_cost'
                        'task.created_at',
                        'task.task_number',
                        'task.amount_paid',
                        'task.amount_due',
                        'task.sub_total',
                        DB::raw('SUM(sales.bulk * sales.price + sales.retail * sales.retail_price) as expected_amount'),
                        'employee.first_name',
                        'employee.last_name'
                    )
                    ->groupBy(
                        'task.id',
                        'task.empoyee_id',
                        'task.account_id',
                        'task.returned',
                        'task.demage_cost', // Corrected 'demage_cost' to 'damage_cost'
                        'task.created_at',
                        'task.task_number',
                        'task.amount_paid',
                        'task.amount_due',
                        'task.sub_total',
                        'employee.id',
                        'employee.first_name',
                        'employee.last_name'
                    )
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
                            <button class="m-5 btn btn-outline btn-default btn-sm"><i class="fa fa-ellipsis-v"></i></button>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                                <a href="#" class="pays" id="' . $task->id . '" test="' . $task->id . '">
                                    <i class="fa fa-money text-success"></i> Receive Payment
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" onclick="deleteData(' . $task->id . ')" id="' . $task->id . '">
                                    <i class="fas fa-trash-alt text-danger"></i> Delete
                                </a>
                            </li>
                            <li>
                                <a href="single_report/' . $task->id . '" target="_blank" class="more_details ">
                                    <i class="fa fa-file-pdf text-danger"></i> Report
                                </a>
                            </li>
                           <!-- <li>
                                <a href="task_info/' . $task->id . '" class="more_details">
                                    <i class="fa fa-info text-info"></i> More 
                                </a>
                            </li> -->
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
                      <div class="dropdown" style="width:100%">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <button class="m-5 btn btn-outline btn-default btn-sm"><i class="fa fa-ellipsis-v"></i></button>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                                <a href="#" class=" pays" data-task-id="{{ task.id }}" data-employee-id="{{ task.id }}">
                                    <i class="fa fa-money"></i> Receive Payment
                                </a>
                            </li>
                            <!-- <li>
                                <a href="#" class=" damageForm" data-task-id="{{ task.id }}">
                                    <i class="fa fa-download"></i> download form
                                </a> 
                            </li> -->
                             <li>
                                <a href="single_report/' . $task->id . '" target="_blank" class="more_details ">
                                    <i class="fa fa-file-pdf text-danger"></i> Report
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
        $session_id = Auth::User()->session_id;
        $tasks = DB::table('employee')
            ->where('empoyee_id', '=', $id)
            ->join('task', 'employee.id', '=', 'task.empoyee_id')
            ->whereDate('task.created_at', '>=', $start)
            ->whereDate('task.created_at', '<=', $end)
            ->where('task.session_id', '=', $session_id)
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
                   <!--<li><a href="#" class="pays" style="color:white" task="' . $task->id . '" id="' . $task->empoyee_id . '" ><i class="fa fa-money" style="color:white"></i>Receive Payment</a></li>-->
                            <li>
                                <a href="single_report/' . $task->id . '" target="_blank" class="more_details ">
                                    <i class="fa fa-file-pdf text-danger"></i> Report
                                </a>
                            </li>
                       <li><a href="#" class="block" style="color:white" id="' . $task->empoyee_id . '"><i class="fa fa-money" style="color:white"></i>Block Account</a></li>
                   </ul>
               </div> ';
                })

                ->editColumn('amount_due', function ($task) {

                    return '<div class="text-danger">' . number_format($task->amount_due, 2) . '</div>';
                })
                ->editColumn('amount_paid', function ($task) {

                    return '<div class="text-success">' . number_format($task->amount_paid, 2) . '</div>';
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
                   <li><a href="#" class="btn btn-warning btn-xs pays" style="color:white" task="' . $task->id . '"  id="' . $task->empoyee_id . '"><i class="fa fa-money" style="color:white"></i> Receive Payment</a></li>

                            <li>
                                <a href="single_report/' . $task->id . '" target="_blank" class="more_details ">
                                    <i class="fa fa-file-pdf text-danger"></i> Report
                                </a>
                            </li>
                   </ul>
               </div> ';
                })
                ->editColumn('amount_due', function ($task) {

                    return '<div class="text-danger">' . number_format($task->amount_due, 2) . '</div>';
                })
                ->editColumn('amount_paid', function ($task) {

                    return '<div class="text-success">' . number_format($task->amount_paid, 2) . '</div>';
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
        $task = null;
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
               <div class="dropdown" style="width:100%">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <button class="m-5 btn btn-outline btn-default btn-sm"><i class="fa fa-ellipsis-v"></i></button>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                   <li>
                            <li>
                                <a href="single_report/' . $task->id . '" target="_blank" class="more_details ">
                                    <i class="fa fa-file-pdf text-danger"></i> Report
                                </a>
                            </li>
                   </ul>
               </div> ';
            })
            ->editColumn('amount_due', function ($task) {

                return '<div class="text-danger">' . number_format($task->amount_due, 2) . '</div>';
            })
            ->editColumn('amount_paid', function ($task) {

                return '<div class="text-success">' . number_format($task->amount_paid, 2) . '</div>';
            })
            ->editColumn('sub_total', function ($task) {

                return '<div class="text-primary">' . number_format($task->sub_total, 2) . '</div>';
            })
            ->escapeColumns([])
            ->rawColumns(['action'])->make(true);
    }
    public function apiDamagedTask($start, $end, $empId)
    {

        $task = null;
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
                <div class="dropdown" style="width:100%">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <button class="m-5 btn btn-outline btn-default btn-sm"><i class="fa fa-ellipsis-v"></i></button>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                   <li>

                       <li><a href="task_info/' . $task->id . '" class="btn btn-success btn-xs more_details" style="color:white" ><i class="glyphicon glyphicon-eye-open" style="color:white"></i>More Details</a></li>
                   </ul>
               </div> ';
            })
            ->editColumn('amount_due', function ($task) {

                return '<div class="text-danger">' . number_format($task->amount_due, 2) . '</div>';
            })
            ->editColumn('amount_paid', function ($task) {

                return '<div class="text-success">' . number_format($task->amount_paid, 2) . '</div>';
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
                info("task id --->" . $id);
                // Fetch the task data
                $data = DB::table('task')
                    ->where('task.id', '=', $id)
                    ->join('employee', 'employee.id', '=', 'task.empoyee_id')
                    ->join('account', 'account.id', '=', 'task.account_id')
                    ->join('sales', 'sales.task_id', '=', 'task.id')
                    ->where('task.amount_due', '>', '0')
                    ->selectRaw('
                        task.id, 
                        task.account_id,
                        task.amount_due,
                        task.sub_total,
                        account.account_name,
                        employee.employee_number,
                        CONCAT(employee.first_name, " ", employee.last_name) as employee_name,
                        SUM(task.returned) as returned,
                        SUM(task.demage_cost) as demage_cost
                    ')
                    ->groupBy('task.id', 'task.amount_due', 'task.empoyee_id', 'task.account_id', 'task.sub_total', 'account.account_name', 'employee.employee_number', 'employee.first_name', 'employee.last_name')
                    ->first();

                // Check if task data exists
                if (!$data) {
                    return response()->json(['error' => 'No data found'], 404);
                }

                $paid = DB::table('receive_sales')
                    ->where('receive_sales.task_id', '=', $data->id)
                    ->sum('receive_sales.amount');
                // $latest_payment_date = DB::table('receive_sales')
                //     ->where('receive_sales.task_id', '=', $data->id)
                //     ->max('receive_sales.updated_at');
                // Assuming $paid is fetched from the database
                // Example: $paid = DB::table('receive_sales')->where('task_id', $task_id)->sum('amount');

                if (is_object($paid) && property_exists($paid, 'paid')) {
                    $data['amount_paid'] = $paid->paid;
                } else if (is_numeric($paid)) {
                    // If $paid is a numeric value (likely from a sum operation), use it directly
                    $data->amount_paid = $paid;
                } else {
                    // Handle the case where $paid is not an object or a number (unexpected case)
                    $data['amount_paid'] = 0; // Set a default value
                }

                // Continue with the calculation
                $subTotal = floatval($data->sub_total);
                $amountPaid = floatval($data->amount_paid);
                $data->amount_due = $subTotal - $amountPaid;

                // Log information about the task
                info('task id ==>' . json_encode($data));


                // Fetch the associated sales data
                info('this is data: ' . $paid);
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
                $data->amount_paid = $paid;

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
        $data = $this->getTaskData($id);
        $pay = $this->getPayData($id);
        $returnTask = $this->getReturnTaskData($id);
        $damageProducts = $this->getDamageProductData($id);
        $employeeInfo = $this->getEmployeeInfo($id);

        // Extracting employee-related details
        $empId = $employeeInfo->id;
        $empNumber = $employeeInfo->employee_number;
        $created = $employeeInfo->created_at;

        // Fetching previous and latest tasks
        $previous_task = $this->getPreviousTasks($empId, $created);
        $latest_task = $this->getLatestTasks($empId, $created);

        return view('task.task_info', compact('data', 'previous_task', 'pay', 'employeeInfo', 'returnTask', 'latest_task', 'damageProducts', 'id'));
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
        // Fetch basic employee data with related sales and product info
        $employee_data = DB::table('employee')
            ->join('task', 'task.empoyee_id', '=', 'employee.id')
            ->join('sales', 'task.id', '=', 'sales.task_id')
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->select(
                'task.*',
                'employee.first_name',
                'employee.last_name',
                'employee.employee_number',
                'sales.qty',
                'sales.amt',
                'sales.price',
                'products.product_name',
                'products.stock',
                'sales.product_id',
                'sales.return_qty',
                'sales.return_price',
                'sales.return_amt'
            )
            ->where('employee.id', '=', $id)
            ->get();

        // Fetch employee payment details
        $employee_payments = DB::table('employee')
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

        // Fetch task return information
        $return_tasks = DB::table('employee')
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

        // Fetch damaged products information
        $damaged_products = DB::table('employee')
            ->join('task', 'task.empoyee_id', '=', 'employee.id')
            ->join('stock_return', 'task.id', '=', 'stock_return.task_id') // This should join `stock_return`, not `products`
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

        // Fetch employee basic information
        $employee_info = DB::table('employee')
            ->where('id', '=', $id)
            ->first();
        $emp_number = $employee_info->employee_number;

        // Fetch closed tasks where amount due is zero
        $closed_tasks = DB::table('task')
            ->where('task.empoyee_id', '=', $id)
            ->where('task.amount_due', '=', '0')
            ->get();

        return view('task.task_info', compact(
            'employee_data',
            'employee_payments',
            'return_tasks',
            'damaged_products',
            'closed_tasks',
            'employee_info',
            'id'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportTask(Request $request)
    {
        // Parse date range
        $datesArray = explode('-', $request->date_range);
        $from = Carbon::createFromFormat('m/d/Y', trim($datesArray[0]))->startOfDay();
        $to = Carbon::createFromFormat('m/d/Y', trim($datesArray[1]))->endOfDay();

        // Initialize employee ID and status
        $empId = $request->employeeId;
        // If all employees are selected
        if ($empId == 'all') {
            $employee = 'All';
            $product_outs = $this->getProductOuts(null, $from, $to);
            $payments = $this->getPayments(null, $from, $to);
            $product_out = $this->processProductOuts($product_outs, $payments);
            $sums = $this->getSums(null, $from, $to);
            $demage = $this->getDemageRecords(null, $from, $to);
            $x = $this->getReceiveSales(null, $from, $to);

            // Generate PDF
            return $this->generatePdf($product_out, $sums, $x, $demage, $payments, $from, $to, $employee);
        } else {


            // Fetch data for a specific employee
            $employeee = DB::table('employee')->where('id', '=', $empId)->first();
            $employee = $employeee->first_name . ' ' . $employeee->last_name;
            $product_outs = $this->getProductOuts($empId, $from, $to);
            $payments = $this->getPayments($empId, $from, $to);
            $product_out = $this->processProductOuts($product_outs, $payments);
            $sums = $this->getSums($empId, $from, $to);
            $demage = $this->getDemageRecords($empId, $from, $to);
            $x = $this->getReceiveSales($empId, $from, $to);

            // Additional logic for employee-specific processing

            // Generate PDF
            return $this->generatePdf($product_out, $sums, $x, $demage, $payments, $from, $to, $employee);
        }
    }

    // Helper functions can be defined here for better structure, like:
        protected function getProductOuts($empId, $from, $to)
        {
            $query = DB::table('sales')
                ->join('task', 'task.id', '=', 'sales.task_id')
                ->leftJoin('receive_sales', 'task.id', '=', 'receive_sales.task_id')
                ->join('employee', 'employee.id', '=', 'task.empoyee_id')
                ->join('products', 'sales.product_id', '=', 'products.id')
                ->whereBetween('task.created_at', [$from, $to])
                ->select(
                    'sales.id as sales_id',       // Sales ID for reference (if needed)
                    'sales.qty',                  // Quantity sold
                    'sales.amt as amount',        // Amount for this sale
                    'task.amount_paid',           // Amount paid for the task
                    'task.task_number',           // Task number
                    'task.sub_total',             // Subtotal for the task
                    DB::raw('CONCAT(employee.first_name, " ", employee.last_name) as employee_name'), // Full employee name
                    'products.product_name',      // Product name
                    'task.created_at',            // Date of the task
                    'employee.employee_number',    // Employee number for reference
                    'employee.first_name',        // Employee's first name
                    'employee.last_name',         // Employee's last name
                    'employee.phone',             // Employee's phone number
                    'sales.retail',               // Retail quantity (ensure this exists in your sales table)
                    'sales.bulk',                 // Bulk quantity (ensure this exists in your sales table)
                    'sales.price',                // Price per unit
                    'sales.retail_price'          // Retail price per unit
                )
                ->groupBy(
                    'sales.id',                   // Group by sales ID to avoid duplicates
                    'sales.qty',
                    'sales.amt',
                    'task.amount_paid',
                    'task.task_number',
                    'task.sub_total',
                    'employee.first_name',
                    'employee.last_name',
                    'products.product_name',
                    'task.created_at',
                    'employee.employee_number',
                    'employee.phone',
                    'sales.retail',
                    'sales.bulk',
                    'sales.price',
                    'sales.retail_price'
                )
                ->orderBy('sales.created_at', 'ASC');
        
            // Apply employee filter if ID is present
            if ($empId) {
                $query->where('task.empoyee_id', '=', $empId);
            }
        
            // Execute and return the results
            return $query->get();
        }
        

    protected function getPayments($empId, $from, $to)
    {
        $query = DB::table('receive_sales')
            ->join('task', 'receive_sales.task_id', '=', 'task.id')
            ->whereBetween('task.created_at', [$from, $to]);
            if ($empId) {
                $query->where('task.empoyee_id', '=', $empId); // Apply employee filter if ID is present
            }
        
            // Execute and return the results
            return $query->sum('receive_sales.amount');
    }

    protected function processProductOuts($product_outs, $payments)
    {
        // Process logic here
        return $product_outs; // Simplified for now
    }

    protected function getSums($empId, $from, $to)
    {
        // Start building the query
        $query = DB::table('task')
        ->leftJoin(DB::raw('(SELECT task_id, SUM(qty) as sum_qty, SUM(amt) as sum_amt, SUM(retail_amt) as sum_retail_amt FROM sales GROUP BY task_id) as sales_summary'), 'sales_summary.task_id', '=', 'task.id')
        ->leftJoin(DB::raw('(SELECT task_id, SUM(qty) as sum_return_qty, SUM(amt) as sum_return_amt FROM stock_return GROUP BY task_id) as stock_return_summary'), 'stock_return_summary.task_id', '=', 'task.id')
        ->leftJoin(DB::raw('(SELECT task_id, SUM(qty) as sum_demage_qty, SUM(amt) as sum_demage_amt FROM product_demage GROUP BY task_id) as demage_summary'), 'demage_summary.task_id', '=', 'task.id')
        ->leftJoin(DB::raw('(SELECT task_id, SUM(amount) as sum_receive FROM receive_sales GROUP BY task_id) as receive_sales_summary'), 'receive_sales_summary.task_id', '=', 'task.id')
        ->leftJoin('employee', 'employee.id', '=', 'task.empoyee_id')
        ->whereBetween('task.created_at', [$from, $to]) // Apply date range filter
        ->selectRaw('
            CONCAT(employee.first_name, " ", employee.last_name) as employee_name,
            task.task_number as dispatch,
            task.created_at as date,
            sales_summary.sum_qty,
            sales_summary.sum_amt,
            sales_summary.sum_retail_amt,  -- Added here
            stock_return_summary.sum_return_qty,
            stock_return_summary.sum_return_amt,
            task.amount_due as sum_due,
            demage_summary.sum_demage_qty,
            demage_summary.sum_demage_amt,
            receive_sales_summary.sum_receive
        ')
        ->groupBy(
            'employee.first_name',
            'employee.last_name',
            'task.task_number',
            'task.created_at',
            'sales_summary.sum_qty',
            'sales_summary.sum_amt',
            'sales_summary.sum_retail_amt',
            'stock_return_summary.sum_return_qty',
            'stock_return_summary.sum_return_amt',
            'task.amount_due',
            'demage_summary.sum_demage_qty',
            'demage_summary.sum_demage_amt',
            'receive_sales_summary.sum_receive'
        );
        // If employee ID is provided, add it to the query
        if ($empId) {
            $query->where('task.empoyee_id', '=', $empId); // Filter by employee ID
        }
    
        // Execute and return the results
        return $query->get();
    }
    
    

    protected function getDemageRecords($empId, $from, $to)
    {
        $query= DB::table('product_demage')
            ->join('task', 'task.id', '=', 'product_demage.task_id')
            ->join('employee', 'employee.id', '=', 'task.empoyee_id')
            ->join('products', 'product_demage.product_id', '=', 'products.id')
            ->whereBetween('task.created_at', [$from, $to])
            ->select(
                'product_demage.*',
                'products.product_name',
                'task.created_at',
                'employee.employee_number',
                'employee.first_name',
                'employee.last_name',
                'employee.phone'
            );
            if ($empId) {
                $query->where('task.empoyee_id', '=', $empId); // Apply employee filter if ID is present
            }
        
            // Execute and return the results
            return $query->get();
    }

    protected function getReceiveSales($empId, $from, $to)
    {
        $query = DB::table('receive_sales')
            ->join('task', 'receive_sales.task_id', '=', 'task.id')
            ->join('employee', 'task.empoyee_id', '=', 'employee.id')
            ->whereBetween('task.created_at', [$from, $to])
            ->select(
                'receive_sales.*',
                'task.task_number',
                'employee.employee_number',
                'employee.first_name',
                'employee.last_name',
                'employee.phone'
            );

            if ($empId) {
                $query->where('task.empoyee_id', '=', $empId); // Apply employee filter if ID is present
            }
        
            // Execute and return the results
            return $query->get();
    }

    protected function generatePdf($product_out, $sums, $x, $demage, $payments, $from, $to, $employee)
    {
        $total_qty = $total_amt = $total_return_qty = $total_return_amt = $total_due = $total_retail_amt = 0;

        foreach ($sums as $sum) {
            $total_qty += $sum->sum_qty;
            $total_amt += $sum->sum_amt;
            $total_retail_amt += $sum->sum_retail_amt;
            $total_return_qty += $sum->sum_return_qty;
            $total_return_amt += $sum->sum_return_amt;
            $total_due += (($total_retail_amt + $total_amt)-$payments);
        }

        // Pass the grand totals to the PDF view
        $pdf = PDF::loadView('task.export_task', [
            'from' => $from,
            'to' => $to,
            'x' => $x,
            'employee' => $employee,
            'loggedInUser' => Auth::user(),
            'dates' => $sums[0]->date, // Use date from the first item or modify as needed
            'count' => count($product_out),
            'product_out' => $product_out,
            'sum_qty' => $total_qty, // Total sum of qty
            'sum_amt' => $total_amt+$total_retail_amt, // Total sum of amt
            'sum_return_qty' => $total_return_qty, // Total sum of returned qty
            'sum_return_amt' => $total_return_amt, // Total sum of returned amt
            'sum_due' => $total_due,
            'dispatch' => implode(', ', array_column($sums->toArray(), 'dispatch')), // Combine all dispatches
            'demage' => $demage,
            'sum_recive' => $payments,
        ]);


        return $pdf->setPaper('A4', 'landscape')->stream('Dispatch.pdf');
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function single_report($id)
    {
        // Fetch all necessary data in a single query
        $product_outs = DB::table('sales')
            ->join('task', 'task.id', '=', 'sales.task_id')
            ->leftJoin('receive_sales', 'task.id', '=', 'receive_sales.task_id') // Changed to leftJoin
            ->join('employee', 'employee.id', '=', 'task.empoyee_id')
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->where('task.id', '=', $id)
            ->select(
                'sales.*',
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


        // Calculate total payments in a single query
        $payments = DB::table('receive_sales')
            ->where('task_id', '=', $id)
            ->sum('amount');

        // Preload demages by product to avoid redundant queries
        $demagesByProduct = DB::table('product_demage')
            ->whereIn('product_id', $product_outs->pluck('product_id'))
            ->where('task_id', $id)
            ->get()
            ->groupBy('product_id');

        // Process each product
        $product_out = [];
        $pded = 0;

        foreach ($product_outs as $key => $value) {
            $ppay = $payments - $pded;
            $value->demages = 0;
            $value->amount_paid = 0;

            // Determine start and end dates
            if ($key == 0) $start = $value->created_at;
            if ($key == sizeof($product_outs) - 1) $end = $value->created_at;

            // Get demages for the current product
            $demages = $demagesByProduct[$value->product_id] ?? collect();

            if ($demages->isNotEmpty()) {
                $demage = $demages->first();
                $value->amt += ($value->sub_total - $value->amt);
                $value->amount_paid = ($ppay > $value->amt) ? $value->amt : max($ppay, 0);
                $value->demage_qty = $demage->qty;
                $value->demages += $demage->amt;
                $pded += $value->amt;
            } else {
                $value->amount_paid = ($ppay > $value->amt) ? $value->amt : max($ppay, 0);
                $pded += $value->amt;
            }

            $product_out[] = $value;
        }

        // Fetch demage records
        $demage = DB::table('product_demage')
            ->join('task', 'task.id', '=', 'product_demage.task_id')
            ->join('employee', 'employee.id', '=', 'task.empoyee_id')
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

        // Fetch sums and necessary data
        $task = DB::table('task')->where('task.id', '=', $id)->first();
        $sums = DB::table('task')
            ->leftJoin(DB::raw('(SELECT task_id, SUM(qty) as sum_qty, SUM(retail) as sum_retail, SUM(bulk) as sum_bulk, SUM(amt) as sum_amt, SUM(retail_amt) as sum_retail_amt, MIN(created_at) as created_at FROM sales GROUP BY task_id) as sales_summary'), 'sales_summary.task_id', '=', 'task.id')
            ->leftJoin(DB::raw('(SELECT task_id, SUM(qty) as sum_return_qty, SUM(amt) as sum_return_amt FROM stock_return GROUP BY task_id) as stock_return_summary'), 'stock_return_summary.task_id', '=', 'task.id')
            ->leftJoin(DB::raw('(SELECT task_id, SUM(qty) as sum_demage_qty, SUM(amt) as sum_demage_amt FROM product_demage GROUP BY task_id) as demage_summary'), 'demage_summary.task_id', '=', 'task.id')
            ->leftJoin(DB::raw('(SELECT task_id, SUM(amount) as sum_receive FROM receive_sales GROUP BY task_id) as receive_sales_summary'), 'receive_sales_summary.task_id', '=', 'task.id')
            ->leftJoin('employee', 'employee.id', '=', 'task.empoyee_id')
            ->where('task.id', '=', $id)
            ->selectRaw('
            CONCAT(employee.first_name, " ", employee.last_name) as employee_name,
            task.task_number as dispatch,
            sales_summary.created_at as date,
            sales_summary.sum_qty,
            sales_summary.sum_retail,
            sales_summary.sum_bulk,
            sales_summary.sum_amt,
            sales_summary.sum_retail_amt,
            stock_return_summary.sum_return_qty,
            stock_return_summary.sum_return_amt,
            task.returned as sum_return,
            task.amount_due as sum_due,
            demage_summary.sum_demage_qty,
            demage_summary.sum_demage_amt,
            receive_sales_summary.sum_receive
        ')
            ->first();

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
        $start = null;
        // Generate the PDF
        $pdf = PDF::loadView('task.single_report', [
            'loggedInUser' => Auth::User(),
            'dates' => $sums->date,
            'count' => count($product_out),
            'product_out' => $product_out,
            'sum_qty' => $sums->sum_qty,
            'sum_retail' => $sums->sum_retail,
            'sum_bulk' => $sums->sum_bulk,
            'sum_amt' => $sums->sum_retail_amt + $sums->sum_amt,
            'sum_return_qty' => $sums->sum_return_qty,
            'sum_return_amt' => $sums->sum_return_amt,
            'sum_return' => $sums->sum_return,
            'sum_due' => ($sums->sum_retail_amt + $sums->sum_amt) - $payments,
            'x' => $x,
            'demage' => $demage,
            'employee' => $sums->employee_name,
            'dispatch' => $sums->dispatch,
            'sum_recive' => $payments,
            'sum_demage_qty' => $sums->sum_demage_qty,
            'sum_demage_amt' => $sums->sum_demage_amt
        ]);

        return $pdf->setPaper('A4', 'landscape')->stream('supplier.pdf');
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