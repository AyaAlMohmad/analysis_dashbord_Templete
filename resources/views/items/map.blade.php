@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                <div class="mb-6 text-center">
                    <h2 class="text-2xl font-bold mb-4">AZYAN {{ strtoupper($site) }}</h2>
                   
                @include('items.map-svg')
                </div>

            

                <script>
                    const siteData = @json($siteData);

                    const statusColors = {
                        available: '#28a745',
                        reserved: '#ffc107',
                        blocked: '#6c757d',
                        contracted: '#007bff',
                        unknown: '#d6d6d6'
                    };

                    function setPlotColor(plots, status) {
                        plots.forEach(item => {
                            let id = item.description || item.id; // تأكد من استخدام حقل صحيح
                            const el = document.getElementById('plot-' + id);
                            if (el) {
                                el.style.fill = statusColors[status];
                                el.setAttribute('title', `${item.description} - ${status}`);
                            }
                        });
                    }

                    setPlotColor(siteData.available, 'available');
                    setPlotColor(siteData.reserved, 'reserved');
                    setPlotColor(siteData.blocked, 'blocked');
                    setPlotColor(siteData.contracted, 'contracted');
                </script>

            </div>
        </div>
    </div>
@endsection
