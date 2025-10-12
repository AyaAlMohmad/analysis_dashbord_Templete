<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Cache;

class LeadsSourcesReportController extends Controller
{
    public function index()
    {
        $error = null;
        $allData = [];

        try {
            $allData = Cache::remember('leads_sources_separated', 3600, function () {
                $data = [];

                // Fetch data from the first source
                $response1 = Http::get('https://crm.azyanaldhahran.com/api/leads_sources');
                if ($response1->successful()) {
                    $data['aldhahran'] = $this->formatApiData($response1->json());
                }

                // Fetch data from the second source
                $response2 = Http::get('https://crm.azyanalbashaer.com/api/leads_sources');
                if ($response2->successful()) {
                    $data['albashaer'] = $this->formatApiData($response2->json());
                }

                return $data;
            });

            if (empty($allData)) {
                $error = "Failed to load data from sources.";
            }

        } catch (\Exception $e) {
            $error = "An error occurred while loading data: " . $e->getMessage();
            $allData = [];
        }

        return view('reports.leads_sources', compact('allData', 'error'));
    }

    private function formatApiData($apiData)
    {
        if (is_object($apiData)) {
            return (array)$apiData;
        }
        return $apiData;
    }
    public function sourceReport()
    {
        return view('sources.source_report');
    }
   public function sourceReportResult(Request $request)
{
    $validated = $request->validate([
        'site' => 'required|in:dhahran,bashaer,jeddah', // إضافة جدة
        'from_date' => 'nullable|date',
        'to_date' => 'nullable|date|after_or_equal:from_date',
    ]);

    $apiUrls = [
        'dhahran' => 'https://crm.azyanaldhahran.com/api/custom-reports/api_generate_source_report',
        'bashaer' => 'https://crm.azyanalbashaer.com/api/custom-reports/api_generate_source_report',
        'jeddah' => 'https://crm.azyanjeddah.com/api/custom-reports/api_generate_source_report', // إضافة جدة
    ];

    try {
        $response = Http::timeout(30)
            ->asForm() // important for multipart/form-data
            ->post($apiUrls[$validated['site']], [
                'from_date' => $validated['from_date'],
                'to_date' => $validated['to_date'],
            ]);

        if ($response->successful()) {
            $result = $response->json();
            return view('sources.source_report_result', [
                'result' => $result,
                'site' => $validated['site']
            ]);
        }

        return back()->with('error', 'Failed to fetch report data.');
    } catch (\Exception $e) {
        return back()->with('error', 'Error contacting API: ' . $e->getMessage());
    }
}

}
