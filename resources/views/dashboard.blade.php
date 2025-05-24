@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- fitness target -->
            @php
                $items = $comparisonData['items'];
            @endphp
            @php
                $grandTotal = 0;
                foreach (['available', 'blocked', 'reserved', 'contracted'] as $key) {
                    $grandTotal += ($items[$key]['dhahran'] ?? 0) + ($items[$key]['bashaer'] ?? 0);
                }
            @endphp

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                @foreach ([['label' => 'Available', 'key' => 'available', 'color' => 'primary', 'icon' => 'icon-check', 'colorCode' => '#00B5B8'], ['label' => 'Blocked', 'key' => 'blocked', 'color' => 'danger', 'icon' => 'icon-ban', 'colorCode' => '#FF7588'], ['label' => 'Reserved', 'key' => 'reserved', 'color' => 'warning', 'icon' => 'icon-calendar', 'colorCode' => '#FFA87D'], ['label' => 'Contracted', 'key' => 'contracted', 'color' => 'success', 'icon' => 'icon-briefcase', 'colorCode' => '#16D39A']] as $item)
                                    @php
                                        $dh = $items[$item['key']]['dhahran'] ?? 0;
                                        $bs = $items[$item['key']]['bashaer'] ?? 0;
                                        $total = $dh + $bs;
                                        $percent = $grandTotal ? round(($total / $grandTotal) * 100) : 0;
                                        $dh_percent = $total ? round(($dh / $total) * 100) : 0;
                                        $bs_percent = $total ? round(($bs / $total) * 100) : 0;
                                    @endphp
                                    <div class="col-xl-3 col-lg-6 col-md-12 border-right-blue-grey border-right-lighten-5">
                                        <div class="my-1 text-center">
                                            <div class="card-header mb-2 pt-0">
                                                {{-- <h5 class="{{ $item['color'] }}">{{ $item['label'] }}</h5> --}}
                                                <h5 class="{{ $item['color'] }}">
                                                    {{ __('comparison_report.' . $item['key']) }}</h5>

                                                <h3 class="font-large-2 text-bold-200">{{ $total }}</h3>
                                            </div>
                                            <div class="card-content">
                                                <input type="text" value="{{ $bs_percent }}"
                                                    class="knob hide-value responsive angle-offset" data-angleOffset="40"
                                                    data-thickness=".15" data-linecap="round" data-width="130"
                                                    data-height="130" data-inputColor="#BABFC7" data-readOnly="true"
                                                    data-fgColor="{{ $item['colorCode'] }}"
                                                    data-knob-icon="{{ $item['icon'] }}">

                                                <ul class="list-inline clearfix pt-1 mb-0">
                                                    <li class="border-right-grey border-right-lighten-2 pr-2">
                                                        <h2 class="grey darken-1 text-bold-400">{{ $dh_percent }}%</h2>
                                                        <span class="{{ $item['color'] }}">
                                                            <span class="ft-arrow-up"></span>
                                                            {{ __('comparison_report.dhahran') }}</span>
                                                    </li>
                                                    <li class="pl-2">
                                                        <h2 class="grey darken-1 text-bold-400">{{ $bs_percent }}%</h2>
                                                        <span class="{{ $item['color'] }}">
                                                            <span class="ft-arrow-down"></span>
                                                            {{ __('comparison_report.bashaer') }}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- activity charts -->
            <div class="row match-height">
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

                                <ul class="list-inline text-center m-0">
                                    <li>
                                        <h6><i class="ft-circle danger"></i> {{ __('comparison_report.dhahran_added') }}
                                        </h6>
                                    </li>
                                    <li class="ml-1">
                                        <h6><i class="ft-circle success"></i> {{ __('comparison_report.bashaer_added') }}
                                        </h6>
                                    </li>
                                    <li class="ml-1">
                                        <h6><i class="ft-circle warning"></i> {{ __('comparison_report.dhahran_edited') }}
                                        </h6>
                                    </li>
                                    <li class="ml-1">
                                        <h6><i class="ft-circle info"></i> {{ __('comparison_report.bashaer_edited') }}
                                        </h6>
                                    </li>
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

            <!--/ activity charts -->

            <!-- Added Calls -->
            <div class="row">
                @if (isset($comparisonData['call_logs']['error']))
                    <div class="col-12">
                        <div class="alert alert-danger">
                            {{ $comparisonData['call_logs']['error'] }}
                        </div>
                    </div>
                @else
                    @php
                        $added = $comparisonData['call_logs']['totals'];
                    @endphp

                    <!-- Dhahran Added Calls -->
                    <div class="col-xl-3 col-lg-6 col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body text-left w-100">
                                            @php
                                                $totalDhahranAdded = $added['dhahran']['added'] ?? 0;
                                            @endphp
                                            <h3 class="primary">{{ $totalDhahranAdded }}</h3>
                                            <span>{{ __('comparison_report.added_calls_dhahran') }}</span>

                                        </div>
                                        <div class="media-right media-middle">
                                            <i class="icon-call-in primary font-large-2 float-right"></i>
                                        </div>
                                    </div>
                                    <div class="progress progress-sm mt-1 mb-0">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bashaer Added Calls -->
                    <div class="col-xl-3 col-lg-6 col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body text-left w-100">
                                            @php
                                                $totalBashaerAdded = $added['bashaer']['added'] ?? 0;
                                            @endphp
                                            <h3 class="info">{{ $totalBashaerAdded }}</h3>
                                            <span>{{ __('comparison_report.added_calls_bashaer') }}</span>

                                        </div>
                                        <div class="media-right media-middle">
                                            <i class="icon-call-in info font-large-2 float-right"></i>
                                        </div>
                                    </div>
                                    <div class="progress progress-sm mt-1 mb-0">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 100%"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dhahran Ended Calls -->
                    <div class="col-xl-3 col-lg-6 col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body text-left w-100">
                                            @php
                                                $totalDhahranEnded = $added['dhahran']['ended'] ?? 0;
                                            @endphp
                                            <h3 class="danger">{{ $totalDhahranEnded }}</h3>
                                            <span>{{ __('comparison_report.ended_calls_dhahran') }}</span>
                                        </div>
                                        <div class="media-right media-middle">
                                            <i class="icon-call-end danger font-large-2 float-right"></i>
                                        </div>
                                    </div>
                                    <div class="progress progress-sm mt-1 mb-0">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 100%"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bashaer Ended Calls -->
                    <div class="col-xl-3 col-lg-6 col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media">
                                        <div class="media-body text-left w-100">
                                            @php
                                                $totalBashaerEnded = $added['bashaer']['ended'] ?? 0;
                                            @endphp
                                            <h3 class="danger">{{ $totalBashaerEnded }}</h3>
                                            <span>{{ __('comparison_report.ended_calls_bashaer') }}</span>
                                        </div>
                                        <div class="media-right media-middle">
                                            <i class="icon-call-end danger font-large-2 float-right"></i>
                                        </div>
                                    </div>
                                    <div class="progress progress-sm mt-1 mb-0">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 100%"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Appointments Section -->
            <div class="row match-height">
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
                            <h4 class="card-title">{{ __('comparison_report.appointments_by_date') }} </h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content">
                            <div id="goal-list-scroll" class="table-responsive height-250 position-relative">
                                <table class="table mb-0">
                                    <thead>

                                        <tr>
                                            <th>{{ __('comparison_report.date') }}</th>
                                            <th>{{ __('comparison_report.location') }}</th>
                                            <th>{{ __('comparison_report.appointment_count') }}</th>
                                        </tr>


                                    </thead>
                                    <tbody>
                                        @foreach ($comparisonData['appointments']['timeline'] as $row)
                                            <tr>
                                                <td>{{ $row['date'] }}</td>
                                                <td>{{ __('comparison_report.dhahran') }}</td>
                                                <td class="text-center font-small-2">
                                                    {{ round(($row['dhahran'] / max(1, $row['bashaer'] + $row['dhahran'])) * 100, 1) }}%
                                                    <div class="progress progress-sm mt-1 mb-0">
                                                        <div class="progress-bar bg-primary" role="progressbar"
                                                            style="width: {{ round(($row['dhahran'] / max(1, $row['bashaer'] + $row['dhahran'])) * 100, 1) }}%"
                                                            aria-valuenow="{{ $row['dhahran'] }}" aria-valuemin="0"
                                                            aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{ $row['date'] }}</td>
                                                <td>{{ __('comparison_report.bashaer') }}</td>
                                                <td class="text-center font-small-2">
                                                    {{ round(($row['bashaer'] / max(1, $row['bashaer'] + $row['dhahran'])) * 100, 1) }}%
                                                    <div class="progress progress-sm mt-1 mb-0">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                            style="width: {{ round(($row['bashaer'] / max(1, $row['bashaer'] + $row['dhahran'])) * 100, 1) }}%"
                                                            aria-valuenow="{{ $row['bashaer'] }}" aria-valuemin="0"
                                                            aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Charts Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Appointments Chart
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('appointments-chart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json(collect($comparisonData['appointments']['timeline'])->pluck('date')),
                    datasets: [{
                            label: '{{ __('comparison_report.dhahran') }}',
                            data: @json(collect($comparisonData['appointments']['timeline'])->pluck('dhahran')),
                            borderColor: '#4CAF50',
                            backgroundColor: 'rgba(76, 175, 80, 0.2)',
                            tension: 0.4,
                            fill: true,
                        },
                        {
                            label: '{{ __('comparison_report.bashaer') }}',
                            data: @json(collect($comparisonData['appointments']['timeline'])->pluck('bashaer')),
                            borderColor: '#2196F3',
                            backgroundColor: 'rgba(33, 150, 243, 0.2)',
                            tension: 0.4,
                            fill: true,
                        }
                    ]
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
                            beginAtZero: true,
                            grid: {
                                color: '#f5f5f5'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });

        // Activity Charts
        const leadsTimeline = @json($comparisonData['leads_timeline']);
        const labels = Object.keys(leadsTimeline);
        const dhahranAdded = labels.map(date => leadsTimeline[date]['dhahran_added']);
        const bashaerAdded = labels.map(date => leadsTimeline[date]['bashaer_added']);
        const dhahranEdited = labels.map(date => leadsTimeline[date]['dhahran_edited']);
        const bashaerEdited = labels.map(date => leadsTimeline[date]['bashaer_edited']);

        // Weekly Activity Chart
        new Chart(document.getElementById('weekly-activity-chart').getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                        label: "{{ __('comparison_report.dhahran_added') }}",
                        borderColor: '#FF6384',
                        data: dhahranAdded,
                        tension: 0.4
                    },
                    {
                        label: "{{ __('comparison_report.bashaer_added') }}",
                        borderColor: '#4BC0C0',
                        data: bashaerAdded,
                        tension: 0.4
                    },
                    {
                        label: "{{ __('comparison_report.dhahran_edited') }}",
                        borderColor: '#FFCD56',
                        data: dhahranEdited,
                        tension: 0.4
                    },
                    {
                        label: "{{ __('comparison_report.bashaer_edited') }}",
                        borderColor: '#36A2EB',
                        data: bashaerEdited,
                        tension: 0.4
                    }


                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Doughnut Chart
        new Chart(document.getElementById('activity-division').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: [
                    '{{ __('comparison_report.dhahran_added') }}',
                    '{{ __('comparison_report.bashaer_added') }}',
                    '{{ __('comparison_report.dhahran_edited') }}',
                    '{{ __('comparison_report.bashaer_edited') }}'
                ],
                datasets: [{
                    data: [
                        dhahranAdded.reduce((a, b) => a + b, 0),
                        bashaerAdded.reduce((a, b) => a + b, 0),
                        dhahranEdited.reduce((a, b) => a + b, 0),
                        bashaerEdited.reduce((a, b) => a + b, 0)
                    ],
                    backgroundColor: ['#FF6384', '#4BC0C0', '#FFCD56', '#36A2EB']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
    @push('scripts')
        <script src="{{ asset('app-assets/js/scripts/pages/dashboard-fitness.js') }}" type="text/javascript"></script>
    @endpush
@endsection
