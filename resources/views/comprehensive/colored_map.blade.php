<div class="map-section"
        style="background-color: #f9f6f2; padding: 60px 20px; font-family: 'Arial', sans-serif; position: relative; display: flex; flex-direction: row; flex-wrap: wrap; justify-content: space-between; align-items: flex-start;">

        <div style="flex: 0 0 auto; min-width: 100px;">
            <img src="{{ asset('images/style2.png') }}" alt="Decoration" style="height: 500px;">
        </div>

        <div style="flex: 1 1 70%; text-align: center; padding: 0 20px;">
            <h2
                style="font-size: 28px; color: #8b5a3b; border-bottom: 2px solid #8b5a3b; display: inline-block; margin-bottom: 30px;">
               {{__('messages.color_map')}}
            </h2>

            <div style="width: 100%; max-width: 400px; margin: 0 auto;" id="colored-map-{{ Str::slug($project_name, '_') }}">
                @if($project_name == 'أزيان البشائر')
                    @include('comprehensive.colored_map_result_js', ['data' => $data, 'project_name' => $project_name])
                @elseif($project_name == 'أزيان الظهران')
                    <img src="{{ asset('images/dhahran.png') }}" alt="خريطة الظهران" style="width: 100%;">
                @endif
            </div>
            
            
        </div>

        <div style="position: absolute; right: 30px; bottom: 30px;">
            @if(isset($project_name) && $project_name == 'أزيان الظهران')
                <img src="{{ asset('images/logo5.png') }}" alt="Azyan Logo Dhahran" style="height: 50px;">
            @elseif(isset($project_name) && $project_name == 'أزيان البشائر')
                <img src="{{ asset('images/logo6.png') }}" alt="Azyan Logo Albashaer" style="height: 50px;">
            @else
                <img src="{{ asset('images/default-logo.png') }}" alt="Default Logo" style="height: 50px;">
            @endif
        </div>

        <div style="position: absolute; top: 30px; right: 30px;">
            <img src="{{ asset('build/logo.png') }}" alt="Tatwir Logo" style="height: 50px;">
        </div>
    </div>