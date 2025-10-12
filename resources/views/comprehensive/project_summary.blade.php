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
                // تحديد هيكل البيانات بناءً على البيانات الفعلية
                $options = $data['data']['options'] ?? [];
                $totalItems = $data['data']['total_items'] ?? [];
                $reservedItems = $data['data']['reserved_items'] ?? [];
                $contractedItems = $data['data']['contracted_items'] ?? [];
                $percentages = $data['data']['percentages'] ?? [];
                $contractedPercentages = $data['data']['contracted_percentages'] ?? [];

                // تحديد أسماء النماذج بناءً على المشروع ونوع المفاتيح
                $modelNames = [];
                if (isset($project_name)) {
                    if ($project_name == 'أزيان الظهران') {
                        $modelNames = [
                            1 => 'روفان',
                            2 => 'إيوان',
                            3 => 'مجدان',
                            4 => 'رونق',
                            5 => 'مقام'
                        ];
                    } elseif ($project_name == 'أزيان جدة') {
                        $modelNames = [
                            'A' => 'مرجانة',
                            'B' => 'أجوان',
                            'C' => 'رونق',
                            'D' => 'مقام'
                        ];
                    } elseif ($project_name == 'أزيان البشائر') {
                        $modelNames = [
                            'A' => 'A',
                            'B' => 'B',
                            'C' => 'C',
                            'D' => 'D',
                            'E' => 'E',
                            'F' => 'F'
                        ];
                    }
                }

                // إذا لم نحدد أسماء، نستخدم الخيارات الأصلية
                if (empty($modelNames)) {
                    foreach ($options as $key => $value) {
                        $modelNames[$key] = $value;
                    }
                }

                $sumTotal = array_sum(array_map('intval', $totalItems));
                $sumReserved = array_sum(array_map('intval', $reservedItems));
                $sumContracted = array_sum(array_map('intval', $contractedItems));
                $avgPercent = $sumTotal > 0 ? round(($sumReserved / $sumTotal) * 100, 2) : 0;
                $avgContractedPercent = $sumTotal > 0 ? round(($sumContracted / $sumTotal) * 100, 2) : 0;
            @endphp

            <table style="width: 100%; border-collapse: collapse; margin-top: 20px; background-color: white; font-size: 15px;">
                <thead>
                    <tr style="background-color: #ffe082; font-weight: bold;">
                        <th colspan="{{ count($modelNames) + 2 }}" style="padding: 14px; border: 1px solid #ccc;">  {{__('components.project_summary')}} </th>
                    </tr>
                    <tr style="background-color: #8b5a3b; color: white;">
                        <th style="border: 1px solid #ccc; padding: 10px;">{{__('components.data')}}</th>
                        @foreach ($modelNames as $key => $name)
                            <th style="border: 1px solid #ccc; padding: 10px;">{{ $name }}</th>
                        @endforeach
                        <th style="border: 1px solid #ccc; padding: 10px;">{{__('components.total')}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="background-color: #f4f4f4;">
                        <td style="border: 1px solid #ccc; padding: 10px;">  {{__('components.total_units')}} </td>
                        @foreach ($modelNames as $key => $name)
                            <td style="border: 1px solid #ccc; padding: 10px;">{{ $totalItems[$key] ?? 0 }}</td>
                        @endforeach
                        <td style="border: 1px solid #4CAF50; font-weight: bold; color: green; padding: 10px;">{{ $sumTotal }}</td>
                    </tr>
                    <tr style="background-color: #f9f9f9;">
                        <td style="border: 1px solid #ccc; padding: 10px;">{{__('components.total_reservations')}} </td>
                        @foreach ($modelNames as $key => $name)
                            <td style="border: 1px solid #ccc; padding: 10px;">{{ $reservedItems[$key] ?? 0 }}</td>
                        @endforeach
                        <td style="border: 1px solid #4CAF50; font-weight: bold; padding: 10px;">{{ $sumReserved }}</td>
                    </tr>
                    <tr style="background-color: #e8f5e8;">
                        <td style="border: 1px solid #ccc; padding: 10px;">{{__('components.contracted_units')}} </td>
                        @foreach ($modelNames as $key => $name)
                            <td style="border: 1px solid #ccc; padding: 10px;">{{ $contractedItems[$key] ?? 0 }}</td>
                        @endforeach
                        <td style="border: 1px solid #4CAF50; font-weight: bold; padding: 10px;">{{ $sumContracted }}</td>
                    </tr>
                    <tr style="background-color: #eee;">
                        <td style="border: 1px solid #ccc; padding: 10px;">{{__('components.reservation_percentage')}} </td>
                        @foreach ($modelNames as $key => $name)
                            @php
                                $percentage = $percentages[$key] ?? 0;
                                // إذا كانت النسبة أقل من 1، نضرب في 100 (مثل 0.42 تصبح 42%)
                                $displayPercentage = $percentage < 1 ? round($percentage * 100, 2) : $percentage;
                            @endphp
                            <td style="border: 1px solid #ccc; padding: 10px;">{{ $displayPercentage }}%</td>
                        @endforeach
                        <td style="border: 1px solid #4CAF50; font-weight: bold; padding: 10px;">{{ $avgPercent }}%</td>
                    </tr>
                    <tr style="background-color: #e8f5e8;">
                        <td style="border: 1px solid #ccc; padding: 10px;">{{__('components.contracted_percentage')}} </td>
                        @foreach ($modelNames as $key => $name)
                            @php
                                $percentage = $contractedPercentages[$key] ?? 0;
                                // إذا كانت النسبة أقل من 1، نضرب في 100 (مثل 0.42 تصبح 42%)
                                $displayPercentage = $percentage < 1 ? round($percentage * 100, 2) : $percentage;
                            @endphp
                            <td style="border: 1px solid #ccc; padding: 10px;">{{ $displayPercentage }}%</td>
                        @endforeach
                        <td style="border: 1px solid #4CAF50; font-weight: bold; padding: 10px;">{{ $avgContractedPercent }}%</td>
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
        @elseif(isset($project_name) && $project_name == 'أزيان جدة')
            <img src="{{ asset('images/jadah.png') }}" alt="Azyan Logo Jeddah" style="height: 50px;">
        @elseif (!empty($logo) && file_exists(public_path('storage/' . $logo)))
            <img src="{{ asset('storage/' . $logo) }}" alt="Site Logo" style="height: 50px;">
        @else
            <span style="font-size: 14px; color: #8b5a3b; font-weight: bold;">{{ $project_name }}</span>
        @endif
    </div>
</div>
