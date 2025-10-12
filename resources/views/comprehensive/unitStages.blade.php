<div style="background-color: #f9f6f2; padding: 60px 20px; font-family: 'Arial', sans-serif; direction: rtl; position: relative;">

    <!-- Logo Top -->
    <div style="position: absolute; top: 30px; right: 30px;">
        <img src="{{ asset('build/logo.png') }}" alt="Tatwir Logo" style="height: 50px;">
    </div>

    <!-- Title -->
    <div style="text-align: center; margin-bottom: 40px;">
        <h2 style="font-size: 28px; color: #8b5a3b; border-bottom: 2px solid #8b5a3b; display: inline-block;">
            {{ __('messages.unit_stages') }}
        </h2>
    </div>
    <div style="display: flex; flex-direction: row-reverse; align-items: flex-start;">
        <!-- Side Decoration -->
        <div style="flex: 0 0 auto; min-width: 100px; position: absolute; top: 100px;">
            <img src="{{ asset('images/style2.png') }}" alt="Decoration" style="height: 500px;">
        </div>
    <div style="display: flex; flex-direction: column; gap: 30px; width: 100%; align-items: center;">

        @php
            // تحديد هيكل البيانات بناءً على البيانات الفعلية
            $isDhahranStructure = isset($unitStages['groups']) &&
                                 isset($unitStages['groups'][1]) &&
                                 isset($unitStages['groups'][1]['total']);

            $isJeddahStructure = isset($unitStages['groups']) &&
                                isset($unitStages['groups']['A']) &&
                                isset($unitStages['groups']['A']['total']);

            $isAlbashaerStructure = (isset($unitStages['A']) && isset($unitStages['A']['total'])) ||
                                   (isset($unitStages['A']) && isset($unitStages['A']['count']));

            // إذا لم نتمكن من تحديد الهيكل، نستخدم البيانات كما هي
            $hasData = $isDhahranStructure || $isJeddahStructure || $isAlbashaerStructure ||
                      (isset($unitStages['groups']) && !empty($unitStages['groups'])) ||
                      (isset($unitStages['A']) || isset($unitStages['B']) || isset($unitStages['C']));
        @endphp

        @if($isDhahranStructure)
        <!-- جدول مخصص للظهران -->
        <table style="width: 90%; max-width: 800px; border-collapse: collapse; font-size: 12px; font-family: 'Arial', sans-serif; text-align: center; background-color: white; border: 1px solid #ccc;">
            <thead>
                <tr style="background-color: #fff; font-weight: bold; color: #333;">
                    <th style="border: 1px solid #ccc; width: 80px;">{{ __('components.unit_sales.prices') }}</th>
                    @foreach([1, 2, 3, 4, 5] as $group)
                    <th style="border: 1px solid #ccc;">-</th>
                    @endforeach
                </tr>
                <tr style="background-color: #ffe082; font-weight: bold;">
                    <th style="border: 1px solid #ccc;">{{ __('components.unit_sales.models') }}</th>
                    <th style="border: 1px solid #ccc;">1<br>روفان</th>
                    <th style="border: 1px solid #ccc;">2<br>إيوان</th>
                    <th style="border: 1px solid #ccc;">3<br>مجدان</th>
                    <th style="border: 1px solid #ccc;">4<br>رونق</th>
                    <th style="border: 1px solid #ccc;">5<br>مقام</th>
                    <th style="border: 1px solid #ccc; background-color: #ffe082;">{{ __('components.unit_sales.total') }}</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td style="border: 1px solid #ccc; background-color: #ffe082;">{{ __('components.unit_sales.villas_count') }}</td>
                    @php
                        $totalVillas = 0;
                    @endphp
                    @foreach([1, 2, 3, 4, 5] as $group)
                        @php
                            $count = $unitStages['groups'][$group]['total'] ?? 0;
                            $totalVillas += $count;
                        @endphp
                        <td style="border: 1px solid #ccc;">{{ $count }}</td>
                    @endforeach
                    <td style="border: 1px solid #ccc; background-color: #ffe082;">{{ $totalVillas }}</td>
                </tr>

                <tr>
                    <td style="border: 1px solid #ccc; background-color: #ffe082;">{{ __('components.unit_sales.reserved') }}</td>
                    @php
                        $totalReserved = 0;
                    @endphp
                    @foreach([1, 2, 3, 4, 5] as $group)
                        @php
                            $reserved = $unitStages['groups'][$group]['reserved'] ?? 0;
                            $totalReserved += $reserved;
                        @endphp
                        <td style="border: 1px solid #ccc;">{{ $reserved }}</td>
                    @endforeach
                    <td style="border: 1px solid #ccc; background-color: #ffe082;">{{ $totalReserved }}</td>
                </tr>

                <tr>
                    <td style="border: 1px solid #ccc; background-color: #ffe082;">{{ __('components.unit_sales.contracted') }}</td>
                    @php
                        $totalContracted = 0;
                    @endphp
                    @foreach([1, 2, 3, 4, 5] as $group)
                        @php
                            $contracted = $unitStages['groups'][$group]['contracted'] ?? 0;
                            $totalContracted += $contracted;
                        @endphp
                        <td style="border: 1px solid #ccc;">{{ $contracted }}</td>
                    @endforeach
                    <td style="border: 1px solid #ccc; background-color: #ffe082;">{{ $totalContracted }}</td>
                </tr>

                <tr>
                    <td style="border: 1px solid #ccc; background-color: #ffe082;">{{ __('components.unit_sales.blocked') }}</td>
                    @php
                        $totalBlocked = 0;
                    @endphp
                    @foreach([1, 2, 3, 4, 5] as $group)
                        @php
                            $blocked = $unitStages['groups'][$group]['blocked'] ?? 0;
                            $totalBlocked += $blocked;
                        @endphp
                        <td style="border: 1px solid #ccc;">{{ $blocked }}</td>
                    @endforeach
                    <td style="border: 1px solid #ccc; background-color: #ffe082;">{{ $totalBlocked }}</td>
                </tr>

                <tr>
                    <td style="border: 1px solid #ccc; background-color: #ffe082;">{{ __('components.unit_sales.available') }}</td>
                    @php
                        $totalAvailable = 0;
                    @endphp
                    @foreach([1, 2, 3, 4, 5] as $group)
                        @php
                            $available = $unitStages['groups'][$group]['available'] ?? 0;
                            $totalAvailable += $available;
                        @endphp
                        <td style="border: 1px solid #ccc;">{{ $available }}</td>
                    @endforeach
                    <td style="border: 1px solid #ccc; background-color: #ffe082;">{{ $totalAvailable }}</td>
                </tr>

                <tr style="background-color: #ffe082; font-weight: bold;">
                    <td style="border: 1px solid #ccc;">{{ __('components.unit_sales.total') }}</td>
                    @php
                        $grandTotal = 0;
                    @endphp
                    @foreach([1, 2, 3, 4, 5] as $group)
                        @php
                            $groupTotal = ($unitStages['groups'][$group]['reserved'] ?? 0)
                                        + ($unitStages['groups'][$group]['contracted'] ?? 0)
                                        + ($unitStages['groups'][$group]['blocked'] ?? 0)
                                        + ($unitStages['groups'][$group]['available'] ?? 0);
                            $grandTotal += $groupTotal;
                        @endphp
                        <td style="border: 1px solid #ccc;">{{ $groupTotal }}</td>
                    @endforeach
                    <td style="border: 1px solid #ccc; font-weight: bold;">{{ $grandTotal }}</td>
                </tr>

                <tr style="background-color: #8b5a3b; color: white; font-weight: bold;">
                    <td style="border: 1px solid #ccc;">{{ __('components.unit_sales.final_contracted') }}</td>
                    @foreach([1, 2, 3, 4, 5] as $group)
                        @php
                            $groupTotal = ($unitStages['groups'][$group]['reserved'] ?? 0)
                                        + ($unitStages['groups'][$group]['contracted'] ?? 0)
                                        + ($unitStages['groups'][$group]['blocked'] ?? 0)
                                        + ($unitStages['groups'][$group]['available'] ?? 0);
                            $contracted = $unitStages['groups'][$group]['contracted'] ?? 0;
                            $percentage = $groupTotal > 0 ? round(($contracted / $groupTotal) * 100) : 0;
                        @endphp
                        <td style="border: 1px solid #ccc;">{{ $percentage }}%</td>
                    @endforeach
                    <td style="border: 1px solid #ccc;">-</td>
                </tr>

                <tr style="background-color: #8b5a3b; color: white; font-weight: bold;">
                    <td style="border: 1px solid #ccc;">{{ __('components.unit_sales.model_percentage') }}</td>
                    @foreach([1, 2, 3, 4, 5] as $group)
                        @php
                            $groupTotal = ($unitStages['groups'][$group]['reserved'] ?? 0)
                                        + ($unitStages['groups'][$group]['contracted'] ?? 0)
                                        + ($unitStages['groups'][$group]['blocked'] ?? 0)
                                        + ($unitStages['groups'][$group]['available'] ?? 0);
                            $percentage = $grandTotal > 0 ? round(($groupTotal / $grandTotal) * 100) : 0;
                        @endphp
                        <td style="border: 1px solid #ccc;">{{ $percentage }}%</td>
                    @endforeach
                    <td style="border: 1px solid #ccc;">-</td>
                </tr>
            </tbody>
        </table>

        <!-- Second Table for Dhahran -->
        <table style="width: 50%; direction: rtl; border-collapse: collapse; font-size: 13px; font-family: 'Arial'; text-align: center; border: 1px solid #ccc;">
            @php
                $totalSoldReserved = $totalReserved + $totalContracted;
                $group1 = ($unitStages['groups'][1]['reserved'] ?? 0) + ($unitStages['groups'][1]['contracted'] ?? 0);
                $group2 = ($unitStages['groups'][2]['reserved'] ?? 0) + ($unitStages['groups'][2]['contracted'] ?? 0);
                $group3 = ($unitStages['groups'][3]['reserved'] ?? 0) + ($unitStages['groups'][3]['contracted'] ?? 0);
                $group4 = ($unitStages['groups'][4]['reserved'] ?? 0) + ($unitStages['groups'][4]['contracted'] ?? 0);
                $group5 = ($unitStages['groups'][5]['reserved'] ?? 0) + ($unitStages['groups'][5]['contracted'] ?? 0);
            @endphp

            <tr style="background-color: #5e3d2c; color: white; font-weight: bold;">
                <td style="border: 1px solid #ccc; text-align: right;">المجموعة 1 (روفان)</td>
                <td style="border: 1px solid #ccc; background-color: #ffe082; color: black;">{{ $group1 }}</td>
                <td style="border: 1px solid #ccc;">النسبة {{ $group1 }}</td>
                <td style="border: 1px solid #ccc; background-color: #ffe082; color: black;">
                    {{ $totalSoldReserved > 0 ? round(($group1 / $totalSoldReserved) * 100, 1) : 0 }}%
                </td>
            </tr>
            <tr style="background-color: #5e3d2c; color: white; font-weight: bold;">
                <td style="border: 1px solid #ccc; text-align: right;">المجموعة 2 (إيوان)</td>
                <td style="border: 1px solid #ccc; background-color: #ffe082; color: black;">{{ $group2 }}</td>
                <td style="border: 1px solid #ccc;">النسبة {{ $group2 }}</td>
                <td style="border: 1px solid #ccc; background-color: #ffe082; color: black;">
                    {{ $totalSoldReserved > 0 ? round(($group2 / $totalSoldReserved) * 100, 1) : 0 }}%
                </td>
            </tr>
            <tr style="background-color: #5e3d2c; color: white; font-weight: bold;">
                <td style="border: 1px solid #ccc; text-align: right;">المجموعة 3 (مجدان)</td>
                <td style="border: 1px solid #ccc; background-color: #ffe082; color: black;">{{ $group3 }}</td>
                <td style="border: 1px solid #ccc;">النسبة {{ $group3 }}</td>
                <td style="border: 1px solid #ccc; background-color: #ffe082; color: black;">
                    {{ $totalSoldReserved > 0 ? round(($group3 / $totalSoldReserved) * 100, 1) : 0 }}%
                </td>
            </tr>
            <tr style="background-color: #5e3d2c; color: white; font-weight: bold;">
                <td style="border: 1px solid #ccc; text-align: right;">المجموعة 4 (رونق)</td>
                <td style="border: 1px solid #ccc; background-color: #ffe082; color: black;">{{ $group4 }}</td>
                <td style="border: 1px solid #ccc;">النسبة {{ $group4 }}</td>
                <td style="border: 1px solid #ccc; background-color: #ffe082; color: black;">
                    {{ $totalSoldReserved > 0 ? round(($group4 / $totalSoldReserved) * 100, 1) : 0 }}%
                </td>
            </tr>
            <tr style="background-color: #5e3d2c; color: white; font-weight: bold;">
                <td style="border: 1px solid #ccc; text-align: right;">المجموعة 5 (مقام)</td>
                <td style="border: 1px solid #ccc; background-color: #ffe082; color: black;">{{ $group5 }}</td>
                <td style="border: 1px solid #ccc;">النسبة {{ $group5 }}</td>
                <td style="border: 1px solid #ccc; background-color: #ffe082; color: black;">
                    {{ $totalSoldReserved > 0 ? round(($group5 / $totalSoldReserved) * 100, 1) : 0 }}%
                </td>
            </tr>
        </table>

        @elseif($isJeddahStructure)
        <!-- جدول مخصص لجدة -->
        <table style="width: 90%; max-width: 800px; border-collapse: collapse; font-size: 12px; font-family: 'Arial', sans-serif; text-align: center; background-color: white; border: 1px solid #ccc;">
            <thead>
                <tr style="background-color: #fff; font-weight: bold; color: #333;">
                    <th style="border: 1px solid #ccc; width: 80px;">{{ __('components.unit_sales.prices') }}</th>
                    @foreach(['A', 'B', 'C', 'D'] as $model)
                    <th style="border: 1px solid #ccc;">-</th>
                    @endforeach
                </tr>
                <tr style="background-color: #ffe082; font-weight: bold;">
                    <th style="border: 1px solid #ccc;">{{ __('components.unit_sales.models') }}</th>
                    <th style="border: 1px solid #ccc;">A<br>مرجانة</th>
                    <th style="border: 1px solid #ccc;">B<br>أجوان</th>
                    <th style="border: 1px solid #ccc;">C<br>رونق</th>
                    <th style="border: 1px solid #ccc;">D<br>مقام</th>
                    <th style="border: 1px solid #ccc; background-color: #ffe082;">{{ __('components.unit_sales.total') }}</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td style="border: 1px solid #ccc; background-color: #ffe082;">{{ __('components.unit_sales.villas_count') }}</td>
                    @php
                        $totalVillas = 0;
                    @endphp
                    @foreach(['A', 'B', 'C', 'D'] as $model)
                        @php
                            $count = $unitStages['groups'][$model]['total'] ?? 0;
                            $totalVillas += $count;
                        @endphp
                        <td style="border: 1px solid #ccc;">{{ $count }}</td>
                    @endforeach
                    <td style="border: 1px solid #ccc; background-color: #ffe082;">{{ $totalVillas }}</td>
                </tr>

                <tr>
                    <td style="border: 1px solid #ccc; background-color: #ffe082;">{{ __('components.unit_sales.reserved') }}</td>
                    @php
                        $totalReserved = 0;
                    @endphp
                    @foreach(['A', 'B', 'C', 'D'] as $model)
                        @php
                            $reserved = $unitStages['groups'][$model]['reserved'] ?? 0;
                            $totalReserved += $reserved;
                        @endphp
                        <td style="border: 1px solid #ccc;">{{ $reserved }}</td>
                    @endforeach
                    <td style="border: 1px solid #ccc; background-color: #ffe082;">{{ $totalReserved }}</td>
                </tr>

                <tr>
                    <td style="border: 1px solid #ccc; background-color: #ffe082;">{{ __('components.unit_sales.contracted') }}</td>
                    @php
                        $totalContracted = 0;
                    @endphp
                    @foreach(['A', 'B', 'C', 'D'] as $model)
                        @php
                            $contracted = $unitStages['groups'][$model]['contracted'] ?? 0;
                            $totalContracted += $contracted;
                        @endphp
                        <td style="border: 1px solid #ccc;">{{ $contracted }}</td>
                    @endforeach
                    <td style="border: 1px solid #ccc; background-color: #ffe082;">{{ $totalContracted }}</td>
                </tr>

                <tr>
                    <td style="border: 1px solid #ccc; background-color: #ffe082;">{{ __('components.unit_sales.blocked') }}</td>
                    @php
                        $totalBlocked = 0;
                    @endphp
                    @foreach(['A', 'B', 'C', 'D'] as $model)
                        @php
                            $blocked = $unitStages['groups'][$model]['blocked'] ?? 0;
                            $totalBlocked += $blocked;
                        @endphp
                        <td style="border: 1px solid #ccc;">{{ $blocked }}</td>
                    @endforeach
                    <td style="border: 1px solid #ccc; background-color: #ffe082;">{{ $totalBlocked }}</td>
                </tr>

                <tr>
                    <td style="border: 1px solid #ccc; background-color: #ffe082;">{{ __('components.unit_sales.available') }}</td>
                    @php
                        $totalAvailable = 0;
                    @endphp
                    @foreach(['A', 'B', 'C', 'D'] as $model)
                        @php
                            $available = $unitStages['groups'][$model]['available'] ?? 0;
                            $totalAvailable += $available;
                        @endphp
                        <td style="border: 1px solid #ccc;">{{ $available }}</td>
                    @endforeach
                    <td style="border: 1px solid #ccc; background-color: #ffe082;">{{ $totalAvailable }}</td>
                </tr>

                <tr style="background-color: #ffe082; font-weight: bold;">
                    <td style="border: 1px solid #ccc;">{{ __('components.unit_sales.total') }}</td>
                    @php
                        $grandTotal = 0;
                    @endphp
                    @foreach(['A', 'B', 'C', 'D'] as $model)
                        @php
                            $modelTotal = ($unitStages['groups'][$model]['reserved'] ?? 0)
                                        + ($unitStages['groups'][$model]['contracted'] ?? 0)
                                        + ($unitStages['groups'][$model]['blocked'] ?? 0)
                                        + ($unitStages['groups'][$model]['available'] ?? 0);
                            $grandTotal += $modelTotal;
                        @endphp
                        <td style="border: 1px solid #ccc;">{{ $modelTotal }}</td>
                    @endforeach
                    <td style="border: 1px solid #ccc; font-weight: bold;">{{ $grandTotal }}</td>
                </tr>

                <tr style="background-color: #8b5a3b; color: white; font-weight: bold;">
                    <td style="border: 1px solid #ccc;">{{ __('components.unit_sales.final_contracted') }}</td>
                    @foreach(['A', 'B', 'C', 'D'] as $model)
                        @php
                            $modelTotal = ($unitStages['groups'][$model]['reserved'] ?? 0)
                                        + ($unitStages['groups'][$model]['contracted'] ?? 0)
                                        + ($unitStages['groups'][$model]['blocked'] ?? 0)
                                        + ($unitStages['groups'][$model]['available'] ?? 0);
                            $contracted = $unitStages['groups'][$model]['contracted'] ?? 0;
                            $percentage = $modelTotal > 0 ? round(($contracted / $modelTotal) * 100) : 0;
                        @endphp
                        <td style="border: 1px solid #ccc;">{{ $percentage }}%</td>
                    @endforeach
                    <td style="border: 1px solid #ccc;">-</td>
                </tr>

                <tr style="background-color: #8b5a3b; color: white; font-weight: bold;">
                    <td style="border: 1px solid #ccc;">{{ __('components.unit_sales.model_percentage') }}</td>
                    @foreach(['A', 'B', 'C', 'D'] as $model)
                        @php
                            $modelTotal = ($unitStages['groups'][$model]['reserved'] ?? 0)
                                        + ($unitStages['groups'][$model]['contracted'] ?? 0)
                                        + ($unitStages['groups'][$model]['blocked'] ?? 0)
                                        + ($unitStages['groups'][$model]['available'] ?? 0);
                            $percentage = $grandTotal > 0 ? round(($modelTotal / $grandTotal) * 100) : 0;
                        @endphp
                        <td style="border: 1px solid #ccc;">{{ $percentage }}%</td>
                    @endforeach
                    <td style="border: 1px solid #ccc;">-</td>
                </tr>
            </tbody>
        </table>

        <!-- Second Table for Jeddah -->
        <table style="width: 50%; direction: rtl; border-collapse: collapse; font-size: 13px; font-family: 'Arial'; text-align: center; border: 1px solid #ccc;">
            @php
                $totalSoldReserved = $totalReserved + $totalContracted;
                $groupA = ($unitStages['groups']['A']['reserved'] ?? 0) + ($unitStages['groups']['A']['contracted'] ?? 0);
                $groupB = ($unitStages['groups']['B']['reserved'] ?? 0) + ($unitStages['groups']['B']['contracted'] ?? 0);
                $groupC = ($unitStages['groups']['C']['reserved'] ?? 0) + ($unitStages['groups']['C']['contracted'] ?? 0);
                $groupD = ($unitStages['groups']['D']['reserved'] ?? 0) + ($unitStages['groups']['D']['contracted'] ?? 0);
            @endphp

            <tr style="background-color: #5e3d2c; color: white; font-weight: bold;">
                <td style="border: 1px solid #ccc; text-align: right;">المجموعة A (مرجانة)</td>
                <td style="border: 1px solid #ccc; background-color: #ffe082; color: black;">{{ $groupA }}</td>
                <td style="border: 1px solid #ccc;">النسبة {{ $groupA }}</td>
                <td style="border: 1px solid #ccc; background-color: #ffe082; color: black;">
                    {{ $totalSoldReserved > 0 ? round(($groupA / $totalSoldReserved) * 100, 1) : 0 }}%
                </td>
            </tr>
            <tr style="background-color: #5e3d2c; color: white; font-weight: bold;">
                <td style="border: 1px solid #ccc; text-align: right;">المجموعة B (أجوان)</td>
                <td style="border: 1px solid #ccc; background-color: #ffe082; color: black;">{{ $groupB }}</td>
                <td style="border: 1px solid #ccc;">النسبة {{ $groupB }}</td>
                <td style="border: 1px solid #ccc; background-color: #ffe082; color: black;">
                    {{ $totalSoldReserved > 0 ? round(($groupB / $totalSoldReserved) * 100, 1) : 0 }}%
                </td>
            </tr>
            <tr style="background-color: #5e3d2c; color: white; font-weight: bold;">
                <td style="border: 1px solid #ccc; text-align: right;">المجموعة C (رونق)</td>
                <td style="border: 1px solid #ccc; background-color: #ffe082; color: black;">{{ $groupC }}</td>
                <td style="border: 1px solid #ccc;">النسبة {{ $groupC }}</td>
                <td style="border: 1px solid #ccc; background-color: #ffe082; color: black;">
                    {{ $totalSoldReserved > 0 ? round(($groupC / $totalSoldReserved) * 100, 1) : 0 }}%
                </td>
            </tr>
            <tr style="background-color: #5e3d2c; color: white; font-weight: bold;">
                <td style="border: 1px solid #ccc; text-align: right;">المجموعة D (مقام)</td>
                <td style="border: 1px solid #ccc; background-color: #ffe082; color: black;">{{ $groupD }}</td>
                <td style="border: 1px solid #ccc;">النسبة {{ $groupD }}</td>
                <td style="border: 1px solid #ccc; background-color: #ffe082; color: black;">
                    {{ $totalSoldReserved > 0 ? round(($groupD / $totalSoldReserved) * 100, 1) : 0 }}%
                </td>
            </tr>
        </table>

        @elseif($hasData)
        <!-- جدول عام للبيانات الأخرى -->
        <table style="width: 90%; max-width: 800px; border-collapse: collapse; font-size: 12px; font-family: 'Arial', sans-serif; text-align: center; background-color: white; border: 1px solid #ccc;">
            <thead>
                <tr style="background-color: #ffe082; font-weight: bold;">
                    <th style="border: 1px solid #ccc;">{{ __('components.unit_sales.models') }}</th>
                    <th style="border: 1px solid #ccc;">{{ __('components.unit_sales.villas_count') }}</th>
                    <th style="border: 1px solid #ccc;">{{ __('components.unit_sales.reserved') }}</th>
                    <th style="border: 1px solid #ccc;">{{ __('components.unit_sales.contracted') }}</th>
                    <th style="border: 1px solid #ccc;">{{ __('components.unit_sales.blocked') }}</th>
                    <th style="border: 1px solid #ccc;">{{ __('components.unit_sales.available') }}</th>
                    <th style="border: 1px solid #ccc;">{{ __('components.unit_sales.total') }}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalVillas = 0;
                    $totalReserved = 0;
                    $totalContracted = 0;
                    $totalBlocked = 0;
                    $totalAvailable = 0;
                    $grandTotal = 0;
                @endphp

                @if(isset($unitStages['groups']) && is_array($unitStages['groups']))
                    @foreach($unitStages['groups'] as $model => $data)
                        @if(is_array($data) && isset($data['total']))
                        @php
                            $totalVillas += $data['total'] ?? 0;
                            $totalReserved += $data['reserved'] ?? 0;
                            $totalContracted += $data['contracted'] ?? 0;
                            $totalBlocked += $data['blocked'] ?? 0;
                            $totalAvailable += $data['available'] ?? 0;
                            $modelTotal = ($data['reserved'] ?? 0) + ($data['contracted'] ?? 0) + ($data['blocked'] ?? 0) + ($data['available'] ?? 0);
                            $grandTotal += $modelTotal;
                        @endphp
                        <tr>
                            <td style="border: 1px solid #ccc;">{{ $model }}</td>
                            <td style="border: 1px solid #ccc;">{{ $data['total'] ?? 0 }}</td>
                            <td style="border: 1px solid #ccc;">{{ $data['reserved'] ?? 0 }}</td>
                            <td style="border: 1px solid #ccc;">{{ $data['contracted'] ?? 0 }}</td>
                            <td style="border: 1px solid #ccc;">{{ $data['blocked'] ?? 0 }}</td>
                            <td style="border: 1px solid #ccc;">{{ $data['available'] ?? 0 }}</td>
                            <td style="border: 1px solid #ccc;">{{ $modelTotal }}</td>
                        </tr>
                        @endif
                    @endforeach
                @else
                    @foreach(['A', 'B', 'C', 'D', 'E', 'F'] as $model)
                        @if(isset($unitStages[$model]))
                        @php
                            $data = $unitStages[$model];
                            if (isset($data['count'])) {
                                $count = $data['count'];
                            } elseif (isset($data['total'])) {
                                $count = $data['total'];
                            } else {
                                $count = 0;
                            }

                            if (isset($data['status_counts'])) {
                                $reserved = $data['status_counts']['reserved'] ?? 0;
                                $contracted = $data['status_counts']['contracted'] ?? 0;
                                $blocked = $data['status_counts']['blocked'] ?? 0;
                                $available = $data['status_counts']['available'] ?? 0;
                            } else {
                                $reserved = $data['reserved'] ?? 0;
                                $contracted = $data['contracted'] ?? 0;
                                $blocked = $data['blocked'] ?? 0;
                                $available = $data['available'] ?? 0;
                            }

                            $totalVillas += $count;
                            $totalReserved += $reserved;
                            $totalContracted += $contracted;
                            $totalBlocked += $blocked;
                            $totalAvailable += $available;
                            $modelTotal = $reserved + $contracted + $blocked + $available;
                            $grandTotal += $modelTotal;
                        @endphp
                        <tr>
                            <td style="border: 1px solid #ccc;">{{ $model }}</td>
                            <td style="border: 1px solid #ccc;">{{ $count }}</td>
                            <td style="border: 1px solid #ccc;">{{ $reserved }}</td>
                            <td style="border: 1px solid #ccc;">{{ $contracted }}</td>
                            <td style="border: 1px solid #ccc;">{{ $blocked }}</td>
                            <td style="border: 1px solid #ccc;">{{ $available }}</td>
                            <td style="border: 1px solid #ccc;">{{ $modelTotal }}</td>
                        </tr>
                        @endif
                    @endforeach
                @endif

                <!-- صف المجموع -->
                <tr style="background-color: #ffe082; font-weight: bold;">
                    <td style="border: 1px solid #ccc;">المجموع</td>
                    <td style="border: 1px solid #ccc;">{{ $totalVillas }}</td>
                    <td style="border: 1px solid #ccc;">{{ $totalReserved }}</td>
                    <td style="border: 1px solid #ccc;">{{ $totalContracted }}</td>
                    <td style="border: 1px solid #ccc;">{{ $totalBlocked }}</td>
                    <td style="border: 1px solid #ccc;">{{ $totalAvailable }}</td>
                    <td style="border: 1px solid #ccc;">{{ $grandTotal }}</td>
                </tr>
            </tbody>
        </table>
        @else
        <div style="text-align: center; padding: 20px; color: #8b5a3b; font-weight: bold;">
            {{ __('components.unit_sales.no_data') }}
        </div>
        @endif
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
