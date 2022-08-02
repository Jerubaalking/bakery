<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
                       $allProducts=DB::table('products')->get();
                       $current_stock =array();
                       $count_stock = 0;
                       foreach ($allProducts as $product) {
                            // $product = json_encode($product->id);
                            $id =$product->id;
                            
                            $total_in=DB::table('product_in')
                            ->where('product_id','=', $product->id)->sum('qty');
                            $total_out=DB::table('sales')
                            ->where('product_id','=', $product->id)->sum('qty');
                            $product->stock = $total_in-$total_out;
                            $count_stock += $product->stock;
                            array_push($current_stock, $product);
                       }

                    $count_suppliers=DB::table('position')
                    ->join('employee','employee.position_id','=','position.id')
                    ->where('position.position_name','=','supplier')
                    ->count();

                    $materialz=DB::table('materials')
                    ->join('measurements','measurements.id','=','materials.measurement_id')
                    ->join('material_categories','material_categories.id','=','materials.material_category_id')
                    ->select('materials.*', 'measurements.symbol','measurements.measurement', 'material_categories.category_name','material_categories.type')
                    ->orderBy('created_at', 'DESC')
                    ->get();
                    $materials = array();
                    foreach ($materialz as $value) {
                        // info(json_encode($value->id));
                        $into_store = DB::table('into_store')
                        ->where('material_id', $value->id)
                        ->where('status', '=','in')
                        ->selectRaw('sum(qty) as intoT')
                        ->get();
            
                        // info(json_encode($into_store));
                        // $value['available'] = $into_store->inT;
                        $out_store = DB::table('into_store')
                        ->where('material_id', $value->id)
                        ->where('status', '!=', 'in')
                        ->selectRaw('sum(qty) as outT')
                        ->get();
                        $inTotal = 0;
                        $outTotal = 0;
                        foreach ($into_store as $ans) {
                            $inTotal += $ans->intoT;
                        }
                        foreach ($out_store as $ans) {
                            $outTotal +=$ans->outT;
                        }
                       
                        $value->available = $inTotal - $outTotal;
                        array_push($materials, $value);
                    }

       $sum_exp=DB::table('expensive')->sum('amount');
       
       $sum_exp=DB::table('expensive')->sum('amount');

       $count_cat=DB::table('categories')->count();
        
    //    $count_stock=DB::table('products')->sum('stock');

           
       $stock_in=DB::table('product_in')->sum('qty');

       $sale_sum=DB::table('sales')->sum('qty');
       $sale_amt=DB::table('sales')->sum('amt'); 

       $count_task=DB::table('task')->count();
       $sum_task=DB::table('task')->sum('sub_total');
       
       $task_amount_paid=DB::table('task')
                         ->where('amount_paid','>',0)
                         ->sum('amount_paid');
       $task_amount_paid_count=DB::table('task')
                         ->where('amount_paid','>',0)
                         ->count();
        $task_amount_due=DB::table('task')
                         ->where('amount_due','>',0)
                         ->sum('amount_due');
        $task_amount_due_count=DB::table('task')
                         ->where('amount_due','>',0)
                         ->count();
      
        $products_demmage_count=DB::table('product_demage')
                             ->count();
       $products_demmage_sum=DB::table('product_demage')
                             ->sum('amt');
    
        $products_return_count=DB::table('stock_return')
                             ->count();
       $products_return_sum=DB::table('stock_return')
                             ->sum('amt');
        
        $monthly_report=DB::table('sales')
                      ->select(DB::raw("sum(amt) as amt,date_format(created_at, '%Y-%M') as months"))
                             // ->orderBy('amount')
                     ->groupBy('months')
                      ->get();
                      $mydata=[];
 
          
       $sum_balance=DB::table('account')->sum('account_balance');
              
       $acc=DB::table('account')->get();

       $mydata['monthly_report'] = json_encode($mydata);
       foreach( $monthly_report as $row1) {
           $mydata['monthly'][] = $row1->months;
           $mydata['amt'][] = $row1->amt;
         }
        $mydata['monthly_report'] = json_encode($mydata);

       $sum_paid=DB::table('task')
       ->sum('amount_paid');
         
       $sum_due=DB::table('task')
       ->sum('amount_due');
       $sum_demage=DB::table('product_demage')
       ->sum('amt');
       $sum_return=DB::table('stock_return')
       ->sum('amt');

       $array_data["data"]=array(
        'paid'=>$sum_paid,
        'due'=>$sum_due,
        'return'=>$sum_demage,
        'demage'=>$sum_return,
        );
        $data['chart_data'] = json_encode($array_data);

        $sum_income=DB::table('task')
       ->sum('amount_paid');

       $sum_expenses=DB::table('expensive')
       ->sum('amount');

       
       $sum_idel=DB::table('task')
       ->sum('amount_due');

       $array_expenses["expenses"]=array(
        'income'=>$sum_paid,
        'expenses'=>$sum_due,
        'idel'=>$sum_idel
        
        );
        $expenses['expenses'] = json_encode($array_expenses);
        


        return view('home.index',compact('materials','current_stock','count_stock','stock_in','sale_sum',
        'count_suppliers','sum_exp','sum_balance','acc','count_cat','sale_amt','count_task',
        'sum_task','task_amount_paid','task_amount_paid_count','task_amount_due','products_demmage_count',
        'task_amount_due_count','products_demmage_count','products_demmage_sum','products_demmage_sum',
         'products_return_count','products_return_sum','mydata','data','expenses'));
    }
}
