<?php

namespace App\Http\Controllers\ComprehensiveReport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UnitStagesController extends ReportBaseController
{
    protected $reportView = 'comprehensive.partials.unit-stages';
    protected $formRouteName = 'admin.comprehensive.unit-stages.process';

    public function index(Request $request)
    {
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        $dhahranUnitStages = $this->getDhahranUnitStages($from_date, $to_date);
        $albashaerUnitStages = $this->getAlbashaerUnitStages($from_date, $to_date);
        $jeddahUnitStages = $this->getJeddahUnitStages($from_date, $to_date);
// dd($dhahranUnitStages,$albashaerUnitStages,$jeddahUnitStages);
        return view($this->reportView, [
            'from_date' => $from_date,
            'to_date' => $to_date,
            'dhahranUnitStages' => $dhahranUnitStages,
            'albashaerUnitStages' => $albashaerUnitStages,
            'jeddahUnitStages' => $jeddahUnitStages,
        ]);

    }

    private function getDhahranUnitStages($from_date = null, $to_date = null)
    {
        try {
            $formData = [];

            if ($from_date) {
                $formData['from_date'] = $from_date;
            }

            if ($to_date) {
                $formData['to_date'] = $to_date;
            }

            // استخدام POST إذا كانت التواريخ موجودة، وإلا استخدام GET
            if (!empty($formData)) {
                $response = Http::asForm()->post('https://crm.azyanaldhahran.com/api/Item_reports/unitStages', $formData);
            } else {
                $response = Http::get('https://crm.azyanaldhahran.com/api/Item_reports/unitStages');
            }

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Unit Stages API Error: " . $e->getMessage());
        }

        return [
            'stages' => [],
            'totals' => [],
        ];
    }

    private function getAlbashaerUnitStages($from_date = null, $to_date = null)
    {
        try {
            $formData = [];

            if ($from_date) {
                $formData['from_date'] = $from_date;
            }

            if ($to_date) {
                $formData['to_date'] = $to_date;
            }

            // استخدام POST إذا كانت التواريخ موجودة، وإلا استخدام GET
            if (!empty($formData)) {
                $response = Http::asForm()->post('https://crm.azyanalbashaer.com/api/Item_reports/unitStages', $formData);
            } else {
                $response = Http::get('https://crm.azyanalbashaer.com/api/Item_reports/unitStages');
            }

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer Unit Stages API Error: " . $e->getMessage());
        }

        return [
            'stages' => [],
            'totals' => [],
        ];
    }

    private function getJeddahUnitStages($from_date = null, $to_date = null)
    {
        try {
            $formData = [];

            if ($from_date) {
                $formData['from_date'] = $from_date;
            }

            if ($to_date) {
                $formData['to_date'] = $to_date;
            }

            // استخدام POST إذا كانت التواريخ موجودة، وإلا استخدام GET
            if (!empty($formData)) {
                $response = Http::asForm()->post('https://crm.azyanjeddah.com/api/Item_reports/unitStages', $formData);
            } else {
                $response = Http::get('https://crm.azyanjeddah.com/api/Item_reports/unitStages');
            }

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Jeddah Unit Stages API Error: " . $e->getMessage());
        }

        return [
            'stages' => [],
            'totals' => [],
        ];
    }
}
