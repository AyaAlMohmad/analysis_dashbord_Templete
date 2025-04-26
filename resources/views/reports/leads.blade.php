@extends('layouts.app')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-4 bg-white shadow-sm rounded-lg">
                    <h1 class="text-2xl font-bold text-gray-800 text-center">Leads Report</h1>
                    <div class="flex items-center gap-4 mt-4">
                        <!-- Dhahran Log -->
                        <a href="{{ route('admin.leads.log', 'dhahran') }}"
                            class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl"
                            title="View Dhahran Log">
                            <i class="fas fa-clipboard-list"></i> dhahran
                        </a>

                        <!-- Bashaer Log -->
                        <a href="{{ route('admin.leads.log', 'bashaer') }}"
                            class="p-3 rounded-xl hover:bg-gray-100 transition text-gray-600 text-2xl"
                            title="View Bashaer Log">
                            <i class="fas fa-clipboard-list"></i> bashaer
                        </a>
                    </div>
                </div>

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
                                </div>
                            </div>
                        </div>


                        <!-- Loop Through Sites -->
                        @foreach (['dhahran' => 'Azyan Dhahran', 'bashaer' => 'Azyan Bashaer'] as $key => $title)
                            @php
                                $leadsData = $key === 'dhahran' ? $leadsAzyanDhahran : $leadsAzyanBashaer;
                            @endphp

                            <div id="site-{{ $key }}"
                                class="site-container hidden bg-white overflow-hidden shadow-xl sm:rounded-lg my-8">
                                <div class="export-header hidden" id="export-header-{{ $key }}">
                                    <h1 class="text-xl font-bold mb-1">Leads Report</h1>
                                    <h2 class="text-lg text-gray-600">Location: {{ ucfirst($title) }}</h2><br>
                                </div>
                                <div class="p-4 bg-white shadow-sm rounded-lg">
                                    <h1 class="text-2xl font-bold text-gray-800 text-center">{{ $title }} - Leads
                                        Report</h1>
                                    </>

                                    @if ($errors[$key])
                                        <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                            {{ $errors[$key] }}
                                        </div>
                                    @endif

                                    @if (count($leadsData) > 0)
                                        <!-- Summary -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                                            <div class="bg-green-50 p-6 rounded-lg shadow-sm">
                                                <h3 class="text-lg font-semibold text-green-800">Total Added</h3>
                                                <div class="text-3xl font-bold text-green-600">
                                                    {{ $totals[$key]['added'] }}
                                                </div>
                                            </div>
                                            <div class="bg-purple-50 p-6 rounded-lg shadow-sm">
                                                <h3 class="text-lg font-semibold text-purple-800">Total Edited</h3>
                                                <div class="text-3xl font-bold text-purple-600">
                                                    {{ $totals[$key]['edited'] }}</div>
                                            </div>
                                        </div>

                                        <!-- Table -->
                                        <div class="p-6">
                                            <div x-data="{ showTable: false }" class="bg-white rounded-lg shadow-sm">
                                                <div class="p-4 border-b">
                                                    <button @click="showTable = !showTable"
                                                        class="text-blue-500 hover:text-blue-700 flex items-center">
                                                        <span
                                                            x-text="showTable ? 'Hide Details' : 'Show Daily Details'"></span>
                                                        <svg class="w-4 h-4 ml-2 transform transition-transform"
                                                            :class="{ 'rotate-180': showTable }" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div x-show="showTable" id="daily-details-{{ $key }}"
                                                    class="overflow-x-auto">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-50">
                                                            <tr>
                                                                <th
                                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                                    Date</th>
                                                                <th
                                                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                                                    Added</th>
                                                                <th
                                                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                                                    Edited</th>
                                                                <th
                                                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                                                    Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="bg-white divide-y divide-gray-200">
                                                            @foreach ($leadsData as $date => $stats)
                                                                <tr class="hover:bg-gray-50">
                                                                    <td class="px-6 py-4 text-sm text-gray-600">
                                                                        {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                                                                    </td>
                                                                    <td
                                                                        class="px-6 py-4 text-center text-sm text-green-600">
                                                                        {{ $stats['added'] }}</td>
                                                                    <td
                                                                        class="px-6 py-4 text-center text-sm text-purple-600">
                                                                        {{ $stats['edited'] }}</td>
                                                                    <td
                                                                        class="px-6 py-4 text-center text-sm text-blue-600 font-semibold">
                                                                        {{ $stats['added'] + $stats['edited'] }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Chart -->
                                        <div class="w-full max-w-6xl mx-auto mt-8 p-6">
                                            <div class="bg-white p-6 rounded-lg shadow-sm relative">
                                                <div wire:ignore class="relative h-[500px]">
                                                    <div x-data="{
                                                        init() {
                                                            const dates = @js(array_keys($leadsData));
                                                            const addedData = @js(array_column($leadsData, 'added'));
                                                            const editedData = @js(array_column($leadsData, 'edited'));
                                                    
                                                            new Chart(this.$refs.chart, {
                                                                type: 'bar',
                                                                data: {
                                                                    labels: dates,
                                                                    datasets: [{
                                                                            label: 'Added',
                                                                            data: addedData,
                                                                            backgroundColor: '#A2C2D6',
                                                                            borderColor: '#A2C2D6',
                                                                            borderWidth: 1
                                                                        },
                                                                        {
                                                                            label: 'Edited',
                                                                            data: editedData,
                                                                            backgroundColor: '#543829',
                                                                            borderColor: '#00262f',
                                                                            borderWidth: 1
                                                                        }
                                                                    ]
                                                                },
                                                                options: {
                                                                    responsive: true,
                                                                    maintainAspectRatio: false,
                                                                    scales: {
                                                                        x: { stacked: true },
                                                                        y: { stacked: true, beginAtZero: true }
                                                                    }
                                                                }
                                                            });
                                                        }
                                                    }">
                                                        <canvas id="chart-{{ $key }}" x-ref="chart"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-white p-6 rounded-lg shadow-sm">
                                            <p class="text-center text-gray-500">No data available</p>
                                        </div>
                                    @endif
                                </div>
                        @endforeach
                    </div>
                </div>

                <!-- Scripts -->
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script src="//unpkg.com/alpinejs" defer></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const select = document.getElementById('siteSelect');
                        const containers = document.querySelectorAll('.site-container');

                        select.addEventListener('change', function() {
                            containers.forEach(div => div.classList.add('hidden'));
                            const selected = select.value;
                            if (selected) {
                                document.getElementById(`site-${selected}`)?.classList.remove('hidden');
                            }
                        });
                    });
                </script>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Required scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script>
        function submitExport(type) {
            const site = document.getElementById('siteSelect').value;
            if (!site) return alert('Please select a site');

            const exportHeader = document.getElementById(`export-header-${site}`);
            const chartCanvas = document.getElementById(`chart-${site}`);
            const siteSection = document.getElementById(`site-${site}`);
            const detailsContent = document.getElementById(`daily-details-${site}`);


            if (!exportHeader || !chartCanvas || !detailsContent) {
                alert('Required elements not found');
                return;
            }

            const originalHeaderDisplay = exportHeader.style.display;
            const originalDetailsDisplay = detailsContent.style.display;
            exportHeader.style.display = 'block';
            detailsContent.style.display = 'block';

            const exportedBy = "{{ Auth::user()->name }}";
            const exportDate = new Date().toLocaleString();
            if (type === 'pdf') {
                html2canvas(chartCanvas).then((chartImg) => {
    const doc = new jspdf.jsPDF();
    const imgWidth = 190;
    let yPos = 10;

    // Header (text only)
    doc.setFontSize(16);
    doc.text(`Leads Report`, 14, yPos);
    yPos += 10;

    doc.setFontSize(12);
    doc.text(`Site: ${site}`, 14, yPos);
    yPos += 8;

    // Chart image
    doc.addImage(chartImg, 'PNG', 10, yPos, imgWidth, 100);
    yPos += 110;

    // Details (daily details)
    doc.setFontSize(12);
    doc.text(`Daily Details:`, 14, yPos);
    yPos += 8;

    const tableRows = detailsContent.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        const date = row.children[0].textContent.trim();
        const added = row.children[1].textContent.trim();
        const edited = row.children[2].textContent.trim();
        const total = row.children[3].textContent.trim();

        doc.text(`${date} - Added: ${added}, Edited: ${edited}, Total: ${total}`, 14, yPos);
        yPos += 7;

        // Add new page if content overflows
        if (yPos > 270) {
            doc.addPage();
            yPos = 10;
        }
    });

    // ➕ Add footer only after all pages have been generated (on the last page)
    const pageCount = doc.internal.getNumberOfPages();
    doc.setPage(pageCount); // Move to last page
    const pageHeight = doc.internal.pageSize.height;
    const margin = 10;

    doc.setFontSize(10);
    doc.text(`Exported by: ${exportedBy}`, margin, pageHeight - 20);
    doc.text(`Export date: ${exportDate}`, margin, pageHeight - 15);

    doc.save(`${site}_Leads.pdf`);
});

            } else if (type === 'zip') {
                const zip = new JSZip();

                // Create CSV file
                let csvContent = "Date,Count\n";
                const tableRows = detailsContent.querySelectorAll('tbody tr');
                csvContent = "Date,Added,Edited,Total\n";
                tableRows.forEach(row => {
                    const date = row.children[0].textContent.trim();
                    const added = row.children[1].textContent.trim();
                    const edited = row.children[2].textContent.trim();
                    const total = row.children[3].textContent.trim();
                    csvContent += `${date},${added},${edited},${total}\n`;
                });


                // Add metadata
                csvContent += `\nExported by,${exportedBy}\nExport date,${exportDate}`;

                // Add files to zip
                zip.file(`${site}_Leads.csv`, csvContent);

                html2canvas(chartCanvas).then(canvas => {
                    canvas.toBlob(blob => {
                        zip.file(`${site}_chart.png`, blob);
                        zip.generateAsync({
                            type: "blob"
                        }).then(content => {
                            const url = URL.createObjectURL(content);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = `${site}_Leads_report.zip`;
                            a.click();
                            URL.revokeObjectURL(url);
                        });
                    });
                });
            }
        }
    </script>
@endsection
