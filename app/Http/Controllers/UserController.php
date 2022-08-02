<?php

namespace App\Http\Controllers;
use App\User;
use Excel;
use Illuminate\Http\Request;
use PDF;
use Yajra\DataTables\DataTables;
use App\Models\UserModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
	    return view('user.index');
		
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
			'user_name' => 'required',
			'email' => 'required',
		
			'role' => 'required',
		
		]);
			$form_data = array(
				'name' => $request->user_name,
				'email' => $request->email,
				'phone' => $request->phone,
				'role' => $request->role,
				'password' => Hash::make('misana')	
			);
         DB::table('users')->insert($form_data);

		return response()->json([
			'success' => true,
			'message' => 'User Created',
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
            $data =DB::table('users')->find($id);
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
		$this->validate($request, [
			
			'email' => 'required|string|email|max:255|unique:users',
		]);

	  
		$updated_at= Carbon::now();
    
		$form_data = array(
			'name' => $request->user_name,
			'email' => $request->email,
			'phone' => $request->phone,
			'role' => $request->role,
			'password' => Hash::make('misana')	
		);

	  DB::table('users')->where('id','=',$id)->update($form_data);
	  return response()->json([
		'success' => true,
		'message' => 'User Email Updated',
	  ]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		// User::destroy($id);
	       DB::table('users')->delete($id);
		return response()->json([
			'success' => true,
			'message' => 'User Delete',
		]);
	}

	public function apiUsers() {
		$users = DB::table('users')->get();

		return Datatables::of($users)
			->addColumn('action', function ($users) {
				return 
				'<a onclick="editForm(' . $users->id . ')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> '.
				'<a onclick="deleteData(' . $users->id . ')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trush"></i> Delete</a> ';

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
		$suppliers = Supplier::all();
		$pdf = PDF::loadView('suppliers.SuppliersAllPDF', compact('suppliers'));
		return $pdf->download('suppliers.pdf');
	}

	public function exportExcel() {
		return (new ExportSuppliers)->download('suppliers.xlsx');
	}
}
