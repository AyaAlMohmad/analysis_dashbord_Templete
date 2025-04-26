@extends('layouts.app')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-4 bg-white shadow-sm rounded-lg">
                    <h1 class="text-2xl font-bold text-center text-gray-800">Call Logs Report</h1>
                    <a href="{{ route('admin.call.log', 'dhahran') }}"
                        class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl"
                        title="View Dhahran Log">
                        <i class="fas fa-clipboard-list"></i> dhahran
                    </a>

                    <!-- Bashaer Log -->
                    <a href="{{ route('admin.call.log', 'bashaer') }}"
                        class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl"
                        title="View Bashaer Log">
                        <i class="fas fa-clipboard-list"></i> bashaer
                    </a>
                </div>

                <!-- Site Selector -->
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <!-- Site Selector -->
                        <div class="text-center mb-10">
                            <label for="siteSelect" class="block text-xl font-bold text-gray-800 mb-3">
                                Choose a Site:
                            </label>

                            <div class="inline-block relative w-64">
                                <select id="siteSelect"
                                    class="block appearance-none w-full bg-white border border-gray-300 text-gray-700 py-3 px-4 pr-10 rounded leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                    <option value="">-- Select a site --</option>
                                    <option value="dhahran">Azyan Dhahran</option>
                                    <option value="bashaer">Azyan Bashaer</option>
                                </select>

                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">

                                </div>
                                <div class="flex items-center gap-4 mt-4">
                                    <button type="button" onclick="submitExport('pdf')"
                                        class="p-3 rounded-xl hover:bg-red-100 transition text-red-500 text-2xl"
                                        title="Export PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </button>

                                    <button type="button" onclick="submitExport('zip')"
                                        class="p-3 rounded-xl hover:bg-blue-100 transition text-blue-500 text-2xl"
                                        title="Export ZIP">
                                        <i class="fas fa-file-archive"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        @foreach (['dhahran' => 'Azyan Dhahran', 'bashaer' => 'Azyan Bashaer'] as $key => $label)
                            <div class="site-section" id="site-{{ $key }}" style="display: none;">
                                @if ($errors[$key] ?? false)
                                    <div class="p-4 bg-red-100 text-red-800 rounded">{{ $errors[$key] }}</div>
                                @else
                                    <div class="export-header hidden" id="export-header-{{ $key }}">
                                        <h1 class="text-xl font-bold mb-1">Call Logs Report</h1>
                                        <h2 class="text-lg text-gray-600">Location: {{ ucfirst($label) }}</h2><br>
                                    </div>
                                    <!-- Stats Cards -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6">
                                        <div class="bg-blue-50 p-6 rounded-lg shadow-sm">
                                            <h3 class="text-lg font-semibold text-blue-800 mb-2">Total Added</h3>
                                            <div class="text-3xl font-bold text-blue-600">{{ $totals[$key]['added'] }}
                                            </div>
                                        </div>
                                        <div class="bg-green-50 p-6 rounded-lg shadow-sm">
                                            <h3 class="text-lg font-semibold text-green-800 mb-2">Calls Started</h3>
                                            <div class="text-3xl font-bold text-green-600">
                                                {{ $totals[$key]['started'] }}</div>
                                        </div>
                                        <div class="bg-purple-50 p-6 rounded-lg shadow-sm">
                                            <h3 class="text-lg font-semibold text-purple-800 mb-2">Calls Ended</h3>
                                            <div class="text-3xl font-bold text-purple-600">{{ $totals[$key]['ended'] }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Chart -->
                                    <div class="w-full max-w-6xl mx-auto p-6">
                                        <div class="bg-white p-6 rounded-lg shadow relative">
                                            <div wire:ignore class="relative h-[400px]">
                                                <canvas id="chart-{{ $key }}"></canvas>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Table -->
                                    <div class="p-6">
                                        <div x-data="{ showDetails: false }" class="bg-white rounded-lg shadow">
                                            <div class="p-4 border-b">
                                                <button @click="showDetails = !showDetails"
                                                    class="text-blue-500 hover:text-blue-700 flex items-center">
                                                    <span
                                                        x-text="showDetails ? 'Hide Details' : 'Show Daily Details'"></span>
                                                    <svg class="w-4 h-4 ml-2 transform"
                                                        :class="{ 'rotate-180': showDetails }" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div x-show="showDetails" class="p-4 overflow-x-auto">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead>
                                                        <tr>
                                                            <th
                                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                                                Date</th>
                                                            <th
                                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                                                Added</th>
                                                            <th
                                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                                                Started</th>
                                                            <th
                                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                                                Ended</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white divide-y divide-gray-200">
                                                        @foreach ($callLogs[$key]['added'] as $date => $added)
                                                            <tr>
                                                                <td class="px-6 py-4 text-sm text-gray-600">
                                                                    {{ \Carbon\Carbon::parse($date)->translatedFormat('d M Y') }}
                                                                </td>
                                                                <td class="px-6 py-4 text-center text-sm text-blue-600">
                                                                    {{ $added }}</td>
                                                                <td
                                                                    class="px-6 py-4 text-center text-sm text-green-600">
                                                                    {{ $callLogs[$key]['started'][$date] ?? 0 }}</td>
                                                                <td
                                                                    class="px-6 py-4 text-center text-sm text-purple-600">
                                                                    {{ $callLogs[$key]['ended'][$date] ?? 0 }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
            <!-- Required scripts -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
            <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const select = document.getElementById('siteSelect');
                    const sections = document.querySelectorAll('.site-section');

                    select.addEventListener('change', () => {
                        const val = select.value;
                        sections.forEach(section => section.style.display = 'none');
                        if (val) {
                            document.getElementById(`site-${val}`).style.display = 'block';
                            drawChart(val);
                        }
                    });

                    function drawChart(site) {
                        const ctx = document.getElementById('chart-' + site);
                        if (!ctx) return;

                        const siteDates = @json([
                            'dhahran' => array_keys($callLogs['dhahran']['added'] ?? []),
                            'bashaer' => array_keys($callLogs['bashaer']['added'] ?? []),
                        ]);

                        const siteAdded = @json([
                            'dhahran' => array_values($callLogs['dhahran']['added'] ?? []),
                            'bashaer' => array_values($callLogs['bashaer']['added'] ?? []),
                        ]);

                        const siteStarted = @json([
                            'dhahran' => array_values($callLogs['dhahran']['started'] ?? []),
                            'bashaer' => array_values($callLogs['bashaer']['started'] ?? []),
                        ]);

                        const siteEnded = @json([
                            'dhahran' => array_values($callLogs['dhahran']['ended'] ?? []),
                            'bashaer' => array_values($callLogs['bashaer']['ended'] ?? []),
                        ]);

                        const chartColors = {
                            dhahran: {
                                added: {
                                    bg: '#00262f ',
                                    border: '#00181f '
                                },
                                started: {
                                    bg: '#334F5D',
                                    border: '#00181f'
                                },
                                ended: {
                                    bg: '#A2C2D6',
                                    border: '#00181f'
                                }
                            },
                            bashaer: {
                                added: {
                                    bg: '#543829',
                                    border: '#3C251C'
                                },
                                started: {
                                    bg: '#7A5A4B',
                                    border: '#3C251C'
                                },
                                ended: {
                                    bg: '#D6B29C',
                                    border: '#3C251C'
                                }
                            }
                        };

                        const colors = chartColors[site];

                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: siteDates[site],
                                datasets: [{
                                        label: 'Added',
                                        data: siteAdded[site],
                                        backgroundColor: colors.added.bg,
                                        borderColor: colors.added.border,
                                        borderWidth: 1,
                                    },
                                    {
                                        label: 'Started',
                                        data: siteStarted[site],
                                        backgroundColor: colors.started.bg,
                                        borderColor: colors.started.border,
                                        borderWidth: 1,
                                    },
                                    {
                                        label: 'Ended',
                                        data: siteEnded[site],
                                        backgroundColor: colors.ended.bg,
                                        borderColor: colors.ended.border,
                                        borderWidth: 1,
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: {
                                        ticks: {
                                            autoSkip: false,
                                            maxRotation: 45,
                                            minRotation: 45
                                        }
                                    },
                                    y: {
                                        beginAtZero: true
                                    }
                                },
                                plugins: {
                                    legend: {
                                        position: 'top',
                                        labels: {
                                            font: {
                                                size: 14,
                                                family: 'Tajawal'
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                });
            </script>
            <script>
                function submitExport(type) {
                    const site = document.getElementById('siteSelect').value;
                    if (!site) return alert('Please select a site');

                    const exportHeader = document.getElementById(`export-header-${site}`);
                    const chartCanvas = document.getElementById(`chart-${site}`);
                    const siteSection = document.getElementById(`site-${site}`);
                    const detailsContent = siteSection.querySelector('[x-show="showDetails"]');

                    if (!exportHeader || !chartCanvas || !detailsContent) {
                        alert('Required elements not found');
                        return;
                    }

                    const originalHeaderDisplay = exportHeader.style.display;
                    const originalDetailsDisplay = detailsContent.style.display;
                    exportHeader.style.display = 'block';
                    detailsContent.style.display = 'block';

                    const exportedBy = "{{ Auth::user()->name }}";
                    const exportDate = new Date().toLocaleString();
                    if (type === 'pdf') {
                        html2canvas(chartCanvas).then((chartImg) => {
                            const doc = new jspdf.jsPDF();
                            const imgWidth = 190;
                            let yPos = 10;

                            // Header (text only)
                            doc.setFontSize(16);
                            doc.text(`${site} Call Logs Report`, 14, yPos);
                            yPos += 10;

                            doc.setFontSize(12);
                            doc.text(`Site: ${site}`, 14, yPos);
                            yPos += 8;

                            // Add footer only on the last page
                            const pageHeight = doc.internal.pageSize.height;
                            const margin = 10;
                            doc.setFontSize(10);
                            doc.text(`Exported by: ${exportedBy}`, margin, pageHeight - 20);
                            doc.text(`Export date: ${exportDate}`, margin, pageHeight - 15);

                            // Chart image
                            doc.addImage(chartImg, 'PNG', 10, yPos, imgWidth, 100);
                            yPos += 110;

                            // Details (daily details)
                            doc.setFontSize(12);
                            doc.text(`Daily Details:`, 14, yPos);
                            yPos += 8;

                            const rows = detailsContent.querySelectorAll('table tbody tr');
                            rows.forEach(row => {
                                const cells = row.querySelectorAll('td');
                                const date = cells[0].textContent.trim();
                                const added = cells[1].textContent.trim();
                                const started = cells[2].textContent.trim();
                                const ended = cells[3].textContent.trim();

                                doc.text(`${date} | Added: ${added} | Started: ${started} | Ended: ${ended}`, 14,
                                    yPos);
                                yPos += 7;
                            });

                            doc.save(`${site}_call_logs.pdf`);
                        });
                    } else if (type === 'zip') {
                        const zip = new JSZip();

                        // Create CSV file
                        let csvContent = "Date,Added,Started,Ended\n";
                        detailsContent.querySelectorAll('table tbody tr').forEach(row => {
                            const cells = row.querySelectorAll('td');
                            const date = cells[0].textContent.trim();
                            const added = cells[1].textContent.trim();
                            const started = cells[2].textContent.trim();
                            const ended = cells[3].textContent.trim();

                            csvContent += `${date},${added},${started},${ended}\n`;
                        });

                        // Add metadata
                        csvContent += `\nExported by,${exportedBy}\nExport date,${exportDate}`;

                        // Add files to zip
                        zip.file(`${site}_call_logs.csv`, csvContent);

                        html2canvas(chartCanvas).then(canvas => {
                            canvas.toBlob(blob => {
                                zip.file(`${site}_chart.png`, blob);
                                zip.generateAsync({
                                    type: "blob"
                                }).then(content => {
                                    const url = URL.createObjectURL(content);
                                    const a = document.createElement('a');
                                    a.href = url;
                                    a.download = `${site}_call_logs_report.zip`;
                                    a.click();
                                    URL.revokeObjectURL(url);
                                });
                            });
                        });
                    }
                }
            </script>
@endsection
