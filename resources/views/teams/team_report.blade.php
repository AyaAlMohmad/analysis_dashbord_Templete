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
        <h4>Team Report</h4>



        <select name="site" id="site" class="form-control">
            <option value="">-- Select Site --</option>
            <option value="dhahran">Dhahran</option>
            <option value="bashaer">Bashaer</option>
        </select>

        <div id="overlay" class="overlay d-none">
            <div class="modal-content">
                <button type="button" class="btn btn-secondary mb-3" id="changeSiteBtn">Change Site</button>

                <form method="POST" action="{{ route('admin.reports.teamReport.result') }}">
                    @csrf
                    <input type="hidden" name="site" id="siteInput">

                    <div class="form-group">
                        <label for="from_date">From Date:</label>
                        <input type="date" name="from_date" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="to_date">To Date:</label>
                        <input type="date" name="to_date" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="role">Select Role:</label>
                        <select name="role" id="role" class="form-control"></select>
                    </div>

                    <div class="form-group">
                        <label for="employee">Select Employee:</label>
                        <select name="employee" id="employee" class="form-control"></select>
                    </div>
                  
                        <button type="submit" class="btn btn-primary mt-3">Report</button>
                    
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

                fetch("{{ route('admin.reports.teamReport') }}?site=" + site, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('overlay').classList.remove('d-none');

                        const roleSelect = document.getElementById('role');
                        roleSelect.innerHTML = '<option value="">-- Select Role --</option>';
                        data.roles.forEach(role => {
                            const option = document.createElement('option');
                            option.value = role.roleid;
                            option.textContent = role.name;
                            roleSelect.appendChild(option);
                        });

                        const employeeSelect = document.getElementById('employee');
                        employeeSelect.innerHTML = '<option value="">-- Select Employee --</option>';
                        data.employees.forEach(emp => {
                            const option = document.createElement('option');
                            option.value = emp.staffid;
                            option.textContent = emp.firstname + ' ' + emp.lastname;
                            employeeSelect.appendChild(option);
                        });

                        document.getElementById('siteInput').value = site;
                    });
            });
        </script>
    </div>
@endsection
