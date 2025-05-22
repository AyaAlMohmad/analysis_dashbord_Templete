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
        <h4 id="reportTitle" class="no-export">{{ __('unit_stages_staticies.title') }}</h4>
        <select id="site" class="form-control w-25 mx-auto no-export mt-2">
            <option value="">-- {{ __('unit_stages_staticies.select_site') }} --</option>
            <option value="dhahran">{{ __('unit_stages_staticies.dhahran') }}</option>
            <option value="bashaer">{{ __('unit_stages_staticies.bashaer') }}</option>
        </select>
        <img id="logo" src="" style="max-height: 70px; display: none;" class="mt-2">
    </div>

    <div class="header-section d-none" id="reportHeader">{{ __('unit_stages_staticies.title') }}   </div>

    <table class="unit-table d-none" id="unitTable">
        <thead id="unitTableHead"></thead>
        <tbody id="unitTableBody"></tbody>
    </table>

    <div class="text-muted mt-2" id="generatedAt"></div>

    <div class="text-center mt-4" id="pdf-export-button" style="display: none;">
        <button onclick="exportPDF()" class="btn btn-dark"> {{ __('unit_stages_staticies.export_pdf') }}</button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
@php
    $translations = [
        'metric' => __('unit_stages_staticies.metric'),
        'price_range' => __('unit_stages_staticies.price_range'),
        'total_count' => __('unit_stages_staticies.total_count'),
        'available' => __('unit_stages_staticies.available'),
        'contracted' => __('unit_stages_staticies.contracted'),
        'reserved' => __('unit_stages_staticies.reserved'),
        'blocked' => __('unit_stages_staticies.blocked'),
        'contracted_percentage' => __('unit_stages_staticies.contracted_percentage'),
        'sold_percentage' => __('unit_stages_staticies.sold_percentage'),
        'generated_at' => __('unit_stages_staticies.generated_at'),
    ];
@endphp
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
        const translations = @json($translations);
        const headRow = `<tr><th>${translations.metric}</th>${phases.map(p => `<th>${p}</th>`).join('')}</tr>`;
        document.getElementById('unitTableHead').innerHTML = headRow;

        const body = document.getElementById('unitTableBody');
        body.innerHTML = '';

        const getRow = (label, keyFn) => {
            return `<tr><td>${label}</td>` + phases.map(p => keyFn(data[p])).join('') + `</tr>`;
        };
        body.innerHTML += getRow(translations.price_range, d => `<td>${d.min_price} - ${d.max_price}</td>`);
        body.innerHTML += getRow(translations.total_count, d => `<td>${d.count}</td>`);
        body.innerHTML += getRow(translations.available, d => `<td>${d.status_counts.available}</td>`);
        body.innerHTML += getRow(translations.contracted, d => `<td>${d.status_counts.contracted}</td>`);
        body.innerHTML += getRow(translations.reserved, d => `<td>${d.status_counts.reserved}</td>`);
        body.innerHTML += getRow(translations.blocked, d => `<td>${d.status_counts.blocked}</td>`);

        body.innerHTML += getRow(translations.contracted_percentage, d => {
            const val = d.count ? Math.round((d.status_counts.contracted / d.count) * 100) : 0;
            return `<td>${val}%</td>`;
        });
        body.innerHTML += getRow(translations.sold_percentage, d => {

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
