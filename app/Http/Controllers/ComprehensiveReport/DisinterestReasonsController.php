<?php

namespace App\Http\Controllers\ComprehensiveReport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DisinterestReasonsController extends ReportBaseController
{
    protected $reportView = 'comprehensive.partials.disinterest-reasons';
    protected $formRouteName = 'admin.comprehensive.disinterest.process';

    public function index(Request $request)
    {
        $albashaerDisinterestReasons = $this->getAlbashaerDisinterestReasons();
        $dhahranDisinterestReasons = $this->getDhahranDisinterestReasons();
        
        return view($this->reportView, [
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            'albashaerDisinterestReasons' => $albashaerDisinterestReasons,
            'dhahranDisinterestReasons' => $dhahranDisinterestReasons,
        ]);
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
}