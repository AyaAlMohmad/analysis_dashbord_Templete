<?php

namespace App\Http\Controllers\ComprehensiveReport;

use App\Http\Controllers\Controller;
use App\Models\ComprehensiveReport;
use App\Models\ReportData;
use App\Models\ReportSection;
use Illuminate\Http\Request;
use App\Models\Site;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    public function filterByDate(Request $request)
    {
        try {
            $from = $request->input('from_date');
            $to = $request->input('to_date');

            $reports = ComprehensiveReport::with('reportData')
                ->whereDate('from_date', '>=', $from)
                ->whereDate('to_date', '<=', $to)
                ->get();

            $siteIds = [];

            foreach ($reports as $report) {
                foreach ($report->reportData as $data) {
                    $siteIds[] = $data->site_id;
                }
            }

            $siteIds = array_unique($siteIds);

            $sites = Site::whereIn('id', $siteIds)->get();

            return response()->json($sites);
        } catch (\Exception $e) {
            Log::error('Error filtering sites: ' . $e->getMessage());

            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        return Site::all();
    }

    public function store(Request $request)
    {
        return $this->storeOrUpdate($request);
    }

    public function storeOrUpdate(Request $request)
    {
        DB::beginTransaction();

        try {
            // 1. معالجة بيانات الموقع
            $site = $this->handleSiteData($request);

            // 2. معالجة التقرير
            $report = $this->handleReportData($request);

            // 3. معالجة أقسام البيانات وحذف غير الموجودة
            $this->handleReportSections($request, $site, $report);

            DB::commit();

            return response()->json([
                'message' => $request->filled('site_id') ? 'Site updated successfully.' : 'Report saved successfully.',
                'site' => $site,
                'report' => $report,
            ], $request->filled('site_id') ? 200 : 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in storeOrUpdate: ' . $e->getMessage());

            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * معالجة بيانات الموقع (إنشاء أو تحديث)
     */
    private function handleSiteData(Request $request)
    {
        $siteName = Str::slug($request->name ?? 'unnamed-site-' . now()->timestamp);
        $siteFolder = "public/sites/{$siteName}";
        $today = now()->format('Y-m-d');

        // معالجة الصور والملفات
        $logo = $request->file('logo_path')?->store($siteFolder, 'public');
        $logoWhite = $request->file('logo_path_white')?->store($siteFolder, 'public');
        $map = $request->file('map_path')?->store("{$siteFolder}/{$today}", 'public');

        if ($request->filled('site_id')) {
            // تحديث الموقع الموجود
            $site = Site::findOrFail($request->site_id);

            if ($request->has('name')) {
                $site->name = $request->name;
            }
            if ($logo) $site->logo_path = $logo;
            if ($logoWhite) $site->logo_path_white = $logoWhite;
            if ($map) $site->map_path = $map;

            $site->save();
            return $site;
        } else {
            // إنشاء موقع جديد
            return Site::create([
                'name' => $request->name ?? 'Unnamed Site',
                'logo_path' => $logo,
                'logo_path_white' => $logoWhite,
                'map_path' => $map,
            ]);
        }
    }

    /**
     * معالجة بيانات التقرير
     */
    private function handleReportData(Request $request)
    {
        if ($request->filled(['filter_from_date', 'filter_to_date'])) {
            return ComprehensiveReport::firstOrCreate([
                'from_date' => $request->filter_from_date,
                'to_date' => $request->filter_to_date,
            ]);
        }

        return null;
    }

    /**
     * معالجة أقسام التقرير وحذف غير الموجودة
     */
    private function handleReportSections(Request $request, Site $site, ?ComprehensiveReport $report)
    {
        if (!$report) return;

        // خريطة جميع الحقول الممكنة من الواجهة
        $allPossibleSections = [
            'map_path' => 'colored_map',
            'reserved_projects' => 'reserved_report',
            'contracted_projects' => 'contracts_report',
            'unit_cases' => 'status_item',
            'project_summary' => 'project_summary',
            'unit_stages' => 'unitStages',
            'unit_stats' => 'unitStatisticsByStage',
            'visits_payments' => 'visits_payments_contracts',
            'disinterest_reasons' => 'disinterest_reasons',
            'total_sales' => 'unit_sales',
            'source_stats' => 'source_stats',
            'monthly_appointments' => 'monthly_appointments',
            'target' => 'targeted_report',
        ];

        // الحصول على الأقسام المرسلة فعلياً من الواجهة
        $submittedSections = [];
        foreach ($allPossibleSections as $requestKey => $sectionName) {
            if ($request->has($requestKey)) {
                $submittedSections[] = $sectionName;
            }
        }

        // 1. حذف الأقسام غير المرسلة من الواجهة
        if (!empty($submittedSections)) {
            ReportData::where('site_id', $site->id)
                ->where('report_id', $report->id)
                ->whereHas('section', function($query) use ($submittedSections) {
                    $query->whereNotIn('name', $submittedSections);
                })
                ->delete();
        }

        // 2. معالجة الأقسام المرسلة
        foreach ($allPossibleSections as $requestKey => $sectionName) {
            if ($request->has($requestKey)) {
                $section = ReportSection::where('name', $sectionName)->first();

                if ($section) {
                    $rawData = $request->input($requestKey);

                    // معالجة البيانات الخاصة بكل قسم
                    $formattedData = $this->formatSection($sectionName, $rawData);

                    // حفظ أو تحديث البيانات
                    ReportData::updateOrCreate(
                        [
                            'site_id' => $site->id,
                            'report_id' => $report->id,
                            'section_id' => $section->id,
                        ],
                        [
                            'data' => $formattedData,
                        ]
                    );
                }
            }
        }
    }

    protected function formatSection($key, $value)
    {
        switch ($key) {
            case 'reserved_report':
                $projects = collect($value)->values()->map(function ($item) {
                    return [
                        'project_name' => $item['project_name'] ?? '',
                        'developer_name' => $item['developer'] ?? '',
                        'total_units' => (int)($item['units'] ?? 0),
                        'reserved_units' => (int)($item['reserved'] ?? 0),
                    ];
                });
                return [
                    'projects' => $projects,
                    'chart_labels' => $projects->pluck('project_name'),
                    'chart_data' => $projects->pluck('reserved_units'),
                ];

            case 'contracts_report':
                $projects = collect($value)->values()->map(function ($item) {
                    return [
                        'project_name' => $item['project_name'] ?? '',
                        'developer_name' => $item['developer'] ?? '',
                        'total_units' => (int)($item['units'] ?? 0),
                        'contracted_units' => (int)($item['contracted_units'] ?? 0),
                    ];
                });
                return [
                    'projects' => $projects,
                    'chart_labels' => $projects->pluck('project_name'),
                    'chart_data' => $projects->pluck('contracted_units'),
                ];

          case 'status_item':
    $input = $value;
    $groups = [];
    // تغيير hidden إلى blocked لتتناسب مع الواجهة
    $statuses = ['reserved', 'contracted', 'available', 'blocked'];
    $totals = [];

    $allData = [];

    // البيانات الأساسية
    $allData[] = $input;

    // البيانات الإضافية
    if (!empty($input['additional'])) {
        foreach ($input['additional'] as $add) {
            $allData[] = $add;
        }
    }

    $groupId = 1;
    foreach ($allData as $group) {
        $statusList = [];

        foreach ($statuses as $status) {
            // استخدام hidden_beneficiary إذا كان status هو blocked
            $beneficiaryKey = $status === 'blocked' ? 'hidden_beneficiary' : $status . '_beneficiary';
            $nonBeneficiaryKey = $status === 'blocked' ? 'hidden_non_beneficiary' : $status . '_non_beneficiary';

            $beneficiary = (int)($group[$beneficiaryKey] ?? 0);
            $nonBeneficiary = (int)($group[$nonBeneficiaryKey] ?? 0);
            $total = $beneficiary + $nonBeneficiary;

            $statusList[] = [
                'status_name' => $status,
                'total' => $total,
                'beneficiary' => $beneficiary,
                'non_beneficiary' => $nonBeneficiary,
            ];

            // جمع التوتالات
            if (!isset($totals[$status])) {
                $totals[$status] = ['beneficiary' => 0, 'non_beneficiary' => 0];
            }
            $totals[$status]['beneficiary'] += $beneficiary;
            $totals[$status]['non_beneficiary'] += $nonBeneficiary;
        }

        $groups[] = [
            'group_id' => $groupId++,
            'total_items' => (int)($group['units_per_stage'] ?? 0),
            'statuses' => $statusList,
        ];
    }

    $statusNames = collect($statuses)->map(fn($s) => ['unit_status' => $s]);

    return [
        'groups' => $groups,
        'statuses' => $statusNames,
        'totals' => [
            'total_items' => array_sum(array_column($groups, 'total_items')),
            'status_totals' => $totals,
        ],
    ];

            case 'project_summary':
                $options = ['A', 'B', 'C', 'D', 'E', 'F'];
                $totalItems = [];
                $reservedItems = [];
                $percentages = [];

                foreach ($options as $option) {
                    $total = (int)($value[$option]['total_units'] ?? 0);
                    $reserved = (int)($value[$option]['total_reservations'] ?? 0);
                    $percentage = ($total > 0) ? round(($reserved / $total) * 100, 2) : 0;

                    $totalItems[$option] = $total;
                    $reservedItems[$option] = $reserved;
                    $percentages[$option] = $percentage;
                }

                return [
                    'items' => [
                        'options' => array_combine($options, $options),
                        'total_items' => $totalItems,
                        'reserved_items' => $reservedItems,
                    ],
                    'percentages' => $percentages
                ];

            case 'unitStages':
                $groups = [];
                $statuses = ['available', 'reserved', 'contracted', 'blocked'];
                $totals = array_fill_keys($statuses, 0);

                foreach (['A_Abq', 'B_Ewan', 'C_Najdiyah', 'D_Ruweiq', 'E_Maqam', 'F_Roof'] as $model) {
                    $group = $value[$model] ?? [];
                    $groups[$model] = [];
                    foreach ($statuses as $status) {
                        $groups[$model][$status] = (int)($group[$status] ?? 0);
                        $totals[$status] += $groups[$model][$status];
                    }
                    $groups[$model]['total'] = array_sum($groups[$model]);
                }
                return [
                    'groups' => $groups,
                    'statuses' => $statuses,
                    'totals' => $totals,
                    'grand_total' => array_sum($totals)
                ];

            case 'unitStatisticsByStage':
                $result = [];
                foreach ($value as $phaseKey => $models) {
                    $groupId = str_replace('phase', '', $phaseKey);
                    $report = [];
                    $totals = ['total_items' => 0, 'available_blocked' => 0, 'min_rate' => null, 'max_rate' => null];

                    foreach ($models as $type => $data) {
                        $count = (int)($data['units'] ?? 0);
                        $availBlock = (int)($data['sold_available'] ?? 0);
                        $min = is_numeric($data['from']) ? (float)$data['from'] : null;
                        $max = is_numeric($data['to']) ? (float)$data['to'] : null;

                        $totals['total_items'] += $count;
                        $totals['available_blocked'] += $availBlock;
                        $totals['min_rate'] = is_null($totals['min_rate']) ? $min : min($totals['min_rate'], $min);
                        $totals['max_rate'] = is_null($totals['max_rate']) ? $max : max($totals['max_rate'], $max);

                        $report[] = [
                            'type' => trim($type, ' ()'),
                            'count' => $count,
                            'available_blocked' => $availBlock,
                            'min_rate' => $min,
                            'max_rate' => $max,
                        ];
                    }
                    $result[] = [
                        'group_id' => $groupId,
                        'group_name' => $groupId,
                        'report_data' => $report,
                        'totals' => $totals,
                    ];
                }
                return $result;

            case 'visits_payments_contracts':
                $output = ['date_from' => now()->startOfMonth()->format('Y-m-d'), 'date_to' => now()->endOfMonth()->format('Y-m-d')];

                // معالجة البيانات الحالية فقط
                $output['current_month'] = [
                    'visits' => (int)($value['visits']['current_month'] ?? 0),
                    'payments' => (int)($value['payments']['current_month'] ?? 0),
                    'contracts' => (int)($value['contracted_units']['current_month'] ?? 0),
                ];

                return $output;

            case 'disinterest_reasons':
                $reasons = collect($value)->values()->map(function ($item) {
                    return [
                        'reason' => $item['reason'] ?? '',
                        'count' => (int)($item['clients'] ?? 0),
                        'percentage' => (float)($item['percentage'] ?? 0),
                    ];
                });
                $totalLeads = $reasons->sum('count');
                return ['total_leads' => $totalLeads, 'reasons' => $reasons];

            case 'unit_sales':
                $models = ['A', 'B', 'C', 'D', 'E', 'F'];
                $reservedTotals = [];
                $contractedTotals = [];

                foreach ($models as $model) {
                    $reservedTotals[$model] = [
                        'area' => (float)($value[$model]['reserved_area'] ?? 0),
                        'price' => (float)($value[$model]['reserved_price'] ?? 0),
                    ];
                    $contractedTotals[$model] = [
                        'area' => (float)($value[$model]['contracted_area'] ?? 0),
                        'price' => (float)($value[$model]['contracted_price'] ?? 0),
                    ];
                }

                return [
                    'models' => $models,
                    'items_by_model' => $value,
                    'min_area' => collect($value)->pluck('reserved_area')->merge(collect($value)->pluck('contracted_area'))->filter()->min() ?? 0,
                    'max_area' => collect($value)->pluck('reserved_area')->merge(collect($value)->pluck('contracted_area'))->filter()->max() ?? 0,
                    'reserved_totals' => $reservedTotals,
                    'contracted_totals' => $contractedTotals,
                ];

           case 'source_stats':
    // إذا كانت البيانات فارغة، إرجاع هيكل افتراضي
    if (empty($value)) {
        return [
            'sources' => [],
            'totals' => [
                'total_leads' => 0,
                'visited_leads' => 0,
                'paid_leads' => 0,
                'contract_leads' => 0,
                'visited_percent' => 0,
                'paid_from_visited_percent' => 0,
                'contract_from_paid_percent' => 0,
            ]
        ];
    }

    $sources = collect($value)->values()->map(function ($item) {
        $customers = (int)($item['customers'] ?? 0);
        $visits = (int)($item['visits'] ?? 0);
        $registrations = (int)($item['registrations'] ?? 0);
        $contracted_units = (int)($item['contracted_units'] ?? 0);

        return [
            'source_name' => $item['source'] ?? 'غير معروف',
            'total_leads' => $customers,
            'visited_leads' => $visits,
            'visited_percent' => $customers > 0 ? round(($visits / $customers) * 100, 2) : 0,
            'paid_leads' => $registrations,
            'paid_from_visited_percent' => $visits > 0 ? round(($registrations / $visits) * 100, 2) : 0,
            'contract_leads' => $contracted_units,
            'contract_from_paid_percent' => $registrations > 0 ? round(($contracted_units / $registrations) * 100, 2) : 0,
        ];
    });

    $totalLeads = $sources->sum('total_leads');
    $totalVisited = $sources->sum('visited_leads');
    $totalPaid = $sources->sum('paid_leads');
    $totalContracts = $sources->sum('contract_leads');

    return [
        'sources' => $sources->toArray(),
        'totals' => [
            'total_leads' => $totalLeads,
            'visited_leads' => $totalVisited,
            'paid_leads' => $totalPaid,
            'contract_leads' => $totalContracts,
            'visited_percent' => $totalLeads > 0 ? round(($totalVisited / $totalLeads) * 100, 2) : 0,
            'paid_from_visited_percent' => $totalVisited > 0 ? round(($totalPaid / $totalVisited) * 100, 2) : 0,
            'contract_from_paid_percent' => $totalPaid > 0 ? round(($totalContracts / $totalPaid) * 100, 2) : 0,
        ]
    ];

            case 'monthly_appointments':
                $appointments = (int)($value['appointments'] ?? 0);
                $visited = (int)($value['visited'] ?? 0);

                return [
                    'total_appointments' => $appointments,
                    'completed_visits' => $visited,
                    'success_rate' => $appointments > 0 ? round(($visited / $appointments) * 100, 2) : 0,
                    'external_rate' => (int)($value['external_rate'] ?? 0),
                ];

            case 'targeted_report':
                $statuses = ['مهتم', 'موعد', 'زيارة', 'حجز', 'إلغاء', 'عقد'];
                $data = [];

                foreach ($value as $item) {
                    $status = $item['status'] ?? '';
                    $target = (int)($item['target'] ?? 0);
                    $achieved = (int)($item['achieved'] ?? 0);

                    $data[$status] = [
                        'target' => $target,
                        'count' => $achieved,
                        'percentage' => $target > 0 ? round(($achieved / $target) * 100, 2) : 0
                    ];
                }

                $totalTarget = array_sum(array_column($data, 'target'));
                $totalAchieved = array_sum(array_column($data, 'count'));

                $data['total'] = [
                    'target' => $totalTarget,
                    'count' => $totalAchieved,
                    'percentage' => $totalTarget > 0 ? round(($totalAchieved / $totalTarget) * 100, 2) : 0
                ];

                return ['data' => $data];

            default:
                return $value;
        }
    }

    public function show($id)
    {
        $site = Site::with('reportData.section')->findOrFail($id);
        return response()->json($site);
    }

    public function update(Request $request, $id)
    {
        $request->merge(['site_id' => $id]);
        return $this->storeOrUpdate($request);
    }

    public function destroy(Site $site)
    {
        try {
            DB::beginTransaction();

            $site->reportData()->delete();
            $site->delete();

            DB::commit();
            return response()->json(['message' => 'Site deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error deleting site', 'error' => $e->getMessage()], 500);
        }
    }
}
