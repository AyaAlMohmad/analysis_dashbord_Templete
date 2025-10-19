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


        <div class="project-section" style="margin-bottom: 50px;">
            <div class="page-section">@include('comprehensive.visits_payments_contracts', [
                'data' => $dhahranVPCData,
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


        <div class="project-section" style="margin-bottom: 50px;">
            <div class="page-section">@include('comprehensive.visits_payments_contracts', [
                'data' => $albashaerVPCData,
                'project_name' => 'أزيان البشائر',
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
                            <img src="{{ asset('images/jadah.png') }}" alt="Project Logo" style="height:200px;">
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


        <div class="project-section" style="margin-bottom: 50px;">
            <div class="page-section">@include('comprehensive.visits_payments_contracts', [
                'data' => $jeddahVPCData,
                'project_name' => 'أزيان جدة',
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
                            <img src="{{ asset('images/alfursan.png') }}" alt="Project Logo" style="height:200px;">
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


        <div class="project-section" style="margin-bottom: 50px;">
            <div class="page-section">@include('comprehensive.visits_payments_contracts', [
                'data' => $alfursanVPCData,
                'project_name' => 'أزيان الفرسان',
            ])</div>
        </div>






    </div>



@endsection
