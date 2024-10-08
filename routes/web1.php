<?php
// use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ExpensiveController;
use App\Http\Controllers\DepositeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductionSessionController;
use App\Http\Controllers\ProductInController;
use App\Http\Controllers\MeasurementController;
use App\Http\Controllers\IntoStoreController;
use App\Http\Controllers\MaterialCategoriesController;
use App\Http\Controllers\ProductOutController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\CashAdvance;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\StockReturn;
use App\Http\Controllers\ReceivePayment;
use App\Http\Controllers\ProductDemage;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CashFlow;
use App\Http\Controllers\Expensive_Income;
use App\Http\Controllers\AuditController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//user router will be here

Route::resource('/measurements',MeasurementController::class);
Route::get('/apiMeasurements',[MeasurementController::class, 'apiMeasurements']);


Route::resource('/productionSessions',ProductionSessionController::class);
Route::get('/apiProductionSession',[ProductionSessionController::class, 'apiProductionSession']);
//Materials Route here 

Route::resource('/intoStore',IntoStoreController::class);
Route::get('/apiIntoStore',[IntoStoreController::class, 'apiIntoStore']);
Route::post('/apiUseStore',[IntoStoreController::class, 'apiUseStore']);
Route::get('/intoStoreShow',[IntoStoreController::class, 'show']);
Route::get('/intoStoreShow/{batch_number}',[IntoStoreController::class, 'showBatch']);
Route::get('/intoStoreShowByDates',[IntoStoreController::class, 'showByDates']);
Route::get('/exportIntoStorePDF',[IntoStoreController::class, 'exportPDF']);
Route::get('/batchReport',[IntoStoreController::class, 'batchReport']);
//Materials Route here 

Route::resource('/materialCategories',MaterialCategoriesController::class);
Route::get('/apiMaterialCategories',[MaterialCategoriesController::class, 'apiMaterialCategories']);
//Materials Route here 

Route::resource('/materials',MaterialController::class);
Route::get('/apiMaterial',[MaterialController::class, 'apiMaterial']);
//User Route here 
Route::resource('/user',UserController::class);
Route::get('/apiUser', [UserController::class,'apiUsers']);

// will be here
route::resource('/Account',AccountController::class);
route::get('/AccountApi', [AccountController::class,'AccountApi']);
route::post('/activateData/{id}',[AccountController::class,'activateData']);
// expensive will be here
route::resource('/Expensive',ExpensiveController::class);
route::get('/ExpensiveApi', [ExpensiveController::class,'ExpensiveApi']);
route::resource('/deposite',DepositeController::class);
route::get('/DepositeApi', [DepositeController::class,'DepositeApi']);
Route::get('/check_balance/{id}', [DepositeController::class,'check_balance']);
Route::get('/check_account/{id}', [DepositeController::class,'check_account']);

//  CATEGORY ROUTE HERE 
Route::get('cat',[CategoryController::class,'index']);
Route::resource('categories', CategoryController::class);

//Product In will be here
Route::get('/get_litre/{id}', [ProductInController::class,'get_litre']);
Route::resource('productsIn',ProductInController::class);
Route::get('/apiProducts_in', [ProductInController::class,'apiProducts_in']);
Route::post('/exportProduct_inAll', [ProductInController::class,'exportProduct_inAll']);
Route::get('/exportProduct_inAllExcel', [ProductInController::class,'exportExcel']);
Route::get('/get_item/{id}', [ProductInController::class,'get_item']);
Route::get('/get_stock/{id}', [ProductInController::class,'get_stock']);
Route::get('/get_manufacture_details/{id}', [ProductionSessionController::class,'get_manufacture_details']);
Route::get('/get_process_batches', [ProductionSessionController::class,'get_process_batches']);
//Product Out will be here

Route::resource('productsOut',ProductOutController::class);
Route::get('/apiProducts_out', [ProductOutController::class,'apiProducts_out']);
Route::post('/exportProduct_outAll', [ProductOutController::class,'exportProduct_OutAll']);
Route::get('/exportProduct_outAllExcel', [ProductOutController::class,'exportExcel']);
Route::get('/sales_info/{id}', [ProductOutController::class,'sales_info']);


