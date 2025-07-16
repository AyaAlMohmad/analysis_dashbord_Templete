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
                @if($project_name == 'أزيان البشائر' || $project_name == 'أزيان الظهران')
                    @include('comprehensive.colored_map_result_js', ['data' => $data, 'project_name' => $project_name])

                @else
                    <img src="{{ asset('storage/' .$map) }}" alt="خريطة " style="width: 100%;">
                @endif
            </div>


        </div>

        <div style="position: absolute; right: 30px; bottom: 30px;">
            @if (isset($project_name) && $project_name == 'أزيان الظهران')
                <img src="{{ asset('images/logo5.png') }}" alt="Azyan Logo Dhahran" style="height: 50px;">
            @elseif(isset($project_name) && $project_name == 'أزيان البشائر')
                <img src="{{ asset('images/logo6.png') }}" alt="Azyan Logo Albashaer" style="height: 50px;">
                @elseif (!empty($logo) && file_exists(public_path('storage/' . $logo)))
                <img src="{{ asset('storage/' . $logo) }}" alt="Site Logo" style="height: 50px;">
            @else
                <span style="font-size: 14px; color: #8b5a3b; font-weight: bold;">{{ $project_name }}</span>
            @endif

        </div>
    </div>
