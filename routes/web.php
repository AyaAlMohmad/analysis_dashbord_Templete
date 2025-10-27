<?php

use App\Http\Controllers\AppointmentLogController;
use App\Http\Controllers\AppointmentReportController;
use App\Http\Controllers\CallLogsLogController;
use App\Http\Controllers\CallLogsReportController;
use App\Http\Controllers\ComprehensiveReport\AppointmentsController;
use App\Http\Controllers\ComprehensiveReport\ComprehensiveReportController;
use App\Http\Controllers\ComprehensiveReport\DisinterestReasonsController;
use App\Http\Controllers\ComprehensiveReport\MapController;
use App\Http\Controllers\ComprehensiveReport\ProjectSummaryController;
use App\Http\Controllers\ComprehensiveReport\SiteController;
use App\Http\Controllers\ComprehensiveReport\StatusController;
use App\Http\Controllers\ComprehensiveReport\TargetedReportController;
use App\Http\Controllers\ComprehensiveReport\UnitDetailsController;
use App\Http\Controllers\ComprehensiveReport\UnitSalesController;
use App\Http\Controllers\ComprehensiveReport\UnitStagesController;
use App\Http\Controllers\ComprehensiveReport\UnitStatisticsController;
use App\Http\Controllers\ComprehensiveReport\VisitsPaymentsContractsController;
use App\Http\Controllers\CrmAdvertisingCampaignController;
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
use App\Http\Controllers\ProjectProgressController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectPlanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CostLegislatorController;
use App\Http\Controllers\StaffTreeController;



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
    Route::put('/project-progress/update', [DashboardController::class, 'updateProjectProgress'])
        ->name('project-progress.update');
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
    Route::resource('roles', RoleController::class);

    // Routes إضافية
    Route::get('/users/{user}/manage-permissions', [UserController::class, 'managePermissions'])
        ->name('users.manage-permissions');

    Route::put('/users/{user}/update-permissions', [UserController::class, 'updatePermissions'])
        ->name('users.update-permissions');

    Route::get('/project-progress', [ProjectProgressController::class, 'index'])->name('project-progress.index');
    Route::get('/project-progress/create', [ProjectProgressController::class, 'create'])->name('project-progress.create');
    Route::post('/project-progress', [ProjectProgressController::class, 'store'])->name('project-progress.store');
    Route::get('/project-progress/{id}/edit', [ProjectProgressController::class, 'edit'])->name('project-progress.edit');
    Route::put('/project-progress/{id}', [ProjectProgressController::class, 'update'])->name('project-progress.update');
    Route::delete('/project-progress/{id}', [ProjectProgressController::class, 'destroy'])->name('project-progress.destroy');
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

    Route::get('/campaign/form', [CrmAdvertisingCampaignController::class, 'form'])->name('campaign.form');
    Route::post('/campaign/result', [CrmAdvertisingCampaignController::class, 'submitCampaign'])->name('campaign.result');
    Route::get('/campaign/result', [CrmAdvertisingCampaignController::class, 'show'])->name('campaign.show');

    Route::get('/admin/campaign/sources', [CrmAdvertisingCampaignController::class, 'getSources'])->name('campaign.sources');
    Route::get('/admin/campaign/tags', [CrmAdvertisingCampaignController::class, 'getTags'])->name('campaign.tags');
    Route::get('/comprehensive/form', [ComprehensiveReportController::class, 'form'])->name('comprehensive.form');
    // Route::post('/comprehensive', [ComprehensiveReportController::class, 'index'])->name('comprehensive.index');
    Route::post('/comprehensive', [ComprehensiveReportController::class, 'store'])->name('comprehensive.store');
    Route::get('/comprehensive', [ComprehensiveReportController::class, 'show'])->name('comprehensive.show');
    // Map Report
    Route::get('/comprehensive/map', [MapController::class, 'showForm'])->name('comprehensive.map.form');
    Route::post('/comprehensive/map', [MapController::class, 'processForm'])->name('comprehensive.map.process');

    // Status Report
    Route::get('/comprehensive/status', [StatusController::class, 'showForm'])->name('comprehensive.status.form');
    Route::post('/comprehensive/status', [StatusController::class, 'processForm'])->name('comprehensive.status.process');

    // Unit Stages
    Route::get('/comprehensive/unit-stages', [UnitStagesController::class, 'showForm'])->name('comprehensive.unit-stages.form');
    Route::post('/comprehensive/unit-stages', [UnitStagesController::class, 'processForm'])->name('comprehensive.unit-stages.process');

    // Unit Details
    Route::get('/comprehensive/unit-details', [UnitDetailsController::class, 'showForm'])->name('comprehensive.unit-details.form');
    Route::post('/comprehensive/unit-details', [UnitDetailsController::class, 'processForm'])->name('comprehensive.unit-details.process');

    // Visits/Payments/Contracts
    Route::get('/comprehensive/vpc', [VisitsPaymentsContractsController::class, 'showForm'])->name('comprehensive.vpc.form');
    Route::post('/comprehensive/vpc', [VisitsPaymentsContractsController::class, 'processForm'])->name('comprehensive.vpc.process');

    // Disinterest Reasons
    Route::get('/comprehensive/disinterest', [DisinterestReasonsController::class, 'showForm'])->name('comprehensive.disinterest.form');
    Route::post('/comprehensive/disinterest', [DisinterestReasonsController::class, 'processForm'])->name('comprehensive.disinterest.process');

    // Unit Statistics
    Route::get('/comprehensive/unit-statistics', [UnitStatisticsController::class, 'showForm'])->name('comprehensive.unit-statistics.form');
    Route::post('/comprehensive/unit-statistics', [UnitStatisticsController::class, 'processForm'])->name('comprehensive.unit-statistics.process');

    // Appointments
    Route::get('/comprehensive/appointments', [AppointmentsController::class, 'showForm'])->name('comprehensive.appointments.form');
    Route::post('/comprehensive/appointments', [AppointmentsController::class, 'processForm'])->name('comprehensive.appointments.process');

    // Targeted Report
    Route::get('/comprehensive/targeted', [TargetedReportController::class, 'showForm'])->name('comprehensive.targeted.form');
    Route::post('/comprehensive/targeted', [TargetedReportController::class, 'processForm'])->name('comprehensive.targeted.process');

    // Unit Sales
    Route::get('/comprehensive/unit-sales', [UnitSalesController::class, 'showForm'])->name('comprehensive.unit-sales.form');
    Route::post('/comprehensive/unit-sales', [UnitSalesController::class, 'processForm'])->name('comprehensive.unit-sales.process');

    // Project Summary
    Route::get('/comprehensive/project-summary', [ProjectSummaryController::class, 'showForm'])->name('comprehensive.project-summary.form');
    Route::post('/comprehensive/project-summary', [ProjectSummaryController::class, 'processForm'])->name('comprehensive.project-summary.process');

    // Site
    Route::put('/comprehensive/site/{site}', [SiteController::class, 'update'])->name('comprehensive.site.update');

    Route::get('/comprehensive/site', [SiteController::class, 'index'])->name('comprehensive.site.index');
    Route::post('/comprehensive/site', [SiteController::class, 'store'])->name('comprehensive.site.store');
    Route::get('/comprehensive/site/{id}', [SiteController::class, 'show'])->name('comprehensive.site.show');
    Route::post('/comprehensive/site/store-or-update', [SiteController::class, 'storeOrUpdate'])->name('comprehensive.site.storeOrUpdate');

    Route::get('/campaign/tags', [CrmAdvertisingCampaignController::class, 'getTags'])->name('campaign.tags');

    Route::delete('/comprehensive/site/{site}', [SiteController::class, 'destroy'])->name('comprehensive.site.destroy');

    // جميع الرواتب تأخذ site كمعامل
    Route::get('/project-plans/{site}', [ProjectPlanController::class, 'index'])->name('project_plan.index');
    Route::get('/project-plans/{site}/create', [ProjectPlanController::class, 'create'])->name('project-plans.create');
    Route::post('/project-plans/{site}/store', [ProjectPlanController::class, 'store'])->name('project-plans.store');
    Route::get('/project-plans/{site}/edit/{id}', [ProjectPlanController::class, 'edit'])->name('project-plans.edit');
    Route::put('/project-plans/{site}/update/{id}', [ProjectPlanController::class, 'update'])->name('project-plans.update');
    Route::delete('/project-plans/{site}/destroy/{id}', [ProjectPlanController::class, 'destroy'])->name('project-plans.destroy');
    Route::post('/project-plans/{site}/update-status/{id}', [ProjectPlanController::class, 'updateStatus'])->name('project-plans.update-status');
   Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/cost-legislator', [CostLegislatorController::class, 'index'])->name('admin.cost.legislator.index');
    Route::post('/cost-legislator/calculate', [CostLegislatorController::class, 'calculate'])->name('admin.cost.legislator.calculate');
    Route::get('/cost-legislator/results', [CostLegislatorController::class, 'results'])->name('admin.cost.legislator.results');
    Route::post('/cost-legislator/export', [CostLegislatorController::class, 'export'])->name('admin.cost.legislator.export');
});
Route::get('/staff-tree', [StaffTreeController::class, 'index'])->name('staff-tree.index');
Route::get('/staff-tree/project/{id}', [StaffTreeController::class, 'getProject'])->name('staff-tree.project');
});
Route::get('/api/sites/filter', [SiteController::class, 'filterByDate'])->name('api.sites.filter');
require __DIR__ . '/auth.php';
