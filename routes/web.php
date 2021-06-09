<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SectionController;

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




Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        return view('home');
    });
    // Route::get('/{page}', [AdminController::class, 'index']);
    // Route::get('admin/invoice', [InvoiceController::class, 'index'])->name('admin.invoice');


    Route::resources([
        'section' => SectionController::class,
        'product' => ProductController::class,
        'invoice' => InvoiceController::class,
    ]);

    Route::get('invoice/section/getproducts/{section_id}', [InvoiceController::class, 'getProducts']);
});
