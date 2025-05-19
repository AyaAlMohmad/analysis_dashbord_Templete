@extends('layouts.app')

@section('content')
@php
    $logo = $site === 'dhahran' ? asset('images/logo1.png') : asset('images/logo2.png');
    $darkColor = $site === 'dhahran' ? '#00262f' : '#543829';
@endphp

{{-- JS Libraries --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

<div class="container mt-4 text-center" id="report-content">

    <img src="{{ $logo }}" alt="Azyan Logo" height="80" class="mb-3">

    <div class="py-3 mb-4 rounded text-white" style="background-color: {{ $darkColor }}">
        <h5 class="mb-1">Customer lists, social media, and outreach</h5>
        <h6 class="mb-0">Cumulative Report</h6>
    </div>

    @if (isset($result['status']) && $result['status'] === 'success')

        {{-- Main Table --}}
        <div class="table-responsive mb-4">
            <table class="table table-bordered text-center">
                <thead style="background-color: {{ $darkColor }}; color: white;">
                    <tr>
                        <th>Staff ID</th>
                        <th>Staff Name</th>
                        <th>Calls</th>
                        <th>Offers</th>
                        <th>Visits</th>
                        <th>Contracts</th>
                        <th>Units</th>
                        <th>Leads</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($result['report_data'] as $row)
                        <tr>
                            <td>{{ $row['staffid'] }}</td>
                            <td>{{ $row['staff_name'] }}</td>
                            <td>{{ $row['call_logs_count'] }}</td>
                            <td>{{ $row['interest_leads_count'] }}</td>
                            <td>{{ $row['visit_count'] }}</td>
                            <td>{{ $row['contracts'] }}</td>
                            <td>{{ $row['item_count'] }}</td>
                            <td>{{ $row['leads'] }}</td>
                        </tr>
                    @endforeach
                    <tr class="fw-bold table-light">
                        <td colspan="7">Total</td>
                        <td>{{ array_sum(array_column($result['report_data'], 'leads')) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Table 2 --}}
        <div class="text-end fw-bold mb-2">Leeds visited</div>
        <div class="table-responsive mb-4">
            <table class="table table-bordered text-center">
                <thead style="background-color: {{ $darkColor }}; color: white;">
                    <tr>
                        <th>Source</th>
                        <th>Count</th>
                        <th>Success</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($result['report_data3'] ?? [] as $row)
                        <tr>
                            <td>{{ $row['lead_source_name'] ?? '-' }}</td>
                            <td>{{ $row['lead_count'] ?? 0 }}</td>
                            <td>{{ $row['success'] ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3">No data available</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table 3 --}}
        <div class="text-end fw-bold mb-2">Leeds paid</div>
        <div class="table-responsive mb-4">
            <table class="table table-bordered text-center">
                <thead style="background-color: {{ $darkColor }}; color: white;">
                    <tr>
                        <th>Source</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($result['report_data4'] ?? [] as $row)
                        <tr>
                            <td>{{ $row['lead_source_name'] ?? '-' }}</td>
                            <td>{{ $row['lead_count'] ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2">No data available</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div class="text-center mt-3">
            <small>Report Export Date: {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}</small>
        </div>

        {{-- Export Button --}}
        <div class="center ">
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
    @else
        <div class="alert alert-danger">Failed to load report data.</div>
    @endif
</div>

{{-- Export Script --}}
<script>
    async function exportPDF() {
        const { jsPDF } = window.jspdf;

        const element = document.getElementById('report-content');

        html2canvas(element, { scale: 2 }).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF('p', 'mm', 'a4');
            const imgProps = pdf.getImageProperties(imgData);
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

            pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
            pdf.save("{{ $site }}_report.pdf");
        });
    }
</script>
@endsection
