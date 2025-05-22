@extends('layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="container mt-4">
        <h4>{{ __('reports.sales_report') }}</h4>

        {{-- Select Site --}}
        <div class="form-group mb-4">
            <select name="site" id="site" class="form-control">
                <option value="">{{ __('reports.select_site') }}</option>
                <option value="dhahran" {{ $site === 'dhahran' ? 'selected' : '' }}>{{ __('reports.site_dhahran') }}</option>
                <option value="bashaer" {{ $site === 'bashaer' ? 'selected' : '' }}>{{ __('reports.site_bashaer') }}</option>
            </select>
        </div>

        {{-- Error Message --}}
        @if ($error)
            <div class="alert alert-danger">{{ $error }}</div>
        @endif
        {{-- Hidden content until site is selected --}}
        {{-- Hidden content until site is selected --}}
        @if (!empty($site))
            <div id="reportSection">
                {{-- Chart: Sources --}}
                @if ($data && isset($data['report_data']))
                    <div class="card p-3 bg-light mb-5">
                        <h5> {{ __('reports.sources_chart', ['title' => $data['title']]) }}</h5>
                        <canvas id="sourcesChart" height="300" style="background-color: white;"></canvas>
                    </div>
                @endif

                {{-- Chart: Status --}}
                @if ($data && isset($data['status_data']))
                    <div class="card p-3 bg-light mb-5">
                        <h5>{{ __('reports.status_chart', ['title' => $data['title']]) }}</h5>
                        <canvas id="statusChart" height="300" style="background-color: white;"></canvas>
                    </div>
                @endif

                {{-- Filter Form --}}
                <div class="card p-4 shadow-sm bg-light mb-5">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-end">
                            <h5 class="mb-3 text-secondary">{{ __('reports.sales_report') }}</h5>
                            <p>{{ __('reports.select_date_range') }}</p>
                        </div>
                        <div class="col-md-6">
                            <form method="post" action="{{ route('admin.sales.report.result') }}">
                                @csrf
                                <input type="hidden" name="site" value="{{ $site }}">
                            
                                <div class="mb-2">
                                    <label class="form-label d-block text-start">{{ __('reports.from') }}</label>
                                    <input type="date" name="from_date" class="form-control" required>
                                </div>
                            
                                <div class="mb-3">
                                    <label class="form-label d-block text-start">{{ __('reports.to') }}</label>
                                    <input type="date" name="to_date" class="form-control" required>
                                </div>
                            
                                <button type="submit" class="btn btn-primary">{{ __('reports.generate') }} </button>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>

    <script>
        document.getElementById('site').addEventListener('change', function() {
            const selectedSite = this.value;
            if (selectedSite) {
                window.location.href = `?site=${selectedSite}`;
            }
        });


        document.addEventListener('DOMContentLoaded', function() {
            const currentSite = "{{ $site }}";
            if (currentSite !== "") {
                document.getElementById('reportSection').style.display = 'block';
            }
        });

        // Handle dropdown change
        document.getElementById('site').addEventListener('change', function() {
            const selectedSite = this.value;
            if (selectedSite) {
                window.location.href = `?site=${selectedSite}`;
            }
        });

        // Chart: Sources
        @if ($data && isset($data['report_data']))
            const sourcesCtx = document.getElementById('sourcesChart').getContext('2d');
            new Chart(sourcesCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($data['report_data']['labels']) !!},
                    datasets: [{
                        label: 'Sources',
                        data: {!! json_encode(array_map('intval', $data['report_data']['data'])) !!},
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        @endif

        // Chart: Status
        @if ($data && isset($data['status_data']))
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($data['status_data']['labels']) !!},
                    datasets: [{
                        label: 'Status',
                        data: {!! json_encode(array_map('intval', $data['status_data']['data'])) !!},
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        @endif
    </script>
@endsection
