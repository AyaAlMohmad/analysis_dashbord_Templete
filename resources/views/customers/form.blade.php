@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Customer Report</h2>
    <form action="{{ route('admin.customers.report.result') }}" method="post">
        @csrf
        <div class="mb-3">
            <label for="site">Site</label>
            <select name="site" id="site" class="form-control" required>
                <option value="">{{__('reports.select_site')}}</option>
                <option value="aldhahran">{{__('reports.dhahran')}}</option>
                <option value="albashaer">{{__('reports.bashaer')}}</option>
            </select>
        </div>
        <div class="mb-3">
         
            <label for="from_date">{{__('reports.from_date')}}</label>
            <input type="date" name="from_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="to_date">{{__('reports.to_date')}}</label>
            <input type="date" name="to_date" class="form-control" required>
        </div>
        <button class="btn btn-primary">{{__('reports.generate_report')}}</button>
    </form>
</div>
@endsection
