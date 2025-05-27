<?php

namespace App\Http\Controllers\ComprehensiveReport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StatusController extends ReportBaseController
{
    protected $reportView = 'comprehensive.partials.status';
    protected $formRouteName = 'admin.comprehensive.status.process';

    public function index(Request $request)
    {
        $dhahranStatusData = $this->getDhahranStatus();
        $bashaerStatusData = $this->getbashaerStatus();
        
        return view($this->reportView, [
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            'dhahranStatusData' => $dhahranStatusData,
            'bashaerStatusData' => $bashaerStatusData,
        ]);
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
}