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
                                        <h1 class="text-2xl font-bold text-gray-800 text-center">
                                            {{ __('units_report.title') }} </h1>
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
                                <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 12px;">
                                    {{ __('units_report.available') }}</h3>
                                <div style="font-size: 32px; font-weight: bold;">
                                    {{ number_format($data['available'] ?? 0) }}
                                </div>
                            </div>

                            <div
                                style="flex: 1 1 calc(50% - 12px); background-color: #D6B29C; color: #543829; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 12px;">
                                    {{ __('units_report.reserved') }}</h3>
                                <div style="font-size: 32px; font-weight: bold;">
                                    {{ number_format($data['reserved']['total'] ?? 0) }}
                                </div>
                            </div>

                            <div
                                style="flex: 1 1 calc(50% - 12px); background-color: #00262f; color: #ffffff; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 12px;">
                                    {{ __('units_report.blocked') }}</h3>
                                <div style="font-size: 32px; font-weight: bold;">
                                    {{ number_format($data['blocked'] ?? 0) }}
                                </div>
                            </div>

                            <div
                                style="flex: 1 1 calc(50% - 12px); background-color: #543829; color: #ffffff; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 12px;">
                                    {{ __('units_report.contracted') }}</h3>
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
    <div id="pdf-loading-overlay" style="display: none;">
        <div class="loading-spinner">
            <div class="spinner-circle"></div>
            <div class="loading-text" style="margin-top: 20px; color: #333; text-align: center; font-size: 18px;">
                {{ __('messages.generating_report') }}
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <script>
        function goToMap() {
            const site = document.getElementById('siteSelect').value;
            if (!site) {
                alert('Please select a site first');
                return;
            }
                window.location.href = `/admin/items/map/${site}`;
        }
    </script>
    
    
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
        document.addEventListener('DOMContentLoaded', function() {

            const siteSelect = document.getElementById('siteSelect');
            siteSelect.addEventListener('change', function() {
                const selected = this.value;
                if (!selected) return;
                if (window.currentChart) {
                    window.currentChart.destroy();
                }

                const ctx = document.getElementById('chart-' + selected).getContext('2d');
                window.currentChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['{{ __('units_report.available') }}',
                            '{{ __('units_report.reserved') }}',
                            '{{ __('units_report.blocked') }}',
                            '{{ __('units_report.contracted') }}'
                        ],

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
        async function submitExport(type) {
            try {
                const site = document.getElementById('siteSelect').value;
                if (!site) return alert('Please select a site');
    
                const loadingOverlay = document.getElementById('pdf-loading-overlay');
                if (loadingOverlay) loadingOverlay.style.display = 'flex';
    
                const { jsPDF } = window.jspdf;
                const exportedBy = "{{ Auth::user()->name }}";
                const exportDate = new Date().toLocaleString('en-US');
    
                const siteName = site === 'dhahran' ? 'Azyan Dhahran' : 'Azyan Bashaer';
    
                const leftLogoUrl = "{{ asset('build/logo.png') }}";
                const rightLogoUrl = site === 'dhahran'
                    ? "{{ asset('images/logo5.png') }}"
                    : "{{ asset('images/logo6.png') }}";
    
                const chartCanvas = document.getElementById(`chart-${site}`);
                const siteSection = document.getElementById(`site-${site}`);
                const statsCards = siteSection.querySelectorAll('[style*="background-color"]');
    
                if (!chartCanvas || !siteSection || !statsCards.length) {
                    if (loadingOverlay) loadingOverlay.style.display = 'none';
                    alert('Required elements not found');
                    return;
                }
    
                if (type === 'pdf') {
                    const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });
    
                    const loadImage = (url) => {
                        return new Promise((resolve, reject) => {
                            const img = new Image();
                            img.crossOrigin = "anonymous";
                            img.onload = () => resolve(img);
                            img.onerror = () => reject(new Error('Failed to load image'));
                            img.src = url;
                        });
                    };
    
                    try {
                        const [leftLogoImg, rightLogoImg] = await Promise.all([
                            loadImage(leftLogoUrl),
                            loadImage(rightLogoUrl)
                        ]);
    
                        doc.addImage(leftLogoImg, 'PNG', 15, 10, 20, 20);
                        doc.addImage(rightLogoImg, 'PNG', 155, 10, 20, 20);
    
                        doc.setFontSize(16);
                        doc.text(`Units Report - ${siteName}`, 105, 50, { align: 'center' });
                        doc.line(10, 55, 200, 55);
    
                        let yPos = 60;
    
                        const chartImg = await html2canvas(chartCanvas, {
                            scale: 2,
                            useCORS: true,
                            backgroundColor: '#FFFFFF'
                        });
    
                        const chartDataUrl = chartImg.toDataURL('image/png', 1.0);
                        const chartAspectRatio = chartCanvas.height / chartCanvas.width;
                        const chartWidth = 190;
                        const chartHeight = chartWidth * chartAspectRatio;
    
                        doc.addImage(chartDataUrl, 'PNG', 10, yPos, chartWidth, chartHeight);
                        yPos += chartHeight + 10;
    
                        doc.setFontSize(14);
                        doc.text('Statistics:', 14, yPos);
                        yPos += 10;
    
                        doc.setFontSize(12);
                        statsCards.forEach(card => {
                            const title = card.querySelector('h3')?.textContent.trim() ?? '';
                            const value = card.querySelector('div[style*="font-size: 32px"]')?.textContent.trim() ?? '';
                            doc.text(`${title}: ${value}`, 14, yPos);
                            yPos += 8;
    
                            if (yPos > 270) {
                                doc.addPage();
                                yPos = 10;
                            }
                        });
    
                        const totalPages = doc.internal.getNumberOfPages();
                        for (let i = 1; i <= totalPages; i++) {
                            doc.setPage(i);
                            const pageHeight = doc.internal.pageSize.getHeight();
                            doc.setFontSize(10);
                            doc.text(`Exported by: ${exportedBy}`, 10, pageHeight - 20);
                            doc.text(`Export date: ${exportDate}`, 10, pageHeight - 15);
                            doc.text(`Page ${i} of ${totalPages}`, 200 - 10, pageHeight - 15, { align: 'right' });
                        }
    
                        doc.save(`${siteName}_Units_Report.pdf`);
                    } catch (error) {
                        console.error('PDF generation error:', error);
                        alert('Failed to generate PDF');
                    }
    
                } else if (type === 'csv') {
                    try {
                        const zip = new JSZip();
                        let csvContent = "\uFEFFMetric,Value\n";
    
                        statsCards.forEach(card => {
                            const title = card.querySelector('h3')?.textContent.trim() ?? '';
                            const value = card.querySelector('div[style*="font-size: 32px"]')?.textContent.trim() ?? '';
                            csvContent += `"${title.replace(/"/g, '""')}",${value}\n`;
                        });
    
                        csvContent += `\nExported by,${exportedBy}\nExport date,${exportDate}`;
    
                        const chartImg = await html2canvas(chartCanvas, {
                            scale: 2,
                            useCORS: true,
                            backgroundColor: '#FFFFFF'
                        });
    
                        const blob = await new Promise((resolve) =>
                            chartImg.toBlob(resolve, 'image/png', 1.0)
                        );
    
                        const csvBlob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    
                        zip.file(`${siteName}_Units_Data.csv`, csvBlob);
                        zip.file(`${siteName}_Units_Chart.png`, blob);
    
                        const content = await zip.generateAsync({ type: "blob" });
                        const url = URL.createObjectURL(content);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = `${siteName}_Units_Report.zip`;
                        document.body.appendChild(a);
                        a.click();
                        setTimeout(() => {
                            document.body.removeChild(a);
                            URL.revokeObjectURL(url);
                        }, 100);
                    } catch (error) {
                        console.error('CSV/ZIP generation error:', error);
                        alert('Failed to generate ZIP');
                    }
                }
    
            } catch (error) {
                console.error('Export error:', error);
                alert('Unexpected export error');
            } finally {
                const loadingOverlay = document.getElementById('pdf-loading-overlay');
                if (loadingOverlay) loadingOverlay.style.display = 'none';
            }
        }
    </script>
    
    
@endsection
