@extends('layouts.app')
@section('content')
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h1 class="text-2xl font-bold mb-6 text-center">Appointment Logs Statistics - {{ ucfirst($site) }}</h1>

                {{-- Line Chart for Daily Counts --}}
                <canvas id="logChart" height="120" class="mb-12"></canvas>

            

                <div class="mt-6">
                    <a href="{{ route('admin.leads.logs', $site) }}"
                       >
                        Back to Logs
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('logChart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_keys($dailyCounts->toArray())) !!},
                    datasets: [{
                        label: 'Number of Logs',
                        data: {!! json_encode(array_values($dailyCounts->toArray())) !!},
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Daily Activity Log Trend',
                            font: {
                                size: 18
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Changes'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
