@extends('layouts.app')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-4 bg-white shadow-sm rounded-lg">
                    <!-- Main Title -->
                    <div class="p-4 bg-white shadow-sm rounded-lg">
                        <h1 class="text-2xl font-bold text-gray-800 text-center">Leads Status Report</h1>
                    </div>

                    <!-- Site Switcher -->
                    <div class="text-center mb-10">
                        <label for="siteSelect" class="block text-xl font-bold text-gray-800 mb-3">
                            Select a Site:
                        </label>

                        <div class="inline-block relative w-64">
                            <select id="siteSelect"
                                class="block appearance-none w-full bg-white border border-gray-300 text-gray-700 py-3 px-4 pr-10 rounded leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                <option value="">-- Choose a site --</option>
                                <option value="dhahran">Azyan Dhahran</option>
                                <option value="bashaer">Azyan Bashaer</option>
                            </select>

                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500"></div>
                            
                            <!-- Export Buttons -->
                            <div class="flex items-center gap-4 mt-4">
                                <button type="button" onclick="submitExport('pdf')"
                                    class="p-3 rounded-xl hover:bg-red-100 transition text-red-500 text-2xl"
                                    title="Export PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </button>

                                <button type="button" onclick="submitExport('zip')"
                                    class="p-3 rounded-xl hover:bg-blue-100 transition text-blue-500 text-2xl"
                                    title="Export ZIP">
                                    <i class="fas fa-file-archive"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Site Sections -->
                    @foreach (['dhahran' => 'Azyan Dhahran', 'bashaer' => 'Azyan Bashaer'] as $key => $label)
                        <div class="site-section" id="site-{{ $key }}" style="display: none;">
                            <!-- Export Header (Hidden) -->
                            <div class="export-header hidden" id="export-header-{{ $key }}">
                                <h1 class="text-xl font-bold mb-1">Leads Status Report</h1>
                                <h2 class="text-lg text-gray-600">Location: {{ $label }}</h2><br>
                            </div>

                            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg my-8">
                                <div class="p-4 bg-white shadow-sm rounded-lg">
                                    <h2 class="text-2xl font-bold text-gray-800 text-center">{{ $label }} - Status Overview</h2>
                                </div>

                                @if ($errors[$key])
                                    <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                        {{ $errors[$key] }}
                                    </div>
                                @else
                                    <!-- Total Leads Card -->
                                    <div class="p-6">
                                        <div class="bg-indigo-50 p-6 rounded-lg shadow-sm max-w-xs mx-auto">
                                            <h3 class="text-lg font-semibold text-indigo-800 mb-2">Total Leads</h3>
                                            <div class="text-3xl font-bold text-indigo-600">
                                                {{ number_format($totals[$key]) }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Chart Section -->
                                    <div class="w-full max-w-4xl mx-auto mt-8 p-6">
                                        <div class="bg-white p-6 rounded-lg shadow-sm relative">
                                            <div wire:ignore class="relative h-[400px]">
                                                <div x-data="{
                                                    chartLabels: @js(array_keys($key === 'dhahran' ? $dataDhahran : $dataBashaer)),
                                                    chartData: @js(array_values($key === 'dhahran' ? $dataDhahran : $dataBashaer)),
                                                    initChart() {
                                                        const ctx = this.$refs.chart;
                                                        new Chart(ctx, {
                                                            type: 'bar',
                                                            data: {
                                                                labels: this.chartLabels,
                                                                datasets: [{
                                                                    label: 'Number of Leads',
                                                                    data: this.chartData,
                                                                    backgroundColor: '#3B82F6',
                                                                    borderColor: '#3B82F6',
                                                                    borderWidth: 1
                                                                }]
                                                            },
                                                            options: {
                                                                responsive: true,
                                                                maintainAspectRatio: false,
                                                                scales: {
                                                                    y: {
                                                                        beginAtZero: true,
                                                                        ticks: { stepSize: 1 }
                                                                    }
                                                                },
                                                                plugins: {
                                                                    legend: {
                                                                        labels: {
                                                                            font: {
                                                                                size: 14,
                                                                                family: 'Tajawal'
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        });
                                                    }
                                                }" x-init="initChart">
                                                    <canvas id="chart-{{ $key }}" x-ref="chart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Details Section -->
                                    <div class="p-6">
                                        <div x-data="{ showDetails: false }" class="bg-white rounded-lg shadow-sm">
                                            <div class="p-4 border-b">
                                                <button @click="showDetails = !showDetails"
                                                    class="text-blue-500 hover:text-blue-700 flex items-center">
                                                    <span x-text="showDetails ? 'Hide Details' : 'Show Full Details'"></span>
                                                    <svg class="w-4 h-4 ml-2 transform transition-transform"
                                                        :class="{ 'rotate-180': showDetails }" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div x-show="showDetails" id="list-{{ $key }}" class="p-4 space-y-3">
                                                @foreach ($key === 'dhahran' ? $dataDhahran : $dataBashaer as $status => $count)
                                                    <div
                                                        class="flex justify-between items-center p-3 bg-gray-50 rounded hover:bg-gray-100">
                                                        <span class="text-gray-600">{{ $status }}</span>
                                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">{{ $count }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
@endsection
