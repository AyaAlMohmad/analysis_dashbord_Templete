@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h4>{{ __('contract_report.title') }}</h4>

        <div class="modal-content">
            <form method="POST" action="{{ route('admin.reports.contracts.result') }}">
                @csrf

                <div class="form-group">
                    <select name="site" id="site" class="form-control" required>
                        <option value="">{{ __('contract_report.select_site') }}</option>
                        <option value="dhahran">{{ __('contract_report.dhahran') }}</option>
                        <option value="bashaer">{{ __('contract_report.bashaer') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="from_date">{{ __('contract_report.from_date') }}</label>
                    <input type="date" name="from_date" class="form-control">
                </div>

                <div class="form-group">
                    <label for="to_date">{{ __('contract_report.to_date') }}</label>
                    <input type="date" name="to_date" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary mt-3">{{ __('contract_report.submit') }}</button>
            </form>
        </div>
    </div>
@endsection
