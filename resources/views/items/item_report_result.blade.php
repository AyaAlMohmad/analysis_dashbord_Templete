@extends('layouts.app')

@section('content')
@php
    // تحديد اللوجو واللون بناءً على الموقع
    $logos = [
        'dhahran' => asset('images/logo1.png'),
        'bashaer' => asset('images/logo2.png'),
        'jeddah' => asset('images/jadah.png')
    ];
    $colors = [
        'dhahran' => '#00262f',
        'bashaer' => '#543829',
        'jeddah' => '#1a472a'
    ];

    $logo = $logos[$site] ?? asset('images/logo1.png');
    $primaryColor = $colors[$site] ?? '#00262f';
    $projectNames = [
        'dhahran' => 'Azyan Dhahran',
        'bashaer' => 'Azyan Bashaer',
        'jeddah' => 'Azyan Jeddah'
    ];
    $projectName = $projectNames[$site] ?? 'Azyan Project';
@endphp

<style>
    .logo {
        max-height: 80px;
        margin-bottom: 20px;
    }
    .section-title {
        background-color: {{ $primaryColor }};
        color: #fff;
        text-align: center;
        padding: 15px;
        font-size: 18px;
        border-radius: 5px;
        margin: 20px 0;
        font-weight: bold;
    }
    .status-card {
        padding: 20px;
        color: white;
        border-radius: 10px;
        text-align: center;
        min-width: 120px;
        margin: 5px;
        font-weight: bold;
    }
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .table-container {
        width: 100%;
        overflow-x: auto;
        margin-bottom: 20px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        border-radius: 5px;
    }
    .data-table {
        width: 100%;
        background: #fff;
        border-collapse: collapse;
        min-width: 600px;
    }
    .data-table th,
    .data-table td {
        text-align: center;
        padding: 12px 10px;
        border: 1px solid #ddd;
        font-size: 14px;
    }
    .data-table th {
        background: {{ $primaryColor }};
        color: #fff;
        font-weight: bold;
        position: sticky;
        top: 0;
    }
    .data-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .data-table tr:hover {
        background-color: #f1f1f1;
    }
    .chart-container {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto;
    }
    .chart-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 24px;
        z-index: 1;
    }
    .pdf-button {
        text-align: center;
        margin: 30px 0;
    }
    .pdf-icon {
        font-size: 48px;
        color: #dc3545;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .pdf-icon:hover {
        transform: scale(1.1);
        color: #c82333;
    }
    .stat-value {
        font-size: 24px;
        font-weight: bold;
        margin: 10px 0;
    }
    .stat-percentage {
        font-weight: bold;
        margin-top: 10px;
    }
    .badge {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: bold;
    }
    .badge-success { background-color: #27ae60; color: white; }
    .badge-danger { background-color: #e74c3c; color: white; }
    .badge-warning { background-color: #f39c12; color: white; }
    .badge-dark { background-color: #2c3e50; color: white; }
    .badge-secondary { background-color: #6c757d; color: white; }
</style>

<div class="container mt-4" id="reportContent">
    {{-- Logo and Project Title --}}
    <div class="text-center mb-4">
        <img src="{{ $logo }}" class="logo" alt="Logo">
        <h3 class="mt-2" style="color: {{ $primaryColor }};">{{ $projectName }}</h3>
    </div>

    {{-- Statistics Cards --}}
    <div class="row text-center my-4">
        @php
            $statuses = [
                'available' => ['label' => __('units.available'), 'color' => '#27ae60', 'icon' => 'check-circle'],
                'blocked' => ['label' => __('units.blocked'), 'color' => '#e74c3c', 'icon' => 'ban'],
                'reserved' => ['label' => __('units.reserved'), 'color' => '#f39c12', 'icon' => 'clock'],
                'contracted' => ['label' => __('units.contracted'), 'color' => '#2c3e50', 'icon' => 'file-signature'],
            ];

            $chartConfig = [];
            foreach ($statuses as $key => $info) {
                $count = $result[$key] ?? 0;
                $total = $result['total'] ?? 1;
                $percentage = round(($count / $total) * 100);
                $chartConfig[$key] = [
                    'value' => $percentage,
                    'color' => $info['color'],
                    'icon' => $info['icon'],
                    'count' => $count
                ];
            }
        @endphp

        @foreach ($statuses as $key => $info)
            @php
                $count = $result[$key] ?? 0;
                $total = $result['total'] ?? 1;
                $percentage = round(($count / $total) * 100);
            @endphp
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card shadow p-3 text-center h-100">
                    <h5 style="color: {{ $info['color'] }}">{{ $info['label'] }}</h5>
                    <div class="stat-value" style="color: {{ $info['color'] }}">{{ $count }}</div>
                    <div class="chart-container">
                        <canvas id="circle_{{ $key }}" width="120" height="120"></canvas>
                        <div class="chart-icon" style="color: {{ $info['color'] }}">
                            <i class="fas fa-{{ $info['icon'] }}"></i>
                        </div>
                    </div>
                    <div class="stat-percentage" style="color: {{ $info['color'] }}">
                        {{ $percentage }}%
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Table Title --}}
    <div class="section-title">{{ __('units.title') }}</div>

    {{-- Data Table --}}
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th width="20%">{{ __('units.table.group') }}</th>
                    <th width="40%">{{ __('units.table.description') }}</th>
                    <th width="20%">{{ __('units.table.status') }}</th>
                    <th width="20%">{{ __('units.table.block') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($result['items'] ?? [] as $item)
                    <tr>
                        <td>{{ $item['group'] ?? '-' }}</td>
                        <td>{{ $item['description'] ?? '-' }}</td>
                        <td>
                            <span class="badge
                                @if(($item['unit_status'] ?? '') === 'available') badge-success
                                @elseif(($item['unit_status'] ?? '') === 'blocked') badge-danger
                                @elseif(($item['unit_status'] ?? '') === 'reserved') badge-warning
                                @elseif(($item['unit_status'] ?? '') === 'contracted') badge-dark
                                @else badge-secondary @endif">
                                {{ $item['unit_status'] ?? '-' }}
                            </span>
                        </td>
                        <td>{{ $item['value'] ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            {{ __('units.no_data') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="text-center mt-3 mb-4">
        <small class="text-muted">
            {{ __('units.export_date', ['date' => \Carbon\Carbon::now()->format('d-m-Y H:i:s')]) }}
        </small>
    </div>

    {{-- PDF Export Button --}}
    <div class="pdf-button">
        <a href="javascript:void(0);" onclick="exportPDF()" title="{{ __('Export PDF') }}">
            <i class="fas fa-file-pdf pdf-icon"></i>
            <div style="margin-top: 10px; color: #dc3545; font-weight: bold;">
                {{ __('Download PDF Report') }}
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
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('p', 'mm', 'a4');
        const element = document.getElementById('reportContent');

        // إخفاء زر التصدير مؤقتاً
        const pdfButton = document.querySelector('.pdf-button');
        if (pdfButton) pdfButton.style.display = 'none';

        const canvas = await html2canvas(element, {
            scale: 2,
            useCORS: true,
            logging: false
        });

        const imgData = canvas.toDataURL('image/png');
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

        pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        pdf.save("{{ $site }}_unit_report.pdf");

        // إعادة عرض زر التصدير
        if (pdfButton) pdfButton.style.display = 'block';
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
                        backgroundColor: [data.color, '#f8f9fa'],
                        borderWidth: 0,
                        borderRadius: value === 100 ? 0 : 10,
                    }]
                },
                options: {
                    cutout: '75%',
                    responsive: false,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: { enabled: false },
                        legend: { display: false },
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });
        });
    });
</script>
@endsection
