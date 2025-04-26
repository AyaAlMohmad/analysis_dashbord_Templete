<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class ItemReportController extends Controller
{
    public function index()
    {
        $data = [
            'dhahran' => [],
            'bashaer' => []
        ];

        $errors = [
            'dhahran' => null,
            'bashaer' => null
        ];

        // Fetch data for Dhahran
        $this->fetchItemData(
            'https://crm.azyanaldhahran.com/api/items',
            'dhahran',
            $data,
            $errors
        );

        // Fetch data for Bashaer
        $this->fetchItemData(
            'https://crm.azyanalbashaer.com/api/items',
            'bashaer',
            $data,
            $errors
        );
        $dataDhahran= $data['dhahran'];
        $dataBashaer= $data['bashaer'];
        return view('reports.items', compact('data', 'dataDhahran', 'dataBashaer', 'errors'));

    }
   
    
    private function fetchItemData($url, $location, &$data, &$errors)
    {
        try {
            $response = Http::get($url);

            if ($response->successful()) {
                $data[$location] = $response->json();
            } else {
                $errors[$location] = "Failed to fetch data from Azyan " . ucfirst($location);
            }
        } catch (\Exception $e) {
            $errors[$location] = "Error connecting to Azyan " . ucfirst($location) . " API";
        }
    }

    private function fetchItems($url, &$error)
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

    public function map($site)
{
    $validSites = ['dhahran', 'bashaer'];

    if (!in_array($site, $validSites)) {
        return redirect()->back()->with('error', 'Invalid site selected.');
    }

    $apiUrls = [
        'dhahran' => 'https://crm.azyanaldhahran.com/api/items/all',
        'bashaer' => 'https://crm.azyanalbashaer.com/api/items/all'
    ];

    $response = Http::timeout(30)->get($apiUrls[$site]);

    if (!$response->ok()) {
        return redirect()->back()->with('error', 'Failed to fetch data from ' . $site);
    }

    $result = $response->json();
    $siteData = [
        'available' =>array_map('strval',  array_column($result['available']['items'] ?? [], 'description')),
        'reserved' =>array_map('strval',  array_column($result['reserved']['items'] ?? [], 'description')),
        'blocked' =>array_map('strval',  array_column($result['blocked']['items'] ?? [], 'description')),
        'contracted' =>array_map('strval',  array_column($result['contracted']['items'] ?? [], 'description')),
        'total' => [
            'available' => $result['available']['total'] ?? 0,
            'reserved' => $result['reserved']['total'] ?? 0,
            'blocked' => $result['blocked']['total'] ?? 0,
            'contracted' => $result['contracted']['total'] ?? 0,
        ],
    ];
    // dd($siteData);
    return view('items.map', compact('site', 'siteData'));
}
    public function export(Request $request)
    {
        $site = $request->get('site', 'dhahran');
        $type = $request->get('type', 'pdf');
    
        $url = $site === 'bashaer'
            ? 'https://crm.azyanalbashaer.com/api/items'
            : 'https://crm.azyanaldhahran.com/api/items';
    
        $error = null;
        $data = $this->fetchItems($url, $error);
    
        if ($error) {
            return back()->with('error', 'Failed to fetch data from the selected site');
        }
    
        $byCategory = $data['by_category'] ?? [];
        $chartImage = null;

        switch ($type) {
            case 'pdf':
                $pdf = Pdf::loadView('exports.items_pdf', [
                    'site' => $site,
                    'items' => $data,
                    'byCategory' => $byCategory,
                    'maxCount' => max($byCategory ?: [0]) 
                ]);
                
                return $pdf->download("items_{$site}.pdf");
                
            case 'csv':
                return $this->exportCsv($byCategory, $site);
                
            default:
                abort(400, 'Invalid export type');
        }
    }
    
    protected function exportCsv($data, $site)
    {
        $filename = "items_{$site}_" . now()->format('Ymd_His') . ".csv";
        
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
