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
    
    public function store1(Request $request, $id)
    {
        
        if(request()->ajax()){
                $date=Carbon::now()->format('Y-m-d');
                $task_id=$id;
                $item_name=$request->item_name;
                $date_in=$request->date_in;
                $sales_id=$request->sales_id;
                $qty=$request->damage_qty;
                $price=$request->price;
                $amt=$request->amt;
                $subtotal=$request->sub_total;
                $id=$request->task_id;
                $product_id=$request->product_id;
                info($request);
                foreach ($qty as $count => $value) {
                    if($qty[$count]>0){
                        $demages = DB::table('product_demage')->where('sales_id', $sales_id[$count])->get();
                        if(sizeof($demages)<=0){  
                            $form_data[]= array(
                                'task_id'=>$task_id,
                                'sales_id'=>$sales_id[$count],
                                'product_id' =>$product_id[$count],
                                'qty'  =>$qty[$count],
                                'price'  =>round($price[$count],2),
                                'amt'=>round($price[$count]*$qty[$count],2),
                                'sales_id'  =>$sales_id[$count],
                                'created_at'=>Carbon::now(),
                            ); 
                            DB::table('product_demage')->insert($form_data); 
                            DB::table('sales')
                            ->where('id', $sales_id[$count])
                            ->update([
                            'return_qty' => DB::raw('return_qty + '.intVal($qty[$count])),
                            'return_amt' => DB::raw('return_amt + '.round($price[$count]*$qty[$count],2)),
                            ]); 
                            DB::table('task')
                            ->where('id', $task_id)
                            ->update([
                                'demage_cost' => DB::raw('demage_cost + '.(intVal($qty[$count])*intVal($price[$count]))),
                                'amount_due' => DB::raw('amount_due - '.(intVal($qty[$count])*intVal($price[$count]))),
                            ]);
                        }else{
                            
                            $dqty = $demages[0]->qty;
                            info($demages[0]->qty);
                            DB::table('product_demage')
                            ->where('sales_id', $sales_id[$count])
                            ->update([
                                'qty'=>DB::raw('qty + '.$qty[$count]-$dqty),
                                'amt'=>DB::raw('amt + '.round($price[$count]*($qty[$count]-$dqty),2))
                            ]);
                            DB::table('sales')
                            ->where('id', $sales_id[$count])
                            ->update([
                            'return_qty' => DB::raw('return_qty + '.intVal($qty[$count])-$dqty),
                            'return_amt' => DB::raw('return_amt + '.round($price[$count]*($qty[$count]-$dqty),2)),
                            ]); 
                            DB::table('task')
                            ->where('id', $task_id)
                            ->update([
                                'demage_cost' => DB::raw('demage_cost + '.(intVal($qty[$count]-$dqty)*intVal($price[$count]))),
                                'amount_due' => DB::raw('amount_due - '.round(intVal($price[$count])*intVal($qty[$count]-$dqty), 2)),
                            ]);
                        }
                    }
                }
                
                info($task_id);
                return response()->json([
                            'success'    => true,
                            'message'    => 'Demaged product recorded successfully'
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