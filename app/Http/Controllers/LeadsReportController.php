<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;

class LeadsReportController extends Controller
{
    public function index()
    {
        // Initialize totals for each site
        $totals = [
            'dhahran' => ['added' => 0, 'edited' => 0],
            'bashaer' => ['added' => 0, 'edited' => 0],
            'jeddah' => ['added' => 0, 'edited' => 0],
        ];

        // Initialize error messages
        $errors = [
            'dhahran' => null,
            'bashaer' => null,
            'jeddah' => null,
        ];

        // Initialize leads data containers
        $leadsData = [
            'dhahran' => [],
            'bashaer' => [],
            'jeddah' => [],
        ];

        // Fetch leads data from Dhahran site
        $this->fetchLeadsData(
            'https://crm.azyanaldhahran.com/api/leads',
            'dhahran',
            $leadsData,
            $totals,
            $errors
        );

        // Fetch leads data from Bashaer site
        $this->fetchLeadsData(
            'https://crm.azyanalbashaer.com/api/leads',
            'bashaer',
            $leadsData,
            $totals,
            $errors
        );
        // Fetch leads data from Jeddah site
        $this->fetchLeadsData(
            'https://crm.azyanjeddah.com/api/leads',
            'jeddah',
            $leadsData,
            $totals,
            $errors
        );

        // Separate variables for easy access in view
        $leadsAzyanDhahran = $leadsData['dhahran'];
        $leadsAzyanBashaer = $leadsData['bashaer'];
        $leadsAzyanJeddah = $leadsData['jeddah'];

        // Return the view with all required data
        return view('reports.leads', compact(
            'leadsData',
            'leadsAzyanDhahran',
            'leadsAzyanBashaer',
            'leadsAzyanJeddah',
            'totals',
            'errors'
        ));
    }

    private function fetchLeadsData($url, $key, &$leadsData, &$totals, &$errors)
    {
        try {
            $response = Http::get($url);

            if ($response->successful()) {
                $leadsData[$key] = $response->json();

                // Sum up added and edited leads
                foreach ($leadsData[$key] as $day => $stats) {
                    $totals[$key]['added'] += $stats['added'];
                    $totals[$key]['edited'] += $stats['edited'];
                }
            } else {
                $errors[$key] = 'Failed to fetch leads data from ' . $key;
            }
        } catch (\Exception $e) {
            $errors[$key] = 'Connection error with server ' . $key;
        }
    }

    private function fetchleads($url, &$error)
    {
        try {
            $response = Http::get($url);
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'by_category' => $data['by_category'] ?? [],
                    'total' => $data['total'] ?? 0
                ];
            } else {
                $error = 'Failed to fetch data';
                return ['by_category' => [], 'total' => 0];
            }
        } catch (\Exception $e) {
            $error = 'Connection error';
            return ['by_category' => [], 'total' => 0];
        }
    }

    public function export(Request $request)
    {
        $site = $request->get('site', 'dhahran');
        $type = $request->get('type', 'pdf');

        // Determine the appropriate URL based on the site
        $url = $site === 'bashaer'
            ? 'https://crm.azyanalbashaer.com/api/leads'
            : 'https://crm.azyanaldhahran.com/api/leads';

        $error = null;
        $data = $this->fetchleads($url, $error);

        if ($error) {
            return back()->with('error', 'Failed to fetch data from the selected site');
        }

        $byCategory = $data['by_category'] ?? [];
        $chartImage = null;

        switch ($type) {
            case 'pdf':
                $pdf = Pdf::loadView('exports.leads_pdf', [
                    'site' => $site,
                    'leads' => $data,
                    'byCategory' => $byCategory,
                    'maxCount' => max($byCategory ?: [0])
                ]);

                return $pdf->download("leads_{$site}.pdf");

            case 'csv':
                return $this->exportCsv($byCategory, $site);

            default:
                abort(400, 'Invalid export type');
        }
    }

    protected function exportCsv($data, $site)
    {
        $filename = "leads_{$site}_" . now()->format('Ymd_His') . ".csv";

        $headers = [
            "Content-type" => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Category', 'Item Count']);

            foreach ($data as $category => $count) {
                fputcsv($file, [$category, $count]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
