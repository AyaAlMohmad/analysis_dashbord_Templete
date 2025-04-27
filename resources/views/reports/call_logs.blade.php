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
                                        <h1 class="text-2xl font-bold text-gray-800 text-center">Call Logs Report</h1>
                                        <div class="flex items-center gap-4 mt-4">
                                            <a href="{{ route('admin.call.log', 'dhahran') }}" class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl">
                                                <i class="fas fa-clipboard-list"></i> dhahran
                                            </a>
                                            <a href="{{ route('admin.call.log', 'bashaer') }}" class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl">
                                                <i class="fas fa-clipboard-list"></i> bashaer
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="text-center mt-4">📍 Select Location</p>
                            <select id="siteSelect" class="select2-placeholder form-control mt-2">
                                <option value="">-- Please choose a location --</option>
                                <option value="dhahran">Azyan Dhahran</option>
                                <option value="bashaer">Azyan Bashaer</option>
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

        @foreach (['dhahran' => 'Azyan Dhahran', 'bashaer' => 'Azyan Bashaer'] as $key => $label)
            <div class="site-section" id="site-{{ $key }}" style="display: none;">
                <div class="export-header hidden" id="export-header-{{ $key }}">
                    <h1 class="text-xl font-bold mb-1">Call Logs Report</h1>
                    <h2 class="text-lg text-gray-600">Location: {{ ucfirst($label) }}</h2><br>
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
                                <h3 class="text-lg font-semibold text-indigo-800 mb-2">Total Calls Added</h3>
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
                            <h3 class="text-lg font-semibold mb-4 text-gray-700 text-center">Distribution by Date</h3>
                            <section id="section-content-{{ $key }}" class="hidden">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-content collapse show">
                                                <div class="card-body card-dashboard">
                                                    <table class="table table-striped table-bordered dataex-key-basic">
                                                        <thead class="bg-gray-100">
                                                            <tr>
                                                                <th class="py-2 px-4 text-center">Date</th>
                                                                <th class="py-2 px-4 text-center">Added</th>
                                                                <th class="py-2 px-4 text-center">Started</th>
                                                                <th class="py-2 px-4 text-center">Ended</th>
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
                            <a href="javascript:void(0);" class="text-blue-500 hover:text-blue-700 hover:underline mt-4 block text-center" onclick="toggleTable('{{ $key }}')">
                                Show Details
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <script>
        async function submitExport(type) {
            const site = document.getElementById('siteSelect').value;
            if (!site) return alert('Please select a site');
    
            const { jsPDF } = window.jspdf;
            const exportedBy = "{{ Auth::user()->name }}";
            const exportDate = new Date().toLocaleString();
            const logoUrl = "{{ asset('build/logo.png') }}";
    
            const chartCanvas = document.getElementById(`chart-${site}`);
            const detailsTable = document.querySelector(`#site-${site} table`);
    
            if (!chartCanvas || !detailsTable) {
                alert('Required elements not found');
                return;
            }
    
            if (type === 'pdf') {
                const doc = new jsPDF('p', 'mm', 'a4');
                const logoImg = new Image();
                logoImg.crossOrigin = "anonymous";
                logoImg.src = logoUrl;
    
                logoImg.onload = async function() {
                    // ✨ إضافة الشعار
                    doc.addImage(logoImg, 'PNG', 80, 10, 50, 30);
    
                    // ✨ إضافة عنوان التقرير
                    doc.setFontSize(16);
                    doc.text(`Call Logs Report - Azyan ${site.charAt(0).toUpperCase() + site.slice(1)}`, 105, 50, { align: 'center' });
                    doc.line(10, 55, 200, 55);
    
                    let yPos = 60;
    
                    // ✨ إضافة الرسم البياني
                    const chartImg = await html2canvas(chartCanvas);
                    const chartDataUrl = chartImg.toDataURL('image/png');
                    doc.addImage(chartDataUrl, 'PNG', 10, yPos, 190, 80);
                    yPos += 90;
    
                    // ✨ تجهيز بيانات الجدول
                    const rows = [];
                    const tableRows = detailsTable.querySelectorAll('tbody tr');
                    tableRows.forEach(row => {
                        const date = row.children[0]?.innerText.trim() ?? '';
                        const added = row.children[1]?.innerText.trim() ?? '';
                        const started = row.children[2]?.innerText.trim() ?? '';
                        const ended = row.children[3]?.innerText.trim() ?? '';
                        rows.push([date, added, started, ended]);
                    });
    
                    // ✨ رسم الجدول
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
                            fillColor: [92, 64, 51] // لون رأس الجدول بني
                        },
                        alternateRowStyles: {
                            fillColor: [240, 240, 240]
                        },
                        margin: { top: 10 },
                    });
    
                    // ✨ إضافة توقيع أخير
                    const totalPages = doc.internal.getNumberOfPages();
                    doc.setPage(totalPages);
    
                    const pageSize = doc.internal.pageSize;
                    const pageHeight = pageSize.height || pageSize.getHeight();
    
                    doc.setFontSize(10);
                    doc.text(`Exported by: ${exportedBy}`, 10, pageHeight - 20);
                    doc.text(`Export date: ${exportDate}`, 10, pageHeight - 15);
                    doc.text(`Page ${totalPages} of ${totalPages}`, 200 - 10, pageHeight - 15, { align: 'right' });
    
                    // ✨ حفظ الملف
                    doc.save(`${site}_call_logs_report.pdf`);
                };
    
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
    
                html2canvas(chartCanvas).then(canvas => {
                    canvas.toBlob(blob => {
                        zip.file(`${site}_call_logs.csv`, csvContent);
                        zip.file(`${site}_chart.png`, blob);
    
                        zip.generateAsync({ type: "blob" }).then(content => {
                            const url = URL.createObjectURL(content);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = `${site}_call_logs_report.zip`;
                            a.click();
                            URL.revokeObjectURL(url);
                        });
                    }, 'image/png');
                });
            }
        }
    </script>
    <script>
        let charts = {}; // متغير لتخزين الرسوم البيانية حتى لا يتم رسمها مرتين
    
        document.getElementById('siteSelect').addEventListener('change', function () {
            const selectedSite = this.value;
    
            // إخفاء جميع الأقسام أولاً
            document.querySelectorAll('.site-section').forEach(section => {
                section.style.display = 'none';
            });
    
            // عرض القسم المختار فقط
            if (selectedSite) {
                const siteDiv = document.getElementById('site-' + selectedSite);
                if (siteDiv) {
                    siteDiv.style.display = 'block';
                }
    
                // رسم الرسم البياني إذا لم يتم رسمه من قبل
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
    
            // إعداد البيانات للعرض
            const labels = @json([
                'dhahran' => array_keys($callLogs['dhahran']['added'] ?? []),
                'bashaer' => array_keys($callLogs['bashaer']['added'] ?? []),
            ]);
    
            const addedData = @json([
                'dhahran' => array_values($callLogs['dhahran']['added'] ?? []),
                'bashaer' => array_values($callLogs['bashaer']['added'] ?? []),
            ]);
    
            const startedData = @json([
                'dhahran' => array_values($callLogs['dhahran']['started'] ?? []),
                'bashaer' => array_values($callLogs['bashaer']['started'] ?? []),
            ]);
    
            const endedData = @json([
                'dhahran' => array_values($callLogs['dhahran']['ended'] ?? []),
                'bashaer' => array_values($callLogs['bashaer']['ended'] ?? []),
            ]);
    
            charts[siteKey] = new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels[siteKey],
                    datasets: [
                        {
                            label: 'Added',
                            data: addedData[siteKey],
                            backgroundColor: '#5C4033',
                            borderColor: '#3C2F2F',
                            borderWidth: 1
                        },
                        {
                            label: 'Started',
                            data: startedData[siteKey],
                            backgroundColor: '#334F5D',
                            borderColor: '#00181f',
                            borderWidth: 1
                        },
                        {
                            label: 'Ended',
                            data: endedData[siteKey],
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
                            beginAtZero: true
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
