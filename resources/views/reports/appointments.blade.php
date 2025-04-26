@extends('layouts.app')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-4 bg-white shadow-sm rounded-lg">
                    <h1 class="text-2xl font-bold text-gray-800 text-center">Appointments Report</h1>
                    <div class="flex items-center gap-4 mt-4">
                        <!-- Dhahran Log -->
                        <a href="{{ route('admin.appointments.log', 'dhahran') }}"
                            class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl"
                            title="View Dhahran Log">
                            <i class="fas fa-clipboard-list"></i> dhahran
                        </a>

                        <!-- Bashaer Log -->
                        <a href="{{ route('admin.appointments.log', 'bashaer') }}"
                            class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl"
                            title="View Bashaer Log">
                            <i class="fas fa-clipboard-list"></i> bashaer
                        </a>
                    </div>
                </div>

                <!-- Site Switcher -->
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

                                <!-- Export Buttons -->
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
                                <!-- Hidden Export Header -->
                                <div class="export-header hidden" id="export-header-{{ $key }}">
                                    <h1 class="text-xl font-bold mb-1">Appointments Report</h1>
                                    <h2 class="text-lg text-gray-600">Location: {{ ucfirst($label) }}</h2><br>
                                </div>
                                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg my-8">
                                    <div class="p-4 bg-white shadow-sm rounded-lg">
                                        <h2 class="text-2xl font-bold text-gray-800 text-center">{{ $label }}
                                        </h2>
                                    </div>

                                    @if ($errors[$key])
                                        <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                            {{ $errors[$key] }}
                                        </div>
                                    @else
                                        <!-- Summary -->
                                        <div class="p-6">
                                            <div class="bg-indigo-50 p-6 rounded-lg shadow-sm max-w-xs mx-auto">
                                                <h3 class="text-lg font-semibold text-indigo-800 mb-2">Total
                                                    Appointments</h3>
                                                <div class="text-3xl font-bold text-indigo-600">
                                                    {{ number_format($appointments[$key]['total']) }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Toggle Table -->
                                        <div class="p-6">
                                            <div x-data="{ showDetails: false }" class="bg-white rounded-lg shadow-sm">
                                                <div class="p-4 border-b">
                                                    <button @click="showDetails = !showDetails"
                                                        class="text-blue-500 hover:text-blue-700 flex items-center">
                                                        <span
                                                            x-text="showDetails ? 'Hide Details' : 'Show Details by Date'"></span>
                                                        <svg class="w-4 h-4 ml-2 transform transition-transform"
                                                            :class="{ 'rotate-180': showDetails }" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div x-show="showDetails" class="p-4 space-y-3">
                                                    @foreach ($appointments[$key]['by_date'] as $date => $count)
                                                        <div
                                                            class="flex justify-between items-center p-3 bg-gray-50 rounded hover:bg-gray-100">
                                                            <span
                                                                class="text-gray-600">{{ \Carbon\Carbon::parse($date)->translatedFormat('d M Y') }}</span>
                                                            <span
                                                                class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm">
                                                                {{ $count }} Appointments
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Chart -->
                                        <div class="w-full max-w-4xl mx-auto mt-8 p-6">
                                            <div class="bg-white p-6 rounded-lg shadow-sm relative">
                                                <div wire:ignore class="relative h-[400px]">
                                                    <div x-data="{
                                                        chart: null,
                                                        labels: {{ Js::from(array_keys($appointments[$key]['by_date'])) }},
                                                        data: {{ Js::from(array_values($appointments[$key]['by_date'])) }},
                                                        init() {
                                                            const ctx = this.$refs.chart;
                                                            if (this.chart) {
                                                                this.chart.destroy();
                                                            }
                                                            this.chart = new Chart(ctx, {
                                                                type: 'bar',
                                                                data: {
                                                                    labels: this.labels,
                                                                    datasets: [{
                                                                        label: 'Appointments Count',
                                                                        data: this.data,
                                                                        backgroundColor: '#543829',
                                                                        borderColor: '#00262f',
                                                                        borderWidth: 2
                                                                    }]
                                                                },
                                                                options: {
                                                                    responsive: true,
                                                                    maintainAspectRatio: false,
                                                                    scales: {
                                                                        y: {
                                                                            beginAtZero: true,
                                                                            ticks: {
                                                                                stepSize: 1,
                                                                                precision: 0
                                                                            }
                                                                        },
                                                                        x: {
                                                                            ticks: {
                                                                                autoSkip: false,
                                                                                maxRotation: 45,
                                                                                minRotation: 45
                                                                            }
                                                                        }
                                                                    },
                                                                    plugins: {
                                                                        legend: {
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
                                                    }" x-init="init()">
                                                        <div wire:ignore class="relative"
                                                            style="height: 400px; width: 100%">
                                                            <canvas id="chart-{{ $key }}" x-ref="chart"
                                                                style="width: 100% !important; height: 400px !important"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Required scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <!-- Chart Script -->
    <script>
        function appointmentChart(labels, data) {
            return {
                initChart() {
                    const ctx = this.$refs.chart;
                    if (!ctx) return;

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Appointments Count',
                                data: data,
                                backgroundColor: 'rgba(79, 70, 229, 0.7)',
                                borderColor: 'rgb(79, 70, 229)',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1,
                                        precision: 0
                                    }
                                },
                                x: {
                                    ticks: {
                                        autoSkip: false,
                                        maxRotation: 45,
                                        minRotation: 45
                                    }
                                }
                            },
                            plugins: {
                                legend: {
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
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const siteSelect = document.getElementById('siteSelect');
            const sections = document.querySelectorAll('.site-section');

            siteSelect.addEventListener('change', function() {
                const selected = this.value;
                sections.forEach(section => section.style.display = 'none');
                if (selected) {
                    document.getElementById(`site-${selected}`).style.display = 'block';
                }
            });
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
                    doc.text(`Appointments Report`, 14, yPos);
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

                    detailsContent.querySelectorAll('.flex.justify-between').forEach(row => {
                        const date = row.children[0].textContent.trim();
                        const count = row.children[1].textContent.trim();
                        doc.text(`${date}: ${count}`, 14, yPos);
                        yPos += 7;

                        // Add new page if content overflows
                        if (yPos > 270) {
                            doc.addPage();
                            yPos = 10;
                        }
                    });

                    doc.save(`${site}_appointments.pdf`);
                });
            } else if (type === 'zip') {
                const zip = new JSZip();

                // Create CSV file
                let csvContent = "Date,Count\n";
                detailsContent.querySelectorAll('.flex.justify-between').forEach(row => {
                    const date = row.children[0].textContent;
                    const count = row.children[1].textContent.match(/\d+/)[0] || '0';
                    csvContent += `${date},${count}\n`;
                });

                // Add metadata
                csvContent += `\nExported by,${exportedBy}\nExport date,${exportDate}`;

                // Add files to zip
                zip.file(`${site}_appointments.csv`, csvContent);

                html2canvas(chartCanvas).then(canvas => {
                    canvas.toBlob(blob => {
                        zip.file(`${site}_chart.png`, blob);
                        zip.generateAsync({
                            type: "blob"
                        }).then(content => {
                            const url = URL.createObjectURL(content);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = `${site}_appointments_report.zip`;
                            a.click();
                            URL.revokeObjectURL(url);
                        });
                    });
                });
            }
        }
    </script>
@endsection
