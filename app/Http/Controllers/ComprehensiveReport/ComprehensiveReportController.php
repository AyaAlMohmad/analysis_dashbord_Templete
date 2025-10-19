<?php

namespace App\Http\Controllers\ComprehensiveReport;

use App\Http\Controllers\Controller;
use App\Models\ReportData;
use App\Models\ReportSection;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ComprehensiveReportController extends Controller
{
    public function form()
    {
        return view('comprehensive.form');
    }

    public function store(Request $request)
    {
        session()->put('report_filter', $request->all());
        return redirect()->route('admin.comprehensive.show');
    }

    public function show()
    {
        $filter = session('report_filter');

        if (!$filter || !isset($filter['from_date']) || !isset($filter['to_date'])) {
            return redirect()->route('admin.comprehensive.form')->withErrors(['msg' => 'يرجى اختيار فترة زمنية.']);
        }

        $request = new \Illuminate\Http\Request($filter);
        return $this->index($request);
    }


    public function index(Request $request)
    {
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $manualProjects = $request->input('projects', []);
        $albashaerData = $this->getAlbashaerReserved($from_date, $to_date);
        $jeddahData = $this->getJeddahReserved($from_date, $to_date);
        $dhahranData = $this->getDhahranReserced($from_date, $to_date);
        $dhahranColoredMap = $this->getDhahranColoredMap($from_date, $to_date);
        $jeddahColoredMap = $this->getJeddahColoredMap($from_date, $to_date);
        $albashaerColoredMap = $this->getAlbashaerColoredMap($from_date, $to_date);
        $dhahranStatusData = $this->getDhahranStatus();
        $jeddahStatusData = $this->getJeddahStatus();
        $bashaerStatusData = $this->getbashaerStatus();
        $dhahranUnitStages = $this->getDhahranUnitStages();
        $jeddahUnitStages = $this->getJeddahUnitStages();
        $albashaerUnitStages = $this->getAlbashaerUnitStages();
        $unitDetailsByStageResultDhahran = $this->unitDetailsByStageResultDhahran();
        $unitDetailsByStageResultJeddah = $this->unitDetailsByStageResultJeddah();
        $unitDetailsByStageResultAlbashaer = $this->unitDetailsByStageResultAlbashaer();
        $albashaerVPCData = $this->getAlbashaerVisitsPaymentsContracts();
        $jeddahVPCData = $this->getJeddahVisitsPaymentsContracts();
        $dhahranVPCData = $this->getDhahranVisitsPaymentsContracts();
        $albashaerDisinterestReasons = $this->getAlbashaerDisinterestReasons();
        $dhahranDisinterestReasons = $this->getDhahranDisinterestReasons();
        // $albashaerUnitStatisticsByStage = $this->getAlbashaerUnitStatisticsByStage();
        // $dhahranUnitStatisticsByStage = $this->getDhahranUnitStatisticsByStage();
        $albashaerUnitStatisticsByStage = $this->getAlbashaerSourceStats();
        $jeddahUnitStatisticsByStage = $this->getJeddahSourceStats();
        $dhahranUnitStatisticsByStage = $this->getDhahranSourceStats();
        $jeddahMonthlyAppointments = $this->getJeddahMonthlyAppointments();
        $dhahranMonthlyAppointments = $this->getDhahranMonthlyAppointments();
        $albashaerMonthlyAppointments = $this->getAlbashaerMonthlyAppointments();

        $albashaerTargetedReport = $this->getAlbashaerTargetedReport($from_date, $to_date);
        $jeddahTargetedReport = $this->getJeddahTargetedReport($from_date, $to_date);
        $dhahranTargetedReport = $this->getDhahranTargetedReport($from_date, $to_date);
        $albashaerUnitSales = $this->getAlbashaerUnitSales($from_date, $to_date);
        $jeddahUnitSales = $this->getJeddahUnitSales($from_date, $to_date);
        $dhahranUnitSales = $this->getDhahranUnitSales($from_date, $to_date);
        $albashaerProjectSummary = $this->getAlbashaerProjectSummaryReport($from_date, $to_date);
        $jeddahProjectSummary = $this->getJeddahProjectSummaryReport($from_date, $to_date);
        $dhahranProjectSummary = $this->getDhahranProjectSummaryReport($from_date, $to_date);
        $selectedSites = $request->input('sites', []);
        $localSites = Site::whereIn('id', $selectedSites)->get();
        $selectedSections = $request->input('sections', []);

        //         $localSectionResults = [];
        // foreach ($selectedSections as $sectionKey) {
        //     // $localSectionResults[$sectionKey] = $this->getSectionDataByName($sectionKey, $from_date, $to_date, $selectedSites);

        // }
        $localSectionResults = $this->getAllSectionsPerSite($selectedSections, $from_date, $to_date, $selectedSites);

        $mergedProjects = collect();

        if (!empty($albashaerData['projects'])) {
            foreach ($albashaerData['projects'] as $project) {
                $mergedProjects->push([
                    'name' => $project['project_name'] ?? '',
                    'developer' => $project['developer_name'] ?? '-',
                    'units' => $project['total_units'] ?? 0,
                    'reserved' => $project['reserved_units'] ?? 0,
                ]);
            }
        }
        if ($jeddahData['projects'] ?? false) {
            foreach ($jeddahData['projects'] as $project) {
                $mergedProjects->push([
                    'name' => $project['project_name'] ?? '',
                    'developer' => $project['developer_name'] ?? '-',
                    'units' => $project['total_units'] ?? 0,
                    'reserved' => $project['reserved_units'] ?? 0,
                ]);
            }
        }



        if (!empty($dhahranData['projects'])) {
            foreach ($dhahranData['projects'] as $project) {
                $mergedProjects->push([
                    'name' => $project['project_name'] ?? '',
                    'developer' => $project['developer_name'] ?? '-',
                    'units' => $project['total_units'] ?? 0,
                    'reserved' => $project['reserved_units'] ?? 0,
                ]);
            }
        }

        foreach ($localSectionResults as $siteResult) {
            $reservedData = $siteResult['sections']['reserved_report']['data']['projects'] ?? [];

            foreach ($reservedData as $project) {
                $mergedProjects->push([
                    'name' => $project['name'] ?? '',
                    'developer' => $project['developer'] ?? '-',
                    'units' => $project['units'] ?? 0,
                    'reserved' => $project['reserved'] ?? 0,
                ]);
            }
        }


        foreach ($manualProjects as $project) {
            $mergedProjects->push([
                'name' => $project['name'] ?? '',
                'developer' => $project['developer'] ?? '-',
                'units' => $project['units'] ?? 0,
                'reserved' => $project['reserved'] ?? 0,
            ]);
        }

        $mergedProjects = $mergedProjects->sortByDesc('reserved')->values();

        $mergedChartLabels = $mergedProjects->pluck('name');
        $mergedChartData = $mergedProjects->pluck('reserved');

        $albashaerDataContract = $this->getAlbashaerContract($from_date, $to_date);
        $dhahranDataContract = $this->getDhahranContract($from_date, $to_date);
        $jeddahDataContract = $this->getJeddahContract($from_date, $to_date);


        $mergedProjectsContract = collect();


        if ($albashaerDataContract['projects'] ?? false) {
            foreach ($albashaerDataContract['projects'] as $project) {
                $mergedProjectsContract->push([
                    'name' => $project['project_name'] ?? '',
                    'developer' => $project['developer_name'] ?? '-',
                    'units' => $project['total_units'] ?? 0,
                    'Contracts' => $project['reserved_units'] ?? 0,
                ]);
            }
        }


        if ($dhahranDataContract['projects'] ?? false) {
            foreach ($dhahranDataContract['projects'] as $project) {
                $mergedProjectsContract->push([
                    'name' => $project['project_name'] ?? '',
                    'developer' => $project['developer_name'] ?? '-',
                    'units' => $project['total_units'] ?? 0,
                    'Contracts' => $project['reserved_units'] ?? 0,
                ]);
            }
        }
        if ($jeddahDataContract['projects'] ?? false) {
            foreach ($jeddahDataContract['projects'] as $project) {
                $mergedProjectsContract->push([
                    'name' => $project['project_name'] ?? '',
                    'developer' => $project['developer_name'] ?? '-',
                    'units' => $project['total_units'] ?? 0,
                    'Contracts' => $project['reserved_units'] ?? 0,
                ]);
            }
        }

        foreach ($localSectionResults as $siteResult) {
            $contractsData = $siteResult['sections']['contracts_report']['data']['projects'] ?? [];

            foreach ($contractsData as $project) {
                $mergedProjectsContract->push([
                    'name' => $project['name'] ?? '',
                    'developer' => $project['developer'] ?? '-',
                    'units' => $project['units'] ?? 0,
                    'Contracts' => $project['Contracts'] ?? 0,
                ]);
            }
        }

        $mergedProjectsContract = $mergedProjectsContract->sortByDesc('Contracts')->values();

        $mergedChartLabelsContract = $mergedProjectsContract->pluck('name');
        $mergedChartDataContract = $mergedProjectsContract->pluck('Contracts');

        // dd($bashaerStatusData);
        // dd($albashaerUnitSales );
        // dd($albashaerUnitStatisticsByStage );
        // dd($localSectionResults[2]['sections']['reserved_report']);
        // dd($unitDetailsByStageResultDhahran,$unitDetailsByStageResultJeddah,$unitDetailsByStageResultAlbashaer);
        return view('comprehensive.index', [
            'from_date' => $from_date,
            'to_date' => $to_date,
            'dhahranData' => $dhahranData,
            'albashaerData' => $albashaerData,
            'jeddahData' => $jeddahData,
            'dhahranDataContract' => $dhahranDataContract,
            'jeddahDataContract' => $jeddahDataContract,
            'albashaerDataContract' => $albashaerDataContract,
            'dhahranStatusData' => $dhahranStatusData,
            'jeddahStatusData' => $jeddahStatusData,
            'bashaerStatusData' => $bashaerStatusData,
            'dhahranUnitStages' => $dhahranUnitStages,
            'jeddahUnitStages' => $jeddahUnitStages,
            'albashaerUnitStages' => $albashaerUnitStages,
            'unitDetailsByStageResultDhahran' => $unitDetailsByStageResultDhahran,
            'unitDetailsByStageResultJeddah' => $unitDetailsByStageResultJeddah,
            'unitDetailsByStageResultAlbashaer' => $unitDetailsByStageResultAlbashaer,
            'unitDetailsByStageResultJeddah' => $unitDetailsByStageResultJeddah,
            'albashaerUnitStatisticsByStage' => $albashaerUnitStatisticsByStage,
            'jeddahUnitStatisticsByStage' => $jeddahUnitStatisticsByStage,
            'dhahranUnitStatisticsByStage' => $dhahranUnitStatisticsByStage,
            'projects' => $mergedProjects,

            'albashaerVPCData' => $albashaerVPCData,
            'jeddahVPCData' => $jeddahVPCData,
            'dhahranVPCData' => $dhahranVPCData,
            'albashaerDisinterestReasons' => $albashaerDisinterestReasons,
            'dhahranDisinterestReasons' => $dhahranDisinterestReasons,
            'dhahranMonthlyAppointments' => $dhahranMonthlyAppointments,
            'jeddahMonthlyAppointments' => $jeddahMonthlyAppointments,
            'albashaerMonthlyAppointments' => $albashaerMonthlyAppointments,
            'albashaerTargetedReport' => $albashaerTargetedReport,
            'jeddahTargetedReport' => $jeddahTargetedReport,
            'dhahranTargetedReport' => $dhahranTargetedReport,
            'albashaerUnitSales' => $albashaerUnitSales,
            'jeddahUnitSales' => $jeddahUnitSales,
            'dhahranUnitSales' => $dhahranUnitSales,
            'albashaerProjectSummary' => $albashaerProjectSummary,
            'jeddahProjectSummary' => $jeddahProjectSummary,
            'dhahranProjectSummary' => $dhahranProjectSummary,
            'dhahranColoredMap' => $dhahranColoredMap,
            'jeddahColoredMap' => $jeddahColoredMap,
            'albashaerColoredMap' => $albashaerColoredMap,
            'localSectionResults' => $localSectionResults,
            'localSites' => $localSites,
            'projectsContract' => $mergedProjectsContract,

            'chart' => [
                'labels' => $mergedChartLabels,
                'data' => $mergedChartData
            ],
            // 'projectsContract' => $mergedProjectsContract,
            'chartContract' => [
                'labels' => $mergedChartLabelsContract,
                'data' => $mergedChartDataContract
            ],
            'requestSites' => $selectedSites,
            'requestSections' => $selectedSections,
        ]);
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
    public function getJeddahReserved($from_date, $to_date)
    {
        try {
            $response = Http::post('https://crm.azyanjeddah.com/api/Item_reports/api_reserved_report', [
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
            Log::error("Jeddah API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch data from Jeddah API',
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
     * getJeddahContractAPI
     */
    private function getJeddahContract($from_date, $to_date)
    {
        try {
            $response = Http::post('https://crm.azyanjeddah.com/api/Item_reports/api_contracts_report', [
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
            Log::error("Jeddah API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch data from Jeddah API',
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
    /**
     * getDhahranStatus API
     */
    private function getDhahranStatus()
    {
        try {
            $response = Http::get('https://crm.azyanaldhahran.com/api/Item_reports_api/api_status_item');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Status API Error: " . $e->getMessage());
        }

        return [
            'groups' => [],
            'statuses' => [],
            'totals' => [],
        ];
    }

    /**
     * getBashaerStatus API
     */
    private function getBashaerStatus()
    {
        try {
            $response = Http::get('https://crm.azyanalbashaer.com/api/Item_reports_api/api_status_item');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Bashaer Status API Error: " . $e->getMessage());
        }

        return [
            'groups' => [],
            'statuses' => [],
            'totals' => [],
        ];
    }

    /**
     * getJeddahStatus API
     */
    private function getJeddahStatus()
    {
        try {
            $response = Http::get('https://crm.azyanjeddah.com/api/Item_reports_api/api_status_item');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Jeddah Status API Error: " . $e->getMessage());
        }

        return [
            'groups' => [],
            'statuses' => [],
            'totals' => [],
        ];
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

    /**
     * Get Dhahran Unit Statistics by Stage
     */
    private function unitDetailsByStageResultDhahran()
    {
        try {
            $response = Http::post('https://crm.azyanaldhahran.com/api/Item_reports/unit_details_by_stage_result');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    // إرجاع البيانات مباشرة بدون 'reports'
                    return $data['data']['reports'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Unit Statistics by Stage API Error: " . $e->getMessage());
        }

        return [];
    }

    /**
     * Get Albashaer Unit Statistics by Stage
     */
    private function unitDetailsByStageResultAlbashaer()
    {
        try {
            $response = Http::post('https://crm.azyanalbashaer.com/api/Item_reports/unit_details_by_stage_result');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    // إرجاع البيانات مباشرة بدون 'reports'
                    return $data['data']['reports'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer Unit Statistics by Stage API Error: " . $e->getMessage());
        }

        return [];
    }

    /**
     * Get Jeddah Unit Statistics by Stage
     */
    private function unitDetailsByStageResultJeddah()
    {
        try {
            $response = Http::post('https://crm.azyanjeddah.com/api/Item_reports/unit_details_by_stage_result');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    // إرجاع البيانات مباشرة بدون 'reports'
                    return $data['data']['reports'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Jeddah Unit Statistics by Stage API Error: " . $e->getMessage());
        }

        return [];
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
                'contracts' => 0,
                'payments' => 0,
                'visits' => 0
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
                'contracts' => 0,
                'payments' => 0,
                'visits' => 0
            ],
            'date_from' => null,
            'date_to' => null
        ];
    }

    /**
     * Get Jeddah Visits, Payments and Contracts Data
     */
    private function getJeddahVisitsPaymentsContracts()
    {
        try {
            $response = Http::get('https://crm.azyanjeddah.com/api/Item_reports/visits_payments_contracts_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Jeddah Visits Payments Contracts API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch data from Jeddah Visits Payments Contracts API',
            'data' => [
                'contracts' => 0,
                'payments' => 0,
                'visits' => 0
            ],
            'date_from' => null,
            'date_to' => null
        ];
    }
    /**
     * Get Albashaer Disinterest Reasons Data
     */
    private function getAlbashaerDisinterestReasons()
    {
        try {
            $response = Http::get('https://crm.azyanalbashaer.com/api/lead_reports/disinterest_reasons_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer Disinterest Reasons API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch data from Albashaer Disinterest Reasons API',
            'data' => [
                'total_leads' => 0,
                'reasons' => []
            ]
        ];
    }

    /**
     * Get Dhahran Disinterest Reasons Data
     */
    private function getDhahranDisinterestReasons()
    {
        try {
            $response = Http::get('https://crm.azyanaldhahran.com/api/lead_reports/disinterest_reasons_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Disinterest Reasons API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch data from Dhahran Disinterest Reasons API',
            'data' => [
                'total_leads' => 0,
                'reasons' => []
            ]
        ];
    }
    /**
     * Get Dhahran Unit Statistics by Stage
     */
    private function getDhahranUnitStatisticsByStage()
    {
        try {
            $response = Http::get('https://crm.azyanaldhahran.com/api/Item_reports/unitStatisticsByStage');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Unit Statistics by Stage API Error: " . $e->getMessage());
        }

        return [
            'statistics' => [],
            'totals' => [],
        ];
    }

    /**
     * Get Albashaer Unit Statistics by Stage
     */
    private function getAlbashaerUnitStatisticsByStage()
    {
        try {
            $response = Http::get('https://crm.azyanalbashaer.com/api/Item_reports/unitStatisticsByStage');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer Unit Statistics by Stage API Error: " . $e->getMessage());
        }

        return [
            'statistics' => [],
            'totals' => [],
        ];
    }
    /**
     * Get Dhahran Source Statistics
     */
    private function getDhahranSourceStats()
    {
        try {
            $response = Http::get('https://crm.azyanaldhahran.com/api/lead_reports/source_stats_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Source Stats API Error: " . $e->getMessage());
        }

        return [
            'sources' => [],
        ];
    }

    /**
     * Get Albashaer Source Statistics
     */
    private function getAlbashaerSourceStats()
    {
        try {
            $response = Http::get('https://crm.azyanalbashaer.com/api/lead_reports/source_stats_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer Source Stats API Error: " . $e->getMessage());
        }

        return [
            'sources' => [],
        ];
    }

    /**
     * Get Jeddah Source Statistics - الدالة المضافة
     */
    private function getJeddahSourceStats()
    {
        try {
            $response = Http::get('https://crm.azyanjeddah.com/api/lead_reports/source_stats_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data['data'] ?? [];
                }
            }
        } catch (\Exception $e) {
            Log::error("Jeddah Source Stats API Error: " . $e->getMessage());
        }

        return [
            'sources' => [],
        ];
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
    private function getAlbashaerTargetedReport($from_date, $to_date)
    {
        try {
            $response = Http::post('https://crm.azyanalbashaer.com/api/lead_reports/targeted_report_api', [
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
            Log::error("Albashaer Targeted Report API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch data from Albashaer Targeted Report API',
            'data' => [],
        ];
    }

    private function getDhahranTargetedReport($from_date, $to_date)
    {
        try {
            $response = Http::post('https://crm.azyanaldhahran.com/api/lead_reports/targeted_report_api', [
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
            Log::error("Dhahran Targeted Report API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch data from Dhahran Targeted Report API',
            'data' => [],
        ];
    }

    private function getJeddahTargetedReport($from_date, $to_date)
    {
        try {
            $response = Http::post('https://crm.azyanjeddah.com/api/lead_reports/targeted_report_api', [
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
            Log::error("Jeddah Targeted Report API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch data from Jeddah Targeted Report API',
            'data' => [],
        ];
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

     private function getAlbashaerProjectSummaryReport($from_date = null, $to_date = null)
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

                $response = Http::get('https://crm.azyanalbashaer.com/api/Item_reports/api_project_summary_report');


            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer Project Summary API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch project summary report from Albashaer API',
            'data' => [],
        ];
    }

    private function getDhahranProjectSummaryReport($from_date = null, $to_date = null)
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

                $response = Http::get('https://crm.azyanaldhahran.com/api/Item_reports/api_project_summary_report');


            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Project Summary API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch project summary report from Dhahran API',
            'data' => [],
        ];
    }

    private function getJeddahProjectSummaryReport($from_date = null, $to_date = null)
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

                $response = Http::get('https://crm.azyanjeddah.com/api/Item_reports/api_project_summary_report');


            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Jeddah Project Summary API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch project summary report from Jeddah API',
            'data' => [],
        ];
    }


    private function getDhahranColoredMap($from_date, $to_date)
    {
        try {
            $response = Http::post('https://crm.azyanaldhahran.com/api/Item_reports/colored_map_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Project Summary API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch project summary report from Dhahran API',
            'data' => [],
        ];
    }
    private function getJeddahColoredMap($from_date, $to_date)
    {
        try {
            $response = Http::post('https://crm.azyanjeddah.com/api/Item_reports/colored_map_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Jeddah Project Summary API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch project summary report from Jeddah API',
            'data' => [],
        ];
    }
    private function getAlbashaerColoredMap($from_date, $to_date)
    {
        try {
            $response = Http::post('https://crm.azyanalbashaer.com/api/Item_reports/colored_map_api');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Project Summary API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch project summary report from Dhahran API',
            'data' => [],
        ];
    }
    private function getSectionId($key)
    {
        return ReportSection::where('name', $key)->value('id');
    }
    private function getSectionDataByName(string $section, string $from, string $to, array $siteIds): array
    {
        $results = [];

        foreach ($siteIds as $siteId) {
            $siteIdArray = is_array($siteId) ? $siteId : [$siteId];
            $results[$siteId] = match ($section) {
                'colored_map' => $this->getColoredMapData($from, $to, $siteId),
                'reserved_report' => $this->getReservedReportData($from, $to, $siteId),
                'contracts_report' => $this->getContractsReportData($from, $to, $siteId),
                'status_item' => $this->getStatusData($from, $to, $siteId),
                'project_summary' => $this->getProjectSummaryData($from, $to, $siteId),
                'unitStages' => $this->getUnitStagesData($from, $to, $siteId),
                'unitStatisticsByStage' => $this->getUnitStatisticsByStageData($from, $to, $siteId),
                'visits_payments_contracts' => $this->getVisitsPaymentsContractsData($from, $to, $siteId),
                'disinterest_reasons' => $this->getDisinterestReasonsData($from, $to, $siteId),
                'unit_sales' => $this->getUnitSalesData($from, $to, $siteId),
                'source_stats' => $this->getSourceStatsData($from, $to, $siteId),
                'monthly_appointments' => $this->getMonthlyAppointmentsData($from, $to, $siteId),
                'targeted_report' => $this->getTargetedReportData($from, $to, $siteId),
                default => [
                    'site' => Site::find($siteId),
                    'sections' => [],
                ],
            };
        }

        return $results;
    }
    private function getAllSectionsPerSite(array $sections, string $from, string $to, array $siteIds): array
    {

        $excluded = ['dhahran', 'albashaer', 'jeddah'];
        $filteredSiteIds = array_filter($siteIds, fn($id) => !in_array(strtolower($id), $excluded));

        $results = [];

        foreach ($filteredSiteIds as $siteId) {
            $site = Site::find($siteId);
            $results[$siteId] = [
                'site' => $site,
                'sections' => [],
            ];

            foreach ($sections as $section) {
                $results[$siteId]['sections'][$section] = match ($section) {
                    'colored_map' => $this->getColoredMapData($from, $to, $siteId),
                    'reserved_report' => $this->getReservedReportData($from, $to, $siteId),
                    'contracts_report' => $this->getContractsReportData($from, $to, $siteId),
                    'status_item' => $this->getStatusData($from, $to, $siteId),
                    'project_summary' => $this->getProjectSummaryData($from, $to, $siteId),
                    'unitStages' => $this->getUnitStagesData($from, $to, $siteId),
                    'unitStatisticsByStage' => $this->getUnitStatisticsByStageData($from, $to, $siteId),
                    'visits_payments_contracts' => $this->getVisitsPaymentsContractsData($from, $to, $siteId),
                    'disinterest_reasons' => $this->getDisinterestReasonsData($from, $to, $siteId),
                    'unit_sales' => $this->getUnitSalesData($from, $to, $siteId),
                    'source_stats' => $this->getSourceStatsData($from, $to, $siteId),
                    'monthly_appointments' => $this->getMonthlyAppointmentsData($from, $to, $siteId),
                    'targeted_report' => $this->getTargetedReportData($from, $to, $siteId),
                    default => [],
                };
            }
        }

        return $results;
    }



    private function getColoredMapData($from, $to, $siteId)
    {
        $sectionId = $this->getSectionId('colored_map');


        $site = Site::where('id', $siteId)->get();

        return $site;
    }

    public function getReservedReportData($from, $to, $siteId)
    {
        $sectionId = $this->getSectionId('reserved_report');

        $records = ReportData::where('section_id', $sectionId)
            ->where('site_id', $siteId)
            ->get();

        $projects = collect();
        $chartLabels = [];
        $chartData = [];

        foreach ($records as $record) {
            $data = $record->data;

            foreach ($data['projects'] ?? [] as $proj) {
                $project = [
                    'name' => $proj['project_name'] ?? '—',
                    'developer' => $proj['developer_name'] ?? '—',
                    'units' => $proj['total_units'] ?? 0,
                    'reserved' => $proj['reserved_units'] ?? 0,
                ];

                $projects->push($project);
            }

            $chartLabels = $data['chart_labels'] ?? [];
            $chartData = $data['chart_data'] ?? [];
        }

        return [
            'status' => true,
            'data' => [
                'projects' => $projects,
                'chart' => [
                    'labels' => array_values($chartLabels),
                    'data' => array_values($chartData),
                ],
            ],
        ];
    }




    private function getContractsReportData($from, $to, $siteId)
    {
        $sectionId = $this->getSectionId('contracts_report');

        $records = ReportData::where('section_id', $sectionId)
            ->where('site_id', $siteId)
            ->get();

        $projects = collect();
        $chartLabels = [];
        $chartData = [];

        foreach ($records as $record) {
            $data = $record->data;

            foreach ($data['projects'] ?? [] as $proj) {
                $project = [
                    'name' => $proj['project_name'] ?? '—',
                    'developer' => $proj['developer_name'] ?? '—',
                    'units' => $proj['total_units'] ?? 0,
                    'Contracts' => $proj['contracted_units'] ?? 0,
                ];

                $projects->push($project);
            }

            $chartLabels = $data['chart_labels'] ?? [];
            $chartData = $data['chart_data'] ?? [];
        }

        return [
            'status' => true,
            'data' => [
                'projects' => $projects,
                'chart' => [
                    'labels' => array_values($chartLabels),
                    'data' => array_values($chartData),
                ],
            ],
        ];
    }

    public function getStatusData($from, $to, $siteId)
    {
        $sectionId = $this->getSectionId('status_item');

        $data = ReportData::where('section_id', $sectionId)
            ->where('site_id', $siteId)
            ->get();

        $statusGroups = [];
        $allStatuses = [];
        $statusTotals = [];
        $totalItems = 0;

        foreach ($data as $item) {
            $dataItem = is_string($item->data) ? json_decode($item->data, true) : $item->data;

            if (!isset($dataItem['groups'])) {
                continue;
            }

            foreach ($dataItem['groups'] as $group) {
                $groupId = (string) ($group['group_id'] ?? 0);
                $statuses = $group['statuses'] ?? [];

                if (!isset($statusGroups[$groupId])) {
                    $statusGroups[$groupId] = [
                        'group_id' => $groupId,
                        'total_items' => 0,
                        'statuses' => [],
                    ];
                }

                foreach ($statuses as $status) {
                    $name = $status['status_name'] ?? 'unknown';
                    $total = $status['total'] ?? 0;
                    $beneficiary = $status['beneficiary'] ?? 0;
                    $nonBeneficiary = $status['non_beneficiary'] ?? 0;

                    if (!isset($statusGroups[$groupId]['statuses'][$name])) {
                        $statusGroups[$groupId]['statuses'][$name] = [
                            'status_name' => $name,
                            'total' => 0,
                            'beneficiary' => 0,
                            'non_beneficiary' => 0,
                        ];
                    }

                    $statusGroups[$groupId]['statuses'][$name]['total'] += $total;
                    $statusGroups[$groupId]['statuses'][$name]['beneficiary'] += $beneficiary;
                    $statusGroups[$groupId]['statuses'][$name]['non_beneficiary'] += $nonBeneficiary;

                    if (!isset($statusTotals[$name])) {
                        $statusTotals[$name] = [
                            'beneficiary' => 0,
                            'non_beneficiary' => 0,
                        ];
                    }

                    $statusTotals[$name]['beneficiary'] += $beneficiary;
                    $statusTotals[$name]['non_beneficiary'] += $nonBeneficiary;

                    $totalItems += $total;
                    $statusGroups[$groupId]['total_items'] += $total;

                    $allStatuses[$name] = ['unit_status' => $name];
                }
            }
        }



        $groups = array_map(function ($group) {
            $group['statuses'] = array_values($group['statuses']);
            return $group;
        }, array_values($statusGroups));

        return [
            'status' => true,
            'data' => [
                'groups' => $groups,
                'statuses' => array_values($allStatuses),
                'totals' => [
                    'total_items' => $totalItems,
                    'status_totals' => $statusTotals,
                ],
            ],
        ];
    }





    public function getProjectSummaryData($from, $to, $siteId)
    {
        $sectionId = $this->getSectionId('project_summary');

        $records = ReportData::where('section_id', $sectionId)
            ->where('site_id', $siteId)
            ->get();

        $options = ['A', 'B', 'C', 'D', 'E', 'F'];

        $totalItems = array_fill_keys($options, 0);
        $reservedItems = array_fill_keys($options, 0);
        $percentages = array_fill_keys($options, 0);

        foreach ($records as $record) {
            $data = $record->data['items'] ?? [];

            foreach ($options as $key) {
                $totalUnits = (int)($data['total_items'][$key] ?? 0);
                $totalReservations = (int)($data['reserved_items'][$key] ?? 0);

                $totalItems[$key] += $totalUnits;
                $reservedItems[$key] += $totalReservations;
            }
        }

        foreach ($options as $key) {
            $total = $totalItems[$key];
            $reserved = $reservedItems[$key];
            $percentages[$key] = ($total > 0) ? round(($reserved / $total) * 100, 2) : 0;
        }

        return [
            'status' => true,
            'message' => 'Project summary retrieved successfully.',
            'data' => [
                'items' => [
                    'options' => array_combine($options, $options),
                    'total_items' => $totalItems,
                    'reserved_items' => $reservedItems,
                ],
                'percentages' => $percentages,
            ],
        ];
    }




    public function getUnitStagesData($from, $to, $siteId)
    {
        $sectionId = $this->getSectionId('unitStages');

        $records = ReportData::where('section_id', $sectionId)
            ->where('site_id', $siteId)
            ->get();

        $result = [];
        $totals = [
            'available' => 0,
            'reserved' => 0,
            'contacted' => 0,
            'blocked' => 0,
        ];

        $stages = ['A', 'B', 'C', 'D', 'E', 'F'];

        // Initialize stages
        foreach ($stages as $stage) {
            $result[$stage] = [
                'available' => 0,
                'reserved' => 0,
                'contacted' => 0,
                'blocked' => 0,
                'total' => 0,
            ];
        }

        foreach ($records as $record) {
            $data = $record->data ?? [];
            $stageGroups = $data['groups'] ?? [];

            foreach ($stageGroups as $stage => $values) {
                $available = (int)($values['available'] ?? 0);
                $reserved = (int)($values['reserved'] ?? 0);
                $contacted = (int)($values['contacted'] ?? 0);
                $blocked = (int)($values['blocked'] ?? 0);
                $total = (int)($values['total'] ?? 0);

                // Initialize if not exist (in case new/unexpected stage)
                if (!isset($result[$stage])) {
                    $result[$stage] = [
                        'available' => 0,
                        'reserved' => 0,
                        'contacted' => 0,
                        'blocked' => 0,
                        'total' => 0,
                    ];
                }

                $result[$stage]['available'] += $available;
                $result[$stage]['reserved'] += $reserved;
                $result[$stage]['contacted'] += $contacted;
                $result[$stage]['blocked'] += $blocked;
                $result[$stage]['total'] += $total;

                $totals['available'] += $available;
                $totals['reserved'] += $reserved;
                $totals['contacted'] += $contacted;
                $totals['blocked'] += $blocked;
            }
        }

        $result['totals'] = $totals;

        return $result;
    }

    public function getUnitStatisticsByStageData($from, $to, $siteId)
    {
        $sectionId = $this->getSectionId('unitStatisticsByStage');

        $records = ReportData::where('section_id', $sectionId)
            ->where('site_id', $siteId)
            ->get();
        // dd($records);
        $result = [];

        foreach ($records as $record) {
            $data = $record->data ?? [];

            foreach ($data as $groupEntry) {
                $groupId = $groupEntry['group_id'] ?? 'unknown';
                $groupName = $groupEntry['group_name'] ?? $groupId;
                $entries = $groupEntry['report_data'] ?? [];

                if (!isset($result[$groupId])) {
                    $result[$groupId] = [
                        'group_id' => $groupId,
                        'group_name' => $groupName,
                        'report_data' => [],
                        'totals' => [
                            'total_items' => 0,
                            'available_blocked' => 0,
                            'min_rate' => null,
                            'max_rate' => null,
                        ],
                    ];
                }

                foreach ($entries as $entry) {
                    $type = $entry['type'] ?? 'Unknown';
                    $count = (int)($entry['count'] ?? 0);
                    $availableBlocked = (int)($entry['available_blocked'] ?? 0);
                    $minRate = is_numeric($entry['min_rate']) ? (float)$entry['min_rate'] : null;
                    $maxRate = is_numeric($entry['max_rate']) ? (float)$entry['max_rate'] : null;

                    $result[$groupId]['report_data'][$type] = [
                        'type' => $type,
                        'count' => $count,
                        'available_blocked' => $availableBlocked,
                        'min_rate' => $minRate,
                        'max_rate' => $maxRate,
                    ];

                    $result[$groupId]['totals']['total_items'] += $count;
                    $result[$groupId]['totals']['available_blocked'] += $availableBlocked;

                    if ($minRate !== null) {
                        $tMin = $result[$groupId]['totals']['min_rate'];
                        $result[$groupId]['totals']['min_rate'] = ($tMin === null) ? $minRate : min($tMin, $minRate);
                    }

                    if ($maxRate !== null) {
                        $tMax = $result[$groupId]['totals']['max_rate'];
                        $result[$groupId]['totals']['max_rate'] = ($tMax === null) ? $maxRate : max($tMax, $maxRate);
                    }
                }
            }
        }


        $types = ['A', 'B', 'C', 'D', 'E', 'F'];
        foreach ($result as &$group) {
            foreach ($types as $type) {
                if (!isset($group['report_data'][$type])) {
                    $group['report_data'][$type] = [
                        'type' => $type,
                        'count' => 0,
                        'available_blocked' => 0,
                        'min_rate' => null,
                        'max_rate' => null,
                    ];
                }
            }

            uksort($group['report_data'], function ($a, $b) use ($types) {
                return array_search($a, $types) - array_search($b, $types);
            });

            $group['report_data'] = array_values($group['report_data']);
        }


        return array_values($result);
    }


    public function getVisitsPaymentsContractsData($from, $to, $siteId)
    {
        $sectionId = $this->getSectionId('visits_payments_contracts');

        $records = ReportData::where('section_id', $sectionId)
            ->where('site_id', $siteId)
            ->get();

        $result = [
            'status' => true,
            'message' => 'Visit, payment, and contract stats retrieved successfully.',
            'data' => [],
            'date_from' => $from,
            'date_to' => $to,
        ];

        $periods = ['current_month', 'last_month', 'two_months_ago'];
        $weeks = ['week_1', 'week_2', 'week_3', 'week_4', 'week_5'];

        // ✅ init structure
        foreach ($periods as $period) {
            $result['data'][$period] = [];

            foreach ($weeks as $week) {
                $result['data'][$period][$week] = ['visits' => 0, 'payments' => 0, 'contracts' => 0];
            }

            $result['data'][$period]['month_total'] = ['visits' => 0, 'payments' => 0, 'contracts' => 0];
        }

        foreach ($records as $record) {
            $data = $record->data;

            foreach ($periods as $period) {
                $periodData = $data[$period] ?? [];

                foreach ($weeks as $week) {
                    $visits = (int)($periodData[$week]['visits'] ?? 0);
                    $payments = (int)($periodData[$week]['payments'] ?? 0);
                    $contracts = (int)($periodData[$week]['contracts'] ?? 0);

                    $result['data'][$period][$week]['visits'] += $visits;
                    $result['data'][$period][$week]['payments'] += $payments;
                    $result['data'][$period][$week]['contracts'] += $contracts;

                    $result['data'][$period]['month_total']['visits'] += $visits;
                    $result['data'][$period]['month_total']['payments'] += $payments;
                    $result['data'][$period]['month_total']['contracts'] += $contracts;
                }
            }
        }

        return $result;
    }

    public function getDisinterestReasonsData($from, $to, $siteId)
    {
        $sectionId = $this->getSectionId('disinterest_reasons');

        $records = ReportData::where('section_id', $sectionId)
            ->where('site_id', $siteId)
            ->get();

        $reasonCounts = [];
        $totalLeads = 0;

        foreach ($records as $record) {
            $data = $record->data;

            $reasons = $data['reasons'] ?? [];
            $recordLeads = (int)($data['total_leads'] ?? 0);

            foreach ($reasons as $item) {
                $reason = (string)($item['reason'] ?? 'غير معروف');
                $count = (int)($item['count'] ?? 0);

                if (!isset($reasonCounts[$reason])) {
                    $reasonCounts[$reason] = 0;
                }

                $reasonCounts[$reason] += $count;
            }

            $totalLeads += $recordLeads;
        }

        $finalReasons = [];

        foreach ($reasonCounts as $reason => $count) {
            $percentage = $totalLeads > 0 ? round(($count / $totalLeads) * 100, 2) : 0;

            $finalReasons[] = [
                'reason' => $reason,
                'count' => $count,
                'percentage' => $percentage,
            ];
        }

        return [
            'status' => true,
            'message' => 'Disinterest reasons retrieved successfully.',
            'data' => [
                'total_leads' => $totalLeads,
                'reasons' => $finalReasons,
            ],
        ];
    }



    public function getUnitSalesData($from, $to, $siteId)
    {
        $sectionId = $this->getSectionId('unit_sales');

        $records = ReportData::where('section_id', $sectionId)
            ->where('site_id', $siteId)
            ->get();

        $models = ['A', 'B', 'C', 'D', 'E', 'F'];
        $reservedTotals = [];
        $contractedTotals = [];
        $minArea = null;
        $maxArea = null;
        $allItems = [];

        foreach ($records as $record) {
            $data = is_string($record->data) ? json_decode($record->data, true) : $record->data;
            if (!is_array($data)) continue;

            $items = $data['items_by_model'] ?? [];

            foreach ($items as $model => $modelData) {
                $reservedArea = (float)($modelData['reserved_area'] ?? 0);
                $reservedPrice = (float)($modelData['reserved_price'] ?? 0);
                $contractedArea = (float)($modelData['contracted_area'] ?? 0);
                $contractedPrice = (float)($modelData['contracted_price'] ?? 0);

                // Totals (نريد فقط السعر هنا)
                $reservedTotals[$model] = ($reservedTotals[$model] ?? 0) + $reservedPrice;
                $contractedTotals[$model] = ($contractedTotals[$model] ?? 0) + $contractedPrice;

                // Min/Max Area
                foreach ([$reservedArea, $contractedArea] as $area) {
                    if ($area > 0) {
                        $minArea = $minArea === null ? $area : min($minArea, $area);
                        $maxArea = $maxArea === null ? $area : max($maxArea, $area);
                    }
                }

                // نحتفظ بالبيانات كما هي لعرض التفاصيل في الجدول
                $allItems[$model] = $modelData;
            }
        }

        return [
            'status' => true,
            'message' => 'Unit sales data retrieved successfully',
            'data' => [
                'models' => $models,
                'items_by_model' => $allItems,
                'min_area' => $minArea,
                'max_area' => $maxArea,
                'reserved_totals' => $reservedTotals,
                'contracted_totals' => $contractedTotals,
            ],
        ];
    }




    public function getSourceStatsData($from, $to, $siteId)
    {
        $sectionId = $this->getSectionId('source_stats');

        $records = ReportData::where('section_id', $sectionId)
            ->where('site_id', $siteId)
            ->get();

        $totals = [
            'total_leads' => 0,
            'visited_leads' => 0,
            'paid_leads' => 0,
            'contract_leads' => 0,
            'visited_percent' => 0,
            'paid_from_visited_percent' => 0,
            'contract_from_paid_percent' => 0,
        ];

        $sources = [];

        foreach ($records as $record) {
            $data = $record->data ?? [];

            // اجمع إجمالي البيانات من كل سجل
            $totals['total_leads'] += (int)($data['totals']['total_leads'] ?? 0);
            $totals['visited_leads'] += (int)($data['totals']['visited_leads'] ?? 0);
            $totals['paid_leads'] += (int)($data['totals']['paid_leads'] ?? 0);
            $totals['contract_leads'] += (int)($data['totals']['contract_leads'] ?? 0);

            foreach ($data['sources'] ?? [] as $entry) {
                $name = $entry['source_name'] ?? 'Unknown';

                if (!isset($sources[$name])) {
                    $sources[$name] = [
                        'source_name' => $name,
                        'total_leads' => 0,
                        'visited_leads' => 0,
                        'paid_leads' => 0,
                        'contract_leads' => 0,
                    ];
                }

                $sources[$name]['total_leads'] += (int)($entry['total_leads'] ?? 0);
                $sources[$name]['visited_leads'] += (int)($entry['visited_leads'] ?? 0);
                $sources[$name]['paid_leads'] += (int)($entry['paid_leads'] ?? 0);
                $sources[$name]['contract_leads'] += (int)($entry['contract_leads'] ?? 0);
            }
        }

        // احسب النسب المئوية لكل مصدر
        $sources = array_values(array_map(function ($source) {
            $source['visited_percent'] = $source['total_leads'] > 0 ? round(($source['visited_leads'] / $source['total_leads']) * 100, 2) : 0;
            $source['paid_from_visited_percent'] = $source['visited_leads'] > 0 ? round(($source['paid_leads'] / $source['visited_leads']) * 100, 2) : 0;
            $source['contract_from_paid_percent'] = $source['paid_leads'] > 0 ? round(($source['contract_leads'] / $source['paid_leads']) * 100, 2) : 0;
            return $source;
        }, $sources));

        // احسب النسب المئوية للإجماليات
        $totals['visited_percent'] = $totals['total_leads'] > 0 ? round(($totals['visited_leads'] / $totals['total_leads']) * 100, 2) : 0;
        $totals['paid_from_visited_percent'] = $totals['visited_leads'] > 0 ? round(($totals['paid_leads'] / $totals['visited_leads']) * 100, 2) : 0;
        $totals['contract_from_paid_percent'] = $totals['paid_leads'] > 0 ? round(($totals['contract_leads'] / $totals['paid_leads']) * 100, 2) : 0;

        return [
            'totals' => $totals,
            'sources' => $sources,
        ];
    }



    public function getMonthlyAppointmentsData($from, $to, $siteId)
    {
        $sectionId = $this->getSectionId('monthly_appointments');

        $record = ReportData::where('section_id', $sectionId)
            ->where('site_id', $siteId)
            ->first();

        $data = $record?->data ?? [];

        return [
            'total_appointments' => (int)($data['total_appointments'] ?? 0),
            'completed_visits' => (int)($data['completed_visits'] ?? 0),
        ];
    }

    public function getTargetedReportData($from, $to, $siteId)
    {
        $sectionId = $this->getSectionId('targeted_report');

        $record = ReportData::where('section_id', $sectionId)
            ->where('site_id', $siteId)
            ->first();

        $item = $record?->data['data'] ?? [];

        $statuses = ['مهتم', 'موعد', 'زيارة', 'حجز', 'إلغاء', 'عقد'];
        $result = [];

        foreach ($statuses as $status) {
            $count = (int)($item[$status]['count'] ?? 0);
            $target = (int)($item[$status]['target'] ?? 0);
            $percentage = $target > 0 ? round($count / $target, 4) : 0;

            $result[$status] = [
                'count' => $count,
                'target' => $target,
                'percentage' => $percentage,
            ];
        }

        $result['total'] = (int)($item['total'] ?? array_sum(array_column($result, 'count')));

        return [
            'status' => true,
            'message' => 'Targeted lead status report retrieved successfully.',
            'data' => $result,
            'from_date' => $from,
            'to_date' => $to,
        ];
    }
}
