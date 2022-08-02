<?php
namespace App\Http\Controllers;
use App\Exports\ExportProdukMasuk;
use App\Http\Models\ProductModel;
use App\Http\Models\SupplierModel;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\ProductInModel;
class ProductInController extends Controller

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
        //
        $into_stores=DB::table('into_store')
        ->get();

        $batches=DB::table('into_store')
        ->where('status','=', 'process')
        ->join('materials','materials.id','=','into_store.material_id')
        ->orderBy('into_store.batch_number', 'DESC')
        ->select('into_store.batch_number','into_store.qty','materials.unit_cost','into_store.updated_at as manufacture_date')
        ->selectRaw('(into_store.qty * materials.unit_cost) as material_value')
        ->get();
        $BatchOut=DB::table('into_store')
        ->where('status','=', 'process')
        ->groupBy('batch_number')
        ->select('into_store.batch_number')
        ->get();
        
        $materials = DB::table('materials')
        ->get();
        $cat= DB::table('categories')
        ->get();
        $products_in =DB::table('product_in')
        ->join('products','products.id','=','product_in.product_id')
        ->join('categories','categories.id','=','products.category_id')
        ->select('product_in.*','products.product_name','products.category_id', 'categories.cat_name')
        ->get();
        $products_out =DB::table('product_out')
        ->get();
        $invoice_data =DB::table('product_in')
        ->get();
        $products =DB::table('products')->get();
        return view('product_in.index', compact('into_stores','materials', 'batches','BatchOut','products','invoice_data','cat','products_in', 'products_out'));
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

        // try{
            // $this->validate($request, [
            //     'product_id'     => 'required',
            //     'batch_number'     => 'required',
            //     'qty'            => 'required',
            //     'current_stock'            => 'required',
            //     'manufacture_date'        => 'required'
            // ]);
            $batch_number = $request->batch_number;
            $date_in = $request->manufacture_date;
            $product_id = $request->product_id;
            $qty = $request->qty;
            
            foreach ($qty as $i=>$val){
                $form_datas[] = array(
                    'batch_number' => $batch_number,
                    'product_id' => $product_id[$i],
                    'qty' => $qty[$i],
                    'date_in' => $date_in,
                    'created_at' =>Carbon::now(),
                    'updated_at' =>Carbon::now(),
            
                );
            }
        
            $proccess = array(
                'status' => 'finished',
                'updated_at' =>Carbon::now(),
            );
            ProductInModel::insert( $form_datas);

            //DB::table('product_in')->insert($form_datas);

            // $product =DB::table('products')->where('id','=',$request->product_id)->get();
            $processUpdate = DB::table('into_store')
            ->where('batch_number','=',$batch_number)
            ->update($proccess);

            // $x=$request->tlitre;
            // $form_data = array(
            //     'stock'=>$product[0]->stock + $request->qty, 
            //     // 'tlitre'=>$product[0]->tlitre +$x,
            // );
            // DB::table('into_store')->where('id','=',$request->product_id)->update($form_datas);
            // $savedProductIn = ProductInModel::insert($form_datas);
            // \dump ($form_datas);
            return response()->json([
                'success'    => true,
                'message'    => 'Products batch added to stock'
            ]);
        // }catch(\Throwable $th){
        //     return response()->json([
        //         'success' => false,
        //         'message' => json_encode($th)
        //     ]);
        // }

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
      
            $data =DB::table('product_in')->find($id);
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
            'tlitre'         => 'required',
            'date_in'        => 'required'
        ]);
        $form_datas = array(
            'product_id' => $request->product_id,
            'qty' => $request->qty,
            'price' => $request->price,
            'tprice' => $request->tprice,
            'tlitre' => '1',
            'date_in' => $request->date_in,
    
        );
        $product_inV=DB::table('product_in')->select('qty','price','tprice','tlitre')->where('id','=',$id)->get();
        $product=DB::table('products')->select('qty','tlitre')->where('id','=',$request->product_id)
        ->get();
        $x=$product[0]->qty-$product_inV[0]->qty;
        $tlitre=$product[0]->tlitre-$product_inV[0]->tlitre;
        $y= $request->qty+=$x;
        $mylitre=90;
        $tlitre_update=$mylitre+=$tlitre;
            $product_in=DB::table('product_in')
            ->where('id','=',$id)
            ->update($form_datas);
      
            //$x=$product_in->qty-$product->qty; 
             $myform_data = array(
                 'qty'=>$y,
                 'tlitre'=>$tlitre_update,
             );
       
             DB::table('products')
             ->where('id','=',$request->product_id)->update($myform_data);
             
             return response()->json([
                 'success'    => true,
                 'message'    => 'Product In Updated'
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
        
        $mydata= DB::table('product_in')->find($id);
        $qty=$mydata->qty;
      
        $product_id=$mydata->product_id;

        $getdata=DB::table('products')->where('id','=',$product_id)->get();
        $qtys=$getdata[0]->stock;
    
     
         $remain_qty= $qtys- $qty;
      
        
        $upform=array(
            'stock'=>$remain_qty,
        
            
        );
        
       DB::table('products')->where('id', '=',$product_id)->update($upform);
      

        $productdelete =  ProductInModel::find($id);
        $productdelete->delete();

        return response()->json([
            'success'    => true,
            'message'    => 'Products In Deleted'
        ]);
    }
    public function apiProducts_in(){
        $products=DB::table('products')->join('product_in','product_in.product_id','=','products.id')
        ->select('product_in.*','products.product_name')
        ->orderBy('product_in.id','ASC')
        ->get();
        if(Auth::user()->role=="Superadministrator"){
        return Datatables::of($products)
            ->addColumn('products_name', function ($products){
                return $products->product_name;
            })
           
            ->addColumn('action', function($products){
                return
                    '<a onclick="deleteData('. $products->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a> ';
            })
            ->rawColumns(['products_name','supplier_name','action'])->make(true);

    }
    else{
        return Datatables::of($products)
        ->addColumn('products_name', function ($products){
            return $products->product_name;
        })
       
        ->addColumn('action', function($products){
            return
                '<a onclick="" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a> ';



        })
        ->rawColumns(['products_name','supplier_name','action'])->make(true);  
    }
}

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportProduct_inAll(Request $request)

    {

      
        if($request->product_id1=="all"){
        $from=$request->from;
        $to=$request->to;
        $product_in=DB::table('products')
        ->join('product_in','product_in.product_id','=','products.id')
        ->whereBetween('product_in.date_in',array($request->from,$request->to))
        ->select('product_in.*','products.product_name',)
        ->get();
        $sum=DB::table('products')
        ->join('product_in','product_in.product_id','=','products.id')
        ->whereBetween('product_in.date_in',array($request->from,$request->to))
        ->sum('product_in.qty');
        $pdf = PDF::loadView('product_in.productInAllPDF',compact('product_in','from','to','sum'));
      //  return response()->file($pathToFile);
         return $pdf->stream('product_in.pdf');
        }
        else{
            $from=$request->from;
            $to=$request->to;
            $product_in=DB::table('products')
            ->join('product_in','product_in.product_id','=','products.id')
            ->whereBetween('product_in.date_in',array($request->from,$request->to))
            ->select('product_in.*','products.product_name',)
            ->where('product_in.product_id',$request->product_id1)
            ->get();
            $sum=DB::table('products')
            ->join('product_in','product_in.product_id','=','products.id')
            ->whereBetween('product_in.date_in',array($request->from,$request->to))
            ->where('product_in.product_id',$request->product_id1)
            ->sum('product_in.qty');
            $pdf = PDF::loadView('product_in.productInAllPDF',compact('product_in','from','to','sum'));
          //  return response()->file($pathToFile);
             return $pdf->stream('product_in.pdf');  
        }
      //  return $pdf->file('');
    }

    public function exportProductMasuk($id)
    {
        $product_in = product_in::findOrFail($id);
        $pdf = PDF::loadView('product_in.productMasukPDF', compact('product_in'));
        return $pdf->stream($product_in->id.'_product_in.pdf');
    }

    public function exportExcel()
    {
        return (new ExportProdukMasuk)->stream('product_in.xlsx');
    }

    public function  get_item($id){
        $data=DB::table('products')->where('category_id','=',$id)
       ->get();
       return response()->json([
           'data'    =>$data,
       ]);
 
   }
   public function  get_stock($id){
    $data=DB::table('products')->where('id','=',$id)
   ->get();
   return response()->json([
       'data'    =>$data,
   ]);

}
}
