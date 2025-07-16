<div style="background-color: #f9f6f2; padding: 60px 20px; font-family: 'Arial', sans-serif; direction: rtl; position: relative;">

    <!-- Logo Top -->
    <div style="position: absolute; top: 30px; right: 30px;">
        <img src="{{ asset('build/logo.png') }}" alt="Tatwir Logo" style="height: 50px;">
    </div>

    <!-- Title -->
    <div style="text-align: center; margin-bottom: 40px;">
        <h2 style="font-size: 28px; color: #8b5a3b; border-bottom: 2px solid #8b5a3b; display: inline-block;">
       {{__('messages.disinterest_reasons')}}

         {{ date('F') }}

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



             <table style="width: 100%; border-collapse: collapse; margin-top: 20px; background-color: white;">
                <thead style="background-color: #ffe082; font-weight: bold;">
                    <tr>
                        <th style="border: 1px solid #ccc; width: 40%;">{{__('components.disinterest_reasons')}}</th>
                        <th style="border: 1px solid #ccc; width: 30%;">{{__('components.number_of_clients')}}</th>
                        <th style="border: 1px solid #ccc; width: 30%;">{{__('components.percentage_of_total')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalCount = $data['data']['total_leads'] ?? 0;
                        $displayedReasons = collect($data['data']['reasons'] ?? [])
                            ->sortByDesc('count');
                    @endphp

                    @foreach($displayedReasons as $reason)
                        <tr style="{{ $reason['count'] == 0 ? 'color: #999;' : '' }}">
                            <td style="border: 1px solid #ccc; text-align: right;">{{ $reason['reason'] }}</td>
                            <td style="border: 1px solid #ccc;">{{ $reason['count'] }}</td>
                            <td style="border: 1px solid #ccc;">
                                {{ number_format($reason['percentage'], 2) }}%
                                @if($reason['count'] > 0)
                                    <div style="background: #f0f0f0; height: 10px; border-radius: 5px; margin-top: 3px;">
                                        <div style="background: #8b5a3b; height: 100%; width: {{ $reason['percentage'] }}%; border-radius: 5px;"></div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    @if(empty($data['data']['reasons']))
                        <tr>
                            <td colspan="3" style="border: 1px solid #ccc; padding: 15px; color: #666;">
                              {{__('messages.no_data_available')}}
                            </td>
                        </tr>
                    @endif

                    <tr style="font-weight: bold; background-color: #ffe082;">
                        <td style="border: 1px solid #ccc; padding: 10px;">{{__('components.total')}}</td>
                        <td style="border: 1px solid #ccc; padding: 10px;">{{ $totalCount }}</td>
                        <td style="border: 1px solid #ccc; padding: 10px;">100%</td>
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
            @elseif (!empty($logo) && file_exists(public_path('storage/' . $logo)))
            <img src="{{ asset('storage/' . $logo) }}" alt="Site Logo" style="height: 50px;">
        @else
            <span style="font-size: 14px; color: #8b5a3b; font-weight: bold;">{{ $project_name }}</span>
        @endif

    </div>
</div>
