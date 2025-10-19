@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h4>{{ __('source_report.title') }}</h4>

        <div class="modal-content">
            <form method="POST" action="{{ route('admin.reports.source.result') }}">
                @csrf

                <div class="form-group">
                    <select name="site" id="site" class="form-control">
                        <option value="">{{ __('source_report.select_site') }}</option>
                        <option value="dhahran">{{ __('source_report.dhahran') }}</option>
                        <option value="bashaer">{{ __('source_report.bashaer') }}</option>
                        <option value="jeddah">{{ __('components.jeddah') }}</option>
                        <option value="alfursan">{{ __('components.alfursan') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="from_date">{{ __('source_report.from_date') }}</label>
                    <input type="date" name="from_date" class="form-control">
                </div>

                <div class="form-group">
                    <label for="to_date">{{ __('source_report.to_date') }}</label>
                    <input type="date" name="to_date" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary mt-3">
                    {{ __('source_report.submit') }}
                </button>
            </form>
        </div>
    </div>
@endsection
