@extends('layouts.app')

@section('content')
    <style>
        .logo-section {
            background: linear-gradient(45deg, #4b3a3a 0%, #794D30 70%, #3b2b20 100%);
            color: white;
            text-align: center;
            padding: 60px 20px;
            font-family: 'Arial', sans-serif;
        }

        .main-logo img {
            height: 200px;
            margin-bottom: 30px;
        }

        .projects-logos {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            gap: 60px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .project-logo {
            text-align: center;
        }

        .project-logo img {
            height: 50px;
            margin-bottom: 10px;
        }

        .page-break {
            page-break-after: always;
            break-after: page;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #report-content,
            #report-content * {
                visibility: visible;
            }

            #report-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>

    <div id="report-content">

        <div class="page-section">
            <div class="logo-section">
                <div class="main-logo">
                    <img src="{{ asset('build/logo6.png') }}" alt="Tatwir Logo">
                </div>
                <div class="projects-logos">
                    <div class="project-logo">
                        <img src="{{ asset('images/logo4.png') }}" alt="Azyan Al Dhahran">
                    </div>
                    <div class="project-logo">
                        <img src="{{ asset('images/logo3.png') }}" alt="Azyan Al Bashaer">
                    </div>
                </div>
            </div>
        </div>


        <div class="page-section">
            <div class="khozam-section"
                style="background-color: #f9f6f2; padding: 60px 20px; direction: rtl; position: relative; display: flex; align-items: center; justify-content: space-between; font-family: 'Arial', sans-serif; flex-wrap: wrap;">
                <div style="position: absolute; top: 30px; right: 30px;">
                    <img src="{{ asset('build/logo.png') }}" alt="Tatwir Logo" style="height: 60px;">
                </div>
                <div style="flex: 2; text-align: center; padding: 20px; max-width: 100%;">
                    <div style="margin-bottom: 30px;">
                        <img src="{{ asset('images/logo5.png') }}" alt="Project Logo" style="height:200px;">
                    </div>
                    <div style="margin-top: 20px; font-size: 18px; color: #8b5a3b;">
                        @if (request()->filled('from_date') && request()->filled('to_date'))
                            From {{ request('from_date') }} to {{ request('to_date') }}
                        @else
                            Please select a date range from the form
                        @endif
                    </div>


                </div>
                <div style="flex: 1; min-width: 200px; display: flex; justify-content: flex-start;">
                    <img src="{{ asset('images/style.png') }}" alt="Decoration" style="height: 400px;">
                </div>
            </div>
        </div>


        <!-- قسم الظهران -->
        <div class="project-section" style="margin-bottom: 50px;">

            <div class="page-section">@include('comprehensive.colored_map', [
                'data' => $dhahranColoredMap,
                'project_name' => 'أزيان الظهران',
            ])</div>
            <div class="page-section">@include('comprehensive.reserved_report', [
                'data' => $dhahranData,
                'project_name' => 'أزيان الظهران',
            ])</div>
            <div class="page-section">@include('comprehensive.contracts_report', [
                'data' => $dhahranDataContract,
                'project_name' => 'أزيان الظهران',
            ])</div>
            <div class="page-section">@include('comprehensive.status_item', [
                'statusData' => $dhahranStatusData,
                'project_name' => 'أزيان الظهران',
            ])</div>
             <div class="page-section">@include('comprehensive.project_summary', [
                'data' => $dhahranProjectSummary,
                'project_name' => 'أزيان الظهران',
            ])</div>
            <div class="page-section">@include('comprehensive.unitStages', [
                'unitStages' => $dhahranUnitStages,
                'project_name' => 'أزيان الظهران',
            ])</div>
            <div class="page-section">@include('comprehensive.unitStatisticsByStage', [
                'unitStats' => $unitDetailsByStageResultDhahran,
                'project_name' => 'أزيان الظهران',
            ])</div>
            <div class="page-section">@include('comprehensive.visits_payments_contracts', [
                'data' => $dhahranVPCData,
                'project_name' => 'أزيان الظهران',
            ])</div>
            <div class="page-section">@include('comprehensive.disinterest_reasons', [
                'data' => $dhahranDisinterestReasons,
                'project_name' => 'أزيان الظهران',
            ])</div>
            <div class="page-section">@include('comprehensive.unit_sales', [
                'data' => $dhahranUnitSales,
                'project_name' => 'أزيان الظهران',
            ])</div>

            <div class="page-section">@include('comprehensive.source_stats', [
                'data' => $dhahranUnitStatisticsByStage,
                'project_name' => 'أزيان الظهران',
            ])></div>
            <div class="page-section">@include('comprehensive.monthly_appointments', [
                'data' => $dhahranMonthlyAppointments,
                'project_name' => 'أزيان الظهران',
            ])</div>
            <div class="page-section">@include('comprehensive.targeted_report', [
                'data' => $dhahranTargetedReport,
                'project_name' => 'أزيان الظهران',
            ])</div>

        </div>
        <div class="page-section">
            <div class="khozam-section"
                style="background-color: #f9f6f2; padding: 60px 20px; direction: rtl; position: relative; display: flex; align-items: center; justify-content: space-between; font-family: 'Arial', sans-serif; flex-wrap: wrap;">
                <div style="position: absolute; top: 30px; right: 30px;">
                    <img src="{{ asset('build/logo.png') }}" alt="Tatwir Logo" style="height: 60px;">
                </div>
                <div style="flex: 2; text-align: center; padding: 20px; max-width: 100%;">
                    <div style="margin-bottom: 30px;">
                        <img src="{{ asset('images/logo6.png') }}" alt="Project Logo" style="height:200px;">
                    </div>
                    <div style="margin-top: 20px; font-size: 18px; color: #8b5a3b;">
                        @if (request()->filled('from_date') && request()->filled('to_date'))
                            From {{ request('from_date') }} to {{ request('to_date') }}
                        @else
                            Please select a date range from the form
                        @endif
                    </div>


                </div>
                <div style="flex: 1; min-width: 200px; display: flex; justify-content: flex-start;">
                    <img src="{{ asset('images/style.png') }}" alt="Decoration" style="height: 400px;">
                </div>
            </div>
        </div>

        <!-- قسم البشائر -->
        <div class="project-section" style="margin-bottom: 50px;">
            {{-- <h2 style="text-align: center; color: #8b5a3b; margin-bottom: 30px;">مشروع أزيان البشائر</h2> --}}
            <div class="page-section">@include('comprehensive.colored_map', [
                'data' => $albashaerColoredMap,
                'project_name' => 'أزيان البشائر',
            ])</div>
            <div class="page-section">@include('comprehensive.reserved_report', [
                'data' => $albashaerData,
                'project_name' => 'أزيان البشائر',
            ])</div>
            <div class="page-section">@include('comprehensive.contracts_report', [
                'data' => $albashaerDataContract,
                'project_name' => 'أزيان البشائر',
            ])</div>
            <div class="page-section">@include('comprehensive.status_item', [
                'statusData' => $bashaerStatusData,
                'project_name' => 'أزيان البشائر',
            ])</div>
            <div class="page-section">@include('comprehensive.project_summary', [
                'data' => $albashaerProjectSummary,
                'project_name' => 'أزيان البشائر',
            ])</div>
            <div class="page-section">@include('comprehensive.unitStages', [
                'unitStages' => $albashaerUnitStages,
                'project_name' => 'أزيان البشائر',
            ])</div>
            <div class="page-section">@include('comprehensive.unitStatisticsByStage', [
                'unitStats' => $unitDetailsByStageResultAlbashaer,
                'project_name' => 'أزيان البشائر',
            ])</div>
            <div class="page-section">@include('comprehensive.visits_payments_contracts', [
                'data' => $albashaerVPCData,
                'project_name' => 'أزيان البشائر',
            ])</div>
            <div class="page-section">@include('comprehensive.disinterest_reasons', [
                'data' => $albashaerDisinterestReasons,
                'project_name' => 'أزيان البشائر',
            ])</div>
            <div class="page-section">@include('comprehensive.unit_sales', [
                'data' => $albashaerUnitSales,
                'project_name' => 'أزيان البشائر',
            ])</div>

            <div class="page-section">@include('comprehensive.source_stats', [
                'data' => $albashaerUnitStatisticsByStage,
                'project_name' => 'أزيان البشائر',
            ])></div>
            <div class="page-section">@include('comprehensive.monthly_appointments', [
                'data' => $albashaerMonthlyAppointments,
                'project_name' => 'أزيان البشائر',
            ])</div>
            <div class="page-section">@include('comprehensive.targeted_report', [
                'data' => $albashaerTargetedReport,
                'project_name' => 'أزيان البشائر',
            ])</div>
        </div>






        <!-- الصفحة الأخيرة -->
        <div class="page-section">
            <div class="logo-section">
                <div class="main-logo">
                    <img src="{{ asset('build/logo5.png') }}" alt="Tatwir Logo">
                </div>
                <div style="margin-top: 20px; font-size: 24px; color: #ccc;">
                    وشكرا
                </div>
                <div class="projects-logos">
                    <div class="project-logo">
                        <img src="{{ asset('images/logo4.png') }}" alt="Azyan Al Dhahran">
                    </div>
                    <div class="project-logo">
                        <img src="{{ asset('images/logo3.png') }}" alt="Azyan Al Bashaer">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center my-4" id="pdf-export-button">
        <a href="javascript:void(0);" onclick="exportToPDF()" title="Export PDF"
            class="transition duration-300 transform hover:scale-110 hover:rotate-6 d-block mt-4">
            <div class="fonticon-container flex items-center justify-center custom-hover-red">
                <div class="fonticon-wrap"
                    style="float: left; width: 1104px; height: 60px;line-height: 4.8rem; text-align: center; border-radius: 0.1875rem;margin-right: 1rem; margin-bottom: 1.5rem;">
                    <i class="fa fa-file-pdf-o text-red-500 hover:text-red-700 text-5xl"></i>
                </div>
            </div>
        </a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
        async function exportToPDF() {
            const {
                jsPDF
            } = window.jspdf;
            // const pdf = new jsPDF('p', 'mm', 'a4');
            const pdf = new jsPDF('l', 'mm', 'a4'); // 'l' تعني landscape

            const exportButton = document.getElementById('pdf-export-button');
            exportButton.style.display = 'none';


            document.querySelectorAll('#pdf-export-button').forEach(el => el.style.display = 'none');


            const pageSections = document.querySelectorAll('.page-section');

            for (let i = 0; i < pageSections.length; i++) {
                const section = pageSections[i];


                section.style.visibility = 'visible';
                section.style.position = 'absolute';
                section.style.left = '0';
                section.style.top = '0';
                section.style.width = '100%';


                const canvas = await html2canvas(section, {
                    scale: 2,
                    logging: true,
                    useCORS: true,
                    allowTaint: true
                });


                const imgData = canvas.toDataURL('image/png');
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

                if (i > 0) {
                    pdf.addPage();
                }

                pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);

                // إعادة العناصر إلى وضعها الطبيعي
                section.style.visibility = '';
                section.style.position = '';
                section.style.left = '';
                section.style.top = '';
                section.style.width = '';
            }

            // حفظ الملف
            pdf.save("{{ $site ?? 'report' }}_report.pdf");

            // إعادة عرض زر التصدير
            exportButton.style.display = 'block';
            document.querySelectorAll('#pdf-export-button').forEach(el => el.style.display = '');
        }
    </script>
@endsection
