@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Customer Report</h2>
    <form action="{{ route('admin.customers.report.result') }}" method="post">
        @csrf
        <div class="mb-3">
            <label for="site">Site</label>
            <select name="site" id="site" class="form-control" required>
                <option value="">-- Select Site --</option>
                <option value="aldhahran">Dhahran</option>
                <option value="albashaer">Bashaer</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="from_date">From Date</label>
            <input type="date" name="from_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="to_date">To Date</label>
            <input type="date" name="to_date" class="form-control" required>
        </div>
        <button class="btn btn-primary">View Report</button>
    </form>
</div>
@endsection
