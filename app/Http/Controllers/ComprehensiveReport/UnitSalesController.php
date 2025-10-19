<?php

namespace App\Http\Controllers\ComprehensiveReport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UnitSalesController extends ReportBaseController
{
    protected $reportView = 'comprehensive.partials.unit-sales';
    protected $formRouteName = 'admin.comprehensive.unit-sales.process';

    public function index(Request $request)
    {
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        $albashaerUnitSales = $this->getAlbashaerUnitSales($from_date, $to_date);
        $dhahranUnitSales = $this->getDhahranUnitSales($from_date, $to_date);
        $jeddahUnitSales = $this->getJeddahUnitSales($from_date, $to_date);
        $alfursanUnitSales = $this->getAlfursanUnitSales($from_date, $to_date);
// var_dump($dhahranUnitSales);
        return view($this->reportView, [
            'from_date' => $from_date,
            'to_date' => $to_date,
            'albashaerUnitSales' => $albashaerUnitSales,
            'dhahranUnitSales' => $dhahranUnitSales,
            'jeddahUnitSales' => $jeddahUnitSales,
            'alfursanUnitSales' => $alfursanUnitSales,
        ]);
    }

    /**
     * Get Albashaer Unit Sales Data
     */
    private function getAlbashaerUnitSales($from_date, $to_date)
    {
        try {
            $response = Http::get('https://crm.azyanalbashaer.com/api/Item_reports/unit_sales_api');

            if ($response->successful()) {
                $data = $response->json();
                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer Unit Sales API Error: " . $e->getMessage());
        }

        return [
            'status'  => false,
            'message' => 'Failed to fetch data from Albashaer Unit Sales API',
            'data'    => [],
        ];
    }

    /**
     * Get Dhahran Unit Sales Data
     */
    private function getDhahranUnitSales($from_date, $to_date)
    {
        try {
            $response = Http::get('https://crm.azyanaldhahran.com/api/Item_reports/unit_sales_api');

            if ($response->successful()) {
                $data = $response->json();
                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Unit Sales API Error: " . $e->getMessage());
        }

        return [
            'status'  => false,
            'message' => 'Failed to fetch data from Dhahran Unit Sales API',
            'data'    => [],
        ];
    }

    /**
     * Get Jeddah Unit Sales Data
     */
    private function getJeddahUnitSales($from_date, $to_date)
    {
        try {
            $response = Http::get('https://crm.azyanjeddah.com/api/Item_reports/unit_sales_api');

            if ($response->successful()) {
                $data = $response->json();
                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Jeddah Unit Sales API Error: " . $e->getMessage());
        }

        return [
            'status'  => false,
            'message' => 'Failed to fetch data from Jeddah Unit Sales API',
            'data'    => [],
        ];
    }
    private function getAlfursanUnitSales($from_date, $to_date)
    {
        try {
            $response = Http::get('https://crm.azyanalfursan.com/api/Item_reports/unit_sales_api');

            if ($response->successful()) {
                $data = $response->json();
                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Alfursan Unit Sales API Error: " . $e->getMessage());
        }

        return [
            'status'  => false,
            'message' => 'Failed to fetch data from Alfursan Unit Sales API',
            'data'    => [],
        ];
    }
}
