<?php

namespace App\Http\Controllers\ComprehensiveReport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

abstract class ReportBaseController extends Controller
{
    

    protected $reportView;
    protected $formRouteName;

    public function showForm()
    {
        return view('comprehensive.partials.date-form', [
            'actionUrl' => route($this->formRouteName)
        ]);
    }

    public function processForm(Request $request)
    {
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        return $this->index($request);
    }
        /**
     *  getAlbashaerReserved API
     */
    private function getAlbashaerReserved($from_date, $to_date)
    {
        try {
            $response = Http::post('https://crm.azyanalbashaer.com/api/Item_reports/api_reserved_report', [
                'from_date' => $from_date,
                'to_date' => $to_date,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch data from Albashaer API',
            'projects' => [],
            'chart_labels' => [],
            'chart_data' => []
        ];
    }

    /**
     * getDhahranResercedAPI
     */
    private function getDhahranReserced($from_date, $to_date)
    {
        try {
            $response = Http::post('https://crm.azyanaldhahran.com/api/Item_reports/api_reserved_report', [
                'from_date' => $from_date,
                'to_date' => $to_date,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch data from Dhahran API',
            'projects' => [],
            'chart_labels' => [],
            'chart_data' => []
        ];
    }
    /**
     *  getAlbashaerContract API
     */
    private function getAlbashaerContract($from_date, $to_date)
    {
        try {
            $response = Http::post('https://crm.azyanalbashaer.com/api/Item_reports/api_contracts_report', [
                'from_date' => $from_date,
                'to_date' => $to_date,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch data from Albashaer API',
            'projects' => [],
            'chart_labels' => [],
            'chart_data' => []
        ];
    }

    /**
     * getDhahranContractAPI
     */
    private function getDhahranContract($from_date, $to_date)
    {
        try {
            $response = Http::post('https://crm.azyanaldhahran.com/api/Item_reports/api_contracts_report', [
                'from_date' => $from_date,
                'to_date' => $to_date,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch data from Dhahran API',
            'projects' => [],
            'chart_labels' => [],
            'chart_data' => []
        ];
    }
 
 
  
}
