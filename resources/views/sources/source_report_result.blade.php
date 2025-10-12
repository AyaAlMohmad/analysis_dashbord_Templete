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

    // الحالات بالترتيب المطلوب
    $desiredStatuses = [
        'عميل جديد',
        'غير مناسب',
        'متابعة',
        'زيارة',
        'حجز',
        'الغاء',
        'تم التعاقد',
        'مهتم',
        'مهتم بمشروعات الشركة',
        'متوقع حجوزات'
    ];

    $statuses = $result['statuses'] ?? [];
    $reportData = $result['report_data'] ?? [];
    $from = request('from_date');
    $to = request('to_date');

    // إعادة ترتيب الحالات حسب الترتيب المطلوب
    $orderedStatuses = [];
    foreach ($desiredStatuses as $desiredStatus) {
        foreach ($statuses as $status) {
            if ($status['name'] === $desiredStatus) {
                $orderedStatuses[] = $status;
                break;
            }
        }
    }

    // إضافة أي حالات أخرى غير موجودة في القائمة المطلوبة
    foreach ($statuses as $status) {
        if (!in_array($status['name'], $desiredStatuses)) {
            $orderedStatuses[] = $status;
        }
    }
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
        padding: 12px;
        font-size: 18px;
        border-radius: 5px;
        margin: 15px 0;
        font-weight: bold;
    }
    .report-container {
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
        min-width: 800px;
    }
    .data-table th, .data-table td {
        text-align: center;
        padding: 10px 8px;
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
    .total-row {
        font-weight: bold;
        background-color: #e9ecef !important;
        border-top: 2px solid #333;
    }
    .source-name {
        font-weight: bold;
        text-align: left !important;
        padding-left: 15px !important;
    }
    .generated-info {
        text-align: center;
        margin-top: 20px;
        font-size: 12px;
        color: #6c757d;
    }
    .pdf-button {
        text-align: center;
        margin: 30px 0;
    }
    .pdf-icon {
        font-size: 48px;
        color: #dc3545;
        transition: all 0.3s ease;
    }
    .pdf-icon:hover {
        transform: scale(1.1);
        color: #c82333;
    }
</style>

<div class="container" id="reportContent">
    <div class="text-center">
        <img src="{{ $logo }}" class="logo" alt="Logo">
    </div>

    <div class="section-title">
        {{ __('source_report.title') }}
        <div style="font-size: 14px; margin-top: 5px;">
            @if ($from && $to)
                {{ __('source_report.from_to', [
                    'from' => \Carbon\Carbon::parse($from)->format('d-m-Y'),
                    'to' => \Carbon\Carbon::parse($to)->format('d-m-Y'),
                ]) }}
            @else
                {{ __('source_report.cumulative') }}
            @endif
        </div>
    </div>

    <div class="report-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th width="15%">{{ __('source_report.source') }}</th>
                    <th width="10%">{{ __('source_report.total_leads') }}</th>
                    @foreach($orderedStatuses as $status)
                        <th>{{ $status['name'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $grandTotal = 0;
                    $statusTotals = array_fill_keys(array_map(function($s) { return $s['id']; }, $orderedStatuses), 0);
                @endphp

                @foreach($reportData as $row)
                    @php
                        $grandTotal += $row['total_leads'];
                    @endphp
                    <tr>
                        <td class="source-name">{{ $row['source_name'] }}</td>
                        <td>{{ $row['total_leads'] }}</td>
                        @foreach($orderedStatuses as $status)
                            @php
                                $key = 'status_' . $status['id'];
                                $value = $row[$key] ?? 0;
                                $statusTotals[$status['id']] += $value;
                            @endphp
                            <td>{{ $value }}</td>
                        @endforeach
                    </tr>
                @endforeach

                {{-- صف الإجمالي --}}
                <tr class="total-row">
                    <td class="source-name">{{ __('source_report.grand_total') }}</td>
                    <td>{{ $grandTotal }}</td>
                    @foreach($orderedStatuses as $status)
                        <td>{{ $statusTotals[$status['id']] }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>

    <div class="generated-info">
        {{ __('source_report.generated_at') }} {{ now()->format('d-m-Y H:i:s') }}
    </div>

    <div class="pdf-button">
        <a href="javascript:void(0);" onclick="exportPDF()" title="Export PDF">
            <i class="fa fa-file-pdf-o pdf-icon"></i>
            <div style="margin-top: 10px; color: #dc3545; font-weight: bold;">تحميل PDF</div>
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

        const canvas = await html2canvas(element, {
            scale: 2,
            useCORS: true,
            logging: false
        });

        const imgData = canvas.toDataURL('image/png');
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

        pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        pdf.save("{{ $site }}_source_report.pdf");
    }
</script>
@endsection
