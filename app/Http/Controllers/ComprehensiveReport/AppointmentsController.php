<?php

namespace App\Http\Controllers\ComprehensiveReport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AppointmentsController extends ReportBaseController
{
    protected $reportView = 'comprehensive.partials.appointments';
    protected $formRouteName = 'admin.comprehensive.appointments.process';

    public function index(Request $request)
    {
        $dhahranMonthlyAppointments = $this->getDhahranMonthlyAppointments();
        $albashaerMonthlyAppointments = $this->getAlbashaerMonthlyAppointments();
        
        return view($this->reportView, [
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            'dhahranMonthlyAppointments' => $dhahranMonthlyAppointments,
            'albashaerMonthlyAppointments' => $albashaerMonthlyAppointments,
        ]);
    }
        /**
     * Get Dhahran Monthly Appointments Data
     */
    private function getDhahranMonthlyAppointments()
    {
        try {
            $response = Http::post('https://crm.azyanaldhahran.com/api/lead_reports/monthly_appointments_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Monthly Appointments API Error: " . $e->getMessage());
        }

        return [
            'appointments' => [],
            'totals' => [],
        ];
    }

    /**
     * Get Albashaer Monthly Appointments Data
     */
    private function getAlbashaerMonthlyAppointments()
    {
        try {
            $response = Http::post('https://crm.azyanalbashaer.com/api/lead_reports/monthly_appointments_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer Monthly Appointments API Error: " . $e->getMessage());
        }

        return [
            'appointments' => [],
            'totals' => [],
        ];
    }
}