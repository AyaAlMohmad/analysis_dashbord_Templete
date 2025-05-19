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
    .unit-table th, .unit-table td {
        border: 1px solid #ccc;
        text-align: center;
        padding: 8px;
    }
    .header-section {
        padding: 15px;
        font-size: 20px;
        color: white;
        margin: 20px 0;
        border-radius: 8px;
    }
    .centered-section {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
</style>

<div class="container mt-4 text-center">
    <div class="centered-section">
        <h4 id="reportTitle" class="no-export">Unit Statistics by Stage</h4>
        <select id="site" class="form-control w-25 mx-auto no-export mt-2">
            <option value="">-- Select Site --</option>
            <option value="dhahran">Dhahran</option>
            <option value="bashaer">Bashaer</option>
        </select>
        <img id="logo" src="" style="max-height: 70px; display: none;" class="mt-2">
    </div>

    <div class="header-section d-none" id="reportHeader">Unit Statistics by Stage</div>

    <table class="unit-table d-none" id="unitTable">
        <thead id="unitTableHead"></thead>
        <tbody id="unitTableBody"></tbody>
    </table>

    <div class="text-muted mt-2" id="generatedAt"></div>

    <div class="text-center mt-4" id="pdf-export-button" style="display: none;">
        <button onclick="exportPDF()" class="btn btn-dark">Export PDF</button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
function exportPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const report = document.querySelector('.container');
    const hidden = document.querySelectorAll('.no-export');
    hidden.forEach(el => el.style.display = 'none');

    html2canvas(report, { scale: 2 }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const pdfWidth = doc.internal.pageSize.getWidth();
        const pdfHeight = canvas.height * pdfWidth / canvas.width;
        doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        doc.save('unit_statistics_by_stage.pdf');
        hidden.forEach(el => el.style.display = '');
    });
}

document.getElementById('site').addEventListener('change', function () {
    const site = this.value;
    if (!site) return;

    const logo = site === 'dhahran' ? '{{ $logoDhahran }}' : '{{ $logoBashaer }}';
    const color = site === 'dhahran' ? '#00262f' : '#543829';
    document.getElementById('logo').src = logo;
    document.getElementById('logo').style.display = 'block';
    document.getElementById('reportHeader').style.backgroundColor = color;
    document.getElementById('reportHeader').classList.remove('d-none');

    fetch(`{{ route('admin.items.unitStages') }}?site=${site}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(response => {
        if (!response.status) return;

        const data = response.data;
        const phases = Object.keys(data);
        const metrics = [
            'Price (Min - Max)', 'Total Count', 'Available', 'Contracted', 'Reserved', 'Blocked',
            'Contracted %', 'Sold %'
        ];

        const headRow = `<tr><th>Metric</th>${phases.map(p => `<th>${p}</th>`).join('')}</tr>`;
        document.getElementById('unitTableHead').innerHTML = headRow;

        const body = document.getElementById('unitTableBody');
        body.innerHTML = '';

        const getRow = (label, keyFn) => {
            return `<tr><td>${label}</td>` + phases.map(p => keyFn(data[p])).join('') + `</tr>`;
        };

        body.innerHTML += getRow('Price (Min - Max)', d => `<td>${d.min_price} - ${d.max_price}</td>`);
        body.innerHTML += getRow('Total Count', d => `<td>${d.count}</td>`);
        body.innerHTML += getRow('Available', d => `<td>${d.status_counts.available}</td>`);
        body.innerHTML += getRow('Contracted', d => `<td>${d.status_counts.contracted}</td>`);
        body.innerHTML += getRow('Reserved', d => `<td>${d.status_counts.reserved}</td>`);
        body.innerHTML += getRow('Blocked', d => `<td>${d.status_counts.blocked}</td>`);
        body.innerHTML += getRow('Contracted %', d => {
            const val = d.count ? Math.round((d.status_counts.contracted / d.count) * 100) : 0;
            return `<td>${val}%</td>`;
        });
        body.innerHTML += getRow('Sold %', d => {
            const sold = d.status_counts.contracted + d.status_counts.reserved;
            const val = d.count ? Math.round((sold / d.count) * 100) : 0;
            return `<td>${val}%</td>`;
        });

        document.getElementById('unitTable').classList.remove('d-none');
        document.getElementById('pdf-export-button').style.display = 'block';
        document.getElementById('generatedAt').textContent = "Generated at: " + new Date().toLocaleString();
    })
    .catch(err => {
        console.error(err);
        alert('Error loading data');
    });
});
</script>
@endsection
