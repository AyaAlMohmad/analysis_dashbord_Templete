<div
    style="background-color: #f9f6f2; padding: 60px 20px; font-family: 'Arial', sans-serif; direction: rtl; position: relative;">

    <!-- Logo Top -->
    <div style="position: absolute; top: 30px; right: 30px;">
        <img src="{{ asset('build/logo.png') }}" alt="Tatwir Logo" style="height: 50px;">
    </div>

    <!-- Title -->
    <div style="text-align: center; margin-bottom: 40px;">
        <h2 style="font-size: 28px; color: #8b5a3b; border-bottom: 2px solid #8b5a3b; display: inline-block;">
            {{ __('messages.visits_payments_contracts') }} {{ date('F') }}

        </h2>
    </div>

    <!-- Content Row -->
    <div style="display: flex; flex-direction: row-reverse; align-items: flex-start;">
        <!-- Side Decoration -->
        <div style="flex: 0 0 auto; min-width: 100px; position: absolute; top: 100px;">
            <img src="{{ asset('images/style2.png') }}" alt="Decoration" style="height: 500px;">
        </div>

        <!-- Main Tables Container -->
        <div
            style="max-width: 95%; margin: 40px auto; font-family: 'Arial', sans-serif; font-size: 13px; text-align: center;">

            <canvas id="visitsChart_{{ Str::slug($project_name, '_') }}"
                style="max-width: 100%; height: 250px;"></canvas>

            <table style="width: 100%; border-collapse: collapse; margin-top: 30px;">
                <thead style="background-color: #ffe082; font-weight: bold;">
                    <tr>
                        <th>{{ __('components.period') }}</th>
                        <th>{{ __('components.current_month') }}</th>
                        <th>{{ __('components.last_month') }}</th>
                        <th>{{ __('components.two_months_ago') }}</th>
                        <th>{{ __('components.week_1') }}</th>
                        <th>{{ __('components.week_2') }}</th>
                        <th>{{ __('components.week_3') }}</th>
                        <th>{{ __('components.week_4') }}</th>
                        @if (isset($data['data']['current_month']['week_5']))
                            <th>{{ __('components.week_5') }}</th>
                        @endif

                    </tr>
                </thead>
                <tbody>
                    <tr style="background-color: #f4f4f4;">
                        <td>{{ __('components.visits') }}</td>


                        <td>{{ $data['data']['current_month']['month_total']['visits'] ?? 0 }}</td>
                        <td>{{ $data['data']['last_month']['month_total']['visits'] ?? 0 }}</td>
                        <td>{{ $data['data']['two_months_ago']['month_total']['visits'] ?? 0 }}</td>
                        <td>{{ $data['data']['current_month']['week_1']['visits'] ?? 0 }}</td>
                        <td>{{ $data['data']['current_month']['week_2']['visits'] ?? 0 }}</td>
                        <td>{{ $data['data']['current_month']['week_3']['visits'] ?? 0 }}</td>
                        <td>{{ $data['data']['current_month']['week_4']['visits'] ?? 0 }}</td>
                        @if (isset($data['data']['current_month']['week_5']))
                            <td>{{ $data['data']['current_month']['week_5']['visits'] ?? 0 }}</td>
                        @endif
                    </tr>
                    <tr style="background-color: #f9f9f9;">
                        <td>{{ __('components.payments') }}</td>

                        <td>{{ $data['data']['current_month']['month_total']['payments'] ?? 0 }}</td>
                        <td>{{ $data['data']['last_month']['month_total']['payments'] ?? 0 }}</td>
                        <td>{{ $data['data']['two_months_ago']['month_total']['payments'] ?? 0 }}</td>
                        <td>{{ $data['data']['current_month']['week_1']['payments'] ?? 0 }}</td>
                        <td>{{ $data['data']['current_month']['week_2']['payments'] ?? 0 }}</td>
                        <td>{{ $data['data']['current_month']['week_3']['payments'] ?? 0 }}</td>
                        <td>{{ $data['data']['current_month']['week_4']['payments'] ?? 0 }}</td>
                        @if (isset($data['data']['current_month']['week_5']))
                            <td>{{ $data['data']['current_month']['week_5']['payments'] ?? 0 }}</td>
                        @endif
                    </tr>
                    <tr style="background-color: #f4f4f4;">
                        <td>{{ __('components.contracts') }}</td>
                        <td>{{ $data['data']['current_month']['month_total']['contracts'] ?? 0 }}</td>
                        <td>{{ $data['data']['last_month']['month_total']['contracts'] ?? 0 }}</td>
                        <td>{{ $data['data']['two_months_ago']['month_total']['contracts'] ?? 0 }}</td>
                        <td>{{ $data['data']['current_month']['week_1']['contracts'] ?? 0 }}</td>
                        <td>{{ $data['data']['current_month']['week_2']['contracts'] ?? 0 }}</td>
                        <td>{{ $data['data']['current_month']['week_3']['contracts'] ?? 0 }}</td>
                        <td>{{ $data['data']['current_month']['week_4']['contracts'] ?? 0 }}</td>
                        @if (isset($data['data']['current_month']['week_5']))
                            <td>{{ $data['data']['current_month']['week_5']['contracts'] ?? 0 }}</td>
                        @endif
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- ✅ Script for Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const visitsChartCanvas_{{ Str::slug($project_name, '_') }} = document.getElementById(
                'visitsChart_{{ Str::slug($project_name, '_') }}').getContext('2d');
            new Chart(visitsChartCanvas_{{ Str::slug($project_name, '_') }}, {
                type: 'line',
                data: {
                    labels: [
                        '{{ __('components.two_months_ago') }}',
                        '{{ __('components.last_month') }}',
                        '{{ __('components.current_month') }}',
                        '{{ __('components.week_1') }}',
                        '{{ __('components.week_2') }}',
                        '{{ __('components.week_3') }}',
                        '{{ __('components.week_4') }}'
                        @if (isset($data['data']['current_month']['week_5']))
                            , '{{ __('components.week_5') }}'
                        @endif
                    ],

                    datasets: [{
                            label: '{{ __('components.visits') }}',
                            data: [
                                {{ $data['data']['two_months_ago']['month_total']['visits'] ?? 0 }},
                                {{ $data['data']['last_month']['month_total']['visits'] ?? 0 }},
                                {{ $data['data']['current_month']['month_total']['visits'] ?? 0 }},
                                {{ $data['data']['current_month']['week_1']['visits'] ?? 0 }},
                                {{ $data['data']['current_month']['week_2']['visits'] ?? 0 }},
                                {{ $data['data']['current_month']['week_3']['visits'] ?? 0 }},
                                {{ $data['data']['current_month']['week_4']['visits'] ?? 0 }}
                                @if (isset($data['data']['current_month']['week_5']))
                                    , {{ $data['data']['current_month']['week_5']['visits'] ?? 0 }}
                                @endif
                            ],
                            borderColor: '#1f4e79',
                            backgroundColor: '#1f4e79',
                            tension: 0.4
                        },
                        {
                            label: '{{ __('components.payments') }}',
                            data: [
                                {{ $data['data']['two_months_ago']['month_total']['payments'] ?? 0 }},
                                {{ $data['data']['last_month']['month_total']['payments'] ?? 0 }},
                                {{ $data['data']['current_month']['month_total']['payments'] ?? 0 }},
                                {{ $data['data']['current_month']['week_1']['payments'] ?? 0 }},
                                {{ $data['data']['current_month']['week_2']['payments'] ?? 0 }},
                                {{ $data['data']['current_month']['week_3']['payments'] ?? 0 }},
                                {{ $data['data']['current_month']['week_4']['payments'] ?? 0 }}
                                @if (isset($data['data']['current_month']['week_5']))
                                    , {{ $data['data']['current_month']['week_5']['payments'] ?? 0 }}
                                @endif
                            ],
                            borderColor: '#db4437',
                            backgroundColor: '#db4437',
                            tension: 0.4
                        },
                        {
                            label: '{{ __('components.contracts') }}',
                            data: [
                                {{ $data['data']['two_months_ago']['month_total']['contracts'] ?? 0 }},
                                {{ $data['data']['last_month']['month_total']['contracts'] ?? 0 }},
                                {{ $data['data']['current_month']['month_total']['contracts'] ?? 0 }},
                                {{ $data['data']['current_month']['week_1']['contracts'] ?? 0 }},
                                {{ $data['data']['current_month']['week_2']['contracts'] ?? 0 }},
                                {{ $data['data']['current_month']['week_3']['contracts'] ?? 0 }},
                                {{ $data['data']['current_month']['week_4']['contracts'] ?? 0 }}
                                @if (isset($data['data']['current_month']['week_5']))
                                    , {{ $data['data']['current_month']['week_5']['contracts'] ?? 0 }}
                                @endif
                            ],
                            borderColor: '#0f9d58',
                            backgroundColor: '#0f9d58',
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            rtl: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        </script>
    </div>

    <!-- Logo Bottom -->
    <div style="position: absolute; right: 30px; bottom: 30px;">
        @if (isset($project_name) && $project_name == 'أزيان الظهران')
            <img src="{{ asset('images/logo5.png') }}" alt="Azyan Logo Dhahran" style="height: 50px;">
        @elseif(isset($project_name) && $project_name == 'أزيان البشائر')
            <img src="{{ asset('images/logo6.png') }}" alt="Azyan Logo Albashaer" style="height: 50px;">
            @elseif (!empty($logo) && file_exists(public_path('storage/' . $logo)))
            <img src="{{ asset('storage/' . $logo) }}" alt="Site Logo" style="height: 50px;">
        @else
            <span style="font-size: 14px; color: #8b5a3b; font-weight: bold;">{{ $project_name }}</span>
        @endif

    </div>
</div>
