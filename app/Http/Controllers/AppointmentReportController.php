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
            'bashaer' => ['by_date' => [], 'total' => 0]
        ];

        $errors = [
            'dhahran' => null,
            'bashaer' => null
        ];

        $appointments['dhahran'] = $this->fetchAppointments('https://crm.azyanaldhahran.com/api/appointments', $errors['dhahran']);
        $appointments['bashaer'] = $this->fetchAppointments('https://crm.azyanalbashaer.com/api/appointments', $errors['bashaer']);

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

    public function export(Request $request)
    {
        $site = $request->get('site', 'dhahran');
        $type = $request->get('type', 'pdf');
    
        $url = $site === 'bashaer'
            ? 'https://crm.azyanalbashaer.com/api/appointments'
            : 'https://crm.azyanaldhahran.com/api/appointments';
    
        $error = null;
        $data = $this->fetchAppointments($url, $error);
    
        if ($error) {
            return back()->with('error', 'Failed to fetch data from the selected site');
        }
    
        $byDate = $data['by_date'] ?? [];
        $chartImage = NULL;

      
        

        switch ($type) {
            case 'pdf':
                $pdf = Pdf::loadView('exports.appointments_pdf', [
                    'site' => $site,
                    'appointments' => $data,
               'byDate' => $byDate,
               'maxCount' => max($byDate ?: [0]) 
                ]);
                
                return $pdf->download("appointments_{$site}.pdf");
            case 'csv':
                return $this->exportCsv($byDate, $site);
            default:
                abort(400, 'Invalid export type');
        }
    }
    
    protected function exportCsv($data, $site)
    {
        $filename = "appointments_{$site}_" . now()->format('Ymd_His') . ".csv";
        
        $headers = [
            "Content-type" => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$filename",
        ];
    
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Appointments']);
            
            foreach ($data as $date => $count) {
                fputcsv($file, [$date, $count]);
            }
            
            fclose($file);
        };
    
        return response()->stream($callback, 200, $headers);
    }
    
    
}
