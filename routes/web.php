<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;

use App\Http\Controllers\SectionController;
use App\Http\Controllers\InvoiceDetailsController;
use App\Http\Controllers\InvoiceArchievedController;
use App\Http\Controllers\InvoiceAttachmentController;
use App\Http\Controllers\InvoiceReportController;

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




Route::middleware(['auth', 'active'])->group(function () {
    // Route::get('/{page}', [AdminController::class, 'index']);

    Route::get('/', [HomeController::class, 'index'])->name('admin.dashboard');
    Route::redirect('home', '/');


    Route::resources([
        'section' => SectionController::class,
        'product' => ProductController::class,
        'invoice' => InvoiceController::class,
        'archieve' => InvoiceArchievedController::class,
        'invoice/attachment' => InvoiceAttachmentController::class,
        'invoice/details' => InvoiceDetailsController::class,
        'roles' => RoleController::class,
        'users' => UserController::class,
    ]);



    Route::get('invoice/section/getproducts/{section_id}', [InvoiceController::class, 'getProducts']);
    Route::post('invoice/archieve/{id}', [InvoiceController::class, 'archieve'])->name('invoice.archieve');
    Route::get('invoice/edit/status/{id}', [InvoiceController::class, 'editStatus'])->name('invoice.edit.status');
    Route::post('invoice/update/status/{id}', [InvoiceController::class, 'updateStatus'])->name('invoice.update.status');

    Route::get('unpaid', [InvoiceController::class, 'Invoice_unPaid'])->name('invoice.unpaid');
    Route::get('paid', [InvoiceController::class, 'Invoice_Paid'])->name('invoice.paid');
    Route::get('partial', [InvoiceController::class, 'Invoice_Partial'])->name('invoice.partial');
    Route::get('print/invoice/{id}', [InvoiceController::class, 'Invoice_Print'])->name('invoice.print');
    Route::get('export_invoices/',  [InvoiceController::class, 'export'])->name('excel.export');
    Route::get('MarkAsRead_all/',  [InvoiceController::class, 'MarkAsRead_all'])->name('MarkAsRead_all');
    Route::get('MarkAsRead_one/{id}',  [InvoiceController::class, 'MarkAsRead_one'])->name('read.notification');
    // Route::get('unreadNotifications_count/',  [InvoiceController::class, 'unreadNotifications_count'])->name('unreadNotifications_count');
    // Route::get('unreadNotifications/',  [InvoiceController::class, 'unreadNotifications'])->name('unreadNotifications');


    Route::get('attachment/open/{invoice_number}/{file_name}', [InvoiceAttachmentController::class, 'open_file']);
    Route::get('attachment/download/{invoice_number}/{file_name}', [InvoiceAttachmentController::class, 'download_file']);


    // reports
    Route::get('report_invoices', [InvoiceReportController::class, 'index'])->name('invoice.report');
    Route::post('invoice_search', [InvoiceReportController::class, 'Search_invoices'])->name('invoice.search');

    Route::get('report_customers', [CustomerReportController::class, 'index'])->name('customer.report');
    Route::post('customer_search', [CustomerReportController::class, 'Search_customers'])->name('customer.search');
});
