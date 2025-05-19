@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h4>Source Report</h4>





            <div class="modal-content">
                
                <form method="POST" action="{{ route('admin.reports.source.result') }}">
                    @csrf
                    <div class="form-group">
                        <select name="site" id="site" class="form-control">
                            <option value="">-- Select Site --</option>
                            <option value="dhahran">Dhahran</option>
                            <option value="bashaer">Bashaer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="from_date">From Date:</label>
                        <input type="date" name="from_date" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="to_date">To Date:</label>
                        <input type="date" name="to_date" class="form-control">
                    </div>




                    <button type="submit" class="btn btn-primary mt-3">Report</button>

                </form>
            </div>
       


    </div>
@endsection
