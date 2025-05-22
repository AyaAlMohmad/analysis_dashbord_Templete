@extends('layouts.app')

@section('content')
<style>
    .table th, .table td {
        white-space: normal;
        word-wrap: break-word;
    }
</style>

@php
    $logo = $site === 'dhahran' ? asset('images/logo1.png') : asset('images/logo2.png');
    $darkColor = $site === 'dhahran' ? '#00262f' : '#543829';
@endphp

<div class="container mt-4 text-center" id="report-content">
    <img src="{{ $logo }}" alt="Azyan Logo" height="80" class="mb-3">

    <div class="py-3 mb-4 rounded text-white" style="background-color: {{ $darkColor }}">
        <h5 class="mb-1">{{ __('reports.sales_report') }}</h5>
        <div>
            <span> {{__('reports.from')}}: {{ $fromDate }} {{__('reports.to')}}: {{ $toDate }}</span>
        </div>
    </div>

    @if (!empty($error))
        <div class="alert alert-danger text-center">{{ $error }}</div>
    @endif

    @if (!empty($data))
        <div class="text-white text-center p-2 mb-4" style="background-color: {{ $darkColor }}">
            <strong>{{__('reports.detailed_report')}} </strong>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        <th>{{__('reports.entry_details')}} </th>
                        <th>{{__('reports.advertisements')}} </th>
                        <th>{{__('reports.offer_attendance')}} </th>
                        <th>{{__('reports.follow_up')}} </th>
                        <th>{{__('reports.final_bookings')}} </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $data['report_data']['leads_logged_in'] ?? 0 }}</td>
                        <td>{{ $data['report_data']['leads_from_exhibition'] ?? 0 }}</td>
                        <td>{{ $data['report_data']['leads_canceled'] ?? 0 }}</td>
                        <td>{{ $data['report_data']['leads_under_processing'] ?? 0 }}</td>
                        <td>{{ $data['report_data']['leads_with_lack_of_interest'] ?? 0 }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        <th>{{__('reports.price')}} </th>
                        <th>{{__('reports.city')}} </th>
                        <th>{{__('reports.area')}} </th>
                        <th>{{__('reports.not_interested_in_area')}} </th>
                        <th>{{__('reports.design')}} </th>
                        <th>{{__('reports.other')}} </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $data['report_data']['lack_of_interest_price'] ?? '-' }}</td>
                        <td>{{ $data['report_data']['lack_of_interest_city'] ?? '-' }}</td>
                        <td>{{ $data['report_data']['lack_of_interest_area'] ?? '-' }}</td>
                        <td>{{ $data['report_data']['lack_of_interest_not_interested_in_area'] ?? '-' }}</td>
                        <td>{{ $data['report_data']['lack_of_interest_design'] ?? '-' }}</td>
                        <td>{{ $data['report_data']['lack_of_interest_other'] ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        <th>{{__('reports.not_qualified_leads')}} </th>
                        <th>{{__('reports.financial_capacity')}} </th>
                        <th>{{__('reports.not_eligible')}} </th>
                        <th>{{__('reports.benefited_support')}} </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $data['report_data']['not_qualified_leads'] ?? 0 }}</td>
                        <td>{{ $data['report_data']['not_qualified_financial_capacity'] ?? '-' }}</td>
                        <td>{{ $data['report_data']['not_qualified_not_eligible'] ?? '-' }}</td>
                        <td>{{ $data['report_data']['not_qualified_benefited_support'] ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif
</div>

<div class="container mt-4" id="team-performance">
    @if (!empty($data['cumulative_stuff_data']) && is_array($data['cumulative_stuff_data']))
        <div class="text-white text-center p-2 my-4" style="background-color: {{ $darkColor }}">
            <strong>{{__('reports.team_cumulative_performance')}} </strong>
        </div>

        <div class="table-responsive" style="overflow-x: auto;">
            <table class="table table-bordered text-center" style="table-layout: fixed; font-size: 12px;">
                <thead class="table-light">
                    <tr>
                        <th>{{__('reports.id')}} </th>
                        <th>{{__('reports.name')}} </th>
                        <th style="min-width: 80px;">{{__('reports.sign_ins')}} </th>
                        <th style="min-width: 80px;">{{__('reports.not_eligible')}} </th>
                        <th style="min-width: 80px;">{{__('reports.no_financial_capacity')}} </th>
                        <th style="min-width: 80px;">{{__('reports.sign_in_capacity')}} </th>
                        <th style="min-width: 80px;">{{__('reports.paid_wo_cancel')}} </th>
                        <th style="min-width: 80px;">{{__('reports.cancel_after_pay')}} </th>
                        <th style="min-width: 80px;">{{__('reports.contracts')}} </th>
                        <th style="min-width: 80px;">{{__('reports.proposal')}} </th>
                        <th style="min-width: 80px;">{{__('reports.contract_count')}} </th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['cumulative_stuff_data'] as $staff)
                        <tr>
                            <td>{{ $staff['staffid'] }}</td>
                            <td>{{ $staff['staff_name'] }}</td>
                            <td>{{ $staff['signin_count'] }}</td>
                            <td>{{ $staff['not_eligible_count'] }}</td>
                            <td>{{ $staff['no_financial_capacity_count'] }}</td>
                            <td>{{ $staff['signin_capacity'] }}</td>
                            <td>{{ $staff['payment_without_cancellations'] }}</td>
                            <td>{{ $staff['cancellations_after_payment'] }}</td>
                            <td>{{ $staff['contracts'] }}</td>
                            <td>{{ $staff['proposal_count'] }}</td>
                            <td>{{ $staff['contract_count'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-center mt-2 mb-4">
            <small class="text-muted">{{__('reports.export_date')}}: {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}</small>
        </div>
    @endif
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

        const reportContent = document.getElementById('report-content');
        const teamContent = document.getElementById('team-performance');

        await html2canvas(reportContent, { scale: 2 }).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
            pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        });

        if (teamContent) {
            pdf.addPage();
            await html2canvas(teamContent, { scale: 2 }).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
                pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
            });
        }

        pdf.save("{{ $site }}_report.pdf");
        if (exportButton) exportButton.style.display = 'block';
    }
</script>
@endsection
