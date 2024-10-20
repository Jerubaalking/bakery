<?php

namespace App\Http\Controllers;

use App\Traits\LogsActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Yajra\DataTables\DataTables;

class ReceivePayment extends Controller
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

        // if(request()->ajax()){
        // $this->validate($request, [
        //     'amount' => 'required|string',
        //     'payment_methode' => 'required',
        // ]);
        info('requested payment received ===>>' . $request->all());
        // $date = Carbon::now()->format('Y-m-d H:m:s');
        // $task_id = $request->task_id_receive;
        // info($task_id);
        // $taskss = DB::table('task')->where('id', $task_id)->first();
        // $amount_paid = $request->amount;
        // // $account = DB::table('account')->where('id', '=', $taskss->account_id);
        // $payment_form = array(
        //     'task_id' => $request->task_id_receive,
        //     'employee_id' => $request->employee_id,
        //     'account_id' => $taskss->account_id,
        //     'amount' => $taskss->amount_paid,
        //     'payment_methode' => $request->payment_methode,
        //     'created_at' => $request->payment_date,
        //     'updated_at' => Carbon::now()->format('Y-m-d H:m:s')

        // );
        // //    return $request->account_id;

        // DB::table('receive_sales')->insert($payment_form);
        // $remainder = 0;
        // if (sizeof($taskss) > 0) {
        //     $remainder = intVal($taskss[0]->amount_due) - intVal($amount_paid);
        //     if ($remainder > 0) {
        //         DB::table('task')
        //             ->where('id', $task_id)
        //             ->update([
        //                 'amount_paid' => DB::raw('amount_paid + ' . $taskss[0]->amount_due),
        //                 'amount_due' => DB::raw('amount_due -' . $taskss[0]->amount_due)
        //             ]);
        //         $prevs = DB::table('task')
        //             ->where('employee_id', $payment_form->employee_id)
        //             ->whereDate('created_at', '<', $taskss[0]->created_at)
        //             ->get();
        //         foreach ($prevs as $key => $value) {
        //             # code...
        //             if (intVal($value->amount_due) >= $remainder) {
        //                 DB::table('task')
        //                     ->where('id', $task_id)
        //                     ->update([
        //                         'amount_paid' => DB::raw('amount_paid + ' . $remainder),
        //                         'amount_due' => DB::raw('amount_due -' . $remainder)
        //                     ]);
        //                 $reminder = 0;
        //                 break;
        //             } else {
        //                 DB::table('task')
        //                     ->where('id', $task_id)
        //                     ->update([
        //                         'amount_paid' => DB::raw('amount_paid + ' . intVal($value->amount_due)),
        //                         'amount_due' => DB::raw('amount_due -' . intVal($value->amount_due))
        //                     ]);
        //                 $reminder -= intVal($value->amount_due);
        //             }
        //         }
        //     }
        //     return response()->json([
        //         'success'    => true,
        //         'message'    => 'Information successfuly added'
        //     ]);
        // } else {
        //     return response()->json([
        //         'success'    => false,
        //         'message'    => 'Task does not exist!'
        //     ]);
        // }
        // }
    }

    public function save_public(Request $request)
    {
        $session_id = DB::table('sessions')->where('active','=', true)->first();
        if ($request->ajax()) {
            DB::beginTransaction(); // Start transaction

            try {
                info('Incoming form ===>>', $request->all());

                // Retrieve the task based on the task ID
                $task = DB::table('task')->where('id', $request->task_id_receive)->first();
                if (!$task) {
                    // Log activity for task not found
                    $this->logActivity('Task not found', 'error', 'Task ID: ' . $request->task_id_receive);
                    return response()->json([
                        'success' => false,
                        'message' => 'Task not found',
                    ], 404);
                }

                // Calculate the total from the amt[] in the request
                $total_amt = array_sum($request->amt);
                $total_retial_amt = array_sum($request->retail_amt);

                // Prepare the payment data
                $payment_data = [
                    'task_id' => $request->task_id_receive,
                    'account_id' => $task->account_id,
                    'employee_id' => $task->empoyee_id,
                    'amount' => $request->received_total,
                    'payment_methode' => $request->payment_methode,
                    'session_id'=>$session_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];

                // Insert the payment data into the receive_sales table
                DB::table('receive_sales')->insert($payment_data);
                // Updated sales
                $task_sales = DB::table('sales')->where('task_id', $request->task_id_receive)->get();

                // Use an index to match each sale with the corresponding values from the request arrays
                foreach ($task_sales as $index => $sale) {
                    // Ensure the index exists in the request arrays
                    if (isset($request->retail[$index]) && isset($request->bulk[$index])) {
                        $retail_value = $request->retail[$index];
                        $bulk_value = $request->bulk[$index];

                        // Update the sale with the corresponding retail and bulk values
                        DB::table('sales')
                            ->where('id', $sale->id)  // Update the specific sale by its ID
                            ->update([
                                'updated_at' => Carbon::now(),
                                'retail' => $retail_value,
                                'bulk' => $bulk_value,
                                'retail_amt' => $retail_value * $sale->retail_price,
                                'amt' => $bulk_value * $sale->price,
                            ]);
                    }
                }

                // Log successful payment insertion
                $this->logActivity('Payment recorded', 'success', json_encode($payment_data));

                // Sum the amounts from receive_sales for the current task
                $total_received = DB::table('receive_sales')
                    ->where('task_id', $request->task_id_receive)
                    ->sum('amount');

                // Calculate amount_due as total_amt - total received
                $amount_due = ($total_amt + $total_retial_amt) - $total_received;
                $sub_total = ($total_amt + $total_retial_amt);

                // Update the task's sub_total and amount_due
                $task_data = [
                    'amount_paid' => $total_received,
                    'sub_total' => $sub_total,
                    'amount_due' => max($amount_due, 0),  // Ensuring amount_due does not go negative
                    'updated_at' => Carbon::now(),
                ];

                DB::table('task')->where('id', $request->task_id_receive)->update($task_data);

                info('niko hapa ===>> bb');
                // Log successful task update
                $this->logActivity('Dispatch updated with payment information', 'success', json_encode($task_data));

                DB::commit(); // Commit transaction

                return response()->json([
                    'success' => true,
                    'message' => 'Payment and sales successfully recorded',
                ]);
            } catch (\Exception $e) {
                DB::rollBack(); // Rollback transaction on error

                // Log the error
                $this->logActivity('Error processing payment', 'error', $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while processing your request.',
                ], 500);
            }
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
