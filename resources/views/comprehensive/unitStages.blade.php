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


        @if(isset($unitStages['A']) && isset($unitStages['B']) && isset($unitStages['C']) &&
            isset($unitStages['D']) && isset($unitStages['E']) && isset($unitStages['F']))

        <!-- First Table -->
        <table style="width: 90%; max-width: 800px; border-collapse: collapse; font-size: 12px; font-family: 'Arial', sans-serif; text-align: center; background-color: white; border: 1px solid #ccc;">
            <thead>
                <tr style="background-color: #fff; font-weight: bold; color: #333;">
                    <th style="border: 1px solid #ccc; width: 80px;">{{ __('components.unit_sales.prices') }}</th>
                    @foreach(['A', 'B', 'C', 'D', 'E', 'F'] as $model)
                    <th style="border: 1px solid #ccc;">{{ number_format($unitStages[$model]['min_price'] ?? 0) }}</th>
                    @endforeach
                </tr>
                <tr style="background-color: #ffe082; font-weight: bold;">
                    <th style="border: 1px solid #ccc;">{{ __('components.unit_sales.models') }}</th>
                    <th style="border: 1px solid #ccc;">A<br>{{__('components.abq')}}</th>
                    <th style="border: 1px solid #ccc;">B<br>{{__('components.ewan')}}</th>
                    <th style="border: 1px solid #ccc;">C<br>{{__('components.najdiyah')}}</th>
                    <th style="border: 1px solid #ccc;">D<br>{{__('components.ruweiq')}}</th>
                    <th style="border: 1px solid #ccc;">E<br>{{__('components.maqam')}}</th>
                    <th style="border: 1px solid #ccc;">F<br>{{__('components.roof')}}</th>
                    <th style="border: 1px solid #ccc; background-color: #ffe082;">{{ __('components.unit_sales.total') }}</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td style="border: 1px solid #ccc; background-color: #ffe082;">{{ __('components.unit_sales.villas_count') }}</td>
                    @php
                        $totalVillas = 0;
                    @endphp
                    @foreach(['A', 'B', 'C', 'D', 'E', 'F'] as $model)
                        @php
                            $count = $unitStages[$model]['count'] ?? 0;
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
                    @foreach(['A', 'B', 'C', 'D', 'E', 'F'] as $model)
                        @php
                            $reserved = $unitStages[$model]['status_counts']['reserved'] ?? 0;
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
                    @foreach(['A', 'B', 'C', 'D', 'E', 'F'] as $model)
                        @php
                            $contracted = $unitStages[$model]['status_counts']['contracted'] ?? 0;
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
                    @foreach(['A', 'B', 'C', 'D', 'E', 'F'] as $model)
                        @php
                            $blocked = $unitStages[$model]['status_counts']['blocked'] ?? 0;
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
                    @foreach(['A', 'B', 'C', 'D', 'E', 'F'] as $model)
                        @php
                            $available = $unitStages[$model]['status_counts']['available'] ?? 0;
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
                    @foreach(['A', 'B', 'C', 'D', 'E', 'F'] as $model)
                        @php
                            $modelTotal = ($unitStages[$model]['status_counts']['reserved'] ?? 0)
                                        + ($unitStages[$model]['status_counts']['contracted'] ?? 0)
                                        + ($unitStages[$model]['status_counts']['blocked'] ?? 0)
                                        + ($unitStages[$model]['status_counts']['available'] ?? 0);
                            $grandTotal += $modelTotal;
                        @endphp
                        <td style="border: 1px solid #ccc;">{{ $modelTotal }}</td>
                    @endforeach
                    <td style="border: 1px solid #ccc; font-weight: bold;">{{ $grandTotal }}</td>
                </tr>


                <tr style="background-color: #8b5a3b; color: white; font-weight: bold;">
                    <td style="border: 1px solid #ccc;">{{ __('components.unit_sales.final_contracted') }}</td>
                    @foreach(['A', 'B', 'C', 'D', 'E', 'F'] as $model)
                        @php
                            $modelTotal = ($unitStages[$model]['status_counts']['reserved'] ?? 0)
                                        + ($unitStages[$model]['status_counts']['contracted'] ?? 0)
                                        + ($unitStages[$model]['status_counts']['blocked'] ?? 0);
                            $contracted = $unitStages[$model]['status_counts']['contracted'] ?? 0;
                            $percentage = $modelTotal > 0 ? round(($contracted / $modelTotal) * 100) : 0;
                        @endphp
                        <td style="border: 1px solid #ccc;">{{ $percentage }}%</td>
                    @endforeach
                    <td style="border: 1px solid #ccc;">-</td>
                </tr>

                <tr style="background-color: #8b5a3b; color: white; font-weight: bold;">
                    <td style="border: 1px solid #ccc;">{{ __('components.unit_sales.model_percentage') }}</td>
                    @foreach(['A', 'B', 'C', 'D', 'E', 'F'] as $model)
                        @php
                            $modelTotal = ($unitStages[$model]['status_counts']['reserved'] ?? 0)
                                        + ($unitStages[$model]['status_counts']['contracted'] ?? 0)
                                        + ($unitStages[$model]['status_counts']['blocked'] ?? 0);
                            $percentage = $grandTotal > 0 ? round(($modelTotal / $grandTotal) * 100) : 0;
                        @endphp
                        <td style="border: 1px solid #ccc;">{{ $percentage }}%</td>
                    @endforeach
                    <td style="border: 1px solid #ccc;">-</td>
                </tr>
            </tbody>
        </table>

        <!-- Second Table -->
        <table style="width: 50%; direction: rtl; border-collapse: collapse; font-size: 13px; font-family: 'Arial'; text-align: center; border: 1px solid #ccc;">
            @php
                $totalSoldReserved = $totalReserved + $totalContracted;
                $group1 = ($unitStages['A']['status_counts']['reserved'] ?? 0) + ($unitStages['A']['status_counts']['contracted'] ?? 0)
                        + ($unitStages['B']['status_counts']['reserved'] ?? 0) + ($unitStages['B']['status_counts']['contracted'] ?? 0);
                $group2 = ($unitStages['C']['status_counts']['reserved'] ?? 0) + ($unitStages['C']['status_counts']['contracted'] ?? 0)
                        + ($unitStages['D']['status_counts']['reserved'] ?? 0) + ($unitStages['D']['status_counts']['contracted'] ?? 0);
                $group3 = ($unitStages['E']['status_counts']['reserved'] ?? 0) + ($unitStages['E']['status_counts']['contracted'] ?? 0)
                        + ($unitStages['F']['status_counts']['reserved'] ?? 0) + ($unitStages['F']['status_counts']['contracted'] ?? 0);
            @endphp

            <tr style="background-color: #5e3d2c; color: white; font-weight: bold;">
                <td style="border: 1px solid #ccc; text-align: right;">{{ __('components.unit_sales.group_sales.group1') }}</td>
                <td style="border: 1px solid #ccc; background-color: #ffe082; color: black;">{{ $group1 }}</td>

                <td style="border: 1px solid #ccc;">{{ __('components.unit_sales.group_sales.percentage') }} {{ $group1 }}</td>
                <td style="border: 1px solid #ccc; background-color: #ffe082; color: black;">
                    {{ $totalSoldReserved > 0 ? round(($group1 / $totalSoldReserved) * 100, 1) : 0 }}%
                </td>
            </tr>
            <tr style="background-color: #5e3d2c; color: white; font-weight: bold;">
                <td style="border: 1px solid #ccc; text-align: right;">{{ __('components.unit_sales.group_sales.group2') }}</td>
                <td style="border: 1px solid #ccc; background-color: #ffe082; color: black;">{{ $group2 }}</td>

                <td style="border: 1px solid #ccc;">{{ __('components.unit_sales.group_sales.percentage') }} {{ $group2 }}</td>
                <td style="border: 1px solid #ccc; background-color: #ffe082; color: black;">
                    {{ $totalSoldReserved > 0 ? round(($group2 / $totalSoldReserved) * 100, 1) : 0 }}%
                </td>
            </tr>
            <tr style="background-color: #5e3d2c; color: white; font-weight: bold;">
                <td style="border: 1px solid #ccc; text-align: right;">{{ __('components.unit_sales.group_sales.group3') }}</td>
                <td style="border: 1px solid #ccc; background-color: #ffe082; color: black;">{{ $group3 }}</td>

                <td style="border: 1px solid #ccc;">{{ __('components.unit_sales.group_sales.percentage') }} {{ $group3 }}</td>
                <td style="border: 1px solid #ccc; background-color: #ffe082; color: black;">
                    {{ $totalSoldReserved > 0 ? round(($group3 / $totalSoldReserved) * 100, 1) : 0 }}%
                </td>
            </tr>
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
            @elseif (!empty($logo) && file_exists(public_path('storage/' . $logo)))
            <img src="{{ asset('storage/' . $logo) }}" alt="Site Logo" style="height: 50px;">
        @else
            <span style="font-size: 14px; color: #8b5a3b; font-weight: bold;">{{ $project_name }}</span>
        @endif

    </div>
</div>
