<?php

namespace App\Http\Controllers\ComprehensiveReport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ComprehensiveReportController extends Controller
{
    public function form()
    {
        return view('comprehensive.form');
    }

    public function index(Request $request)
    {
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $manualProjects = $request->input('projects', []);
        $albashaerData = $this->getAlbashaerReserved($from_date, $to_date);
        $dhahranData = $this->getDhahranReserced($from_date, $to_date);
        $dhahranColoredMap = $this->getDhahranColoredMap($from_date, $to_date);
        $albashaerColoredMap = $this->getAlbashaerColoredMap($from_date, $to_date);
        $dhahranStatusData = $this->getDhahranStatus();
        $bashaerStatusData = $this->getbashaerStatus();
        $dhahranUnitStages = $this->getDhahranUnitStages();
        $albashaerUnitStages = $this->getAlbashaerUnitStages();
        $unitDetailsByStageResultDhahran = $this->unitDetailsByStageResultDhahran();
        $unitDetailsByStageResultAlbashaer = $this->unitDetailsByStageResultAlbashaer();
        $albashaerVPCData = $this->getAlbashaerVisitsPaymentsContracts();
        $dhahranVPCData = $this->getDhahranVisitsPaymentsContracts();
        $albashaerDisinterestReasons = $this->getAlbashaerDisinterestReasons();
        $dhahranDisinterestReasons = $this->getDhahranDisinterestReasons();
        $albashaerUnitStatisticsByStage = $this->getAlbashaerUnitStatisticsByStage();
        $dhahranUnitStatisticsByStage = $this->getDhahranUnitStatisticsByStage();
        $dhahranMonthlyAppointments = $this->getDhahranMonthlyAppointments();
        $albashaerMonthlyAppointments = $this->getAlbashaerMonthlyAppointments();
        $albashaerTargetedReport = $this->getAlbashaerTargetedReport($from_date, $to_date);
        $dhahranTargetedReport = $this->getDhahranTargetedReport($from_date, $to_date);
        $albashaerUnitSales = $this->getAlbashaerUnitSales($from_date, $to_date);
        $dhahranUnitSales = $this->getDhahranUnitSales($from_date, $to_date);
        $albashaerProjectSummary = $this->getAlbashaerProjectSummaryReport($from_date, $to_date);
        $dhahranProjectSummary = $this->getDhahranProjectSummaryReport($from_date, $to_date);

        $mergedProjects = collect($albashaerData['projects'] ?? [])
            ->merge($dhahranData['projects'] ?? [])
            ->merge($manualProjects ?? [])
            ->map(function ($project) {
                return [
                    'name' => $project['project_name'] ?? '',
                    'developer' => $project['developer_name'] ?? '-',
                    'units' => $project['total_units'] ?? 0,
                    'reserved' => $project['reserved_units'] ?? 0,
                ];
            })
            ->sortByDesc('reserved')
            ->values();

        $mergedChartLabels = $mergedProjects->pluck('name');
        $mergedChartData = $mergedProjects->pluck('reserved');
        $albashaerDataContract = $this->getAlbashaerContract($from_date, $to_date);
        $dhahranDataContract = $this->getDhahranContract($from_date, $to_date);
        $mergedProjectsContract = collect($albashaerDataContract['projects'] ?? [])
            ->merge($dhahranDataContract['projects'] ?? [])
            ->merge($manualProjects ?? [])
            ->map(function ($project) {
                return [
                    'name' => $project['project_name'] ?? '',
                    'developer' => $project['developer_name'] ?? '-',
                    'units' => $project['total_units'] ?? 0,
                    'Contracts' => $project['contracts_units'] ?? 0,
                ];
            })
            ->sortByDesc('Contracts')
            ->values();

        $mergedChartLabelsContract = $mergedProjectsContract->pluck('name');
        $mergedChartDataContract = $mergedProjectsContract->pluck('Contracts');
        // dd($albashaerColoredMap);
        // dd($dhahranMonthlyAppointments);
        return view('comprehensive.index', [
            'from_date' => $from_date,
            'to_date' => $to_date,
            'dhahranData' => $dhahranData,
            'albashaerData' => $albashaerData,
            'dhahranDataContract' => $dhahranDataContract,
            'albashaerDataContract' => $albashaerDataContract,
            'dhahranStatusData' => $dhahranStatusData,
            'bashaerStatusData' => $bashaerStatusData,
            'dhahranUnitStages' => $dhahranUnitStages,
            'albashaerUnitStages' => $albashaerUnitStages,
            'unitDetailsByStageResultDhahran' => $unitDetailsByStageResultDhahran,
            'unitDetailsByStageResultAlbashaer' => $unitDetailsByStageResultAlbashaer,
            'albashaerUnitStatisticsByStage' => $albashaerUnitStatisticsByStage,
            'dhahranUnitStatisticsByStage' => $dhahranUnitStatisticsByStage,
            'projects' => $mergedProjects,
            'albashaerVPCData' => $albashaerVPCData,
            'dhahranVPCData' => $dhahranVPCData,
            'albashaerDisinterestReasons' => $albashaerDisinterestReasons,
            'dhahranDisinterestReasons' => $dhahranDisinterestReasons,
            'dhahranMonthlyAppointments' => $dhahranMonthlyAppointments,
            'albashaerMonthlyAppointments' => $albashaerMonthlyAppointments,
            'albashaerTargetedReport' => $albashaerTargetedReport,
            'dhahranTargetedReport' => $dhahranTargetedReport,
            'albashaerUnitSales' => $albashaerUnitSales,
            'dhahranUnitSales' => $dhahranUnitSales,
            'albashaerProjectSummary' => $albashaerProjectSummary,
            'dhahranProjectSummary' => $dhahranProjectSummary,
            'dhahranColoredMap' => $dhahranColoredMap,
            'albashaerColoredMap' => $albashaerColoredMap,
            'chart' => [
                'labels' => $mergedChartLabels,
                'data' => $mergedChartData
            ],
            'projectsContract' => $mergedProjectsContract,
            'chartContract' => [
                'labels' => $mergedChartLabelsContract,
                'data' => $mergedChartDataContract
            ],

        ]);
    }

