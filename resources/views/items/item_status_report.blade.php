@extends('layouts.app')

@section('content')
@php
    $logoDhahran = asset('images/logo1.png');
    $logoBashaer = asset('images/logo2.png');
@endphp

<style>
    .unit-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .unit-table th,
    .unit-table td {
        text-align: center;
        padding: 8px;
        font-size: 14px;
        border: 1px solid #ccc;
    }

    .summary-row {
        font-weight: bold;
        background-color: #f3f3f3;
    }

    .header-section {
        padding: 15px;
        font-size: 20px;
        border-radius: 8px;
        margin: 20px 0;
        text-align: center;
        color: white;
    }

    .centered-section {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .site-select {
        width: 200px;
        margin-top: 15px;
    }
</style>

<div class="container mt-4 text-center">
    <div class="centered-section">
        <h4 id="reportTitle" class="no-export">{{ __('components.unit_status_report') }}</h4>
        <select name="site" id="site" class="form-control site-select no-export">
            <option value="">{{ __('components.select_site') }}</option>
            <option value="dhahran">{{ __('components.dhahran') }}</option>
            <option value="bashaer">{{ __('components.bashaer') }}</option>
        </select>
        
        <img id="logo" src="" alt="Logo" style="max-height: 70px; display: none;">
    </div>

    <div class="header-section d-none" id="reportHeader">{{ __('components.unit_status_report') }}</div>

    <table class="unit-table d-none" id="unitTable">
        <thead>
            <tr id="tableHeaderRow1"></tr>
            <tr id="tableHeaderRow2"></tr>
        </thead>
        <tbody id="unitTableBody"></tbody>
        <tfoot>
            <tr class="summary-row" id="summaryRow">
                <td>{{ __('components.total') }}</td>
                <td id="totalUnits">0</td>
                <td id="totalAvailableB">0</td>
                <td id="totalAvailableNB">0</td>
                <td id="totalReservedB">0</td>
                <td id="totalReservedNB">0</td>
                <td id="totalSoldB">0</td>
                <td id="totalSoldNB">0</td>
            </tr>
        </tfoot>
    </table>

    <div class="text-muted text-center mt-3" id="generatedAt"></div>

    {{-- PDF Export Button --}}
    <div class="text-center my-4" id="pdf-export-button" style="display: none;">
        <a href="javascript:void(0);" onclick="exportPDF()" title="{{ __('components.export_pdf') }}"
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

{{-- PDF Libraries --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
  function exportPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'mm', 'a4');
    const reportElement = document.querySelector('.container');

    const hiddenElements = document.querySelectorAll('.no-export');
    hiddenElements.forEach(el => el.style.display = 'none');

    html2canvas(reportElement, { scale: 2 }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const pdfWidth = doc.internal.pageSize.getWidth();
        const pdfHeight = canvas.height * pdfWidth / canvas.width;

        const now = new Date();
        const timestamp = now.toISOString().slice(0, 10);
        const selectedSite = document.getElementById('site').value || 'site';

        doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        doc.save(`unit_status_report_${selectedSite}_${timestamp}.pdf`);

        hiddenElements.forEach(el => el.style.display = '');
    });
}

    document.getElementById('site').addEventListener('change', function () {
        const site = this.value;
        if (!site) return;

        const logo = site === 'dhahran' ? '{{ $logoDhahran }}' : '{{ $logoBashaer }}';
        const color = site === 'dhahran' ? '#00262f' : '#543829';

        document.getElementById('logo').src = logo;
        document.getElementById('logo').style.display = 'block';
        document.getElementById('reportTitle').classList.remove('d-none');
        const header = document.getElementById('reportHeader');
        header.classList.remove('d-none');
        header.style.backgroundColor = color;

        const table = document.getElementById('unitTable');
        table.classList.remove('d-none');
        document.getElementById('tableHeaderRow1').innerHTML = `
            <th>{{ __('components.phase') }}</th>
            <th>{{ __('components.total_units') }}</th>
            <th colspan="2">{{ __('components.available') }}</th>
            <th colspan="2">{{ __('components.reserved') }}</th>
            <th colspan="2">{{ __('components.sold') }}</th>`;
        document.getElementById('tableHeaderRow2').innerHTML = `
            <th></th>
            <th></th>
            <th>{{ __('components.beneficiary') }}</th>
            <th>{{ __('components.non_beneficiary') }}</th>
            <th>{{ __('components.beneficiary') }}</th>
            <th>{{ __('components.non_beneficiary') }}</th>
            <th>{{ __('components.beneficiary') }}</th>
            <th>{{ __('components.non_beneficiary') }}</th>`;

        table.querySelectorAll('th').forEach(th => {
            th.style.backgroundColor = color;
            th.style.color = 'white';
        });

        fetch(`{{ route('admin.items.status') }}?site=${site}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(response => {
            if (!response.status) return;

            const data = response.data.data;
            const groups = data.groups;
            const totals = data.totals.status_totals;

            const tbody = document.getElementById('unitTableBody');
            tbody.innerHTML = '';

            groups.forEach(group => {
                const row = document.createElement('tr');
                const getStatus = (statusName) => {
                    const s = group.statuses.find(s => s.status_name === statusName);
                    return {
                        b: s?.beneficiary ?? 0,
                        nb: s?.non_beneficiary ?? 0
                    };
                };
                const available = getStatus('available');
                const reserved = getStatus('reserved');
                const sold = getStatus('sold');

                row.innerHTML = `
                    <td>${group.group_id}</td>
                    <td>${group.total_items}</td>
                    <td>${available.b}</td>
                    <td>${available.nb}</td>
                    <td>${reserved.b}</td>
                    <td>${reserved.nb}</td>
                    <td>${sold.b}</td>
                    <td>${sold.nb}</td>`;
                tbody.appendChild(row);
            });

            const totalAvailable = totals.available ?? { beneficiary: 0, non_beneficiary: 0 };
            const totalReserved = totals.reserved ?? { beneficiary: 0, non_beneficiary: 0 };
            const totalSold = totals.sold ?? { beneficiary: 0, non_beneficiary: 0 };

            document.getElementById('totalUnits').textContent = data.totals.total_items;
            document.getElementById('totalAvailableB').textContent = totalAvailable.beneficiary;
            document.getElementById('totalAvailableNB').textContent = totalAvailable.non_beneficiary;
            document.getElementById('totalReservedB').textContent = totalReserved.beneficiary;
            document.getElementById('totalReservedNB').textContent = totalReserved.non_beneficiary;
            document.getElementById('totalSoldB').textContent = totalSold.beneficiary;
            document.getElementById('totalSoldNB').textContent = totalSold.non_beneficiary;

            document.getElementById('generatedAt').textContent = "{{ __('components.generated_at') }}: " + new Date().toLocaleString();
            document.getElementById('pdf-export-button').style.display = 'block';
        })
        .catch(err => {
            console.error(err);
            document.getElementById('unitTableBody').innerHTML = `<tr><td colspan="8">{{ __('components.error_loading_data') }}</td></tr>`;
        });
    });
</script>
@endsection
