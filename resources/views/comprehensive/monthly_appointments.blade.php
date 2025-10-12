<div
    style="background-color: #f9f6f2; padding: 60px 20px; font-family: 'Arial', sans-serif; direction: rtl; position: relative; min-height: 700px;">

    <!-- Logo Top -->
    <div style="position: absolute; top: 30px; right: 30px;">
        <img src="{{ asset('build/logo.png') }}" alt="Tatwir Logo" style="height: 50px;">
    </div>

    <!-- Report Title -->
    <div style="text-align: center; margin-bottom: 10px;">
        <h2
            style="font-size: 26px; color: #8b5a3b; border-bottom: 2px solid #8b5a3b; display: inline-block; padding-bottom: 5px;">
            {{ __('messages.monthly_appointments') }}
        </h2>
        <div style="font-size: 20px; color: #5c4033; margin-top: 10px;"> {{ __('components.month') }}
            {{ now()->format('F') }}</div>
    </div>

    <!-- Main Content -->
    <div
        style="display: flex; flex-direction: row-reverse; justify-content: center; align-items: flex-start; margin-top: 40px; gap: 80px;">

        <!-- Side Decoration -->
        <div style="flex: 0 0 auto; position: absolute; top: 120px; left: 0;">
            <img src="{{ asset('images/style2.png') }}" alt="Decoration" style="height: 500px;">
        </div>

        <!-- Chart Circle -->
        <div style="text-align: center;">
            <div style="position: relative; width: 200px; height: 200px; margin: auto;">
                @php
                    $totalAppointments = $data['total_appointments'] ?? 0;
                    $completedVisits = $data['completed_visits'] ?? 0;
                    $percentage = $totalAppointments > 0 ? ($completedVisits / $totalAppointments) * 100 : 0;
                    $dashArray = number_format($percentage, 2) . ', ' . number_format(100 - $percentage, 2);
                    $externalVisitLeadsCount = $data['external_visit_leads_count'] ?? 0;
                    $percentageExternalVisitLeads = $externalVisitLeadsCount > 0 ? ($completedVisits / $externalVisitLeadsCount) * 100 : 0;
                    $dashArrayExternalVisitLeads = number_format($percentageExternalVisitLeads, 2) . ', ' . number_format(100 - $percentageExternalVisitLeads, 2);
                @endphp

                <svg viewBox="0 0 36 36" style="width: 100%; height: auto;">

                    <path style="fill: none; stroke: #e6e6e6; stroke-width: 3.8;" d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 0 31.831
                            a 15.9155 15.9155 0 0 1 0 -31.831" />

                    <path style="fill: none; stroke: #a87b4e; stroke-width: 3.8; stroke-linecap: round;" d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 0 31.831
                            a 15.9155 15.9155 0 0 1 0 -31.831" stroke-dasharray="{{ $dashArray }}" />

                    <text x="18" y="20.35" text-anchor="middle" fill="#2e4631" font-size="6" font-weight="bold">
                        {{ number_format($percentage, 1) }}%
                    </text>
                </svg>


            </div>


            <div style="margin-top: 10px;">
                <span
                    style="display: inline-block; width: 12px; height: 12px; background-color: #a87b4e; margin-left: 5px;"></span>
                نسبة الإنجاز
                <span style="margin: 0 10px;"></span>
                <span
                    style="display: inline-block; width: 12px; height: 12px; background-color: #e6e6e6; margin-left: 5px;"></span>
                باقي الزيارات
            </div>
        </div>

        <!-- Table - Moved further to the right -->
        <div style="margin-right: 100px;"> <!-- Increased margin-right from 50px to 100px -->
            <table style="border-collapse: collapse; width: 300px; font-size: 14px; text-align: center;">
                <thead style="background-color: #8b5a3b; color: white;">
                    <tr>
                        <th style="padding: 10px; border: 1px solid #ccc;"> {{ __('components.appointments') }}</th>
                        <th style="padding: 10px; border: 1px solid #ccc;"> {{ __('components.visited') }}</th>
                        <th style="padding: 10px; border: 1px solid #ccc;"> {{ __('components.success_rate') }} </th>
                        <th style="padding: 10px; border: 1px solid #ccc;"> {{ __('components.external_visit_leads_count') }} </th>
                        <th style="padding: 10px; border: 1px solid #ccc;"> {{ __('components.external_visit_leads') }} </th>

                    </tr>
                </thead>
                <tbody>
                    <tr style="background-color: #fff;">
                        <td style="padding: 10px; border: 1px solid #ccc;">
                            {{ $totalAppointments }}
                        </td>
                        <td style="padding: 10px; border: 1px solid #ccc;">
                            {{ $completedVisits }}
                        </td>
                        <td style="padding: 10px; border: 1px solid #ccc;">
                            % {{ number_format($percentage, 2) }}
                        </td>
                        <td style="padding: 10px; border: 1px solid #ccc;">
                            {{ $data['external_visit_leads_count'] }}
                        </td>
                        <td style="padding: 10px; border: 1px solid #ccc;">
                            % {{ number_format($percentageExternalVisitLeads, 2) }}
                        </td>
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
