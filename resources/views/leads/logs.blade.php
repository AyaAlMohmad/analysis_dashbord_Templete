<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                @if (session('success'))
                    <div style="background-color:#d1fae5; color:#166534; padding:4px; border-radius:4px;">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div style="background-color:#fee2e2; color:#991b1b; padding:4px; border-radius:4px;">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="p-4 bg-white shadow-sm rounded-lg">
                    <h1 class="text-2xl font-bold text-gray-800 text-center">Change Log - {{ $site }}</h1>
                </div>

                <div class="flex items-center justify-between my-4">
                    <a href="{{ route('admin.leads.log', $site) }}"
                        class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition">
                        Update Data
                    </a>
                    <a href="{{ route('admin.leads.statistics', $site) }}"
                    style="color: blue">
                    <i class="fas fa-chart-bar mr-2"></i>
                    View Analysis
                    </a>
                </div>

                <div class="space-y-4">
                    @foreach ($logs as $index => $log)
                        <div class="border rounded-lg overflow-hidden">
                            <!-- Accordion Header -->
                            <a href="#log-{{ $index }}"
                                class="accordion-header block p-4 bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer"
                                onclick="toggleLog(event, '{{ $index }}')">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center space-x-4">
                                        <span class="px-3 py-1 text-xs rounded-full"
                                            style="@if ($log->action === 'insert') background-color:#d1fae5; color:#166534;
                                        @elseif($log->action === 'update') background-color:#fef08a; color:#854d0e;
                                        @else background-color:#fee2e2; color:#991b1b; @endif">
                                            {{ $log->action === 'insert' ? 'Insert' : ($log->action === 'update' ? 'Update' : 'Delete') }}
                                        </span>
                                        <span>
                                            {{ $log->created_at->format('Y-m-d H:i') }}
                                            by {{ $log->changed_by }}
                                        </span>
                                    </div>
                                    <span class="transform transition-transform duration-200"
                                        id="arrow-{{ $index }}">▼</span>
                                </div>
                            </a>

                            <!-- Accordion Content -->
                            <div id="log-{{ $index }}" class="hidden bg-white">
                                <div class="overflow-x-auto p-4">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-right">Field</th>
                                                <th class="px-4 py-2 text-right">New Value</th>
                                                <th class="px-4 py-2 text-right">Old Value</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            @php
                                                $fields = [
                                                    'name' => 'Name',
                                                    'title' => 'Title',
                                                    'company' => 'Company',
                                                    'description' => 'Description',
                                                    'country' => 'Country',
                                                    'zip' => 'ZIP Code',
                                                    'city' => 'City',
                                                    'state' => 'State',
                                                    'address' => 'Address',
                                                    'assigned' => 'Assigned To',
                                                    'dateadded' => 'Creation Date',
                                                    'status' => 'Status',
                                                    'source' => 'Source',
                                                    'email' => 'Email',
                                                    'phonenumber' => 'Phone Number',
                                                    'website' => 'Website',
                                                    'leadorder' => 'Lead Priority',
                                                    'date_converted' => 'Conversion Date',
                                                    'lead_value' => 'Lead Value',
                                                    'lastcontact' => 'Last Contact Date',
                                                    'status_name' => 'Status Name',
                                                    'source_name' => 'Source Name',
                                                    'public_url' => 'Public URL',
                                                    'color' => 'Status Color',
                                                    'attachments' => 'Attachments',
                                                    'hash' => 'Unique Hash',
                                                ];
                                            @endphp

                                            @foreach ($fields as $field => $label)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-4 py-2 font-medium">{{ $label }}</td>
                                                    <td class="px-4 py-2">
                                                        @if ($log->action === 'insert')
                                                            <div
                                                                style="background-color:#d1fae5; color:#166534; padding:4px; border-radius:4px;">
                                                                {{ $log->data_new[$field] ?? 'Not Available' }}
                                                            </div>
                                                        @else
                                                            @php
                                                                $old = $log->data_old[$field] ?? null;
                                                                $new = $log->data_new[$field] ?? null;
                                                            @endphp

                                                            @if ($old !== $new)
                                                                <div
                                                                    style="background-color:#d1fae5; color:#166534; padding:4px; border-radius:4px; margin-bottom:2px;">
                                                                    {{ is_array($new) ? json_encode($new) : ($new ?? 'Not Available') }}
                                                                </div>
                                                            @else
                                                                <div style="padding:4px;">
                                                                    {{ is_array($new) ? json_encode($new) : ($new ?? 'Not Available') }}
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        @if ($log->action !== 'insert' && $old !== $new)
                                                            <div
                                                                style="background-color:#fee2e2; color:#991b1b; padding:4px; border-radius:4px;">
                                                              {{ is_array($old) ? json_encode($old) : ($old ?? 'Not Available') }}

                                                            </div>
                                                        @else
                                                            <div style="padding:4px;">
                                                              {{ is_array($old) ? json_encode($old) : ($old ?? 'Not Available') }}

                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 px-4">{{ $logs->links() }}</div>

            </div>
        </div>
    </div>

    <script>
        function toggleLog(event, index) {
            event.preventDefault();
            const details = document.getElementById(`log-${index}`);
            const arrow = document.getElementById(`arrow-${index}`);

            // Close all other logs
            document.querySelectorAll('.hidden').forEach(item => {
                if (item.id !== `log-${index}` && item.classList.contains('bg-white')) {
                    item.classList.add('hidden');
                    const otherArrows = document.querySelectorAll(`[id^="arrow-"]`);
                    otherArrows.forEach(a => {
                        if (a.id !== `arrow-${index}`) a.innerHTML = '▼';
                    });
                }
            });

            // Toggle current state
            details.classList.toggle('hidden');
            arrow.innerHTML = details.classList.contains('hidden') ? '▼' : '▲';
        }
    </script>
</x-app-layout>
