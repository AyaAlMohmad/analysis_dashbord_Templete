<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TeamController extends Controller
{
    public function teamCategoryReport(Request $request)
    {
        $site = $request->get('site', 'dhahran');

        $apiUrls = [
            'dhahran' => 'https://crm.azyanaldhahran.com/api/custom-reports/api_team_category_report_data',
            'bashaer' => 'https://crm.azyanalbashaer.com/api/custom-reports/api_team_category_report_data',
            'jeddah' => 'https://crm.azyanjeddah.com/api/custom-reports/api_team_category_report_data',
            'alfursan' => 'https://crm.azyanalfursan.com/api/custom-reports/api_team_category_report_data',

        ];

        if (!in_array($site, ['dhahran', 'bashaer','jeddah','alfursan'])) {
            return response()->json(['error' => 'Invalid site selected.'], 400);
        }

        try {
            $response = Http::timeout(30)->get($apiUrls[$site]);

            if (!$response->successful()) {
                $errorMessage = 'Failed to fetch data from ' . $site;
                if ($request->ajax()) {
                    return response()->json(['error' => $errorMessage], 500);
                } else {
                    return view('teams.team_category', [
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

            return view('teams.team_category', compact('site', 'data'));

        } catch (\Exception $e) {
            $errorMessage = 'An error occurred while connecting to the site: ' . $site;
            if ($request->ajax()) {
                return response()->json(['error' => $errorMessage], 500);
            }

            return view('teams.team_category', [
                'site' => $site,
                'data' => [],
                'error' => $errorMessage
            ]);
        }
    }
    public function teamReport(Request $request)
    {
        $site = $request->get('site', 'dhahran');

        $apiUrls = [
            'dhahran' => 'https://crm.azyanaldhahran.com/api/custom-reports/api_team_category_report_data',
            'bashaer' => 'https://crm.azyanalbashaer.com/api/custom-reports/api_team_category_report_data',
            'jeddah' => 'https://crm.azyanjeddah.com/api/custom-reports/api_team_category_report_data',
            'alfursan' => 'https://crm.azyanalfursan.com/api/custom-reports/api_team_category_report_data',

        ];

        if (!in_array($site, ['dhahran', 'bashaer','jeddah','alfursan'])) {
            return response()->json(['error' => 'Invalid site selected.'], 400);
        }

        try {
            $response = Http::timeout(30)->get($apiUrls[$site]);

            if (!$response->successful()) {
                $errorMessage = 'Failed to fetch data from ' . $site;
                if ($request->ajax()) {
                    return response()->json(['error' => $errorMessage], 500);
                } else {
                    return view('teams.team_category', [
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

            return view('teams.team_report', compact('site', 'data'));

        } catch (\Exception $e) {
            $errorMessage = 'An error occurred while connecting to the site: ' . $site;
            if ($request->ajax()) {
                return response()->json(['error' => $errorMessage], 500);
            }

            return view('teams.team_report', [
                'site' => $site,
                'data' => [],
                'error' => $errorMessage
            ]);
        }
    }
    public function teamReportSearch(Request $request)
    {
        $validated = $request->validate([
            'site' => 'required|in:dhahran,bashaer',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'role' => 'nullable',
            'employee' => 'nullable',
        ]);

        $apiUrls = [
            'dhahran' => 'https://crm.azyanaldhahran.com/api/custom-reports/api_generate_team_report',
            'bashaer' => 'https://crm.azyanalbashaer.com/api/custom-reports/api_generate_team_report',
            'jeddah' => 'https://crm.azyanjeddah.com/api/custom-reports/api_generate_team_report',
            'alfursan' => 'https://crm.azyanalfursan.com/api/custom-reports/api_generate_team_report',
        ];

        try {
            $response = Http::timeout(30)
                ->asForm() // important for multipart/form-data
                ->post($apiUrls[$validated['site']], [
                    'from_date' => $validated['from_date'],
                    'to_date' => $validated['to_date'],
                    'role' => $validated['role'],
                    'employee' => $validated['employee'],
                ]);

            if ($response->successful()) {
                $result = $response->json();
                // dd($result);
                return view('teams.team_report_result', [
                    'result' => $result,
                    'site' => $validated['site']
                ]);
            }

            return back()->with('error', 'Failed to fetch report data.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error contacting API: ' . $e->getMessage());
        }
    }
public function teamCategorySearch(Request $request)
{
    $validated = $request->validate([
        'site' => 'required|in:dhahran,bashaer',
        'from_date' => 'nullable|date',
        'to_date' => 'nullable|date|after_or_equal:from_date',
        'role' => 'nullable',
        'employee' => 'nullable',
    ]);

    $apiUrls = [
        'dhahran' => 'https://crm.azyanaldhahran.com/api/custom-reports/api_team_category_report',
        'bashaer' => 'https://crm.azyanalbashaer.com/api/custom-reports/api_team_category_report',
        'jeddah' => 'https://crm.azyanjeddah.com/api/custom-reports/api_team_category_report',
        'alfursan' => 'https://crm.azyanalfursan.com/api/custom-reports/api_team_category_report',
    ];

    try {
        $response = Http::timeout(30)
            ->asForm() // important for multipart/form-data
            ->post($apiUrls[$validated['site']], [
                'from_date' => $validated['from_date'],
                'to_date' => $validated['to_date'],
                'role' => $validated['role'],
                'employee' => $validated['employee'],
            ]);

        if ($response->successful()) {
            $result = $response->json();
            return view('teams.team_category_result', [
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
