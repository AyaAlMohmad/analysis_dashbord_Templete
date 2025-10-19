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
                                        <div class="flex items-center gap-4 mt-4">
                                            <!-- Dhahran Log -->
                                            <a href="{{ route('admin.leads.log', 'dhahran') }}"
                                                class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl"
                                                title="View Dhahran Log">
                                                {{ __('leads.view_log_dhahran') }}<i class="fas fa-clipboard-list"></i>
                                            </a>
                                            </div>
                                            <div class="flex items-center gap-4 mt-4">
                                            <!-- Bashaer Log -->
                                            <a href="{{ route('admin.leads.log', 'bashaer') }}"
                                                class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl"
                                                title="View Bashaer Log">
                                                {{ __('leads.view_log_bashaer') }} <i class="fas fa-clipboard-list"></i>
                                            </a>
                                            </div>
                                            <div class="flex items-center gap-4 mt-4">
                                            <a href="{{ route('admin.leads.log', 'jeddah') }}"
                                                class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl"
                                                title="View Jeddah Log">
                                                {{ __('leads.view_log_jeddah') }} <i class="fas fa-clipboard-list"></i>
                                            </a>
                                        </div>
                                        <div class="flex items-center gap-4 mt-4">
                                            <a href="{{ route('admin.leads.log', 'alfursan') }}"
                                                class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl"
                                                title="View Alfursan Log">
                                                {{ __('leads.view_log_alfursan') }} <i class="fas fa-clipboard-list"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <p class="text-center mt-4">{{ __('leads.select_location') }}</p>

                                <select id="siteSelect" class="select2-placeholder form-control mt-2">
                                    <option value="">{{ __('leads.choose_location') }}</option>
                                    <option value="dhahran">{{ __('leads.dhahran') }} </option>
                                    <option value="bashaer">{{ __('leads.bashaer') }} </option>
                                    <option value="jeddah">{{ __('leads.jeddah') }} </option>
                                    <option value="alfursan">{{ __('leads.alfursan') }} </option>
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


            @foreach (['dhahran' => 'Azyan Dhahran', 'bashaer' => 'Azyan Bashaer', 'jeddah' => 'Azyan Jeddah', 'alfursan' => 'Azyan Alfursan'] as $key => $title)
                @php
                    // Fixed ternary operator with proper syntax
                    $leadsData = $key === 'dhahran'
                        ? $leadsAzyanDhahran
                        : ($key === 'bashaer'
                            ? $leadsAzyanBashaer
                            : ($key === 'jeddah'
                                ? $leadsAzyanJeddah
                                : $leadsAzyanAlfursan));
                @endphp

                <div id="site-{{ $key }}" class="site-container hidden">
                    <div class="bg-white p-8 rounded-lg shadow-md mt-12">
                        <div id="export-header-{{ $key }}" class="hidden text-center mb-6">
                            <img src="{{ asset('build/logo.png') }}" alt="Logo"
                                style="max-height:80px;margin-bottom:10px;">
                            <h2 class="text-2xl font-bold text-gray-800">Leads Report - {{ $title }}</h2>
                            <hr class="my-4">
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-sm mt-8">
                            <canvas id="chart-{{ $key }}" class="w-full" style="height:400px;"></canvas>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-sm mt-8">

                            <h3 class="text-lg font-semibold mb-4 text-gray-700 text-center">
                                {{ __('leads.daily_details') }} </h3>

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
                                                                    {{ __('leads.date') }}</th>
                                                                <th
                                                                    class="py-2 px-4 text-green-700 font-semibold text-center">
                                                                    {{ __('leads.added') }}</th>
                                                                <th
                                                                    class="py-2 px-4 text-purple-700 font-semibold text-center">
                                                                    {{ __('leads.edited') }}</th>
                                                                <th
                                                                    class="py-2 px-4 text-blue-700 font-semibold text-center">
                                                                    {{ __('leads.total') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($leadsData as $date => $stats)
                                                                <tr class="hover:bg-gray-50 text-center">
                                                                    <td class="py-2 px-4">
                                                                        {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                                                                    </td>
                                                                    <td class="py-2 px-4 text-green-600">
                                                                        {{ $stats['added'] }}</td>
                                                                    <td class="py-2 px-4 text-purple-600">
                                                                        {{ $stats['edited'] }}</td>
                                                                    <td class="py-2 px-4 font-bold">
                                                                        {{ $stats['added'] + $stats['edited'] }}</td>
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

                            <a href="javascript:void(0);"
                                class="text-blue-500 hover:text-blue-700 hover:underline mt-4 block text-center"
                                onclick="toggleTable('{{ $key }}')">
                                {{ __('leads.show_details') }}
                            </a>
                        </div>

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const siteSelect = document.getElementById('siteSelect');
            const containers = document.querySelectorAll('.site-container');
            const charts = {};

            const data = {
                dhahran: {
                    dates: @json(array_keys($leadsAzyanDhahran)),
                    added: @json(array_column($leadsAzyanDhahran, 'added')),
                    edited: @json(array_column($leadsAzyanDhahran, 'edited'))
                },
                bashaer: {
                    dates: @json(array_keys($leadsAzyanBashaer)),
                    added: @json(array_column($leadsAzyanBashaer, 'added')),
                    edited: @json(array_column($leadsAzyanBashaer, 'edited'))
                },
                jeddah: {
                    dates: @json(array_keys($leadsAzyanJeddah)),
                    added: @json(array_column($leadsAzyanJeddah, 'added')),
                    edited: @json(array_column($leadsAzyanJeddah, 'edited'))
                },
                alfursan: {
                    dates: @json(array_keys($leadsAzyanAlfursan)),
                    added: @json(array_column($leadsAzyanAlfursan, 'added')),
                    edited: @json(array_column($leadsAzyanAlfursan, 'edited'))
                }
            };

            siteSelect.addEventListener('change', function() {
                containers.forEach(div => div.classList.add('hidden'));
                const site = this.value;
                if (site && data[site]) {
                    document.getElementById(`site-${site}`).classList.remove('hidden');

                    if (!charts[site]) {
                        const ctx = document.getElementById(`chart-${site}`).getContext('2d');
                        charts[site] = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data[site].dates,
                                datasets: [{
                                        label: "{{ __('leads.added') }}",
                                        data: data[site].added,
                                        backgroundColor: '#A2C2D6',
                                    },
                                    {
                                        label: "{{ __('leads.edited') }}",
                                        data: data[site].edited,
                                        backgroundColor: '#543829',
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: {
                                        stacked: true
                                    },
                                    y: {
                                        stacked: true,
                                        beginAtZero: true
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
        const site = document.getElementById('siteSelect').value;
        if (!site) return alert('Please select a site');

        // Show loading overlay
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
            : (site.toLowerCase() === 'bashaer'
                ? "{{ asset('images/logo6.png') }}"
                : "{{ asset('images/jadah.png') }}");

        const chartCanvas = document.getElementById(`chart-${site}`);
        const detailsTable = document.querySelector(`#daily-details-${site} table`);

        if (!chartCanvas || !detailsTable) {
            if (loadingOverlay) loadingOverlay.style.display = 'none';
            alert('Required elements not found');
            return;
        }

        if (type === 'pdf') {
            const doc = new jsPDF('p', 'mm', 'a4');

            // Load both logos
            const leftLogoImg = new Image();
            leftLogoImg.crossOrigin = "anonymous";
            leftLogoImg.src = leftLogoUrl;

            const rightLogoImg = new Image();
            rightLogoImg.crossOrigin = "anonymous";
            rightLogoImg.src = rightLogoUrl;

            // Wait for both logos to load
            await Promise.all([
                new Promise(resolve => { leftLogoImg.onload = resolve; }),
                new Promise(resolve => { rightLogoImg.onload = resolve; })
            ]);

            // Add logos (left and right)
            doc.addImage(leftLogoImg, 'PNG', 15, 10, 20, 20); // Left logo (Tatwir)
            doc.addImage(rightLogoImg, 'PNG', 155, 10, 20, 20); // Right logo (conditional)

            // Add title and line
            doc.setFontSize(16);
            doc.text(`Leads Report - Azyan ${site.charAt(0).toUpperCase() + site.slice(1)}`, 105, 50, {
                align: 'center'
            });
            doc.line(10, 55, 200, 55);

            let yPos = 60;

            // Add chart
            const chartImg = await html2canvas(chartCanvas);
            const chartDataUrl = chartImg.toDataURL('image/png');
            doc.addImage(chartDataUrl, 'PNG', 10, yPos, 190, 80);
            yPos += 90;

            // Prepare table data
            const rows = [];
            const tableRows = detailsTable.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                const date = row.children[0]?.innerText.trim() ?? '';
                const added = row.children[1]?.innerText.trim() ?? '';
                const edited = row.children[2]?.innerText.trim() ?? '';
                const total = row.children[3]?.innerText.trim() ?? '';
                rows.push([date, added, edited, total]);
            });

            // Add table
            await doc.autoTable({
                head: [['Date', 'Added', 'Edited', 'Total']],
                body: rows,
                startY: yPos,
                theme: 'grid',
                styles: { fontSize: 10 },
                headStyles: { fillColor: [41, 128, 185] },
                alternateRowStyles: { fillColor: [240, 240, 240] },
                margin: { top: 10 }
            });

            // Add footer
            const totalPages = doc.internal.getNumberOfPages();
            doc.setPage(totalPages);
            const pageHeight = doc.internal.pageSize.height || doc.internal.pageSize.getHeight();

            doc.setFontSize(10);
            doc.text(`Exported by: ${exportedBy}`, 10, pageHeight - 20);
            doc.text(`Export date: ${exportDate}`, 10, pageHeight - 15);
            doc.text(`Page ${totalPages} of ${totalPages}`, 200 - 10, pageHeight - 15, {
                align: 'right'
            });

            doc.save(`${site}_leads_report.pdf`);

        } else if (type === 'csv') {
            const zip = new JSZip();

            let csvContent = "Date,Added,Edited,Total\n";
            const tableRows = detailsTable.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                const date = row.children[0]?.innerText.trim() ?? '';
                const added = row.children[1]?.innerText.trim() ?? '';
                const edited = row.children[2]?.innerText.trim() ?? '';
                const total = row.children[3]?.innerText.trim() ?? '';
                csvContent += `${date},${added},${edited},${total}\n`;
            });

            csvContent += `\nExported by,${exportedBy}\nExport date,${exportDate}`;

            html2canvas(chartCanvas).then(canvas => {
                canvas.toBlob(blob => {
                    zip.file(`${site}_details.csv`, csvContent);
                    zip.file(`${site}_chart.png`, blob);

                    zip.generateAsync({ type: "blob" }).then(content => {
                        const url = URL.createObjectURL(content);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = `${site}_leads_report.zip`;
                        a.click();
                        URL.revokeObjectURL(url);
                    });
                }, 'image/png');
            });
        }

        // Hide loading overlay
        if (loadingOverlay) loadingOverlay.style.display = 'none';
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
