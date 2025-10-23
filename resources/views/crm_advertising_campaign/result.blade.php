@extends('layouts.app')

@section('content')
    @php
        $logo = $site === 'aldhahran' ? asset('images/logo1.png') : ($site === 'albashaer' ? asset('images/logo2.png') :  ($site === 'jeddah' ? asset('images/jadah.png') : asset('images/alfursan.png')));
        $darkColor = $site === 'aldhahran' ? '#00262f' :( $site === 'albashaer' ? '#543829' :($site === 'jeddah' ? '#1a472a': '#37160d'));
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


<div class="container" id="fullReport">

    <div class="container">
        <div class="text-center">
            <img src="{{ $logo }}" class="logo" alt="Logo" style="width: 300px; height: 200px;">
        </div>
        <div class="title">
            <h2>{{ __('campaigns.campaign_details') }}</h2>
            <h4>{{ $result->name }}</h4>

        </div>
    </div>

    <div id="contract-section" class="container">
        <hr>
        <div class="d-flex">
            <div class="card text-center">
                <div class="card-header">{{ __('campaigns.Tag Name') }}</div>
                <div class="card-footer">{{ $result->name }}</div>
            </div>
            <div class="card text-center">
                <div class="card-header">{{ __('campaigns.start_date') }}</div>
                <div class="card-footer">{{ $result->from_date }}</div>
            </div>
            <div class="card text-center">
                <div class="card-header">{{ __('campaigns.end_date') }}</div>
                <div class="card-footer">{{ $result->end_date }}</div>
            </div>
            <div class="card text-center">
                <div class="card-header">{{ __('campaigns.source') }}</div>
                <div class="card-footer">{{ $result->source }}</div>
            </div>
            <div class="card text-center">
                <div class="card-header">{{ __('campaigns.total_amount') }}</div>
                <div class="card-footer">{{ number_format($result->total_cpl, 2) }} </div>
            </div>
        </div>
        <hr>



        <div class="d-flex">
            <div class="card text-center">
                <div class="card-header">{{ __('campaigns.cpl') }}</div>
                <div class="card-footer">{{ number_format($result->cpl, 2) }} </div>
            </div>
            <div class="card text-center">
                <div class="card-header">{{ __('campaigns.total_leads') }}</div>
                <div class="card-footer">{{ $result->leads_count }}</div>
            </div>
            <div class="card text-center">
                <div class="card-header">{{ __('campaigns.reserved_leads') }}</div>
                <div class="card-footer">{{ $result->leads_reserved }}</div>
            </div>
            <div class="card text-center">
                <div class="card-header">{{ __('campaigns.contracted_leads') }}</div>
                <div class="card-footer">{{ $result->leads_contracted }}</div>
            </div>
            <div class="card text-center">
                <div class="card-header">{{ __('campaigns.contacted_leads') }}</div>
                <div class="card-footer">{{ $result->leads_contacted }}</div>
            </div>
            <div class="card text-center">
                <div class="card-header">{{ __('campaigns.visits') }}</div>
                <div class="card-footer">{{ $result->leads_visits }}</div>
            </div>

            <div class="card text-center">
                <div class="card-header">{{ __('campaigns.impression') }}</div>
                <div class="card-footer">{{ $result->impression }}</div>
            </div>
            <div class="card text-center">
                <div class="card-header">{{ __('campaigns.clicks') }}</div>
                <div class="card-footer">{{ $result->clicks }}</div>
            </div>
            <div class="card text-center">
                <div class="card-header">{{ __('campaigns.cpc') }}</div>
                <div class="card-footer">{{ number_format($result->cpc, 2) }}</div>
            </div>
            <div class="card text-center">
                <div class="card-header">{{ __('campaigns.ctr') }}</div>
                <div class="card-footer">{{ number_format($result->ctr, 2) }}%</div>
            </div>
            <div class="card text-center">
                <div class="card-header">{{ __('campaigns.cpm') }}</div>
                <div class="card-footer">{{ number_format($result->cpm, 2) }}</div>
            </div>


        </div>

        <div class="footer">
            {{ __('reports.exported_at') }}:{{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}
        </div>



    </div>
    </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        async function exportPDF() {
            const {
                jsPDF
            } = window.jspdf;
            const pdf = new jsPDF('p', 'mm', 'a4');
            const reportElement = document.getElementById('fullReport');

            const exportButton = document.getElementById('pdf-export-button');

            exportButton.style.display = 'none';
            await new Promise(resolve => setTimeout(resolve, 200));
            const canvas = await html2canvas(reportElement, {
                scale: 2
            });
            const imgData = canvas.toDataURL('image/png');
            const imgProps = pdf.getImageProperties(imgData);
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
            let position = 0;
            let heightLeft = pdfHeight;
            pdf.addImage(imgData, 'PNG', 0, position, pdfWidth, pdfHeight);
            heightLeft -= pdf.internal.pageSize.getHeight();
            while (heightLeft > 0) {
                position = heightLeft - pdfHeight;
                pdf.addPage();
                pdf.addImage(imgData, 'PNG', 0, position, pdfWidth, pdfHeight);
                heightLeft -= pdf.internal.pageSize.getHeight();
            }
            pdf.save("{{ $site }}_campaign_report.pdf");
            exportButton.style.display = 'block';
        }
    </script>
@endsection


