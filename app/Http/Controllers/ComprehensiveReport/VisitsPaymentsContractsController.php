<?php

namespace App\Http\Controllers\ComprehensiveReport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VisitsPaymentsContractsController extends ReportBaseController
{
    protected $reportView = 'comprehensive.partials.visits-payments-contracts';
    protected $formRouteName = 'admin.comprehensive.vpc.process';

    public function index(Request $request)
    {
        $albashaerVPCData = $this->getAlbashaerVisitsPaymentsContracts();
        $dhahranVPCData = $this->getDhahranVisitsPaymentsContracts();
        
        return view($this->reportView, [
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            'albashaerVPCData' => $albashaerVPCData,
            'dhahranVPCData' => $dhahranVPCData,
        ]);
    }
      /**
     * Get Albashaer Visits, Payments and Contracts Data
     */
    private function getAlbashaerVisitsPaymentsContracts()
    {
        try {
            $response = Http::get('https://crm.azyanalbashaer.com/api/Item_reports/visits_payments_contracts_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer Visits Payments Contracts API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch data from Albashaer Visits Payments Contracts API',
            'data' => [
                'current_month' => [],
                'last_month' => [],
                'two_months_ago' => [],
            ],
            'date_from' => null,
            'date_to' => null
        ];
    }

    /**
     * Get Dhahran Visits, Payments and Contracts Data
     */
    private function getDhahranVisitsPaymentsContracts()
    {
        try {
            $response = Http::get('https://crm.azyanaldhahran.com/api/Item_reports/visits_payments_contracts_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Visits Payments Contracts API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch data from Dhahran Visits Payments Contracts API',
            'data' => [
                'current_month' => [],
                'last_month' => [],
                'two_months_ago' => [],
            ],
            'date_from' => null,
            'date_to' => null
        ];
    }
 
}