<?php

namespace App\Http\Controllers;
use App\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\ProductModel;
use PDF;
use Auth;

class ProductController extends Controller
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
        $category=DB::table('categories')->get();
        $producs =DB::table('products')->get();
        return view('products.index', ['category'=>$category]);
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
        $date=Carbon::now()->format('Y-m-d');
        $this->validate($request , [
            'product_name' => 'required|string', 
        ]);

        $stock='0';

        $formdata=array(
            'batch_number' => $request->batch_number,
            'category_id' => $request->category_id,
            'product_name' => $request->product_name,
            'stock'=>$stock,
            'created_at'=>$date,
        );

        ProductModel::create($formdata);
        
       // DB::table('products')->insert($formdata);

        return response()->json([
            'success' => true,
            'message' => 'Products Created'
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
        
        if(request()->ajax()){
      
         $data =DB::table('products')->find($id);
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
        $date=Carbon::now()->format('Y-m-d');
        $this->validate($request , [
            'product_name' => 'required|string',
          
        ]);

        $formdata=array(
            'category_id' => $request->category_id,
            'product_name' => $request->product_name,
            'updated_at'=>$date,
        );

        ProductModel::find($request->id)
        ->update(['product_name' => $request->product_name,
            'category_id'=>$request->category_id]);

        //   DB::table('products')->where('id','=',$id)->update($formdata);
       

        return response()->json([
            'success' => true,
            'message' => 'Products Update'
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
     

        $productdelete =  ProductModel::find($id);
        $productdelete->delete();

        return response()->json([
            'success' => true,
            'message' => 'Products Deleted'
        ]);
    }

/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function  checkStock($id)
    {
        
        //$data=DB::table('products')->where('id', '=', $id)->get();
        $products=DB::table('products')
                ->where('id', '=', $id)
                ->get();
        $product = array();
        foreach ($products as $data) {
            $product_in=DB::table('product_in')
            ->where('product_id','=', $data->id)->sum('qty');
            $product_sales =DB::table('sales')
            ->where('product_id','=', $data->id)->sum('qty');
            $available = $product_in - $product_sales;
            $data->available = $available;
            
            array_push($product, $data);
        }

        return response()->json(['data' => $product]);    
    }


    public function apiProducts(){
        $product=DB::table('categories')
        ->join('products','products.category_id','=','categories.id')
        ->get();
        if(Auth::user()->role=="Superadministrator"){
        return Datatables::of($product)
            ->addColumn('action', function($product){
                return 
                    '<a onclick="editForm('. $product->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
                    '<a onclick="deleteData('. $product->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->rawColumns(['category_name','show_photo','action'])
     
            ->make(true);
        }else{
            return Datatables::of($product)
            ->addColumn('action', function($product){
                return 
                    '<a onclick="" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
                    '<a onclick="" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->rawColumns(['category_name','show_photo','action'])
            ->make(true);
        }
    }

    public function stockExport(Request $request)
    {
        
        $from=$request->from;
        $to=$request->to;
        $product=DB::table('products')
        ->get();
        $sum_stock=DB::table('products')
        ->sum('stock');
        $pdf = PDF::loadView('products.stoctexport',compact('product','from','to','sum_stock'));
        return $pdf->stream('stock.pdf');
    }

  
    
}
