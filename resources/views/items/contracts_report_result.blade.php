@extends('layouts.app')

@section('content')
@php
    $themes = [
        'dhahran' => [
            'logo' => asset('images/logo1.png'),
            'primary' => '#00262f',
            'secondary' => '#00838f',
        ],
        'bashaer' => [
            'logo' => asset('images/logo2.png'),
            'primary' => '#543829',
            'secondary' => '#a1887f',
        ]
    ];
    $theme = $themes[$site] ?? $themes['dhahran'];
    $data = $result['data'] ?? [];
@endphp

<style>
    .logo { max-height: 100px; }

    .stat-box {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        padding: 20px;
        text-align: center;
        margin: 10px;
        min-width: 200px;
    }

    .stat-box h4 {
        color: #555;
        font-size: 16px;
        margin-bottom: 10px;
    }

    .stat-box .value {
        font-size: 24px;
        font-weight: bold;
        color: {{ $theme['primary'] }};
    }

    .section-title {
        background: {{ $theme['primary'] }};
        color: #fff;
        padding: 10px;
        font-size: 20px;
        margin: 30px 0 20px;
        text-align: center;
        border-radius: 6px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
    }

    th, td {
        text-align: center;
        padding: 12px;
        border: 1px solid #eee;
    }

    th {
        background: {{ $theme['primary'] }};
        color: #fff;
    }

    .export-button {
        margin-top: 20px;
    }
</style>

<div class="container mt-4" id="reportContent">
    {{-- Logo --}}
    <div class="text-center">
        <img src="{{ $theme['logo'] }}" class="logo" alt="Logo">
    </div>

    {{-- Headings --}}
    <h4 class="text-center mt-2">Contracts Report</h4>
    <p class="text-center text-muted">Cumulative Overview</p>

    {{-- Statistics --}}
    <div class="d-flex justify-content-center flex-wrap">
        <div class="stat-box">
            <h4>Total Contracts</h4>
            <div class="value">{{ $data['all_items'] ?? 0 }}</div>
        </div>
        <div class="stat-box">
            <h4>Visited Contracts</h4>
            <div class="value">{{ $data['visited'] ?? 0 }}</div>
        </div>
        <div class="stat-box">
            <h4>Signed Contracts</h4>
            <div class="value">{{ $data['contracted'] ?? 0 }}</div>
        </div>
        <div class="stat-box">
            <h4>Total Value</h4>
            <div class="value">{{ number_format($data['rated'], 2) }}</div>
        </div>
    </div>

    {{-- Table --}}
    <div class="section-title">Indicators</div>

    <table>
        <thead>
            <tr>
                <th>Metric</th>
                <th>Count</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Visit Rate from All Contracts</td>
                <td>{{ $data['visited'] ?? 0 }}</td>
                <td>{{ ($data['all_items'] ?? 0) > 0 ? round(($data['visited'] / $data['all_items']) * 100, 2) . '%' : '0%' }}</td>
            </tr>
            <tr>
                <td>Contract Rate from Visits</td>
                <td>{{ $data['contracted'] ?? 0 }}</td>
                <td>{{ ($data['visited'] ?? 0) > 0 ? round(($data['contracted'] / $data['visited']) * 100, 2) . '%' : '0%' }}</td>
            </tr>
            <tr>
                <td>Total Value</td>
                <td colspan="2">{{ number_format($data['rated'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Footer --}}
    <div class="text-center mt-3">
        <small class="text-muted">Report generated at: {{ now()->format('H:i:s d-m-Y') }}</small>
    </div>

    {{-- Export Button --}}
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

{{-- JS Libraries --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

{{-- Export PDF Script --}}
<script>
    async function exportPDF() {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('p', 'mm', 'a4');
        const element = document.getElementById('reportContent');

        const canvas = await html2canvas(element, { scale: 2 });
        const imgData = canvas.toDataURL('image/png');

        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

        pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        pdf.save("{{ $site }}_contracts_report.pdf");
    }
</script>
@endsection
