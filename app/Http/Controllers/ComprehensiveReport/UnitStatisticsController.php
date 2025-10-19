<?php

namespace App\Http\Controllers\ComprehensiveReport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UnitStatisticsController extends ReportBaseController
{
    protected $reportView = 'comprehensive.partials.unit-statistics';
    protected $formRouteName = 'admin.comprehensive.unit-statistics.process';

    public function index(Request $request)
    {
        // $albashaerUnitStatistics = $this->getAlbashaerUnitStatisticsByStage();
        // $dhahranUnitStatistics = $this->getDhahranUnitStatisticsByStage();
        $albashaerUnitStatistics = $this->getAlbashaerSourceStats();
        $dhahranUnitStatistics = $this->getDhahranSourceStats();
        $jeddahUnitStatistics = $this->getJeddahSourceStats();
        $alfursanUnitStatistics = $this->getAlfursanSourceStats();
        return view($this->reportView, [
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            'albashaerUnitStatistics' => $albashaerUnitStatistics,
            'dhahranUnitStatistics' => $dhahranUnitStatistics,
            'jeddahUnitStatistics' => $jeddahUnitStatistics,
            'alfursanUnitStatistics' => $alfursanUnitStatistics,
        ]);
    }

    /**
     * Get Dhahran Source Statistics
     */
    private function getDhahranSourceStats()
    {
        try {
            $response = Http::get('https://crm.azyanaldhahran.com/api/lead_reports/source_stats_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Source Stats API Error: " . $e->getMessage());
        }

        return [
            'sources' => [],
        ];
    }

    /**
     * Get Albashaer Source Statistics
     */
    private function getAlbashaerSourceStats()
    {
        try {
            $response = Http::get('https://crm.azyanalbashaer.com/api/lead_reports/source_stats_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer Source Stats API Error: " . $e->getMessage());
        }

        return [
            'sources' => [],
        ];
    }

    /**
     * Get Jeddah Source Statistics - الدالة المضافة
     */
    private function getJeddahSourceStats()
    {
        try {
            $response = Http::get('https://crm.azyanjeddah.com/api/lead_reports/source_stats_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Jeddah Source Stats API Error: " . $e->getMessage());
        }

        return [
            'sources' => [],
        ];
    }
    private function getAlfursanSourceStats()
    {
        try {
            $response = Http::get('https://crm.azyanalfursan.com/api/lead_reports/source_stats_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Alfursan Source Stats API Error: " . $e->getMessage());
        }

        return [
            'sources' => [],
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

    private function getAlfursanUnitStatisticsByStage()
    {
        try {
            $response = Http::get('https://crm.azyanalfursan.com/api/Item_reports/unitStatisticsByStage');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Alfursan Unit Statistics by Stage API Error: " . $e->getMessage());
        }

        return [
            'statistics' => [],
            'totals' => [],
        ];
    }


}
