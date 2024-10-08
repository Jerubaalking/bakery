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

        // if(request()->ajax()){
        $this->validate($request, [
            'amount' => 'required|string',
            'payment_methode' => 'required',
        ]);

        $date = Carbon::now()->format('Y-m-d H:m:s');
        $task_id = $request->task_id_receive;
        info($task_id);
        $taskss = DB::table('task')->where('id', $task_id)->first();
        $amount_paid = $request->amount;
        // $account = DB::table('account')->where('id', '=', $taskss->account_id);
        $payment_form = array(
            'task_id' => $request->task_id_receive,
            'employee_id' => $request->employee_id,
            'account_id' => $taskss->account_id,
            'amount' => $request->amount,
            'payment_methode' => $request->payment_methode,
            'created_at' => $request->payment_date,
            'updated_at' => Carbon::now()->format('Y-m-d H:m:s')

        );
        //    return $request->account_id;

        DB::table('receive_sales')->insert($payment_form);
        $remainder = 0;
        if (sizeof($taskss) > 0) {
            $remainder = intVal($taskss[0]->amount_due) - intVal($amount_paid);
            if ($remainder > 0) {
                DB::table('task')
                    ->where('id', $task_id)
                    ->update([
                        'amount_paid' => DB::raw('amount_paid + ' . $taskss[0]->amount_due),
                        'amount_due' => DB::raw('amount_due -' . $taskss[0]->amount_due)
                    ]);
                $prevs = DB::table('task')
                    ->where('employee_id', $payment_form->employee_id)
                    ->whereDate('created_at', '<', $taskss[0]->created_at)
                    ->get();
                foreach ($prevs as $key => $value) {
                    # code...
                    if (intVal($value->amount_due) >= $remainder) {
                        DB::table('task')
                            ->where('id', $task_id)
                            ->update([
                                'amount_paid' => DB::raw('amount_paid + ' . $remainder),
                                'amount_due' => DB::raw('amount_due -' . $remainder)
                            ]);
                        $reminder = 0;
                        break;
                    } else {
                        DB::table('task')
                            ->where('id', $task_id)
                            ->update([
                                'amount_paid' => DB::raw('amount_paid + ' . intVal($value->amount_due)),
                                'amount_due' => DB::raw('amount_due -' . intVal($value->amount_due))
                            ]);
                        $reminder -= intVal($value->amount_due);
                    }
                }
            }
            return response()->json([
                'success'    => true,
                'message'    => 'Information successfuly added'
            ]);
        } else {
            return response()->json([
                'success'    => false,
                'message'    => 'Task does not exist!'
            ]);
        }
        // }
    }
    public function save_public(Request $request)
    {
        if ($request->ajax()) {
            info('incoming form ===>>', $request->all());

            // Retrieve the task based on the task ID
            $task = DB::table('task')->where('id', $request->task_id_receive)->first();
            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task not found',
                ], 404);
            }

            // Prepare the payment data
            $payment_data = [
                'task_id' => $request->task_id_receive,
                'account_id' => $request->account_id,
                'employee_id' => $request->employee_id,
                'amount' => $request->received_total - $task->amount_paid,
                'payment_methode' => $request->payment_methode,
                'created_at' => $request->payment_date,
                'updated_at' => Carbon::now(),
            ];

            // Insert the payment data into the database
            DB::table('receive_sales')->insert($payment_data);

            // Calculate the remainder and update the task
            $amount_paid = $request->received_total;
            $remainder = $amount_paid - $task->amount_due;

            if ($remainder >= 0) {
                // Update current task with the paid amount and mark as fully paid
                DB::table('task')
                    ->where('id', $task->id)
                    ->update([
                        'amount_paid' => DB::raw('amount_paid + ' . $task->amount_due),
                        'amount_due' => 0,
                    ]);

                // Pay off previous tasks if there's remaining amount
                $previous_tasks = DB::table('task')
                    ->where('empoyee_id', $request->employee_id)
                    ->where('created_at', '<', $task->created_at)
                    ->orderBy('created_at', 'asc')
                    ->get();

                foreach ($previous_tasks as $previous_task) {
                    if ($remainder <= 0) {
                        break;
                    }

                    if ($previous_task->amount_due > 0) {
                        $payment_for_task = min($remainder, $previous_task->amount_due);
                        DB::table('task')
                            ->where('id', $previous_task->id)
                            ->update([
                                'amount_paid' => DB::raw('amount_paid + ' . $payment_for_task),
                                'amount_due' => DB::raw('amount_due - ' . $payment_for_task),
                            ]);
                        $remainder -= $payment_for_task;
                    }
                }
            } else {
                // Partially pay the current task
                DB::table('task')
                    ->where('id', $task->id)
                    ->update([
                        'amount_paid' => DB::raw('amount_paid + ' . $amount_paid),
                        'amount_due' => DB::raw('amount_due - ' . $amount_paid),
                    ]);
            }

            // Update sales table with retail, bulk, amt_retail, and amt for each sale
            foreach ($request->sale_id as $index => $sale_id) {
                // Fetch the retail price from the sales table for the specific sale_id
                $sale = DB::table('sales')->where('id', $sale_id)->first();
            
                // Calculate amt_retail based on the retail price from the sales table
                $amt_retail = $request->retail[$index] * $sale->retail_price;
            
                // Update the sales record with the new values
                DB::table('sales')->where('id', $sale_id)->update([
                    'retail' => $request->retail[$index],
                    'bulk' => $request->bulk[$index],
                    'retail_amt' => $amt_retail,  // Use retail_price from sales table
                    'amt' => $request->amt[$index],
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment and sales successfully recorded',
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
