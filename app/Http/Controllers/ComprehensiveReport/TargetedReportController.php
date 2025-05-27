<?php

namespace App\Http\Controllers\ComprehensiveReport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TargetedReportController extends ReportBaseController
{
    protected $reportView = 'comprehensive.partials.targeted-report';
    protected $formRouteName = 'admin.comprehensive.targeted.process';

    public function index(Request $request)
    {
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        
        $albashaerTargetedReport = $this->getAlbashaerTargetedReport($from_date, $to_date);
        $dhahranTargetedReport = $this->getDhahranTargetedReport($from_date, $to_date);
        
        return view($this->reportView, [
            'from_date' => $from_date,
            'to_date' => $to_date,
            'albashaerTargetedReport' => $albashaerTargetedReport,
            'dhahranTargetedReport' => $dhahranTargetedReport,
        ]);
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
}