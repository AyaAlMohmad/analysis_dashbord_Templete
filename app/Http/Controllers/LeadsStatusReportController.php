<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;

class LeadsStatusReportController extends Controller
{
    public function index()
    {
        $data = [
            'dhahran' => [],
            'bashaer' => [],
            'jeddah'=>[]

        ];

        $totals = [
            'dhahran' => 0,
            'bashaer' => 0,
            'jeddah' => 0
        ];

        $errors = [
            'dhahran' => null,
            'bashaer' => null,
            'jeddah' => null
        ];

        // Fetch Dhahran data
        $this->fetchData(
            'https://crm.azyanaldhahran.com/api/leads_status',
            'dhahran',
            $data,
            $totals,
            $errors
        );
        // Fetch Jeddah data
        $this->fetchData(
            'https://crm.azyanjeddah.com/api/leads_status',
            'jeddah',
            $data,
            $totals,
            $errors
        );

        // Fetch Bashaer data
        $this->fetchData(
            'https://crm.azyanalbashaer.com/api/leads_status',
            'bashaer',
            $data,
            $totals,
            $errors
        );
        // Fetch Alfursan data
        $this->fetchData(
            'https://crm.azyanalfursan.com/api/leads_status',
            'alfursan',
            $data,
            $totals,
            $errors
        );

       $dataDhahran= $data['dhahran'];
        $dataBashaer= $data['bashaer'];
        $dataJeddah= $data['jeddah'];
        $dataAlfursan= $data['alfursan'];
        return view('reports.leads_status', compact('data', 'dataDhahran', 'dataJeddah','dataBashaer', 'dataAlfursan','totals', 'errors'));
    }

    private function fetchData($url, $location, &$data, &$totals, &$errors)
    {
        try {
            $response = Http::get($url);

            if ($response->successful()) {
                $data[$location] = $response->json();
                $totals[$location] = array_sum($data[$location]);
            } else {
                $errors[$location] = "Failed to fetch data from Azyan " . ucfirst($location);
            }
        } catch (\Exception $e) {
            $errors[$location] = "Connection error to Azyan " . ucfirst($location);
        }
    }

    private function fetchleads_status($url, &$error)
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

    // public function export(Request $request)
    // {
    //     $site = $request->get('site', 'dhahran');
    //     $type = $request->get('type', 'pdf');

    //     $url = $site === 'bashaer'
    //         ? 'https://crm.azyanalbashaer.com/api/leads_status'
    //         : 'https://crm.azyanaldhahran.com/api/leads_status';

    //     $error = null;
    //     $data = $this->fetchleads_status($url, $error);

    //     if ($error) {
    //         return back()->with('error', 'Failed to fetch data from the selected site');
    //     }

    //     $byCategory = $data['by_category'] ?? [];
    //     $chartImage = null;

    //     switch ($type) {
    //         case 'pdf':
    //             $pdf = Pdf::loadView('exports.leads_status_pdf', [
    //                 'site' => $site,
    //                 'leads_status' => $data,
    //                 'byCategory' => $byCategory,
    //                 'maxCount' => max($byCategory ?: [0])
    //             ]);

    //             return $pdf->download("leads_status_{$site}.pdf");

    //         case 'csv':
    //             return $this->exportCsv($byCategory, $site);

    //         default:
    //             abort(400, 'Invalid export type');
    //     }
    // }

    protected function exportCsv($data, $site)
    {
        $filename = "leads_status_{$site}_" . now()->format('Ymd_His') . ".csv";

        $headers = [
            "Content-type" => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $callback = function() use ($data) {
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
