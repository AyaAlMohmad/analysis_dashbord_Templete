<div style="background-color: #f9f6f2; padding: 60px 20px; font-family: 'Arial', sans-serif; direction: rtl; position: relative;">

    <!-- Logo Top -->
    <div style="position: absolute; top: 30px; right: 30px;">
        <img src="{{ asset('build/logo.png') }}" alt="Tatwir Logo" style="height: 50px;">
    </div>

    <!-- Title -->
    <div style="text-align: center; margin-bottom: 40px;">
        <h2 style="font-size: 28px; color: #8b5a3b; border-bottom: 2px solid #8b5a3b; display: inline-block;">
            إحصائيات الوحدات حسب المرحلة - {{ isset($unitStats['project_name']) ? $unitStats['project_name'] : 'Project' }}
        </h2>
    </div>

    <!-- Content Row -->
    <div style="display: flex; flex-direction: row-reverse; align-items: flex-start;">
        <!-- Side Decoration -->
        <div style="flex: 0 0 auto; min-width: 100px; position: absolute; top: 100px;">
            <img src="{{ asset('images/style2.png') }}" alt="Decoration" style="height: 500px;">
        </div>

        <!-- Main Tables Container -->
        @if(isset($unitStats) && count($unitStats) > 0)
        <div style="flex: 1 0 auto; display: grid; margin-right: 120px; margin-left: 120px; grid-template-columns: repeat(2, 1fr); gap: 12px; font-family: 'Arial', sans-serif; font-size: 9px;">
            @foreach ($unitStats as $stage)
                @if($loop->index < 4) <!-- عرض أول 4 مراحل فقط -->
                <table style="border-collapse: collapse; background-color: white; table-layout: fixed; font-size: 8px; width: 100%; max-width: 400px;">
                    <caption style="font-weight: bold; margin-bottom: 3px;">مرحلة رقم {{ $stage['group_name'] ?? 'N/A' }}</caption>
                    <thead style="background-color: #ffe082; font-weight: bold;">
                        <tr>
                            <th style="border: 1px solid #ccc; padding: 2px; font-size: 8px;">النموذج</th>
                            <th style="border: 1px solid #ccc; padding: 2px;">الوحدات</th>
                            <th style="border: 1px solid #ccc; padding: 2px;">المبيع/متاح</th>
                            <th style="border: 1px solid #ccc; padding: 2px;">من</th>
                            <th style="border: 1px solid #ccc; padding: 2px;">إلى</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (($stage['report_data'] ?? []) as $modelData)
                            @php
                                $modelName = [
                                    'A' => 'عبق A',
                                    'B' => 'إيوان B',
                                    'C' => 'نجديه C',
                                    'D' => 'رويق D',
                                    'E' => 'مقام E',
                                    'F' => 'روف F',
                                ][$modelData['type'] ?? ''] ?? ($modelData['type'] ?? 'N/A');
                            @endphp
                            <tr>
                                <td style="border: 1px solid #ccc; padding: 2px;">{{ $modelName }}</td>
                                <td style="border: 1px solid #ccc; padding: 2px;">{{ $modelData['count'] ?? 0 }}</td>
                                <td style="border: 1px solid #ccc; padding: 2px;">{{ $modelData['available_blocked'] ?? 0 }}</td>
                                <td style="border: 1px solid #ccc; padding: 2px;">
                                    {{ isset($modelData['min_rate']) ? number_format($modelData['min_rate'], 0, '.', ',') : '--' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 2px;">
                                    {{ isset($modelData['max_rate']) ? number_format($modelData['max_rate'], 0, '.', ',') : '--' }}
                                </td>
                            </tr>
                        @endforeach
                        <tr style="background-color: #eee; font-weight: bold;">
                            <td style="border: 1px solid #ccc; padding: 2px;">إجمالي المرحلة</td>
                            <td style="border: 1px solid #ccc; padding: 2px;">{{ $stage['totals']['total_items'] ?? 0 }}</td>
                            <td style="border: 1px solid #ccc; padding: 2px;">{{ $stage['totals']['available_blocked'] ?? 0 }}</td>
                            <td style="border: 1px solid #ccc; padding: 2px;" colspan="2">
                                @if(isset($stage['totals']['min_rate']) && isset($stage['totals']['max_rate']))
                                    {{ number_format($stage['totals']['min_rate'], 0, '.', ',') }} - {{ number_format($stage['totals']['max_rate'], 0, '.', ',') }}
                                @else
                                    ---
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
                @endif
            @endforeach
        </div>
        @else
        <div style="text-align: center; width: 100%; padding: 20px; color: #8b5a3b; font-weight: bold;">
            لا توجد بيانات متاحة للإحصائيات حسب المرحلة
        </div>
        @endif
    </div>

    <!-- Logo Bottom -->
    <div style="position: absolute; right: 30px; bottom: 30px;">
        @if(isset($project_name) && $project_name == 'أزيان الظهران')
            <img src="{{ asset('images/logo5.png') }}" alt="Azyan Logo Dhahran" style="height: 50px;">
        @elseif(isset($project_name) && $project_name == 'أزيان البشائر')
            <img src="{{ asset('images/logo6.png') }}" alt="Azyan Logo Albashaer" style="height: 50px;">
        @else
            <img src="{{ asset('images/default-logo.png') }}" alt="Default Logo" style="height: 50px;">
        @endif
    </div>
</div>

<!-- الصفحة الثانية للمراحل 5 و6 -->
@if(isset($unitStats) && count($unitStats) > 4)
<div style="background-color: #f9f6f2; padding: 60px 20px; font-family: 'Arial', sans-serif; direction: rtl; position: relative;">

    <!-- Logo Top -->
    <div style="position: absolute; top: 30px; right: 30px;">
        <img src="{{ asset('build/logo.png') }}" alt="Tatwir Logo" style="height: 50px;">
    </div>

    <!-- Title -->
    <div style="text-align: center; margin-bottom: 40px;">
        <h2 style="font-size: 28px; color: #8b5a3b; border-bottom: 2px solid #8b5a3b; display: inline-block;">
            إحصائيات الوحدات حسب المرحلة (تابع) - {{ isset($unitStats['project_name']) ? $unitStats['project_name'] : 'Project' }}
        </h2>
    </div>

    <!-- Content Row -->
    <div style="display: flex; flex-direction: row-reverse; align-items: flex-start;">
        <!-- Side Decoration -->
        <div style="flex: 0 0 auto; min-width: 100px; position: absolute; top: 100px;">
            <img src="{{ asset('images/style2.png') }}" alt="Decoration" style="height: 500px;">
        </div>

        <!-- Main Tables Container -->
        <div style="flex: 1 0 auto; display: grid; margin-right: 120px; margin-left: 120px; grid-template-columns: repeat(2, 1fr); gap: 12px; font-family: 'Arial', sans-serif; font-size: 9px;">
            @foreach ($unitStats as $stage)
                @if($loop->index >= 4 && $loop->index <= 5) <!-- المرحلتين 5 و6 فقط -->
                <table style="border-collapse: collapse; background-color: white; table-layout: fixed; font-size: 8px; width: 100%; max-width: 400px;">
                    <caption style="font-weight: bold; margin-bottom: 3px;">مرحلة رقم {{ $stage['group_name'] ?? 'N/A' }}</caption>
                    <thead style="background-color: #ffe082; font-weight: bold;">
                        <tr>
                            <th style="border: 1px solid #ccc; padding: 2px; font-size: 8px;">النموذج</th>
                            <th style="border: 1px solid #ccc; padding: 2px;">الوحدات</th>
                            <th style="border: 1px solid #ccc; padding: 2px;">المبيع/متاح</th>
                            <th style="border: 1px solid #ccc; padding: 2px;">من</th>
                            <th style="border: 1px solid #ccc; padding: 2px;">إلى</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (($stage['report_data'] ?? []) as $modelData)
                            @php
                                $modelName = [
                                    'A' => 'عبق A',
                                    'B' => 'إيوان B',
                                    'C' => 'نجديه C',
                                    'D' => 'رويق D',
                                    'E' => 'مقام E',
                                    'F' => 'روف F',
                                ][$modelData['type'] ?? ''] ?? ($modelData['type'] ?? 'N/A');
                            @endphp
                            <tr>
                                <td style="border: 1px solid #ccc; padding: 2px;">{{ $modelName }}</td>
                                <td style="border: 1px solid #ccc; padding: 2px;">{{ $modelData['count'] ?? 0 }}</td>
                                <td style="border: 1px solid #ccc; padding: 2px;">{{ $modelData['available_blocked'] ?? 0 }}</td>
                                <td style="border: 1px solid #ccc; padding: 2px;">
                                    {{ isset($modelData['min_rate']) ? number_format($modelData['min_rate'], 0, '.', ',') : '--' }}
                                </td>
                                <td style="border: 1px solid #ccc; padding: 2px;">
                                    {{ isset($modelData['max_rate']) ? number_format($modelData['max_rate'], 0, '.', ',') : '--' }}
                                </td>
                            </tr>
                        @endforeach
                        <tr style="background-color: #eee; font-weight: bold;">
                            <td style="border: 1px solid #ccc; padding: 2px;">إجمالي المرحلة</td>
                            <td style="border: 1px solid #ccc; padding: 2px;">{{ $stage['totals']['total_items'] ?? 0 }}</td>
                            <td style="border: 1px solid #ccc; padding: 2px;">{{ $stage['totals']['available_blocked'] ?? 0 }}</td>
                            <td style="border: 1px solid #ccc; padding: 2px;" colspan="2">
                                @if(isset($stage['totals']['min_rate']) && isset($stage['totals']['max_rate']))
                                    {{ number_format($stage['totals']['min_rate'], 0, '.', ',') }} - {{ number_format($stage['totals']['max_rate'], 0, '.', ',') }}
                                @else
                                    ---
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Logo Bottom -->
    <div style="position: absolute; right: 30px; bottom: 30px;">
        @if(isset($project_name) && $project_name == 'أزيان الظهران')
            <img src="{{ asset('images/logo5.png') }}" alt="Azyan Logo Dhahran" style="height: 50px;">
        @elseif(isset($project_name) && $project_name == 'أزيان البشائر')
            <img src="{{ asset('images/logo6.png') }}" alt="Azyan Logo Albashaer" style="height: 50px;">
        @else
            <img src="{{ asset('images/default-logo.png') }}" alt="Default Logo" style="height: 50px;">
        @endif
    </div>
</div>
@endif