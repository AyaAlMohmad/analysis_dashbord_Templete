@extends('layouts.app')

@section('content')
@php
    $logo = $site === 'dhahran' ? asset('images/logo1.png') : asset('images/logo2.png');
    $primaryColor = $site === 'dhahran' ? '#00262f' : '#543829';
    $statuses = $result['statuses'] ?? [];
    $reportData = $result['report_data'] ?? [];
    $from = request('from');
    $to = request('to');
@endphp

<style>
    .logo { max-height: 100px; }
    .section-title {
        background-color: {{ $primaryColor }};
        color: #fff;
        text-align: center;
        padding: 15px;
        font-size: 20px;
        border-radius: 6px;
        margin: 20px 0;
    }
    .table {
        width: 100%;
        background: #fff;
        border-collapse: collapse;
    }
    th, td {
        text-align: center;
        padding: 10px;
        border: 1px solid #eee;
    }
    /* th {
        background: {{ $primaryColor }};
        color: #fff;
    } */
</style>

<div class="container mt-4" id="reportContent">
    <div class="text-center mb-3">
        <img src="{{ $logo }}" class="logo" alt="Logo">
    </div>

    <div class="section-title">
        {{ __('source_report.title') }} <br>
        <small>
            @if ($from && $to)
                {{ __('source_report.from_to', [
                    'from' => \Carbon\Carbon::parse($from)->format('d-m-Y'),
                    'to' => \Carbon\Carbon::parse($to)->format('d-m-Y'),
                ]) }}
            @else
                {{ __('source_report.cumulative') }}
            @endif
        </small>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>{{ __('source_report.source') }}</th>
                <th>{{ __('source_report.total_leads') }}</th>
                @foreach($statuses as $status)
                    <th>{{ $status['name'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $row)
                <tr>
                    <td>{{ $row['source_name'] }}</td>
                    <td>{{ $row['total_leads'] }}</td>
                    @foreach($statuses as $status)
                        @php
                            $key = 'status_' . $status['id'];
                        @endphp
                        <td>{{ $row[$key] ?? 0 }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <small class="text-muted">
        {{ __('source_report.generated_at') }} {{ now()->format('d-m-Y H:i:s') }}
    </small>
    

    <div class="text-center my-4" id="pdf-export-button">
        <a href="javascript:void(0);" onclick="exportPDF()" title="Export PDF"
           class="transition duration-300 transform hover:scale-110 hover:rotate-6 d-block mt-4">
            <div class="fonticon-container flex items-center justify-center custom-hover-red">
                <div class="fonticon-wrap"
                     style="width: 100%; height: 60px; line-height: 4.8rem; text-align: center;
                            border-radius: 0.1875rem; margin: 0 auto; background-color: #fff;">
                    <i class="fa fa-file-pdf-o text-danger text-5xl"></i>
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
        const element = document.getElementById('reportContent');

        const canvas = await html2canvas(element, { scale: 2 });
        const imgData = canvas.toDataURL('image/png');

        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

        pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        pdf.save("{{ $site }}_source_report.pdf");
    }
</script>
@endsection
