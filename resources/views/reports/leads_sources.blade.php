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
                                        <h1 class="text-2xl font-bold text-gray-800 text-center">Leads Sources Report</h1>

                                    </div>
                                    <p> 📍 Select Location</p>
                                    <select class="select2-placeholder form-control" id="location-filter">

                                        <option value="">-- Please choose a location --</option>
                                        <optgroup label="sites">
                                            @foreach (array_keys($allData) as $location)
                                                <option value="{{ $location }}">Azyan {{ ucfirst($location) }}</option>
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
                    <!-- الرسم البياني -->
                    <div class="bg-white p-6 rounded-lg shadow-sm mt-8">
                        <canvas id="chart-{{ $location }}" height="120"></canvas>
                    </div>

                    <!-- Data List -->
                    <div class="bg-white p-6 rounded-lg shadow-sm mt-8">
                        <h3 class="text-lg font-semibold mb-4 text-gray-700 text-center">Distribution by Source</h3>
                        <section id="section-content-{{ $location }}" class="hidden">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-content collapse show">
                                            <div class="card-body card-dashboard">
                                                <table class="table table-striped table-bordered dataex-key-basic">
                                                    <thead class="bg-gray-100">
                                                        <tr>
                                                            <th class="py-2 px-4 text-gray-600 font-semibold">Source</th>
                                                            <th class="py-2 px-4 text-gray-600 font-semibold">Count</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($data as $source => $count)
                                                            <tr class="hover:bg-gray-50">
                                                                <td class="py-2 px-4 text-gray-700">{{ $source }}</td>
                                                                <td class="py-2 px-4">
                                                                    <span
                                                                        class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm inline-block">
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

                        <!-- زر التفاصيل كـ <a> بدل <button> -->
                        <a href="javascript:void(0);" class="text-blue-500 hover:text-blue-700 hover:underline"
                            onclick="toggleTable('{{ $location }}')">
                            Show Details
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-white p-6 rounded-lg shadow-sm mt-10">
                    <div class="text-center text-gray-500">No data available for {{ ucfirst($location) }}</div>
                </div>
            @endif
        @endforeach
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

                // إخفاء كل الأقسام
                allSections.forEach(section => {
                    section.style.display = 'none';
                });

                if (selected) {
                    const selectedSection = document.getElementById(`chart-section-${selected}`);
                    if (selectedSection) {
                        selectedSection.style.display = 'block';

                        // إذا ما كان فيه رسم بياني منشأ، أنشئه
                        if (!chartInstances[selected]) {
                            const ctx = document.getElementById(`chart-${selected}`).getContext('2d');
                            const chartData = @json($allData);

                            chartInstances[selected] = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: Object.keys(chartData[selected]),
                                    datasets: [{
                                        label: 'Number of Leads',
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
    </script>

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
            const detailsContent = document.getElementById(`section-content-${site}`);

            if (!exportHeader || !chartCanvas || !detailsContent) {
                alert('Required elements not found');
                return;
            }

            const originalHeaderDisplay = exportHeader.style.display;
            const originalDetailsClass = detailsContent.classList.contains('hidden');
            exportHeader.style.display = 'block';
            detailsContent.classList.remove('hidden');

            const exportedBy = "{{ Auth::user()->name }}";
            const exportDate = new Date().toLocaleString();
            const logoUrl = "{{ asset('build/logo.png') }}";

            if (type === 'pdf') {
                const doc = new jspdf.jsPDF('p', 'mm', 'a4');

                const logoImg = new Image();
                logoImg.crossOrigin = "anonymous";
                logoImg.src = logoUrl;

                logoImg.onload = function() {
                    Promise.all([
                        html2canvas(exportHeader),
                        html2canvas(chartCanvas),
                        html2canvas(detailsContent)
                    ]).then(([headerImg, chartImg, detailsImg]) => {
                        const imgWidth = 190;
                        let yPos = 10;

                   
                        doc.addImage(logoImg, 'PNG', 80, yPos, 50, 30);

                        yPos += 35;
              
                        doc.setFontSize(16); 
                        doc.text(`Leads Sources Report - Azyan ${site.charAt(0).toUpperCase() + site.slice(1)}`,
                            105, yPos, {
                                align: 'center'
                            });

                        yPos += 5;
                      
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
                        if (originalDetailsClass) {
                            detailsContent.classList.add('hidden');
                        }
                    });
                };
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

                        zip.generateAsync({
                            type: "blob"
                        }).then(content => {
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

<script>
    async function submitExport(type) {
        const site = document.getElementById('location-filter').value;
        if (!site) return alert('Please select a site');

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');
        const exportedBy = "{{ Auth::user()->name }}";
        const exportDate = new Date().toLocaleString();
        const logoUrl = "{{ asset('build/logo.png') }}";

        const chartSection = document.getElementById(`chart-section-${site}`);
        const chartCanvas = chartSection.querySelector('canvas');
        const detailsTable = document.getElementById(`section-content-${site}`)?.querySelector('table');

        if (!chartCanvas || !detailsTable) {
            alert('Required elements not found');
            return;
        }

        const logoImg = new Image();
        logoImg.crossOrigin = "anonymous";
        logoImg.src = logoUrl;

        logoImg.onload = async function () {
            // 1. إضافة اللوغو
            doc.addImage(logoImg, 'PNG', 80, 10, 50, 30);

            // 2. إضافة العنوان
            doc.setFontSize(16);
            doc.text(`Leads Sources Report - Azyan ${site.charAt(0).toUpperCase() + site.slice(1)}`, 105, 50, { align: 'center' });
            doc.line(10, 55, 200, 55);

            let yPos = 60;

            // 3. إضافة الرسم البياني
            const chartImg = await html2canvas(chartCanvas);
            const chartDataUrl = chartImg.toDataURL('image/png');
            doc.addImage(chartDataUrl, 'PNG', 10, yPos, 190, 80);
            yPos += 90;

            // 4. كتابة التفاصيل اليومية (من الجدول نصيًا)
            doc.setFontSize(12);
            doc.text('Daily Details:', 10, yPos);
            yPos += 8;

            // رؤوس الأعمدة
            doc.setFontSize(11);
            doc.setTextColor(0, 0, 0);
            doc.text('Source', 10, yPos);
            doc.text('Count', 140, yPos);
            yPos += 7;

            const rows = detailsTable.querySelectorAll('tbody tr');
            rows.forEach((row, index) => {
                const source = row.children[0].innerText.trim();
                const count = row.children[1].innerText.trim();

                doc.text(source, 10, yPos);
                doc.text(count, 140, yPos);

                yPos += 7;

                // إذا اقتربنا من نهاية الصفحة
                if (yPos > 270) {
                    doc.addPage();
                    yPos = 10;
                }
            });

            // 5. إضافة footer في آخر صفحة
            const pageCount = doc.internal.getNumberOfPages();
            for (let i = 1; i <= pageCount; i++) {
                doc.setPage(i);
                const pageHeight = doc.internal.pageSize.height;
                doc.setFontSize(10);
                doc.text(`Exported by: ${exportedBy}`, 10, pageHeight - 20);
                doc.text(`Export date: ${exportDate}`, 10, pageHeight - 15);
                doc.text(`Page ${i} of ${pageCount}`, 190, pageHeight - 15, { align: 'right' });
            }

            // 6. حفظ الملف
            doc.save(`${site}_leads_report.pdf`);
        };
    }
</script>

@endsection
