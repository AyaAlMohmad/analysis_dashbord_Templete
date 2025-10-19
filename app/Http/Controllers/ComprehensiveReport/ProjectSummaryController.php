<?php

namespace App\Http\Controllers\ComprehensiveReport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProjectSummaryController extends ReportBaseController
{
    protected $reportView = 'comprehensive.partials.project-summary';
    protected $formRouteName = 'admin.comprehensive.project-summary.process';

    public function index(Request $request)
    {
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        $albashaerProjectSummary = $this->getAlbashaerProjectSummaryReport($from_date, $to_date);
        $dhahranProjectSummary = $this->getDhahranProjectSummaryReport($from_date, $to_date);
        $jeddahProjectSummary = $this->getJeddahProjectSummaryReport($from_date, $to_date);
        $alfursanProjectSummary = $this->getAlfursanProjectSummaryReport($from_date, $to_date);
// dd($jeddahProjectSummary);
        return view($this->reportView, [
            'from_date' => $from_date,
            'to_date' => $to_date,
            'albashaerProjectSummary' => $albashaerProjectSummary,
            'dhahranProjectSummary' => $dhahranProjectSummary,
            'jeddahProjectSummary' => $jeddahProjectSummary,
            'alfursanProjectSummary' => $alfursanProjectSummary,
        ]);
    }

    private function getAlbashaerProjectSummaryReport($from_date = null, $to_date = null)
    {
        try {
            $formData = [];

            if ($from_date) {
                $formData['from_date'] = $from_date;
            }

            if ($to_date) {
                $formData['to_date'] = $to_date;
            }

            // استخدام POST إذا كانت التواريخ موجودة، وإلا استخدام GET

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

    private function getDhahranProjectSummaryReport($from_date = null, $to_date = null)
    {
        try {
            $formData = [];

            if ($from_date) {
                $formData['from_date'] = $from_date;
            }

            if ($to_date) {
                $formData['to_date'] = $to_date;
            }

            // استخدام POST إذا كانت التواريخ موجودة، وإلا استخدام GET

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

    private function getJeddahProjectSummaryReport($from_date = null, $to_date = null)
    {
        try {
            $formData = [];

            if ($from_date) {
                $formData['from_date'] = $from_date;
            }

            if ($to_date) {
                $formData['to_date'] = $to_date;
            }

            // استخدام POST إذا كانت التواريخ موجودة، وإلا استخدام GET

                $response = Http::get('https://crm.azyanjeddah.com/api/Item_reports/api_project_summary_report');


            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Jeddah Project Summary API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch project summary report from Jeddah API',
            'data' => [],
        ];
    }

    private function getAlfursanProjectSummaryReport($from_date = null, $to_date = null)
    {
        try {
            $formData = [];

            if ($from_date) {
                $formData['from_date'] = $from_date;
            }

            if ($to_date) {
                $formData['to_date'] = $to_date;
            }

            // استخدام POST إذا كانت التواريخ موجودة، وإلا استخدام GET

                $response = Http::get('https://crm.azyanalfursan.com/api/Item_reports/api_project_summary_report');


            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Alfursan Project Summary API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch project summary report from Alfursan API',
            'data' => [],
        ];
    }

}
