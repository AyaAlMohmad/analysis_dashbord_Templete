@extends('layouts.app')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-4 bg-white shadow-sm rounded-lg">
                    <h1 class="text-2xl font-bold text-gray-800 text-center">
                        {{ __('comparison_report.dashboard_comparison') }}</h1>
                </div>


                <!-- Leads Timeline Chart -->
                <div class="mb-5">
                    <h1 class="text-2xl font-bold text-center text-gray-800">{{ __('comparison_report.leads_timeline') }}
                    </h1>
                    <canvas id="leadsTimelineChart" style="max-width: 700px; height: 400px; margin: 0 auto;"></canvas>
                </div>

                <!-- Leads Status Chart -->
                <div class="mb-5">
                    <h1 class="text-2xl font-bold text-center text-gray-800">{{ __('comparison_report.leads_status') }}</h1>
                    <canvas id="leadsStatusChart" style="max-width: 700px; height: 400px; margin: 0 auto;"></canvas>
                </div>

                <!-- Appointments Total Chart -->
                <div class="mb-5">
                    <h1 class="text-2xl font-bold text-center text-gray-800">
                        {{ __('comparison_report.appointments_total') }}</h1>
                    <canvas id="appointmentsChart" style="max-width: 200; height: 200; margin: 0 auto;"></canvas>
                </div>

                <!-- Call Logs Chart -->
                <div class="mb-5">
                    <h1 class="text-2xl font-bold text-center text-gray-800">{{ __('comparison_report.call_logs_summary') }}
                    </h1>
                    <canvas id="callLogsChart" style="max-width: 700px; height: 400px; margin: 0 auto;"></canvas>
                </div>

                <!-- Items Status Chart -->
                <div class="mb-5">
                    <h1 class="text-2xl font-bold text-center text-gray-800">{{ __('comparison_report.items_status') }}</h1>
                    <canvas id="itemsChart" style="max-width: 700px; height: 400px; margin: 0 auto;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const comparisonData = @json($comparisonData);

        // Leads Timeline
        const leadsTimelineLabels = Object.keys(comparisonData.leads_timeline);
        const dhahranTimelineData = leadsTimelineLabels.map(d => comparisonData.leads_timeline[d].dhahran_added);
        const bashaerTimelineData = leadsTimelineLabels.map(d => comparisonData.leads_timeline[d].bashaer_added);
        const jaddahTimelineData = leadsTimelineLabels.map(d => comparisonData.leads_timeline[d].jaddah_added);
        const alfursanTimelineData = leadsTimelineLabels.map(d => comparisonData.leads_timeline[d].alfursan_added);
        new Chart(document.getElementById('leadsTimelineChart'), {
            type: 'line',
            data: {
                labels: leadsTimelineLabels,
                datasets: [{
                        label: "{{ __('comparison_report.dhahran') }}",
                        data: dhahranTimelineData,
                        borderColor: '#00262f',
                        fill: false
                    },
                    {
                        label: "{{ __('comparison_report.bashaer') }}",
                        data: bashaerTimelineData,
                        borderColor: '#543829',
                        fill: false
                    },
                    {
                        label: "{{ __('comparison_report.jaddah') }}",
                        data: jaddahTimelineData,
                        borderColor: '#1a472a',
                        fill: false
                    },
                    {
                        label: "{{ __('comparison_report.alfursan') }}",
                        data: alfursanTimelineData,
                        borderColor: '#37160d;',
                        fill: false
                    }
                ]
            }
        });

        // Leads Status
        const statusLabels = comparisonData.leads_status.map(s => s.status);
        const statusDhahran = comparisonData.leads_status.map(s => s.dhahran);
        const statusBashaer = comparisonData.leads_status.map(s => s.bashaer);
        const statusJaddah = comparisonData.leads_status.map(s => s.jaddah);
        const statusAlfursan = comparisonData.leads_status.map(s => s.alfursan);

        new Chart(document.getElementById('leadsStatusChart'), {
            type: 'bar',
            data: {
                labels: statusLabels,
                datasets: [{
                        label: "{{ __('comparison_report.dhahran') }}",
                        data: statusDhahran,
                        backgroundColor: '#00262f'
                    },
                    {
                        label: "{{ __('comparison_report.bashaer') }}",
                        data: statusBashaer,
                        backgroundColor: '#543829'
                    },
                    {
                        label: "{{ __('comparison_report.jaddah') }}",
                        data: statusJaddah,
                        backgroundColor: '#1a472a'
                    },
                    {
                        label: "{{ __('comparison_report.alfursan') }}",
                        data: statusAlfursan,
                        backgroundColor: '#37160d'
                    }
                ]
            }
        });

        // Appointments
        new Chart(document.getElementById('appointmentsChart'), {
            type: 'pie',
            data: {
                labels: ["{{ __('comparison_report.dhahran') }}", "{{ __('comparison_report.bashaer') }}", "{{ __('comparison_report.jaddah') }}", "{{ __('comparison_report.alfursan') }}"],
                datasets: [{
                    data: [
                        comparisonData.appointments.total_dhahran,
                        comparisonData.appointments.total_bashaer,
                        comparisonData.appointments.total_jaddah,
                        comparisonData.appointments.total_alfursan,
                    ],
                    backgroundColor: ['#00262f', '#543829', '#1a472a', '#37160d']
                }]
            },
            options: {
                responsive: false,
                maintainAspectRatio: false,
                width: 50,
                height: 50
            }
        });


        // Call Logs
        new Chart(document.getElementById('callLogsChart'), {
            type: 'bar',
            data: {
                labels: [
                    "{{ __('comparison_report.added') }}",
                    "{{ __('comparison_report.started') }}",
                    "{{ __('comparison_report.ended') }}"
                ],
                datasets: [{
                        label: "{{ __('comparison_report.dhahran') }}",
                        data: Object.values(comparisonData.call_logs.totals.dhahran),
                        backgroundColor: '#00262f'
                    },
                    {
                        label: "{{ __('comparison_report.bashaer') }}",
                        data: Object.values(comparisonData.call_logs.totals.bashaer),
                        backgroundColor: '#543829'
                    },
                    {
                        label: "{{ __('comparison_report.jaddah') }}",
                        data: Object.values(comparisonData.call_logs.totals.jaddah),
                        backgroundColor: '#1a472a'
                    },
                    {
                        label: "{{ __('comparison_report.alfursan') }}",
                        data: Object.values(comparisonData.call_logs.totals.alfursan),
                        backgroundColor: '#37160d'
                    }
                ]
            }
        });

        // Items
        new Chart(document.getElementById('itemsChart'), {
            type: 'bar',
            data: {
                labels: [
                    "{{ __('comparison_report.available') }}",
                    "{{ __('comparison_report.reserved') }}",
                    "{{ __('comparison_report.blocked') }}",
                    "{{ __('comparison_report.contacted') }}"
                ],

                datasets: [{
                        label: "{{ __('comparison_report.dhahran') }}",
                        data: [
                            comparisonData.items.available.dhahran,
                            comparisonData.items.reserved.dhahran,
                            comparisonData.items.blocked.dhahran,
                            comparisonData.items.contacted.dhahran,
                        ],
                        backgroundColor: '#00262f'
                    },
                    {
                        label: "{{ __('comparison_report.bashaer') }}",
                        data: [
                            comparisonData.items.available.bashaer,
                            comparisonData.items.reserved.bashaer,
                            comparisonData.items.blocked.bashaer,
                            comparisonData.items.contacted.bashaer,
                        ],
                        backgroundColor: '#543829'
                    },
                    {
                        label: "{{ __('comparison_report.jaddah') }}",
                        data: [
                            comparisonData.items.available.jaddah,
                            comparisonData.items.reserved.jaddah,
                            comparisonData.items.blocked.jaddah,
                            comparisonData.items.contacted.jaddah,
                        ],
                        backgroundColor: '#1a472a'
                    },
                    {
                        label: "{{ __('comparison_report.alfursan') }}",
                        data: [
                            comparisonData.items.available.alfursan,
                            comparisonData.items.reserved.alfursan,
                            comparisonData.items.blocked.alfursan,
                            comparisonData.items.contacted.alfursan,
                        ],
                        backgroundColor: '#37160d'
                    }
                ]
            }
        });
    </script>
@endsection
