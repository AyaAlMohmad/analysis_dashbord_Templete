@extends('layouts.app')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-4 bg-white shadow-sm rounded-lg">
                    <h1 class="text-2xl font-bold text-gray-800 text-center">Leads Sources Report</h1>
                    {{-- <div class="flex items-center gap-4 mt-4">
                        <!-- Dhahran Log -->
                        <a href="{{ route('admin.leads-sources.log', 'dhahran') }}"
                            class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl"
                            title="View Dhahran Log">
                            <i class="fas fa-clipboard-list"></i> dhahran
                        </a>

                        <!-- Bashaer Log -->
                        <a href="{{ route('admin.leads-sources.log', 'bashaer') }}"
                            class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl"
                            title="View Bashaer Log">
                            <i class="fas fa-clipboard-list"></i> bashaer
                        </a>
                    </div> --}}
                </div>

                <!-- Site Switcher -->
                <div class="p-6 bg-gradient-to-r from-blue-50 to-white shadow-sm rounded-lg mb-6">
                    <div class="max-w-md mx-auto text-center">
                        <label for="location-filter" class="block text-lg font-semibold text-gray-700 mb-2">
                            📍 Select Location
                        </label>
                        <select id="location-filter"
                            class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none transition duration-200">
                            <option value="">-- Please choose a location --</option>
                            @foreach (array_keys($allData) as $location)
                                <option value="{{ $location }}">Azyan {{ ucfirst($location) }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500"></div>

                        <!-- Export Buttons -->
                        <form id="exportForm" class="flex items-center gap-4 mt-4">
                            @csrf
                            <!-- Exported by info -->
                            <input type="hidden" id="exportedBy" value="{{ Auth::user()->name }}">
                            <!-- PDF Icon -->
                            <button type="button" onclick="submitExport('pdf')"
                                class="p-3 rounded-xl hover:bg-red-100 transition text-red-500 text-2xl"
                                title="Export PDF">
                                <i class="fas fa-file-pdf"></i>
                            </button>

                            <!-- ZIP Icon -->
                            <button type="button" onclick="submitExport('csv')"
                                class="p-3 rounded-xl hover:bg-blue-100 transition text-blue-500 text-2xl"
                                title="Export ZIP">
                                <i class="fas fa-file-archive"></i>
                            </button>
                        </form>
                    </div>
                </div>

                @if ($error)
                    <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ $error }}
                    </div>
                @endif

                @foreach ($allData as $location => $data)
                    @if (count($data) > 0)
                        <div class="mt-10 chart-section" id="chart-section-{{ $location }}" style="display: none;">
                            <!-- Hidden Export Header -->
                            <div class="export-header hidden" id="export-header-{{ $location }}">
                                <h1 class="text-2xl font-bold mb-2">Leads Sources Report</h1>
                                <h2 class="text-xl text-gray-600">Location: {{ ucfirst($location) }}</h2><br>
                            </div>

                            <!-- Data List -->
                            <div class="bg-white p-6 rounded-lg shadow-sm">
                                <h3 class="text-lg font-semibold mb-4 text-gray-700">Distribution by Source</h3>
                                <div class="space-y-3 hidden" id="list-{{ $location }}">
                                    @foreach ($data as $source => $count)
                                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded hover:bg-gray-100">
                                            <span class="text-gray-600">{{ $source }}</span>
                                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                                {{ $count }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                                <button style="color: rgb(17, 37, 226)"
                                    class="text-blue-500 hover:text-blue-700 hover:underline"
                                    onclick="document.getElementById('list-{{ $location }}').classList.toggle('hidden'); this.textContent = this.textContent === 'Show Details' ? 'Hide Details' : 'Show Details'">
                                    Show Details
                                </button>
                            </div>

                            <!-- Chart Area -->
                            <div class="w-full max-w-4xl mx-auto mt-6">
                                <div class="bg-white p-6 rounded-lg shadow-sm relative">
                                    <div class="relative h-[400px]">
                                        <canvas id="chart-{{ $location }}"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chart Script -->
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const ctx = document.getElementById('chart-{{ $location }}');
                                const labels = @json(array_keys($data));
                                const values = @json(array_values($data));

                                new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: labels,
                                        datasets: [{
                                            label: 'Number of Leads',
                                            data: values,
                                            backgroundColor: '#543829',
                                            borderColor: '#00262f',
                                            borderWidth: 1,
                                            barThickness: 30
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        scales: {
                                            x: {
                                                ticks: {
                                                    minRotation: 45,
                                                    maxRotation: 45,
                                                    autoSkip: false
                                                }
                                            },
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    stepSize: 500
                                                }
                                            }
                                        },
                                        plugins: {
                                            legend: {
                                                position: 'top',
                                                labels: {
                                                    font: {
                                                        size: 14
                                                    }
                                                }
                                            }
                                        },
                                    }
                                });
                            });
                        </script>
                    @else
                        <div class="bg-white p-6 rounded-lg shadow-sm mt-10">
                            <div class="text-center text-gray-500">No data available for {{ ucfirst($location) }}</div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- JS Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <!-- Location Switcher Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const locationSelect = document.getElementById('location-filter');
            const allSections = document.querySelectorAll('.chart-section');

            locationSelect.addEventListener('change', function() {
                const selected = this.value;

                allSections.forEach(section => {
                    section.style.display = 'none';
                });

                if (selected) {
                    const selectedSection = document.getElementById(`chart-section-${selected}`);
                    if (selectedSection) {
                        selectedSection.style.display = 'block';
                    }
                }
            });
        });
    </script>

    <!-- Export Function -->
    <script>
        function submitExport(type) {
            const site = document.getElementById('location-filter').value;
            if (!site) return alert('Please select a site');
    
            const exportHeader = document.getElementById(`export-header-${site}`);
            const chartSection = document.getElementById(`chart-section-${site}`);
            const chartCanvas = chartSection.querySelector('canvas');
            const detailsContent = document.getElementById(`list-${site}`);
    
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
                window.jsPDF = window.jspdf.jsPDF;
                const doc = new jsPDF('p', 'mm', 'a4');
    
                Promise.all([
                    html2canvas(exportHeader),
                    html2canvas(chartCanvas),
                    html2canvas(detailsContent)
                ]).then(([headerImg, chartImg, detailsImg]) => {
                    const imgWidth = 190;
                    let yPos = 10;
    
                    const headerHeight = (headerImg.height * imgWidth) / headerImg.width;
                    doc.addImage(headerImg, 'PNG', 10, yPos, imgWidth, headerHeight);
                    yPos += headerHeight + 10;
    
                    const chartHeight = (chartImg.height * imgWidth) / chartImg.width;
                    doc.addImage(chartImg, 'PNG', 10, yPos, imgWidth, chartHeight);
                    yPos += chartHeight + 10;
    
                    const detailsHeight = (detailsImg.height * imgWidth) / detailsImg.width;
                    doc.addImage(detailsImg, 'PNG', 10, yPos, imgWidth, detailsHeight);
    
                    doc.setFontSize(10);
                    const pageHeight = doc.internal.pageSize.height;
                    doc.text(`Exported by: ${exportedBy}`, 10, pageHeight - 20);
                    doc.text(`Export date: ${exportDate}`, 10, pageHeight - 15);
    
                    doc.save(`${site}_leads_report.pdf`);
                }).catch(error => {
                    console.error('Export failed:', error);
                    alert('Export failed, check console for details');
                }).finally(() => {
                    exportHeader.style.display = originalHeaderDisplay;
                    detailsContent.style.display = originalDetailsDisplay;
                });
    
            } else if (type === 'csv') {
                const zip = new JSZip();
    
                const data = @json($allData);
                let csvContent = "Source,Count\n";
                csvContent += Object.entries(data[site])
                    .map(([source, count]) => `${source},${count}`)
                    .join("\n");
    
                csvContent += `\n\nExported by:,${exportedBy}`;
                csvContent += `\nExport date:,${exportDate}`;
    
                html2canvas(chartCanvas).then(canvas => {
                    canvas.toBlob(blob => {
                        zip.file(`${site}_data.csv`, csvContent);
                        zip.file(`${site}_chart.png`, blob);
    
                        zip.generateAsync({type: "blob"}).then(content => {
                            const url = window.URL.createObjectURL(content);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = `${site}_report.zip`;
                            a.click();
                            window.URL.revokeObjectURL(url);
                        });
                    }, 'image/png');
                });
            }
        }
    </script>
@endsection
