<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware('auth:sanctum');
//get outlet by user
Route::get('/my-outlet', [App\Http\Controllers\Api\AuthController::class, 'getOutletByUser'])->middleware('auth:sanctum');

//me
Route::get('/me', [App\Http\Controllers\Api\AuthController::class, 'me'])->middleware('auth:sanctum');

//add manager
Route::post('/add-manager', [App\Http\Controllers\Api\AuthController::class, 'addManager'])->middleware('auth:sanctum');

//staff
Route::get('/get-staff/{businessId}', [App\Http\Controllers\Api\StaffController::class, 'getStaff'])->middleware('auth:sanctum');
//add staff
Route::post('/add-staff', [App\Http\Controllers\Api\StaffController::class, 'addStaff'])->middleware('auth:sanctum');
//edit staff
Route::put('/edit-staff/{id}', [App\Http\Controllers\Api\StaffController::class, 'editStaff'])->middleware('auth:sanctum');

//outlets
Route::post('/add-outlet', [App\Http\Controllers\Api\OutletController::class, 'addOutlet'])->middleware('auth:sanctum');
Route::put('/update-outlet/{id}', [App\Http\Controllers\Api\OutletController::class, 'updateOutlet'])->middleware('auth:sanctum');
Route::get('/get-outlets/{businessId}', [App\Http\Controllers\Api\OutletController::class, 'getOutlets'])->middleware('auth:sanctum');

//categories
Route::post('/add-category', [App\Http\Controllers\Api\CategoryController::class, 'addCategory'])->middleware('auth:sanctum');
Route::get('/get-categories', [App\Http\Controllers\Api\CategoryController::class, 'getCategories'])->middleware('auth:sanctum');
Route::put('/update-category/{id}', [App\Http\Controllers\Api\CategoryController::class, 'updateCategory'])->middleware('auth:sanctum');

//products
Route::post('/add-product', [App\Http\Controllers\Api\ProductController::class, 'addProduct'])->middleware('auth:sanctum');
Route::put('/update-product/{id}', [App\Http\Controllers\Api\ProductController::class, 'updateProduct'])->middleware('auth:sanctum');
Route::post('/update-product-with-image/{id}', [App\Http\Controllers\Api\ProductController::class, 'updateProductWithImage'])->middleware('auth:sanctum');
Route::get('/get-products', [App\Http\Controllers\Api\ProductController::class, 'getProducts'])->middleware('auth:sanctum');
Route::get('/get-product/{id}', [App\Http\Controllers\Api\ProductController::class, 'getProduct'])->middleware('auth:sanctum');
Route::delete('/delete-product/{id}', [App\Http\Controllers\Api\ProductController::class, 'deleteProduct'])->middleware('auth:sanctum');

//stocks
Route::post('/add-stock', [App\Http\Controllers\Api\StockController::class, 'addStock'])->middleware('auth:sanctum');
Route::put('/update-stock/{id}', [App\Http\Controllers\Api\StockController::class, 'updateStock'])->middleware('auth:sanctum');
Route::get('/get-stocks', [App\Http\Controllers\Api\StockController::class, 'getStocks'])->middleware('auth:sanctum');
Route::get('/get-stock/{id}', [App\Http\Controllers\Api\StockController::class, 'getStock'])->middleware('auth:sanctum');
Route::delete('/delete-stock/{id}', [App\Http\Controllers\Api\StockController::class, 'deleteStock'])->middleware('auth:sanctum');

//orders
Route::post('/add-order', [App\Http\Controllers\Api\OrderController::class, 'addOrder'])->middleware('auth:sanctum');
Route::get('/get-orders', [App\Http\Controllers\Api\OrderController::class, 'getOrders'])->middleware('auth:sanctum');
Route::get('/get-order/{id}', [App\Http\Controllers\Api\OrderController::class, 'getOrder'])->middleware('auth:sanctum');
Route::delete('/delete-order/{id}', [App\Http\Controllers\Api\OrderController::class, 'deleteOrder'])->middleware('auth:sanctum');

//get order by outlet id
Route::get('/get-orders-by-outlet/{id}', [App\Http\Controllers\Api\OrderController::class, 'getOrdersByOutlet'])->middleware('auth:sanctum');

//printers
Route::post('/add-printer', [App\Http\Controllers\Api\PrinterController::class, 'addPrinter'])->middleware('auth:sanctum');
Route::get('/get-printers-by-outlet/{outlet_id}', [App\Http\Controllers\Api\PrinterController::class, 'getPrintersByOutlet'])->middleware('auth:sanctum');
Route::delete('/delete-printer/{id}', [App\Http\Controllers\Api\PrinterController::class, 'deletePrinter'])->middleware('auth:sanctum');

//business settings
Route::post('/add-business-setting', [App\Http\Controllers\Api\BusinessSettingController::class, 'addBusinessSetting'])->middleware('auth:sanctum');
Route::get('/get-business-settings-by-business/{business_id}', [App\Http\Controllers\Api\BusinessSettingController::class, 'getBusinessSettingsByBusiness'])->middleware('auth:sanctum');
Route::put('/update-business-setting/{id}', [App\Http\Controllers\Api\BusinessSettingController::class, 'updateBusinessSetting'])->middleware('auth:sanctum');
//delete
Route::delete('/delete-business-setting/{id}', [App\Http\Controllers\Api\BusinessSettingController::class, 'deleteBusinessSetting'])->middleware('auth:sanctum');

//sales report
Route::post('/get-daily-sales-report', [App\Http\Controllers\Api\SalesReportController::class, 'getDailySalesReport'])->middleware('auth:sanctum');
