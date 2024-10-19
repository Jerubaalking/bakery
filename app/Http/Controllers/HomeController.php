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
        // Fetch all products
        $allProducts = DB::table('products')->get();

        // Initialize variables
        $current_stock = [];
        $count_stock = 0;

        // Calculate total in, out, and stock value
        foreach ($allProducts as $product) {
            $total_in = DB::table('product_in')->where('product_id', $product->id)->sum('qty');
            $total_out = DB::table('sales')->where('product_id', $product->id)->sum('qty');
            $product->stock = $total_in - $total_out;

            $sale_price = DB::table('sales')->where('product_id', $product->id)->value('price');
            if ($sale_price) {
                $count_stock += $product->stock * $sale_price;
            }

            $current_stock[] = $product;
        }

        // Fetch supplier count
        $count_suppliers = DB::table('position')
            ->join('employee', 'employee.position_id', '=', 'position.id')
            ->where('position.position_name', 'supplier')
            ->count();

        // Fetch material details
        $materialz = DB::table('materials')
            ->join('measurements', 'measurements.id', '=', 'materials.measurement_id')
            ->join('material_categories', 'material_categories.id', '=', 'materials.material_category_id')
            ->select('materials.*', 'measurements.symbol', 'measurements.measurement', 'material_categories.category_name', 'material_categories.type')
            ->get();

        // Fetch stock movements
        $into_stores = DB::table('into_store')
            ->join('materials', 'materials.id', '=', 'into_store.material_id')
            ->join('measurements', 'measurements.id', '=', 'materials.measurement_id')
            ->select('into_store.*', 'materials.name', 'measurements.symbol')
            ->get();

        // Process material availability
        $materials = [];
        foreach ($materialz as $material) {
            $inTotal = $into_stores->where('status', 'in')->where('material_id', $material->id)->sum('qty');
            $outTotal = $into_stores->where('status', '!=', 'in')->where('material_id', $material->id)->sum('qty');
            $material->available = max(0, $inTotal - $outTotal);

            // Convert to kilograms if needed
            if ($material->symbol == 'g' && $material->available > 999) {
                $material->available = $material->available / 1000;
                $material->symbol = 'kg';
            }

            $material->available = number_format($material->available, 2) . ' ' . $material->symbol;
            $materials[] = $material;
        }

        // Sum expenses and other aggregated data
        $sum_exp = DB::table('expensive')->sum('amount');
        $count_cat = DB::table('categories')->count();
        $stock_in = DB::table('product_in')->sum('qty');
        $sale_sum = DB::table('sales')->sum('qty');
        $sale_amt = DB::table('sales')->sum('amt');
        $count_task = DB::table('task')->count();
        $sum_task = DB::table('task')->sum('sub_total');

        // Task payments and due amounts
        $task_amount_paid = DB::table('task')->where('amount_paid', '>', 0)->sum('amount_paid');
        $task_amount_paid_count = DB::table('task')->where('amount_paid', '>', 0)->count();
        $task_amount_due = DB::table('task')->where('amount_due', '>', 0)->sum('amount_due');
        $task_amount_due_count = DB::table('task')->where('amount_due', '>', 0)->count();

        // Product damage and return details
        $products_demmage_count = DB::table('product_demage')->count();
        $products_demmage_sum = DB::table('product_demage')->sum('amt');
        $products_return_count = DB::table('stock_return')->count();
        $products_return_sum = DB::table('stock_return')->sum('amt');

        // Monthly report
        $monthly_report = DB::table('sales')
            ->select(DB::raw("sum(amt) as amt, DATE_FORMAT(created_at, '%Y-%M') as months"))
            ->groupBy('months')
            ->get();

        $mydata = [
            'monthly' => $monthly_report->pluck('months'),
            'amt' => $monthly_report->pluck('amt'),
            'monthly_report' => json_encode($monthly_report)
        ];

        // Financial summary
        $sum_paid = DB::table('task')->sum('amount_paid');
        $sum_due = DB::table('task')->sum('amount_due');
        $sum_balance = DB::table('account')->sum('account_balance');
        $acc = DB::table('account')->get();

        $array_data = [
            'paid' => $sum_paid,
            'due' => $sum_due,
            'return' => $products_demmage_sum,
            'demage' => $products_return_sum
        ];

        $data['chart_data'] = json_encode($array_data);

        // Income vs expenses report
        $sum_expenses = DB::table('expensive')->sum('amount');
        $material_value = DB::table('into_store')
            ->selectRaw('SUM(CASE WHEN status = "in" THEN cost ELSE 0 END) AS in_value,
                 SUM(CASE WHEN status != "in" THEN cost ELSE 0 END) AS used_value')
            ->first();

        $material_value = $material_value->in_value - $material_value->used_value;

        $account_count = DB::table('account')->count();
        $array_expenses = [
            'income' => $sum_paid,
            'expenses' => $sum_expenses,
            'idel' => $sum_due
        ];

        $expenses['expenses'] = json_encode($array_expenses);

        return view('home.index', compact(
            'materials',
            'current_stock',
            'count_stock',
            'stock_in',
            'sale_sum',
            'count_suppliers',
            'sum_exp',
            'sum_balance',
            'acc',
            'count_cat',
            'sale_amt',
            'count_task',
            'sum_task',
            'task_amount_paid',
            'task_amount_paid_count',
            'task_amount_due',
            'task_amount_due_count',
            'products_demmage_count',
            'products_demmage_sum',
            'products_return_count',
            'products_return_sum',
            'mydata',
            'data',
            'expenses',
            'account_count',
            'material_value'
        ));
    }
}
