<?php

namespace App\Http\Controllers;

use App\Models\ProjectProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $comparisonData = [
            'villa_summary'    => $this->compareVillaSummary(),
            'leads_timeline'   => $this->compareLeadsActivity(),
            'leads_status'     => $this->compareLeadsStatus(),
            'appointments'     => $this->compareAppointments1(),
            'call_logs'        => $this->compareCallLogs(),
            'items'            => $this->compareItems1(),
            'leads_sources'    => $this->compareLeadsSources(),
        ];
// dd($comparisonData);
        $progressData = $this->getProjectProgress();

        // Remove the dd() to see the actual page
        return view('dashboard', compact('comparisonData', 'progressData'));
    }

    private function getProjectProgress()
    {
        $dhahran = ProjectProgress::where('site', 'dhahran')->latest()->value('progress_percentage') ?? 0;
        $bashaer = ProjectProgress::where('site', 'bashaer')->latest()->value('progress_percentage') ?? 0;
        $jaddah = ProjectProgress::where('site', 'jaddah')->latest()->value('progress_percentage') ?? 0;
        $alfursan = ProjectProgress::where('site', 'alfursan')->latest()->value('progress_percentage') ?? 0;
        return [
            'dhahran' => $dhahran,
            'bashaer' => $bashaer,
            'jaddah' => $jaddah,
            'alfursan' => $alfursan,
            'all' => round(($dhahran + $bashaer + $jaddah+$alfursan) / 4, 2), // Fixed: use average instead of sum
        ];
    }

    public function updateProjectProgress(Request $request)
    {
        $data = $request->validate([
            'site' => 'required|in:dhahran,bashaer,jaddah',
            'progress_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $data['user_id'] = auth()->user()->id;

        // Use create instead of update to maintain history
        ProjectProgress::create($data);

        return redirect()->back()->with('success', 'Project progress updated successfully');
    }

    private function compareVillaSummary()
    {
        $sites = [
            'dhahran' => 'https://crm.azyanaldhahran.com/api/Item_reports/villaSummary',
            'bashaer' => 'https://crm.azyanalbashaer.com/api/Item_reports/villaSummary',
            'jaddah' => 'https://crm.azyanjeddah.com/api/Item_reports/villaSummary'
        ];

        $result = [];

        foreach ($sites as $site => $url) {
            try {
                Log::info("Fetching villa summary for {$site}: {$url}");

                $response = Http::timeout(10)->get($url);

                if ($response->successful()) {
                    $data = $response->json();
                    Log::info("{$site} API Response:", $data);

                    $result[$site] = $this->parseVillaSummary($data);
                } else {
                    Log::error("{$site} API Failed - Status: " . $response->status());
                    $result[$site] = $this->getEmptyVillaSummary();
                }
            } catch (\Exception $e) {
                Log::error("{$site} API Exception: " . $e->getMessage());
                $result[$site] = $this->getEmptyVillaSummary();
            }
        }

        return $result;
    }

    private function parseVillaSummary($response)
    {
        $data = $response['data'] ?? $response;

        // If we have an error in response, return empty data
        if (isset($data['error']) || empty($data)) {
            return $this->getEmptyVillaSummary();
        }

        return [
            'total_units' => $data['total_units'] ?? 0,
            'total_price' => $data['total_price'] ?? 0,
            'available' => [
                'count' => $data['available']['count'] ?? 0,
                'percentage' => $data['available']['percentage'] ?? 0,
                'total_value' => $data['available']['total_value'] ?? 0,
            ],
            'blocked' => [
                'count' => $data['blocked']['count'] ?? 0,
                'percentage' => $data['blocked']['percentage'] ?? 0,
                'total_value' => $data['blocked']['total_value'] ?? 0,
            ],
            'reserved' => [
                'count' => $data['reserved']['count'] ?? 0,
                'percentage' => $data['reserved']['percentage'] ?? 0,
                'total_value' => $data['reserved']['total_value'] ?? 0,
            ],
            'contracted' => [
                'count' => $data['contracted']['count'] ?? 0,
                'percentage' => $data['contracted']['percentage'] ?? 0,
                'total_value' => $data['contracted']['total_value'] ?? 0,
            ],
            'overall_value' => $data['overall_value'] ?? 0,
            'overall_progress_percentage' => $data['overall_progress_percentage'] ?? 0,
        ];
    }

    private function getEmptyVillaSummary()
    {
        return [
            'total_units' => 0,
            'total_price' => 0,
            'available' => ['count' => 0, 'percentage' => 0, 'total_value' => 0],
            'blocked' => ['count' => 0, 'percentage' => 0, 'total_value' => 0],
            'reserved' => ['count' => 0, 'percentage' => 0, 'total_value' => 0],
            'contracted' => ['count' => 0, 'percentage' => 0, 'total_value' => 0],
            'overall_value' => 0,
            'overall_progress_percentage' => 0,
        ];
    }

    private function compareAppointments1()
    {
        try {
            $dhahran = Http::get('https://crm.azyanaldhahran.com/api/appointments')->json();
            $bashaer = Http::get('https://crm.azyanalbashaer.com/api/appointments')->json();
            $jaddah = Http::get('https://crm.azyanjeddah.com/api/appointments')->json();
            $alfursan = Http::get('https://crm.azyanalfursan.com/api/appointments')->json();

            $dates = array_unique(array_merge(
                array_keys($dhahran['by_date'] ?? []),
                array_keys($bashaer['by_date'] ?? []),
                array_keys($jaddah['by_date'] ?? []),
                array_keys($alfursan['by_date'] ?? [])
            ));
            sort($dates);

            $timeline = [];
            foreach ($dates as $date) {
                $timeline[] = [
                    'date' => $date,
                    'dhahran' => $dhahran['by_date'][$date] ?? 0,
                    'bashaer' => $bashaer['by_date'][$date] ?? 0,
                    'jaddah' => $jaddah['by_date'][$date] ?? 0,
                    'alfursan' => $alfursan['by_date'][$date] ?? 0,
                ];
            }

            return [
                'total_dhahran' => $dhahran['total'] ?? 0,
                'total_bashaer' => $bashaer['total'] ?? 0,
                'total_jaddah' => $jaddah['total'] ?? 0,
                'total_alfursan' => $alfursan['total'] ?? 0,
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
            $jaddah = Http::get('https://crm.azyanjeddah.com/api/items')->json();
            $alfursan = Http::get('https://crm.azyanalfursan.com/api/items')->json();

            return [
                'available' => [
                    'dhahran' => $dhahran['available'] ?? 0,
                    'bashaer' => $bashaer['available'] ?? 0,
                    'jaddah' => $jaddah['available'] ?? 0,
                    'alfursan' => $alfursan['available'] ?? 0,
                ],
                'blocked' => [
                    'dhahran' => $dhahran['blocked'] ?? 0,
                    'bashaer' => $bashaer['blocked'] ?? 0,
                    'jaddah' => $jaddah['blocked'] ?? 0,
                ],
                'reserved' => [
                    'dhahran' => $dhahran['reserved']['total'] ?? 0,
                    'bashaer' => $bashaer['reserved']['total'] ?? 0,
                    'jaddah' => $jaddah['reserved']['total'] ?? 0,
                    'alfursan' => $alfursan['reserved']['total'] ?? 0,
                ],
                'contracted' => [
                    'dhahran' => $dhahran['contracted']['total'] ?? 0,
                    'bashaer' => $bashaer['contracted']['total'] ?? 0,
                    'jaddah' => $jaddah['contracted']['total'] ?? 0,
                    'alfursan' => $alfursan['contracted']['total'] ?? 0,
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
            $jaddah = Http::get('https://crm.azyanjeddah.com/api/leads')->json();
            $alfursan = Http::get('https://crm.azyanalfursan.com/api/leads')->json();
            $result = [];

            // Get all unique dates from both APIs
            $allDates = array_unique(array_merge(
                array_keys($dhahran ?? []),
                array_keys($bashaer ?? []),
                array_keys($jaddah ?? []),
                array_keys($alfursan ?? [])
            ));

            sort($allDates);

            foreach ($allDates as $date) {
                $result[$date] = [
                    'dhahran_added' => $dhahran[$date]['added'] ?? 0,
                    'dhahran_edited' => $dhahran[$date]['edited'] ?? 0,
                    'bashaer_added' => $bashaer[$date]['added'] ?? 0,
                    'bashaer_edited' => $bashaer[$date]['edited'] ?? 0,
                    'jaddah_added' => $jaddah[$date]['added'] ?? 0,
                    'jaddah_edited' => $jaddah[$date]['edited'] ?? 0,
                    'alfursan_added' => $alfursan[$date]['added'] ?? 0,
                    'alfursan_edited' => $alfursan[$date]['edited'] ?? 0,
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
            $jaddah = Http::get('https://crm.azyanjeddah.com/api/leads')->json();
            $result = [];

            foreach ($dhahran as $date => $values) {
                $result[$date] = [
                    'dhahran_added' => $values['added'] ?? 0,
                    'bashaer_added' => $bashaer[$date]['added'] ?? 0,
                    'jaddah_added' => $jaddah[$date]['added'] ?? 0,
                    'alfursan_added' => $alfursan[$date]['added'] ?? 0,
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
            $jaddah = Http::get('https://crm.azyanjeddah.com/api/leads_status')->json();
            $alfursan = Http::get('https://crm.azyanalfursan.com/api/leads_status')->json();
            $result = [];

            foreach ($dhahran as $status => $count) {
                $result[] = [
                    'status' => $status,
                    'dhahran' => $count,
                    'bashaer' => $bashaer[$status] ?? 0,
                    'jaddah' => $jaddah[$status] ?? 0,
                    'alfursan' => $alfursan[$status] ?? 0,
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
            $jaddah = Http::get('https://crm.azyanjeddah.com/api/appointments')->json();
            $alfursan = Http::get('https://crm.azyanalfursan.com/api/appointments')->json();

            return [
                'total_dhahran' => $dhahran['total'] ?? 0,
                'total_bashaer' => $bashaer['total'] ?? 0,
                'total_jaddah' => $jaddah['total'] ?? 0,
                'by_date' => [
                    'dhahran' => $dhahran['by_date'] ?? [],
                    'bashaer' => $bashaer['by_date'] ?? [],
                    'jaddah' => $jaddah['by_date'] ?? [],
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
            $jaddah = Http::get('https://crm.azyanjeddah.com/api/call_logs')->json();
            $alfursan = Http::get('https://crm.azyanalfursan.com/api/call_logs')->json();

            return [
                'totals' => [
                    'dhahran' => $dhahran['totals'] ?? ['added' => 0, 'started' => 0, 'ended' => 0],
                    'bashaer' => $bashaer['totals'] ?? ['added' => 0, 'started' => 0, 'ended' => 0],
                    'jaddah' => $jaddah['totals'] ?? ['added' => 0, 'started' => 0, 'ended' => 0],
                    'alfursan' => $alfursan['totals'] ?? ['added' => 0, 'started' => 0, 'ended' => 0],
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
            $jaddah = Http::get('https://crm.azyanjeddah.com/api/items')->json();
            $alfursan = Http::get('https://crm.azyanalfursan.com/api/items')->json();

            return [
                'available' => [
                    'dhahran' => $dhahran['available'] ?? 0,
                    'bashaer' => $bashaer['available'] ?? 0,
                    'jaddah' => $jaddah['available'] ?? 0,
                    'alfursan' => $alfursan['available'] ?? 0,
                ],
                'reserved' => [
                    'dhahran' => $dhahran['reserved'] ?? 0,
                    'bashaer' => $bashaer['reserved'] ?? 0,
                    'jaddah' => $jaddah['reserved'] ?? 0,
                    'alfursan' => $alfursan['reserved'] ?? 0,
                ],
                'blocked' => [
                    'dhahran' => $dhahran['blocked'] ?? 0,
                    'bashaer' => $bashaer['blocked'] ?? 0,
                    'jaddah' => $jaddah['blocked'] ?? 0,
                    'alfursan' => $alfursan['blocked'] ?? 0,
                ],
                'contacted' => [
                    'dhahran' => $dhahran['contacted'] ?? 0,
                    'bashaer' => $bashaer['contacted'] ?? 0,
                    'jaddah' => $jaddah['contacted'] ?? 0,
                    'alfursan' => $alfursan['contacted'] ?? 0,
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
            $jaddah = Http::get('https://crm.azyanjeddah.com/api/leads_sources')->json();
            $alfursan = Http::get('https://crm.azyanalfursan.com/api/leads_sources')->json();

            return [
                'dhahran' => $dhahran,
                'bashaer' => $bashaer,
                'jaddah' => $jaddah,
                'alfursan' => $alfursan,
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
