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
                style="width: 100%; max-width: 700px; border-collapse: collapse; font-size: 13px; font-family: 'Arial', sans-serif; text-align: center; background-color: #fff; margin: auto;">
                <thead>
                    <tr style="background-color: #8b5a3b; color: white;">
                        <th rowspan="2" style="padding: 8px; border: 1px solid #ccc;">{{ __('components.stage') }}</th>
                        <th rowspan="2" style="padding: 8px; border: 1px solid #ccc;">{{ __('components.units_per_stage') }}</th>
                        <th colspan="2" style="padding: 8px; border: 1px solid #ccc;">{{ __('components.reserved') }}</th>
                        <th colspan="2" style="padding: 8px; border: 1px solid #ccc;">{{ __('components.contracted') }}</th>
                        <th colspan="2" style="padding: 8px; border: 1px solid #ccc;">{{ __('components.available') }}</th>
                        <th colspan="2" style="padding: 8px; border: 1px solid #ccc;">{{ __('components.hidden') }}</th>
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
                    @foreach($statusData['data']['groups'] as $group)
                        @php
                            $available = collect($group['statuses'])->firstWhere('status_name', 'available');
                            $contracted = collect($group['statuses'])->firstWhere('status_name', 'contracted');
                            $reserved = collect($group['statuses'])->firstWhere('status_name', 'reserved');
                            $hidden = collect($group['statuses'])->firstWhere('status_name', 'hidden');
                        @endphp
                        <tr style="background-color: {{ $loop->odd ? '#f9f9f9' : '#ffffff' }};">
                            <td style="border: 1px solid #ccc;">{{ $group['group_id'] }}</td>
                            <td style="border: 1px solid #ccc;">{{ $group['total_items'] }}</td>
            
                            <td style="border: 1px solid #ccc;">{{ $reserved['beneficiary'] ?? 0 }}</td>
                            <td style="border: 1px solid #ccc;">{{ $reserved['non_beneficiary'] ?? 0 }}</td>
            
                            <td style="border: 1px solid #ccc;">{{ $contracted['beneficiary'] ?? 0 }}</td>
                            <td style="border: 1px solid #ccc;">{{ $contracted['non_beneficiary'] ?? 0 }}</td>
            
                            <td style="border: 1px solid #ccc;">{{ $available['beneficiary'] ?? 0 }}</td>
                            <td style="border: 1px solid #ccc;">{{ $available['non_beneficiary'] ?? 0 }}</td>
            
                            <td style="border: 1px solid #ccc;">{{ $hidden['beneficiary'] ?? 0 }}</td>
                            <td style="border: 1px solid #ccc;">{{ $hidden['non_beneficiary'] ?? 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            
                <tfoot>
                    @php
                        $totals = $statusData['data']['totals'];
                        $statusTotals = $totals['status_totals'];
                    @endphp
                    <tr style="background-color: #ffe082; font-weight: bold;">
                        <td style="border: 1px solid #ccc;">{{ __('components.total') }}</td>
                        <td style="border: 1px solid #ccc;">{{ $totals['total_items'] }}</td>
            
                        <td style="border: 1px solid #ccc;">{{ $statusTotals['reserved']['beneficiary'] ?? 0 }}</td>
                        <td style="border: 1px solid #ccc;">{{ $statusTotals['reserved']['non_beneficiary'] ?? 0 }}</td>
            
                        <td style="border: 1px solid #ccc;">{{ $statusTotals['contracted']['beneficiary'] ?? 0 }}</td>
                        <td style="border: 1px solid #ccc;">{{ $statusTotals['contracted']['non_beneficiary'] ?? 0 }}</td>
            
                        <td style="border: 1px solid #ccc;">{{ $statusTotals['available']['beneficiary'] ?? 0 }}</td>
                        <td style="border: 1px solid #ccc;">{{ $statusTotals['available']['non_beneficiary'] ?? 0 }}</td>
            
                        <td style="border: 1px solid #ccc;">{{ $statusTotals['hidden']['beneficiary'] ?? 0 }}</td>
                        <td style="border: 1px solid #ccc;">{{ $statusTotals['hidden']['non_beneficiary'] ?? 0 }}</td>
                    </tr>
            
                    <tr>
                        <td colspan="2" style="border: none;"></td>
                        <td colspan="2" style="padding: 8px; border: 1px solid #ccc; font-weight: bold;">
                            {{ ($statusTotals['reserved']['beneficiary'] ?? 0) + ($statusTotals['reserved']['non_beneficiary'] ?? 0) }}
                        </td>
                        <td colspan="2" style="padding: 8px; border: 1px solid #ccc; font-weight: bold;">
                            {{ ($statusTotals['contracted']['beneficiary'] ?? 0) + ($statusTotals['contracted']['non_beneficiary'] ?? 0) }}
                        </td>
                        <td colspan="4" style="border: none;"></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border: none;"></td>
                        <td colspan="4" style="padding: 8px; border: 1px solid #ccc; font-weight: bold;">
                            {{
                                ($statusTotals['reserved']['beneficiary'] ?? 0)
                                + ($statusTotals['reserved']['non_beneficiary'] ?? 0)
                                + ($statusTotals['contracted']['beneficiary'] ?? 0)
                                + ($statusTotals['contracted']['non_beneficiary'] ?? 0)
                            }}
                        </td>
                        <td colspan="4" style="border: none;"></td>
                    </tr>
                </tfoot>
            </table>
            


            </div>
        </div>
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