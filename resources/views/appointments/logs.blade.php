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

            .no-change {
                background-color: #f3f4f6;
                color: #6b7280;
                padding: 6px 10px;
                border-radius: 6px;
            }

            .value-badge {
                padding: 6px 10px;
                border-radius: 6px;
                font-weight: bold;
                display: inline-block;
                min-width: 60px;
            }

            /* Additional styles for the layout */
            .header-section {
                margin-bottom: 2rem;
            }

            .navigation-buttons {
                display: flex;
                gap: 1rem;
                justify-content: center;
                margin-bottom: 2rem;
            }

            .nav-btn {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.75rem 1.5rem;
                background-color: #3b82f6;
                color: white;
                border-radius: 0.5rem;
                text-decoration: none;
                transition: background-color 0.3s ease;
            }

            .nav-btn:hover {
                background-color: #2563eb;
            }

            .empty-state {
                text-align: center;
                padding: 3rem;
                color: #6b7280;
            }

            .empty-state i {
                font-size: 3rem;
                margin-bottom: 1rem;
                color: #d1d5db;
            }
        </style>

        <div class="log-container px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="header-section">
                <h1 class="text-3xl font-bold text-center mb-4">
                    {{ __('appointments_log.title') }} - {{ ucfirst($site) }}
                </h1>
                <p class="text-center text-blue-100 text-lg">
                    {{ __('appointments_log.subtitle') }}
                </p>
            </div>

            <!-- Navigation Buttons -->
            <div class="navigation-buttons">
                <a href="{{ route('admin.appointments.log', $site) }}" class="nav-btn">
                    <i class="fas fa-sync-alt"></i>
                    {{ __('appointments_log.update_data') }}
                </a>
                <a href="{{ route('admin.appointments.statistics', $site) }}" class="nav-btn">
                    <i class="fas fa-chart-bar"></i>
                    {{ __('appointments_log.view_analysis') }}
                </a>
            </div>

            <div class="space-y-4">
                @if($logs->count() > 0)
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
                                    // Define fields with their translations
                                    $fields = [
                                        'description' => __('appointments_log.description'),
                                        'date' => __('appointments_log.date'),
                                        'address' => __('appointments_log.address'),
                                        'reminder_before' => __('appointments_log.reminder_before'),
                                        'reminder_before_type' => __('appointments_log.reminder_before_type'),
                                        'name' => __('appointments_log.name'),
                                        'email' => __('appointments_log.email'),
                                        'phone' => __('appointments_log.phone'),
                                        'source' => __('appointments_log.source'),
                                        'notes' => __('appointments_log.notes'),
                                        'approved' => __('appointments_log.approved'),
                                        'start_hour' => __('appointments_log.start_hour'),
                                        'recurring' => __('appointments_log.recurring'),
                                    ];
                                @endphp

                                <div class="log-table-container">
                                    <table class="log-table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('appointments_log.field') }}</th>
                                                <th>{{ __('appointments_log.old_value') }}</th>
                                                <th>{{ __('appointments_log.new_value') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($fields as $field => $label)
                                                @php
                                                    $old = $log->data_old[$field] ?? null;
                                                    $new = $log->data_new[$field] ?? null;
                                                    $hasChanged = $old !== $new;
                                                @endphp
                                                <tr>
                                                    <td><strong>{{ $label }}</strong></td>
                                                    <td>
                                                        <span class="value-badge {{ $hasChanged ? 'old-value' : 'no-change' }}">
                                                            {{ $old ? (is_array($old) ? json_encode($old) : $old) : __('appointments_log.no_data') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="value-badge {{ $hasChanged ? 'new-value' : 'no-change' }}">
                                                            {{ $new ? (is_array($new) ? json_encode($new) : $new) : __('appointments_log.no_data') }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <i class="fas fa-clipboard-list"></i>
                        <h3 class="text-xl font-semibold mb-2">{{ __('appointments_log.no_logs') }}</h3>
                        <p class="text-gray-600">{{ __('appointments_log.no_logs_description') }}</p>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if($logs->count() > 0)
                <div class="pagination mt-6">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        function toggleLog(index) {
            const logContent = document.getElementById(`log-${index}`);
            const arrow = document.getElementById(`arrow-${index}`);

            logContent.classList.toggle('show');
            arrow.classList.toggle('rotate-180');
        }

        // Auto-expand first log if only one exists
        document.addEventListener('DOMContentLoaded', function() {
            const logs = document.querySelectorAll('.log-card');
            if (logs.length === 1) {
                toggleLog(0);
            }
        });
    </script>
@endsection
