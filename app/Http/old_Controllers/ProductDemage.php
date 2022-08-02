<?php

namespace App\Http\Controllers;
use App\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Yajra\DataTables\DataTables;
use App\Models\DemageModel;
use Illuminate\Support\Facades\Validator;
class ProductDemage extends Controller

{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
           
        $product=DB::table('products')
                    ->whereNotIn('stock',[0])
                     ->get();
        return view('demage_products.index',compact('product'));

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
        if($request->normal=="normal"){
              //
        $validator = Validator::make($request->all(), [
            "price.*" => 'required|integer|min:1',
      
            "qty.*" => 'required|integer|min:1',
        ]);
        if ($validator->fails()) 
           { 
            return response()->json([
                'error'    => true,
                'message'    => 'Stock return is Invalid check all fields'
            ]);

         }
        
       else{
        $date=Carbon::now()->format('Y-m-d');
        $item_name=$request->item_name;
        $date_in=$request->date_in;
        $sales_id=$request->sales_id;
        $qty=$request->qty;
        $price=$request->price;
        $amt=$request->amt;
        $subtotal=$request->sub_total;
        $id=$request->task_id;
        $product_id=$request->product_id;

       for($count=0; $count < count($qty);$count++){
            $form_data[]= array(
             'product_id' =>$product_id[$count],
             'qty'  =>$qty[$count],
             'price'  =>round($price[$count],2),
             'amt'=>round($price[$count]*$qty[$count],2),
             'created_at'  =>$date,
            );   
         
             
            //  DB::table('products')
            //  ->where('id', $product_id[$count])
            //  ->update([
            //  'stock' => DB::raw('stock - '.$qty[$count]),
            //   ]);

            }

          DB::table('product_demage')->insert($form_data); 

         
          return response()->json([
                        'success'    => true,
                        'message'    => 'Demage product Record'
              ]);
               } 
               }
      else{
               //
              
            $validator = Validator::make($request->all(), [
            "qty.*" => 'required|integer|min:1',
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
        $qty=$request->qty;
        $subtotal=$request->sub_total;
    
            $form_data= array(
             'task_id'=>$get_task->task_id,
             'product_id' =>$get_task->product_id,
             'qty'  =>$qty,
             'price' =>$get_task->price,
             'amt'=>round($get_task->price*$qty,2),
             'created_at'  =>$date_in,
            );   
         
             
            //  DB::table('products')
            //  ->where('id', $get_task->product_id)
            //  ->update([
            //  'stock' => DB::raw('stock + '.$qty),
            //   ]);

            
          
            DB::table('sales')
            ->where('id', $id)
            ->where('product_id',$get_task->product_id)
            ->update([
            'qty' => DB::raw('qty - '.$qty),
            'amt' => DB::raw('amt - '. $get_task->price*$qty),
           // 'return_qty' => DB::raw('return_qty + '.$qty),
           // 'return_amt' => DB::raw('return_amt + '.$get_task->price*$qty),
          //  'return_price' => DB::raw('return_price + '.$get_task->price),
         
             ]); 
                 

             DB::table('product_demage')->insert($form_data); 
       
              $task=DB::table('task')->where('id',$get_task->task_id)
                      ->first();

        $amount_remain=$task->amount_due-$get_task->price*$qty;
        $amount_returned=$task->demage_cost+$get_task->price*$qty;
        
        

        $update_amount_form=array(
            'amount_due'=>$amount_remain,
            'demage_cost'=>$amount_returned
        );

      DB::table('task')->where('id',$get_task->task_id)
                      ->update($update_amount_form);

   
        return response()->json([
                        'success'    => true,
                        'message'    => 'Demage product Record'
       ]);
    }
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

    public function apiDemage()
    {
        $product=DB::table('products')
        ->join('product_demage','product_demage.product_id','=','products.id')
        ->select('product_demage.*','products.product_name')
        ->get();

        return Datatables::of($product)
            ->addColumn('action', function($product){
                return '
                <div class="btn-group" style="width:100%">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Action <span class="caret"></span>
                   </button>
                   <ul class="dropdown-menu">
                   <li>
                   <li><a href="single_report/'.$product->id .'" class="btn btn-warning btn-xs " style="color:white" ><i class="fa fa-file" style="color:white"></i>Report</a></li>
                  
                       <li><a href="product_info/'.$product->id .'" class="btn btn-success btn-xs more_details" style="color:white" ><i class="glyphicon glyphicon-eye-open" style="color:white"></i>More Details</a></li>
                   </ul>
               </div> ';
            })
            ->rawColumns(['action'])->make(true);
    }


      /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */   
      public function exportDemage(Request $request)
       {
        $from=$request->from;
        $to=$request->to;
        $product=DB::table('products')
        ->join('product_demage','product_demage.product_id','=','products.id')
        ->whereBetween('product_demage.created_at',array($request->from,$request->to))
        ->select('product_demage.*','products.product_name',)
        ->get();

        $sum_qty=DB::table('products')
        ->join('product_demage','product_demage.product_id','=','products.id')
        ->whereBetween('product_demage.created_at',array($request->from,$request->to))
        ->select('product_demage.*','products.product_name',)
        ->sum('product_demage.qty');

        $sum_amt=DB::table('products')
        ->join('product_demage','product_demage.product_id','=','products.id')
        ->whereBetween('product_demage.created_at',array($request->from,$request->to))
        ->select('product_demage.*','products.product_name',)
        ->sum('product_demage.amt');

        $pdf = PDF::loadView('demage_products.demage_report',compact('product','sum_qty','sum_amt','from','to'));
        
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('demage.pdf');
    }
}