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
                                        <h1 class="text-2xl font-bold text-gray-800 text-center">{{ __('appointments_report.title') }}</h1>
                                        <div class="flex items-center gap-4 mt-4">
                                            <!-- Dhahran Log -->
                                            <a href="{{ route('admin.appointments.log', 'dhahran') }}"
                                                class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl"
                                                title="View Dhahran Log">
                                                <i class="fas fa-clipboard-list"></i> {{ __('appointments_report.view_log_dhahran') }}
                                            </a>
                                        </div>
                                        <div class="flex items-center gap-4 mt-4">
                                            <!-- Bashaer Log -->
                                            <a href="{{ route('admin.appointments.log', 'bashaer') }}"
                                                class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl"
                                                title="View Bashaer Log">
                                                <i class="fas fa-clipboard-list"></i>{{__('appointments_report.view_log_bashaer')}}
                                            </a>
                                        </div>
                                        <div class="flex items-center gap-4 mt-4">
                                            <!-- Jeddah Log -->
                                            <a href="{{ route('admin.appointments.log', 'jeddah') }}"
                                                class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl"
                                                title="View Jeddah Log">
                                                <i class="fas fa-clipboard-list"></i>{{__('appointments_report.view_log_jeddah')}}
                                            </a>
                                        </div>
                                        <div class="flex items-center gap-4 mt-4">
                                            <!-- Alfursan Log -->
                                            <a href="{{ route('admin.appointments.log', 'alfursan') }}"
                                                class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl"
                                                title="View Alfursan Log">
                                                <i class="fas fa-clipboard-list"></i>{{__('appointments_report.view_log_alfursan')}}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <p class="text-center mt-4">{{ __('appointments_report.select_location') }}</p>
                            <select id="siteSelect" class="select2-placeholder form-control mt-2">
                                <option value="">{{ __('appointments_report.choose_location') }}</option>
                                <option value="dhahran">{{ __('appointments_report.location_dhahran') }}</option>
                                <option value="bashaer">{{ __('appointments_report.location_bashaer') }}</option>
                                <option value="jeddah">{{ __('appointments_report.location_jeddah') }}</option>
                                <option value="alfursan">{{ __('appointments_report.location_alfursan') }}</option>
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
                                        <i class="fa fa-file-archive-o text-indigo-500 hover:text-indigo-700 text-7xl"></i>
                                    </div>
                                </div>
                            </a>
                        </form>
                    </div>
                </div>
            </div>

        </section>


        @foreach (['dhahran' =>  __('appointments_report.location_dhahran') , 'bashaer' => __('appointments_report.location_bashaer'), 'jeddah' => __('appointments_report.location_jeddah') ] as $key => $label)
            <div class="site-section" id="site-{{ $key }}" style="display: none;">
                <!-- Hidden Export Header -->
                <div class="export-header hidden" id="export-header-{{ $key }}">
                    <h1 class="text-xl font-bold mb-1">{{ __('appointments_report.title') }}</h1>
                    <h2 class="text-lg text-gray-600">{{ __('appointments_report.location') }} {{ ucfirst($label) }}</h2><br>
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
                                <h3 class="text-lg font-semibold text-indigo-800 mb-2">
                                    {{ __('appointments_report.total_appointments') }}</h3>
                                <div class="text-3xl font-bold text-indigo-600">
                                    {{ number_format($appointments[$key]['total']) }}
                                </div>
                            </div>
                        </div>
                        <div class="w-full max-w-4xl mx-auto mt-8 p-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm relative">
                                <canvas id="chart-{{ $key }}" height="400"></canvas>
                            </div>
                        </div>



                        <!-- Toggle Table -->
                        <div class="bg-white p-6 rounded-lg shadow-sm mt-8">
                            <h3 class="text-lg font-semibold mb-4 text-gray-700 text-center">{{ __('appointments_report.distribution_by_date') }}</h3>
                            <section id="section-content-{{ $key }}" class="hidden">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-content collapse show">
                                                <div class="card-body card-dashboard">
                                                    <table class="table table-striped table-bordered dataex-key-basic">
                                                        <thead class="bg-gray-100">
                                                            <tr>
                                                                <th
                                                                    class="py-2 px-4 text-gray-600 font-semibold text-center">
                                                                    {{ __('appointments_report.date') }}</th>
                                                                <th
                                                                    class="py-2 px-4 text-green-700 font-semibold text-center">
                                                                    {{ __('appointments_report.appointments_count') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($appointments[$key]['by_date'] as $date => $count)
                                                                <tr class="hover:bg-gray-50 text-center">
                                                                    <td class="py-2 px-4 text-gray-600">
                                                                        {{ \Carbon\Carbon::parse($date)->translatedFormat('d M Y') }}
                                                                    </td>
                                                                    <td class="py-2 px-4 text-green-600">
                                                                        {{ $count }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- col -->
                                </div> <!-- row -->
                            </section>

                            <!-- زر Show/Hide Details -->
                            <a href="javascript:void(0);"
                                class="text-blue-500 hover:text-blue-700 hover:underline mt-4 block text-center"
                                onclick="toggleTable('{{ $key }}')">
                              {{ __('appointments_report.show_details') }}
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
    <!-- Required scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

    <!-- Chart Script -->
    <script>
        function toggleTable(key) {
            const section = document.getElementById(`section-content-${key}`);
            const link = document.querySelector(`[onclick="toggleTable('${key}')"]`);

            if (!section || !link) {
                console.error('Section or link not found');
                return;
            }

            if (section.classList.contains('hidden')) {
                section.classList.remove('hidden');
                link.textContent = 'Hide Details';
            } else {
                section.classList.add('hidden');
                link.textContent = 'Show Details';
            }
        }
    </script>
    <script>
        let charts = {};

        document.getElementById('siteSelect').addEventListener('change', function() {
            var selectedSite = this.value;


            document.querySelectorAll('.site-section').forEach(section => {
                section.style.display = 'none';
            });

            if (selectedSite) {
                var siteDiv = document.getElementById('site-' + selectedSite);
                if (siteDiv) {
                    siteDiv.style.display = 'block';
                }


                if (!charts[selectedSite]) {
                    createChart(selectedSite);
                }
            }
        });

        function createChart(siteKey) {
            const ctx = document.getElementById('chart-' + siteKey);
            if (!ctx) {
                console.error('Canvas not found for site:', siteKey);
                return;
            }

            const labels = {!! json_encode(
                array_map(function ($appointments) {
                    return array_keys($appointments['by_date']);
                }, $appointments),
            ) !!};

            const data = {!! json_encode(
                array_map(function ($appointments) {
                    return array_values($appointments['by_date']);
                }, $appointments),
            ) !!};

            charts[siteKey] = new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels[siteKey],
                    datasets: [{
                        label: '{{ __('appointments_report.appointments_count') }}',
                        data: data[siteKey],
                        backgroundColor: '#5C4033',
                        borderColor: '#2F2F2F',
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
    </script>

    <script>
        document.getElementById('siteSelect').addEventListener('change', function() {
            var selectedSite = this.value;


            document.querySelectorAll('.site-section').forEach(section => {
                section.style.display = 'none';
            });


            if (selectedSite) {
                var siteDiv = document.getElementById('site-' + selectedSite);
                if (siteDiv) {
                    siteDiv.style.display = 'block';
                }
            }
        });
    </script>

<script>
    async function submitExport(type) {
        try {
            const site = document.getElementById('siteSelect').value;
            if (!site) return alert('Please select a site');

            const loadingOverlay = document.getElementById('pdf-loading-overlay');
            if (loadingOverlay) loadingOverlay.style.display = 'flex';

            const { jsPDF } = window.jspdf;
            const exportedBy = "{{ Auth::user()->name }}";
            const exportDate = new Date().toLocaleString();
     const siteName = site === 'dhahran' ? 'Azyan Dhahran' :
                 site === 'bashaer' ? 'Azyan Bashaer' :
                 'Azyan Jeddah'; // Add this line if keeping jeddah

            const leftLogoUrl = "{{ asset('build/logo.png') }}";
          const rightLogoUrl = site === 'dhahran'
    ? "{{ asset('images/logo5.png') }}"
    : site === 'bashaer'
    ? "{{ asset('images/logo6.png') }}"
    : site === 'alfursan'
    ? "{{ asset('images/alfursan.png') }}"
    : "{{ asset('images/jadah.png') }}"; // Add default for jeddah

            const chartCanvas = document.getElementById(`chart-${site}`);
            const detailsTable = document.querySelector(`#site-${site} table`);

            if (!chartCanvas || !detailsTable) {
                if (loadingOverlay) loadingOverlay.style.display = 'none';
                return alert('Required elements not found');
            }

            const loadImage = (url) => {
                return new Promise((resolve, reject) => {
                    const img = new Image();
                    img.crossOrigin = "anonymous";
                    img.onload = () => resolve(img);
                    img.onerror = () => reject(new Error('Failed to load image'));
                    img.src = url;
                });
            };

            if (type === 'pdf') {
                const doc = new jsPDF('p', 'mm', 'a4');
                const [leftLogoImg, rightLogoImg] = await Promise.all([
                    loadImage(leftLogoUrl),
                    loadImage(rightLogoUrl)
                ]);

                // Add logos
                doc.addImage(leftLogoImg, 'PNG', 15, 10, 20, 20);
                doc.addImage(rightLogoImg, 'PNG', 175, 10, 20, 20);

                doc.setFontSize(16);
                doc.text(`Appointments Report - ${siteName}`, 105, 50, { align: 'center' });
                doc.line(10, 55, 200, 55);

                let yPos = 60;

                const chartImg = await html2canvas(chartCanvas, { scale: 2 });
                const chartDataUrl = chartImg.toDataURL('image/png');
                doc.addImage(chartDataUrl, 'PNG', 10, yPos, 190, 80);
                yPos += 90;

                const rows = [];
                const tableRows = detailsTable.querySelectorAll('tbody tr');
                tableRows.forEach(row => {
                    const date = row.children[0]?.innerText.trim() ?? '';
                    const count = row.children[1]?.innerText.trim() ?? '';
                    rows.push([date, count]);
                });

                await doc.autoTable({
                    head: [['Date', 'Appointments Count']],
                    body: rows,
                    startY: yPos,
                    theme: 'grid',
                    styles: {
                        fontSize: 10,
                        halign: 'center',
                        valign: 'middle',
                    },
                    headStyles: { fillColor: [92, 64, 51] },
                    alternateRowStyles: { fillColor: [240, 240, 240] },
                    margin: { top: 10 },
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

                doc.save(`${site}_appointments_report.pdf`);
            } else if (type === 'csv') {
                const zip = new JSZip();
                let csvContent = "\uFEFFDate,Appointments Count\n";

                const tableRows = detailsTable.querySelectorAll('tbody tr');
                tableRows.forEach(row => {
                    const date = row.children[0]?.innerText.trim() ?? '';
                    const count = row.children[1]?.innerText.trim() ?? '';
                    csvContent += `${date},${count}\n`;
                });

                csvContent += `\nExported by,${exportedBy}\nExport date,${exportDate}`;

                const chartImg = await html2canvas(chartCanvas, { scale: 2 });
                const blob = await new Promise(resolve => chartImg.toBlob(resolve, 'image/png'));

                const csvBlob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });

                zip.file(`${site}_appointments.csv`, csvBlob);
                zip.file(`${site}_chart.png`, blob);

                const content = await zip.generateAsync({ type: "blob" });
                const url = URL.createObjectURL(content);
                const a = document.createElement('a');
                a.href = url;
                a.download = `${site}_appointments_report.zip`;
                a.click();
                setTimeout(() => URL.revokeObjectURL(url), 100);
            }

        } catch (error) {
            console.error('Export error:', error);
            alert('Export failed.');
        } finally {
            const loadingOverlay = document.getElementById('pdf-loading-overlay');
            if (loadingOverlay) loadingOverlay.style.display = 'none';
        }
    }
</script>


@endsection
