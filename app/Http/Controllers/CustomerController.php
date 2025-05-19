<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CustomerController extends Controller
{
    // Show the form to select site and date range
    public function showForm()
    {
        return view('customers.form');
    }

    // Fetch customer report from selected CRM based on site and date range
    public function fetchReport(Request $request)
    {
        $request->validate([
            'site' => 'required|in:aldhahran,albashaer',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $siteMap = [
            'aldhahran' => 'https://crm.azyanaldhahran.com',
            'albashaer' => 'https://crm.azyanalbashaer.com',
        ];
        $site = $request->site;
        $baseUrl = $siteMap[$site];
        $url = "$baseUrl/api/custom-reports/api_customers_report";

        try {
            $response = Http::get($url, [
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
            ]);

            $data = $response->json();

            if (!is_array($data)) {
                $data = [];
            }

            return view('customers.report', [
                'data' => $data,
                'site' => $site,
                'from' => $request->from_date,
                'to' => $request->to_date,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to connect to the server.']);
        }
    }
}
