<?php

namespace App\Http\Controllers;

use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;

class DesignationController extends Controller
{
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
        $employees = DB::table('employee')->get();
        return view('designation.index', compact('accounts', 'departments', 'employees'));
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

    public function apiDesignation()
    {
        $employee = DB::table('account')
            ->join('employee', 'employee.account_id', '=', 'account.id')
            ->join('position', 'employee.position_id', '=', 'position.id')
            ->select('account.id','account.account_name','employee.*', 'employee.id as employee_id')
            ->get();

        return Datatables::of($employee)
            ->addColumn('action', function ($employee) {
                return
                    '<!--<a onclick="exportReport(' . $employee->employee_id . ')" class="btn btn-primary btn-xs"><i class="fa fa-file"></i>Report</a> -->';
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
        if (request()->ajax()) {
            $data = DB::table('employee')
                ->where('id', '=', $id)
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
