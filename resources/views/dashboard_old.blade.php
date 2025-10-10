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
    </style>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="site-selector text-center">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-outline-primary active" id="all-btn">
                                    <input type="radio" name="site" value="all" checked>
                                    {{ __('comparison_report.all') }}
                                </label>
                                <label class="btn btn-outline-primary" id="dhahran-btn">
                                    <input type="radio" name="site" value="dhahran">
                                    {{ __('comparison_report.dhahran') }}
                                </label>
                                <label class="btn btn-outline-primary" id="bashaer-btn">
                                    <input type="radio" name="site" value="bashaer">
                                    {{ __('comparison_report.bashaer') }}
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
            <div class="row match-height" id="activity-charts-section">
                <div class="col-xl-8 col-lg-12">
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
                                <canvas id="weekly-activity-chart" class="height-250"></canvas>
                                <ul class="list-inline text-center m-0" id="activity-chart-legend">
                                    <!-- Legend will be loaded here by JavaScript -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <canvas id="activity-division" class="height-250 echart-container"></canvas>
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
        const leadsTimeline = comparisonData.leads_timeline;
        const callLogs = comparisonData.call_logs;
        const appointments = comparisonData.appointments;

        // Format number
        function numberFormat(number) {
            return new Intl.NumberFormat().format(number);
        }

        // Change active button
        function setActiveButton(button) {
            document.querySelectorAll('.site-selector .btn').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
        }

        // Load full site data
        function loadSiteData(site) {
            let summary, siteName, siteColor;

            if (site === 'all') {
                siteName = '{{ __('comparison_report.all') }}';
                siteColor = 'dark';

                const dh = villaSummary['dhahran'];
                const bs = villaSummary['bashaer'];

                summary = {
                    total_units: dh.total_units + bs.total_units,
                    total_price: dh.total_price + bs.total_price,
                    available: {
                        count: dh.available.count + bs.available.count,
                        percentage: ((dh.available.count + bs.available.count) / (dh.total_units + bs.total_units)) *
                            100,
                        total_value: dh.available.total_value + bs.available.total_value
                    },
                    blocked: {
                        count: dh.blocked.count + bs.blocked.count,
                        percentage: ((dh.blocked.count + bs.blocked.count) / (dh.total_units + bs.total_units)) * 100,
                        total_value: dh.blocked.total_value + bs.blocked.total_value
                    },
                    reserved: {
                        count: dh.reserved.count + bs.reserved.count,
                        percentage: ((dh.reserved.count + bs.reserved.count) / (dh.total_units + bs.total_units)) * 100,
                        total_value: dh.reserved.total_value + bs.reserved.total_value
                    },
                    contracted: {
                        count: dh.contracted.count + bs.contracted.count,
                        percentage: ((dh.contracted.count + bs.contracted.count) / (dh.total_units + bs.total_units)) *
                            100,
                        total_value: dh.contracted.total_value + bs.contracted.total_value
                    },
                    overall_value: dh.overall_value + bs.overall_value,
                    overall_progress_percentage: site === 'all' ?
        ((dh.reserved.count + bs.reserved.count + dh.contracted.count + bs.contracted.count) /
         (dh.total_units + bs.total_units)) * 100 :
        ((site === 'dhahran' ? dh : bs).reserved.count +
         (site === 'dhahran' ? dh : bs).contracted.count) /
         (site === 'dhahran' ? dh : bs).total_units * 100
                };
            } else {
                siteName = site === 'dhahran' ? '{{ __('comparison_report.dhahran') }}' :
                    '{{ __('comparison_report.bashaer') }}';
                siteColor = site === 'dhahran' ? 'primary' : 'success';
                summary = villaSummary[site];
            }

            document.getElementById('villa-summary-title').innerText = siteName;

            document.getElementById('villa-summary-data').innerHTML = `
        <!-- إجمالي الوحدات -->
        <div class="col-xl-4 col-md-6 col-12 mb-3">
            <div class="card-box-dark">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-${siteColor} mb-0">${summary.total_units}</h3>
                            <small class="text-muted">{{ __('comparison_report.total_units') }}</small><br>
                            <small class="text-muted"><i class="fas fa-dollar-sign"></i> ${numberFormat(summary.total_price)}</small>
                        </div>
                        <div>
                            <i class="fas fa-home fa-2x text-${siteColor}"></i>
                        </div>
                    </div>
                    <div class="progress mt-2" style="height: 5px;">
                        <div class="progress-bar bg-${siteColor}" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- المتاحة -->
        <div class="col-xl-4 col-md-6 col-12 mb-3">
            <div class="card-box-dark">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-info mb-0">${summary.available.count} (${summary.available.percentage.toFixed(2)}%)</h3>
                            <small class="text-muted">{{ __('comparison_report.available') }}</small><br>
                            <small class="text-muted"><i class="fas fa-dollar-sign"></i> ${numberFormat(summary.available.total_value)}</small>
                        </div>
                        <div>
                            <i class="fas fa-check-circle fa-2x text-info"></i>
                        </div>
                    </div>
                    <div class="progress mt-2 position-relative" style="height: 5px;">
                        <div class="progress-bar bg-info" style="width: ${summary.available.percentage}%"></div>
                        <span class="position-absolute end-0 me-2 small text-muted" style="top: -20px;">
                            ${summary.available.percentage.toFixed(2)}%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- المحجوبة -->
        <div class="col-xl-4 col-md-6 col-12 mb-3">
            <div class="card-box-dark">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-warning mb-0">${summary.blocked.count} (${summary.blocked.percentage.toFixed(2)}%)</h3>
                            <small class="text-muted">{{ __('comparison_report.blocked') }}</small><br>
                            <small class="text-muted"><i class="fas fa-dollar-sign"></i> ${numberFormat(summary.blocked.total_value)}</small>
                        </div>
                        <div>
                            <i class="fas fa-lock fa-2x text-warning"></i>
                        </div>
                    </div>
                    <div class="progress mt-2 position-relative" style="height: 5px;">
                        <div class="progress-bar bg-warning" style="width: ${summary.blocked.percentage}%"></div>
                        <span class="position-absolute end-0 me-2 small text-muted" style="top: -20px;">
                            ${summary.blocked.percentage.toFixed(2)}%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- المحجوزة -->
        <div class="col-xl-4 col-md-6 col-12 mb-3">
            <div class="card-box-dark">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-danger mb-0">${summary.reserved.count} (${summary.reserved.percentage.toFixed(2)}%)</h3>
                            <small class="text-muted">{{ __('comparison_report.reserved') }}</small><br>
                            <small class="text-muted"><i class="fas fa-dollar-sign"></i> ${numberFormat(summary.reserved.total_value)}</small>
                        </div>
                        <div>
                            <i class="fas fa-handshake fa-2x text-danger"></i>
                        </div>
                    </div>
                    <div class="progress mt-2 position-relative" style="height: 5px;">
                        <div class="progress-bar bg-danger" style="width: ${summary.reserved.percentage}%"></div>
                        <span class="position-absolute end-0 me-2 small text-muted" style="top: -20px;">
                            ${summary.reserved.percentage.toFixed(2)}%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- المتعاقد عليها -->
        <div class="col-xl-4 col-md-6 col-12 mb-3">
            <div class="card-box-dark">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-success mb-0">${summary.contracted.count} (${summary.contracted.percentage.toFixed(2)}%)</h3>
                            <small class="text-muted">{{ __('comparison_report.contracted') }}</small><br>
                            <small class="text-muted"><i class="fas fa-dollar-sign"></i> ${numberFormat(summary.contracted.total_value)}</small>
                        </div>
                        <div>
                            <i class="fas fa-file-contract fa-2x text-success"></i>
                        </div>
                    </div>
                    <div class="progress mt-2 position-relative" style="height: 5px;">
                        <div class="progress-bar bg-success" style="width: ${summary.contracted.percentage}%"></div>
                        <span class="position-absolute end-0 me-2 small text-muted" style="top: -20px;">
                            ${summary.contracted.percentage.toFixed(2)}%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- الإجمالي المحجوز والمتعاقد -->
        <div class="col-xl-4 col-md-6 col-12 mb-3">
            <div class="card-box-dark">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-${siteColor} mb-0">${summary.reserved.count + summary.contracted.count}</h3>
                            <small class="text-muted">{{ __('comparison_report.overall_reserved_contracted') }}</small><br>
                            <small class="text-muted"><i class="fas fa-dollar-sign"></i> ${numberFormat(summary.overall_value)}</small>
                        </div>
                        <div>
                            <i class="fas fa-dollar-sign fa-2x text-purple"></i>
                        </div>
                    </div>
                    <div class="progress mt-2 position-relative" style="height: 5px;">
                        <div class="progress-bar bg-purple" style="width: ${summary.overall_progress_percentage}%"></div>
                        <span class="position-absolute end-0 me-2 small text-muted" style="top: -20px;">
                            ${summary.overall_progress_percentage.toFixed(2)}%
                        </span>
                    </div>
                </div>
            </div>
        </div>

<div class="col-xl-4 col-md-6 col-12 mb-3 position-relative mx-auto">
    <div class="card-box-dark">
        <div class="card-body text-center">
            <div class="d-flex justify-content-between align-items-start">
                <div class="text-start">
                    <h3 class="text-primary mb-0">
                        <span id="progress-value">
                            ${site === 'all' ?
                                '{{ $progressData['all'] }}%' :
                                site === 'dhahran' ?
                                '{{ $progressData['dhahran'] }}%' :
                                '{{ $progressData['bashaer'] }}%'}
                        </span>
                    </h3>
                    <small class="text-muted">{{ __('comparison_report.project_progress') }}</small>
                </div>
            </div>
            <div class="progress mt-2" style="height: 5px;">
                <div class="progress-bar bg-primary"
                     style="width: ${
                         site === 'all' ?
                             '{{ $progressData['all'] }}%' :
                             site === 'dhahran' ?
                             '{{ $progressData['dhahran'] }}%' :
                             '{{ $progressData['bashaer'] }}%'
                     }"></div>
            </div>
        </div>
    </div>
</div>`;




            // تحميل الأقسام الأخرى (الاتصالات - الأنشطة - المواعيد)
            updateActivityCharts(site);
            updateAppointments(site);

            if (site === 'all') {
                const dh = callLogs.totals['dhahran'];
                const bs = callLogs.totals['bashaer'];
                document.getElementById('calls-section').innerHTML = `
        ${createCallCard('primary', dh.added, '{{ __('comparison_report.added_calls_dhahran') }}', 'icon-call-in')}
        ${createCallCard('info', bs.added, '{{ __('comparison_report.added_calls_bashaer') }}', 'icon-call-in')}
        ${createCallCard('danger', dh.ended, '{{ __('comparison_report.ended_calls_dhahran') }}', 'icon-call-end')}
        ${createCallCard('success', bs.ended, '{{ __('comparison_report.ended_calls_bashaer') }}', 'icon-call-end')}
    `;
            } else {
                const totals = callLogs.totals[site];
                document.getElementById('calls-section').innerHTML = `
            <!-- Added & Ended Calls Cards -->
            <div class="col-xl-6 col-md-6 col-12 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="text-primary mb-0">${totals.added || 0}</h3>
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
                                <h3 class="text-danger mb-0">${totals.ended || 0}</h3>
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
                datasets = [{
                        label: "{{ __('comparison_report.dhahran_added') }}",
                        data: labels.map(date => leadsTimeline[date]['dhahran_added'] || 0),
                        borderColor: '#FF6384',
                        tension: 0.4
                    },
                    {
                        label: "{{ __('comparison_report.bashaer_added') }}",
                        data: labels.map(date => leadsTimeline[date]['bashaer_added'] || 0),
                        borderColor: '#4BC0C0',
                        tension: 0.4
                    },
                    {
                        label: "{{ __('comparison_report.dhahran_edited') }}",
                        data: labels.map(date => leadsTimeline[date]['dhahran_edited'] || 0),
                        borderColor: '#FFCD56',
                        tension: 0.4
                    },
                    {
                        label: "{{ __('comparison_report.bashaer_edited') }}",
                        data: labels.map(date => leadsTimeline[date]['bashaer_edited'] || 0),
                        borderColor: '#36A2EB',
                        tension: 0.4
                    }
                ];
            } else {
                datasets = [{
                        label: "{{ __('comparison_report.added') }}",
                        data: labels.map(date => leadsTimeline[date][`${site}_added`] || 0),
                        borderColor: '#4CAF50',
                        tension: 0.4
                    },
                    {
                        label: "{{ __('comparison_report.edited') }}",
                        data: labels.map(date => leadsTimeline[date][`${site}_edited`] || 0),
                        borderColor: '#2196F3',
                        tension: 0.4
                    }
                ];
            }

            if (weeklyActivityChart) weeklyActivityChart.destroy();
            weeklyActivityChart = new Chart(document.getElementById('weekly-activity-chart').getContext('2d'), {
                type: 'line',
                data: {
                    labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Doughnut chart for summary
            if (activityDivisionChart) activityDivisionChart.destroy();
            const totalAdded = site === 'all' ?
                [
                    labels.reduce((a, b, i) => a + (leadsTimeline[b]['dhahran_added'] || 0), 0),
                    labels.reduce((a, b, i) => a + (leadsTimeline[b]['bashaer_added'] || 0), 0)
                ] :
                [labels.reduce((a, b) => a + (leadsTimeline[b][`${site}_added`] || 0), 0)];

            const totalEdited = site === 'all' ?
                [
                    labels.reduce((a, b, i) => a + (leadsTimeline[b]['dhahran_edited'] || 0), 0),
                    labels.reduce((a, b, i) => a + (leadsTimeline[b]['bashaer_edited'] || 0), 0)
                ] :
                [labels.reduce((a, b) => a + (leadsTimeline[b][`${site}_edited`] || 0), 0)];

            const doughnutData = site === 'all' ?
                {
                    labels: [
                        "{{ __('comparison_report.dhahran_added') }}",
                        "{{ __('comparison_report.bashaer_added') }}",
                        "{{ __('comparison_report.dhahran_edited') }}",
                        "{{ __('comparison_report.bashaer_edited') }}"
                    ],
                    datasets: [{
                        data: [...totalAdded, ...totalEdited],
                        backgroundColor: ['#FF6384', '#4BC0C0', '#FFCD56', '#36A2EB']
                    }]
                } :
                {
                    labels: [
                        "{{ __('comparison_report.added') }}",
                        "{{ __('comparison_report.edited') }}"
                    ],
                    datasets: [{
                        data: [totalAdded[0], totalEdited[0]],
                        backgroundColor: ['#4CAF50', '#2196F3']
                    }]
                };

            activityDivisionChart = new Chart(document.getElementById('activity-division').getContext('2d'), {
                type: 'doughnut',
                data: doughnutData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }



        function updateAppointments(site) {
            const timeline = appointments.timeline;

            let labels = [];
            let values = [];

            if (site === 'all') {
                labels = timeline.map(item => item.date);
                values = timeline.map(item => (item.dhahran || 0) + (item.bashaer || 0));
            } else {
                labels = timeline.map(item => item.date);
                values = timeline.map(item => item[site] || 0);
            }

            if (appointmentsChart) appointmentsChart.destroy();
            appointmentsChart = new Chart(document.getElementById('appointments-chart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: site === 'all' ? '{{ __('comparison_report.all') }}' : (site === 'dhahran' ?
                            '{{ __('comparison_report.dhahran') }}' :
                            '{{ __('comparison_report.bashaer') }}'),
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

            // جدول المواعيد
            let appointmentsHtml = '';
            timeline.forEach(item => {
                const count = site === 'all' ? (item.dhahran + item.bashaer) : item[site];
                appointmentsHtml += `
            <tr>
                <td>${item.date}</td>
                <td class="text-center font-small-2">
                    ${count}
                    <div class="progress progress-sm mt-1 mb-0">
                        <div class="progress-bar ${site === 'dhahran' ? 'bg-primary' : site === 'bashaer' ? 'bg-success' : 'bg-dark'}" style="width: 100%"></div>
                    </div>
                </td>
            </tr>
        `;
            });
            document.getElementById('appointments-data').innerHTML = appointmentsHtml;
        }


        // DOM Ready
        document.addEventListener('DOMContentLoaded', function() {
            loadSiteData('all');
            document.getElementById('all-btn').addEventListener('click', function() {
                setActiveButton(this);
                loadSiteData('all');
            });
            document.getElementById('dhahran-btn').addEventListener('click', function() {
                setActiveButton(this);
                loadSiteData('dhahran');
            });
            document.getElementById('bashaer-btn').addEventListener('click', function() {
                setActiveButton(this);
                loadSiteData('bashaer');
            });
        });
    </script>




    {{-- @push('scripts')
        <script src="{{ asset('app-assets/js/scripts/pages/dashboard-fitness.js') }}" type="text/javascript"></script>
    @endpush --}}
@endsection
