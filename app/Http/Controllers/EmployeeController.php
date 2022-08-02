<?php

namespace App\Http\Controllers;

use App\Exports\ExportSuppliers;
use App\Imports\SuppliersImport;
use App\Supplier;
use Excel;
use Illuminate\Http\Request;
use PDF;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller {
	public function __construct() {
		
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$position =DB::table('position')->get();
		return view('employee.index',compact('position'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$this->validate($request, [
			'first_name' => 'required',
			'last_name' => 'required',
			'address' => 'required',
			'phone' => 'required',
		]);
		$record= DB::table('employee')->orderBy('id', 'DESC')->first();
        if(!$record)
        {
                 //check first day in a year
        if ( date('l',strtotime(date('Y-01-01'))) ){
           $employee_number= date('Y').'-0001';
          }
        }
       if($record){
          
      $expNum = explode('-', $record->employee_number);
      $increments=($expNum[1]+1);
       //increase 1 with last invoice number
         $employee_number= $expNum[0].'-'.sprintf("%04d",$increments);
     
       }
          $formdata=([
			'position_id' => $request->position_id,
			'first_name' => $request->first_name,
            'last_name' => $request->last_name,
			'gender' => $request->gender,
			'birthday' => $request->birthday,
            'address' => $request->address,
            'phone' => $request->phone,
			'employee_number'=>$employee_number,
		  ]);
		  DB::table('employee')->insert($formdata);

		return response()->json([
			'success' => true,
			'message' => 'Employee Created',
		]);

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		if(request()->ajax()){
         
            $data=DB::table('employee')->find($id);
           
            return response()->json(['data' => $data]);   
        }
        
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		
          $formdata=([
			'position_id' => $request->position_id,
			'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'phone' => $request->phone,
		  ]);
       DB::table('employee')->where('id','=',$id)->update($formdata);
		return response()->json([
			'success' => true,
			'message' => 'Employee Updated',
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
	
    
        DB::table('employee')->where('id', '=', $id)->delete();

		return response()->json([
			'success' => true,
			'message' => 'Employee Delete',
		]);
	}

	public function apiEmployee() {
		$employee =DB::table('department')
		          ->join('position','department.id','=','position.department_id')
				  ->join('employee','position.id','=','employee.position_id')
				  ->select('employee.*','position.position_name','department.department_name')
		          ->get();

		return Datatables::of($employee)
			->addColumn('action', function ($employee) {
				return '<a href="#" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-eye-open"></i> Show</a> ' .
				'<a onclick="editForm(' . $employee->id . ')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
				'<a onclick="deleteData(' . $employee->id . ')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
			})
			->rawColumns(['action'])->make(true);
	}

	public function ImportExcel(Request $request) {
		//Validasi
		$this->validate($request, [
			'file' => 'required|mimes:xls,xlsx',
		]);

		if ($request->hasFile('file')) {
			//UPLOAD FILE
			$file = $request->file('file'); //GET FILE
			Excel::import(new SuppliersImport, $file); //IMPORT FILE
			return redirect()->back()->with(['success' => 'Upload file data suppliers !']);
		}

		return redirect()->back()->with(['error' => 'Please choose file before!']);
	}

	public function exportSuppliersAll() {
		$employee = Supplier::all();
		$pdf = PDF::loadView('suppliers.SuppliersAllPDF', compact('suppliers'));
		return $pdf->download('suppliers.pdf');
	}

	public function exportExcel() {
		return (new ExportSuppliers)->download('suppliers.xlsx');
	}
}
