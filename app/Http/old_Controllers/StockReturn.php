<?php
namespace App\Http\Controllers;
use App\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class StockReturn extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('stock_return.index');

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
        $validator = Validator::make($request->all(), [
            "return_qty.*" => 'required|integer|min:1',
        ]);
        if ($validator->fails()) 
           { 
            return response()->json([
                'error'    => true,
                'message'    => 'Stock return is Invalid check all fields'
            ]);

         }
        
       else{
        $id=$request->sales_id;

        $get_task=DB::table('sales')->where('id',$id)->first();
        $date_in=$request->date_in;
        $qty=$request->return_qty;
        $subtotal=$request->sub_total;
       
      

    
            $form_data= array(
             'task_id'=>$get_task->task_id,
             'product_id' =>$get_task->product_id,
             'qty'  =>$qty,
             'price' =>$get_task->price,
             'amt'=>round($get_task->price*$qty,2),
             'created_at'  =>$date_in,
            );   
         
             
             DB::table('products')
             ->where('id', $get_task->product_id)
             ->update([
             'stock' => DB::raw('stock + '.$qty),
              ]);

            
          
            DB::table('sales')
            ->where('id', $id)
            ->where('product_id',$get_task->product_id)
            ->update([
            'qty' => DB::raw('qty - '.$qty),
            'amt' => DB::raw('amt - '. $get_task->price*$qty),
            'return_qty' => DB::raw('return_qty + '.$qty),
            'return_amt' => DB::raw('return_amt + '.$get_task->price*$qty),
            'return_price' => DB::raw('return_price + '.$get_task->price),
         
             ]); 
                 

       DB::table('stock_return')->insert($form_data); 
       
       $task=DB::table('task')->where('id',$get_task->task_id)
                      ->first();

        $amount_remain=$task->amount_due-$get_task->price*$qty;
        $amount_returned=$task->returned+$get_task->price*$qty;
        
        

        $update_amount_form=array(
            'amount_due'=>$amount_remain,
            'returned'=>$amount_returned
        );

      DB::table('task')->where('id',$get_task->task_id)
                      ->update($update_amount_form);

   
        return response()->json([
                        'success'    => true,
                        'message'    => 'Quantity Return To Stock'
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
