<div
    style="background-color: #f9f6f2; padding: 60px 20px; font-family: 'Arial', sans-serif; direction: rtl; position: relative;">


    <div style="position: absolute; top: 30px; right: 30px;">
        <img src="{{ asset('build/logo.png') }}" alt="Tatwir Logo" style="height: 50px;">
    </div>


    <div style="text-align: center; margin-bottom: 40px;">
        <h2 style="font-size: 28px; color: #8b5a3b; border-bottom: 2px solid #8b5a3b; display: inline-block;">
         {{__('messages.reserved')}}
        </h2>
    </div>


    <div
        style="display: flex; gap: 20px; flex-direction: row-reverse; justify-content: space-between; align-items: flex-start;">


        <div style="flex: 0 0 auto; min-width: 100px;">
            <img src="{{ asset('images/style2.png') }}" alt="Decoration" style="height: 500px;">
        </div>

        <div style="flex: 1; max-width: 50%;">
            <canvas id="bookingsChart_{{ Str::slug($project_name, '_') }}"
                style="width: 100%; max-height: 400px;"></canvas>

        </div>


        <div style="flex: 1; max-width: 40%;">
            <table
                style="width: 100%; max-width: 500px; border-collapse: collapse; font-size: 12px; background-color: #fff; direction: rtl; text-align: center; font-family: 'Arial', sans-serif;">
                <thead>
                    <tr style="background-color: #8b5a3b; color: #fff;">
                        <th style="padding: 6px 8px; border: 1px solid #ddd;">{{__('components.project_name')}}</th>
                        <th style="padding: 6px 8px; border: 1px solid #ddd;">{{__('components.developer')}} </th>
                        <th style="padding: 6px 8px; border: 1px solid #ddd;">{{__('components.units')}} </th>
                        <th style="padding: 6px 8px; border: 1px solid #ddd;"> {{__('components.reserved')}}</th>
                    </tr>
                </thead>
                <tbody>


                
                    @php
                    $projectN = match($project_name) {
                        'أزيان الظهران' => 'azyan aldhahran',
                        'أزيان البشائر' => 'azyan albashaer',
                        default => '',
                    };
                @endphp
                
                
                @foreach ($projects as $index => $project)
                    
                   <tr style="
                   background-color: {{ $project['name'] === $projectN ? '#d9f3e2' : ($loop->even ? '#f5f5f5' : '#ffffff') }};
                   border-bottom: 1px solid #ddd;
               ">
               
                        <td style="padding: 6px 8px;">{{ $project['name'] }}</td>
                        <td style="padding: 6px 8px;">{{ $project['developer'] }}</td>
                        <td style="padding: 6px 8px; font-weight: bold;">{{ $project['units'] }}</td>
                        <td style="padding: 6px 8px; font-weight: bold;">{{ $project['reserved'] }}</td>
                    </tr>
                @endforeach
                

                </tbody>
            </table>


            <div style="position: absolute; right: 30px; bottom: 30px;">
                @if (isset($project_name) && $project_name == 'أزيان الظهران')
                    <img src="{{ asset('images/logo5.png') }}" alt="Azyan Logo Dhahran" style="height: 50px;">
                @elseif(isset($project_name) && $project_name == 'أزيان البشائر')
                    <img src="{{ asset('images/logo6.png') }}" alt="Azyan Logo Albashaer" style="height: 50px;">
                @else
                    <img src="{{ asset('images/default-logo.png') }}" alt="Default Logo" style="height: 50px;">
                @endif
            </div>

        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let ctx_{{ Str::slug($project_name, '_') }} = document.getElementById(
        'bookingsChart_{{ Str::slug($project_name, '_') }}').getContext('2d');
    new Chart(ctx_{{ Str::slug($project_name, '_') }}, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chart['labels']) !!},
            datasets: [{
                label: 'Reserved',
                data: {!! json_encode($chart['data']) !!},
                backgroundColor: ['#5e3e2f', '#7a4e36', '#a96845', ...Array(14).fill('#C57B57')]
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'x',
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 20
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    rtl: true
                }
            }
        }
    });
</script>
