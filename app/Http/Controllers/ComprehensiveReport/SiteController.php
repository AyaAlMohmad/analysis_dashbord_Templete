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
            // Log the error and return JSON
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
        $siteName = Str::slug($request->name ?? 'unnamed-site-' . now()->timestamp);
        $siteFolder = "public/sites/{$siteName}";
        $today = now()->format('Y-m-d');

        $logo = $request->file('logo_path')?->store($siteFolder, 'public');
        $logoWhite = $request->file('logo_path_white')?->store($siteFolder, 'public');
        $map = $request->file('map_path')?->store("{$siteFolder}/{$today}", 'public');

        $site = Site::create([
            'name' => $request->name ?? 'Unnamed Site',
            'logo_path' => $logo,
            'logo_path_white' => $logoWhite,
            'map_path' => $map,
        ]);

        $report = ComprehensiveReport::create([
            'from_date' => $request->filter_from_date,
            'to_date' => $request->filter_to_date,
        ]);

        $sectionMap = [
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

        foreach ($sectionMap as $requestKey => $sectionName) {
            if ($request->has($requestKey)) {
                $section = ReportSection::where('name', $sectionName)->first();
                if ($section) {
                    $data = $request->input($requestKey);


                    if (is_array($data) && count($data) === 1 && array_is_list($data)) {
                        $data = $data[0];
                    }

                    ReportData::create([
                        'site_id' => $site->id,
                        'report_id' => $report->id,
                        'section_id' => $section->id,
                        'data' => $data,
                    ]);
                }
            }
        }

        return response()->json([
            'message' => 'Report saved successfully.',
            'site' => $site,
            'report' => $report,
        ], 201);
    }


    public function storeOrUpdate(Request $request)
    {
        // dd($request->all());
        $site = null;
        $isUpdate = $request->filled('site_id');

        $siteName = Str::slug($request->name ?? 'unnamed-site-' . now()->timestamp);
        $siteFolder = "public/sites/{$siteName}";
        $today = now()->format('Y-m-d');

        $logo = $request->file('logo_path')?->store($siteFolder, 'public');
        $logoWhite = $request->file('logo_path_white')?->store($siteFolder, 'public');
        $map = $request->file('map_path')?->store("{$siteFolder}/{$today}", 'public');

        if ($isUpdate) {
            $site = Site::findOrFail($request->site_id);
            $site->name = $request->name ?? $site->name;
            if ($logo) $site->logo_path = $logo;
            if ($logoWhite) $site->logo_path_white = $logoWhite;
            if ($map) $site->map_path = $map;
            $site->save();
        } else {
            $site = Site::create([
                'name' => $request->name ?? 'Unnamed Site',
                'logo_path' => $logo,
                'logo_path_white' => $logoWhite,
                'map_path' => $map,
            ]);
        }

        $report = $isUpdate
            ? ComprehensiveReport::firstOrCreate([
                'from_date' => $request->filter_from_date,
                'to_date' => $request->filter_to_date,
            ])
            : ComprehensiveReport::create([
                'from_date' => $request->filter_from_date,
                'to_date' => $request->filter_to_date,
            ]);
            $sectionMap = [
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

            foreach ($sectionMap as $requestKey => $sectionName) {
                if ($request->has($requestKey)) {
                    $section = ReportSection::where('name', $sectionName)->first();

                    if ($section) {
                        $rawData = $request->input($requestKey);


                        $formattedData = $this->formatSection($sectionName, $rawData);

                        if ($isUpdate) {
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
                        } else {
                            ReportData::create([
                                'site_id' => $site->id,
                                'report_id' => $report->id,
                                'section_id' => $section->id,
                                'data' => $formattedData,
                            ]);
                        }
                    }
                }
            }




        return response()->json([
            'message' => $isUpdate ? 'Site updated successfully.' : 'Report saved successfully.',
            'site' => $site,
            'report' => $report,
        ], $isUpdate ? 200 : 201);
    }
    // public function storeOrUpdate(Request $request, $siteId = null)
    // {
    //     // dd($request->all());
    //     try {
    //         $site = $siteId ? Site::findOrFail($siteId) : new Site();
    //         $site->name = $request->name;
    //         $site->save();

    //         $sections = [
    //             'reserved_report' => $request->input('reserved_projects', []),
    //             'contracts_report' => $request->input('contracted_projects', []),
    //             'unitStages' => $request->input('unit_stages', []),
    //             'unitStatisticsByStage' => $request->input('unit_stats', []),
    //             'visits_payments_contracts' => $request->input('visits_payments', []),
    //             'disinterest_reasons' => $request->input('disinterest_reasons', []),
    //             'total_sales' => $request->input('total_sales', []),
    //             'source_stats' => $request->input('source_stats', []),
    //             'monthly_appointments' => $request->input('monthly_appointments', []),
    //             'targeted_report' => $request->input('target', []),
    //         ];

    //         foreach ($sections as $key => $value) {
    //             $reportData = ReportData::updateOrCreate([
    //                 'site_id' => $site->id,
    //                 'report_section_id' => ReportSection::where('name', $key)->value('id'),
    //             ], [
    //                 'data' => $this->formatSection($key, $value)
    //             ]);
    //         }

    //         if ($request->ajax()) {
    //             return response()->json(['status' => 'success', 'message' => 'تم الحفظ بنجاح']);
    //         }

    //         return redirect()->back()->with('success', 'تم الحفظ بنجاح');
    //     } catch (\Exception $e) {
    //         if ($request->ajax()) {
    //             return response()->json(['status' => 'error', 'message' => 'حدث خطأ أثناء الحفظ: ' . $e->getMessage()]);
    //         }
    //         return redirect()->back()->with('error', 'حدث خطأ أثناء الحفظ: ' . $e->getMessage());
    //     }
    // }

    protected function formatSection($key, $value)
    {
        switch ($key) {
            case 'reserved_report':
                $projects = collect($value)->values()->map(function ($item) {
                    return [
                        'project_name' => $item['project_name'] ?? '',
                        'developer_name' => $item['developer'] ?? '',
                        'total_units' => (int)($item['units'] ?? 0),
                        'reserved_units' => (int)($item['reserved_units'] ?? 0),
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
                    $statuses = ['reserved', 'contracted', 'available', 'hidden'];
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
                            $beneficiary = (int)($group[$status . '_beneficiary'] ?? 0);
                            $nonBeneficiary = (int)($group[$status . '_non_beneficiary'] ?? 0);
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
                $statuses = ['available', 'reserved', 'contacted', 'blocked'];
                $totals = array_fill_keys($statuses, 0);
                foreach (['A','B','C','D','E','F'] as $model) {
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
                foreach (['current_month', 'last_month', 'two_months_ago'] as $month) {
                    $output[$month] = [];
                    for ($i = 1; $i <= 5; $i++) {
                        $output[$month]["week_$i"] = [
                            'visits' => (int)($value['visits']["week$i"] ?? 0),
                            'payments' => (int)($value['payments']["week$i"] ?? 0),
                            'contracts' => (int)($value['contracted_units']["week$i"] ?? 0),
                        ];
                    }
                    $output[$month]['month_total'] = [
                        'visits' => (int)($value['visits'][$month] ?? 0),
                        'payments' => (int)($value['payments'][$month] ?? 0),
                        'contracts' => (int)($value['contracted_units'][$month] ?? 0),
                    ];
                }
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
                $sources = collect($value)->values()->map(function ($item) {
                    return [
                        'source_name' => $item['source'] ?? '',
                        'total_leads' => (int)($item['customers'] ?? 0),
                        'visited_leads' => (int)($item['visits'] ?? 0),
                        'visited_percent' => (float)($item['visit_rate'] ?? 0),
                        'paid_leads' => (int)($item['registrations'] ?? 0),
                        'paid_from_visited_percent' => (float)($item['registration_rate'] ?? 0),
                        'contract_leads' => (int)($item['contracted_units'] ?? 0),
                        'contract_from_paid_percent' => (float)($item['contract_rate'] ?? 0),
                    ];
                });
                return [
                    'sources' => $sources,
                    'totals' => [
                        'total_leads' => $sources->sum('total_leads'),
                        'visited_leads' => $sources->sum('visited_leads'),
                        'paid_leads' => $sources->sum('paid_leads'),
                        'contract_leads' => $sources->sum('contract_leads'),
                        'visited_percent' => 0, // احسبها إذا أردت
                        'paid_from_visited_percent' => 0,
                        'contract_from_paid_percent' => 0,
                    ]
                ];

            case 'monthly_appointments':
                return [
                    'total_appointments' => (int)($value['appointments'] ?? 0),
                    'completed_visits' => (int)($value['visited'] ?? 0),
                ];

            case 'targeted_report':
                $statuses = ['مهتم', 'موعد', 'زيارة', 'حجز', 'إلغاء', 'عقد'];
                $data = [];
                foreach ($value as $item) {
                    $status = $item['status'] ?? '';
                    $data[$status] = [
                        'target' => (int)($item['target'] ?? 0),
                        'count' => (int)($item['achieved'] ?? 0),
                        'percentage' => 0
                    ];
                }
                $data['total'] = array_sum(array_column($data, 'count'));
                return ['data' => $data];

            default:
                return [];
        }
    }

    public function show($id)
    {
        $site = Site::with('reportData.section')->findOrFail($id);
        return response()->json($site);
    }


    public function update(Request $request, $id)
    {
        $site = Site::findOrFail($id);
        $siteName = Str::slug($request->name ?? $site->name);
        $siteFolder = "public/sites/{$siteName}";
        $today = now()->format('Y-m-d');

        if ($request->hasFile('logo_path')) {
            if ($site->logo_path && Storage::disk('public')->exists($site->logo_path)) {
                Storage::disk('public')->delete($site->logo_path);
            }
            $site->logo_path = $request->file('logo_path')->store($siteFolder, 'public');
        }

        if ($request->hasFile('logo_path_white')) {
            if ($site->logo_path_white && Storage::disk('public')->exists($site->logo_path_white)) {
                Storage::disk('public')->delete($site->logo_path_white);
            }
            $site->logo_path_white = $request->file('logo_path_white')->store($siteFolder, 'public');
        }

        if ($request->hasFile('map_path')) {
            if ($site->map_path && Storage::disk('public')->exists($site->map_path)) {
                Storage::disk('public')->delete($site->map_path);
            }
            $site->map_path = $request->file('map_path')->store("{$siteFolder}/{$today}", 'public');
        }

        if ($request->has('name')) {
            $site->name = $request->name;
        }

        $site->save();

        $report = null;

        if ($request->has(['filter_from_date', 'filter_to_date'])) {
            $report = ComprehensiveReport::firstOrCreate([
                'from_date' => $request->filter_from_date,
                'to_date' => $request->filter_to_date,
            ]);

            $sectionMap = [
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

            foreach ($sectionMap as $requestKey => $sectionName) {
                if ($request->has($requestKey)) {
                    $section = ReportSection::where('name', $sectionName)->first();
                    if ($section) {
                        $data = $request->input($requestKey);


                        if (is_array($data) && count($data) === 1 && array_is_list($data)) {
                            $data = $data[0];
                        }

                        ReportData::updateOrCreate(
                            [
                                'site_id' => $site->id,
                                'report_id' => $report->id,
                                'section_id' => $section->id,
                            ],
                            [
                                'data' => $data,
                            ]
                        );
                    }
                }
            }
        }

        return response()->json([
            'message' => 'Site updated successfully.',
            'site' => $site,
            'report' => $report,
        ]);
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
