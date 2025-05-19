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
        <h4 id="reportTitle" class="no-export">Unit Stages Report</h4>
        <select name="site" id="site" class="form-control site-select no-export">
            <option value="">-- Select Site --</option>
            <option value="dhahran">Dhahran</option>
            <option value="bashaer">Bashaer</option>
        </select>
        <img id="logo" src="" alt="Logo" style="max-height: 70px; display: none;" class="mt-2">
    </div>

    <div class="header-section d-none" id="reportHeader">Unit Stages Report</div>

    <table class="unit-table d-none" id="unitTable">
        <thead>
            <tr>
                <th>Phase</th>
                <th>Total Units</th>
                <th>Reserved</th>
                <th>Contacted</th>
                <th>Available</th>
                <th>Blocked</th>
            </tr>
        </thead>
        <tbody id="unitTableBody"></tbody>
        <tfoot>
            <tr class="summary-row">
                <td>Total</td>
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
    document.getElementById('site').addEventListener('change', function() {
    const site = this.value;
    if (!site) {
        document.getElementById('unitTable').classList.add('d-none');
        return;
    }

    // إظهار حالة التحميل
    document.getElementById('unitTableBody').innerHTML = `
        <tr>
            <td colspan="6">Loading data...</td>
        </tr>
    `;

    fetch(`/admin/items/unitStages?site=${site}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(async response => {
        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (!data.status) {
            throw new Error(data.message || 'Invalid data received');
        }

        const tbody = document.getElementById('unitTableBody');
        tbody.innerHTML = '';

        // التحقق من وجود البيانات
        if (!data.data || !data.data.groups) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6">No data available</td>
                </tr>
            `;
            return;
        }

        // ملء الجدول بالبيانات
        Object.entries(data.data.groups).forEach(([phase, values]) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${phase}</td>
                <td>${values.total || 0}</td>
                <td>${values.reserved || 0}</td>
                <td>${values.contacted || 0}</td>
                <td>${values.available || 0}</td>
                <td>${values.blocked || 0}</td>
            `;
            tbody.appendChild(row);
        });

        // تحديث الإجماليات
        document.getElementById('grandTotal').textContent = data.data.grand_total || 0;
        document.getElementById('totalReserved').textContent = data.data.totals?.reserved || 0;
        document.getElementById('totalContacted').textContent = data.data.totals?.contacted || 0;
        document.getElementById('totalAvailable').textContent = data.data.totals?.available || 0;
        document.getElementById('totalBlocked').textContent = data.data.totals?.blocked || 0;

        document.getElementById('generatedAt').textContent = "Report generated at: " + new Date().toLocaleString();
        document.getElementById('pdf-export-button').style.display = 'block';
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('unitTableBody').innerHTML = `
            <tr>
                <td colspan="6" class="text-danger">
                    Error: ${error.message}
                </td>
            </tr>
        `;
    });
});
</script>
@endsection
