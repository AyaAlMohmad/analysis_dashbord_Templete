@extends('layouts.app')
@section('content')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background: #f0f0f0;
            padding: 20px;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            background: white;
            margin: 0 auto 20px auto;
            padding: 20mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: relative;
        }

        /* الصفحة الأولى */
        .first-page {
            text-align: center;
            padding-top: 50mm;
        }

        .main-title {
            font-size: 32px;
            color: #1a237e;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .sub-title {
            font-size: 24px;
            color: #d4af37;
            font-weight: bold;
            margin-bottom: 40px;
        }

        .locations {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            max-width: 500px;
            margin: 0 auto;
        }

        .location {
            background: #f8f8f8;
            border: 2px solid #1a237e;
            border-radius: 8px;
            padding: 15px 10px;
            text-align: center;
        }

        .location-ar {
            font-size: 16px;
            color: #1a237e;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .location-en {
            font-size: 12px;
            color: #666;
        }

        /* صفحات المشاريع */
        .project-title {
            background: #1a237e;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 25px;
            border-radius: 5px;
        }

        .manager-box {
            background: #e3f2fd;
            border: 2px solid #1a237e;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .manager-title {
            font-weight: bold;
            color: #1a237e;
            margin-bottom: 5px;
            font-size: 16px;
        }

        .manager-name {
            color: #333;
            font-size: 16px;
        }

        .service-title {
            background: #1a237e;
            color: white;
            padding: 10px 15px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            border-radius: 5px;
        }

        .employees-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin: 15px 0;
        }

        .employee-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }

        .positions-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }

        .position-box {
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: hidden;
        }

        .position-header {
            background: #1a237e;
            color: white;
            padding: 10px 15px;
            font-weight: bold;
            text-align: center;
        }

        .position-employees {
            padding: 15px;
            background: white;
        }

        .employee-item {
            padding: 8px 5px;
            border-bottom: 1px solid #eee;
        }

        .employee-item:last-child {
            border-bottom: none;
        }

        .vacant {
            color: #999;
            font-style: italic;
        }

        .not-started {
            color: #e53935;
            font-weight: bold;
        }

        .departments {
            background: #e8f5e8;
            border: 2px solid #388e3c;
            border-radius: 8px;
            padding: 20px;
            margin-top: 25px;
        }

        .departments-list {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .department {
            background: white;
            padding: 8px 15px;
            border: 1px solid #388e3c;
            border-radius: 20px;
            font-weight: bold;
            color: #2e7d32;
        }

        .count-box {
            background: #ff9800;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
        }

        .count-container {
            text-align: center;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .page {
                margin: 0;
                box-shadow: none;
                page-break-after: always;
            }
        }
    </style>
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
                    <div class="project-logo">
                        <img src="{{ asset('images/JeddahLogo.png') }}" alt="Azyan Al Bashaer">
                    </div>

                </div>
            </div>
        </div>
 <div class="page-section">
                <div class="khozam-section"
                    style="background-color: #f9f6f2; padding: 60px 20px; direction: rtl; position: relative; display: flex; align-items: center; justify-content: space-between; font-family: 'Arial', sans-serif; flex-wrap: wrap;">
                    <div style="position: absolute; top: 90px; right: 30px;">
                        <img src="{{ asset('build/logo.png') }}" alt="Tatwir Logo" style="height: 60px;">
                    </div>
                     <div style="position: absolute; top: 30px; right: 30px;">
                        <img src="{{ asset('images/kayan.png') }}" alt="Tatwir Logo" style="height: 60px;">
                    </div>
                    <div style="flex: 2; text-align: center; padding: 20px; max-width: 100%;">
                        <div style="margin-bottom: 30px;">
                            <img src="{{ asset('images/logo5.png') }}" alt="Project Logo" style="height:200px;">
                        </div>
                        <div style="margin-top: 20px; font-size: 18px; color: #8b5a3b;">

                                Please select a date range from the form

                        </div>


                    </div>
                    <div style="flex: 1; min-width: 200px; display: flex; justify-content: flex-start;">
                        <img src="{{ asset('images/stayle.png') }}" alt="Decoration" style="height: 400px;">
                    </div>
                </div>
            </div>
    <!-- الصفحة الأولى -->
    <div class="page first-page">
        <div class="main-title">التطوير النفاطل</div>
        <div class="sub-title">التطوير العقاري</div>

        <div class="locations">
            <div class="location">
                <div class="location-ar">أبيان خزام</div>
                <div class="location-en">AZYAN HADHARAH</div>
            </div>
            <div class="location">
                <div class="location-ar">أبيان البدة</div>
                <div class="location-en">AZYAN AL DASHAFER</div>
            </div>
            <div class="location">
                <div class="location-ar">ت اللّ الشميس</div>
                <div class="location-en"></div>
            </div>
            <div class="location">
                <div class="location-ar">أبيان القيمي</div>
                <div class="location-en">AZYAN AL DHAHRAN</div>
            </div>
            <div class="location">
                <div class="location-ar">أبيان المثاني</div>
                <div class="location-en">AZYAN AL DASHAFER</div>
            </div>
        </div>
    </div>

    <!-- صفحات المشاريع -->
    @foreach($projects as $project)
    <div class="page">
        <div class="project-title">{{ $project['name'] }}</div>

        @if(isset($project['manager']))
        <div class="manager-box">
            <div class="manager-title">
                @if(isset($project['manager_title']))
                    {{ $project['manager_title'] }}
                @else
                    المدير التنفيذي للمبيعات
                @endif
            </div>
            <div class="manager-name">{{ $project['manager'] }}</div>
        </div>
        @endif

        @if(isset($project['service_title']))
        <div class="service-title">{{ $project['service_title'] }}</div>
        <div class="employees-grid">
            @foreach($project['employees'] as $employee)
            <div class="employee-card">{{ $employee }}</div>
            @endforeach
        </div>
        @endif

        @if(isset($project['positions']))
        <div class="positions-container">
            @foreach($project['positions'] as $position => $employees)
            <div class="position-box">
                <div class="position-header">{{ $position }}</div>
                <div class="position-employees">
                    @foreach($employees as $employee)
                    <div class="employee-item
                        @if(strpos($employee, 'شاغر') !== false) vacant
                        @elseif(strpos($employee, 'لم تتم المباشرة') !== false) not-started
                        @endif">
                        {{ $employee }}
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <div class="departments">
            <div class="departments-list">
                @foreach($project['departments'] as $department)
                <div class="department">{{ $department }}</div>
                @endforeach
            </div>
            <div class="count-container">
                <div class="count-box">
                    @if(isset($project['current_employees']))
                        عدد الموظفين : {{ $project['total_employees'] }} الحاليين
                    @else
                        عدد الموظفين : {{ $project['total_employees'] }}
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endsection
