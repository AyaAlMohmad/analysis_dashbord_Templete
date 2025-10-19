<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class SalesController extends Controller
{
    public function salesReport(Request $request)
{
    $site = $request->get('site', 'dhahran');

    $apiUrls = [
        'dhahran' => 'https://crm.azyanaldhahran.com/api/custom-reports/sales',
        'bashaer' => 'https://crm.azyanalbashaer.com/api/custom-reports/sales',
        'jeddah' => 'https://crm.azyanjeddah.com/api/custom-reports/sales',
        'alfursan' => 'https://crm.azyanalfursan.com/api/custom-reports/sales',
    ];

    if (!in_array($site, ['dhahran', 'bashaer','jeddah','alfursan'])) {
        return view('sales.sale', [
            'site' => $site,
            'data' => [],
            'error' => 'Invalid site selected.'
        ]);
    }

    try {
        $response = Http::timeout(20)->get($apiUrls[$site]);

        if (!$response->successful()) {
            return view('sales.sale', [
                'site' => $site,
                'data' => [],
                'error' => 'Failed to fetch data from API: ' . $site
            ]);
        }

        $data = $response->json();

        return view('sales.sale', [
            'site' => $site,
            'data' => $data,
            'error' => null
        ]);

    } catch (\Exception $e) {
        return view('sales.sale', [
            'site' => $site,
            'data' => [],
            'error' => 'Connection error: ' . $e->getMessage()
        ]);
    }
}
public function salesReportResult(Request $request)
{
    $site = $request->get('site', 'dhahran');
    $fromDate = $request->get('from_date');
    $toDate = $request->get('to_date');

    $apiUrls = [
        'dhahran' => 'https://crm.azyanaldhahran.com/api/custom-reports/sales-report',
        'bashaer' => 'https://crm.azyanalbashaer.com/api/custom-reports/sales-report',
        'jeddah' => 'https://crm.azyanjeddah.com/api/custom-reports/sales-report',
        'alfursan' => 'https://crm.azyanalfursan.com/api/custom-reports/sales-report',

    ];

    if (!in_array($site, ['dhahran', 'bashaer','jeddah','alfursan'])) {
        return response()->json(['error' => 'Invalid site selected.'], 400);
    }

    try {
        $response = Http::timeout(30)->asForm()->post($apiUrls[$site], [
            'from_date' => $fromDate,
            'to_date' => $toDate,
        ]);

        if (!$response->successful()) {
            $errorMessage = 'Failed to fetch data from ' . $site;
            if ($request->ajax()) {
                return response()->json(['error' => $errorMessage], 500);
            } else {
                return view('sales.sales_report_result', [
                    'site' => $site,
                    'data' => [],
                    'error' => $errorMessage
                ]);
            }
        }

        $data = $response->json();

        if ($request->ajax()) {
            return response()->json($data);
        }

        return view('sales.sales_report_result', compact('site', 'data', 'fromDate', 'toDate'));

    } catch (\Exception $e) {
        $errorMessage = 'An error occurred while connecting to the site: ' . $site;
        if ($request->ajax()) {
            return response()->json(['error' => $errorMessage], 500);
        }

        return view('sales.sales_report_result', [
            'site' => $site,
            'data' => [],
            'error' => $errorMessage
        ]);
    }
}
}
