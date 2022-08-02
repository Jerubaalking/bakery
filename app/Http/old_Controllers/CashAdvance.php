<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CashAdvance extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $empo=DB::table('employee')->get();
        $account=DB::table('account')->get();
        return view('cash_advance.index',compact('empo','account'));
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
        
        $date=Carbon::now()->format('Y-m-d');
        $form_data = array(
            'employee_id' => $request->employee_id,
            'amount' => $request->amount,
            'account_id' => $request->account_id,
            'created_at' => $date,
    
        );

        DB::table('cashadvance')->insert($form_data);
        return response()->json([
           'success'    => true,
           'message'    => 'Cash Added'
        ]);
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
    public function apiCash(){
        $cash =DB::table('department')
        ->join('position','department.id','=','position.department_id')
        ->join('employee','position.id','=','employee.position_id')
        ->join('cashadvance','cashadvance.employee_id','=','employee.id')
        ->select('cashadvance.*','employee.employee_number','position.position_name','department.department_name')
        ->get();
        return Datatables::of( $cash)
			->addColumn('action', function ( $cash) {
				return '<a href="#" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-eye-open"></i> Show</a> ' .
				'<a onclick="editForm(' .  $cash->id . ')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
				'<a onclick="deleteData(' .  $cash->id . ')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			})
			->rawColumns(['action'])->make(true);
    }
}
