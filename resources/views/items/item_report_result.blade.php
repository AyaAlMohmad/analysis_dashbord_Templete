@extends('layouts.app')

@section('content')
    <style>
        .status-card {
            padding: 20px;
            color: white;
            border-radius: 10px;
            text-align: center;
            min-width: 120px;
            margin: 5px;
            font-weight: bold;
        }

        .contracted {
            background-color: #2c3e50;
        }

        .blocked {
            background-color: #e74c3c;
        }

        .reserved {
            background-color: #f39c12;
        }

        .available {
            background-color: #27ae60;
        }

        .total {
            background-color: #ecf0f1;
            color: #333;
        }

        .logo {
            max-height: 100px;
        }

        .section-title {
            background: #3e1b59;
            color: #fff;
            padding: 10px;
            margin: 30px 0 10px;
            text-align: center;
            font-size: 20px;
        }

        table {
            width: 100%;
            background: #fff;
        }

        th,
        td {
            text-align: center;
            padding: 10px;
        }

        th {
            background: #f4f4f4;
        }
    </style>
    <div id="reportContent">
        <div class="container mt-4">

            {{-- Statistics Cards --}}
            <div class="row text-center my-4">
                @php
                    $statuses = [
                        'available' => ['label' => 'Available', 'color' => '#00b5ad', 'icon' => 'check-circle'],
                        'blocked' => ['label' => 'Blocked', 'color' => '#e74c3c', 'icon' => 'ban'],
                        'reserved' => ['label' => 'Reserved', 'color' => '#f39c12', 'icon' => 'clock'],
                        'contracted' => ['label' => 'Contracted', 'color' => '#543829', 'icon' => 'file-signature'],
                    ];
                @endphp
                @php
                    $chartConfig = [];
                    foreach ($statuses as $key => $info) {
                        $count = $result[$key] ?? 0;
                        $total = $result['total'] ?? 1;
                        $percentage = round(($count / $total) * 100);
                        $chartConfig[$key] = [
                            'value' => $percentage,
                            'color' => $info['color'],
                            'icon' => $info['icon'],
                        ];
                    }
                @endphp

                @foreach ($statuses as $key => $info)
                    @php
                        $count = $result[$key] ?? 0;
                        $total = $result['total'] ?? 1; // نتفادى القسمة على صفر
                        $percentage = round(($count / $total) * 100);
                    @endphp
                    <div class="col-md-3 mb-4">
                        <div class="card shadow p-3 position-relative text-center">
                            <h5 style="color: {{ $info['color'] }}">{{ $info['label'] }}</h5>
                            <div class="fw-bold fs-4">{{ $count }}</div>
                            <div style="position: relative; width: 120px; margin: 0 auto;">
                                <canvas id="circle_{{ $key }}" width="120" height="120"></canvas>
                            </div>
                            <div class="mt-2" style="color: {{ $info['color'] }}; font-weight: bold;">
                                {{ $percentage }}%
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            @php
                $logo = $site === 'dhahran' ? asset('images/logo1.png') : asset('images/logo2.png');
                $darkColor = $site === 'dhahran' ? '#00262f' : '#543829';
            @endphp
            {{-- Logo and Project Title --}}
            <div class="text-center my-4">
                <img src="{{ $logo }}" class="logo" alt="Logo">
                <h3 class="mt-2">{{ $site === 'dhahran' ? 'Azyan Dhahran' : 'Azyan Bashaer' }}</h3>
            </div>

            {{-- Table Title --}}
            <div class="section-title" style="background-color: {{ $darkColor }}">Unit Report</div>

            {{-- Data Table --}}
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Group</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Block</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($result['items'] ?? [] as $item)
                        <tr>
                            <td>{{ $item['group'] ?? '-' }}</td>
                            <td>{{ $item['description'] ?? '-' }}</td>
                            <td>{{ $item['unit_status'] ?? '-' }}</td>
                            <td>{{ $item['value'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-center mt-2 mb-4">
                <small class="text-muted">Report Export Date: {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}</small>
            </div>

        </div>
        <div class="text-center my-4" id="pdf-export-button">

            <a href="javascript:void(0);" onclick="exportPDF()" title="Export PDF"
                class="transition duration-300 transform hover:scale-110 hover:rotate-6 d-block mt-4">
                <div class="fonticon-container flex items-center justify-center custom-hover-red">
                    <div class="fonticon-wrap"
                        style="float: left; width: 1104px; height: 60px;line-height: 4.8rem; text-align: center; border-radius: 0.1875rem;margin-right: 1rem;
                         margin-bottom: 1.5rem;">
                        <i class="fa fa-file-pdf-o text-red-500 hover:text-red-700 text-5xl"></i>
                    </div>
                </div>
            </a>

        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        async function exportPDF() {
            const {
                jsPDF
            } = window.jspdf;
            const pdf = new jsPDF('p', 'mm', 'a4');
            const element = document.getElementById('reportContent');

           
            document.getElementById('pdf-export-button')?.classList.add('d-none');

           
            await new Promise(resolve => setTimeout(resolve, 200));

        
            const canvas = await html2canvas(element, {
                scale: 2
            });
            const imgData = canvas.toDataURL('image/png');

            const imgProps = pdf.getImageProperties(imgData);
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

            const pageHeight = pdf.internal.pageSize.getHeight();
            let heightLeft = pdfHeight;
            let position = 0;

            // إضافة أول صفحة
            pdf.addImage(imgData, 'PNG', 0, position, pdfWidth, pdfHeight);
            heightLeft -= pageHeight;

            // صفحات إضافية
            while (heightLeft > 0) {
                position = heightLeft - pdfHeight;
                pdf.addPage();
                pdf.addImage(imgData, 'PNG', 0, position, pdfWidth, pdfHeight);
                heightLeft -= pageHeight;
            }

            pdf.save("{{ $site }}_unit_report.pdf");

            // إظهار زر التصدير مرة أخرى
            document.getElementById('pdf-export-button')?.classList.remove('d-none');
        }
    </script>

    <script>
        const chartConfig = @json($chartConfig);

        document.addEventListener("DOMContentLoaded", function() {
            Object.entries(chartConfig).forEach(([key, data]) => {
                const canvas = document.getElementById('circle_' + key);
                const ctx = canvas.getContext('2d');
                const value = data.value > 100 ? 100 : data.value;

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [value, 100 - value],
                            backgroundColor: [data.color, '#f0f0f0'],
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        cutout: '75%',
                        plugins: {
                            tooltip: {
                                enabled: false
                            },
                            legend: {
                                display: false
                            },
                        }
                    }
                });

                const icon = document.createElement('i');
                icon.className = `fas fa-${data.icon}`;
                icon.style.position = 'absolute';
                icon.style.left = canvas.offsetLeft + canvas.width / 2 - 12 + 'px';
                icon.style.top = canvas.offsetTop + canvas.height / 2 - 12 + 'px';
                icon.style.fontSize = '24px';
                icon.style.color = data.color;
                icon.style.zIndex = 1;
                canvas.parentNode.appendChild(icon);
            });
        });
    </script>
@endsection
