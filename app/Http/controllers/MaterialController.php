<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\ExpensiveNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\MaterialCategoryModel;
use App\Models\MaterialModel;
use Auth;
use PDF;

class MaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $materials=DB::table('materials')
        ->get();

        $measurements=DB::table('measurements')
        ->get();

        $material_categories=DB::table('material_categories')
        ->get();
        return view('materials.index',compact('materials', 'measurements', 'material_categories'));
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
        try {
            //code...
            $this->validate($request, [
            
                'name' => 'required',
                'category_id' => 'required',
                'measurement_id' => 'required',
                'unit_cost' => 'required',
            
            ]);

        
                $form_data1 = array(
                    'name'=>$request->name,
                    'material_category_id'=>$request->category_id,
                    'measurement_id'=>$request->measurement_id,
                    'unit_cost'=>$request->unit_cost,
                    'created_at'=> Carbon::now(),
                    'updated_at'=> Carbon::now()
                );
            
    
            MaterialModel::create($form_data1);
            return response()->json([
                'success' => true,
                'message' => 'Material Created!',
            ]);
        }
        catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' =>json_encode($th),
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
            $data =DB::table('materials')->where('materials.id','=',$id)
            ->join('measurements','measurements.id','=','materials.measurement_id')
            ->join('material_categories','material_categories.id','=','materials.material_category_id')
            ->select('materials.*', 'measurements.measurement', 'material_categories.category_name','material_categories.type')
            ->get();
            return response()->json($data);   
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
        // dump("am here::");
            //
            try {
                //code...
                // $this->validate($request, [
                
                //     'name' => 'required',
                //     'type' => 'required',
                
                // ]);
    
            
                    $form_data1 = array(
                        'name'=>$request->name,
                        'material_category_id'=>$request->category_id,
                        'measurement_id'=>$request->measurement_id,
                        'unit_cost'=>$request->unit_cost,
                        'updated_at'=> Carbon::now()
                    );
                
                    

                DB::table('materials')->where('id','=',$id)->update($form_data1);
                return response()->json([
                    'success' => true,
                    'message' => 'Material Category updated!',
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
            $exp_delete = MaterialModel::find($id);
            $exp_delete->delete();
            return response()->json([
                'success' => true,
                'message' => 'Material Category  Deleted'
            ]);
        }catch(\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => "".$th
            ]);
        }
    }
        
        
    public function apiMaterial(){
        
        // $materialz=DB::table('materials')
        // ->join('measurements','measurements.id','=','materials.measurement_id')
        // ->join('material_categories','material_categories.id','=','materials.material_category_id')
        // ->select('materials.id','materials.name','materials.unit_cost', 'measurements.symbol','measurements.measurement', 'material_categories.category_name','material_categories.type')
        // ->orderBy('materials.name', 'DESC')
        // ->get();
    
        $materialz=DB::table('materials')
                    ->join('measurements','measurements.id','=','materials.measurement_id')
                    ->join('material_categories','material_categories.id','=','materials.material_category_id')
                    ->select('materials.*', 'measurements.symbol','measurements.measurement', 'material_categories.category_name','material_categories.type')
                    ->get();

                    $into_stores=DB::table('into_store')
                    ->join('materials', 'materials.id', '=', 'into_store.material_id')
                    ->join('material_categories','material_categories.id','=','materials.material_category_id')
                    ->join('measurements','measurements.id','=','materials.measurement_id')
                    ->select('into_store.*', 'materials.name','material_categories.type', 'materials.unit_cost','materials.material_category_id', 'materials.measurement_id', 'measurements.measurement', 'measurements.symbol')
                    ->get();

                    $materials = array();
                    foreach ($materialz as $value) {
                        // info(json_encode($value->id));
                        
                        $inTotal = 0;
                        $outTotal = 0;
                    foreach ($into_stores as $store) {
                        if($store->status == 'in' && $store->material_id == $value->id){
                            $inTotal += intVal($store->qty);
                        }
                        if($store->status != 'in' && $store->material_id == $value->id){
                            $outTotal += intVal($store->qty);
                        }
                    }
                       if($outTotal > $inTotal){
                        $value->available = -($inTotal - $outTotal);
                       } else{
                        $value->available = $inTotal - $outTotal;
                       }
                       $total = $value->available;
                       
            if($value->symbol == "g"){
                if($total>999){
                    $value->available = floatVal($total/1000);
                    $value->symbol = 'kg';
                }
            }
        
               
            $value->available = number_format($value->available, 2).' '. $value->symbol;
            array_push($materials, $value);
        }
          
        if(Auth::user()->role=="Superadministrator"){
        return Datatables::of($materials)
            ->addColumn('action', function($materials){
                return '
                <div class="btn-group" style="width:100%">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Action <span class="caret"></span>
                   </button>
                   <ul class="dropdown-menu">
                   <li>
                   <li><a onclick="editForm('. $materials->id .')" class="btn btn-info btn-xs" style="color:white"><i class="glyphicon glyphicon-edit" style="color:white"></i> edit</a></li>
                      <li><a onclick="deleteData('. $materials->id .')" class="btn btn-danger btn-xs" style="color:white"><i class="glyphicon glyphicon-trash" style="color:white"></i> Delete</a></li>
                       <li><a href="/sales_info/'.$materials->id .'" class="btn btn-success btn-xs more_details" style="color:white" ><i class="glyphicon glyphicon-eye-open" style="color:white"></i>More Details</a></li>
                   </ul>
               </div> ';
            })
            ->rawColumns(['name','type','material_category_id','measurements','unit_cost','created_at','action'])->make(true);
        }else{
            return Datatables::of($materials)
            ->addColumn('action', function($materials){
                return '
                <div class="btn-group" style="width:100%">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Action <span class="caret"></span>
                   </button>
                   <ul class="dropdown-menu">
                   <li>
                       <li><a href="/sales_info/'.$materials->id .'" class="btn btn-success btn-xs more_details" style="color:white" ><i class="glyphicon glyphicon-eye-open" style="color:white"></i>More Details</a></li>
                   </ul>
               </div> ';
            })
            ->rawColumns(['name','type','material_category_id','measurements','unit_cost','created_at','action'])->make(true);
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
    public function materialReport() {
        // Fetch materials with their related measurements and categories
        $materials = DB::table('materials')
            ->join('measurements', 'measurements.id', '=', 'materials.measurement_id')
            ->join('material_categories', 'material_categories.id', '=', 'materials.material_category_id')
            ->select('materials.*', 'measurements.symbol', 'measurements.measurement', 'material_categories.category_name', 'material_categories.type')
            ->get()
            ->map(function ($material) {
                // Calculate in and out totals directly in the query
                $totals = DB::table('into_store')
                    ->selectRaw('SUM(CASE WHEN status = "in" AND material_id = ? THEN qty ELSE 0 END) as inTotal, 
                                 SUM(CASE WHEN status != "in" AND material_id = ? THEN qty ELSE 0 END) as outTotal', [$material->id, $material->id])
                    ->first();
    
                // Calculate available quantity
                $material->available = $totals->outTotal > $totals->inTotal
                    ? -($totals->inTotal - $totals->outTotal)
                    : $totals->inTotal - $totals->outTotal;
    
                return $material;
            });
        $loggedInUser = Auth::User();
        $pdf = PDF::loadView('materials.material_report', compact('loggedInUser','materials'));
        return $pdf->stream();
    }
    

}
