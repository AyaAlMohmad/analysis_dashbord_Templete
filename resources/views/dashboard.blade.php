@extends('layouts.app')

@section('content')
    <style>
        .card-box-dark {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(9, 8, 8, 0.25);
            border: 5px solid rgba(7, 7, 7, 0.05);
            transition: transform 0.3s ease-in-out;
        }

        .card-box-dark:hover {
            transform: translateY(-5px);
        }

        .card .opacity-25 {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 3.5rem;
            opacity: 0.08;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .stat-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .stat-card .card-header {
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            background: linear-gradient(135deg, var(--color, #f8f9fa) 0%, #ffffff 100%);
        }

        .stat-card .card-header i {
            font-size: 28px;
            color: var(--color, #6c757d);
            background: rgba(255, 255, 255, 0.9);
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .stat-card .card-header h3 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: var(--color, #343a40);
        }

        .stat-card .card-header h3 span {
            font-size: 16px;
            font-weight: 500;
            color: #6c757d;
        }

        .stat-card .card-body {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .stat-card .card-title {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .stat-card .card-value {
            color: #495057;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .stat-card .progress-bar {
            height: 6px;
            border-radius: 3px;
            background: #f0f0f0;
            margin-top: auto;
            position: relative;
        }

        .stat-card .progress-bar::after {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: var(--width, 0%);
            background: var(--color, #007bff);
            border-radius: 3px;
            transition: width 0.6s ease;
        }

        /* ألوان مخصصة لكل بطاقة */
        .stat-card.total-units {
            --color: var(--primary);
        }

        .stat-card.available {
            --color: var(--info);
        }

        .stat-card.blocked {
            --color: var(--warning);
        }

        .stat-card.reserved {
            --color: var(--danger);
        }

        .stat-card.contracted {
            --color: var(--success);
        }

        .stat-card.overall {
            --color: var(--purple);
        }

        .stat-card.progress {
            --color: var(--primary);
        }

        :root {
            --purple: #6f42c1;
            --teal: #20c997;
        }

        .chart-container-narrow {
            width: 100% !important;
            height: 280px !important;
            /* ارتفاع أقل */
            max-width: 100%;
        }

        /* مخطط دائري واسع */
        .chart-container-wide {
            width: 100% !important;
            height: 350px !important;
            /* ارتفاع أكبر */
            max-width: 100%;
        }

        /* لتحسين عرض مخطط الخط الضيق */
        #weekly-activity-chart {
            font-size: 12px !important;
        }

        .chart-container-narrow {
            width: 100% !important;
            height: 320px !important;
            /* زيادة الارتفاع من 280 إلى 320 */
            max-width: 100%;
        }

        /* تحسين إضافي للمظهر */
        #weekly-activity-chart {
            background-color: rgba(248, 249, 250, 0.5);
            /* خلفية فاتحة */
            border-radius: 8px;
            padding: 10px;
        }

        /* ألوان مخصصة لكل بطاقة */
        .stat-card.total-units {
            --color: var(--primary);
        }

        .stat-card.available {
            --color: var(--info);
        }

        .stat-card.blocked {
            --color: var(--warning);
        }

        .stat-card.reserved {
            --color: var(--danger);
        }

        .stat-card.contracted {
            --color: var(--success);
        }

        .stat-card.overall {
            --color: var(--purple);
        }

        .stat-card.progress {
            --color: var(--primary);
        }

        .stat-card.beneficiary {
            --color: var(--teal);
        }

        .stat-card.non-beneficiary {
            --color: var(--secondary);
        }

        :root {
            --purple: #6f42c1;
            --teal: #20c997;
            --secondary: #6c757d;
        }
    </style>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="site-selector">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-outline-primary active" id="all-btn">
                                    <input type="radio" name="site" value="all" checked>
                                    🌐 {{ __('comparison_report.all') }}
                                </label>
                                <label class="btn btn-outline-primary" id="dhahran-btn">
                                    <input type="radio" name="site" value="dhahran">
                                    🏢 {{ __('comparison_report.dhahran') }}
                                </label>
                                <label class="btn btn-outline-primary" id="bashaer-btn">
                                    <input type="radio" name="site" value="bashaer">
                                    🏣 {{ __('comparison_report.bashaer') }}
                                </label>
                                <label class="btn btn-outline-primary" id="jaddah-btn">
                                    <input type="radio" name="site" value="jaddah">
                                    🏬 {{ __('comparison_report.jaddah') }}
                                </label>
                                <label class="btn btn-outline-primary" id="alfursan-btn">
                                    <input type="radio" name="site" value="alfursan">
                                    🏰 {{ __('comparison_report.alfursan') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">

            <div class="row" id="villa-summary-section">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-gradient-primary">
                            <h4 class="card-title text-white" id="villa-summary-title">

                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row" id="villa-summary-data">

                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Activity Charts -->
            <!-- Activity Charts -->
            <div class="row match-height" id="activity-charts-section">
                <!-- مخطط الخط - جعلته أضيق -->
                <div class="col-xl-6 col-lg-12"> <!-- غيرت من col-xl-8 إلى col-xl-6 -->
                    <div class="card">
                        <div class="card-header border-0-bottom">
                            <h4 class="card-title">{{ __('comparison_report.activity_chart_title') }}
                                <span
                                    class="text-muted text-bold-400">{{ __('comparison_report.activity_chart_sub') }}</span>
                            </h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <canvas id="weekly-activity-chart" class="chart-container-narrow"></canvas>
                                <ul class="list-inline text-center m-0" id="activity-chart-legend">
                                    <!-- Legend will be loaded here by JavaScript -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- مخطط الدائرة - جعلته أوسع -->
                <div class="col-xl-6 col-lg-12"> <!-- غيرت من col-xl-4 إلى col-xl-6 -->
                    <div class="card">
                        <div class="card-header border-0-bottom">
                            <h4 class="card-title">{{ __('comparison_report.activity_distribution') }}</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <canvas id="activity-division" class="chart-container-wide"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calls Section -->
            <div class="row" id="calls-section">
                <!-- Calls data will be loaded here by JavaScript -->
            </div>

            <!-- Appointments Section -->
            <div class="row match-height" id="appointments-section">
                <div class="col-xl-6 col-lg-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <h4 class="card-title">{{ __('comparison_report.avg_session_duration') }}</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <canvas id="appointments-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <h4 class="card-title">{{ __('comparison_report.appointments_by_date') }}</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content">
                            <div id="goal-list-scroll" class="table-responsive height-250 position-relative">
                                <table class="table mb-0" id="appointments-table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('comparison_report.date') }}</th>
                                            <th>{{ __('comparison_report.appointment_count') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="appointments-data">
                                        <!-- Data will be loaded here by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Global variables to store chart instances
        let weeklyActivityChart = null;
        let activityDivisionChart = null;
        let appointmentsChart = null;

        // Initial data
        const comparisonData = @json($comparisonData);
        const villaSummary = comparisonData.villa_summary;
        const leadsTimeline = comparisonData.leads_timeline || {};
        const callLogs = comparisonData.call_logs || { totals: {} };
        const appointments = comparisonData.appointments || { timeline: [] };

        // Default empty data structure
        // Default empty data structure
        const defaultSiteSummary = {
            total_units: 0,
            total_price: 0,
            available: { count: 0, percentage: 0, total_value: 0 },
            blocked: { count: 0, percentage: 0, total_value: 0 },
            reserved: { count: 0, percentage: 0, total_value: 0 },
            contracted: { count: 0, percentage: 0, total_value: 0 },
            beneficiary: { count: 0, percentage: 0, total_value: 0 },
            non_beneficiary: { count: 0, percentage: 0, total_value: 0 },
            overall_value: 0,
            overall_progress_percentage: 0
        };
        // const defaultSiteSummary = {
        //     total_units: 0,
        //     total_price: 0,
        //     available: { count: 0, percentage: 0, total_value: 0 },
        //     blocked: { count: 0, percentage: 0, total_value: 0 },
        //     reserved: { count: 0, percentage: 0, total_value: 0 },
        //     contracted: { count: 0, percentage: 0, total_value: 0 },
        //     overall_value: 0,
        //     overall_progress_percentage: 0
        // };

        // Format number
        function numberFormat(number) {
            return new Intl.NumberFormat().format(number);
        }

        // Change active button
        function setActiveButton(button) {
            document.querySelectorAll('.site-selector .btn').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
        }

        // Safe data access with defaults
        function getSiteData(site) {
            return villaSummary[site] || defaultSiteSummary;
        }

        // Load full site data
        // function loadSiteData(site) {
        //     let summary, siteName, siteColor;

        //     if (site === 'all') {
        //         siteName = '{{ __('comparison_report.all') }}';
        //         siteColor = 'primary';

        //         const dh = getSiteData('dhahran');
        //         const bs = getSiteData('bashaer');
        //         const jd = getSiteData('jaddah');
        //         const af = getSiteData('alfursan');

        //         summary = {
        //             total_units: dh.total_units + bs.total_units + jd.total_units + af.total_units,
        //             total_price: dh.total_price + bs.total_price + jd.total_price + af.total_price,
        //             available: {
        //                 count: dh.available.count + bs.available.count + jd.available.count + af.available.count,
        //                 percentage: ((dh.available.count + bs.available.count + jd.available.count + af.available.count) / (dh.total_units + bs.total_units + jd.total_units + af.total_units || 1)) * 100,
        //                 total_value: dh.available.total_value + bs.available.total_value + jd.available.total_value + af.available.total_value
        //             },
        //             blocked: {
        //                 count: dh.blocked.count + bs.blocked.count + jd.blocked.count + af.blocked.count,
        //                 percentage: ((dh.blocked.count + bs.blocked.count + jd.blocked.count + af.blocked.count) / (dh.total_units + bs.total_units + jd.total_units + af.total_units || 1)) * 100,
        //                 total_value: dh.blocked.total_value + bs.blocked.total_value + jd.blocked.total_value + af.blocked.total_value
        //             },
        //             reserved: {
        //                 count: dh.reserved.count + bs.reserved.count + jd.reserved.count + af.reserved.count,
        //                 percentage: ((dh.reserved.count + bs.reserved.count + jd.reserved.count + af.reserved.count) / (dh.total_units + bs.total_units + jd.total_units + af.total_units || 1)) * 100,
        //                 total_value: dh.reserved.total_value + bs.reserved.total_value + jd.reserved.total_value + af.reserved.total_value
        //             },
        //             contracted: {
        //                 count: dh.contracted.count + bs.contracted.count + jd.contracted.count + af.contracted.count,
        //                 percentage: ((dh.contracted.count + bs.contracted.count + jd.contracted.count + af.contracted.count) / (dh.total_units + bs.total_units + jd.total_units + af.total_units || 1)) * 100,
        //                 total_value: dh.contracted.total_value + bs.contracted.total_value + jd.contracted.total_value + af.contracted.total_value
        //             },
        //             overall_value: dh.overall_value + bs.overall_value + jd.overall_value + af.overall_value,
        //             overall_progress_percentage: ((dh.reserved.count + bs.reserved.count + jd.reserved.count + af.reserved.count + dh.contracted.count + bs.contracted.count + jd.contracted.count + af.contracted.count) /
        //                 (dh.total_units + bs.total_units + jd.total_units + af.total_units || 1)) * 100
        //         };

        //     } else {
        //         if (site === 'jaddah') {
        //             siteName = '{{ __('comparison_report.jaddah') }}';
        //             siteColor = 'warning';
        //         } else if (site === 'alfursan') {
        //             siteName = '{{ __('comparison_report.alfursan') }}';
        //             siteColor = 'teal';
        //         } else {
        //             siteName = site === 'dhahran' ? '{{ __('comparison_report.dhahran') }}' : '{{ __('comparison_report.bashaer') }}';
        //             siteColor = site === 'dhahran' ? 'primary' : 'success';
        //         }
        //         summary = getSiteData(site);
        //     }

        //     document.getElementById('villa-summary-title').innerText = siteName;
        //     updateVillaSummaryHTML(summary, site, siteColor);
        //     updateActivityCharts(site);
        //     updateAppointments(site);
        //     updateCallsSection(site, siteName);
        // }
        function loadSiteData(site) {
            let summary, siteName, siteColor;

            if (site === 'all') {
                    siteName = '{{ __('comparison_report.all') }}';
                siteColor = 'primary';

                const dh = getSiteData('dhahran');
                const bs = getSiteData('bashaer');
                const jd = getSiteData('jaddah');
                const af = getSiteData('alfursan');

                summary = {
                    total_units: dh.total_units + bs.total_units + jd.total_units + af.total_units,
                    total_price: dh.total_price + bs.total_price + jd.total_price + af.total_price,
                    available: {
                        count: dh.available.count + bs.available.count + jd.available.count + af.available.count,
                        percentage: ((dh.available.count + bs.available.count + jd.available.count + af.available.count) / (dh.total_units + bs.total_units + jd.total_units + af.total_units || 1)) * 100,
                        total_value: dh.available.total_value + bs.available.total_value + jd.available.total_value + af.available.total_value
                    },
                    blocked: {
                        count: dh.blocked.count + bs.blocked.count + jd.blocked.count + af.blocked.count,
                        percentage: ((dh.blocked.count + bs.blocked.count + jd.blocked.count + af.blocked.count) / (dh.total_units + bs.total_units + jd.total_units + af.total_units || 1)) * 100,
                        total_value: dh.blocked.total_value + bs.blocked.total_value + jd.blocked.total_value + af.blocked.total_value
                    },
                    reserved: {
                        count: dh.reserved.count + bs.reserved.count + jd.reserved.count + af.reserved.count,
                        percentage: ((dh.reserved.count + bs.reserved.count + jd.reserved.count + af.reserved.count) / (dh.total_units + bs.total_units + jd.total_units + af.total_units || 1)) * 100,
                        total_value: dh.reserved.total_value + bs.reserved.total_value + jd.reserved.total_value + af.reserved.total_value
                    },
                    contracted: {
                        count: dh.contracted.count + bs.contracted.count + jd.contracted.count + af.contracted.count,
                        percentage: ((dh.contracted.count + bs.contracted.count + jd.contracted.count + af.contracted.count) / (dh.total_units + bs.total_units + jd.total_units + af.total_units || 1)) * 100,
                        total_value: dh.contracted.total_value + bs.contracted.total_value + jd.contracted.total_value + af.contracted.total_value
                    },
                    beneficiary: {
                        count: (dh.beneficiary?.count || 0) + (bs.beneficiary?.count || 0) + (jd.beneficiary?.count || 0) + (af.beneficiary?.count || 0),
                        percentage: (((dh.beneficiary?.count || 0) + (bs.beneficiary?.count || 0) + (jd.beneficiary?.count || 0) + (af.beneficiary?.count || 0)) / (dh.total_units + bs.total_units + jd.total_units + af.total_units || 1)) * 100,
                        total_value: (dh.beneficiary?.total_value || 0) + (bs.beneficiary?.total_value || 0) + (jd.beneficiary?.total_value || 0) + (af.beneficiary?.total_value || 0)
                    },
                    non_beneficiary: {
                        count: (dh.non_beneficiary?.count || 0) + (bs.non_beneficiary?.count || 0) + (jd.non_beneficiary?.count || 0) + (af.non_beneficiary?.count || 0),
                        percentage: (((dh.non_beneficiary?.count || 0) + (bs.non_beneficiary?.count || 0) + (jd.non_beneficiary?.count || 0) + (af.non_beneficiary?.count || 0)) / (dh.total_units + bs.total_units + jd.total_units + af.total_units || 1)) * 100,
                        total_value: (dh.non_beneficiary?.total_value || 0) + (bs.non_beneficiary?.total_value || 0) + (jd.non_beneficiary?.total_value || 0) + (af.non_beneficiary?.total_value || 0)
                    },
                    overall_value: dh.overall_value + bs.overall_value + jd.overall_value + af.overall_value,
                    overall_progress_percentage: ((dh.reserved.count + bs.reserved.count + jd.reserved.count + af.reserved.count + dh.contracted.count + bs.contracted.count + jd.contracted.count + af.contracted.count) /
                        (dh.total_units + bs.total_units + jd.total_units + af.total_units || 1)) * 100
                };

            } else {
                if (site === 'jaddah') {
                        siteName = '{{ __('comparison_report.jaddah') }}';
                    siteColor = 'warning';
                } else if (site === 'alfursan') {
                        siteName = '{{ __('comparison_report.alfursan') }}';
                    siteColor = 'teal';
                } else {
                    siteName = site === 'dhahran' ? '{{ __('comparison_report.dhahran') }}' : '{{ __('comparison_report.bashaer') }}';
                    siteColor = site === 'dhahran' ? 'primary' : 'success';
                }
                summary = getSiteData(site);
            }

            document.getElementById('villa-summary-title').innerText = siteName;
            updateVillaSummaryHTML(summary, site, siteColor);
            updateActivityCharts(site);
            updateAppointments(site);
            updateCallsSection(site, siteName);
        }
       function updateVillaSummaryHTML(summary, site, siteColor) {
    document.getElementById('villa-summary-data').innerHTML = `
    <!-- إجمالي الوحدات -->
    <div class="col-xl-4 col-md-6 col-12 mb-4">
        <div class="stat-card total-units">
            <div class="card-header">
                <i class="fas fa-home"></i>
                <h3>${summary.total_units}</h3>
            </div>
            <div class="card-body">
                <p class="card-title">{{ __('comparison_report.total_units') }}</p>
                <p class="card-value"><i class="fas fa-dollar-sign"></i> ${numberFormat(summary.total_price)}</p>
                <div class="progress-bar" style="--width: 100%; --color: var(--${siteColor})"></div>
            </div>
        </div>
    </div>

    <!-- المتاحة -->
    <div class="col-xl-4 col-md-6 col-12 mb-4">
        <div class="stat-card available">
            <div class="card-header">
                <i class="fas fa-check-circle"></i>
                <h3>${summary.available.count} <span>(${summary.available.percentage.toFixed(2)}%)</span></h3>
            </div>
            <div class="card-body">
                <p class="card-title">{{ __('comparison_report.available') }}</p>
                <p class="card-value"><i class="fas fa-dollar-sign"></i> ${numberFormat(summary.available.total_value)}</p>
                <div class="progress-bar" style="--width: ${summary.available.percentage}%; --color: var(--info)"></div>
            </div>
        </div>
    </div>

    <!-- المحجوبة -->
    <div class="col-xl-4 col-md-6 col-12 mb-4">
        <div class="stat-card blocked">
            <div class="card-header">
                <i class="fas fa-lock"></i>
                <h3>${summary.blocked.count} <span>(${summary.blocked.percentage.toFixed(2)}%)</span></h3>
            </div>
            <div class="card-body">
                <p class="card-title">{{ __('comparison_report.blocked') }}</p>
                <p class="card-value"><i class="fas fa-dollar-sign"></i> ${numberFormat(summary.blocked.total_value)}</p>
                <div class="progress-bar" style="--width: ${summary.blocked.percentage}%; --color: var(--warning)"></div>
            </div>
        </div>
    </div>

    <!-- المحجوزة -->
    <div class="col-xl-4 col-md-6 col-12 mb-4">
        <div class="stat-card reserved">
            <div class="card-header">
                <i class="fas fa-handshake"></i>
                <h3>${summary.reserved.count} <span>(${summary.reserved.percentage.toFixed(2)}%)</span></h3>
            </div>
            <div class="card-body">
                <p class="card-title">{{ __('comparison_report.reserved') }}</p>
                <p class="card-value"><i class="fas fa-dollar-sign"></i> ${numberFormat(summary.reserved.total_value)}</p>
                <div class="progress-bar" style="--width: ${summary.reserved.percentage}%; --color: var(--danger)"></div>
            </div>
        </div>
    </div>

    <!-- المتعاقد عليها -->
    <div class="col-xl-4 col-md-6 col-12 mb-4">
        <div class="stat-card contracted">
            <div class="card-header">
                <i class="fas fa-file-contract"></i>
                <h3>${summary.contracted.count} <span>(${summary.contracted.percentage.toFixed(2)}%)</span></h3>
            </div>
            <div class="card-body">
                <p class="card-title">{{ __('comparison_report.contracted') }}</p>
                <p class="card-value"><i class="fas fa-dollar-sign"></i> ${numberFormat(summary.contracted.total_value)}</p>
                <div class="progress-bar" style="--width: ${summary.contracted.percentage}%; --color: var(--success)"></div>
            </div>
        </div>
    </div>

    <!-- الإجمالي المحجوز والمتعاقد -->
    <div class="col-xl-4 col-md-6 col-12 mb-4">
        <div class="stat-card overall">
            <div class="card-header">
                <i class="fas fa-dollar-sign"></i>
                <h3>${summary.reserved.count + summary.contracted.count}</h3>
            </div>
            <div class="card-body">
                <p class="card-title">{{ __('comparison_report.overall_reserved_contracted') }}</p>
                <p class="card-value"><i class="fas fa-dollar-sign"></i> ${numberFormat(summary.overall_value)}</p>
                <div class="progress-bar" style="--width: ${summary.overall_progress_percentage}%; --color: var(--purple)"></div>
            </div>
        </div>
    </div>

    <!-- مستفيد -->
    <div class="col-xl-4 col-md-6 col-12 mb-4">
        <div class="stat-card beneficiary">
            <div class="card-header">
                <i class="fas fa-user-check"></i>
                <h3>${summary.beneficiary?.count || 0} <span>(${summary.beneficiary?.percentage?.toFixed(2) || 0}%)</span></h3>
            </div>
            <div class="card-body">
                <p class="card-title">{{ __('comparison_report.beneficiary') }}</p>
                <!-- إزالة الدولار وإظهار النسبة فقط -->
                <p class="card-value">${summary.beneficiary?.percentage?.toFixed(2) || 0}% من إجمالي الوحدات</p>
                <div class="progress-bar" style="--width: ${summary.beneficiary?.percentage || 0}%; --color: var(--teal)"></div>
            </div>
        </div>
    </div>

    <!-- غير مستفيد -->
    <div class="col-xl-4 col-md-6 col-12 mb-4">
        <div class="stat-card non-beneficiary">
            <div class="card-header">
                <i class="fas fa-user-times"></i>
                <h3>${summary.non_beneficiary?.count || 0} <span>(${summary.non_beneficiary?.percentage?.toFixed(2) || 0}%)</span></h3>
            </div>
            <div class="card-body">
                <p class="card-title">{{ __('comparison_report.non_beneficiary') }}</p>

                <p class="card-value">${summary.non_beneficiary?.percentage?.toFixed(2) || 0}% من إجمالي الوحدات</p>
                <div class="progress-bar" style="--width: ${summary.non_beneficiary?.percentage || 0}%; --color: var(--secondary)"></div>
            </div>
        </div>
    </div>


    <!-- تقدم المشروع -->
    <div class="col-xl-4 col-md-6 col-12 mb-4 mx-auto">
        <div class="stat-card progress">
            <div class="card-header">
                <i class="fas fa-chart-line"></i>
                <h3>
                    ${site === 'all' ? '{{ $progressData['all'] }}%' :
                     site === 'dhahran' ? '{{ $progressData['dhahran'] }}%' :
                     site === 'bashaer' ? '{{ $progressData['bashaer'] }}%' :
                     site === 'jaddah' ? '{{ $progressData['jaddah'] }}%' :
                     '{{ $progressData['alfursan'] }}%'}
                </h3>
            </div>
            <div class="card-body">
                <p class="card-title">{{ __('comparison_report.project_progress') }}</p>
                <div class="progress-bar"
                     style="--width: ${site === 'all' ? '{{ $progressData['all'] }}%' :
                              site === 'dhahran' ? '{{ $progressData['dhahran'] }}%' :
                              site === 'bashaer' ? '{{ $progressData['bashaer'] }}%' :
                              site === 'jaddah' ? '{{ $progressData['jaddah'] }}%' :
                              '{{ $progressData['alfursan'] }}%' };
                            --color: var(--primary)">
                </div>
            </div>
        </div>
    </div>
`;
}

            //     function updateVillaSummaryHTML(summary, site, siteColor) {
            //         document.getElementById('villa-summary-data').innerHTML = `
        //     <!-- إجمالي الوحدات -->
        //     <div class="col-xl-4 col-md-6 col-12 mb-4">
        //         <div class="stat-card total-units">
        //             <div class="card-header">
        //                 <i class="fas fa-home"></i>
        //                 <h3>${summary.total_units}</h3>
        //             </div>
        //             <div class="card-body">
        //                 <p class="card-title">{{ __('comparison_report.total_units') }}</p>
        //                 <p class="card-value"><i class="fas fa-dollar-sign"></i> ${numberFormat(summary.total_price)}</p>
        //                 <div class="progress-bar" style="--width: 100%; --color: var(--${siteColor})"></div>
        //             </div>
        //         </div>
        //     </div>

        //     <!-- المتاحة -->
        //     <div class="col-xl-4 col-md-6 col-12 mb-4">
        //         <div class="stat-card available">
        //             <div class="card-header">
        //                 <i class="fas fa-check-circle"></i>
        //                 <h3>${summary.available.count} <span>(${summary.available.percentage.toFixed(2)}%)</span></h3>
        //             </div>
        //             <div class="card-body">
        //                 <p class="card-title">{{ __('comparison_report.available') }}</p>
        //                 <p class="card-value"><i class="fas fa-dollar-sign"></i> ${numberFormat(summary.available.total_value)}</p>
        //                 <div class="progress-bar" style="--width: ${summary.available.percentage}%; --color: var(--info)"></div>
        //             </div>
        //         </div>
        //     </div>

        //     <!-- المحجوبة -->
        //     <div class="col-xl-4 col-md-6 col-12 mb-4">
        //         <div class="stat-card blocked">
        //             <div class="card-header">
        //                 <i class="fas fa-lock"></i>
        //                 <h3>${summary.blocked.count} <span>(${summary.blocked.percentage.toFixed(2)}%)</span></h3>
        //             </div>
        //             <div class="card-body">
        //                 <p class="card-title">{{ __('comparison_report.blocked') }}</p>
        //                 <p class="card-value"><i class="fas fa-dollar-sign"></i> ${numberFormat(summary.blocked.total_value)}</p>
        //                 <div class="progress-bar" style="--width: ${summary.blocked.percentage}%; --color: var(--warning)"></div>
        //             </div>
        //         </div>
        //     </div>

        //     <!-- المحجوزة -->
        //     <div class="col-xl-4 col-md-6 col-12 mb-4">
        //         <div class="stat-card reserved">
        //             <div class="card-header">
        //                 <i class="fas fa-handshake"></i>
        //                 <h3>${summary.reserved.count} <span>(${summary.reserved.percentage.toFixed(2)}%)</span></h3>
        //             </div>
        //             <div class="card-body">
        //                 <p class="card-title">{{ __('comparison_report.reserved') }}</p>
        //                 <p class="card-value"><i class="fas fa-dollar-sign"></i> ${numberFormat(summary.reserved.total_value)}</p>
        //                 <div class="progress-bar" style="--width: ${summary.reserved.percentage}%; --color: var(--danger)"></div>
        //             </div>
        //         </div>
        //     </div>

        //     <!-- المتعاقد عليها -->
        //     <div class="col-xl-4 col-md-6 col-12 mb-4">
        //         <div class="stat-card contracted">
        //             <div class="card-header">
        //                 <i class="fas fa-file-contract"></i>
        //                 <h3>${summary.contracted.count} <span>(${summary.contracted.percentage.toFixed(2)}%)</span></h3>
        //             </div>
        //             <div class="card-body">
        //                 <p class="card-title">{{ __('comparison_report.contracted') }}</p>
        //                 <p class="card-value"><i class="fas fa-dollar-sign"></i> ${numberFormat(summary.contracted.total_value)}</p>
        //                 <div class="progress-bar" style="--width: ${summary.contracted.percentage}%; --color: var(--success)"></div>
        //             </div>
        //         </div>
        //     </div>

        //     <!-- الإجمالي المحجوز والمتعاقد -->
        //     <div class="col-xl-4 col-md-6 col-12 mb-4">
        //         <div class="stat-card overall">
        //             <div class="card-header">
        //                 <i class="fas fa-dollar-sign"></i>
        //                 <h3>${summary.reserved.count + summary.contracted.count}</h3>
        //             </div>
        //             <div class="card-body">
        //                 <p class="card-title">{{ __('comparison_report.overall_reserved_contracted') }}</p>
        //                 <p class="card-value"><i class="fas fa-dollar-sign"></i> ${numberFormat(summary.overall_value)}</p>
        //                 <div class="progress-bar" style="--width: ${summary.overall_progress_percentage}%; --color: var(--purple)"></div>
        //             </div>
        //         </div>
        //     </div>

        //     <!-- تقدم المشروع -->
        //     <div class="col-xl-4 col-md-6 col-12 mb-4 mx-auto">
        //         <div class="stat-card progress">
        //             <div class="card-header">
        //                 <i class="fas fa-chart-line"></i>
        //                 <h3>
        //                     ${site === 'all' ? '{{ $progressData['all'] }}%' :
        //                      site === 'dhahran' ? '{{ $progressData['dhahran'] }}%' :
        //                      site === 'bashaer' ? '{{ $progressData['bashaer'] }}%' :
        //                      site === 'jaddah' ? '{{ $progressData['jaddah'] }}%' :
        //                      '{{ $progressData['alfursan'] }}%'}
        //                 </h3>
        //             </div>
        //             <div class="card-body">
        //                 <p class="card-title">{{ __('comparison_report.project_progress') }}</p>
        //                 <div class="progress-bar"
        //                      style="--width: ${site === 'all' ? '{{ $progressData['all'] }}%' :
        //                               site === 'dhahran' ? '{{ $progressData['dhahran'] }}%' :
        //                               site === 'bashaer' ? '{{ $progressData['bashaer'] }}%' :
        //                               site === 'jaddah' ? '{{ $progressData['jaddah'] }}%' :
        //                               '{{ $progressData['alfursan'] }}%' };
        //                             --color: var(--primary)">
        //                 </div>
        //             </div>
        //         </div>
        //     </div>
        // `;
        //     }

            function updateCallsSection(site, siteName) {
                if (site === 'all') {
                    const dh = callLogs.totals['dhahran'] || { added: 0, ended: 0 };
                    const bs = callLogs.totals['bashaer'] || { added: 0, ended: 0 };
                    const jd = callLogs.totals['jaddah'] || { added: 0, ended: 0 };
                    const af = callLogs.totals['alfursan'] || { added: 0, ended: 0 };

                    document.getElementById('calls-section').innerHTML = `
                ${createCallCard('primary', dh.added, '{{ __('comparison_report.added_calls_dhahran') }}', 'icon-call-in')}
                ${createCallCard('info', bs.added, '{{ __('comparison_report.added_calls_bashaer') }}', 'icon-call-in')}
                ${createCallCard('warning', jd.added, '{{ __('comparison_report.added_calls_jaddah') }}', 'icon-call-in')}
                ${createCallCard('teal', af.added, '{{ __('comparison_report.added_calls_alfursan') }}', 'icon-call-in')}
                ${createCallCard('danger', dh.ended, '{{ __('comparison_report.ended_calls_dhahran') }}', 'icon-call-end')}
                ${createCallCard('success', bs.ended, '{{ __('comparison_report.ended_calls_bashaer') }}', 'icon-call-end')}
                ${createCallCard('secondary', jd.ended, '{{ __('comparison_report.ended_calls_jaddah') }}', 'icon-call-end')}
                ${createCallCard('dark', af.ended, '{{ __('comparison_report.ended_calls_alfursan') }}', 'icon-call-end')}
            `;
                } else {
            const totals = callLogs.totals[site] || { added: 0, ended: 0 };
            document.getElementById('calls-section').innerHTML = `
            <div class="col-xl-6 col-md-6 col-12 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="text-primary mb-0">${totals.added}</h3>
                                <small class="text-muted">{{ __('comparison_report.added_calls') }} ${siteName}</small>
                            </div>
                            <div><i class="icon-call-in primary font-large-2 float-right"></i></div>
                        </div>
                        <div class="progress mt-2" style="height: 5px;">
                            <div class="progress-bar bg-primary" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-md-6 col-12 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="text-danger mb-0">${totals.ended}</h3>
                                <small class="text-muted">{{ __('comparison_report.ended_calls') }} ${siteName}</small>
                            </div>
                            <div><i class="fas fa-phone-slash fa-2x text-danger"></i></div>
                        </div>
                        <div class="progress mt-2" style="height: 5px;">
                            <div class="progress-bar bg-danger" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        }
    }



        function createCallCard(color, count, title, icon) {
            return `
                <div class="col-xl-3 col-lg-6 col-12 mb-3">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="media">
                                    <div class="media-body text-left w-100">
                                        <h3 class="${color}">${count}</h3>
                                        <span>${title}</span>
                                    </div>
                                    <div class="media-right media-middle">
                                        <i class="${icon} ${color} font-large-2 float-right"></i>
                                    </div>
                                </div>
                                <div class="progress progress-sm mt-1 mb-0">
                                    <div class="progress-bar bg-${color}" role="progressbar" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
        }

        function updateActivityCharts(site) {
            const labels = Object.keys(leadsTimeline);

            let datasets = [];

            if (site === 'all') {
                datasets = [
                    {
                        label: "{{ __('comparison_report.dhahran_added') }}",
                        data: labels.map(date => leadsTimeline[date]?.['dhahran_added'] || 0),
                        borderColor: '#FF6384',
                        tension: 0.4,
                        borderWidth: 2.5, // زيادة سماكة الخط
                        pointRadius: 3    // زيادة حجم النقاط
                    },
                    {
                        label: "{{ __('comparison_report.bashaer_added') }}",
                        data: labels.map(date => leadsTimeline[date]?.['bashaer_added'] || 0),
                        borderColor: '#4BC0C0',
                        tension: 0.4,
                        borderWidth: 2.5,
                        pointRadius: 3
                    },
                    {
                        label: "{{ __('comparison_report.jaddah_added') }}",
                        data: labels.map(date => leadsTimeline[date]?.['jaddah_added'] || 0),
                        borderColor: '#9966FF',
                        tension: 0.4,
                        borderWidth: 2.5,
                        pointRadius: 3
                    },
                    {
                        label: "{{ __('comparison_report.alfursan_added') }}",
                        data: labels.map(date => leadsTimeline[date]?.['alfursan_added'] || 0),
                        borderColor: '#20c997',
                        tension: 0.4,
                        borderWidth: 2.5,
                        pointRadius: 3
                    },
                    {
                        label: "{{ __('comparison_report.dhahran_edited') }}",
                        data: labels.map(date => leadsTimeline[date]?.['dhahran_edited'] || 0),
                        borderColor: '#FFCD56',
                        tension: 0.4,
                        borderWidth: 2.5,
                        pointRadius: 3
                    },
                    {
                        label: "{{ __('comparison_report.bashaer_edited') }}",
                        data: labels.map(date => leadsTimeline[date]?.['bashaer_edited'] || 0),
                        borderColor: '#36A2EB',
                        tension: 0.4,
                        borderWidth: 2.5,
                        pointRadius: 3
                    },
                    {
                        label: "{{ __('comparison_report.jaddah_edited') }}",
                        data: labels.map(date => leadsTimeline[date]?.['jaddah_edited'] || 0),
                        borderColor: '#FF9F40',
                        tension: 0.4,
                        borderWidth: 2.5,
                        pointRadius: 3
                    },
                    {
                        label: "{{ __('comparison_report.alfursan_edited') }}",
                        data: labels.map(date => leadsTimeline[date]?.['alfursan_edited'] || 0),
                        borderColor: '#6f42c1',
                        tension: 0.4,
                        borderWidth: 2.5,
                        pointRadius: 3
                    }
                ];
            } else {
                datasets = [
                    {
                        label: "{{ __('comparison_report.added') }}",
                        data: labels.map(date => leadsTimeline[date]?.[`${site}_added`] || 0),
                        borderColor: '#4CAF50',
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4
                    },
                    {
                        label: "{{ __('comparison_report.edited') }}",
                        data: labels.map(date => leadsTimeline[date]?.[`${site}_edited`] || 0),
                        borderColor: '#2196F3',
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4
                    }
                ];
            }

            if (weeklyActivityChart) weeklyActivityChart.destroy();
            const weeklyActivityCtx = document.getElementById('weekly-activity-chart')?.getContext('2d');
            if (weeklyActivityCtx) {
                weeklyActivityChart = new Chart(weeklyActivityCtx, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    boxWidth: 12,
                                    padding: 8,
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.15)',
                                    lineWidth: 1,
                                    drawBorder: true,
                                    borderDash: [5, 5],
                                    drawTicks: true,
                                    // جعل خطوط الوايات تمتد عبر كامل العرض
                                    drawOnChartArea: true,
                                    drawTicks: true,
                                    tickLength: 0 // إزالة الخطوط الصغيرة على المحور
                                },
                                ticks: {
                                    font: {
                                        size: 10
                                    },
                                    padding: 8,
                                    stepSize: 1,
                                    // جعل الأرقام على المحور Y أكثر وضوحاً
                                    backdropColor: 'rgba(255, 255, 255, 0.8)',
                                    backdropPadding: 2
                                },
                                // إعدادات إضافية لتحسين المظهر
                                suggestedMin: 0,
                                suggestedMax: Math.max(...datasets.flatMap(d => d.data)) + 2
                            },
                            x: {
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.15)', // نفس لون المحور Y
                                    lineWidth: 1,
                                    drawBorder: true,
                                    borderDash: [5, 5],
                                    drawOnChartArea: true,
                                    drawTicks: true,
                                    tickLength: 0,
                                    // جعل خطوط الوايات تمتد عبر كامل الارتفاع
                                    offset: false
                                },
                                ticks: {
                                    font: {
                                        size: 9,
                                    },
                                    maxRotation: 45,
                                    padding: 5,
                                    backdropColor: 'rgba(255, 255, 255, 0.8)',
                                    backdropPadding: 2
                                }
                            }
                        },
                        elements: {
                            point: {
                                radius: 3,
                                hoverRadius: 6,
                                backgroundColor: '#ffffff',
                                borderWidth: 2
                            },
                            line: {
                                borderWidth: 2.5,
                                tension: 0.3
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        layout: {
                            padding: {
                                left: 15,  // زيادة المسافة من الجوانب
                                right: 15,
                                top: 15,
                                bottom: 15
                            }
                        }
                    }
                });
            }



            // مخطط الدائرة الكبير
            if (activityDivisionChart) activityDivisionChart.destroy();
            const activityDivisionCtx = document.getElementById('activity-division')?.getContext('2d');
            if (activityDivisionCtx) {
                const totalAdded = site === 'all' ? [
                    labels.reduce((a, b) => a + (leadsTimeline[b]?.['dhahran_added'] || 0), 0),
                    labels.reduce((a, b) => a + (leadsTimeline[b]?.['bashaer_added'] || 0), 0),
                    labels.reduce((a, b) => a + (leadsTimeline[b]?.['jaddah_added'] || 0), 0),
                    labels.reduce((a, b) => a + (leadsTimeline[b]?.['alfursan_added'] || 0), 0),
                ] : [labels.reduce((a, b) => a + (leadsTimeline[b]?.[`${site}_added`] || 0), 0)];

                const totalEdited = site === 'all' ? [
                    labels.reduce((a, b) => a + (leadsTimeline[b]?.['dhahran_edited'] || 0), 0),
                    labels.reduce((a, b) => a + (leadsTimeline[b]?.['bashaer_edited'] || 0), 0),
                    labels.reduce((a, b) => a + (leadsTimeline[b]?.['jaddah_edited'] || 0), 0),
                    labels.reduce((a, b) => a + (leadsTimeline[b]?.['alfursan_edited'] || 0), 0),
                ] : [labels.reduce((a, b) => a + (leadsTimeline[b]?.[`${site}_edited`] || 0), 0)];

                const doughnutData = site === 'all' ? {
                    labels: [
                        "{{ __('comparison_report.dhahran_added') }}",
                        "{{ __('comparison_report.bashaer_added') }}",
                        "{{ __('comparison_report.jaddah_added') }}",
                        "{{ __('comparison_report.alfursan_added') }}",
                        "{{ __('comparison_report.dhahran_edited') }}",
                        "{{ __('comparison_report.bashaer_edited') }}",
                        "{{ __('comparison_report.jaddah_edited') }}",
                        "{{ __('comparison_report.alfursan_edited') }}",
                    ],
                    datasets: [{
                        data: [...totalAdded, ...totalEdited],
                        backgroundColor: ['#FF6384', '#4BC0C0', '#9966FF', '#20c997', '#FFCD56', '#36A2EB', '#FF9F40', '#6f42c1']
                    }]
                } : {
                    labels: [
                        "{{ __('comparison_report.added') }}",
                        "{{ __('comparison_report.edited') }}"
                    ],
                    datasets: [{
                        data: [totalAdded[0], totalEdited[0]],
                        backgroundColor: ['#4CAF50', '#2196F3']
                    }]
                };

                activityDivisionChart = new Chart(activityDivisionCtx, {
                    type: 'doughnut',
                    data: doughnutData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    },
                                    padding: 15
                                }
                            }
                        },
                        cutout: '50%'
                    }
                });
            }
        }
        //
        function updateAppointments(site) {
    const timeline = appointments.timeline || [];

    let monthlyData = {};

    // تجميع البيانات حسب الشهر باللغة الإنجليزية
    timeline.forEach(item => {
        const date = new Date(item.date);
        const monthYear = date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long'
        });

        if (!monthlyData[monthYear]) {
            monthlyData[monthYear] = {
                dhahran: 0,
                bashaer: 0,
                jaddah: 0,
                alfursan: 0,
                total: 0
            };
        }

        monthlyData[monthYear].dhahran += item.dhahran || 0;
        monthlyData[monthYear].bashaer += item.bashaer || 0;
        monthlyData[monthYear].jaddah += item.jaddah || 0;
        monthlyData[monthYear].alfursan += item.alfursan || 0;
        monthlyData[monthYear].total += (item.dhahran || 0) + (item.bashaer || 0) + (item.jaddah || 0) + (item.alfursan || 0);
    });

    // تحويل الكائن إلى مصفوفة للعرض
    const monthlyArray = Object.keys(monthlyData).map(month => ({
        month: month,
        data: monthlyData[month]
    }));

    // ترتيب البيانات حسب التاريخ (من الأحدث إلى الأقدم)
    monthlyArray.sort((a, b) => {
        const dateA = new Date(a.month);
        const dateB = new Date(b.month);
        return dateB - dateA;
    });

    let labels = [];
    let values = [];

    if (site === 'all') {
        labels = monthlyArray.map(item => item.month);
        values = monthlyArray.map(item => item.data.total);
    } else {
        labels = monthlyArray.map(item => item.month);
        values = monthlyArray.map(item => item.data[site] || 0);
    }

    // تحديث مخطط الخط
    if (appointmentsChart) appointmentsChart.destroy();
    const appointmentsCtx = document.getElementById('appointments-chart')?.getContext('2d');
    if (appointmentsCtx) {
        appointmentsChart = new Chart(appointmentsCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: site === 'all' ? '{{ __('comparison_report.all') }}' :
                           (site === 'dhahran' ? '{{ __('comparison_report.dhahran') }}' :
                            site === 'bashaer' ? '{{ __('comparison_report.bashaer') }}' :
                            site === 'jaddah' ? '{{ __('comparison_report.jaddah') }}' :
                            '{{ __('comparison_report.alfursan') }}'),
                    data: values,
                    borderColor: '#00bcd4',
                    backgroundColor: 'rgba(0, 188, 212, 0.2)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // تحديث الجدول لعرض البيانات الشهرية بالإنجليزية
    let appointmentsHtml = '';
    monthlyArray.forEach(item => {
        const count = site === 'all' ?
            item.data.total :
            item.data[site];

        const progressColor = site === 'dhahran' ? 'bg-primary' :
                            site === 'bashaer' ? 'bg-success' :
                            site === 'jaddah' ? 'bg-warning' :
                            'bg-teal';

        appointmentsHtml += `
        <tr>
            <td>${item.month}</td>
            <td class="text-center font-small-2">
                ${count}
                <div class="progress progress-sm mt-1 mb-0">
                    <div class="progress-bar ${progressColor}" style="width: 100%"></div>
                </div>
            </td>
        </tr>
    `;
    });
    document.getElementById('appointments-data').innerHTML = appointmentsHtml;
}

        // DOM Ready
        document.addEventListener('DOMContentLoaded', function () {
            loadSiteData('all');
            document.getElementById('all-btn').addEventListener('click', function () {
                setActiveButton(this);
                loadSiteData('all');
            });
            document.getElementById('dhahran-btn').addEventListener('click', function () {
                setActiveButton(this);
                loadSiteData('dhahran');
            });
            document.getElementById('bashaer-btn').addEventListener('click', function () {
                setActiveButton(this);
                loadSiteData('bashaer');
            });
            document.getElementById('jaddah-btn').addEventListener('click', function () {
                setActiveButton(this);
                loadSiteData('jaddah');
            });
            document.getElementById('alfursan-btn').addEventListener('click', function () {
                setActiveButton(this);
                loadSiteData('alfursan');
            });
        });
    </script>
@endsection
