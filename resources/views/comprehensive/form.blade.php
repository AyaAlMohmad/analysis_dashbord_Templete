@extends('layouts.app')

@section('content')
<div class="container py-4">
    <form method="POST" action="{{ route('admin.comprehensive.index') }}">
        @csrf
        
        {{-- Filter Section --}}
        <div class="card border border-white shadow mb-4">
            <div class="card-body">
                <h4 class="text-center mb-4">Filter by Date</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="from_date" class="form-label">From Date</label>
                        <input type="date" name="from_date" id="from_date" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="to_date" class="form-label">To Date</label>
                        <input type="date" name="to_date" id="to_date" class="form-control" required>
                    </div>
                </div>
            </div>
        </div>

        {{-- Projects Section --}}
        <div class="card border border-white shadow mb-4">
            <div class="card-body">
                <h5 class="text-center mb-3">Projects List</h5>
                
                <div id="projectsContainer">
            
                </div>

                <div class="d-grid mt-3">
                    <button type="button" class="btn btn-success" onclick="addProject()">+ Add Another Project</button>
                </div>
            </div>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">Save All Projects</button>
        </div>
    </form>
</div>

<script>
    let projectId = 0;

    function addProject() {
        projectId++;
        
        const projectDiv = document.createElement('div');
        projectDiv.className = 'project-item card mb-3';
        projectDiv.id = `project-${projectId}`;
        projectDiv.innerHTML = `
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Project #${projectId}</h6>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeProject(${projectId})">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Project Name</label>
                    <input type="text" class="form-control" name="projects[${projectId}][project_name]" >
                </div>
                <div class="mb-3">
                    <label class="form-label">Developer Name</label>
                    <input type="text" class="form-control" name="projects[${projectId}][developer_name]" >
                </div>
                <div class="mb-3">
                    <label class="form-label">Total Units</label>
                    <input type="number" class="form-control" name="projects[${projectId}][total_units]" >
                </div>
                <div class="mb-3">
                    <label class="form-label">Total Reserved</label>
                    <input type="number" class="form-control" name="projects[${projectId}][reserved_units]" >
                </div>
                 <div class="mb-3">
                    <label class="form-label">Total contracted</label>
                    <input type="number" class="form-control" name="projects[${projectId}][contracts_units]" >
                </div>
            </div>
        `;
        
        document.getElementById('projectsContainer').appendChild(projectDiv);
    }

    function removeProject(id) {
        const projectToRemove = document.getElementById(`project-${id}`);
        if (projectToRemove) {
            projectToRemove.remove();
        }
    }

    // إضافة مشروع تلقائياً عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        addProject();
    });
</script>
@endsection