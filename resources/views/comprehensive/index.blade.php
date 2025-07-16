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

        #pdf-loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
        }

        .loading-backdrop {
            position: absolute;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(5px);
            background-color: rgba(255, 255, 255, 0.4);
        }

        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .spinner-circle {
            border: 6px solid #ccc;
            border-top: 6px solid #8b5a3b;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
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
                    @foreach($localSites as $site)
                    @if($site->logo_path_white)
                        <div class="project-logo">
                            <img src="{{ asset('storage/'.$site->logo_path_white) }}" alt="{{ $site->name }}" style="height: 50px;">
                        </div>
                    @endif
                @endforeach
                </div>
            </div>
        </div>
        @if (in_array('dhahran', $requestSites))
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
                            @if ($from_date && $to_date)
                            From {{ $from_date }} to {{ $to_date }}
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
        @endif

        <div class="project-section" style="margin-bottom: 50px;">
            @if (in_array('dhahran', $requestSites) && in_array('colored_map', $requestSections))
                <div class="page-section">@include('comprehensive.colored_map', [
                    'data' => $dhahranColoredMap,
                    'project_name' => 'أزيان الظهران',
                ])</div>
            @endif
            @if (in_array('dhahran', $requestSites) && in_array('reserved_report', $requestSections))
                <div class="page-section">@include('comprehensive.reserved_report', [
                    'data' => $dhahranData,
                    'project_name' => 'أزيان الظهران',
                ])</div>
            @endif
            @if (in_array('dhahran', $requestSites) && in_array('contracts_report', $requestSections))
                <div class="page-section">@include('comprehensive.contracts_report', [
                    'data' => $dhahranDataContract,
                    'project_name' => 'أزيان الظهران',
                ])</div>
            @endif
            @if (in_array('dhahran', $requestSites) && in_array('status_item', $requestSections))
                <div class="page-section">@include('comprehensive.status_item', [
                    'statusData' => $dhahranStatusData,
                    'project_name' => 'أزيان الظهران',
                ])</div>
            @endif
            @if (in_array('dhahran', $requestSites) && in_array('project_summary', $requestSections))
                <div class="page-section">@include('comprehensive.project_summary', [
                    'data' => $dhahranProjectSummary,
                    'project_name' => 'أزيان الظهران',
                ])</div>
            @endif
            @if (in_array('dhahran', $requestSites) && in_array('unitStages', $requestSections))
                <div class="page-section no-print">@include('comprehensive.unitStages', [
                    'unitStages' => $dhahranUnitStages,
                    'project_name' => 'أزيان الظهران',
                ])</div>
            @endif
            @if (in_array('dhahran', $requestSites) && in_array('unitStatisticsByStage', $requestSections))
                <div class="page-section">@include('comprehensive.unitStatisticsByStage', [
                    'unitStats' => $unitDetailsByStageResultDhahran,
                    'project_name' => 'أزيان الظهران',
                ])</div>
            @endif
            @if (in_array('dhahran', $requestSites) && in_array('visits_payments_contracts', $requestSections))
                <div class="page-section">@include('comprehensive.visits_payments_contracts', [
                    'data' => $dhahranVPCData,
                    'project_name' => 'أزيان الظهران',
                ])</div>
            @endif
            @if (in_array('dhahran', $requestSites) && in_array('disinterest_reasons', $requestSections))
                <div class="page-section">@include('comprehensive.disinterest_reasons', [
                    'data' => $dhahranDisinterestReasons,
                    'project_name' => 'أزيان الظهران',
                ])</div>
            @endif
            @if (in_array('dhahran', $requestSites) && in_array('unit_sales', $requestSections))
                <div class="page-section">@include('comprehensive.unit_sales', [
                    'data' => $dhahranUnitSales,
                    'project_name' => 'أزيان الظهران',
                ])</div>
            @endif
            @if (in_array('dhahran', $requestSites) && in_array('source_stats', $requestSections))
                <div class="page-section">@include('comprehensive.source_stats', [
                    'data' => $dhahranUnitStatisticsByStage,
                    'project_name' => 'أزيان الظهران',
                ])></div>
            @endif
            @if (in_array('dhahran', $requestSites) && in_array('monthly_appointments', $requestSections))
                <div class="page-section">@include('comprehensive.monthly_appointments', [
                    'data' => $dhahranMonthlyAppointments,
                    'project_name' => 'أزيان الظهران',
                ])</div>
            @endif
            @if (in_array('dhahran', $requestSites) && in_array('targeted_report', $requestSections))
                <div class="page-section">@include('comprehensive.targeted_report', [
                    'data' => $dhahranTargetedReport,
                    'project_name' => 'أزيان الظهران',
                ])</div>
            @endif
        </div>

        @if (in_array('albashaer', $requestSites))
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
                            @if ($from_date && $to_date)
                            From {{ $from_date }} to {{ $to_date }}
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
        @endif

        <div class="project-section" style="margin-bottom: 50px;">
            @if (in_array('albashaer', $requestSites)&& in_array('colored_map', $requestSections))
            <div class="page-section">@include('comprehensive.colored_map', [
                'data' => $albashaerColoredMap,
                'project_name' => 'أزيان البشائر',
            ])</div>
            @endif
            @if (in_array('albashaer', $requestSites)&& in_array('reserved_report', $requestSections))
            <div class="page-section">@include('comprehensive.reserved_report', [
                'data' => $albashaerData,
                'project_name' => 'أزيان البشائر',
            ])</div>
            @endif
            @if (in_array('albashaer', $requestSites)&& in_array('contracts_report', $requestSections))
            <div class="page-section">@include('comprehensive.contracts_report', [
                'data' => $albashaerDataContract,
                'project_name' => 'أزيان البشائر',
            ])</div>
            @endif
            @if (in_array('albashaer', $requestSites)&& in_array('status_item', $requestSections))
            <div class="page-section">@include('comprehensive.status_item', [
                'statusData' => $bashaerStatusData,
                'project_name' => 'أزيان البشائر',
            ])</div>
            @endif
            @if (in_array('albashaer', $requestSites)&& in_array('project_summary', $requestSections))
            <div class="page-section">@include('comprehensive.project_summary', [
                'data' => $albashaerProjectSummary,
                'project_name' => 'أزيان البشائر',
            ])</div>
            @endif
            @if (in_array('albashaer', $requestSites)&& in_array('unitStages', $requestSections))
            <div class="page-section no-print">@include('comprehensive.unitStages', [
                'unitStages' => $albashaerUnitStages,
                'project_name' => 'أزيان البشائر',
            ])</div>
            @endif
            @if (in_array('albashaer', $requestSites)&& in_array('unitStatisticsByStage', $requestSections))
            <div class="page-section">
                @include('comprehensive.unitStatisticsByStage', [
                    'unitStats' => $unitDetailsByStageResultAlbashaer,
                    'project_name' => 'أزيان البشائر',
                ])
            </div>
            @endif
            @if (in_array('albashaer', $requestSites)&& in_array('visits_payments_contracts', $requestSections))
            <div class="page-section">@include('comprehensive.visits_payments_contracts', [
                'data' => $albashaerVPCData,
                'project_name' => 'أزيان البشائر',
            ])</div>
            @endif
            @if (in_array('albashaer', $requestSites)&& in_array('disinterest_reasons', $requestSections))
            <div class="page-section">@include('comprehensive.disinterest_reasons', [
                'data' => $albashaerDisinterestReasons,
                'project_name' => 'أزيان البشائر',
            ])</div>
            @endif
            @if (in_array('albashaer', $requestSites)&& in_array('unit_sales', $requestSections))
            <div class="page-section">@include('comprehensive.unit_sales', [
                'data' => $albashaerUnitSales,
                'project_name' => 'أزيان البشائر',
            ])</div>
            @endif
            @if (in_array('albashaer', $requestSites)&& in_array('source_stats', $requestSections))
            <div class="page-section">@include('comprehensive.source_stats', [
                'data' => $albashaerUnitStatisticsByStage,
                'project_name' => 'أزيان البشائر',
            ])></div>
              @endif
              @if (in_array('albashaer', $requestSites)&& in_array('monthly_appointments', $requestSections))
            <div class="page-section">@include('comprehensive.monthly_appointments', [
                'data' => $albashaerMonthlyAppointments,
                'project_name' => 'أزيان البشائر',
            ])</div>
            @endif
            @if (in_array('albashaer', $requestSites)&& in_array('targeted_report', $requestSections))
            <div class="page-section">@include('comprehensive.targeted_report', [
                'data' => $albashaerTargetedReport,
                'project_name' => 'أزيان البشائر',
            ])</div>
              @endif

        </div>

@foreach($localSectionResults as $siteId => $siteData)
    @php
        $site = $siteData['site'];
        $sections = $siteData['sections'];
    @endphp

    <div class="page-section">
        <div class="khozam-section" style="background-color: #f9f6f2; padding: 60px 20px; direction: rtl; position: relative; display: flex; align-items: center; justify-content: space-between; font-family: 'Arial', sans-serif; flex-wrap: wrap;">
            <div style="position: absolute; top: 30px; right: 30px;">
                <img src="{{ asset('build/logo.png') }}" alt="Tatwir Logo" style="height: 60px;">
            </div>
            <div style="flex: 2; text-align: center; padding: 20px; max-width: 100%;">
                <div style="margin-bottom: 30px;">
                    @if($site->logo_path)
                        <img src="{{ asset('storage/'.$site->logo_path) }}" alt="{{ $site->name }}" style="height:200px;">
                    @else
                        <h2 style="font-size: 32px; color: #8b5a3b;">{{ $site->name }}</h2>
                    @endif
                </div>
                <div style="margin-top: 20px; font-size: 18px; color: #8b5a3b;">
                    @if ($from_date && $to_date)
                    From {{ $from_date }} to {{ $to_date }}
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

    <div class="project-section" style="margin-bottom: 50px;">

        @if(in_array('colored_map', $requestSections) && isset($sections['colored_map']))
            <div class="page-section">@include('comprehensive.colored_map', [
                'data' => ['data' => $sections['colored_map']],
                'project_name' => $site->name,
                'logo'=>$site->logo_path,
                'map'=>$site->map_path,
            ])</div>
        @endif

        @if(in_array('reserved_report', $requestSections) && isset($sections['reserved_report']))
        <div class="page-section">
            @include('comprehensive.reserved_report', [
                // 'projects' => $sections['reserved_report']['data']['projects'] ?? [],
                'projects' => $projects,
                // 'chart' => $sections['reserved_report']['data']['chart'] ?? [],
                'project_name' => $site->name,
                'logo'=>$site->logo_path,
            ])
        </div>
    @endif


        @if(in_array('contracts_report', $requestSections) && isset($sections['contracts_report']))
            <div class="page-section">
                @include('comprehensive.contracts_report', [
    'projects' => $sections['contracts_report']['data']['projects'] ?? [],
    // 'chart' => $sections['contracts_report']['data']['chart'] ?? [],
    'project_name' => $site->name,
    'logo'=>$site->logo_path,
])

            </div>
        @endif

        @if(in_array('status_item', $requestSections) && isset($sections['status_item']))
            <div class="page-section">@include('comprehensive.status_item', [
                'statusData' => $sections['status_item'],
                'project_name' => $site->name,
                'logo'=>$site->logo_path,
            ])</div>
        @endif

        @if(in_array('project_summary', $requestSections) && isset($sections['project_summary']))
            <div class="page-section">@include('comprehensive.project_summary', [
                'data' => $sections['project_summary'],
                'project_name' => $site->name,
                'logo'=>$site->logo_path,
            ])
            </div>
        @endif

        @if(in_array('unitStages', $requestSections) && isset($sections['unitStages']))
    <div class="page-section no-print">
        @include('comprehensive.unitStages', [
            'unitStages' => $sections['unitStages'],
            'project_name' => $site->name,
            'logo' => $site->logo_path,
        ])
    </div>
@endif


        @if(in_array('unitStatisticsByStage', $requestSections) && isset($sections['unitStatisticsByStage']))
            <div class="page-section">@include('comprehensive.unitStatisticsByStage', [
                'unitStats' => $sections['unitStatisticsByStage'],
                'project_name' => $site->name,
                'logo'=>$site->logo_path,
            ])</div>
        @endif

        @if(in_array('visits_payments_contracts', $requestSections) && isset($sections['visits_payments_contracts']['data']))
        <div class="page-section">
            @include('comprehensive.visits_payments_contracts', [
                'data' => $sections['visits_payments_contracts'],
                'project_name' => $site->name ?? 'مشروع غير معرف',
                'logo'=>$site->logo_path,
            ])
        </div>
    @endif


    @if(in_array('disinterest_reasons', $requestSections) && isset($sections['disinterest_reasons']))
    <div class="page-section">
        @include('comprehensive.disinterest_reasons', [
            'data' => $sections['disinterest_reasons'],
            'project_name' => $site->name,
            'logo'=>$site->logo_path,
        ])
    </div>
@endif


        @if(in_array('unit_sales', $requestSections) && isset($sections['unit_sales']))
            <div class="page-section">@include('comprehensive.unit_sales', [
                'data' => $sections['unit_sales'],
                'project_name' => $site->name,
                'logo'=>$site->logo_path,
            ])
            </div>
        @endif

        @if(in_array('source_stats', $requestSections) && isset($sections['source_stats']))
            <div class="page-section">@include('comprehensive.source_stats', [
                'data' => $sections['source_stats'],
                'project_name' => $site->name,
                'logo'=>$site->logo_path,
            ])</div>
        @endif

        @if(in_array('monthly_appointments', $requestSections) && isset($sections['monthly_appointments']))
            <div class="page-section">@include('comprehensive.monthly_appointments', [
                'data' => $sections['monthly_appointments'],
                'project_name' => $site->name,
            ])</div>
        @endif

        @if(in_array('targeted_report', $requestSections) && isset($sections['targeted_report']))
            <div class="page-section">@include('comprehensive.targeted_report', [
                'data' =>  $sections['targeted_report'],
                'project_name' => $site->name,
                'logo'=>$site->logo_path,
            ])</div>
        @endif
    </div>
@endforeach




        <div class="page-section">
            <div class="logo-section">
                <div class="main-logo">
                    <img src="{{ asset('build/logo5.png') }}" alt="Tatwir Logo">
                </div>
                <div style="margin-top: 20px; font-size: 24px; color: #ccc;">
                    {{ __('components.thanks') }}
                </div>
                <div class="projects-logos">
                    <div class="project-logo">
                        <img src="{{ asset('images/logo4.png') }}" alt="Azyan Al Dhahran">
                    </div>
                    <div class="project-logo">
                        <img src="{{ asset('images/logo3.png') }}" alt="Azyan Al Bashaer">
                    </div>
                    @foreach($localSites as $site)
                    @if($site->logo_path_white)
                        <div class="project-logo">
                            <img src="{{ asset('storage/'.$site->logo_path_white) }}" alt="{{ $site->name }}" style="height: 50px;">
                        </div>
                    @endif
                @endforeach
                </div>
            </div>
        </div>


    </div>
    <div id="pdf-loading-overlay" style="display: none;">
        <div class="loading-backdrop"></div>
        <div class="loading-spinner">
            <div class="spinner-circle"></div>
            <div class="loading-text" style="margin-top: 20px; color: #333; text-align: center; font-size: 18px;">
                {{ __('messages.generating_report') }}
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
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('l', 'mm', 'a4'); // Landscape A4

        const exportButton = document.getElementById('pdf-export-button');
        exportButton.style.display = 'none';
        document.getElementById('pdf-loading-overlay').style.display = 'block';

        // استثناء الأقسام التي تحتوي على no-print
        const pageSections = document.querySelectorAll('.page-section:not(.no-print)');

        for (let i = 0; i < pageSections.length; i++) {
            const section = pageSections[i];

            // تأكد من أن القسم ظاهر تمامًا للتصدير
            section.style.visibility = 'visible';
            section.style.position = 'relative';
            section.style.left = '0';
            section.style.top = '0';
            section.style.width = '100%';

            // ↓ قلّل scale لتقليل الحجم
            const canvas = await html2canvas(section, {
                scale: 1.2,
                useCORS: true,
                allowTaint: true,
                backgroundColor: "#ffffff"
            });

            const imgData = canvas.toDataURL('image/jpeg', 0.8); // JPEG بدقة 80%

            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();

            const ratio = Math.min(pageWidth / canvas.width, pageHeight / canvas.height);
            const imgWidth = canvas.width * ratio;
            const imgHeight = canvas.height * ratio;

            if (i !== 0) pdf.addPage();

            pdf.addImage(imgData, 'JPEG', 0, 0, imgWidth, imgHeight);

            // استرجاع التنسيقات
            section.style.visibility = '';
            section.style.position = '';
            section.style.left = '';
            section.style.top = '';
            section.style.width = '';
        }

        pdf.save("Tatwir_Report.pdf");

        exportButton.style.display = 'block';
        document.getElementById('pdf-loading-overlay').style.display = 'none';
    }
</script>


@endsection
