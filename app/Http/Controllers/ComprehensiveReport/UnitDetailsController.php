<?php

namespace App\Http\Controllers\ComprehensiveReport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UnitDetailsController extends ReportBaseController
{
    protected $reportView = 'comprehensive.partials.unit-details';
    protected $formRouteName = 'admin.comprehensive.unit-details.process';

    public function index(Request $request)
    {
        $unitDetailsByStageResultDhahran = $this->unitDetailsByStageResultDhahran();
        $unitDetailsByStageResultAlbashaer = $this->unitDetailsByStageResultAlbashaer();
        $unitDetailsByStageResultJeddah = $this->unitDetailsByStageResultJeddah();
        $unitDetailsByStageResultAlfursan = $this->unitDetailsByStageResultAlfursan();
// dd($unitDetailsByStageResultJeddah);
        return view($this->reportView, [
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            'unitDetailsByStageResultDhahran' => $unitDetailsByStageResultDhahran,
            'unitDetailsByStageResultAlbashaer' => $unitDetailsByStageResultAlbashaer,
            'unitDetailsByStageResultJeddah' => $unitDetailsByStageResultJeddah,
            'unitDetailsByStageResultAlfursan' => $unitDetailsByStageResultAlfursan,
        ]);
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
                    // إرجاع البيانات مباشرة بدون 'reports'
                    return $data['data']['reports'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Unit Statistics by Stage API Error: " . $e->getMessage());
        }

        return [];
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
                    // إرجاع البيانات مباشرة بدون 'reports'
                    return $data['data']['reports'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer Unit Statistics by Stage API Error: " . $e->getMessage());
        }

        return [];
    }

    /**
     * Get Jeddah Unit Statistics by Stage
     */
    private function unitDetailsByStageResultJeddah()
    {
        try {
            $response = Http::post('https://crm.azyanjeddah.com/api/Item_reports/unit_details_by_stage_result');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    // إرجاع البيانات مباشرة بدون 'reports'
                    return $data['data']['reports'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Jeddah Unit Statistics by Stage API Error: " . $e->getMessage());
        }

        return [];
    }
    private function unitDetailsByStageResultAlfursan()
    {
        try {
            $response = Http::post('https://crm.azyanalfursan.com/api/Item_reports/unit_details_by_stage_result');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    // إرجاع البيانات مباشرة بدون 'reports'
                    return $data['data']['reports'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Alfursan Unit Statistics by Stage API Error: " . $e->getMessage());
        }

        return [];
    }
}
