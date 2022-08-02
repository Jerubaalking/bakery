<?php

namespace App\Http\Controllers;

use App\Category;
use App\Customer;
use App\Exports\ExportProductOut;
use App\Product;
use App\Product_Keluar;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Models\ProductModel;
use App\Models\ProductOutModel;
use Illuminate\Support\Facades\Validator;
use Auth;

class ProductOutController extends Controller
{
    public function __construct()
    {
       
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $account=DB::table('account')
        ->where('status','=','active')
      
        ->where('account_group',['Income'])
        ->get();
        // $customers = DB::table('customers')->get();
  
  
        $categories = DB::table('categories')->get();
        // return $categories;
     
        
        $product=DB::table('products')
                    ->whereNotIn('stock',[0])
                     ->get();

        return view('product_out.index', compact('product','account','categories'));
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
            "price.*" => 'required|integer|min:1',
        ]);
        if ($validator->fails()) 
           { 
            return response()->json([
                'error'    => true,
                'message'    => 'This  is invalid'
            ]);

         }
        
       else{
             //  $prove="Not approved";
       $record= DB::table('product_out')->orderBy('id', 'DESC')->first();
       if(!$record)
       {
     
         $salesNumber= 'SALES'.'-0001';
       
       }
      if($record){
         
     $expNum = explode('-', $record->sales_number);
     $increments=($expNum[1]+1);
      //increase 1 with last invoice number
        $salesNumber= $expNum[0].'-'.sprintf("%04d",$increments);
    
      }
         $date=Carbon::now()->format('Y-m-d');
        $item_name=$request->item_name;
        $date_in=$request->date_in;
        $qty=$request->qty;
        $price=$request->price;
        $amt=$request->amt;
        $subtotal=$request->sub_total;
        $sales_by=$request->sales_by;
        $payment_method=$request->payment_method;
        $product_id=$request->product_id;
        // $return_qty='0';
        // $return_price='0';
        // $return_amt='0';

        $form_datas=array(
            'sub_total'=>round($subtotal,2),
            'payment_method'=>$payment_method,
            'created_at'=>$date,
            'sales_by'=>$sales_by,
            'sales_number'=>$salesNumber,
         
            // 'returned'=>$return_amt

           
       );
        // $product_out=ProductModelOut::create($form_data);
      

        $get_id=DB::table('product_out')->insertGetId($form_datas);
        
        for($count=0; $count < count($qty);$count++){
           $form_data[]= array(
            'product_out_id'=>$get_id,
            'product_id' =>$product_id[$count],
            'qty'  =>$qty[$count],
            'price'  =>round($price[$count],2),
            'amt'=>round($price[$count]*$qty[$count],2),
            // 'return_qty'  =>$return_qty,
            // 'return_price'  =>$return_price,
            // 'return_amt'  =>$return_amt,
            // 'created_at'  =>$date_in,
           
           );
           DB::table('products')->where('id', $product_id[$count])->decrement('stock', $qty[$count]);
       }
     
      


     $invoice=DB::table('sales')->insert($form_data);
       return response()->json([
           'success'    => true,
           'message'    => 'Informatio successfuly added'
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
        if(request()->ajax()){
      
            $data =DB::table('product_out')->find($id);
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
        $this->validate($request, [
            'product_id'     => 'required',
            'qty'            => 'required',
            'price'          => 'required',
            'tprice'         => 'required',
            'litre'          => 'required',
            'tlitre'         => 'required',
            'date_out'        => 'required'
        ]);  
        $form_datas = array(
            'product_id' => $request->product_id,
            'qty' => $request->qty,
            'price' => $request->price,
            'tprice' => $request->tprice,
            'litre' => $request->litre,
            'tlitre' => $request->tlitre,
            'date_out' => $request->date_out,
    
        );
        $product_outV=DB::table('product_out')->select('qty','price','tprice','tlitre')->where('id','=',$id)->get();
        $product=DB::table('products')->select('qty','price','tprice','litre','tlitre')->where('litre','=',$request->litre)
        ->get();
        $x=$product[0]->qty+$product_outV[0]->qty;
        $price= $product[0]->price+$product_outV[0]->price;
        $tprice=$product[0]->tprice+$product_outV[0]->tprice;
         $product_outV[0]->tlitre;
        $y= $x-$request->qty;
        $price_update=$price= $product[0]->price;
        $tprice_update=  $price_update*$y;
        $litre_update=$product[0]->litre;
        $tlitre_update=$y*$litre_update;
     
            $product_in=DB::table('product_out')->where('id','=',$id)
            ->where('litre','=',$request->litre)
            ->update($form_datas);
      
            //$x=$product_in->qty-$product->qty;
            $product =DB::table('products')->where('litre',$request->litre)->get();
            $check_qty=$product[0]->qty;
            if($check_qty=='0'){
                return response()->json([
                    'success'    => true,
                    'message'    => 'Stock is empty'
                ]);  
            }
            if($check_qty<$request->qty){
                return response()->json([
                    'success'    => true,
                    'message'    => 'Stock is less than '
                ]);  
            }
             $myform_data = array(
                 'qty'=>$y,
                 'litre' => $request->litre,
                 'tlitre'=>$tlitre_update,
                 'tprice'=>$tprice_update

             );
             DB::table('products')
             ->where('litre','=',$request->litre)->update($myform_data);
             
            $get_qty=DB::table('products')->get();
            $xx=$get_qty[0]->qty;
          if($xx==0){
            $form_check_qty = array(
                'tlitre'=>0,
                'tprice'=>0,
                'price'=>0,
                 'qty'=>0,

            );
            $product_update_out_qty =DB::table('products')->where('litre','=',$request->litre)
            ->where('litre','=',$request->litre)
            ->update($form_check_qty);
        }
            
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mydata= DB::table('product_out')->find($id);
        $qty=$mydata->qty;
        $product_id=$mydata->product_id;
     
        $getdata=DB::table('products')->where('id','=',$product_id)->get();
        $qtys=$getdata[0]->stock;
        $update_qty= $qtys+$qty;
        
    

        $upform=array(
            'stock'=>$update_qty, 
        );
        
       DB::table('products')->where('id', '=',$product_id)->update($upform);
      

        DB::table('product_out')->where('id', '=', $id)->delete();

        return response()->json([
            'success'    => true,
            'message'    => 'Products Delete Deleted'
        ]);
    }



    public function apiProducts_out(){
        $product=DB::table('product_out')
        ->get();
        if(Auth::user()->role=="Superadministrator"){
        return Datatables::of($product)
            ->addColumn('action', function($product){
                return '
                <div class="btn-group" style="width:100%">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Action <span class="caret"></span>
                   </button>
                   <ul class="dropdown-menu">
                   <li>
                      <li><a onclick="deleteData('. $product->id .')" class="btn btn-danger btn-xs" style="color:white"><i class="glyphicon glyphicon-trash" style="color:white"></i> Delete</a></li>
                       <li><a href="/sales_info/'.$product->id .'" class="btn btn-success btn-xs more_details" style="color:white" ><i class="glyphicon glyphicon-eye-open" style="color:white"></i>More Details</a></li>
                   </ul>
               </div> ';
            })
            ->rawColumns(['products_name','customer_name','action'])->make(true);
        }else{
            return Datatables::of($product)
            ->addColumn('action', function($product){
                return '
                <div class="btn-group" style="width:100%">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Action <span class="caret"></span>
                   </button>
                   <ul class="dropdown-menu">
                   <li>
                       <li><a href="/sales_info/'.$product->id .'" class="btn btn-success btn-xs more_details" style="color:white" ><i class="glyphicon glyphicon-eye-open" style="color:white"></i>More Details</a></li>
                   </ul>
               </div> ';
            })
            ->rawColumns(['products_name','customer_name','action'])->make(true);
        }

    }
//   <li><a onclick="returnForm('. $product->id .')" class="btn btn-danger btn-xs" style="color:white"><i class="fa fa-undo"  style="color:white"></i>Return Stock</a></li>
//   <li><a onclick="deleteData('. $product->id .')" class="btn btn-danger btn-xs" style="color:white"><i class="glyphicon glyphicon-trash" style="color:white"></i> Delete</a></li>
//<li><a href="#" class="btn btn-warning btn-xs pays" style="color:white" id="'.$product->id.'"><i class="fa fa-money" style="color:white"></i>Receive Payment</a></li>
  /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */   
public function exportProduct_OutAll(Request $request)
    {
        
        $from=$request->from;
        $to=$request->to;
        $product_out=DB::table('product_out')
        ->join('sales','product_out.id','=','sales.product_out_id')
        ->join('products','sales.product_id','=','products.id')
        ->whereBetween('product_out.created_at',array($request->from,$request->to))
        ->select('sales.*','products.product_name','product_out.sub_total','product_out.created_at')
        ->get();
        $sum_qty=DB::table('product_out')
        ->join('sales','product_out.id','=','sales.product_out_id')
        ->join('products','sales.product_id','=','products.id')
        ->whereBetween('product_out.created_at',array($request->from,$request->to))
        ->sum('sales.qty');
        $sum_amt=DB::table('product_out')
        ->join('sales','product_out.id','=','sales.product_out_id')
        ->join('products','sales.product_id','=','products.id')
        ->whereBetween('product_out.created_at',array($request->from,$request->to))
        ->sum('sales.amt');
        $pdf = PDF::loadView('product_out.productoutAllPDF',compact('product_out','from','to','sum_qty','sum_amt'));
        return $pdf->stream('product_out.pdf');
    }

    public function exportProductKeluar($id)
    {
        $product_keluar = Product_Keluar::findOrFail($id);
        $pdf = PDF::loadView('product_keluar.productKeluarPDF', compact('product_keluar'));
        return $pdf->download($product_keluar->id.'_product_keluar.pdf');
    }

    public function exportExcel()
    {
        return (new ExportProductOut)->download('product_out.xlsx');
    }

    public function sales_info($id){
        $data=DB::table('product_out')
      
        ->join('sales','product_out.id','=','sales.product_out_id')
        ->join('products','sales.product_id','=','products.id')
        ->select('product_out.*','sales.qty','sales.amt',
        'products.product_name','sales.price','products.stock',
        'sales.product_id')
        ->where('product_out.id','=',$id)
        ->get();
      

        return view('product_out.sales_info',compact('data'));
        
    }
}
