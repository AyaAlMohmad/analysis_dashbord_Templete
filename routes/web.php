<?php

use App\Http\Controllers\AppointmentLogController;
use App\Http\Controllers\AppointmentReportController;
use App\Http\Controllers\CallLogsLogController;
use App\Http\Controllers\CallLogsReportController;
use App\Http\Controllers\ComprehensiveReport\ComprehensiveReportController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemLogController;
use App\Http\Controllers\ItemReportController;
use App\Http\Controllers\LeadsLogController;
use App\Http\Controllers\LeadsReportController;
use App\Http\Controllers\LeadsSourcesLogController;
use App\Http\Controllers\LeadsSourcesReportController;
use App\Http\Controllers\LeadsStatusLogController;
use App\Http\Controllers\LeadsStatusReportController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

Route::middleware(['auth', 'admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::get('lang/{locale}', function ($locale) {
        if (in_array($locale, ['en', 'ar'])) {
            session()->put('locale', $locale);
            app()->setLocale($locale);
        }
        return redirect()->back();
    })->name('lang.switch')->middleware('web');
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/Analysis', [DashboardController::class, 'Analysis'])->name('Analysis');

    Route::get('/appointment', [AppointmentReportController::class, 'index'])->name(('appointment'));
    Route::post('/appointment/export', [AppointmentReportController::class, 'export'])->name('appointments.export');
    Route::get('/appointment-log/{site}', [AppointmentLogController::class, 'log'])->name('appointments.log');
    Route::get('/appointments/logs/{site}', [AppointmentLogController::class, 'showLog'])->name('appointments.logs');
    Route::get('/appointments/statistics/{site}', [AppointmentLogController::class, 'showStatistics'])->name('appointments.statistics');

    Route::get('/item', [ItemReportController::class, 'index'])->name('items');
    Route::get('/items/map/{site}', [ItemReportController::class, 'map'])->name('items.map');
    Route::post('/item/export', [ItemReportController::class, 'export'])->name('items.export');
    Route::get('/item-log/{site}', [ItemLogController::class, 'log'])->name('items.log');
    Route::get('/items/logs/{site}', [ItemLogController::class, 'showLog'])->name('items.logs');
    Route::get('/items/{site}/statistics', [ItemLogController::class, 'statistics'])->name('items.statistics');
    Route::get('/items/status', [ItemReportController::class, 'itemStatus'])->name('items.status');
    Route::get('/items/unitStages', [ItemReportController::class, 'unitStages'])->name('items.unitStages');
    Route::get('/items/unitStatisticsByStage', [ItemReportController::class, 'unitStatisticsByStage'])->name('items.unitStatisticsByStage');




    Route::get('/leads-source', [LeadsSourcesReportController::class, 'index'])->name('leads-sources');
    Route::post('/leads-source/export', [LeadsSourcesReportController::class, 'export'])->name('leads-sources.export');
    Route::get('/leads-source-log/{site}', [LeadsSourcesLogController::class, 'log'])->name('leads-sources.log');
    Route::get('/leads-sources/logs/{site}', [LeadsSourcesLogController::class, 'showLog'])->name('leads-sources.logs');


    Route::get('/leads-status', [LeadsStatusReportController::class, 'index'])->name('leads-status');
    Route::post('/leads-status/export', [LeadsStatusReportController::class, 'export'])->name('leads-status.export');
    Route::get('/leads-status-log/{site}', [LeadsStatusLogController::class, 'log'])->name('leads-status.log');
    Route::get('/leads-status/logs/{site}', [LeadsStatusLogController::class, 'showLog'])->name('leads-status.logs');

    Route::get('/leads', [LeadsReportController::class, 'index'])->name('leads');
    Route::post('/leads/export', [LeadsReportController::class, 'export'])->name('leads.export');
    Route::get('/leads-log/{site}', [LeadsLogController::class, 'log'])->name('leads.log');
    Route::get('/leads/logs/{site}', [LeadsLogController::class, 'showLog'])->name('leads.logs');
    Route::get('/leads/{site}/statistics', [LeadsLogController::class, 'statistics'])->name('leads.statistics');

    Route::get('/call', [CallLogsReportController::class, 'index'])->name('call');
    Route::post('/call/export', [CallLogsReportController::class, 'export'])->name('call.export');
    Route::get('/call/{site}', [CallLogsLogController::class, 'log'])->name('call.log');
    Route::get('/call/logs/{site}', [CallLogsLogController::class, 'showLog'])->name('call.logs');
    Route::get('/call-logs/{site}/statistics', [CallLogsLogController::class, 'statistics'])->name('call.statistics');


    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/reports/itemReport', [ItemReportController::class, 'itemReport'])->name('reports.item');
    Route::post('/reports/itemReport/result', [ItemReportController::class, 'itemReportResult'])->name('reports.itemReport.result');
  
    Route::get('/reports/team-category', [TeamController::class, 'teamCategoryReport'])->name('reports.teamCategory');
    Route::post('/reports/team-category/result', [TeamController::class, 'teamCategorySearch'])->name('reports.teamCategory.result');
    Route::get('/reports/team', [TeamController::class, 'teamReport'])->name('reports.teamReport');
    Route::post('/reports/team/result', [TeamController::class, 'teamReportSearch'])->name('reports.teamReport.result');
    Route::get('/sales/report', [SalesController::class, 'salesReport'])->name('sales.report');
    Route::post('/sales/report/result', [SalesController::class, 'salesReportResult'])->name('sales.report.result');
 


    Route::get('/reports/contracts', [ItemReportController::class, 'contractsReport'])->name('reports.contracts');
    Route::post('/reports/contracts/result', [ItemReportController::class, 'contractsReportResult'])->name('reports.contracts.result');
    
    Route::get('/reports/source', [LeadsSourcesReportController::class, 'sourceReport'])->name('reports.source');
    Route::post('/reports/source/result', [LeadsSourcesReportController::class, 'sourceReportResult'])->name('reports.source.result');

    Route::get('/customers/report', [CustomerController::class, 'showForm'])->name('customers.report');
    Route::post('/customers/report/result', [CustomerController::class, 'fetchReport'])->name('customers.report.result');

    Route::get('/comprehensive/form', [ComprehensiveReportController::class, 'form'])->name('comprehensive.form');
    // Route::post('/comprehensive', [ComprehensiveReportController::class, 'index'])->name('comprehensive.index');
    Route::post('/comprehensive', [ComprehensiveReportController::class, 'store'])->name('comprehensive.store');
Route::get('/comprehensive', [ComprehensiveReportController::class, 'show'])->name('comprehensive.show');

});

require __DIR__.'/auth.php';