Route::get('/apiProducts', [ProductController::class,'apiProducts']);
Route::get('/check_stock/{id}', [ProductController::class,'checkStock']);
Route::get('/stockExport', [ProductController::class,'stockExport']);


//Material will be here

Route::resource('materials',MaterialController::class);
Route::get('/apiProducts_out', [ProductOutController::class,'apiProducts_out']);
Route::post('/exportProduct_outAll', [ProductOutController::class,'exportProduct_OutAll']);
Route::get('/exportProduct_outAllExcel', [ProductOutController::class,'exportExcel']);
Route::get('/sales_info/{id}', [ProductOutController::class,'sales_info']);


Route::get('/apiCategories', [CategoryController::class,'apiCategories']);

//Route::get('products',[ProductController::class,'index']);
Route::resource('/products', ProductController::class);


//Supplier Route will be here
Route::resource('/employee',EmployeeController::class);
Route::get('/apiEmployee', [EmployeeController::class,'apiEmployee']);

//Department will be here
Route::resource('/department',DepartmentController::class);

Route::get('/apiDepartment', [DepartmentController::class,'apiDepartment']);

//Position will be here
Route::resource('position',PositionController::class);
Route::get('/apiPosition', [PositionController::class,'apiPosition']);

// cash in hand will be here
Route::resource('cash_advance',CashAdvance::class);
Route::get('/apiCash', [CashAdvance::class,'apiCash']);

//supplier root
Route::resource('suppliers',SupplierController::class);
Route::get('/apiSupplier', [SupplierController::class,'apiSupplier']);
Route::get('/empo_info/{id}', [SupplierController::class,'empo_info']);
Route::post('/supplier_report', [SupplierController::class,'supplier_report']);
Route::get('/check/{id}', [SupplierController::class,'check']);



Route::resource('task',TaskController::class);
Route::get('/apiTask', [TaskController::class,'apiTask']);
Route::get('/apiTask1', [TaskController::class,'apiTask1']);
Route::post('/exportTask', [TaskController::class,'exportTask']);
Route::get('/view_details/{id}', [TaskController::class,'edit']);
Route::get('/task_info/{id}', [TaskController::class,'task_info']);
Route::get('/infoApi/{id}',[TaskController::class,'infoApi']);

Route::get('/return_stock/{id}', [TaskController::class,'stockReturn']);
Route::get('/returnApi/{id}',[TaskController::class,'returnApi']);
Route::get('/demageApi/{id}',[TaskController::class,'demageApi']);
Route::get('/single_report/{id}', [TaskController::class,'single_report']);




Route::resource('remains',StockReturn::class);

//receive payment

Route::resource('receive_pay',ReceivePayment::class);
Route::get('/check_amount/{id}', [TaskController::class,'amount_due']);

Route::get('/apiPay', [ReceivePayment::class,'apiTask']);


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', function () {
	return view('auth.login');
});


route::post('/exportexpenses',[ExpensiveController::class,'exportexpenses']);
route::post('/exportdeposite',[DepositeController::class,'exportdeposite']);


Route::resource('/demage_products',ProductDemage::class);
Route::get('/apiDemage',[ProductDemage::class,'apiDemage']);

Route::post('/exportDemage',[ProductDemage::class,'exportDemage']);

//payment controller will be here

Route::resource('/payment_history',PaymentController::class);
Route::get('/apiPayment',[PaymentController::class,'apiPayment']);
Route::post('/exportpay',[PaymentController::class,'export_pay']);

//transfer bank route
route::get('/TransferApi', [TransferController::class,'TransferApi']);
route::resource('/transfer',TransferController::class);
route::post('/export_transfer', [TransferController::class,'export_transfer']);

//cash flow route will be here
route::resource('/cash_flow',CashFlow::class);
route::post('/export_flow', [CashFlow::class,'export_flow']);


//cash flow route will be here
route::resource('/income_expenditure',Expensive_Income::class);
route::post('/profit_loss', [Expensive_Income::class,'income_exp']);

route::resource('/get_audit',AuditController::class);


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
