<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('account.index');
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
        $this->validate($request, [
            'account_name' => 'required',
            // 'account_group' => 'required',
            // 'staff_type' => 'required',
        ]);

            $created_at= Carbon::now();
            $account_balance=$request->open_balance;
            $form_data = array(
                'account_name'=>$request->account_name,
                 'account_group'=>$request->account_group,
                'account_balance' =>$account_balance,
                'created_at'=>$request->open_date,
                'status'=>'active',
          
            );
        DB::table('account')->insert($form_data);

        return response()->json([
            'success' => true,
            'message' => 'Account Created',
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
           //
           if(request()->ajax()){
            $data =DB::table('account')->find($id);
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
         //
         $this->validate($request, [
            'account_name' => 'required',
            'account_group' => 'required',
          
        ]);

            $updated_at= Carbon::now();
    
            $form_data = array(

                'account_name'=>$request->account_name,
                'account_group'=>$request->account_group,
                 'updated_at'=>$updated_at,
                 'status'=>'active',
          
            );

        DB::table('account')->where('id','=',$id)->update($form_data);
		return response()->json([
			'success' => true,
			'message' => 'Account Updated',
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
      
       DB::table('account')->where('id', '=', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account Deleted'
        ]);
        
    }

        /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activateData($id)
    {
        //
       $get_status= DB::table('account')->where('id', '=',$id)->get();
       $status=$get_status[0]->status;
       if($status=="active"){
           $form_status=array(
               'status'=>'inactive'
           );
           $get_status= DB::table('account')->where('id', '=', $id)->update($form_status); 
       }
       if($status=="inactive"){
        $form_status=array(
            'status'=>'active'
        );
        $get_status= DB::table('account')->where('id', '=', $id)->update($form_status); 
    }


        return response()->json([
            'success' => true,
            'message' => 'Status'
        ]);
        
    }
    public function AccountApi() {
        $account = DB::table('account')
                 ->select('account.*')
         ->get();

        return Datatables::of($account)
            ->addColumn('action', function ($account) {
          
                return
                '<a onclick="editForm(' .$account->id . ')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
                '<a onclick="activateData(' .$account->id . ')" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-trash"></i>activate&deactivate</a>'.
                '<a onclick="deleteData(' .$account->id . ')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i>Delete</a>';
               
            })
            
            ->rawColumns(['action'])->make(true);
    }
}
