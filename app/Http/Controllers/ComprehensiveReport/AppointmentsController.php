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
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $dhahranMonthlyAppointments = $this->getDhahranMonthlyAppointments($fromDate, $toDate);
        $albashaerMonthlyAppointments = $this->getAlbashaerMonthlyAppointments($fromDate, $toDate);
        $jeddahMonthlyAppointments = $this->getJeddahMonthlyAppointments($fromDate, $toDate);
        $alfursanMonthlyAppointments = $this->getAlfursanMonthlyAppointments($fromDate, $toDate);

        return view($this->reportView, [
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'dhahranMonthlyAppointments' => $dhahranMonthlyAppointments,
            'albashaerMonthlyAppointments' => $albashaerMonthlyAppointments,
            'jeddahMonthlyAppointments' => $jeddahMonthlyAppointments,
            'alfursanMonthlyAppointments' => $alfursanMonthlyAppointments,
        ]);
    }

    /**
     * Get Dhahran Monthly Appointments Data
     */
    private function getDhahranMonthlyAppointments($fromDate = null, $toDate = null)
    {
        try {
            $formData = [];

            if ($fromDate) {
                $formData['from_date'] = $fromDate;
            }

            if ($toDate) {
                $formData['to_date'] = $toDate;
            }

            $response = Http::asForm()->post('https://crm.azyanaldhahran.com/api/lead_reports/monthly_appointments_api', $formData);

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
    private function getAlbashaerMonthlyAppointments($fromDate = null, $toDate = null)
    {
        try {
            $formData = [];

            if ($fromDate) {
                $formData['from_date'] = $fromDate;
            }

            if ($toDate) {
                $formData['to_date'] = $toDate;
            }

            $response = Http::asForm()->post('https://crm.azyanalbashaer.com/api/lead_reports/monthly_appointments_api', $formData);

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

    /**
     * Get Jeddah Monthly Appointments Data
     */
    private function getJeddahMonthlyAppointments($fromDate = null, $toDate = null)
    {
        try {
            $formData = [];

            if ($fromDate) {
                $formData['from_date'] = $fromDate;
            }

            if ($toDate) {
                $formData['to_date'] = $toDate;
            }

            $response = Http::asForm()->post('https://crm.azyanjeddah.com/api/lead_reports/monthly_appointments_api', $formData);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Jeddah Monthly Appointments API Error: " . $e->getMessage());
        }

        return [
            'appointments' => [],
            'totals' => [],
        ];
    }
    /**
     * Get Alfursan Monthly Appointments Data
     */
    private function getAlfursanMonthlyAppointments($fromDate = null, $toDate = null)
    {
        try {
            $formData = [];

            if ($fromDate) {
                $formData['from_date'] = $fromDate;
            }

            if ($toDate) {
                $formData['to_date'] = $toDate;
            }

            $response = Http::asForm()->post('https://crm.azyanalfursan.com/api/lead_reports/monthly_appointments_api', $formData);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Alfursan Monthly Appointments API Error: " . $e->getMessage());
        }

        return [
            'appointments' => [],
            'totals' => [],
        ];
    }
}
