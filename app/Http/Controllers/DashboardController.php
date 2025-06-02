<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $comparisonData = [
            'leads_timeline'   => $this->compareLeadsActivity(),
            'leads_status'     => $this->compareLeadsStatus(),
            'appointments'     => $this->compareAppointments1(),
            'call_logs'        => $this->compareCallLogs(),
            'items'            => $this->compareItems1(),
            'leads_sources'    => $this->compareLeadsSources(),
        ];
// dd($comparisonData);

    return view('dashboard', compact('comparisonData'));
    }
    private function compareAppointments1()
    {
        try {
            $dhahran = Http::get('https://crm.azyanaldhahran.com/api/appointments')->json();
            $bashaer = Http::get('https://crm.azyanalbashaer.com/api/appointments')->json();

            $dates = array_unique(array_merge(
                array_keys($dhahran['by_date'] ?? []),
                array_keys($bashaer['by_date'] ?? [])
            ));
            sort($dates);

            $timeline = [];
            foreach ($dates as $date) {
                $timeline[] = [
                    'date' => $date,
                    'dhahran' => $dhahran['by_date'][$date] ?? 0,
                    'bashaer' => $bashaer['by_date'][$date] ?? 0,
                ];
            }

            return [
                'total_dhahran' => $dhahran['total'] ?? 0,
                'total_bashaer' => $bashaer['total'] ?? 0,
                'timeline' => $timeline,
            ];
        } catch (\Exception $e) {
            return ['error' => 'Connection error in Appointments'];
        }
    }

    private function compareItems1()
    {
        try {
            $dhahran = Http::get('https://crm.azyanaldhahran.com/api/items')->json();
            $bashaer = Http::get('https://crm.azyanalbashaer.com/api/items')->json();

            return [
                'available' => [
                    'dhahran' => $dhahran['available'] ?? 0,
                    'bashaer' => $bashaer['available'] ?? 0,
                ],
                'blocked' => [
                    'dhahran' => $dhahran['blocked'] ?? 0,
                    'bashaer' => $bashaer['blocked'] ?? 0,
                ],
                'reserved' => [
                    'dhahran' => $dhahran['reserved']['total'] ?? 0,
                    'bashaer' => $bashaer['reserved']['total'] ?? 0,
                ],
                'contracted' => [
                    'dhahran' => $dhahran['contracted']['total'] ?? 0,
                    'bashaer' => $bashaer['contracted']['total'] ?? 0,
                ]
            ];
        } catch (\Exception $e) {
            return ['error' => 'Connection error in Items'];
        }
    }
    private function compareLeadsActivity()
    {
        try {
            $dhahran = Http::get('https://crm.azyanaldhahran.com/api/leads')->json();
            $bashaer = Http::get('https://crm.azyanalbashaer.com/api/leads')->json();
            $result = [];

            // Get all unique dates from both APIs
            $allDates = array_unique(array_merge(
                array_keys($dhahran ?? []),
                array_keys($bashaer ?? [])
            ));

            sort($allDates);

            foreach ($allDates as $date) {
                $result[$date] = [
                    'dhahran_added' => $dhahran[$date]['added'] ?? 0,
                    'dhahran_edited' => $dhahran[$date]['edited'] ?? 0,
                    'bashaer_added' => $bashaer[$date]['added'] ?? 0,
                    'bashaer_edited' => $bashaer[$date]['edited'] ?? 0,
                ];
            }

            return $result;
        } catch (\Exception $e) {
            return ['error' => 'Connection error in Leads Activity'];
        }
    }

    private function compareLeadsTimeline()
    {
        try {
            $dhahran = Http::get('https://crm.azyanaldhahran.com/api/leads')->json();
            $bashaer = Http::get('https://crm.azyanalbashaer.com/api/leads')->json();
            $result = [];

            foreach ($dhahran as $date => $values) {
                $result[$date] = [
                    'dhahran_added' => $values['added'] ?? 0,
                    'bashaer_added' => $bashaer[$date]['added'] ?? 0,
                ];
            }

            return $result;
        } catch (\Exception $e) {
            return ['error' => 'Connection error in Leads Timeline'];
        }
    }

    private function compareLeadsStatus()
    {
        try {
            $dhahran = Http::get('https://crm.azyanaldhahran.com/api/leads_status')->json();
            $bashaer = Http::get('https://crm.azyanalbashaer.com/api/leads_status')->json();
            $result = [];

            foreach ($dhahran as $status => $count) {
                $result[] = [
                    'status' => $status,
                    'dhahran' => $count,
                    'bashaer' => $bashaer[$status] ?? 0,
                ];
            }

            return $result;
        } catch (\Exception $e) {
            return ['error' => 'Connection error in Leads Status'];
        }
    }

    private function compareAppointments()
    {
        try {
            $dhahran = Http::get('https://crm.azyanaldhahran.com/api/appointments')->json();
            $bashaer = Http::get('https://crm.azyanalbashaer.com/api/appointments')->json();

            return [
                'total_dhahran' => $dhahran['total'] ?? 0,
                'total_bashaer' => $bashaer['total'] ?? 0,
                'by_date' => [
                    'dhahran' => $dhahran['by_date'] ?? [],
                    'bashaer' => $bashaer['by_date'] ?? [],
                ]
            ];
        } catch (\Exception $e) {
            return ['error' => 'Connection error in Appointments'];
        }
    }

    private function compareCallLogs()
    {
        try {
            $dhahran = Http::get('https://crm.azyanaldhahran.com/api/call_logs')->json();
            $bashaer = Http::get('https://crm.azyanalbashaer.com/api/call_logs')->json();

            return [
                'totals' => [
                    'dhahran' => $dhahran['totals'] ?? ['added' => 0, 'started' => 0, 'ended' => 0],
                    'bashaer' => $bashaer['totals'] ?? ['added' => 0, 'started' => 0, 'ended' => 0],
                ]
            ];
        } catch (\Exception $e) {
            return ['error' => 'Connection error in Call Logs'];
        }
    }

    private function compareItems()
    {
        try {
            $dhahran = Http::get('https://crm.azyanaldhahran.com/api/items')->json();
            $bashaer = Http::get('https://crm.azyanalbashaer.com/api/items')->json();

            return [
                'available' => [
                    'dhahran' => $dhahran['available'] ?? 0,
                    'bashaer' => $bashaer['available'] ?? 0,
                ],
                'reserved' => [
                    'dhahran' => $dhahran['reserved'] ?? 0,
                    'bashaer' => $bashaer['reserved'] ?? 0,
                ],
                'blocked' => [
                    'dhahran' => $dhahran['blocked'] ?? 0,
                    'bashaer' => $bashaer['blocked'] ?? 0,
                ],
                'contacted' => [
                    'dhahran' => $dhahran['contacted'] ?? 0,
                    'bashaer' => $bashaer['contacted'] ?? 0,
                ]
            ];
        } catch (\Exception $e) {
            return ['error' => 'Connection error in Items'];
        }
    }

    private function compareLeadsSources()
    {
        try {
            $dhahran = Http::get('https://crm.azyanaldhahran.com/api/leads_sources')->json();
            $bashaer = Http::get('https://crm.azyanalbashaer.com/api/leads_sources')->json();

            return [
                'dhahran' => $dhahran,
                'bashaer' => $bashaer
            ];
        } catch (\Exception $e) {
            return ['error' => 'Connection error in Leads Sources'];
        }
    }
    public function Analysis()
    {
        $comparisonData = [
            'leads_timeline'   => $this->compareLeadsTimeline(),
            'leads_status'     => $this->compareLeadsStatus(),
            'appointments'     => $this->compareAppointments(),
            'call_logs'        => $this->compareCallLogs(),
            'items'            => $this->compareItems(),
            'leads_sources'    => $this->compareLeadsSources(),
        ];

        return view('reports.dashboard', compact('comparisonData'));
    }
}
