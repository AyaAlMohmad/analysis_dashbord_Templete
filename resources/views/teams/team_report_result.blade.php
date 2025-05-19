@extends('layouts.app')

@section('content')
<style>
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

<div class="container mt-4" id="reportContent">
    <div class="text-center">
        <img src="{{ $logo }}" class="logo" alt="Logo">
    </div>

    <div class="section-title" style="background-color: {{ $darkColor }}">
        Team Report<br>
        <small>Cumulative Report</small>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Staff ID</th>
                <th>Staff Name</th>
                <th>Sign In</th>
                <th>Not Eligible</th>
                <th>No Financial Capacity</th>
                <th>Payment w/o Cancel</th>
                <th>Cancel After Payment</th>
                <th>Contracts</th>
                <th>Sakani Leads</th>
                <th>Hazb Leads</th>
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
        <small class="text-muted">Exported at: {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}</small>
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
