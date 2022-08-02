<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $account=DB::table('account')
       ->where('account_group','=','Income')
        ->get();
        return view('transfer.index',compact('account'));
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
        $from_account_id=$request->from_account_id;
        $to_account_id=$request->to_account_id;
        $amount_transfer=$request->amount;
        DB::table('account')->where('id', $from_account_id)->decrement('account_balance', $amount_transfer);
        DB::table('account')->where('id', $to_account_id)->increment('account_balance', $amount_transfer);
       
        $get=DB::table('account')->where('id','=',$from_account_id)->get();
        $account_name=$get[0]->account_name;
        $get_to=DB::table('account')->where('id','=',$to_account_id)->get();
        $to_account_name=$get_to[0]->account_name;
   

        $trasfer_form=array(
            'from_account'=>$from_account_id,
            'to_account'=>$to_account_name,
            'amount'=>$amount_transfer,
            'memo'=>$request->memo,
            'date'=>$request->transfer_date,
        );
        DB::table('transfer')->insert($trasfer_form);

        return response()->json([
            'success' => true,
            'message' => 'Transfer Successful',
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

    public function TransferApi(){
        $pay_hist =DB::table('transfer')
        ->join('account','account.id','=','transfer.from_account')
        ->select('transfer.*','account.account_name','account.account_group')
        ->get();
         return Datatables::of($pay_hist)
            ->addColumn('action', function($pay_hist){
                return '<a onclick="deleteData('.$pay_hist->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
                   
            })
            ->rawColumns(['category_name','show_photo','action'])->make(true);

}
}
