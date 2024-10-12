<?php

namespace App\Http\Controllers;

use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;

class DesignationController extends Controller
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
        $accounts = DB::table('account')->get();
        $departments = DB::table('department')->get();
        return view('designation.index', compact('accounts', 'departments'));
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
        $newAccount = $request->all();
        info('new account designation store -->'.$newAccount);
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
        info('deignation edit here --->'.$id);
        if (request()->ajax()) {
            $currentAccount = DB::table('account')
                ->where('id', $id)
                ->first();
            return response()->json(['data' => $currentAccount]);
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
        $updatedAccount = $request->all();

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

    public function apiDesignation()
    {
        $account = DB::table('account')
            ->join('employee', 'employee.account_id', '=', 'account.id')
            ->select('account.*', 'employee.id as employee_id')
            ->get();

        return Datatables::of($account)
            ->addColumn('action', function ($account) {
                return '
           <div class="btn-group" style="width: 100%;">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Action <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a href="#" class="btn btn-default btn-xs edit" onclick="editForm()" data-account-id="{{ account.id }}" data-employee-id="{{ account.employee_id }}">
                        <i class="fa fa-edit text-info"></i> Edit 
                    </a>
                </li>
                <li>
                    <a href="#" class="btn btn-default btn-xs damageForm" data-account-id="{{ account.id }}">
                        <i class="glyphicon glyphicon-trash text-danger"></i> delete
                    </a>
                </li>
                <li>
                    <a href="account_info/{{ account.id }}" class="btn btn-default btn-xs more_details">
                        <i class="fa fa-file-pdf text-success font-medium"></i> Report
                    </a>
                </li>
            </ul>
            </div>';
            })
            ->rawColumns(['action'])->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function empo_info($id)
    {
        //
        info('empo_info is run account id --->>' . $id);
        if (request()->ajax()) {
            $data = DB::table('employee')
                ->where('employee.account_id', '=', $id)
                ->get();
            if ($data) {
                return response()->json(['data' => $data]);
            }
        }
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function designation_report(Request $request)
    {

        $from = $request->from;
        $to = $request->to;
        $designation = DB::table('employee')
            ->join('task', 'task.empoyee_id', '=', 'employee.id')
            ->whereBetween('task.created_at', array($request->from, $request->to))
            ->select(
                'task.created_at',
                'task.task_number',
                'employee.employee_number',
                'employee.first_name',
                'task.sub_total',
                'task.amount_paid',
                'task.returned',
                'task.amount_due',
                'employee.phone'
            )
            ->where('task.empoyee_id', '=', $request->id)
            ->get();

        $count = DB::table('task')
            ->where('empoyee_id', '=', $request->id)
            ->count();



        $sum_paid = DB::table('task')
            ->where('empoyee_id', '=', $request->id)
            ->sum('amount_paid');

        $sum_return = DB::table('task')
            ->where('empoyee_id', '=', $request->id)
            ->sum('returned');

        $sum_due = DB::table('task')
            ->where('empoyee_id', '=', $request->id)
            ->sum('amount_due');

        $sum_sub = DB::table('task')
            ->where('empoyee_id', '=', $request->id)
            ->sum('sub_total');

        $sum_recive = DB::table('task')
            ->where('empoyee_id', '=', $request->id)
            ->sum('amount_paid');




        $pdf = PDF::loadView('designation.report', compact(
            'count',
            'designation',
            'from',
            'to',
            'sum_return',
            'sum_due',
            'sum_recive',
            'sum_sub'
        ));
        return $pdf->stream('designation.pdf');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function check($id)
    {
        //
        if (request()->ajax()) {
            $data = DB::table('task')->where('empoyee_id', '=', $id)
                ->count();
            return response()->json(['data' => $data]);
        }
    }
}
