@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                {{-- 🔷 العنوان في المنتصف، عريض وكبير --}}
                <div class="card-header text-center">
                    <h5 class="font-weight-bold" style="font-size: 20px;">
                        {{ __('Select Date Range') }}
                    </h5>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ $actionUrl }}">
                        @csrf

                        <div class="form-group row">
                            <label for="from_date" class="col-md-4 col-form-label text-md-right">
                                {{ __('From Date') }}
                            </label>

                            <div class="col-md-6">
                                <input id="from_date" type="date" 
                                       class="form-control @error('from_date') is-invalid @enderror" 
                                       name="from_date" value="{{ old('from_date') }}"  
                                       autocomplete="from_date" autofocus>

                                @error('from_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="to_date" class="col-md-4 col-form-label text-md-right">
                                {{ __('To Date') }}
                            </label>

                            <div class="col-md-6">
                                <input id="to_date" type="date" 
                                       class="form-control @error('to_date') is-invalid @enderror" 
                                       name="to_date" value="{{ old('to_date') }}"  
                                       autocomplete="to_date">

                                @error('to_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Generate Report') }}
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- 🔴 الملاحظة باللون الأحمر تحت الفورم --}}
                    <div class="mt-3 text-center">
                        <small style="color: red; font-weight: 500;">
                            {{ __('If no dates are selected, the report will be generated cumulatively.') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
