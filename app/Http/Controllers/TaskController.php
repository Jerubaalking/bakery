<?php

namespace App\Http\Controllers;
use App\Exports\ExportSuppliers;
use App\Imports\SuppliersImport;
use App\Supplier;
use Excel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use PDF;
use Auth;
use Yajra\DataTables\DataTables;
use App\Models\SalesModel;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $empo=DB::table('employee')
                ->get();

        $product=DB::table('products')
                    //->whereNotIn('stock',[0])
                     ->get();

        $account=DB::table('account')
                    ->where('account_group','=','Income')
                     ->get();
        $close_task=DB::table('employee')
                     ->join('task','task.empoyee_id','=','employee.id')
                     ->select('task.*','employee.first_name','employee.employee_number')
                     ->where('amount_due','=','0')
                     ->get();
    
        return view('task.index',compact('empo','product','account','close_task'));
          

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
                'message'    => 'This Task is invalid'
            ]);

         }
        
       else{
         $date=Carbon::now()->format('Y-m-d');
        $item_name=$request->item_name;
        $date_in=$request->date_in;
        $qty=$request->qty;
        $price=$request->price;
        $amt=$request->amt;
        $subtotal=$request->sub_total;
        $supplier_id=$request->supplier_id;
        $product_id=$request->product_id;
        $return_qty='0';
        $return_price='0';
        $return_amt='0';
      
       //  $prove="Not approved";
       $record= DB::table('task')->orderBy('id', 'DESC')->first();
       if(!$record)
       {
     
         $nextTask = 'TASK'.'-0001';
       
       }
      if($record){
         
     $expNum = explode('-', $record->task_number);
     $increments=($expNum[1]+1);
      //increase 1 with last invoice number
        $nextTask = $expNum[0].'-'.sprintf("%04d",$increments);
    
      }
        $form_datas=array(

            'sub_total'=>round($subtotal,2),
            'empoyee_id'=>$supplier_id,
            'created_at'=>$date,
            'amount_paid'=>'0',
            'amount_due'=>$subtotal,
            'task_number'=>$nextTask,
            'returned'=>$return_amt

           
       );
        $get_id=DB::table('task')->insertGetId($form_datas);
        for($count=0; $count < count($qty);$count++){
           $form_data[]= array(
            'task_id'=>$get_id,
            'product_id' =>$product_id[$count],
            'qty'  =>$qty[$count],
            'price'  =>round($price[$count],2),
            'amt'=>round($price[$count]*$qty[$count],2),
            'return_qty'  =>$return_qty,
            'return_price'  =>$return_price,
            'return_amt'  =>$return_amt,
            'created_at'  =>$date_in,
           
           );
           DB::table('products')->where('id', $product_id[$count])->decrement('stock', $qty[$count]);
       }
      $invoice=DB::table('sales')->insert($form_data);
     //  SalesModel::create($form_data);    
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
        //
        if(request()->ajax()){
        $data=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->join('sales','task.id','=','sales.task_id')
        ->join('products','sales.product_id','=','products.id')
        ->select('task.*','employee.first_name','employee.employee_number','sales.qty','sales.amt',
        'products.product_name','sales.price','products.stock','sales.product_id')
        ->where('task.id','=',$id)
        ->get();
          if($data){  
            return response()->json(['data' => $data]);
            }
            return view('casual.staffing.index',comapact('data'));
            }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
             //
             $date=Carbon::now()->format('Y-m-d');
             $item_name=$request->item_name;
             $date_in=$request->date_in;
             $qty=$request->qty;
             $price=$request->price;
             $id=$request->id;
             $amt=$request->amt;
             $subtotal=$request->sub_total;
             $supplier_id=$request->supplier_id;
             $product_id=$request->product_id;
           
            //  $prove="Not approved";
            
             $form_datas=array(
                 'sub_total'=>round($subtotal,2),
                 'empoyee_id'=>$supplier_id,
                 'created_at'=>$date,
                 'amount_paid'=>'0',
                 'amount_due'=>'0'
                
            );
         
            DB::table('task')
            ->where('id','=',$id)->update($form_datas);
            
             $x=DB::table('sales')
             ->where('task_id','=',$id)
             ->select('product_id','qty')
             ->get()
             ->toArray();
              
             foreach($x as $x){
                 $ids[]=$x->product_id;
                 $qtys[]=$x->qty;
                  for($count=0; $count < count($ids);$count++){
                    $xx= DB::table('products')->whereIn('id',[$ids[$count]])
                    ->increment('stock',$qtys[$count]);
                    }
                }

                for($count=0; $count < count($qty);$count++){
                    $form_data[]= array(
                     'task_id'=>$id,
                     'product_id' =>$product_id[$count],
                     'qty'  =>$qty[$count],
                     'price'  =>round($price[$count],2),
                     'amt'=>round($price[$count]*$qty[$count],2),
                     'created_at'  =>$date_in,
                    
                    );
                    DB::table('products')->where('id', $product_id[$count])->decrement('stock', $qty[$count]);
                }
           
               
            DB::table('sales')->where('task_id','=',$id)->delete();
    
            DB::table('sales')->insert($form_data);
           return response()->json([
               'success'    => true,
               'message'    => 'Information Updated'
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
        //
     

         $x=DB::table('sales')
         ->where('task_id','=',$id)
         ->select('product_id','qty')
         ->get()
         ->toArray();
             foreach($x as $x){
               $ids[]=$x->product_id;
               $qtys[]=$x->qty;
             
            }  

           $task=  DB::table('task')
            ->where('id','=',$id)->delete();
            if($task){
                for($count=0; $count < count($ids);$count++){
                    $xx= DB::table('products')->whereIn('id',[$ids[$count]])
                    ->increment('stock',$qtys[$count]);
                 }

            }
        

         
    }

    public function apiTask()
    {
        $task=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->select('task.*','employee.first_name','employee.employee_number')
        ->whereNotIn('amount_due',['0'])
        ->get();
        if(Auth::user()->role=="Superadministrator"){
        return Datatables::of($task)
            ->addColumn('action', function($task){
                return '
                <div class="btn-group" style="width:100%">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Action <span class="caret"></span>
                   </button>
                   <ul class="dropdown-menu">
                        <li><a href="#" class="btn btn-warning btn-xs pays" style="color:white" id="'.$task->id.'"><i class="fa fa-money" style="color:white"></i>Receive Payment</a></li>

                       <li><a onclick="deleteData('. $task->id .')" class="btn btn-danger btn-xs" style="color:white"><i class="glyphicon glyphicon-trash" style="color:white"></i> Delete</a></li>
                       <li><a href="task_info/'.$task->id .'" class="btn btn-success btn-xs more_details" style="color:white" ><i class="glyphicon glyphicon-eye-open" style="color:white"></i>More Details</a></li>
                   </ul>
               </div> ';
            })
            ->editColumn('demage_cost', function($task){
     
                return '<div class="text-warning">'.number_format($task->demage_cost,2).'</div>';
            })
            ->editColumn('amount_due', function($task){
     
                return '<div class="text-danger">'.number_format($task->amount_due,2).'</div>';
            })
            ->editColumn('amount_paid', function($task){
     
                return '<div class="text-success">'.number_format($task->amount_paid,2).'</div>';
            })
            ->editColumn('returned', function($task){
     
                return '<div class="text-primary">'.number_format($task->returned,2).'</div>';
            })
            ->editColumn('sub_total', function($task){
     
                return '<div class="text-primary">'.number_format($task->sub_total,2).'</div>';
            })
            ->escapeColumns([])
            ->rawColumns(['action'])->make(true);
        }else{
            return Datatables::of($task)
            ->addColumn('action', function($task){
                return '
                <div class="btn-group" style="width:100%">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Action <span class="caret"></span>
                   </button>
                   <ul class="dropdown-menu">
                   <li><a href="#" class="btn btn-warning btn-xs pays" style="color:white" id="'.$task->id.'"><i class="fa fa-money" style="color:white"></i>Receive Payment</a></li>

                       <li><a href="task_info/'.$task->id .'" class="btn btn-success btn-xs more_details" style="color:white" ><i class="glyphicon glyphicon-eye-open" style="color:white"></i>More Details</a></li>
                   </ul>
               </div> ';
            })
            ->editColumn('demage_cost', function($task){
     
                return '<div class="text-warning">'.number_format($task->demage_cost,2).'</div>';
            })
            ->editColumn('amount_due', function($task){
     
                return '<div class="text-danger">'.number_format($task->amount_due,2).'</div>';
            })
            ->editColumn('amount_paid', function($task){
     
                return '<div class="text-success">'.number_format($task->amount_paid,2).'</div>';
            })
            ->editColumn('returned', function($task){
     
                return '<div class="text-primary">'.number_format($task->returned,2).'</div>';
            })
            ->editColumn('sub_total', function($task){
     
                return '<div class="text-primary">'.number_format($task->sub_total,2).'</div>';
            })
            ->escapeColumns([])
            ->rawColumns(['action'])->make(true);
        }
    }
    public function apiTask1()
    {
        $task=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->select('task.*','employee.first_name','employee.employee_number')
        ->where('amount_due','=','0')
        ->get();

        return Datatables::of($task)
            ->addColumn('action', function($task){
                return '
                <div class="btn-group" style="width:100%">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       Action <span class="caret"></span>
                   </button>
                   <ul class="dropdown-menu">
                   <li>
                   <li><a href="#" class="btn btn-warning btn-xs pays" style="color:white" id="'.$task->id.'"><i class="fa fa-money" style="color:white"></i>Receive Payment</a></li>

                       <li><a href="task_info/'.$task->id .'" class="btn btn-success btn-xs more_details" style="color:white" ><i class="glyphicon glyphicon-eye-open" style="color:white"></i>More Details</a></li>
                   </ul>
               </div> ';
            })
            ->editColumn('demage_cost', function($task){
     
                return '<div class="text-warning">'.number_format($task->demage_cost,2).'</div>';
            })
            ->editColumn('amount_due', function($task){
     
                return '<div class="text-danger">'.number_format($task->amount_due,2).'</div>';
            })
            ->editColumn('amount_paid', function($task){
     
                return '<div class="text-success">'.number_format($task->amount_paid,2).'</div>';
            })
            ->editColumn('returned', function($task){
     
                return '<div class="text-primary">'.number_format($task->returned,2).'</div>';
            })
            ->editColumn('sub_total', function($task){
     
                return '<div class="text-primary">'.number_format($task->sub_total,2).'</div>';
            })
            ->escapeColumns([])
            ->rawColumns(['action'])->make(true);
    }

//                       <li><a href="#" class="btn btn-info btn-xs view" style="color:white" id="'.$task->id.'" ><i class="glyphicon glyphicon-eye-open" style="color:white"></i> Show</a></li>
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function stockReturn($id)
    {
        //
      
        if(request()->ajax()){
        $data=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->join('sales','task.id','=','sales.task_id')
        ->join('products','sales.product_id','=','products.id')
        ->select('sales.*','employee.first_name','employee.employee_number','employee.phone','employee.last_name','sales.qty','task.empoyee_id','sales.amt',
        'products.product_name','sales.price','products.stock','sales.product_id')
        ->where('task.id','=',$id)
        ->whereNotIn('sales.qty',['0'])
        
        ->get();
          if($data){  
            return response()->json(['data' => $data]);
            }
            return view('casual.staffing.index',comapact('data'));
            }
    }

        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function amount_due($id)
    {
        //
        if(request()->ajax()){
        $data=DB::table('task')
        ->where('id','=',$id) 
        ->get();
          if($data){  
            return response()->json(['data' => $data]);
            }
        
            }
    }
/**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function task_info($id){
        $data=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->join('sales','task.id','=','sales.task_id')
        ->join('products','sales.product_id','=','products.id')
        ->select('task.*','employee.first_name','employee.employee_number','sales.qty','sales.amt',
        'products.product_name','sales.price','products.stock','employee.last_name',
        'sales.product_id','return_qty','return_price','return_amt')
        ->where('task.id','=',$id)
        ->get();
        $pay=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->join('receive_sales','receive_sales.task_id','=','task.id')
        ->select('receive_sales.*','employee.first_name',
        'employee.employee_number','task.task_number')
        ->where('task.id','=',$id)
        ->get();
        $return_task=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->join('product_demage','task.id','=','product_demage.task_id')
        ->join('products','products.id','=','product_demage.product_id')
        ->select('product_demage.*','employee.first_name','employee.employee_number',
        'products.product_name','products.stock',
        'task.task_number')
        ->where('product_demage.task_id','=',$id)
        ->get();
        $demage_product=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->join('stock_return','task.id','=','stock_return.task_id')
        ->join('products','products.id','=','stock_return.product_id')
        ->select('stock_return.*','employee.first_name','employee.employee_number',
        'products.product_name','products.stock',
        'task.task_number')
        ->where('stock_return.task_id','=',$id)
        ->get();

        $empo_number=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->where('task.id','=',$id)
        ->get();
        $emp_number=$empo_number[0]->employee_number;

        $close_task=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->where('employee.employee_number','=',$emp_number)
        ->where('task.amount_due','=','0')
        ->get();

        return view('task.task_info',compact('data','pay','return_task','close_task','demage_product','id'));
        
    }
 /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */   
    public function exportTask(Request $request)
    {
        
        $from=$request->from;
        $to=$request->to;
        $product_out=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->join('sales','task.id','=','sales.task_id')
        ->join('products','sales.product_id','=','products.id')
        ->whereBetween('task.created_at',array($request->from,$request->to))
        ->select('sales.*','products.product_name','task.created_at','employee.employee_number','employee.first_name',
        'employee.phone','employee.last_name',)
        ->get();
        $count=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->join('sales','task.id','=','sales.task_id')
        ->join('products','sales.product_id','=','products.id')
        ->whereBetween('task.created_at',array($request->from,$request->to))
        ->select('sales.*','products.product_name','task.created_at','employee.employee_number','employee.first_name',
        'employee.phone')
        ->count();
        $sum_qty=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->join('sales','task.id','=','sales.task_id')
     
        ->whereBetween('task.created_at',array($request->from,$request->to))
       
        ->sum('sales.qty');

        $sum_amt=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->join('sales','task.id','=','sales.task_id')
     
        ->whereBetween('task.created_at',array($request->from,$request->to))
     
        ->sum('sales.amt');

        $sum_return_qty=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->join('sales','task.id','=','sales.task_id')

        ->whereBetween('task.created_at',array($request->from,$request->to))
       
        ->sum('sales.return_qty');

        $sum_return_amt=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->join('sales','task.id','=','sales.task_id')
        ->join('products','sales.product_id','=','products.id')
        ->whereBetween('task.created_at',array($request->from,$request->to))
        ->select('sales.*','products.product_name','task.created_at','employee.employee_number','employee.first_name',
        'employee.phone')
        ->sum('sales.return_amt');

        $sum_return=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
    
        ->whereBetween('task.created_at',array($request->from,$request->to))
      
        ->sum('task.returned');
        $sum_demage=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
    
        ->whereBetween('task.created_at',array($request->from,$request->to))
      
        ->sum('task.demage_cost');

        $sum_due=DB::table('task')
      
        ->whereBetween('created_at',array($request->from,$request->to))
   
        ->sum('amount_due');

        $sum_sub=DB::table('task')
        ->whereBetween('created_at',array($request->from,$request->to))
        ->sum('sub_total');

        $sum_recive=DB::table('task')
        ->whereBetween('task.created_at',array($request->from,$request->to))
      
        ->sum('amount_paid');



        $pdf = PDF::loadView('task.export_task',compact('count','product_out','from','to','sum_qty','sum_amt',
        'sum_return_qty','sum_return_amt','sum_return','sum_due','sum_recive','sum_sub','sum_due','sum_demage'));
        
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('supplier.pdf');
    }


     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */   
    public function single_report($id)
    {
        
    
        $product_out=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->join('sales','task.id','=','sales.task_id')
        ->join('products','sales.product_id','=','products.id')
        ->where('task.id','=',$id)
        ->select('sales.*','products.product_name','task.created_at','employee.employee_number',
        'employee.first_name','employee.last_name',
        'employee.phone')
        ->get();
        $x=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->join('stock_return','task.id','=','stock_return.task_id')
        ->join('products','stock_return.product_id','=','products.id')
        ->where('task.id','=',$id)
        ->select('stock_return.*','products.product_name','task.created_at','employee.employee_number',
        'employee.first_name','employee.last_name',
        'employee.phone')
        ->get();

        $demage=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->join('product_demage','task.id','=','product_demage.task_id')
        ->join('products','product_demage.product_id','=','products.id')
        ->where('task.id','=',$id)
        ->select('product_demage.*','products.product_name','task.created_at','employee.employee_number',
        'employee.first_name','employee.last_name',
        'employee.phone')
        ->get();

        $count=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->join('sales','task.id','=','sales.task_id')
        ->join('products','sales.product_id','=','products.id')
        ->where('task.id','=',$id)
        ->select('sales.*','products.product_name','task.created_at','employee.employee_number','employee.first_name',
        'employee.phone')
        ->count();
        $sum_qty=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->join('sales','task.id','=','sales.task_id')
     
        ->where('task.id','=',$id)
       
        ->sum('sales.qty');

        $sum_amt=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->join('sales','task.id','=','sales.task_id')
        ->where('task.id','=',$id)
        ->sum('sales.amt');

        $sum_return_qty=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->join('stock_return','task.id','=','stock_return.task_id')

        ->where('task.id','=',$id)
       
        ->sum('stock_return.qty');

        $sum_return_amt=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->join('stock_return','task.id','=','stock_return.task_id')
        ->join('products','stock_return.product_id','=','products.id')
        ->where('task.id','=',$id)
        ->select('stock_return.*','products.product_name','task.created_at','employee.employee_number','employee.first_name',
        'employee.phone')
        ->sum('stock_return.amt');

        $sum_demage_qty=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->join('product_demage','task.id','=','product_demage.task_id')
        ->where('task.id','=',$id)
        ->sum('product_demage.qty');

        $sum_demage_amt=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
        ->join('product_demage','task.id','=','product_demage.task_id')
        ->join('products','product_demage.product_id','=','products.id')
        ->where('task.id','=',$id)
        ->select('product_demage.*','products.product_name','task.created_at','employee.employee_number','employee.first_name',
        'employee.phone')
        ->sum('product_demage.amt');

        $sum_return=DB::table('employee')
        ->join('task','task.empoyee_id','=','employee.id')
    
        ->where('task.id','=',$id)
      
        ->sum('task.returned');

        $sum_due=DB::table('task')
        ->where('id','=',$id)
   
        ->sum('amount_due');

        $sum_sub=DB::table('task')
        ->where('id','=',$id)
        ->sum('sub_total');

        $sum_recive=DB::table('task')
        ->where('id','=',$id)
      
        ->sum('amount_paid');



        $pdf = PDF::loadView('task.single_report',compact('count','product_out','sum_qty','sum_amt',
        'sum_return_qty','sum_return_amt','sum_return','sum_due','demage',
        'sum_recive','sum_sub','sum_due','x','sum_demage_qty','sum_demage_amt'));
        
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('supplier.pdf');
    }

    public function exportProductKeluar($id)
    {
        $product_keluar = Product_Keluar::findOrFail($id);
        $pdf = PDF::loadView('product_keluar.productKeluarPDF', compact('product_keluar'));
        return $pdf->download($product_keluar->id.'_product_keluar.pdf');
    }

public function infoApi($id){
    $task=DB::table('employee')
    ->join('task','task.empoyee_id','=','employee.id')
    ->join('sales','task.id','=','sales.task_id')
    ->join('products','sales.product_id','=','products.id')
    ->select('task.*','employee.first_name','employee.last_name','employee.employee_number','sales.id as sales_id','sales.qty','sales.amt',
    'products.product_name','sales.price','products.stock',
    'sales.product_id','return_qty','return_price','return_amt')
    ->where('task.id','=',$id)
    ->get();
    return Datatables::of($task)
        ->addColumn('action', function($task){
            return '
            <div class="btn-group" style="width:100%">
               <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   Action <span class="caret"></span>
               </button>
               <ul class="dropdown-menu">
           
               <li><a onclick="returnForm('. $task->sales_id .')" class="btn btn-danger btn-xs" style="color:white"><i class="fa fa-undo"  style="color:white"></i>Return Stock</a></li>
                    <li><a onclick="demageForm('. $task->sales_id .')" class="btn btn-primary btn-xs" style="color:white"><i class="fa fa-undo"  style="color:white"></i>Demage Products</a></li>
                
               </ul>
           </div> ';
        })
        ->editColumn('demage_cost', function($task){
 
            return '<div class="text-warning">'.number_format($task->demage_cost,2).'</div>';
        })
        ->editColumn('amount_due', function($task){
 
            return '<div class="text-danger">'.number_format($task->amount_due,2).'</div>';
        })
        ->editColumn('amount_paid', function($task){
 
            return '<div class="text-success">'.number_format($task->amount_paid,2).'</div>';
        })
        ->editColumn('returned', function($task){
 
            return '<div class="text-primary">'.number_format($task->returned,2).'</div>';
        })
        ->editColumn('sub_total', function($task){
 
            return '<div class="text-primary">'.number_format($task->sub_total,2).'</div>';
        })
        ->escapeColumns([])
        ->rawColumns(['action'])->make(true);
    }

   public function demageApi($id){
    $demage_product=DB::table('employee')
    ->join('task','task.empoyee_id','=','employee.id')
    ->join('product_demage','task.id','=','product_demage.task_id')
    ->join('products','products.id','=','product_demage.product_id')
    ->select('product_demage.*','employee.first_name','employee.employee_number',
    'products.product_name','products.stock',
    'task.task_number')
    ->where('product_demage.task_id','=',$id)
    ->get();
    return Datatables::of($demage_product)
    // ->addColumn('action', function($demage_product){
    //     return '
    //     <div class="btn-group" style="width:100%">
    //        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    //            Action <span class="caret"></span>
    //        </button>
    //        <ul class="dropdown-menu">
       
    //        <li><a onclick="returnForm('. $demage_product->id .')" class="btn btn-danger btn-xs" style="color:white"><i class="fa fa-undo"  style="color:white"></i>Return Stock</a></li>
    //             <li><a onclick="demageForm('. $demage_product->id .')" class="btn btn-primary btn-xs" style="color:white"><i class="fa fa-undo"  style="color:white"></i>Demage Products</a></li>
    //            <li><a href="#" class="btn btn-warning btn-xs pays" style="color:white" id="'.$demage_product->id.'"><i class="fa fa-money" style="color:white"></i>Receive Payment</a></li>


    //        </ul>
    //    </div> ';
    // })
 
    ->escapeColumns([])
    ->rawColumns(['action'])->make(true);
   }

   public function returnApi($id){
    $return_task=DB::table('employee')
    ->join('task','task.empoyee_id','=','employee.id')
    ->join('stock_return','task.id','=','stock_return.task_id')
    ->join('products','products.id','=','stock_return.product_id')
    ->select('stock_return.*','employee.first_name','employee.employee_number',
    'products.product_name','products.stock',
    'task.task_number')
    ->where('stock_return.task_id','=',$id)
    ->get();
    return Datatables::of($return_task)
    // ->addColumn('action', function($return_task){
    //     return '
    //     <div class="btn-group" style="width:100%">
    //        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    //            Action <span class="caret"></span>
    //        </button>
    //        <ul class="dropdown-menu">
       
    //        <li><a onclick="returnForm('. $return_task->id .')" class="btn btn-danger btn-xs" style="color:white"><i class="fa fa-undo"  style="color:white"></i>Return Stock</a></li>
    //             <li><a onclick="demageForm('.$return_task->id .')" class="btn btn-primary btn-xs" style="color:white"><i class="fa fa-undo"  style="color:white"></i>Demage Products</a></li>
    //            <li><a href="#" class="btn btn-warning btn-xs pays" style="color:white" id="'.$return_task->id.'"><i class="fa fa-money" style="color:white"></i>Receive Payment</a></li>


    //        </ul>
    //    </div> ';
    // })
 
    ->escapeColumns([])
    ->rawColumns(['action'])->make(true);
   }
}
//        <li><a href="exportProforma/'.$task->id .'" class="btn btn-warning btn-xs pro_invoice" style="color:white" ><i class="fas fa-file-invoice" style="color:white"></i>Proforma Invoice</a></li>
//        <li><a onclick=" editForm('. $task->id .')" class="btn btn-success btn-xs" style="color:white"><i class="glyphicon glyphicon-pencil" style="color:white"></i> Edit</a></li>