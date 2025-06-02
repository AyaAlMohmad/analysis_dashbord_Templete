@extends('layouts.app')

@section('content')
<style>
     .report-container {
        max-width: 100%;
        margin: 0 auto;
        padding: 0;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    /* العنوان */
    .report-header {
        width: 100%;
        border-radius: 0;
        padding: 0;
    }

    /* منطقة الجدول */
    .report-table-container {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
        border: 1px solid #ddd;
        border-top: none;
    }

    /* الجدول */
    table {
        width: 100%;
        margin: 0;
        border-collapse: collapse;
    }

    /* الصفوف */
    tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tbody tr:hover {
        background-color: #f1f1f1;
    }

    /* الخلايا */
    th, td {
        padding: 12px 8px;
        border: 1px solid #ddd;
        text-align: center;
    }

    th {
        background-color: #f8f9fa;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    .logo {
        max-height: 100px;
    }
    .section-title {
        background: #3e1b59;
        color: #fff;
        padding: 15px;
        text-align: center;
        font-size: 22px;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        background: #fff;
    }
    th, td {
        text-align: center;
        padding: 10px;
    }
    th {
        background: #f4f4f4;
    }
</style>

@php
    $logo = $site === 'dhahran' ? asset('images/logo1.png') : asset('images/logo2.png');
    $darkColor = $site === 'dhahran' ? '#00262f' : '#543829';
@endphp

<div class="container mt-4 report-container" id="reportContent">
<div class="text-center py-2">
            <img src="{{ $logo }}" class="logo" alt="Logo">
        </div>
<div class="report-header" style="background-color: {{ $darkColor }} ;">

        <h2 class="text-center text-white py-2 m-0">{{ __('reports.team_report') }}</h2>
        <p class="text-center text-white pb-2 m-0">{{ __('reports.cumulative_report') }}</p>
    </div>
    <div class="report-table-container">
    <table class="table table-bordered m-0">
        <thead>
            <tr>
                <th>{{ __('reports.staff_id') }}</th>
                <th>{{ __('reports.staff_name') }}</th>
                <th>{{ __('reports.sign_in') }}</th>
                <th>{{ __('reports.not_eligible') }}</th>
                <th>{{ __('reports.no_financial_capacity') }}</th>
                <th>{{ __('reports.payment_without_cancellations') }}</th>
                <th>{{ __('reports.cancellations_after_payment') }}</th>
                <th>{{ __('reports.contracts') }}</th>
                <th>{{ __('reports.sakani_leads') }}</th>
                <th>{{ __('reports.hazb_leads') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach($result['data'] ?? [] as $item)
                <tr>
                    <td>{{ $item['staffid'] }}</td>
                    <td>{{ $item['staff_name'] }}</td>
                    <td>{{ $item['signin_count'] }}</td>
                    <td>{{ $item['not_eligible_count'] }}</td>
                    <td>{{ $item['no_financial_capacity_count'] }}</td>
                    <td>{{ $item['payment_without_cancellations'] }}</td>
                    <td>{{ $item['cancellations_after_payment'] }}</td>
                    <td>{{ $item['contracts'] }}</td>
                    <td>{{ $item['sakani_leads_count'] }}</td>
                    <td>{{ $item['hazb_leads_count'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-center mt-3">
        <small class="text-muted">{{ __('reports.exported_at') }} {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}</small>

    </div>
    <div class="text-center my-4" id="pdf-export-button">

        <a href="javascript:void(0);" onclick="exportPDF()" title="Export PDF"
           class="transition duration-300 transform hover:scale-110 hover:rotate-6 d-block mt-4">
            <div class="fonticon-container flex items-center justify-center custom-hover-red">
                <div class="fonticon-wrap"  style="float: left; width: 1104px; height: 60px;line-height: 4.8rem; text-align: center; border-radius: 0.1875rem;margin-right: 1rem;
                             margin-bottom: 1.5rem;">
                    <i class="fa fa-file-pdf-o text-red-500 hover:text-red-700 text-5xl"></i>
                </div>
            </div>
        </a>

    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    async function exportPDF() {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('p', 'mm', 'a4');
        const reportElement = document.getElementById('reportContent');

        // Hide export button
        document.getElementById('pdf-export-button')?.classList.add('d-none');

        // Wait to ensure DOM is stable
        await new Promise(resolve => setTimeout(resolve, 200));

        // Convert element to canvas
        const canvas = await html2canvas(reportElement, { scale: 2 });

        const imgData = canvas.toDataURL('image/png');
        const imgProps = pdf.getImageProperties(imgData);

        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

        const pageHeight = pdf.internal.pageSize.getHeight();
        let position = 0;
        let heightLeft = pdfHeight;

        // First page
        pdf.addImage(imgData, 'PNG', 0, position, pdfWidth, pdfHeight);
        heightLeft -= pageHeight;

        // Add more pages if needed
        while (heightLeft > 0) {
            position = heightLeft - pdfHeight;
            pdf.addPage();
            pdf.addImage(imgData, 'PNG', 0, position, pdfWidth, pdfHeight);
            heightLeft -= pageHeight;
        }

        // Save PDF
        pdf.save("{{ $site }}_team_report.pdf");

        // Show export button again
        document.getElementById('pdf-export-button')?.classList.remove('d-none');
    }
</script>

@endsection
