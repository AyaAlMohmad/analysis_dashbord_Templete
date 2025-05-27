<?php

namespace App\Http\Controllers\ComprehensiveReport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MapController extends ReportBaseController
{
    protected $reportView = 'comprehensive.partials.map';
    protected $formRouteName = 'admin.comprehensive.map.process';

    public function index(Request $request)
    {
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        
        $dhahranColoredMap = $this->getDhahranColoredMap($from_date, $to_date);
        $albashaerColoredMap = $this->getAlbashaerColoredMap($from_date, $to_date);
        
        return view($this->reportView, [
            'from_date' => $from_date,
            'to_date' => $to_date,
            'dhahranColoredMap' => $dhahranColoredMap,
            'albashaerColoredMap' => $albashaerColoredMap,
        ]);
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