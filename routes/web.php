<?php

use App\Http\Controllers\AdminCompaniesController;
use App\Http\Controllers\AdminItemsController;
use App\Http\Controllers\AdminPosBranchesController;
use App\Http\Controllers\AdminPosTerminalsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\POSPullController;
use App\Http\Controllers\AdminSalesReportsController;
use App\Http\Controllers\BankMasterController;
use App\Http\Controllers\CreditMasterController;
use App\Http\Controllers\OtherTenderMasterController;
use App\Http\Controllers\PosAccountController;
use App\Http\Controllers\TenderMasterController;

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

Route::get('admin/get-item/{item}', [POSPullController::class, 'getProduct']);
Route::get('admin/get-sales-transaction', [POSPullController::class, 'getSalesTransaction']);
Route::get('admin/get-sales', [POSPullController::class, 'getSales']);

Route::get('admin/get-sales-transaction/{datefrom}/{dateto}/{terminal}', [POSPullController::class, 'getSalesTransactionByDate']);
Route::get('admin/get-sales/{datefrom}/{dateto}/{terminal}', [AdminSalesReportsController::class, 'getSalesTransaction']);
Route::get('admin/get-sales/{receipt}/{terminal}', [POSPullController::class, 'getSalesRecord']);
Route::get('admin/get-sales-receipt/{receipt}/{company}/{terminal}', [POSPullController::class, 'getSalesRecordByTerminal']);

Route::get('admin/get-company',[AdminCompaniesController::class, 'getPosCompany']);
Route::get('admin/get-terminal',[AdminPosTerminalsController::class, 'getPosTerminal']);
Route::get('admin/get-branch',[AdminPosBranchesController::class, 'getPosBranch']);
Route::get('admin/get-items', [AdminItemsController::class, 'getPosItems']);
Route::get('admin/get-accounts', [PosAccountController::class, 'getPosAccounts']);
Route::get('admin/get-credit-master',[CreditMasterController::class, 'getCreditMaster']);
Route::get('admin/get-bank-master',[BankMasterController::class, 'getBankMaster']);
Route::get('admin/get-tender-master',[OtherTenderMasterController::class, 'getTenderMaster']);

Route::get('admin/get-daily-sales', [AdminSalesReportsController::class, 'getDailySalesTransaction']);
Route::get('admin/get-daily-sales/{date_from}/{date_to}', [AdminSalesReportsController::class, 'getDailySalesTransactionByDate']);

Route::post('admin/sales_reports/export',[AdminSalesReportsController::class, 'salesExport'])->name('sales.export');
Route::post('admin/sales_reports/tender-export',[AdminSalesReportsController::class, 'tenderExport'])->name('tender.export');
Route::post('admin/sales_reports/summary-export',[AdminSalesReportsController::class, 'summaryExport'])->name('summary.export');
Route::post('admin/sales_reports/get-branch',[AdminPosBranchesController::class, 'getPosBranchByCompany'])->name('get.branch');

Route::post('admin/sales_reports/search',[AdminSalesReportsController::class, 'searchSales'])->name('sales.search');



