<div
    style="background-color: #f9f6f2; padding: 60px 20px; font-family: 'Arial', sans-serif; direction: rtl; position: relative;">

    <!-- Logo Top -->
    <div style="position: absolute; top: 30px; right: 30px;">
        <img src="{{ asset('build/logo.png') }}" alt="Tatwir Logo" style="height: 50px;">
    </div>

    <!-- Title -->
    <div style="text-align: center; margin-bottom: 40px;">
        <h2 style="font-size: 28px; color: #8b5a3b; border-bottom: 2px solid #8b5a3b; display: inline-block;">
            {{ __('messages.targeted_month') }}{{ now()->format('F') }}
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

            <div style="max-width: 400px; margin: 0 auto;">
                <canvas id="targetChart_{{ $uniqueId = uniqid() }}" height="200"></canvas>
            </div>

            <table style="width: 100%; border-collapse: collapse; margin-top: 20px; background-color: white;">
                <thead style="background-color: #ffe082; font-weight: bold;">
                    <tr>
                        <th style="border: 1px solid #ccc;">{{ __('components.status') }}</th>
                        <th style="border: 1px solid #ccc;">{{ __('components.target') }}</th>
                        <th style="border: 1px solid #ccc;">{{ __('components.achieved') }}</th>
                        <th style="border: 1px solid #ccc;">{{ __('components.percentage') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $statusOrder = ['مهتم', 'موعد', 'زيارة', 'حجز', 'إلغاء', 'عقد'];
                    @endphp

                    @foreach ($statusOrder as $status)
                        @if (isset($data['data'][$status]))
                            <tr>
                                <td style="border: 1px solid #ccc;">{{ __('components.statuses.' . $status) }}</td>
                                <td style="border: 1px solid #ccc;">{{ $data['data'][$status]['target'] }}</td>
                                <td style="border: 1px solid #ccc;">{{ $data['data'][$status]['count'] }}</td>
                                <td style="border: 1px solid #ccc;">
                                    @if ($status !== 'إلغاء')
                                        {{ number_format($data['data'][$status]['percentage'] * 100, 2) }}%
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            (function() {
                const ctx = document.getElementById('targetChart_{{ $uniqueId }}').getContext('2d');
                const statusLabels = {!! json_encode($statusOrder) !!};
                const targetData = [];
                const achievedData = [];
                const percentageData = [];

                @foreach ($statusOrder as $status)
                    @if (isset($data['data'][$status]))
                        targetData.push({{ $data['data'][$status]['target'] }});
                        achievedData.push({{ $data['data'][$status]['count'] }});
                        percentageData.push({{ $data['data'][$status]['percentage'] * 100 }});
                    @else
                        targetData.push(0);
                        achievedData.push(0);
                        percentageData.push(0);
                    @endif
                @endforeach

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: statusLabels,
                        datasets: [{
                            label: '{{ __('components.target') }}',
                            data: targetData,
                            backgroundColor: '#3a3a3a'
                        }, {
                            label: '{{ __('components.achieved') }}',
                            data: achievedData,
                            backgroundColor: '#c87c2a'
                        }, {
                            label: '{{ __('components.percentage') }}',
                            data: percentageData,
                            backgroundColor: '#0f683f'
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                rtl: true
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label === '{{ __('components.percentage') }}') {
                                            label += ': ' + context.raw.toFixed(2) + '%';
                                        } else {
                                            label += ': ' + context.raw;
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1000
                                }
                            }
                        }
                    }
                });
            })();
        </script>

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
</div>
