<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AccountController,
    ActivityController,
    AuditController,
    CategoryController,
    CashAdvance,
    CashFlow,
    CustomerController,
    DepartmentController,
    DepositeController,
    DesignationController,
    EmployeeController,
    ExpensiveController,
    Expensive_Income,
    HomeController,
    IntoStoreController,
    MaterialCategoriesController,
    MaterialController,
    MeasurementController,
    MessageController,
    PaymentController,
    PositionController,
    ProductController,
    ProductDemage,
    ProductInController,
    ProductOutController,
    ProductionSessionController,
    ProfileController,
    ReceivePayment,
    SpendingController,
    StockReturn,
    TaskController,
    TransferController,
    UserController
};
use Illuminate\Support\Facades\Auth;

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

// Home and Profile
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ProfileController::class, 'update'])->name('update');
});

// Measurements
Route::resource('measurements', MeasurementController::class);
Route::get('api/measurements', [MeasurementController::class, 'apiMeasurements']);

// Materials
Route::resource('materials', MaterialController::class);
Route::prefix('materials')->group(function () {
    Route::get('report', [MaterialController::class, 'materialReport']);
    Route::get('api', [MaterialController::class, 'apiMaterial']);
});
Route::resource('material-categories', MaterialCategoriesController::class);
Route::get('api/material-categories', [MaterialCategoriesController::class, 'apiMaterialCategories']);

// Into Store
Route::resource('into-store', IntoStoreController::class);
Route::prefix('into-store')->group(function () {
    Route::get('api', [IntoStoreController::class, 'apiIntoStore']);
    Route::post('use-store', [IntoStoreController::class, 'apiUseStore']);
    Route::get('categories', [IntoStoreController::class, 'categories']);
    Route::get('report/batch', [IntoStoreController::class, 'batchReport']);
    Route::get('report/export-pdf', [IntoStoreController::class, 'exportPDF']);
});

// Products
Route::resource('products', ProductController::class);
Route::prefix('products')->group(function () {
    Route::get('api', [ProductController::class, 'apiProducts']);
    Route::get('stock/check/{id}', [ProductController::class, 'checkStock']);
    Route::get('stock/export', [ProductController::class, 'stockExport']);
});

// Accounts and Payments
Route::resource('accounts', AccountController::class);
Route::prefix('accounts')->group(function () {
    Route::get('api', [AccountController::class, 'AccountApi']);
    Route::post('activate/{id}', [AccountController::class, 'activateData']);
});
Route::resource('payments', PaymentController::class);
Route::post('payments/export', [PaymentController::class, 'export_pay']);

Route::prefix('spendings')->group(function () {
    Route::get('', [SpendingController::class, 'index']);
    Route::post('', [SpendingController::class, 'store']);
    Route::get('/{id}', [SpendingController::class, 'edit']);
    Route::post('/{id}', [SpendingController::class, 'update']);
});
// Other Resources
Route::resources([
    'categories' => CategoryController::class,
    'users' => UserController::class,
    'departments' => DepartmentController::class,
    'positions' => PositionController::class,
    'tasks' => TaskController::class,
    'employees' => EmployeeController::class,
    'cash-advances' => CashAdvance::class,
    'designations' => DesignationController::class,
    'activities' => ActivityController::class,
    'transfers' => TransferController::class,
    'stock-returns' => StockReturn::class,
    'receive-payments' => ReceivePayment::class,
    'product-damages' => ProductDemage::class,
    'cash-flows' => CashFlow::class,
    'income-expenditures' => Expensive_Income::class,
    'audits' => AuditController::class,
	'spendings'=>SpendingController::class,
]);

// Additional Routes
Route::post('/change-session', [UserController::class, 'changeSession'])->name('change.session');

Auth::routes();
