@extends('layouts.app')

@section('content')
@php
    $logo = $site === 'aldhahran' ? asset('images/logo1.png') : asset('images/logo2.png');
    $darkColor = $site === 'aldhahran' ? '#00262f' : '#543829';
@endphp

<style>
    body {
        font-family: 'Cairo', sans-serif;
        direction: rtl;
    }

    .title {
        background-color: {{ $darkColor }};
        color: #eae0cc;
        padding: 15px;
        text-align: center;
        font-size: 24px;
        margin-bottom: 20px;
    }

    .card {
        border: 1px solid #ddd;
        border-radius: 5px;
        margin: 10px;
        width: 160px;
    }

    .card-header {
        background-color: #f2f2f2;
        text-align: center;
        padding: 10px;
    }

    .card-footer {
        background-color: #fff;
        text-align: center;
        padding: 10px;
        font-size: 18px;
        font-weight: bold;
    }

    .d-flex {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    .container {
        margin-top: 20px;
    }

    .footer {
        text-align: center;
        font-size: 13px;
        color: #777;
        margin-top: 30px;
    }
</style>

<div class="container" id="reportContent">
    <div class="text-center mb-4">
        <img src="{{ $logo }}" height="80" alt="Logo">
        <div class="title">
            <h2>Customer Report</h2>
            <p>Report from: {{ $from }} to {{ $to }}</p>
        </div>
    </div>

    <div class="title">Detailed Report</div>

    <div class="d-flex">
        @php
            $defaultStatuses = [
                'Interested', 'Ineligible for Support', 'Financially Ineligible', 'Booked', 'Rebooked', 'Undefined',
                'Not Interested', 'Canceled'
            ];
        @endphp

        @foreach ($defaultStatuses as $status)
            @php
                $count = $data[$status]['count'] ?? 0;
            @endphp
            <div class="card text-center">
                <div class="card-header">{{ $status }}</div>
                <div class="card-footer">{{ $count }}</div>
            </div>
        @endforeach
    </div>

    {{-- Not Interested Details --}}
    <hr>
    <div class="d-flex">
        @php
            $notInterestedReasons = [
                'Prices', 'Not Eligible for Support', 'Design', 'Area', 'Financial Capability',
                'Project Location', 'City', 'Unresponsive', 'Hesitant', 'Wants Another Product', 'Other'
            ];
            $values = $data['Not Interested']['values'] ?? [];
        @endphp

        <div class="card text-center">
            <div class="card-header">Not Interested</div>
            <div class="card-footer">{{ $data['Not Interested']['count'] ?? 0 }}</div>
        </div>
    </div>

    <div class="d-flex">
        @foreach ($notInterestedReasons as $reason)
            <div class="card text-center">
                <div class="card-header">{{ $reason }}</div>
                <div class="card-footer">{{ $values[$reason] ?? 0 }}</div>
            </div>
        @endforeach
    </div>

    {{-- Cancellation Details --}}
    <hr>
    <div class="d-flex">
        @php
            $cancellationReasons = [
                'Prices', 'Unit Change', 'City', 'Project Location', 'Financial Capability',
                'Incomplete Procedures', 'Bank Issue', 'Booking Fee Not Paid', 'Area',
                'Design', 'No Payment to Bank', 'Did Not Meet Payment Terms', 'Technical Problem',
                'Wants Another Project', 'Other'
            ];
            $cancelValues = $data['Canceled']['values'] ?? [];
        @endphp

        <div class="card text-center">
            <div class="card-header">Canceled</div>
            <div class="card-footer">{{ $data['Canceled']['count'] ?? 0 }}</div>
        </div>
    </div>

    <div class="d-flex">
        @foreach ($cancellationReasons as $reason)
            <div class="card text-center">
                <div class="card-header">{{ $reason }}</div>
                <div class="card-footer">{{ $cancelValues[$reason] ?? 0 }}</div>
            </div>
        @endforeach
    </div>
</div>

<div id="contract-section" class="container">
    <hr>
    <div class="d-flex">
        @php
            $contractTypes = ['Cash', 'Real Estate Finance', 'Installments'];
            $contractValues = $data['Contract']['values'] ?? [];
        @endphp
        <div class="card text-center">
            <div class="card-header">Contract</div>
            <div class="card-footer">{{ $data['Contract']['count'] ?? 0 }}</div>
        </div>
    </div>
    <div class="d-flex">
        @foreach ($contractTypes as $type)
            <div class="card text-center">
                <div class="card-header">{{ $type }}</div>
                <div class="card-footer">{{ $contractValues[$type] ?? 0 }}</div>
            </div>
        @endforeach
    </div>

    <div class="footer">
        Exported at: {{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}
    </div>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    async function exportPDF() {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('p', 'mm', 'a4');
        const exportButton = document.getElementById('pdf-export-button');
        if (exportButton) exportButton.style.display = 'none';

        await new Promise(resolve => requestAnimationFrame(() => setTimeout(resolve, 200)));

        const reportContent = document.getElementById('reportContent');

        await html2canvas(reportContent, { scale: 3 }).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
            pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        });

        const contractSection = document.getElementById('contract-section');
        if (contractSection) {
            pdf.addPage();
            const canvas = await html2canvas(contractSection, { scale: 3 });
            const imgData = canvas.toDataURL('image/png');
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
            pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        }

        pdf.save("{{ $site }}_report.pdf");
        if (exportButton) exportButton.style.display = 'block';
    }
</script>
@endsection
