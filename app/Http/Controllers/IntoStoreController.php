<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\ExpensiveNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\MeasurementModel;
use App\Models\MaterialModel;
use App\Models\IntoStoreModel;
use App\Models\OutStoreModel;
use Auth;
use PDF;

class IntoStoreController extends Controller
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
        $measurements=DB::table('measurements')
        ->get();
        $material_categories=DB::table('material_categories')
        ->get();
        
        $batches = $this->generateUniqueNumber();
        $materials=DB::table('materials')
        ->join('measurements','measurements.id','=','materials.measurement_id' )
        ->join('material_categories', 'material_categories.id', '=', 'materials.material_category_id')
        ->select('materials.*', 'measurements.measurement','measurements.symbol', 'material_categories.category_name','material_categories.type')
        ->get();

        return view('intoStore.index',compact('into_stores', 'materials', 'measurements', 'material_categories', 'batches'));
    }

    public function show(){
         //
         $into_stores=DB::table('into_store')
         ->join('materials', 'materials.id', '=', 'into_store.material_id')
         ->join('material_categories','material_categories.id','=','materials.material_category_id')
         ->join('measurements','measurements.id','=','materials.measurement_id')
         ->select('into_store.*', 'materials.name','material_categories.type', 'materials.unit_cost','materials.material_category_id', 'materials.measurement_id', 'measurements.measurement', 'measurements.symbol')
         ->get();
         return json_encode($into_stores);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        // $into_stores=DB::table('into_store')
        // ->get();
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
        // try {
            //code...
            
                $material_id=$request->input('material_id', []);
                $qty=$request->input('qty',[]);
                $comments=$request->input('comments',[]);
                $receipt=$request->input('receipt',[]);
                $date=$request->input('date');
                $batch_number = $request->input('batch_number');
                // do
                // {
                //     $token = $this->getToken(6, $application_id);
                //     $code = 'EN'. $token . substr(strftime("%Y", time()),2);
                //     $batch = User::where('user_code', $code)->first();
                // }
                // while(!empty($user_code));

            foreach ($qty as $i=>$val){

                $material = MaterialModel::find($material_id[$i]);
                $unit_cost = $material->unit_cost;
                $form_data1[] = array(
                    'material_id'=>$material_id[$i],
                    'qty'=>$qty[$i],
                    'unit_price'=>$unit_cost,
                    'cost'=>$unit_cost*$qty[$i],
                    'status' =>'in',
                    'comments' =>$comments[$i],
                    'receipt' =>$receipt[$i],
                    'date'=>$date,
                    'batch_number'=> $batch_number,
                    'created_at'=> Carbon::now(),
                    'updated_at'=> Carbon::now()
                );
            }

            try{
                $nas = IntoStoreModel::insert($form_data1);
                return response()->json([
                    'success' => true,
                    'message' => 'Material added to store!',
                ]);
            }catch(\Throwable $th){
                return response()->json([
                    'success' => false,
                    'message' => json_encode($th)
                ]);
            }
                
            
    }
      /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function apiUseStore(Request $request)
    {
        //
        // try {
            //code...
            
                $material_id=$request->input('material_id', []);
                $qty=$request->input('qty',[]);
                $comments=$request->input('comments',[]);
                $date=$request->input('date');
                $batch_number = $request->input('batch_number');
                // do
                // {
                //     $token = $this->getToken(6, $application_id);
                //     $code = 'EN'. $token . substr(strftime("%Y", time()),2);
                //     $batch = User::where('user_code', $code)->first();
                // }
                // while(!empty($user_code));
             foreach ($qty as $i=>$val){
                $material = MaterialModel::find($material_id[$i]);
                $unit_cost = $material->unit_cost;
                $form_data1[] = array(
                    'material_id'=>$material_id[$i],
                    'qty'=>$qty[$i],
                    'unit_price'=>$unit_cost,
                    'cost'=>$unit_cost*$qty[$i],
                    'status'=>'process',
                    'comments' =>$comments[$i],
                    'date'=>$date,
                    'batch_number'=> $batch_number,
                    'created_at'=> Carbon::now(),
                    'updated_at'=> Carbon::now()
                );
            }

            try{
                $nas = IntoStoreModel::insert($form_data1);
                return response()->json([
                    'success' => true,
                    'message' => 'Material used from store!',
                ]);
            }catch(\Throwable $th){
                return response()->json([
                    'success' => false,
                    'message' => json_encode($th)
                ]);
            }
               
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function generateUniqueNumber()
    {
        do {
            $token = random_int(0001, 9999);
            $code = 'BCH'. $token . substr(strftime("%Y%m%d%H%M%S", time()),2);
        } while (IntoStoreModel::where("batch_number", "=", $code)->first());
  
        return $code;
    }

    // public function show($id)
    // {
    //     //
    // }

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
            $data =DB::table('into_store')->find($id);
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
                
                    

                DB::table('into_stores')->where('id','=',$id)->update($form_data1);
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
            $exp_delete = IntoStoreModel::find($id);
            $exp_delete->delete();
            return response()->json([
                'success' => true,
                'message' => 'Input Deleted'
            ]);
        }catch(\Throwable $th){
            return response()->json([
                'success' => false,
                'message' => "".$th
            ]);
        }
    }
    public function showByDates(Request $request){

        $end=$request->input('end');
        $start=$request->input('start');
        $status=$request->input('status');
        info($status);
        if(!$status){
            $into_store=DB::table('into_store')
            ->whereBetween('into_store.created_at', [$start, $end])
            ->join('materials', 'materials.id', '=', 'into_store.material_id')
            ->select('into_store.*', 'materials.name', 'materials.unit_cost', 'materials.material_category_id','materials.measurement_id','measurements.measurement', 'measurements.symbol','material_categories.category_name','material_categories.type')
            ->join('measurements', 'measurements.id','=','materials.measurement_id')
            ->join('material_categories', 'material_categories.id','=','materials.material_category_id')
            ->selectRaw('into_store.qty * materials.unit_cost as sam')
            ->orderBy('created_at', 'DESC')
            ->get();
            // $out_store=DB::table('into_store')
            // ->whereBetween('into_store.created_at', [$start, $end])
            // ->where('status', '=', $status)
            // ->join('materials', 'materials.id', '=', 'into_store.material_id')
            // ->select('into_store.*', 'materials.name', 'materials.unit_cost', 'materials.material_category_id','materials.measurement_id','measurements.measurement', 'measurements.symbol','material_categories.category_name','material_categories.type')
            // ->join('measurements', 'measurements.id','=','materials.measurement_id')
            // ->join('material_categories', 'material_categories.id','=','materials.material_category_id')
            // ->selectRaw('into_store.qty * materials.unit_cost as sam')
            // ->orderBy('created_at', 'DESC')
            // ->get();
        }else{
        
        $into_store=DB::table('into_store')
        ->whereBetween('into_store.created_at', [$start, $end])
        ->where('status', '=', $status)
        ->join('materials', 'materials.id', '=', 'into_store.material_id')
        ->select('into_store.*', 'materials.name', 'materials.unit_cost', 'materials.material_category_id','materials.measurement_id','measurements.measurement', 'measurements.symbol','material_categories.category_name','material_categories.type')
        ->join('measurements', 'measurements.id','=','materials.measurement_id')
        ->join('material_categories', 'material_categories.id','=','materials.material_category_id')
        ->selectRaw('into_store.qty * materials.unit_cost as sam')
        ->orderBy('created_at', 'DESC')
        ->get();
        }
        return json_encode([$into_store]);
    }
    public function apiIntoStore(Request $request){

        $end=$request->input('end');
        $start=$request->input('start');
        $status=$request->input('status');
        info($status);
        if(!$status){
            $into_store=DB::table('into_store')
            ->whereBetween('into_store.created_at', [$start, $end])
            ->join('materials', 'materials.id', '=', 'into_store.material_id')
            ->select('into_store.*', 'materials.name', 'materials.unit_cost', 'materials.material_category_id','materials.measurement_id','measurements.measurement', 'measurements.symbol','material_categories.category_name','material_categories.type')
            ->join('measurements', 'measurements.id','=','materials.measurement_id')
            ->join('material_categories', 'material_categories.id','=','materials.material_category_id')
            ->selectRaw('into_store.qty * materials.unit_cost as sam')
            ->orderBy('created_at', 'DESC')
            ->get();
        }else{
            $into_store=DB::table('into_store')
            ->whereBetween('into_store.created_at', [$start, $end])
            ->where('into_store.status','=',$status)
            ->join('materials', 'materials.id', '=', 'into_store.material_id')
            ->select('into_store.*', 'materials.name', 'materials.unit_cost', 'materials.material_category_id','materials.measurement_id','measurements.measurement', 'measurements.symbol','material_categories.category_name','material_categories.type')
            ->join('measurements', 'measurements.id','=','materials.measurement_id')
            ->join('material_categories', 'material_categories.id','=','materials.material_category_id')
            ->selectRaw('into_store.qty * materials.unit_cost as sam')
            ->orderBy('created_at', 'DESC')
            ->get();
        }
        

        if(Auth::user()->role=="Superadministrator"){
        return Datatables::of($into_store)
            ->addColumn('action', function($into_store){
                return '
                <div class="btn-group" style="width:100%">
                 <a onclick="deleteData('. $into_store->id .')" class="btn btn-danger btn-xs" style="color:white"><i class="glyphicon glyphicon-trash" style="color:white"></i> Delete</a>
               </div> ';
            })
            ->rawColumns(['qty','symbol','action'])->make(true);
        }else{
            return Datatables::of($into_store)
            ->addColumn('action', function($into_store){
                return '
                <div class="btn-group" style="width:100%">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Action <span class="caret"></span>
                   </button>
                   <ul class="dropdown-menu">
                   </ul>
               </div> ';
            })
            ->rawColumns(['qty','symbol','action'])->make(true);
        }

    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportPDF(Request $request)
    {
        //

          $status=$request->input('status');
          $from=$request->input('start');
          $to=$request->end;
            
            info($from);
            if(!$status){
                $status = 'all';
                $data=DB::table('into_store')
                ->whereBetween('into_store.created_at', [$from, $to])
                ->join('materials', 'materials.id', '=', 'into_store.material_id')
                ->select('into_store.*', 'materials.name', 'materials.unit_cost', 'materials.material_category_id','materials.measurement_id','measurements.measurement', 'measurements.symbol','material_categories.category_name','material_categories.type')
                ->join('measurements', 'measurements.id','=','materials.measurement_id')
                ->join('material_categories', 'material_categories.id','=','materials.material_category_id')
                ->selectRaw('into_store.qty * materials.unit_cost as sam')
                ->orderBy('created_at', 'DESC')
                ->get();
            }else{
                $data=DB::table('into_store')
                ->whereBetween('into_store.created_at', [$from, $to])
                ->where('into_store.status','=',$status)
                ->join('materials', 'materials.id', '=', 'into_store.material_id')
                ->select('into_store.*', 'materials.name', 'materials.unit_cost', 'materials.material_category_id','materials.measurement_id','measurements.measurement', 'measurements.symbol','material_categories.category_name','material_categories.type')
                ->join('measurements', 'measurements.id','=','materials.measurement_id')
                ->join('material_categories', 'material_categories.id','=','materials.material_category_id')
                ->selectRaw('into_store.qty * materials.unit_cost as sam')
                ->orderBy('created_at', 'DESC')
                ->get();
            }
             $pdf = PDF::loadView('intoStore.exportPDF',compact('from','to','status','data' ));
            
             return $pdf->stream();
    
      
    }


}
