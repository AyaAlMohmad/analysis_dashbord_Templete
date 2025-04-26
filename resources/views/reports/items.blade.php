@extends('layouts.app')
@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.0/jszip.min.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-4 bg-white shadow-sm rounded-lg">
                    <h1 class="text-2xl font-bold text-gray-800 text-center">Units Report</h1>
                    <div class="flex items-center gap-4 mt-4">
                        <!-- Dhahran Log -->
                        <a href="{{ route('admin.items.log', 'dhahran') }}"
                            class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl"
                            title="View Dhahran Log">
                            <i class="fas fa-clipboard-list"></i> dhahran
                        </a>

                        <!-- Bashaer Log -->
                        <a href="{{ route('admin.items.log', 'bashaer') }}"
                            class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl"
                            title="View Bashaer Log">
                            <i class="fas fa-clipboard-list"></i> bashaer
                        </a>
                    </div>
                </div>

                <!-- Site Switch -->
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
                                    <!-- Map Button (Visible After Site Selection) -->
                                    <button id="mapButton" onclick="goToMap()"
                                        class="p-3 rounded-xl hover:bg-green-100 transition text-green-600 text-2xl"
                                        style="display: none;" title="View Map">
                                        <i class="fas fa-map-marked-alt"></i>
                                    </button>

                                </div>
                            </div>
                        </div>


                        @foreach (['dhahran' => 'Azyan Dhahran', 'bashaer' => 'Azyan Bashaer'] as $key => $label)
                            <div id="site-{{ $key }}" class="site-section" style="display: none;">
                                @php
                                    $data = $key === 'dhahran' ? $dataDhahran : $dataBashaer;
                                @endphp
                                <div class="export-header hidden" id="export-header-{{ $key }}">
                                    <h1 class="text-xl font-bold mb-1">Units Report</h1>
                                    <h2 class="text-lg text-gray-600">Location: {{ ucfirst($label) }}</h2><br>
                                </div>
                                <div class="bg-white my-8 shadow-sm rounded-lg">
                                    <div class="p-4 bg-white shadow-sm rounded-lg">
                                        <h2 class="text-2xl font-bold text-gray-800 text-center">{{ $label }} -
                                            Units
                                            Overview</h2>
                                    </div>

                                    @if ($errors[$key])
                                        <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                            {{ $errors[$key] }}
                                        </div>
                                    @else
                                        <!-- Stats -->
                                        <!-- Stats Cards -->
                                        <div
                                            class="stats-cards grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 p-6">
                                            <div class="p-6 rounded-lg shadow-sm"
                                                style="background-color: #A2C2D6; color: #00262f;">
                                                <h3 class="text-lg font-semibold mb-2">Available</h3>
                                                <div class="text-3xl font-bold">
                                                    {{ number_format($data['available'] ?? 0) }}</div>
                                            </div>

                                            <div class="p-6 rounded-lg shadow-sm"
                                                style="background-color: #D6B29C; color: #543829;">
                                                <h3 class="text-lg font-semibold mb-2">Reserved</h3>
                                                <div class="text-3xl font-bold">
                                                    {{ number_format($data['reserved']['total'] ?? 0) }}</div>
                                            </div>

                                            <div class="p-6 rounded-lg shadow-sm"
                                                style="background-color: #00262f; color: white;">
                                                <h3 class="text-lg font-semibold mb-2">Blocked</h3>
                                                <div class="text-3xl font-bold">
                                                    {{ number_format($data['blocked'] ?? 0) }}</div>
                                            </div>

                                            <div class="p-6 rounded-lg shadow-sm"
                                                style="background-color: #543829; color: white;">
                                                <h3 class="text-lg font-semibold mb-2">contracted</h3>
                                                <div class="text-3xl font-bold">
                                                    {{ number_format($data['contracted']['total']  ?? 0) }}</div>
                                            </div>
                                        </div>




                                        <!-- Chart -->
                                        <div class="w-full max-w-3xl mx-auto mt-6 p-6">
                                            <div class="bg-white p-6 rounded-lg shadow-sm relative">
                                                <div wire:ignore>
                                                    <div x-data="{
                                                        chart: null,
                                                        init() {
                                                            if (this.chart) this.chart.destroy();
                                                    
                                                            const ctx = this.$refs.chart;
                                                            this.chart = new Chart(ctx, {
                                                                type: 'doughnut',
                                                                data: {
                                                                    labels: ['Available', 'Reserved', 'Blocked', 'contracted'],
                                                                    datasets: [{
                                                                        data: [
                                                                            {{ $data['available'] ?? 0 }},
                                                                            {{ $data['reserved']['total'] ?? 0 }},
                                                                            {{ $data['blocked'] ?? 0 }},
                                                                            {{ $data['contracted']['total']?? 0 }}
                                                                        ],
                                                                        backgroundColor: [
                                                                            '#A2C2D6',
                                                                            '#D6B29C',
                                                                            '#00262f', // red
                                                                            '#543829' // green
                                                                        ],
                                                                        borderColor: [
                                                                            '#A2C2D6',
                                                                            '#D6B29C',
                                                                            '#00262f',
                                                                            '#543829'
                                                                        ],
                                                                        borderWidth: 2
                                                                    }]
                                                                },
                                                                options: {
                                                                    responsive: true,
                                                                    maintainAspectRatio: false,
                                                                    plugins: {
                                                                        legend: {
                                                                            position: 'bottom',
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
                                                        <canvas id="chart-{{ $key }}" x-ref="chart"></canvas>
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
            <!-- Scripts -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const siteSelect = document.getElementById('siteSelect');
                    const sections = document.querySelectorAll('.site-section');

                    siteSelect.addEventListener('change', function() {
                        const selected = this.value;
                        sections.forEach(section => {
                            section.style.display = 'none';
                        });
                        if (selected) {
                            document.getElementById(`site-${selected}`).style.display = 'block';
                        }
                        // Show map button after site selection
                        const mapButton = document.getElementById('mapButton');
                        if (mapButton) {
                            mapButton.style.display = 'block';
                        }
                    });
                });
            </script>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
            <script>
                function goToMap() {
                    const site = document.getElementById('siteSelect').value;
                    if (!site) {
                        alert('Please select a site to view the map.');
                        return;
                    }
                    window.location.href = `/admin/items/map/${site}`;
                }

                function submitExport(type) {
                    const site = document.getElementById('siteSelect').value;
                    if (!site) return alert('Please select a site');

                    const exportHeader = document.getElementById(`export-header-${site}`);
                    const chartCanvas = document.getElementById(`chart-${site}`);
                    const siteSection = document.getElementById(`site-${site}`);


                    if (!exportHeader || !chartCanvas) {
                        alert('Required elements not found');
                        return;
                    }

                    const originalHeaderDisplay = exportHeader.style.display;

                    exportHeader.style.display = 'block';


                    const exportedBy = "{{ Auth::user()->name }}";
                    const exportDate = new Date().toLocaleString();
                    if (type === 'pdf') {
                        const stats = siteSection.querySelectorAll('.stats-cards > .p-6');

                        html2canvas(chartCanvas).then((chartImg) => {
                            const doc = new jspdf.jsPDF();
                            const imgWidth = 190;
                            let yPos = 10;

                            // Header (text only)
                            doc.setFontSize(16);
                            doc.text(`Units Report`, 14, yPos);
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
                            doc.text(`Statistics:`, 14, yPos);
                            yPos += 8;

                            stats.forEach(card => {
                                const title = card.querySelector('h3')?.textContent.trim() ?? '';
                                const value = card.querySelector('div.text-3xl')?.textContent.trim() ?? '';
                                doc.text(`${title}: ${value}`, 14, yPos);
                                yPos += 7;

                                // Add new page if content overflows
                                if (yPos > 270) {
                                    doc.addPage();
                                    yPos = 10;
                                }
                            });


                            doc.save(`${site}_Units.pdf`);
                        });
                    } else if (type === 'zip') {
                        const zip = new JSZip();

                        // Create CSV file
                        let csvContent = "Metric,Value\n";
                        const stats = siteSection.querySelectorAll('.stats-cards > .p-6');

                        stats.forEach(card => {
                            const title = card.querySelector('h3')?.textContent.trim() ?? '';
                            const value = card.querySelector('.text-3xl')?.textContent.trim() ?? '';
                            csvContent += `${title},${value}\n`;
                        });
                        // Add metadata
                        csvContent += `\nExported by,${exportedBy}\nExport date,${exportDate}`;

                        // Add files to zip
                        zip.file(`${site}_Units.csv`, csvContent);

                        html2canvas(chartCanvas).then(canvas => {
                            canvas.toBlob(blob => {
                                zip.file(`${site}_chart.png`, blob);
                                zip.generateAsync({
                                    type: "blob"
                                }).then(content => {
                                    const url = URL.createObjectURL(content);
                                    const a = document.createElement('a');
                                    a.href = url;
                                    a.download = `${site}_Units_report.zip`;
                                    a.click();
                                    URL.revokeObjectURL(url);
                                });
                            });
                        });
                    }
                }
            </script>

@endsection

