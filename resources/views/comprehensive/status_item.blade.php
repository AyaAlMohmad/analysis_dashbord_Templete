<div
    style="background-color: #f9f6f2; padding: 60px 20px; font-family: 'Arial', sans-serif; direction: rtl; position: relative;">

    <div style="position: absolute; top: 30px; right: 30px;">
        <img src="{{ asset('build/logo.png') }}" alt="Tatwir Logo" style="height: 50px;">
    </div>

    <div style="text-align: center; margin-bottom: 40px;">
        <h2 style="font-size: 28px; color: #8b5a3b; border-bottom: 2px solid #8b5a3b; display: inline-block;">
            {{__('messages.unit_cases')}}
        </h2>
    </div>

    <div
        style="display: flex; gap: 20px; flex-direction: row-reverse; justify-content: space-between; align-items: flex-start;">

        <div style="flex: 0 0 auto; min-width: 100px; position: absolute; top: 100px;">
            <img src="{{ asset('images/style2.png') }}" alt="Decoration" style="height: 500px;">
        </div>

        <div style="flex: 1; max-width: 100%; overflow-x: auto; display: flex; justify-content: center;">
            <table
                style="width: 100%; max-width: 800px; border-collapse: collapse; font-size: 13px; font-family: 'Arial', sans-serif; text-align: center; background-color: #fff; margin: auto;">
                <thead>
                    <tr style="background-color: #8b5a3b; color: white;">
                        <th rowspan="2" style="padding: 8px; border: 1px solid #ccc;">{{ __('components.stage') }}</th>
                        <th rowspan="2" style="padding: 8px; border: 1px solid #ccc;">
                            {{ __('components.units_per_stage') }}
                        </th>
                        <th colspan="2" style="padding: 8px; border: 1px solid #ccc;">{{ __('components.reserved') }}
                        </th>
                        <th colspan="2" style="padding: 8px; border: 1px solid #ccc;">{{ __('components.contracted') }}
                        </th>
                        <th colspan="2" style="padding: 8px; border: 1px solid #ccc;">{{ __('components.available') }}
                        </th>
                        <th colspan="2" style="padding: 8px; border: 1px solid #ccc;">{{ __('components.blocked') }}
                        </th>
                    </tr>
                    <tr style="background-color: #8b5a3b; color: white;">
                        <th style="padding: 6px; border: 1px solid #ccc;">{{ __('components.beneficiary') }}</th>
                        <th style="padding: 6px; border: 1px solid #ccc;">{{ __('components.non_beneficiary') }}</th>
                        <th style="padding: 6px; border: 1px solid #ccc;">{{ __('components.beneficiary') }}</th>
                        <th style="padding: 6px; border: 1px solid #ccc;">{{ __('components.non_beneficiary') }}</th>
                        <th style="padding: 6px; border: 1px solid #ccc;">{{ __('components.beneficiary') }}</th>
                        <th style="padding: 6px; border: 1px solid #ccc;">{{ __('components.non_beneficiary') }}</th>
                        <th style="padding: 6px; border: 1px solid #ccc;">{{ __('components.beneficiary') }}</th>
                        <th style="padding: 6px; border: 1px solid #ccc;">{{ __('components.non_beneficiary') }}</th>
                    </tr>
                </thead>
                
                <tbody>
                    @if(isset($statusData['groups']) && is_array($statusData['groups']))
                        @foreach($statusData['groups'] as $group)
                            @php
                                $available = collect($group['statuses'])->firstWhere('status_name', 'available');
                                $reserved = collect($group['statuses'])->firstWhere('status_name', 'reserved');
                                $contracted = collect($group['statuses'])->firstWhere('status_name', 'contracted');
                                $blocked = collect($group['statuses'])->firstWhere('status_name', 'blocked');
                            @endphp
                            <tr style="background-color: {{ $loop->odd ? '#f9f9f9' : '#ffffff' }};">
                                <td style="border: 1px solid #ccc;">{{ $group['group_id'] }}</td>
                                <td style="border: 1px solid #ccc;">{{ $group['total_items'] }}</td>
                                <td style="border: 1px solid #ccc;">{{ $reserved['beneficiary'] ?? 0 }}</td>
                                <td style="border: 1px solid #ccc;">{{ $reserved['non_beneficiary'] ?? 0 }}</td>
                                <!-- باقي الخلايا -->
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" style="text-align: center; border: 1px solid #ccc;">
                                لا توجد بيانات متاحة
                            </td>
                        </tr>
                    @endif
                </tbody>

                <tfoot>
                    @php
                        $totals = $statusData['totals'] ?? [];
                        $statusTotals = $totals['status_totals'] ?? [];
                    @endphp

                    <tr style="background-color: #ffe082; font-weight: bold;">
                        <td style="border: 1px solid #ccc;">{{ __('components.total') }}</td>
                        <td style="border: 1px solid #ccc;">{{ $totals['total_items'] ?? 0 }}</td>

                        <td style="border: 1px solid #ccc;">{{ $statusTotals['reserved']['beneficiary'] ?? 0 }}</td>
                        <td style="border: 1px solid #ccc;">{{ $statusTotals['reserved']['non_beneficiary'] ?? 0 }}</td>

                        <td style="border: 1px solid #ccc;">{{ $statusTotals['contracted']['beneficiary'] ?? 0 }}</td>
                        <td style="border: 1px solid #ccc;">{{ $statusTotals['contracted']['non_beneficiary'] ?? 0 }}
                        </td>

                        <td style="border: 1px solid #ccc;">{{ $statusTotals['available']['beneficiary'] ?? 0 }}</td>
                        <td style="border: 1px solid #ccc;">{{ $statusTotals['available']['non_beneficiary'] ?? 0 }}
                        </td>

                        <td style="border: 1px solid #ccc;">{{ $statusTotals['blocked']['beneficiary'] ?? 0 }}</td>
                        <td style="border: 1px solid #ccc;">{{ $statusTotals['blocked']['non_beneficiary'] ?? 0 }}</td>
                    </tr>


                    <tr>
                        <td colspan="2" style="border: none;"></td>
                        <td colspan="2" style="padding: 8px; border: 1px solid #ccc; font-weight: bold;">
                            {{ ($statusTotals['reserved']['beneficiary'] ?? 0) + ($statusTotals['reserved']['non_beneficiary'] ?? 0) }}
                        </td>
                        <td colspan="2" style="padding: 8px; border: 1px solid #ccc; font-weight: bold;">
                            {{ ($statusTotals['contracted']['beneficiary'] ?? 0) + ($statusTotals['contracted']['non_beneficiary'] ?? 0) }}
                        </td>
                        <td colspan="2" style="padding: 8px; border: 1px solid #ccc; font-weight: bold;">
                            {{ ($statusTotals['available']['beneficiary'] ?? 0) + ($statusTotals['available']['non_beneficiary'] ?? 0) }}
                        </td>
                        <td colspan="2" style="border: none;"></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border: none;"></td>
                        <td colspan="6" style="padding: 8px; border: 1px solid #ccc; font-weight: bold;">
                            {{
    ($statusTotals['reserved']['beneficiary'] ?? 0)
    + ($statusTotals['reserved']['non_beneficiary'] ?? 0)
    + ($statusTotals['contracted']['beneficiary'] ?? 0)
    + ($statusTotals['contracted']['non_beneficiary'] ?? 0)
    + ($statusTotals['available']['beneficiary'] ?? 0)
    + ($statusTotals['available']['non_beneficiary'] ?? 0)
                            }}
                        </td>
                        <td colspan="2" style="border: none;"></td>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>
    <div style="position: absolute; right: 30px; bottom: 30px;">
        @if (isset($project_name) && $project_name == 'أزيان الظهران')
            <img src="{{ asset('images/logo5.png') }}" alt="Azyan Logo Dhahran" style="height: 50px;">
        @elseif(isset($project_name) && $project_name == 'أزيان البشائر')
            <img src="{{ asset('images/logo6.png') }}" alt="Azyan Logo Albashaer" style="height: 50px;">
        @elseif(isset($project_name) && $project_name == 'أزيان جدة')
            <img src="{{ asset('images/jadah.png') }}" alt="Azyan Logo Jeddah" style="height: 50px;">
        @elseif(isset($project_name) && $project_name == 'أزيان الفرسان')
            <img src="{{ asset('images/alfursan.png') }}" alt="Azyan Logo Farsan" style="height: 50px;">
        @elseif (!empty($logo) && file_exists(public_path('storage/' . $logo)))
            <img src="{{ asset('storage/' . $logo) }}" alt="Site Logo" style="height: 50px;">
        @else
            <span style="font-size: 14px; color: #8b5a3b; font-weight: bold;">{{ $project_name }}</span>
        @endif
    </div>
</div>