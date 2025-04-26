<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;

class CallLogsReportController extends Controller
{
    public function index()
    {
        $callLogs = [
            'dhahran' => ['added' => [], 'started' => [], 'ended' => []],
            'bashaer' => ['added' => [], 'started' => [], 'ended' => []]
        ];

        $totals = [
            'dhahran' => ['added' => 0, 'started' => 0, 'ended' => 0],
            'bashaer' => ['added' => 0, 'started' => 0, 'ended' => 0]
        ];

        $errors = [];

        foreach ([
            'dhahran' => 'https://crm.azyanaldhahran.com/api/call_logs',
            'bashaer' => 'https://crm.azyanalbashaer.com/api/call_logs'
        ] as $location => $url) {
            try {
                $response = Http::get($url);
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    $callLogs[$location] = [
                        'added' => $data['added'] ?? [],
                        'started' => $data['start_time'] ?? [],
                        'ended' => $data['end_time'] ?? []
                    ];
                    
                    $totals[$location] = $data['totals'] ?? [
                        'added' => 0, 
                        'started' => 0, 
                        'ended' => 0
                    ];
                } else {
                    $errors[$location] = "Failed to fetch data from $location";
                }
            } catch (\Exception $e) {
                $errors[$location] = "Connection error for $location";
            }
        }

        return view('reports.call_logs', compact('callLogs', 'totals', 'errors'));
    }

    private function fetchcall_logs($url, &$error)
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
    
        $url = $site === 'bashaer'
            ? 'https://crm.azyanalbashaer.com/api/call_logs'
            : 'https://crm.azyanaldhahran.com/api/call_logs';
    
        $error = null;
        $data = $this->fetchcall_logs($url, $error);
    
        if ($error) {
            return back()->with('error', 'Failed to fetch data from the selected site');
        }
    
        $byCategory = $data['by_category'] ?? [];
        $chartImage = null;

        switch ($type) {
            case 'pdf':
                $pdf = Pdf::loadView('exports.call_logs_pdf', [
                    'site' => $site,
                    'call_logs' => $data,
                    'byCategory' => $byCategory,
                    'maxCount' => max($byCategory ?: [0]) 
                ]);
                
                return $pdf->download("call_logs_{$site}.pdf");
                
            case 'csv':
                return $this->exportCsv($byCategory, $site);
                
            default:
                abort(400, 'Invalid export type');
        }
    }
    
    protected function exportCsv($data, $site)
    {
        $filename = "call_logs_{$site}_" . now()->format('Ymd_His') . ".csv";
        
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
