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
        <h4 id="reportTitle" class="no-export">{{ __('unit_stages.title') }}</h4>
        <select name="site" id="site" class="form-control site-select no-export">
            <option value="">-- {{ __('unit_stages.select_site') }} --</option>
            <option value="dhahran">{{ __('unit_stages.dhahran') }}</option>
            <option value="bashaer">{{ __('unit_stages.bashaer') }}</option>
        </select>
        <img id="logo" src="" alt="Logo" style="max-height: 70px; display: none;" class="mt-2">
    </div>

    <div class="header-section d-none" id="reportHeader"> {{ __('unit_stages.title') }}</div>

    <table class="unit-table d-none" id="unitTable">
        <thead>
            <tr>
                <th>{{ __('unit_stages.phase') }}</th>
                <th>{{ __('unit_stages.total_units') }}</th>
                <th>{{ __('unit_stages.reserved') }}</th>
                <th>{{ __('unit_stages.contacted') }}</th>
                <th>{{ __('unit_stages.available') }}</th>
                <th>{{ __('unit_stages.blocked') }}</th>
            </tr>
        </thead>
        <tbody id="unitTableBody"></tbody>
        <tfoot>
            <tr class="summary-row">
                <td>{{ __('unit_stages.total') }}</td>
                <td id="grandTotal">0</td>
                <td id="totalReserved">0</td>
                <td id="totalContacted">0</td>
                <td id="totalAvailable">0</td>
                <td id="totalBlocked">0</td>
            </tr>
        </tfoot>
    </table>

    <div class="text-muted text-center mt-3" id="generatedAt"></div>

    <div class="text-center my-4" id="pdf-export-button" style="display: none;">
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
</div>

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
            doc.save(`unit_stages_report_${selectedSite}_${timestamp}.pdf`);

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
        table.querySelectorAll('th').forEach(th => {
            th.style.backgroundColor = color;
            th.style.color = 'white';
        });

        fetch(`{{ route('admin.items.unitStages') }}?site=${site}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(response => {
    if (!response.status) return;

    const groupData = response.data;

    const tbody = document.getElementById('unitTableBody');
    tbody.innerHTML = '';

    let totalCount = 0;
    let totalAvailable = 0;
    let totalReserved = 0;
    let totalContacted = 0;
    let totalBlocked = 0;

    Object.entries(groupData).forEach(([phase, values]) => {
        const count = parseInt(values.count) || 0;
        const available = parseInt(values.status_counts.available) || 0;
        const reserved = parseInt(values.status_counts.reserved) || 0;
        const contacted = parseInt(values.status_counts.contracted || 0); // if contracted = contacted
        const blocked = parseInt(values.status_counts.blocked) || 0;

        totalCount += count;
        totalAvailable += available;
        totalReserved += reserved;
        totalContacted += contacted;
        totalBlocked += blocked;

        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${phase}</td>
            <td>${count}</td>
            <td>${reserved}</td>
            <td>${contacted}</td>
            <td>${available}</td>
            <td>${blocked}</td>
        `;
        tbody.appendChild(row);
    });

    document.getElementById('unitTable').classList.remove('d-none');
    document.getElementById('grandTotal').textContent = totalCount;
    document.getElementById('totalAvailable').textContent = totalAvailable;
    document.getElementById('totalReserved').textContent = totalReserved;
    document.getElementById('totalContacted').textContent = totalContacted;
    document.getElementById('totalBlocked').textContent = totalBlocked;

    document.getElementById('generatedAt').textContent = "Report generated at: " + new Date().toLocaleString();
    document.getElementById('pdf-export-button').style.display = 'block';
})

        .catch(err => {
            console.error(err);
            document.getElementById('unitTableBody').innerHTML = `<tr><td colspan="6">Error loading data</td></tr>`;
        });
    });
</script>
@endsection
