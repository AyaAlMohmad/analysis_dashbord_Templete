@extends('layouts.app')
@section('content')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <div class="py-20 px-8 container mx-auto">
        <section class="basic-select2">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-8">
                    <div class="card mt-[100px]">
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="text-bold-600 font-medium-2">
                                        <h1 class="text-2xl font-bold text-gray-800 text-center">{{ __('units_report.title') }} </h1>
                                        <div class="flex items-center gap-4 mt-4">
                                            <!-- Dhahran Log -->
                                            <a href="{{ route('admin.items.log', 'dhahran') }}"
                                                class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl"
                                                title="View Dhahran Log">
                                                <i class="fas fa-clipboard-list"></i> {{ __('units_report.dhahran') }}
                                            </a>

                                            <!-- Bashaer Log -->
                                            <a href="{{ route('admin.items.log', 'bashaer') }}"
                                                class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl"
                                                title="View Bashaer Log">
                                                <i class="fas fa-clipboard-list"></i> {{ __('units_report.bashaer') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <p class="text-center mt-4">{{ __('units_report.select_location') }}</p>

                                <select id="siteSelect" class="select2-placeholder form-control mt-2">
                                    <option value="">{{ __('units_report.choose_location') }}</option>
                                    <option value="dhahran">{{ __('units_report.dhahran') }} </option>
                                    <option value="bashaer">{{ __('units_report.bashaer') }} </option>
                                </select>
                            </div>
                            <form id="exportForm" class="flex items-center gap-12 mt-12 justify-center">
                                @csrf
                                <input type="hidden" id="exportedBy" value="{{ Auth::user()->name }}">

                                <!-- PDF Export Button -->
                                <a href="javascript:void(0);" onclick="submitExport('pdf')" title="Export PDF"
                                    class="transition duration-300 transform hover:scale-110 hover:rotate-6">
                                    <div class="fonticon-container flex items-center justify-center custom-hover-red">
                                        <div class="fonticon-wrap">
                                            <i class="fa fa-file-pdf-o text-red-500 hover:text-red-700 text-7xl"></i>
                                        </div>
                                    </div>
                                </a>

                                <!-- CSV Export Button -->
                                <a href="javascript:void(0);" onclick="submitExport('csv')" title="Export ZIP"
                                    class="transition duration-300 transform hover:scale-110 hover:-rotate-6">
                                    <div class="fonticon-container flex items-center justify-center">
                                        <div class="fonticon-wrap">
                                            <i
                                                class="fa fa-file-archive-o text-indigo-500 hover:text-indigo-700 text-7xl"></i>
                                        </div>
                                    </div>
                                </a>
                            </form>
                            <!-- Map Button (Visible After Site Selection) -->
                            <a id="mapButton" onclick="goToMap()" style="display: none; color: green" title="View Map"
                                class="transition duration-300 transform hover:scale-110 hover:-rotate-6">
                                <div class="fonticon-container flex items-center justify-center">
                                    <div class="fonticon-wrap">
                                        <i class="fas fa-map-marked-alt"></i>
                                    </div>
                                </div>
                            </a>


                        </div>
                    </div>
                </div>
            </div>
        </section>



        @foreach (['dhahran' => 'Azyan Dhahran', 'bashaer' => 'Azyan Bashaer'] as $key => $label)
            <div id="site-{{ $key }}" class="site-section" style="display: none;">
                @php
                    $data = $key === 'dhahran' ? $dataDhahran : $dataBashaer;
                @endphp
                <div class="export-header hidden" id="export-header-{{ $key }}">
                    <h1 class="text-xl font-bold mb-1">{{ __('units_report.title') }}</h1>
                    <h2 class="text-lg text-gray-600">{{ __('units_report.location') }}: {{ ucfirst($label) }}</h2>
                    <br>
                </div>
                <div class="bg-white my-8 shadow-sm rounded-lg">
                    <div class="p-4 bg-white shadow-sm rounded-lg">
                        <h2 class="text-2xl font-bold text-gray-800 text-center">
                            {{ $label }} -
                            {{ __('units_report.overview') }}</h2>
                    </div>

                    @if ($errors[$key])
                        <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ $errors[$key] }}
                        </div>
                    @else
                        <!-- Stats -->

                        <!-- Stats Cards -->
                        <div style="display: flex; flex-wrap: wrap; gap: 24px; padding: 24px;">

                            <div
                                style="flex: 1 1 calc(50% - 12px); background-color: #A2C2D6; color: #00262f; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 12px;">{{ __('units_report.available') }}</h3>
                                <div style="font-size: 32px; font-weight: bold;">
                                    {{ number_format($data['available'] ?? 0) }}
                                </div>
                            </div>

                            <div
                                style="flex: 1 1 calc(50% - 12px); background-color: #D6B29C; color: #543829; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 12px;">{{ __('units_report.reserved') }}</h3>
                                <div style="font-size: 32px; font-weight: bold;">
                                    {{ number_format($data['reserved']['total'] ?? 0) }}
                                </div>
                            </div>

                            <div
                                style="flex: 1 1 calc(50% - 12px); background-color: #00262f; color: #ffffff; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 12px;">{{ __('units_report.blocked') }}</h3>
                                <div style="font-size: 32px; font-weight: bold;">
                                    {{ number_format($data['blocked'] ?? 0) }}
                                </div>
                            </div>

                            <div
                                style="flex: 1 1 calc(50% - 12px); background-color: #543829; color: #ffffff; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 12px;">{{ __('units_report.contracted') }}</h3>
                                <div style="font-size: 32px; font-weight: bold;">
                                    {{ number_format($data['contracted']['total'] ?? 0) }}
                                </div>
                            </div>

                        </div>







                        <!-- Chart -->
                        <div class="w-full max-w-3xl mx-auto mt-6 p-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm relative">
                          
                                    <div>
                                        <canvas id="chart-{{ $key }}" style="width:100%; height:400px;"></canvas>
                                    </div>          
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        
            const siteSelect = document.getElementById('siteSelect');
            siteSelect.addEventListener('change', function () {
                const selected = this.value;
                if (!selected) return;
         if (window.currentChart) {
                    window.currentChart.destroy();
                }
    
                const ctx = document.getElementById('chart-' + selected).getContext('2d');
                window.currentChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['{{ __('units_report.available') }}', '{{ __('units_report.reserved') }}', '{{ __('units_report.blocked') }}', '{{ __('units_report.contracted') }}'],

                        datasets: [{
                            data: [
                                @json($dataDhahran['available'] ?? 0),
                                @json($dataDhahran['reserved']['total'] ?? 0),
                                @json($dataDhahran['blocked'] ?? 0),
                                @json($dataDhahran['contracted']['total'] ?? 0)
                            ],
                            backgroundColor: [
                                '#A2C2D6',
                                '#D6B29C',
                                '#00262f',
                                '#543829'
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
            });
        });
    </script>
    
    <script>
        function toggleTable(key) {
            const section = document.getElementById(`daily-details-${key}`);
            const link = document.querySelector(`[onclick="toggleTable('${key}')"]`);

            if (section.classList.contains('hidden')) {
                section.classList.remove('hidden');
                link.textContent = 'Hide Details';
            } else {
                section.classList.add('hidden');
                link.textContent = 'Show Details';
            }
        }
    </script>

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
