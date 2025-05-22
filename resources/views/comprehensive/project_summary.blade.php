<div
    style="background-color: #f9f6f2; padding: 60px 20px; font-family: 'Arial', sans-serif; direction: rtl; position: relative;">

    <!-- Logo Top -->
    <div style="position: absolute; top: 30px; right: 30px;">
        <img src="{{ asset('build/logo.png') }}" alt="Tatwir Logo" style="height: 50px;">
    </div>

    <!-- Title -->
    <div style="text-align: center; margin-bottom: 40px;">
        <h2 style="font-size: 28px; color: #8b5a3b; border-bottom: 2px solid #8b5a3b; display: inline-block;">
           {{__('messages.project_summary')}}
        </h2>
    </div>

    <!-- Content Row -->
    <div style="display: flex; flex-direction: row-reverse; align-items: flex-start;">
        <!-- Side Decoration -->
        <div style="flex: 0 0 auto; min-width: 100px; position: absolute; top: 100px;">
            <img src="{{ asset('images/style2.png') }}" alt="Decoration" style="height: 500px;">
        </div>

        <!-- Main Tables Container -->
        <div style="max-width: 95%; margin: 40px auto; font-family: 'Arial', sans-serif; font-size: 13px; text-align: center;">
    
            @php
            $options = $data['data']['items']['options'] ?? [];
            $totalItems = $data['data']['items']['total_items'] ?? [];
            $reservedItems = $data['data']['items']['reserved_items'] ?? [];
            $percentages = $data['data']['percentages'] ?? [];
        
            $sumTotal = array_sum(array_map('intval', $totalItems));
            $sumReserved = array_sum(array_map('intval', $reservedItems));
            $avgPercent = $sumTotal > 0 ? round(($sumReserved / $sumTotal) * 100, 2) : 0;
        @endphp
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px; background-color: white; font-size: 15px;">
            <thead>
                <tr style="background-color: #ffe082; font-weight: bold;">
                    <th colspan="{{ count($options) + 2 }}" style="padding: 14px; border: 1px solid #ccc;">  {{__('components.project_summary')}} </th>
                </tr>
                <tr style="background-color: #8b5a3b; color: white;">
                    <th style="border: 1px solid #ccc; padding: 10px;">{{__('components.data')}}</th>
                    @foreach ($options as $label)
                        <th style="border: 1px solid #ccc; padding: 10px;">{{ $label }}</th>
                    @endforeach
                    <th style="border: 1px solid #ccc; padding: 10px;">{{__('components.total')}}</th>
                </tr>
            </thead>
            <tbody>
                <tr style="background-color: #f4f4f4;">
                    <td style="border: 1px solid #ccc; padding: 10px;">  {{__('components.total_units')}} </td>
                    @foreach ($options as $key => $label)
                        <td style="border: 1px solid #ccc; padding: 10px;">{{ $totalItems[$key] ?? 0 }}</td>
                    @endforeach
                    <td style="border: 1px solid #4CAF50; font-weight: bold; color: green; padding: 10px;">{{ $sumTotal }}</td>
                </tr>
                <tr style="background-color: #f9f9f9;">
                    <td style="border: 1px solid #ccc; padding: 10px;">{{__('components.total_reservations')}} </td>
                    @foreach ($options as $key => $label)
                        <td style="border: 1px solid #ccc; padding: 10px;">{{ $reservedItems[$key] ?? 0 }}</td>
                    @endforeach
                    <td style="border: 1px solid #4CAF50; font-weight: bold; padding: 10px;">{{ $sumReserved }}</td>
                </tr>
                <tr style="background-color: #eee;">
                    <td style="border: 1px solid #ccc; padding: 10px;">{{__('components.percentage')}} </td>
                    @foreach ($options as $key => $label)
                        <td style="border: 1px solid #ccc; padding: 10px;">{{ ($percentages[$key] ?? 0) . '%' }}</td>
                    @endforeach
                    <td style="border: 1px solid #4CAF50; font-weight: bold; padding: 10px;">{{ $avgPercent }}%</td>
                </tr>
            </tbody>
        </table>
        
        

        </div>
    </div>

    <!-- Logo Bottom -->
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
