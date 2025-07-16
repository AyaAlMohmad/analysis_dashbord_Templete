<div style="background-color: #f9f6f2; padding: 60px 20px; font-family: 'Arial', sans-serif; direction: rtl; position: relative;">

    <!-- Logo Top -->
    <div style="position: absolute; top: 30px; right: 30px;">
        <img src="{{ asset('build/logo.png') }}" alt="Tatwir Logo" style="height: 50px;">
    </div>

    <!-- Title -->
    <div style="text-align: center; margin-bottom: 40px;">
        <h2 style="font-size: 28px; color: #8b5a3b; border-bottom: 2px solid #8b5a3b; display: inline-block;">
            {{__('messages.source_stats') }} {{ now()->format('F') }}
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
                        <th style="border: 1px solid #ccc;">{{ __('components.source') }}</th>
                        <th style="border: 1px solid #ccc;"> {{__('components.number_of_customers')}} </th>
                        <th style="border: 1px solid #ccc;"> {{__('components.visits')}}</th>
                        <th style="border: 1px solid #ccc;">  {{__('components.visit_rate_from_customers')}} </th>
                        <th style="border: 1px solid #ccc;">{{__('components.registrations')}}</th>
                        <th style="border: 1px solid #ccc;"> {{__('components.registration_rate_from_visits')}}  </th>
                        <th style="border: 1px solid #ccc;">{{__('components.contracts')}}</th>
                        <th style="border: 1px solid #ccc;"> {{__('components.contract_rate_from_registrations')}}  </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['sources'] as $source)
                        <tr @if(str_contains($source['source_name'], 'WA-Interested')) style="background-color: #e0f2f1;" @endif>
                            <td style="border: 1px solid #ccc;">{{ $source['source_name'] }}</td>
                            <td style="border: 1px solid #ccc;">{{ $source['total_leads'] }}</td>
                            <td style="border: 1px solid #ccc;">{{ $source['visited_leads'] }}</td>
                            <td style="border: 1px solid #ccc;">{{ number_format($source['visited_percent'], 2) }}%</td>
                            <td style="border: 1px solid #ccc;">{{ $source['paid_leads'] }}</td>
                            <td style="border: 1px solid #ccc;">{{ number_format($source['paid_from_visited_percent'], 2) }}%</td>
                            <td style="border: 1px solid #ccc;">{{ $source['contract_leads'] }}</td>
                            <td style="border: 1px solid #ccc;">{{ number_format($source['contract_from_paid_percent'], 2) }}%</td>
                        </tr>
                    @endforeach
                    <tr style="font-weight: bold; background-color: #ffe082;">
                        <td style="border: 1px solid #ccc;">{{__('components.total')}}</td>
                        <td style="border: 1px solid #ccc;">{{ $data['totals']['total_leads'] }}</td>
                        <td style="border: 1px solid #ccc;">{{ $data['totals']['visited_leads'] }}</td>
                        <td style="border: 1px solid #ccc;">{{ number_format($data['totals']['visited_percent'], 2) }}%</td>
                        <td style="border: 1px solid #ccc;">{{ $data['totals']['paid_leads'] }}</td>
                        <td style="border: 1px solid #ccc;">{{ number_format($data['totals']['paid_from_visited_percent'], 2) }}%</td>
                        <td style="border: 1px solid #ccc;">{{ $data['totals']['contract_leads'] }}</td>
                        <td style="border: 1px solid #ccc;">{{ number_format($data['totals']['contract_from_paid_percent'], 2) }}%</td>
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
