@extends('layouts.app')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-4 bg-white shadow-sm rounded-lg">
                    <h1 class="text-2xl font-bold text-gray-800 text-center">CRM Dashboard Comparison</h1>
                </div>
    
    
                <!-- Leads Timeline Chart -->
                <div class="mb-5">
                    <h1 class="text-2xl font-bold text-center text-gray-800">Leads Timeline</h1>
                    <canvas id="leadsTimelineChart" style="max-width: 700px; height: 400px; margin: 0 auto;"></canvas>
                </div>
    
                <!-- Leads Status Chart -->
                <div class="mb-5">
                    <h1 class="text-2xl font-bold text-center text-gray-800">Leads Status</h1>
                    <canvas id="leadsStatusChart" style="max-width: 700px; height: 400px; margin: 0 auto;"></canvas>
                </div>
    
                <!-- Appointments Total Chart -->
                <div class="mb-5">
                    <h1 class="text-2xl font-bold text-center text-gray-800">Appointments Total</h1>
                    <canvas id="appointmentsChart" style="max-width: 200; height: 200; margin: 0 auto;"></canvas>
                </div>
    
                <!-- Call Logs Chart -->
                <div class="mb-5">
                    <h1 class="text-2xl font-bold text-center text-gray-800">Call Logs Summary</h1>
                    <canvas id="callLogsChart" style="max-width: 700px; height: 400px; margin: 0 auto;"></canvas>
                </div>
    
                <!-- Items Status Chart -->
                <div class="mb-5">
                    <h1 class="text-2xl font-bold text-center text-gray-800">Items Status</h1>
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
    
        new Chart(document.getElementById('leadsTimelineChart'), {
            type: 'line',
            data: {
                labels: leadsTimelineLabels,
                datasets: [{
                    label: 'Dhahran',
                    data: dhahranTimelineData,
                    borderColor: '#00262f',
                                fill: false
                            },
                            {
                                label: 'Bashaer',
                                data: bashaerTimelineData,
                                borderColor: '#543829',
                    fill: false
                }]
            }
        });
    
        // Leads Status
        const statusLabels = comparisonData.leads_status.map(s => s.status);
        const statusDhahran = comparisonData.leads_status.map(s => s.dhahran);
        const statusBashaer = comparisonData.leads_status.map(s => s.bashaer);
    
        new Chart(document.getElementById('leadsStatusChart'), {
            type: 'bar',
            data: {
                labels: statusLabels,
                datasets: [{
                        label: 'Dhahran',
                        data: statusDhahran,
                        backgroundColor: '#00262f'
                    },
                    {
                        label: 'Bashaer',
                        data: statusBashaer,
                        backgroundColor: '#543829'
                    }
                ]
            }
        });
    
        // Appointments
        new Chart(document.getElementById('appointmentsChart'), {
            type: 'pie',
            data: {
                labels: ['Dhahran', 'Bashaer'],
                datasets: [{
                    data: [
                        comparisonData.appointments.total_dhahran,
                        comparisonData.appointments.total_bashaer
                    ],
                    backgroundColor: ['#00262f', '#543829']
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
                labels: ['Added', 'Started', 'Ended'],
                datasets: [{
                        label: 'Dhahran',
                        data: Object.values(comparisonData.call_logs.totals.dhahran),
                        backgroundColor: '#00262f'
                    },
                    {
                        label: 'Bashaer',
                        data: Object.values(comparisonData.call_logs.totals.bashaer),
                        backgroundColor: '#543829'
                    }
                ]
            }
        });
    
        // Items
        new Chart(document.getElementById('itemsChart'), {
            type: 'bar',
            data: {
                labels: ['Available', 'Reserved', 'Blocked', 'Contacted'],
                datasets: [{
                        label: 'Dhahran',
                        data: [
                            comparisonData.items.available.dhahran,
                            comparisonData.items.reserved.dhahran,
                            comparisonData.items.blocked.dhahran,
                            comparisonData.items.contacted.dhahran,
                        ],
                        backgroundColor: '#00262f'
                    },
                    {
                        label: 'Bashaer',
                        data: [
                            comparisonData.items.available.bashaer,
                            comparisonData.items.reserved.bashaer,
                            comparisonData.items.blocked.bashaer,
                            comparisonData.items.contacted.bashaer,
                        ],
                        backgroundColor: '#543829'
                    }
                ]
            }
        });
    </script>
@endsection