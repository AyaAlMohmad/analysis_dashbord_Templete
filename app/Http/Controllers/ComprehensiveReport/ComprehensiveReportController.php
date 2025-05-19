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
        $dhahranStatusData = $this->getDhahranStatus();
        $bashaerStatusData = $this->getbashaerStatus();
        $dhahranUnitStages = $this->getDhahranUnitStages();
        $albashaerUnitStages = $this->getAlbashaerUnitStages();
        $dhahranUnitStatisticsByStage = $this->getDhahranUnitStatisticsByStage();
        $albashaerUnitStatisticsByStage = $this->getAlbashaerUnitStatisticsByStage();

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
// dd($dhahranStatusData);
        // dd($albashaerUnitStatisticsByStage);
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
            'dhahranUnitStatisticsByStage' => $dhahranUnitStatisticsByStage,
            'albashaerUnitStatisticsByStage' => $albashaerUnitStatisticsByStage,
            'projects' => $mergedProjects,
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
}
