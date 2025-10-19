@extends('layouts.app')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
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
                                        <h1 class="text-2xl font-bold text-gray-800 text-center">{{ __('call_logs.title') }}
                                        </h1>
                                        <div class="flex items-center gap-4 mt-4">
                                            <a href="{{ route('admin.call.log', 'dhahran') }}"
                                                class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl">

                                                <i class="fas fa-clipboard-list"></i>{{ __('call_logs.view_log_dhahran') }}
                                            </a>
                                        </div>
                                        <div class="flex items-center gap-4 mt-4">
                                            <a href="{{ route('admin.call.log', 'bashaer') }}"
                                                class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl">
                                                <i class="fas fa-clipboard-list"></i>{{ __('call_logs.view_log_bashaer') }}
                                            </a>
                                        </div>
                                        <div class="flex items-center gap-4 mt-4">
                                            <a href="{{ route('admin.call.log', 'jeddah') }}"
                                                class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl">
                                                <i class="fas fa-clipboard-list"></i>{{ __('call_logs.view_log_jeddah') }}
                                            </a>

                                        </div>
                                        <div class="flex items-center gap-4 mt-4">
                                            <a href="{{ route('admin.call.log', 'alfursan') }}"
                                                class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl">
                                                <i class="fas fa-clipboard-list"></i>{{ __('call_logs.view_log_alfursan') }}
                                            </a>

                                        </div>

                                    </div>
                                </div>
                            </div>
                            <p class="text-center mt-4">{{ __('call_logs.select_location') }}</p>
                            <select id="siteSelect" class="select2-placeholder form-control mt-2">
                                <option value="">{{ __('call_logs.choose_location') }}</option>
                                <option value="dhahran">{{ __('call_logs.location_dhahran') }}</option>
                                <option value="bashaer">{{ __('call_logs.location_bashaer') }}</option>
                                <option value="jeddah">{{ __('call_logs.location_jeddah') }}</option>
                                <option value="alfursan">{{ __('call_logs.location_alfursan') }}</option>
                            </select>
                        </div>
                        <form id="exportForm" class="flex items-center gap-12 mt-12 justify-center">
                            @csrf
                            <a href="javascript:void(0);" onclick="submitExport('pdf')" title="Export PDF"
                                class="transition duration-300 transform hover:scale-110 hover:rotate-6">
                                <div class="fonticon-container flex items-center justify-center custom-hover-red">
                                    <div class="fonticon-wrap">
                                        <i class="fa fa-file-pdf-o text-red-500 hover:text-red-700 text-7xl"></i>
                                    </div>
                                </div>
                            </a>
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

        @foreach (['dhahran' => 'Azyan Dhahran', 'bashaer' => 'Azyan Bashaer', 'jeddah' => 'Azyan Jeddah','alfursan' => 'Azyan Alfursan'] as $key => $label)
            <div class="site-section" id="site-{{ $key }}" style="display: none;">
                <div class="export-header hidden" id="export-header-{{ $key }}">
                    <h1 class="text-xl font-bold mb-1">{{ __('call_logs.title') }}</h1>
                    <h2 class="text-lg text-gray-600">{{ __('call_logs.location') }} {{ ucfirst($label) }}</h2><br>
                </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg my-8">
                    <div class="p-4 bg-white shadow-sm rounded-lg">
                        <h2 class="text-2xl font-bold text-gray-800 text-center">{{ $label }}</h2>
                    </div>
                    @if (!empty($errors[$key]))
                        <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ $errors[$key] }}
                        </div>
                    @else
                        <div class="p-6">
                            <div class="bg-indigo-50 p-6 rounded-lg shadow-sm max-w-xs mx-auto">
                                <h3 class="text-lg font-semibold text-indigo-800 mb-2">{{ __('call_logs.total_calls') }}</h3>
                                <div class="text-3xl font-bold text-indigo-600">
                                    {{ number_format($totals[$key]['added']) }}
                                </div>
                            </div>
                        </div>
                        <div class="w-full max-w-4xl mx-auto mt-8 p-6">
                            <div class="bg-white p-6 rounded-lg shadow-sm relative">
                                <canvas id="chart-{{ $key }}" height="400"></canvas>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow-sm mt-8">
                            <h3 class="text-lg font-semibold mb-4 text-gray-700 text-center">
                                {{ __('call_logs.distribution_by_date') }}</h3>
                            <section id="section-content-{{ $key }}" class="hidden">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-content collapse show">
                                                <div class="card-body card-dashboard">
                                                    <table class="table table-striped table-bordered dataex-key-basic">
                                                        <thead class="bg-gray-100">
                                                            <tr>
                                                                <th class="py-2 px-4 text-center">{{ __('call_logs.date') }}</th>
                                                                <th class="py-2 px-4 text-center">{{ __('call_logs.added') }}</th>
                                                                <th class="py-2 px-4 text-center">{{ __('call_logs.started') }}</th>
                                                                <th class="py-2 px-4 text-center">{{ __('call_logs.ended') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($callLogs[$key]['added'] as $date => $added)
                                                                <tr class="text-center">
                                                                    <td>{{ \Carbon\Carbon::parse($date)->translatedFormat('d M Y') }}</td>
                                                                    <td>{{ $added }}</td>
                                                                    <td>{{ $callLogs[$key]['started'][$date] ?? 0 }}</td>
                                                                    <td>{{ $callLogs[$key]['ended'][$date] ?? 0 }}</td>
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
                            <a href="javascript:void(0);"
                            class="text-blue-500 hover:text-blue-700 hover:underline mt-4 block text-center"
                            onclick="toggleTable('{{ $key }}')">
                                {{ __('call_logs.show_details') }}
                            </a>
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
                                site === 'jeddah' ? 'Azyan Jeddah' : 'Azyan Alfursan';

                const leftLogoUrl = "{{ asset('build/logo.png') }}";
                const rightLogoUrl = site === 'dhahran' ?
                    "{{ asset('images/logo5.png') }}" :
                    site === 'bashaer' ?
                    "{{ asset('images/logo6.png') }}" :
                    site === 'jeddah' ?
                    "{{ asset('images/jadah.png') }}" :
                    site === 'alfursan' ?
                    "{{ asset('images/alfursan.png') }}" :
                    "";

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

                    doc.addImage(leftLogoImg, 'PNG', 15, 10, 20, 20);
                    doc.addImage(rightLogoImg, 'PNG', 175, 10, 20, 20);

                    doc.setFontSize(16);
                    doc.text(`Call Logs Report - ${siteName}`, 105, 50, { align: 'center' });
                    doc.line(10, 55, 200, 55);

                    let yPos = 60;

                    const chartImg = await html2canvas(chartCanvas, {
                        scale: 2,
                        useCORS: true
                    });
                    const chartDataUrl = chartImg.toDataURL('image/png');
                    doc.addImage(chartDataUrl, 'PNG', 10, yPos, 190, 80);
                    yPos += 90;

                    const rows = [];
                    const tableRows = detailsTable.querySelectorAll('tbody tr');
                    tableRows.forEach(row => {
                        const date = row.children[0]?.innerText.trim() ?? '';
                        const added = row.children[1]?.innerText.trim() ?? '';
                        const started = row.children[2]?.innerText.trim() ?? '';
                        const ended = row.children[3]?.innerText.trim() ?? '';
                        rows.push([date, added, started, ended]);
                    });

                    await doc.autoTable({
                        head: [['Date', 'Added', 'Started', 'Ended']],
                        body: rows,
                        startY: yPos,
                        theme: 'grid',
                        styles: {
                            fontSize: 10,
                            halign: 'center',
                            valign: 'middle',
                        },
                        headStyles: {
                            fillColor: [92, 64, 51]
                        },
                        alternateRowStyles: {
                            fillColor: [240, 240, 240]
                        },
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

                    doc.save(`${site}_call_logs_report.pdf`);

                } else if (type === 'zip') {
                    const zip = new JSZip();
                    let csvContent = "Date,Added,Started,Ended\n";

                    const tableRows = detailsTable.querySelectorAll('tbody tr');
                    tableRows.forEach(row => {
                        const date = row.children[0]?.innerText.trim() ?? '';
                        const added = row.children[1]?.innerText.trim() ?? '';
                        const started = row.children[2]?.innerText.trim() ?? '';
                        const ended = row.children[3]?.innerText.trim() ?? '';
                        csvContent += `${date},${added},${started},${ended}\n`;
                    });

                    csvContent += `\nExported by,${exportedBy}\nExport date,${exportDate}`;

                    const chartCanvasClone = await html2canvas(chartCanvas, {
                        scale: 2,
                        useCORS: true
                    });
                    const chartBlob = await new Promise(resolve => chartCanvasClone.toBlob(resolve, 'image/png', 1.0));
                    const csvBlob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });

                    zip.file(`${site}_call_logs.csv`, csvBlob);
                    zip.file(`${site}_chart.png`, chartBlob);

                    const zipContent = await zip.generateAsync({ type: "blob" });
                    const url = URL.createObjectURL(zipContent);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `${site}_call_logs_report.zip`;
                    a.click();
                    setTimeout(() => URL.revokeObjectURL(url), 100);
                }

            } catch (err) {
                console.error('Export error:', err);
                alert('Export failed. See console for details.');
            } finally {
                const loadingOverlay = document.getElementById('pdf-loading-overlay');
                if (loadingOverlay) loadingOverlay.style.display = 'none';
            }
        }
    </script>

   <script>
    let charts = {};

    document.getElementById('siteSelect').addEventListener('change', function() {
        const selectedSite = this.value;

        document.querySelectorAll('.site-section').forEach(section => {
            section.style.display = 'none';
        });

        if (selectedSite) {
            const siteDiv = document.getElementById('site-' + selectedSite);
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

        // إعداد البيانات لكل موقع بشكل منفصل
        let labels, addedData, startedData, endedData;

        // استخدام بيانات الموقع المحدد
        switch(siteKey) {
            case 'dhahran':
                labels = @json(array_keys($callLogs['dhahran']['added'] ?? []));
                addedData = @json(array_values($callLogs['dhahran']['added'] ?? []));
                startedData = @json(array_values($callLogs['dhahran']['started'] ?? []));
                endedData = @json(array_values($callLogs['dhahran']['ended'] ?? []));
                break;
            case 'bashaer':
                labels = @json(array_keys($callLogs['bashaer']['added'] ?? []));
                addedData = @json(array_values($callLogs['bashaer']['added'] ?? []));
                startedData = @json(array_values($callLogs['bashaer']['started'] ?? []));
                endedData = @json(array_values($callLogs['bashaer']['ended'] ?? []));
                break;
            case 'jeddah':
                labels = @json(array_keys($callLogs['jeddah']['added'] ?? []));
                addedData = @json(array_values($callLogs['jeddah']['added'] ?? []));
                startedData = @json(array_values($callLogs['jeddah']['started'] ?? []));
                endedData = @json(array_values($callLogs['jeddah']['ended'] ?? []));
                break;
            case 'alfursan':
                labels = @json(array_keys($callLogs['alfursan']['added'] ?? []));
                addedData = @json(array_values($callLogs['alfursan']['added'] ?? []));
                startedData = @json(array_values($callLogs['alfursan']['started'] ?? []));
                endedData = @json(array_values($callLogs['alfursan']['ended'] ?? []));
                break;
            default:
                labels = [];
                addedData = [];
                startedData = [];
                endedData = [];
        }

        // إذا لم تكن هناك بيانات، عرض رسالة
        if (labels.length === 0) {
            ctx.parentElement.innerHTML = '<div class="text-center text-gray-500 p-8">لا توجد بيانات متاحة للعرض</div>';
            return;
        }

        // تدمير الرسم البياني القديم إذا كان موجوداً
        if (charts[siteKey]) {
            charts[siteKey].destroy();
        }

        // إنشاء الرسم البياني الجديد
        charts[siteKey] = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: '{{ __("call_logs.added") }}',
                        data: addedData,
                        backgroundColor: '#5C4033',
                        borderColor: '#3C2F2F',
                        borderWidth: 1
                    },
                    {
                        label: '{{ __("call_logs.started") }}',
                        data: startedData,
                        backgroundColor: '#334F5D',
                        borderColor: '#00181f',
                        borderWidth: 1
                    },
                    {
                        label: '{{ __("call_logs.ended") }}',
                        data: endedData,
                        backgroundColor: '#A2C2D6',
                        borderColor: '#00181f',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0 // لتجنب الكسور العشرية
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
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }

    // تهيئة الرسم البياني عند تحميل الصفحة إذا كان هناك موقع محدد مسبقاً
    document.addEventListener('DOMContentLoaded', function() {
        const initialSite = document.getElementById('siteSelect').value;
        if (initialSite) {
            createChart(initialSite);
        }
    });
</script>

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
@endsection
