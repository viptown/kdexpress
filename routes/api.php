<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\supplier_ordersController;
use App\Models\supplier_order;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/orders/{barcode}', function (Request $request, String $barcode) {
    //return 'order ok' . $barcode;
    return supplier_order::where('supplier_bl', $barcode)->get();
});


Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('/sporders', supplier_ordersController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
});
