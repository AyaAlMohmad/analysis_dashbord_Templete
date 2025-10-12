<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
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
        $dataDhahran = $data['dhahran'];
        $dataBashaer = $data['bashaer'];
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
        } catch (Exception $e) {
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
        } catch (Exception $e) {
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

        // if (!$response->ok()) {
        //     return redirect()->back()->with('error', 'Failed to fetch data from ' . $site);
        // }

        $result = $response->json();
        $siteData = [
            'available' => array_map('strval',  array_column($result['available']['items'] ?? [], 'description')),
            'reserved' => array_map('strval',  array_column($result['reserved']['items'] ?? [], 'description')),
            'blocked' => array_map('strval',  array_column($result['blocked']['items'] ?? [], 'description')),
            'contracted' => array_map('strval',  array_column($result['contracted']['items'] ?? [], 'description')),
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
    // public function export(Request $request)
    // {
    //     $site = $request->get('site', 'dhahran');
    //     $type = $request->get('type', 'pdf');

    //     $url = $site === 'bashaer'
    //         ? 'https://crm.azyanalbashaer.com/api/items'
    //         : 'https://crm.azyanaldhahran.com/api/items';

    //     $error = null;
    //     $data = $this->fetchItems($url, $error);

    //     if ($error) {
    //         return back()->with('error', 'Failed to fetch data from the selected site');
    //     }

    //     $byCategory = $data['by_category'] ?? [];
    //     $chartImage = null;

    //     switch ($type) {
    //         case 'pdf':
    //             $pdf = Pdf::loadView('exports.items_pdf', [
    //                 'site' => $site,
    //                 'items' => $data,
    //                 'byCategory' => $byCategory,
    //                 'maxCount' => max($byCategory ?: [0])
    //             ]);

    //             return $pdf->download("items_{$site}.pdf");

    //         case 'csv':
    //             return $this->exportCsv($byCategory, $site);

    //         default:
    //             abort(400, 'Invalid export type');
    //     }
    // }

    protected function exportCsv($data, $site)
    {
        $filename = "items_{$site}_" . now()->format('Ymd_His') . ".csv";

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
  public function itemReport(Request $request)
{
    $site = $request->get('site', 'dhahran');

    $apiUrls = [
        'dhahran' => 'https://crm.azyanaldhahran.com/api/custom-reports/api_item_report_data',
        'bashaer' => 'https://crm.azyanalbashaer.com/api/custom-reports/api_item_report_data',
        'jeddah' => 'https://crm.azyanjeddah.com/api/custom-reports/api_item_report_data',
    ];

    if (!in_array($site, ['dhahran', 'bashaer', 'jeddah'])) {
        return response()->json(['error' => 'Invalid site selected.'], 400);
    }

    try {
        $response = Http::timeout(30)->get($apiUrls[$site]);

        if (!$response->successful()) {
            $errorMessage = 'Failed to fetch data from ' . $site;
            if ($request->ajax()) {
                return response()->json(['error' => $errorMessage], 500);
            } else {
                return view('items.item_report', [
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

        return view('items.item_report', compact('site', 'data'));
    } catch (\Exception $e) {
        $errorMessage = 'An error occurred while connecting to the site: ' . $site;
        if ($request->ajax()) {
            return response()->json(['error' => $errorMessage], 500);
        }

        return view('items.item_report', [
            'site' => $site,
            'data' => [],
            'error' => $errorMessage
        ]);
    }
}
    public function contractsReport()
    {
        return view('items.contracts_report');
    }
    public function contractsReportResult(Request $request)
    {
        $validated = $request->validate([
            'site' => 'required|in:dhahran,bashaer',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        $apiUrls = [
            'dhahran' => 'https://crm.azyanaldhahran.com/api/custom-reports/api_contracts_report',
            'bashaer' => 'https://crm.azyanalbashaer.com/api/custom-reports/api_contracts_report',
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
                // dd($result);
                return view('items.contracts_report_result', [
                    'result' => $result,
                    'site' => $validated['site']
                ]);
            }

            return back()->with('error', 'Failed to fetch report data.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error contacting API: ' . $e->getMessage());
        }
    }
   public function itemReportResult(Request $request)
{
    $validated = $request->validate([
        'site' => 'required|in:dhahran,bashaer,jeddah',
        'group' => 'required',
    ]);

    $apiUrls = [
        'dhahran' => 'https://crm.azyanaldhahran.com/api/custom-reports/api_item_report',
        'bashaer' => 'https://crm.azyanalbashaer.com/api/custom-reports/api_item_report',
        'jeddah' => 'https://crm.azyanjeddah.com/api/custom-reports/api_item_report',
    ];

    try {
        $response = Http::timeout(30)
            ->asForm() // important for multipart/form-data
            ->post($apiUrls[$validated['site']], [
                'group' => $validated['group'],
            ]);

        if ($response->successful()) {
            $result = $response->json();
            // dd($result);
            return view('items.item_report_result', [
                'result' => $result,
                'site' => $validated['site']
            ]);
        }

        return back()->with('error', 'Failed to fetch report data.');
    } catch (\Exception $e) {
        return back()->with('error', 'Error contacting API: ' . $e->getMessage());
    }
}

 public function itemStatus(Request $request)
{
    $result = null;

    // If the form has been submitted (POST request)
    if ($request->ajax()) {
        $site = $request->get('site');

        $apiUrls = [
            'dhahran' => 'https://crm.azyanaldhahran.com/api/Item_reports_api/api_status_item',
            'bashaer' => 'https://crm.azyanalbashaer.com/api/Item_reports_api/api_status_item',
            'jeddah' => 'https://crm.azyanjeddah.com/api/Item_reports_api/api_status_item',
        ];

        if (!array_key_exists($site, $apiUrls)) {
            return response()->json(['status' => false, 'message' => 'Invalid site'], 400);
        }

        try {
            $response = Http::timeout(30)->asForm()->get($apiUrls[$site]);

            if ($response->successful()) {
                // dd($response->json());
                return response()->json($response->json());
            } else {
                return response()->json(['status' => false, 'message' => 'API request failed'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // On initial GET request (no form submission)
    return view('items.item_status_report', [
        'result' => $result,
        'site' => null
    ]);
}
public function unitStages(Request $request)
{
    $result = null;
    $site = $request->get('site', 'dhahran'); // قيمة افتراضية

    // If the form has been submitted (POST request)
    if ($request->ajax()) {
        $site = $request->get('site');

        $apiUrls = [
            'dhahran' => 'https://crm.azyanaldhahran.com/api/Item_reports/unitStages',
            'bashaer' => 'https://crm.azyanalbashaer.com/api/Item_reports/unitStages',
            'jeddah' => 'https://crm.azyanjeddah.com/api/Item_reports/unitStages',
        ];

        if (!array_key_exists($site, $apiUrls)) {
            return response()->json(['status' => false, 'message' => 'Invalid site'], 400);
        }

        try {
            $response = Http::timeout(30)->asForm()->get($apiUrls[$site]);

            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['status' => false, 'message' => 'API request failed'], 500);
            }
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // On initial GET request (no form submission) - تحميل البيانات مسبقًا
    try {
        $apiUrls = [
            'dhahran' => 'https://crm.azyanaldhahran.com/api/Item_reports/unitStages',
            'bashaer' => 'https://crm.azyanalbashaer.com/api/Item_reports/unitStages',
            'jeddah' => 'https://crm.azyanjeddah.com/api/Item_reports/unitStages',
        ];

        if (array_key_exists($site, $apiUrls)) {
            $response = Http::timeout(30)->asForm()->get($apiUrls[$site]);
            if ($response->successful()) {
                $result = $response->json();
            }
        }
    } catch (Exception $e) {
        // يمكنك تسجيل الخطأ هنا إذا أردت
    }

    return view('items.unitStages', [
        'result' => $result,
        'site' => $site
    ]);
}
    // public function unitStages(Request $request)
    // {
    //     $site = $request->get('site');

    //     if (!$request->ajax()) {
    //         return view('items.unitStages', ['site' => $site]);
    //     }

    //     $apiUrls = [
    //         'dhahran' => 'https://crm.azyanaldhahran.com/api/Item_reports/unitStages',
    //         'bashaer' => 'https://crm.azyanalbashaer.com/api/Item_reports/unitStages',
    //     ];

    //     if (!array_key_exists($site, $apiUrls)) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Invalid site selected'
    //         ], 400);
    //     }

    //     try {
    //         $response = Http::timeout(30)->get($apiUrls[$site]);

    //         if ($response->successful()) {
    //             $data = $response->json();

    //             if (!isset($data['status'])) {
    //                 throw new \Exception('Invalid API response structure');
    //             }

    //             return response()->json($data);
    //         }


    //         \Log::error('API Request Failed', [
    //             'status' => $response->status(),
    //             'response' => $response->body()
    //         ]);

    //         return response()->json([
    //             'status' => false,
    //             'message' => 'API request failed with status: ' . $response->status()
    //         ], $response->status());

    //     } catch (\Exception $e) {
    //         \Log::error('API Error: ' . $e->getMessage());

    //         return response()->json([
    //             'status' => false,
    //             'message' => 'API Error: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }
 public function unitStatisticsByStage(Request $request)
{
    $result = null;

    if ($request->ajax()) {
        $site = $request->get('site');

        $apiUrls = [
            'dhahran' => 'https://crm.azyanaldhahran.com/api/Item_reports/unitStatisticsByStage',
            'bashaer' => 'https://crm.azyanalbashaer.com/api/Item_reports/unitStatisticsByStage',
            'jeddah' => 'https://crm.azyanjeddah.com/api/Item_reports/unitStatisticsByStage', // إضافة جدة
        ];

        if (!array_key_exists($site, $apiUrls)) {
            return response()->json(['status' => false, 'message' => 'Invalid site'], 400);
        }

        try {
            $response = Http::timeout(30)->asForm()->get($apiUrls[$site]);

            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['status' => false, 'message' => 'API request failed'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // On initial GET request (no form submission)
    return view('items.unitStatisticsByStage', [
        'result' => $result,
        'site' => null
    ]);
}
    public function itemGroupData(Request $request)
    {
        $site = $request->get('site', 'dhahran');

        $apiUrls = [
            'dhahran' => 'https://crm.azyanaldhahran.com/api/Item_reports/itemGroupData',
            'bashaer' => 'https://crm.azyanalbashaer.com/api/Item_reports/itemGroupData',
        ];

        if (!in_array($site, ['dhahran', 'bashaer'])) {
            return response()->json(['error' => 'Invalid site selected.'], 400);
        }

        try {
            $response = Http::timeout(30)->get($apiUrls[$site]);

            if (!$response->successful()) {
                $errorMessage = 'Failed to fetch data from ' . $site;
                if ($request->ajax()) {
                    return response()->json(['error' => $errorMessage], 500);
                } else {
                    return view('items.item_report', [
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

            return view('items.item_group_data', compact('site', 'data'));
        } catch (\Exception $e) {
            $errorMessage = 'An error occurred while connecting to the site: ' . $site;
            if ($request->ajax()) {
                return response()->json(['error' => $errorMessage], 500);
            }

            return view('items.item_group_data', [
                'site' => $site,
                'data' => [],
                'error' => $errorMessage
            ]);
        }
    }
    public function groupItemsStatusResult(Request $request)
    {
        $validated = $request->validate([
            'site' => 'required|in:dhahran,bashaer',
            'group' => 'required',

        ]);

        $apiUrls = [
            'dhahran' => 'https://crm.azyanaldhahran.com/api/Item_reports/groupItemsStatusResult',
            'bashaer' => 'https://crm.azyanalbashaer.com/api/Item_reports/groupItemsStatusResult',
        ];

        try {
            $response = Http::timeout(30)
                ->asForm() // important for multipart/form-data
                ->post($apiUrls[$validated['site']], [

                    'group' => $validated['group'],
                ]);

            if ($response->successful()) {

                $result = $response->json();
                // dd($result);
                return view('items.item_group_data_result', [
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
