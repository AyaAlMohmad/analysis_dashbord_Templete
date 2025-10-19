<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class AppointmentReportController extends Controller
{
    public function index(Request $request)
    {
        $appointments = [
            'dhahran' => ['by_date' => [], 'total' => 0],
            'bashaer' => ['by_date' => [], 'total' => 0],
            'jeddah' => ['by_date' => [], 'total' => 0],
        ];

        $errors = [
            'dhahran' => null,
            'bashaer' => null,
            'jeddah' => null,
        ];

        $appointments['dhahran'] = $this->fetchAppointments('https://crm.azyanaldhahran.com/api/appointments', $errors['dhahran']);
        $appointments['bashaer'] = $this->fetchAppointments('https://crm.azyanalbashaer.com/api/appointments', $errors['bashaer']);
        $appointments['jeddah'] = $this->fetchAppointments('https://crm.azyanjeddah.com/api/appointments', $errors['jeddah']);
        $appointments['alfursan'] = $this->fetchAppointments('https://crm.azyanalfursan.com/api/appointments', $errors['alfursan']);
        $siteSelected = $request->get('site', 'dhahran');

        return view('reports.appointments', compact('appointments', 'errors', 'siteSelected'));
    }

    private function fetchAppointments($url, &$error)
    {
        try {
            $response = Http::get($url);
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'by_date' => $data['by_date'] ?? [],
                    'total' => $data['total'] ?? 0
                ];
            } else {
                $error = 'Failed to fetch data';
                return ['by_date' => [], 'total' => 0];
            }
        } catch (\Exception $e) {
            $error = 'Connection error';
            return ['by_date' => [], 'total' => 0];
        }
    }

 


}
