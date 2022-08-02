<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\ExpensiveNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\ProductionSessionModel;
use App\Models\MaterialModel;
use App\Models\MaterialCategoryModel;
use App\Models\MeasurementModel;
use App\Models\IntoStoreModel;
use Auth;
use PDF;

class ProductionSessionController extends Controller
{
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
        $products_out =DB::table('stock_return')
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
    public function get_manufacture_details($id)
    {
        $data=DB::table('into_store')
        ->where('batch_number','=',$id)
        ->where('status','=','process')
        ->join('materials','materials.id','=','into_store.material_id')
        ->selectRaw('sum(into_store.qty * materials.unit_cost) as sumPerQtyTotals')
        ->selectRaw('sum(into_store.qty) as sumQty')
        ->get();
        return response()->json($data);
    }
    public function get_process_batches()
    {
        // $data=DB::table('into_store')
        // ->where('status','=','process')
        // ->join('materials','materials.id','=','into_store.material_id')
        // ->selectRaw('sum(into_store.qty * materials.unit_cost) as sumPerQtyTotals')
        // ->selectRaw('sum(into_store.qty) as sumQty')
        // ->get();
        $BatchOut=DB::table('into_store')
        ->where('status','=', 'process')
        ->groupBy('batch_number')
        ->select('into_store.batch_number')
        ->selectRaw('sum(into_store.qty) as material_value')
        ->get();
        return response()->json($BatchOut);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try {
            //code...
            $this->validate($request, [
            
                'name' => 'required',
                'symbol' => 'required',
            
            ]);

        
                $form_data1 = array(
                    'name'=>$request->name,
                    'symbol'=>$request->symbol,
                    'type' =>$request->type,
                    'description'=>$request->description, 
                    'create_at'=> Carbon::now()
                );
            
    
            ProductionSessionModel::create($form_data1);
            return response()->json([
                'success' => true,
                'message' => 'Measurement Created',
            ]);
        }
        catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th,
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
          //
           //
           if(request()->ajax()){
            $data =DB::table('measurements')->find($id);
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
            try {
                //code...
                $this->validate($request, [
                
                    'name' => 'required',
                    'symbol' => 'required',
                
                ]);
    
            
                    $form_data1 = array(
                        'name'=>$request->name,
                        'symbol'=>$request->symbol,
                        'type' =>$request->type,
                        'description'=>$request->description, 
                        'updated_at'=> Carbon::now()
                    );
                
                    

                DB::table('measurements')->where('id','=',$id)->update($form_data1);
                return response()->json([
                    'success' => true,
                    'message' => 'Measurement updated!',
                ]);
            }
            catch (\Throwable $th) {
                //throw $th;
                return response()->json([
                    'success' => false,
                    'message' => $th,
                ]);
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
        try{
            $exp_delete = ProductionSessionModel::find($id);
            $exp_delete->delete();
            return response()->json([
                'success' => true,
                'message' => 'Measurement  Deleted'
            ]);
        }catch(\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => "".$th
            ]);
        }
    }
    public function apiProdctionSession(){
        $productionSession=DB::table('production_sessions')
        ->get();
        if(Auth::user()->role=="Superadministrator"){
        return Datatables::of($productionSession)
            ->addColumn('action', function($productionSession){
                return '
                <div class="btn-group" style="width:100%">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Action <span class="caret"></span>
                   </button>
                   <ul class="dropdown-menu">
                   <li>
                   <li><a onclick="editForm('. $productionSession->id .')" class="btn btn-info btn-xs" style="color:white"><i class="glyphicon glyphicon-edit" style="color:white"></i> edit</a></li>
                      <li><a onclick="deleteData('. $productionSession->id .')" class="btn btn-danger btn-xs" style="color:white"><i class="glyphicon glyphicon-trash" style="color:white"></i> Delete</a></li>
                       <li><a href="/sales_info/'.$productionSession->id .'" class="btn btn-success btn-xs more_details" style="color:white" ><i class="glyphicon glyphicon-eye-open" style="color:white"></i>More Details</a></li>
                   </ul>
               </div> ';
            })
            ->rawColumns(['name','symbol','action'])->make(true);
        }else{
            return Datatables::of($productionSession)
            ->addColumn('action', function($productionSession){
                return '
                <div class="btn-group" style="width:100%">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Action <span class="caret"></span>
                   </button>
                   <ul class="dropdown-menu">
                   <li>
                       <li><a href="/sales_info/'.$productionSession->id .'" class="btn btn-success btn-xs more_details" style="color:white" ><i class="glyphicon glyphicon-eye-open" style="color:white"></i>More Details</a></li>
                   </ul>
               </div> ';
            })
            ->rawColumns(['name','symbol','action'])->make(true);
        }

    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportexpenses(Request $request)
    {
        //

          $from=$request->from;
          $to=$request->to;
          $account_id=$request->expenses_id;
            
          if($request->expenses_id=="all"){ 
         
            $exp= DB::table('account')
               ->join('expensive','account.id','expensive.account_id')
               ->whereBetween('expensive.expensive_date',array($request->from,$request->to))
               ->select('expensive.*','account.account_name')
               ->get();  
              $sum_amount=
              DB::table('account')
              ->join('expensive','account.id','expensive.account_id')
             ->whereBetween('expensive.expensive_date',array($request->from,$request->to))
             ->where('account.status','=','active')
             ->sum('expensive.amount');

             $pdf = PDF::loadView('expensive.report',compact('from','to','exp','sum_amount'));
               return $pdf->stream('report.pdf');
               }
  
            else{
             $exp= DB::table('account')
             ->join('expensive','account.id','expensive.account_id')
            ->where('account.id',$account_id)
            ->where('account.status','=','active')
            ->whereBetween('expensive.expensive_date',array($request->from,$request->to))
            ->select('expensive.*','account.account_name')
            ->get();  
         $sum_amount=
         DB::table('account')
             ->join('expensive','account.id','expensive.account_id')
            ->where('account.id',$account_id)
             ->whereBetween('expensive.expensive_date',array($request->from,$request->to))
      
            ->where('account.id',$account_id)
             ->sum('expensive.amount');
        $pdf = PDF::loadView('expensive.report',compact('from','to','exp','sum_amount'));
           return $pdf->stream('report.pdf');
            }
         
     
        //  $exp= DB::table('expensive')
        //         ->whereBetween('created_at',array($request->from,$request->to))
        //         ->get();
        //   $sum_amount= DB::table('expensive')->whereBetween('created_at',array($request->from,$request->to))
        //            ->sum('amount');
        //  $pdf = PDF::loadView('expensive.report',compact('from','to','exp','sum_amount'));
        //     return $pdf->stream('report.pdf');
    
      
    }


}
