<?php

namespace App\Http\Controllers\ComprehensiveReport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UnitStagesController extends ReportBaseController
{
    protected $reportView = 'comprehensive.partials.unit-stages';
    protected $formRouteName = 'admin.comprehensive.unit-stages.process';

    public function index(Request $request)
    {
        $dhahranUnitStages = $this->getDhahranUnitStages();
        $albashaerUnitStages = $this->getAlbashaerUnitStages();
        
        return view($this->reportView, [
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            'dhahranUnitStages' => $dhahranUnitStages,
            'albashaerUnitStages' => $albashaerUnitStages,
        ]);
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
}