    /**
     *  getAlbashaerReserved API
     */
    private function getAlbashaerReserved($from_date, $to_date)
    {
        try {
            $response = Http::post('https://crm.azyanalbashaer.com/api/Item_reports/api_reserved_report', [
                'from_date' => $from_date,
                'to_date' => $to_date,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch data from Albashaer API',
            'projects' => [],
            'chart_labels' => [],
            'chart_data' => []
        ];
    }

    /**
     * getDhahranResercedAPI
     */
    private function getDhahranReserced($from_date, $to_date)
    {
        try {
            $response = Http::post('https://crm.azyanaldhahran.com/api/Item_reports/api_reserved_report', [
                'from_date' => $from_date,
                'to_date' => $to_date,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch data from Dhahran API',
            'projects' => [],
            'chart_labels' => [],
            'chart_data' => []
        ];
    }
    /**
     *  getAlbashaerContract API
     */
    private function getAlbashaerContract($from_date, $to_date)
    {
        try {
            $response = Http::post('https://crm.azyanalbashaer.com/api/Item_reports/api_contracts_report', [
                'from_date' => $from_date,
                'to_date' => $to_date,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch data from Albashaer API',
            'projects' => [],
            'chart_labels' => [],
            'chart_data' => []
        ];
    }

    /**
     * getDhahranContractAPI
     */
    private function getDhahranContract($from_date, $to_date)
    {
        try {
            $response = Http::post('https://crm.azyanaldhahran.com/api/Item_reports/api_contracts_report', [
                'from_date' => $from_date,
                'to_date' => $to_date,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch data from Dhahran API',
            'projects' => [],
            'chart_labels' => [],
            'chart_data' => []
        ];
    }
    /**
     * getDhahranStatus API
     */
    private function getDhahranStatus()
    {
        try {
            $response = Http::get('https://crm.azyanaldhahran.com/api/Item_reports_api/api_status_item');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Status API Error: " . $e->getMessage());
        }

        return [
            'groups' => [],
            'statuses' => [],
            'totals' => [],
        ];
    }
    /**
     * getbashaerStatus API
     */
    private function getbashaerStatus()
    {
        try {
            $response = Http::get('https://crm.azyanalbashaer.com/api/Item_reports_api/api_status_item');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Status API Error: " . $e->getMessage());
        }

        return [
            'groups' => [],
            'statuses' => [],
            'totals' => [],
        ];
    }
    private function getDhahranUnitStages()
    {
        try {
            $response = Http::get('https://crm.azyanaldhahran.com/api/Item_reports/unitStages');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Unit Stages API Error: " . $e->getMessage());
        }

        return [
            'stages' => [],
            'totals' => [],
        ];
    }

    /**
     * Get Albashaer Unit Stages Data
     */
    private function getAlbashaerUnitStages()
    {
        try {
            $response = Http::get('https://crm.azyanalbashaer.com/api/Item_reports/unitStages');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer Unit Stages API Error: " . $e->getMessage());
        }

        return [
            'stages' => [],
            'totals' => [],
        ];
    }
    /**
     * Get Dhahran Unit Statistics by Stage
     */
    private function unitDetailsByStageResultDhahran()
    {
        try {
            $response = Http::post('https://crm.azyanaldhahran.com/api/Item_reports/unit_details_by_stage_result');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Unit Statistics by Stage API Error: " . $e->getMessage());
        }

        return [
            'statistics' => [],
            'totals' => [],
        ];
    }

    /**
     * Get Albashaer Unit Statistics by Stage
     */
    private function unitDetailsByStageResultAlbashaer()
    {
        try {
            $response = Http::post('https://crm.azyanalbashaer.com/api/Item_reports/unit_details_by_stage_result');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer Unit Statistics by Stage API Error: " . $e->getMessage());
        }

        return [
            'statistics' => [],
            'totals' => [],
        ];
    }
    /**
     * Get Albashaer Visits, Payments and Contracts Data
     */
    private function getAlbashaerVisitsPaymentsContracts()
    {
        try {
            $response = Http::get('https://crm.azyanalbashaer.com/api/Item_reports/visits_payments_contracts_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer Visits Payments Contracts API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch data from Albashaer Visits Payments Contracts API',
            'data' => [
                'current_month' => [],
                'last_month' => [],
                'two_months_ago' => [],
            ],
            'date_from' => null,
            'date_to' => null
        ];
    }

    /**
     * Get Dhahran Visits, Payments and Contracts Data
     */
    private function getDhahranVisitsPaymentsContracts()
    {
        try {
            $response = Http::get('https://crm.azyanaldhahran.com/api/Item_reports/visits_payments_contracts_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Visits Payments Contracts API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch data from Dhahran Visits Payments Contracts API',
            'data' => [
                'current_month' => [],
                'last_month' => [],
                'two_months_ago' => [],
            ],
            'date_from' => null,
            'date_to' => null
        ];
    }
    /**
     * Get Albashaer Disinterest Reasons Data
     */
    private function getAlbashaerDisinterestReasons()
    {
        try {
            $response = Http::get('https://crm.azyanalbashaer.com/api/lead_reports/disinterest_reasons_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer Disinterest Reasons API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch data from Albashaer Disinterest Reasons API',
            'data' => [
                'total_leads' => 0,
                'reasons' => []
            ]
        ];
    }

    /**
     * Get Dhahran Disinterest Reasons Data
     */
    private function getDhahranDisinterestReasons()
    {
        try {
            $response = Http::get('https://crm.azyanaldhahran.com/api/lead_reports/disinterest_reasons_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Disinterest Reasons API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch data from Dhahran Disinterest Reasons API',
            'data' => [
                'total_leads' => 0,
                'reasons' => []
            ]
        ];
    }
    /**
     * Get Dhahran Unit Statistics by Stage
     */
    private function getDhahranUnitStatisticsByStage()
    {
        try {
            $response = Http::get('https://crm.azyanaldhahran.com/api/Item_reports/unitStatisticsByStage');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Unit Statistics by Stage API Error: " . $e->getMessage());
        }

        return [
            'statistics' => [],
            'totals' => [],
        ];
    }

    /**
     * Get Albashaer Unit Statistics by Stage
     */
    private function getAlbashaerUnitStatisticsByStage()
    {
        try {
            $response = Http::get('https://crm.azyanalbashaer.com/api/Item_reports/unitStatisticsByStage');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer Unit Statistics by Stage API Error: " . $e->getMessage());
        }

        return [
            'statistics' => [],
            'totals' => [],
        ];
    }
    /**
     * Get Dhahran Monthly Appointments Data
     */
    private function getDhahranMonthlyAppointments()
    {
        try {
            $response = Http::post('https://crm.azyanaldhahran.com/api/lead_reports/monthly_appointments_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Monthly Appointments API Error: " . $e->getMessage());
        }

        return [
            'appointments' => [],
            'totals' => [],
        ];
    }

    /**
     * Get Albashaer Monthly Appointments Data
     */
    private function getAlbashaerMonthlyAppointments()
    {
        try {
            $response = Http::post('https://crm.azyanalbashaer.com/api/lead_reports/monthly_appointments_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer Monthly Appointments API Error: " . $e->getMessage());
        }

        return [
            'appointments' => [],
            'totals' => [],
        ];
    }
    private function getAlbashaerTargetedReport($from_date, $to_date)
    {
        try {
            $response = Http::post('https://crm.azyanalbashaer.com/api/lead_reports/targeted_report_api', [
                'from_date' => $from_date,
                'to_date' => $to_date,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer Targeted Report API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch data from Albashaer Targeted Report API',
            'data' => [],
        ];
    }
    private function getDhahranTargetedReport($from_date, $to_date)
    {
        try {
            $response = Http::post('https://crm.azyanaldhahran.com/api/lead_reports/targeted_report_api', [
                'from_date' => $from_date,
                'to_date' => $to_date,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Targeted Report API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch data from Dhahran Targeted Report API',
            'data' => [],
        ];
    }
    /**
     * Get Albashaer Unit Sales Data
     */
    private function getAlbashaerUnitSales($from_date, $to_date)
    {
        try {
            $response = Http::get('https://crm.azyanalbashaer.com/api/Item_reports/unit_sales_api');

            if ($response->successful()) {
                $data = $response->json();
                if ($data['status'] ?? false) {
                    return $data; // تحتوي عادةً على ['status', 'message', 'data' => [...]]
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer Unit Sales API Error: " . $e->getMessage());
        }

        return [
            'status'  => false,
            'message' => 'Failed to fetch data from Albashaer Unit Sales API',
            'data'    => [],
        ];
    }

    /**
     * Get Dhahran Unit Sales Data
     */
    private function getDhahranUnitSales($from_date, $to_date)
    {
        try {
            $response = Http::get('https://crm.azyanaldhahran.com/api/Item_reports/unit_sales_api');

            if ($response->successful()) {
                $data = $response->json();
                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Unit Sales API Error: " . $e->getMessage());
        }

        return [
            'status'  => false,
            'message' => 'Failed to fetch data from Dhahran Unit Sales API',
            'data'    => [],
        ];
    }
    private function getAlbashaerProjectSummaryReport($from_date, $to_date)
    {
        try {
            $response = Http::get('https://crm.azyanalbashaer.com/api/Item_reports/api_project_summary_report');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer Project Summary API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch project summary report from Albashaer API',
            'data' => [],
        ];
    }
    private function getDhahranProjectSummaryReport($from_date, $to_date)
    {
        try {
            $response = Http::get('https://crm.azyanaldhahran.com/api/Item_reports/api_project_summary_report');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Project Summary API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch project summary report from Dhahran API',
            'data' => [],
        ];
    }
    private function getDhahranColoredMap($from_date, $to_date)
    {
        try {
            $response = Http::post('https://crm.azyanaldhahran.com/api/Item_reports/colored_map_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Project Summary API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch project summary report from Dhahran API',
            'data' => [],
        ];
    }
    private function getAlbashaerColoredMap($from_date, $to_date)
    {
        try {
            $response = Http::post('https://crm.azyanalbashaer.com/api/Item_reports/colored_map_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Project Summary API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch project summary report from Dhahran API',
            'data' => [],
        ];
    }
}
