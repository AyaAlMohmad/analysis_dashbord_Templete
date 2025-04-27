@extends('layouts.app')

@section('content')
    <div class="py-12">
        <style>
            /* (CSS محفوظ كما طلبت، بدون أي تغيير) */
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

            .log-body.show {
                max-height: 500px;
                opacity: 1;
            }

            .rotate-180 {
                transform: rotate(180deg);
            }
        </style>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <h1 class="text-2xl font-bold text-center text-gray-800 mb-8">Change Log - {{ $site }}</h1>

                <!-- أزرار Update و View Analysis -->
                <div class="flex flex-wrap justify-center mb-8 gap-4">
                    <a href="{{ route('admin.appointments.log', $site) }}" style="color: blue">
                        🔄 Update Data
                    </a>
                    <a href="{{ route('admin.appointments.statistics', $site) }}" class="text-blue-500 hover:text-blue-600">
                        <i class="fas fa-chart-bar mr-2"></i>View Analysis
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
                                    <span class="log-meta">
                                        {{ $log->created_at->format('Y-m-d H:i') }} by {{ $log->changed_by }}
                                    </span>
                                </div>
                                <div id="arrow-{{ $index }}" class="log-arrow">▼</div>
                            </div>


                            <div id="log-{{ $index }}" class="log-body">
                                <table class="log-table">
                                    <thead>
                                        <tr>
                                            <th>Field</th>
                                            <th>New Value</th>
                                            <th>Old Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $fields = [
                                                'description' => 'Description',
                                                'date' => 'Date',
                                                'address' => 'Address',
                                                'reminder_before' => 'Reminder Before',
                                                'reminder_before_type' => 'Reminder Before Type',
                                                'name' => 'Name',
                                                'email' => 'Email',
                                                'phone' => 'Phone',
                                                'source' => 'Source',
                                                'notes' => 'Notes',
                                                'approved' => 'Approved',
                                                'start_hour' => 'Start Hour',
                                                'recurring' => 'Recurring',
                                            ];
                                        @endphp

                                        @foreach ($fields as $field => $label)
                                            @php
                                                $old = $log->data_old[$field] ?? null;
                                                $new = $log->data_new[$field] ?? null;
                                            @endphp
                                            <tr>
                                                <td>{{ $label }}</td>
                                                <td>
                                                    <div class="{{ $old !== $new ? 'new-value' : '' }}">
                                                        {{ is_array($new) ? json_encode($new) : $new ?? 'Not Available' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="{{ $old !== $new ? 'old-value' : '' }}">
                                                        {{ is_array($old) ? json_encode($old) : $old ?? 'Not Available' }}
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

                <div class="pagination mt-8">
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
    </script>
@endsection
