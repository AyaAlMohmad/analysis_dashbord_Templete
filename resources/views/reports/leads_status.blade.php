@extends('layouts.app')
@section('content')
    <!-- مكتبات الرسوميات -->
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
                                            {{ __('leads.report_title') }}</h1>
                                    </div>
                                </div>


                                <p class="text-center mt-4">{{ __('leads.select_location') }}</p>

                                <select id="siteSelect" class="select2-placeholder form-control mt-2">
                                    <option value="">{{ __('leads.choose_location') }}</option>
                                    <option value="dhahran">{{ __('leads.dhahran') }}</option>
                                    <option value="bashaer">{{ __('leads.bashaer') }}</option>
                                    <option value="jeddah">{{ __('leads.jeddah') }}</option>
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
                        </div>
                    </div>
                </div>
            </div>

            <!-- Site Sections -->
            @foreach (['dhahran' => 'Azyan Dhahran', 'bashaer' => 'Azyan Bashaer','jeddah'=>'Azyan Jeddah'] as $key => $label)
                <div class="site-section" id="site-{{ $key }}" style="display: none;">


                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg my-8">
                        <div class="p-4 bg-white shadow-sm rounded-lg">
                            <h2 class="text-2xl font-bold text-gray-800 text-center">
                                {{ $label }} {{ __('leads.status_overview') }}</h2>
                        </div>

                        @if ($errors[$key])
                            <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                {{ $errors[$key] }}
                            </div>
                        @else
                            <!-- Total Leads Card -->
                            <div class="p-9 text-center">
                                <div class="bg-indigo-50 p-6 rounded-lg shadow-sm max-w-xs mx-auto">
                                    <h3 class="text-lg font-semibold text-indigo-800 mb-2">
                                        {{ __('leads.total') }}
                                    </h3>
                                    <h3 class="text-3xl font-bold text-indigo-600"> {{ number_format($totals[$key]) }}</h3>
                                </div>
                            </div>

                            <!-- Chart Section -->
                            <div class="w-full max-w-4xl mx-auto mt-8 p-6">
                                <div class="bg-white p-6 rounded-lg shadow-sm relative">
                                    <div wire:ignore class="relative h-[400px]">
                                        <canvas id="chart-{{ $key }}" class="w-full"
                                            style="height:400px;"></canvas>


                                    </div>
                                </div>
                            </div>

                            <!-- Details Section -->
                            <div class="bg-white p-6 rounded-lg shadow-sm mt-8">
                                <h3 class="text-lg font-semibold mb-4 text-gray-700 text-center">
                                    {{ __('leads.status_overview') }}</h3>

                                <section id="daily-details-{{ $key }}" class="hidden">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-content collapse show">
                                                    <div class="card-body card-dashboard">
                                                        <table class="table table-striped table-bordered">
                                                            <thead class="bg-gray-100">
                                                                <tr>
                                                                    <th
                                                                        class="py-2 px-4 text-gray-600 font-semibold text-center">
                                                                        {{ __('leads.status') }}</th>
                                                                    <th
                                                                        class="py-2 px-4 text-green-700 font-semibold text-center">
                                                                        {{ __('leads.count') }}</th>

                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($key === 'dhahran' ? $dataDhahran : $dataBashaer as $status => $count)
                                                                    <tr class="hover:bg-gray-50 text-center">

                                                                        <td class="py-2 px-4 text-green-600">
                                                                            {{ $status }}</td>
                                                                        <td class="py-2 px-4 text-purple-600">
                                                                            {{ $count }}</td>

                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div> <!-- card-body -->
                                                </div> <!-- card-content -->
                                            </div> <!-- card -->
                                        </div> <!-- col -->
                                    </div> <!-- row -->
                                </section>

                                <!-- زر Show/Hide Details -->
                                <a href="javascript:void(0);"
                                    class="text-blue-500 hover:text-blue-700 hover:underline mt-4 block text-center"
                                    onclick="toggleTable('{{ $key }}')">
                                    {{ __('leads.show_details') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </section>
    </div>
    <div id="pdf-loading-overlay" style="display: none;">
        <div class="loading-spinner">
            <div class="spinner-circle"></div>
            <div class="loading-text" style="margin-top: 20px; color: #333; text-align: center; font-size: 18px;">
                {{ __('messages.generating_report') }}
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="jsPDF/dist/jspdf.umd.min.js"></script>
    <script src="jsPDF/fonts/Amiri-normal.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const siteSelect = document.getElementById('siteSelect');
            const containers = document.querySelectorAll('.site-section');
            const charts = {};

            const data = {
                dhahran: {
                    labels: @json(array_keys($dataDhahran)),
                    counts: @json(array_values($dataDhahran))
                },
                bashaer: {
                    labels: @json(array_keys($dataBashaer)),
                    counts: @json(array_values($dataBashaer))
                },
                jeddah: {
                    labels: @json(array_keys($dataJeddah)),
                    counts: @json(array_values($dataJeddah))
                }
            };

            siteSelect.addEventListener('change', function() {

                containers.forEach(div => div.style.display = 'none');

                const site = this.value;

                if (site && data[site]) {

                    document.getElementById(`site-${site}`).style.display = 'block';
                    if (!charts[site]) {
                        const canvas = document.getElementById(`chart-${site}`);
                        const ctx = canvas.getContext('2d');

                        charts[site] = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data[site].labels,
                                datasets: [{
                                    label: 'Number of Leads',
                                    data: data[site].counts,
                                    backgroundColor: '#3B82F6',
                                    borderColor: '#3B82F6',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            stepSize: 1
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
            });
        });
    </script>


<script>
    async function submitExport(type) {
        try {
            const site = document.getElementById('siteSelect').value;
            if (!site) {
                alert('Please select a site first.');
                return;
            }

            const loadingOverlay = document.getElementById('pdf-loading-overlay');
            if (loadingOverlay) loadingOverlay.style.display = 'flex';

            const { jsPDF } = window.jspdf;
            const exportedBy = "{{ Auth::user()->name }}";
            const exportDate = new Date().toLocaleString();

            // Left logo (always Tatwir logo)
            const leftLogoUrl = "{{ asset('build/logo.png') }}";

            // Right logo (conditional based on site)
            const rightLogoUrl = site.toLowerCase() === 'dhahran'
                ? "{{ asset('images/logo5.png') }}"
                : site.toLowerCase() === 'bashaer' ? "{{ asset('images/logo6.png') }}":
                "{{ asset('images/jadah.png') }}";

            const chartCanvas = document.getElementById(`chart-${site}`);
            const detailsTable = document.querySelector(`#daily-details-${site} table`);

            if (!chartCanvas || !detailsTable) {
                if (loadingOverlay) loadingOverlay.style.display = 'none';
                alert('Required elements not found');
                return;
            }

            if (type === 'pdf') {
                const doc = new jsPDF('p', 'mm', 'a4');

                // Load both logos with error handling
                const loadImage = (url) => {
                    return new Promise((resolve, reject) => {
                        const img = new Image();
                        img.crossOrigin = "anonymous";
                        img.onload = () => resolve(img);
                        img.onerror = () => reject(new Error(`Failed to load image: ${url}`));
                        img.src = url;
                    });
                };

                try {
                    const [leftLogoImg, rightLogoImg] = await Promise.all([
                        loadImage(leftLogoUrl),
                        loadImage(rightLogoUrl)
                    ]);

                    // Add logos (left and right)
                    doc.addImage(leftLogoImg, 'PNG', 15, 10, 20, 20);
                    doc.addImage(rightLogoImg, 'PNG', 155, 10, 20, 20);

                    // Add title and line
                    doc.setFontSize(16);
                    doc.text(`Leads Report - Azyan ${site.charAt(0).toUpperCase() + site.slice(1)}`, 105, 50, {
                        align: 'center'
                    });
                    doc.line(10, 55, 200, 55);

                    let yPos = 60;

                    // Render chart with better quality settings
                    const chartImg = await html2canvas(chartCanvas, {
                        scale: 2, // Higher quality
                        logging: true,
                        useCORS: true,
                        allowTaint: true,
                        backgroundColor: '#FFFFFF'
                    });

                    const chartDataUrl = chartImg.toDataURL('image/png', 1.0);
                    if (!chartDataUrl || chartDataUrl === 'data:,') {
                        throw new Error('Failed to generate chart image');
                    }

                    // Add chart with proper aspect ratio
                    const chartAspectRatio = chartCanvas.height / chartCanvas.width;
                    const chartWidth = 190;
                    const chartHeight = chartWidth * chartAspectRatio;
                    doc.addImage(chartDataUrl, 'PNG', 10, yPos, chartWidth, chartHeight);
                    yPos += chartHeight + 10;

                    // Prepare table data
                    const rows = [];
                    const tableRows = detailsTable.querySelectorAll('tbody tr');
                    tableRows.forEach(row => {
                        const status = row.children[0]?.innerText.trim() ?? '';
                        const count = row.children[1]?.innerText.trim() ?? '';
                        rows.push([status, count]);
                    });

                    // Add table
                    await doc.autoTable({
                        head: [['Status', 'Count']],
                        body: rows,
                        startY: yPos,
                        theme: 'grid',
                        styles: { fontSize: 10 },
                        headStyles: { fillColor: [41, 128, 185] },
                        alternateRowStyles: { fillColor: [240, 240, 240] },
                        margin: { top: 10 }
                    });

                    // Add footer to all pages
                    const totalPages = doc.internal.getNumberOfPages();
                    for (let i = 1; i <= totalPages; i++) {
                        doc.setPage(i);
                        const pageHeight = doc.internal.pageSize.getHeight();

                        doc.setFontSize(10);
                        doc.text(`Exported by: ${exportedBy}`, 10, pageHeight - 20);
                        doc.text(`Export date: ${exportDate}`, 10, pageHeight - 15);
                        doc.text(`Page ${i} of ${totalPages}`, 200 - 10, pageHeight - 15, {
                            align: 'right'
                        });
                    }

                    doc.save(`${site}_leads_report.pdf`);

                } catch (error) {
                    console.error('PDF generation error:', error);
                    alert('Failed to generate PDF. See console for details.');
                }

            } else if (type === 'csv') {
                try {
                    const zip = new JSZip();

                    // Prepare CSV content
                    let csvContent = "Status,Count\n";
                    const tableRows = detailsTable.querySelectorAll('tbody tr');
                    tableRows.forEach(row => {
                        const status = row.children[0]?.innerText.trim() ?? '';
                        const count = row.children[1]?.innerText.trim() ?? '';
                        csvContent += `"${status.replace(/"/g, '""')}",${count}\n`;
                    });

                    csvContent += `\nExported by,${exportedBy}\nExport date,${exportDate}`;

                    // Generate chart image with better settings
                    const chartImg = await html2canvas(chartCanvas, {
                        scale: 2,
                        logging: true,
                        useCORS: true,
                        allowTaint: true,
                        backgroundColor: '#FFFFFF'
                    });

                    const blob = await new Promise((resolve) => {
                        chartImg.toBlob(resolve, 'image/png', 1.0);
                    });

                    if (!blob) {
                        throw new Error('Failed to generate chart image blob');
                    }

                    // Add files to zip
                    zip.file(`${site}_details.csv`, csvContent);
                    zip.file(`${site}_chart.png`, blob);

                    // Generate and download zip
                    const content = await zip.generateAsync({ type: "blob" });
                    const url = URL.createObjectURL(content);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `${site}_leads_report.zip`;
                    document.body.appendChild(a);
                    a.click();

                    // Cleanup
                    setTimeout(() => {
                        document.body.removeChild(a);
                        URL.revokeObjectURL(url);
                    }, 100);

                } catch (error) {
                    console.error('CSV/ZIP generation error:', error);
                    alert('Failed to generate CSV/ZIP. See console for details.');
                }
            }

        } catch (error) {
            console.error('Export error:', error);
            alert('An unexpected error occurred during export.');
        } finally {
            const loadingOverlay = document.getElementById('pdf-loading-overlay');
            if (loadingOverlay) loadingOverlay.style.display = 'none';
        }
    }
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
@endsection
