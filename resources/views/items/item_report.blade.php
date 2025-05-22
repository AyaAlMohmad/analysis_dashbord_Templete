@extends('layouts.app')

@section('content')
    <style>
       .overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
    z-index: 1000;
    display: flex;
    justify-content: center;
    align-items: flex-start; /* بدل center */
    padding-top: 80px; /* إضافة مسافة من الأعلى */
}

.modal-content {
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    width: 500px;
}

    </style>

    <div class="container mt-4">
        <h4>{{__('reports.item_report')}}</h4>



        <select name="site" id="site" class="form-control">
            <option value="">{{__('reports.select_site')}}</option>
            <option value="dhahran">{{__('reports.dhahran')}}</option>
            <option value="bashaer">{{__('reports.bashaer')}}</option>
        </select>

        <div id="overlay" class="overlay d-none">
            <div class="modal-content">
                <button type="button" class="btn btn-secondary mb-3" id="changeSiteBtn">{{__('reports.change_site')}}</button>

                <form method="POST" action="{{ route('admin.reports.itemReport.result') }}">
                    @csrf
                    <input type="hidden" name="site" id="siteInput">

                

                    <div class="form-group">
                        <label for="group">{{__('reports.select_group')}}:</label>
                        <select name="group" id="group" class="form-control"></select>
                    </div>
                  
                        <button type="submit" class="btn btn-primary mt-3">{{ __('reports.generate') }}</button>
                    
                </form>
            </div>
        </div>

        <script>
            document.getElementById('changeSiteBtn').addEventListener('click', function() {
                document.getElementById('overlay').classList.add('d-none');
                document.getElementById('site').value = "";
            });

            document.getElementById('site').addEventListener('change', function() {
                const site = this.value;
                if (!site) return;

                fetch("{{ route('admin.reports.item') }}?site=" + site, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
.then(data => {
    document.getElementById('overlay').classList.remove('d-none');

    const groupSelect = document.getElementById('group');
    groupSelect.innerHTML = '<option value="">-- {{__('reports.select_group')}}--</option>';
    
    
    data.data.groups.forEach(group => {
        const option = document.createElement('option');
        option.value = group.id;
        option.textContent = group.name;
        groupSelect.appendChild(option);
    });

    document.getElementById('siteInput').value = site;
});
            });
        </script>
    </div>
@endsection
