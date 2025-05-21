<div
    style="background-color: #f9f6f2; padding: 60px 20px; font-family: 'Arial', sans-serif; direction: rtl; position: relative;">

    <!-- Logo Top -->
    <div style="position: absolute; top: 30px; right: 30px;">
        <img src="{{ asset('build/logo.png') }}" alt="Tatwir Logo" style="height: 50px;">
    </div>

    <!-- Title -->
    <div style="text-align: center; margin-bottom: 40px;">
        <h2 style="font-size: 28px; color: #8b5a3b; border-bottom: 2px solid #8b5a3b; display: inline-block;">
            {{ __('messages.total_sales') }}
        </h2>
    </div>

    <!-- Content Row -->
    <div style="display: flex; flex-direction: row-reverse; align-items: flex-start;">
        <!-- Side Decoration -->
        <div style="flex: 0 0 auto; min-width: 100px; position: absolute; top: 100px;">
            <img src="{{ asset('images/style2.png') }}" alt="Decoration" style="height: 500px;">
        </div>

        <!-- Main Tables Container -->
        <div
            style="max-width: 95%; margin: 40px auto; font-family: 'Arial', sans-serif; font-size: 13px; text-align: center;">
            @php
                $models = $data['data']['models'] ?? [];
                $reserved = $data['data']['reserved_totals'] ?? [];
                $contracted = $data['data']['contracted_totals'] ?? [];
                $minArea = $data['data']['min_area'] ?? 0;
                $maxArea = $data['data']['max_area'] ?? 0;

                $areaStep = count($models) > 1 ? ($maxArea - $minArea) / (count($models) - 1) : 0;
                $areas = [];
                foreach ($models as $index => $model) {
                    $areas[$model] = round($minArea + $index * $areaStep, 2);
                }
            @endphp

            <table style="width: 100%; border-collapse: collapse; margin-top: 20px; background-color: white;">
                <thead>
                    <tr style="background-color: #ffe082; font-weight: bold;">
                        <th colspan="{{ count($models) + 1 }}" style="padding: 10px; border: 1px solid #ccc;">
                            {{ __('components.project_sales_title') }}
                        </th>
                    </tr>
                    <tr style="background-color: #8b5a3b; color: white;">
                        <th style="border: 1px solid #ccc;">{{ __('components.model_name') }}</th>
                        @foreach ($models as $model)
                            <th style="border: 1px solid #ccc;">
                                {{ __('components.model_names.' . $model) ?? $model }}<br>{{ $model }}
                            </th>
                        @endforeach
                    </tr>
                    <tr style="background-color: #a97f61; color: white;">
                        <th style="border: 1px solid #ccc;">{{ __('components.build_area') }}</th>
                        @foreach ($models as $model)
                            <td style="border: 1px solid #ccc;">{{ $minArea ?? '-' }} - {{ $maxArea ?? '-' }}</td>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr style="background-color: #f4f4f4;">
                        <th style="border: 1px solid #ccc;">{{ __('components.reserved') }}</th>
                        @foreach ($models as $model)
                            <td style="border: 1px solid #ccc;">{{ number_format($reserved[$model] ?? 0) }}</td>
                        @endforeach
                    </tr>
                    <tr style="background-color: #f9f9f9;">
                        <th style="border: 1px solid #ccc;">{{ __('components.contracted') }}</th>
                        @foreach ($models as $model)
                            <td style="border: 1px solid #ccc;">{{ number_format($contracted[$model] ?? 0) }}</td>
                        @endforeach
                    </tr>
                    <tr style="background-color: #eee; font-weight: bold;">
                        <th style="border: 1px solid #ccc;">{{ __('components.total_per_model') }}</th>
                        @foreach ($models as $model)
                            @php
                                $total = ($reserved[$model] ?? 0) + ($contracted[$model] ?? 0);
                            @endphp
                            <td style="border: 1px solid #ccc;">{{ number_format($total) }}</td>
                        @endforeach
                    </tr>
                </tbody>
                <tfoot>
                    <tr style="background-color: #ffe082; font-weight: bold;">
                        <th>{{ __('components.overall_total') }}</th>
                        @php
                            $grandTotal = 0;
                            foreach ($models as $model) {
                                $grandTotal += ($reserved[$model] ?? 0) + ($contracted[$model] ?? 0);
                            }
                        @endphp
                        <td colspan="{{ count($models) }}"
                            style="text-align: center; padding: 10px; border: 1px solid #ccc;">
                            <span style="color: #000;">{{ number_format($grandTotal) }}</span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>

    <!-- Logo Bottom -->
    <div style="position: absolute; right: 30px; bottom: 30px;">
        @if (isset($project_name) && $project_name == 'أزيان الظهران')
            <img src="{{ asset('images/logo5.png') }}" alt="Azyan Logo Dhahran" style="height: 50px;">
        @elseif(isset($project_name) && $project_name == 'أزيان البشائر')
            <img src="{{ asset('images/logo6.png') }}" alt="Azyan Logo Albashaer" style="height: 50px;">
        @else
            <img src="{{ asset('images/default-logo.png') }}" alt="Default Logo" style="height: 50px;">
        @endif
    </div>
</div>
