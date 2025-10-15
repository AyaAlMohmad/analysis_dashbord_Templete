@extends('layouts.app')

@section('content')
    <div class="py-12">
        <style>
            .pagination {
                display: flex;
                justify-content: center;
                margin-top: 2rem;
                list-style: none;
                padding: 0;
            }

            .pagination li {
                margin: 0 4px;
            }

            .pagination li a,
            .pagination li span {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 8px 14px;
                background-color: #f3f4f6;
                border-radius: 8px;
                font-size: 14px;
                color: #4b5563;
                text-decoration: none;
                transition: all 0.3s ease;
                min-width: 40px;
            }

            .pagination li a:hover {
                background-color: #3b82f6;
                color: white;
            }

            .pagination li.active span {
                background-color: #2563eb;
                color: white;
                font-weight: bold;
            }

            .pagination li.disabled span {
                opacity: 0.5;
                cursor: not-allowed;
            }

            .log-card {
                background-color: #f9fafb;
                border: 1px solid #e5e7eb;
                border-radius: 10px;
                margin-bottom: 16px;
                overflow: hidden;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            }

            .log-header {
                background-color: #f3f4f6;
                padding: 14px 18px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                cursor: pointer;
            }

            .log-info {
                display: flex;
                align-items: center;
                gap: 12px;
                flex-wrap: wrap;
            }

            .log-action {
                padding: 6px 12px;
                border-radius: 9999px;
                font-size: 12px;
                font-weight: bold;
            }

            .log-action.insert {
                background-color: #d1fae5;
                color: #065f46;
            }

            .log-action.update {
                background-color: #fef9c3;
                color: #92400e;
            }

            .log-action.delete {
                background-color: #fee2e2;
                color: #991b1b;
            }

            .log-meta {
                font-size: 14px;
                color: #6b7280;
            }

            .user-name {
                font-weight: bold;
                color: #3b82f6;
            }

            .log-arrow {
                font-size: 20px;
                color: #9ca3af;
                transition: transform 0.3s ease;
            }

            .log-body {
                background-color: #ffffff;
                padding: 0 18px 18px 18px;
                overflow: hidden;
                max-height: 0;
                transition: max-height 0.5s ease, opacity 0.5s ease;
                opacity: 0;
            }

            .log-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 12px;
            }

            .log-table th,
            .log-table td {
                padding: 10px;
                border: 1px solid #e5e7eb;
                text-align: center;
                font-size: 14px;
            }

            .new-value {
                background-color: #d1fae5;
                color: #065f46;
                padding: 6px 10px;
                border-radius: 6px;
                font-weight: bold;
            }

            .old-value {
                background-color: #fee2e2;
                color: #991b1b;
                padding: 6px 10px;
                border-radius: 6px;
                font-weight: bold;
            }

            .unchanged-value {
                background-color: #f3f4f6;
                color: #6b7280;
                padding: 6px 10px;
                border-radius: 6px;
            }

            .log-body.show {
                max-height: 1000px;
                opacity: 1;
            }

            .rotate-180 {
                transform: rotate(180deg);
            }

            .field-name {
                font-weight: bold;
                color: #374151;
            }
        </style>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                @if (session('success'))
                    <div style="background-color:#d1fae5; color:#166534; padding:12px; border-radius:8px; margin-bottom:20px;">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div style="background-color:#fee2e2; color:#991b1b; padding:12px; border-radius:8px; margin-bottom:20px;">
                        {{ session('error') }}
                    </div>
                @endif

                <h1 class="text-2xl font-bold text-center text-gray-800 mb-8">
                    {{ __('leads_log.title', ['site' => ucfirst($site)]) }}
                </h1>

  <div class="flex flex-wrap justify-center mb-8 gap-4">
    <a href="{{ route('admin.leads.log', $site) }}"
      >
        {{ __('leads_log.update_data') }}
    </a>
    <a href="{{ route('admin.leads.statistics', $site) }}"
     >
        <i class="fas fa-chart-bar mr-2"></i> {{ __('leads_log.view_analysis') }}
    </a>
</div>

                <div class="space-y-4">
                    @foreach ($logs as $index => $log)
                        <div class="log-card">
                            <div class="log-header" onclick="toggleLog('{{ $index }}')">
                                <div class="log-info">
                                    <span class="log-action {{ $log->action }}">
                                        {{ ucfirst($log->action) }}
                                    </span>
                              @if($log->arabic_user_name)
    <span class="user-name">
        {{ $log->arabic_user_name }}
    </span>
@endif
                                    <span class="log-meta">
                                        {{ $log->created_at->format('Y-m-d H:i') }} | Changed by: {{ $log->changed_by }}
                                    </span>
                                    <span class="log-meta">
                                        Record ID: {{ $log->record_id }}
                                    </span>
                                </div>
                                <div id="arrow-{{ $index }}" class="log-arrow">▼</div>
                            </div>

                            <div id="log-{{ $index }}" class="log-body">
                                @php
                                    // Get all unique fields from both data_old and data_new
                                    $allFields = array_unique(array_merge(
                                        array_keys($log->data_old ?? []),
                                        array_keys($log->data_new ?? [])
                                    ));

                                    // Sort fields alphabetically for better organization
                                    sort($allFields);
                                @endphp

                                <table class="log-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 25%;">{{ __('leads_log.field') }}</th>
                                            <th style="width: 37.5%;">{{ __('leads_log.old_value') }}</th>
                                            <th style="width: 37.5%;">{{ __('leads_log.new_value') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($allFields as $field)
                                            @php
                                                $old = $log->data_old[$field] ?? null;
                                                $new = $log->data_new[$field] ?? null;
                                                $hasChanged = $old !== $new;

                                                // Format values for display
                                                $formatValue = function($value) {
                                                    if (is_array($value)) {
                                                        return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                                                    }
                                                    if ($value === null || $value === '') {
                                                        return __('leads_log.not_available');
                                                    }
                                                    if (is_bool($value)) {
                                                        return $value ? 'true' : 'false';
                                                    }
                                                    return $value;
                                                };

                                                $formattedOld = $formatValue($old);
                                                $formattedNew = $formatValue($new);
                                            @endphp
                                            <tr>
                                                <td class="field-name">{{ $field }}</td>
                                                <td>
                                                    <div class="{{ $hasChanged ? 'old-value' : 'unchanged-value' }}">
                                                        {{ $formattedOld }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="{{ $hasChanged ? 'new-value' : 'unchanged-value' }}">
                                                        {{ $formattedNew }}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pagination">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleLog(index) {
            const logContent = document.getElementById(`log-${index}`);
            const arrow = document.getElementById(`arrow-${index}`);

            logContent.classList.toggle('show');
            arrow.classList.toggle('rotate-180');
        }

        // Auto-expand if there's only one log for better UX
        document.addEventListener('DOMContentLoaded', function() {
            const logCards = document.querySelectorAll('.log-card');
            if (logCards.length === 1) {
                toggleLog(0);
            }
        });
    </script>
@endsection
