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

   
}
