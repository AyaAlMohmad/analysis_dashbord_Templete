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
        $albashaerUnitStatistics = $this->getAlbashaerUnitStatisticsByStage();
        $dhahranUnitStatistics = $this->getDhahranUnitStatisticsByStage();
        
        return view($this->reportView, [
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            'albashaerUnitStatistics' => $albashaerUnitStatistics,
            'dhahranUnitStatistics' => $dhahranUnitStatistics,
        ]);
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