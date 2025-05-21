@extends('layouts.app')

@section('content')
<div class="container py-4">
    <form method="POST" action="{{ route('admin.comprehensive.index') }}">
        @csrf
        
        {{-- Filter Section --}}
        <div class="card border border-white shadow mb-4">
            <div class="card-body">
                <h4 class="text-center mb-4">{{__('components.filter_by_date')}}</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="from_date" class="form-label">{{__('components.from_data')}}</label>
                        <input type="date" name="from_date" id="from_date" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="to_date" class="form-label">{{__('components.to_data')}}</label>
                        <input type="date" name="to_date" id="to_date" class="form-control" required>
                    </div>
                </div>
            </div>
        </div>

        {{-- Projects Section --}}
        <div class="card border border-white shadow mb-4">
            <div class="card-body">
                <h5 class="text-center mb-3">{{ __('components.projects_list')}}</h5>
                
                <div id="projectsContainer">
            
                </div>

                <div class="d-grid mt-3">
                    <button type="button" class="btn btn-success" onclick="addProject()">+ {{__('components.add_project')}}</button>
                </div>
            </div>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">{{__('components.save_all_projects')}}</button>
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
                    <label class="form-label">{{__('components.project_name')}}</label>
                    <input type="text" class="form-control" name="projects[${projectId}][project_name]" >
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('components.developer')}}</label>
                    <input type="text" class="form-control" name="projects[${projectId}][developer_name]" >
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('components.units')}}</label>
                    <input type="number" class="form-control" name="projects[${projectId}][total_units]" >
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__('components.reserved')}}</label>
                    <input type="number" class="form-control" name="projects[${projectId}][reserved_units]" >
                </div>
                 <div class="mb-3">
                    <label class="form-label">{{__('components.contracts')}}</label>
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

    document.addEventListener('DOMContentLoaded', function() {
        addProject();
    });
</script>
@endsection