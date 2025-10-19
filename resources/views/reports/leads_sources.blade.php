@extends('layouts.app')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="py-20 px-8 container mx-auto">

        <section class="basic-select2">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-8">
                    <div class="card mt-[100px]">
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="text-bold-600 font-medium-2">
                                        <h1 class="text-2xl font-bold text-gray-800 text-center">{{ __('comparison_report.leads_sources_report') }}</h1>
                                    </div>
                                    <p>{{ __('comparison_report.select_location') }}</p>

                                    <select class="select2-placeholder form-control" id="location-filter">
                                        <option value="">{{ __('comparison_report.choose_location') }}</option>
                                        <optgroup label="sites">
                                            @foreach (array_keys($allData) as $location)
                                                <option value="{{ $location }}">
                                                    @if($location == 'aldhahran')
                                                        Azyan Dhahran
                                                    @elseif($location == 'albashaer')
                                                        Azyan Bashaer
                                                    @elseif($location == 'jaddah')
                                                        Azyan Jaddah
                                                        @elseif($location == 'alfursan')
                                                        Azyan Alfursan

                                                    @else
                                                        Azyan {{ ucfirst($location) }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>
                                <form id="exportForm" class="flex items-center gap-12 mt-12 justify-center">
                                    @csrf
                                    <input type="hidden" id="exportedBy" value="{{ Auth::user()->name }}">

                                    <!-- PDF Icon -->
                                    <a href="javascript:void(0);" onclick="submitExport('pdf')" title="Export PDF"
                                        class="transition duration-300 transform hover:scale-110 hover:rotate-6">
                                        <div class="fonticon-container flex items-center justify-center custom-hover-red">
                                            <div class="fonticon-wrap">
                                                <i class="fa fa-file-pdf-o text-red-500 hover:text-red-700 text-7xl"></i>
                                            </div>
                                        </div>
                                    </a>

                                    <!-- CSV Icon -->
                                    <a href="javascript:void(0);" onclick="submitExport('csv')" title="Export ZIP"
                                        class="transition duration-300 transform hover:scale-110 hover:-rotate-6">
                                        <div class="fonticon-container flex items-center justify-center">
                                            <div class="fonticon-wrap">
                                                <i class="fa fa-file-archive-o text-indigo-500 hover:text-indigo-700 text-7xl"></i>
                                            </div>
                                        </div>
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @if ($error)
            <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ $error }}
            </div>
        @endif

        @foreach ($allData as $location => $data)
            @if (count($data) > 0)
                <div class="mt-10 chart-section" id="chart-section-{{ $location }}" style="display: none;">
                    <div class="bg-white p-6 rounded-lg shadow-sm mt-8">
                        <canvas id="chart-{{ $location }}" height="120"></canvas>
                    </div>

                    <!-- Data List -->
                    <div class="bg-white p-6 rounded-lg shadow-sm mt-8">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700 text-center">{{ __('comparison_report.distribution_by_source') }}</h3>
                        <section id="section-content-{{ $location }}" class="hidden">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-content collapse show">
                                            <div class="card-body card-dashboard">
                                                <table class="table table-striped table-bordered dataex-key-basic">
                                                    <thead class="bg-gray-100">
                                                        <tr>
                                                            <th class="py-2 px-4 text-gray-600 font-semibold">{{ __('comparison_report.source') }}</th>
                                                            <th class="py-2 px-4 text-gray-600 font-semibold">{{ __('comparison_report.count') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($data as $source => $count)
                                                            <tr class="hover:bg-gray-50">
                                                                <td class="py-2 px-4 text-gray-700">{{ $source }}</td>
                                                                <td class="py-2 px-4">
                                                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm inline-block">
                                                                        {{ $count }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <a href="javascript:void(0);" class="text-blue-500 hover:text-blue-700 hover:underline"
                            onclick="toggleTable('{{ $location }}')">
                            {{ __('comparison_report.show_details') }}
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-white p-6 rounded-lg shadow-sm mt-10">
                    <div class="text-center text-gray-500">
                        {{ __('comparison_report.no_data', ['location' => $location == 'aldhahran' ? 'Dhahran' : ($location == 'albashaer' ? 'Bashaer' : ($location == 'jaddah' ? 'Jaddah' : ucfirst($location)))]) }}
                    </div>
                </div>
            @endif
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

    <!-- JS Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <!-- Location Switcher Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const locationSelect = document.getElementById('location-filter');
            const allSections = document.querySelectorAll('.chart-section');
            const chartInstances = {};

            locationSelect.addEventListener('change', function() {
                const selected = this.value;

                allSections.forEach(section => {
                    section.style.display = 'none';
                });

                if (selected) {
                    const selectedSection = document.getElementById(`chart-section-${selected}`);
                    if (selectedSection) {
                        selectedSection.style.display = 'block';

                        if (!chartInstances[selected]) {
                            const ctx = document.getElementById(`chart-${selected}`).getContext('2d');
                            const chartData = @json($allData);

                            chartInstances[selected] = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: Object.keys(chartData[selected]),
                                    datasets: [{
                                        label: "{{ __('comparison_report.number_of_leads') }}",
                                        data: Object.values(chartData[selected]),
                                        backgroundColor: 'rgba(60, 60, 60, 0.8)',
                                    }]
                                },
                                options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                precision: 0
                                            }
                                        }
                                    }
                                }
                            });
                        }
                    }
                }
            });
        });

        function toggleTable(location) {
            const section = document.getElementById(`section-content-${location}`);
            if (section) {
                section.classList.toggle('hidden');
            }
        }

        function submitExport(type) {
            const site = document.getElementById('location-filter').value;
            if (!site) return alert('Please select a site');

            // Show loading overlay
            const loadingOverlay = document.getElementById('pdf-loading-overlay');
            loadingOverlay.style.display = 'flex';

            const chartSection = document.getElementById(`chart-section-${site}`);
            const chartCanvas = chartSection.querySelector('canvas');
            const detailsContent = document.getElementById(`section-content-${site}`);

            if (!chartCanvas || !detailsContent) {
                loadingOverlay.style.display = 'none';
                alert('Required elements not found');
                return;
            }

            // Store original state
            const originalDetailsClass = detailsContent.classList.contains('hidden');
            detailsContent.classList.remove('hidden');

            const exportedBy = document.getElementById('exportedBy').value;
            const exportDate = new Date().toLocaleString();

            // Left logo (always the same)
            const leftLogoUrl = "{{ asset('build/logo.png') }}";

            // Right logo (different for each site)
            let rightLogoUrl;
            if (site.toLowerCase() === 'aldhahran') {
                rightLogoUrl = "{{ asset('images/logo5.png') }}";
            } else if (site.toLowerCase() === 'albashaer') {
                rightLogoUrl = "{{ asset('images/logo6.png') }}";
            } else if (site.toLowerCase() === 'jaddah') {
                rightLogoUrl = "{{ asset('images/jadah.png') }}";
            } else {
                rightLogoUrl = "{{ asset('images/default.png') }}";
            }

            if (type === 'pdf') {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF('p', 'mm', 'a4');

                // Load logos
                const leftLogoImg = new Image();
                leftLogoImg.crossOrigin = "anonymous";
                leftLogoImg.src = leftLogoUrl;

                const rightLogoImg = new Image();
                rightLogoImg.crossOrigin = "anonymous";
                rightLogoImg.src = rightLogoUrl;

                Promise.all([
                    new Promise((resolve) => { leftLogoImg.onload = resolve; }),
                    new Promise((resolve) => { rightLogoImg.onload = resolve; }),
                    html2canvas(chartCanvas),
                    html2canvas(detailsContent)
                ]).then(([_, __, chartImg, detailsImg]) => {
                    const imgWidth = 190;
                    let yPos = 10;

                    // Add logos
                    doc.addImage(leftLogoImg, 'PNG', 15, yPos, 20, 20);
                    doc.addImage(rightLogoImg, 'PNG', 155, yPos, 20, 20);

                    yPos += 45;

                    // Add title
                    doc.setFontSize(16);
                    let siteName = '';
                    if (site === 'aldhahran') siteName = 'Dhahran';
                    else if (site === 'albashaer') siteName = 'Bashaer';
                    else if (site === 'jaddah') siteName = 'Jaddah';
                    else if (site === 'alfursan') siteName = 'Alfursan';
                    else siteName = site.charAt(0).toUpperCase() + site.slice(1);

                    doc.text(`Leads Sources Report - Azyan ${siteName}`, 105, yPos, { align: 'center' });

                    yPos += 15;

                    // Add chart
                    const chartHeight = (chartImg.height * imgWidth) / chartImg.width;
                    doc.addImage(chartImg, 'PNG', 10, yPos, imgWidth, chartHeight);
                    yPos += chartHeight + 10;

                    // Add details table
                    const detailsHeight = (detailsImg.height * imgWidth) / detailsImg.width;
                    doc.addImage(detailsImg, 'PNG', 10, yPos, imgWidth, detailsHeight);

                    // Add footer
                    doc.setFontSize(10);
                    const pageHeight = doc.internal.pageSize.height;
                    doc.text(`Exported by: ${exportedBy}`, 10, pageHeight - 20);
                    doc.text(`Export date: ${exportDate}`, 10, pageHeight - 15);

                    doc.save(`${site}_leads_report.pdf`);
                }).catch(error => {
                    console.error('Export failed:', error);
                    alert('Export failed, check console for details');
                }).finally(() => {
                    loadingOverlay.style.display = 'none';
                    if (originalDetailsClass) {
                        detailsContent.classList.add('hidden');
                    }
                });
            } else if (type === 'csv') {
                const data = @json($allData);
                let csvContent = "Source,Count\n";
                csvContent += Object.entries(data[site])
                    .map(([source, count]) => `${source},${count}`)
                    .join("\n");

                csvContent += `\n\nExported by:,${exportedBy}`;
                csvContent += `\nExport date:,${exportDate}`;

                // Create a blob for the CSV
                const csvBlob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });

                // Create chart image
                html2canvas(chartCanvas).then(canvas => {
                    canvas.toBlob(chartBlob => {
                        // Create zip file
                        const zip = new JSZip();
                        zip.file(`${site}_data.csv`, csvBlob);
                        zip.file(`${site}_chart.png`, chartBlob);

                        zip.generateAsync({ type: "blob" }).then(content => {
                            const url = window.URL.createObjectURL(content);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = `${site}_report.zip`;
                            a.click();
                            window.URL.revokeObjectURL(url);
                            loadingOverlay.style.display = 'none';
                        });
                    }, 'image/png');
                }).catch(error => {
                    console.error('Export failed:', error);
                    loadingOverlay.style.display = 'none';
                    alert('Export failed, check console for details');
                });
            }
        }
    </script>
@endsection
