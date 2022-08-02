<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $department=DB::table('department')->get();
        return view ('position.index',compact('department'));
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
        $this->validate(request(), [
            'position_name' => 'required|unique:position|max:255'
 
            ]);
    
        $form_data = array(
            'position_name' => $request->position_name,
            'department_id' => $request->department_id,
    
        );

        DB::table('position')->insert($form_data);
        return response()->json([
           'success'    => true,
           'message'    => 'position'
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
        if(request()->ajax()){
         
            $data=DB::table('position')->find($id);
           
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
    public function update(Request $request, $id)
    {
        //
        
        $this->validate($request, [
            'position_name' => 'required|unique:position,position_name,'. $id .'|max:255',
        ]);
        $formdata=array(
            'department_id' => $request->department_id,
            'position_name' => $request->position_name,

        );
        $data= DB::table('position')->where('id',$request->id)->update($formdata);

        return response()->json([
            'success'    => true,
            'message'    => 'position Update'
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
        DB::table('position')->where('id', '=', $id)->delete();
        return response()->json([
            'success'    => true,
            'message'    => 'position Delete'
        ]);
    }

    public function apiPosition()
    {
        $position = DB::table('department')
                   ->join('position','position.department_id','=','department.id')
                   ->select('position.*','department.department_name')
                   ->get();

        return Datatables::of($position)
            ->addColumn('action', function($position){
                return '<a href="#" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-eye-open"></i> Show</a> ' .
                    '<a onclick="editForm('. $position->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
                    '<a onclick="deleteData('. $position->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->rawColumns(['action'])->make(true);
    }
}
