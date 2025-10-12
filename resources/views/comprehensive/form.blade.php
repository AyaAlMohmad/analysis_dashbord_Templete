<!DOCTYPE html>
@extends('layouts.app')


@section('content')
    <style>
        #loadingOverlay {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9999;
            width: 100vw;
            height: 100vh;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
    </style>

    @php
        $sections = [
            // 'colored_map' => __('components.colored_map'),
            'reserved_report' => __('components.reserved_report'),
            'contracts_report' => __('components.contracts_report'),
            'status_item' => __('components.status_item'),
            'project_summary' => __('components.project_summary'),
            'unitStages' => __('components.unit_stages'),
            'unitStatisticsByStage' => __('components.unit_statistics_by_stage'),
            'visits_payments_contracts' => __('components.visits_payments_contracts'),
            // 'disinterest_reasons' => __('components.disinterest_reasons'),
            'unit_sales' => __('components.unit_sale'),
            'source_stats' => __('components.source_stats'),
            'monthly_appointments' => __('components.monthly_appointments'),
            'targeted_report' => __('components.targeted_report'),
        ];
    @endphp

    <meta name="csrf-token" content="{{ csrf_token() }}">


    <div class="container py-4">
        <form id="mainSiteForm" method="POST" action="{{ route('admin.comprehensive.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Filter Section --}}
            <div class="card border border-white shadow mb-4">
                <div class="card-body">
                    <h4 class="text-center mb-4">{{ __('components.filter_by_date') }}</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="from_date" class="form-label">{{ __('components.from_data') }}</label>
                            <input type="date" name="from_date" id="from_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="to_date" class="form-label">{{ __('components.to_data') }}</label>
                            <input type="date" name="to_date" id="to_date" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sites Selector --}}
            <div class="card border border-white shadow mb-4">
                <div class="card-body">
                    <h5 class="text-center mb-3">{{ __('components.select_sites') }}</h5>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="sites[]" value="dhahran" id="siteDhahran"
                            checked>
                        <label class="form-check-label" for="siteDhahran">{{ __('components.dhahran') }}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="sites[]" value="albashaer" id="siteAlbashaer"
                            checked>
                        <label class="form-check-label" for="siteAlbashaer">{{ __('components.albashaer') }}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="sites[]" value="jeddah" id="siteJeddah"
                            checked>
                        <label class="form-check-label" for="siteJeddah">{{ __('components.jeddah') }}</label>
                    </div>
                    <div id="filteredSitesContainer" class="mt-3"></div>

                    <button type="button" class="btn btn-success mt-3" onclick="handleAddSiteClick()">
                        <i class="fas fa-plus"></i> {{ __('components.add_site') }}
                    </button>

                </div>
            </div>

            {{-- Sections Selector --}}
            <div class="card border border-white shadow mb-4">
                <div class="card-body">
                    <h5 class="text-center mb-3">{{ __('components.select_sections') }}</h5>
                    @foreach ($sections as $key => $label)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="sections[]" value="{{ $key }}"
                                id="section_{{ $key }}" checked>
                            <label class="form-check-label" for="section_{{ $key }}">{{ $label }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Projects Section --}}
            <div class="card border border-white shadow mb-4">
                <div class="card-body">
                    <h5 class="text-center mb-3">{{ __('components.projects_list') }}</h5>
                    <div id="projectsContainer"></div>
                    <div class="d-grid mt-3">
                        <button type="button" class="btn btn-success" onclick="addProject()">
                            <i class="fas fa-plus"></i> {{ __('components.add_project') }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">{{ __('components.save_all_projects') }}</button>
            </div>
        </form>

        @include('comprehensive.modals.add_site')


    </div>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        window.csrfToken = '{{ csrf_token() }}';
    </script>
    <script>
        document.getElementById('addSiteForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) throw new Error('Failed to save site');
                    return response.json();
                })
                .then(data => {

                    $('#addSiteModal').modal('hide');

                    alert(data.message);

                    if (data.site) {
                        if (!window.projectData.sites) window.projectData.sites = [];

                        const index = window.projectData.sites.findIndex(s => s.id === data.site.id);
                        if (index !== -1) {
                            window.projectData.sites[index] = data.site;
                        } else {
                            window.projectData.sites.push(data.site);
                        }

                        renderSites();
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('Error saving site. Please try again.');
                });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let sourceCount = 0;

            // Add new source row
            document.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('add-source-btn')) {
                    sourceCount++;
                    const newRow = `
                    <tr>
                        <td><input type="text" class="form-control" name="source_stats[${sourceCount}][source]" ></td>
                        <td><input type="number" class="form-control customers-count" name="source_stats[${sourceCount}][customers]" ></td>
                        <td><input type="number" class="form-control visits-count" name="source_stats[${sourceCount}][visits]" ></td>
                        <td><input type="text" class="form-control visit-rate" name="source_stats[${sourceCount}][visit_rate]" readonly></td>
                        <td><input type="number" class="form-control registrations-count" name="source_stats[${sourceCount}][registrations]" ></td>
                        <td><input type="text" class="form-control registration-rate" name="source_stats[${sourceCount}][registration_rate]" readonly></td>
                        <td><input type="number" class="form-control contracted-units" name="source_stats[${sourceCount}][contracted_units]" ></td>
                        <td><input type="text" class="form-control contract-rate" name="source_stats[${sourceCount}][contract_rate]" readonly></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger remove-source-btn">
                                {{ __('components.remove_source') }}
                            </button>
                        </td>
                    </tr>
                `;
                    document.getElementById('sourceStatsContainer').insertAdjacentHTML('beforeend', newRow);
                }

                // Remove source row
                if (e.target && e.target.classList.contains('remove-source-btn')) {
                    e.target.closest('tr').remove();
                }
            });

            // Calculate rates when values change
            document.addEventListener('input', function(e) {
                if (e.target && (e.target.classList.contains('customers-count') ||
                        e.target.classList.contains('visits-count') ||
                        e.target.classList.contains('registrations-count') ||
                        e.target.classList.contains('contracted-units'))) {
                    const row = e.target.closest('tr');
                    calculateRates(row);
                }
            });

            function calculateRates(row) {
                const customers = parseFloat(row.querySelector('.customers-count').value) || 0;
                const visits = parseFloat(row.querySelector('.visits-count').value) || 0;
                const registrations = parseFloat(row.querySelector('.registrations-count').value) || 0;
                const contracted = parseFloat(row.querySelector('.contracted-units').value) || 0;

                // Calculate visit rate (visits/customers)
                const visitRate = customers > 0 ? ((visits / customers) * 100).toFixed(2) + '%' : '0%';
                row.querySelector('.visit-rate').value = visitRate;

                // Calculate registration rate (registrations/visits)
                const registrationRate = visits > 0 ? ((registrations / visits) * 100).toFixed(2) + '%' : '0%';
                row.querySelector('.registration-rate').value = registrationRate;

                // Calculate contract rate (contracted/registrations)
                const contractRate = registrations > 0 ? ((contracted / registrations) * 100).toFixed(2) + '%' :
                    '0%';
                row.querySelector('.contract-rate').value = contractRate;
            }
        });
    </script>
    <script>
        // Initialize variables
        if (typeof window.projectData === 'undefined') {
            window.projectData = {
                projectId: 0,
                sites: []
            };
        }

        function addProject() {
            window.projectData.projectId++;
            const projectDiv = document.createElement('div');
            projectDiv.className = 'project-item card mb-3';
            projectDiv.id = `project-${window.projectData.projectId}`;
            projectDiv.innerHTML = `
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Project #${window.projectData.projectId}</h6>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeProject(${window.projectData.projectId})">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('components.project_name') }}</label>
                    <input type="text" class="form-control" name="projects[${window.projectData.projectId}][project_name]" >
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('components.developer') }}</label>
                    <input type="text" class="form-control" name="projects[${window.projectData.projectId}][developer_name]" >
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('components.units') }}</label>
                    <input type="number" class="form-control" name="projects[${window.projectData.projectId}][total_units]" >
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('components.reserved') }}</label>
                    <input type="number" class="form-control" name="projects[${window.projectData.projectId}][reserved_units]" >
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('components.contracts') }}</label>
                    <input type="number" class="form-control" name="projects[${window.projectData.projectId}][contracts_units]" >
                </div>
            </div>`;
            document.getElementById('projectsContainer').appendChild(projectDiv);
        }

        function removeProject(id) {
            const projectToRemove = document.getElementById(`project-${id}`);
            if (projectToRemove) projectToRemove.remove();
        }

        async function fetchMatchingSites() {
            const fromDate = document.getElementById('from_date').value;
            const toDate = document.getElementById('to_date').value;

            if (fromDate && toDate) {
                try {
                    const response = await fetch(`/api/sites/filter?from_date=${fromDate}&to_date=${toDate}`);
                    if (!response.ok) throw new Error('Network response was not ok');
                    window.projectData.sites = await response.json();
                    renderSites();
                } catch (error) {
                    console.error('Error fetching sites:', error);
                    alert('Error fetching sites. Please try again.');
                }
            }
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const from = document.getElementById('from_date');
            const to = document.getElementById('to_date');

            function triggerFetchIfValid() {
                if (from.value && to.value) {
                    fetchMatchingSites();
                }
            }

            from.addEventListener('change', triggerFetchIfValid);
            to.addEventListener('change', triggerFetchIfValid);
        });
    </script>



    <script>
        function renderSites() {
            const container = document.getElementById('filteredSitesContainer');
            container.innerHTML = '';

            window.projectData.sites.forEach(site => {
                const wrapper = document.createElement('div');
                wrapper.className = 'form-check mb-2 d-flex justify-content-between align-items-center';

                wrapper.innerHTML = `
            <div>
                <input class="form-check-input" type="checkbox" name="sites[]" value="${site.id}" id="site_${site.id}" checked>
                <label class="form-check-label ms-2" for="site_${site.id}">
                    ${site.name}
                </label>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-primary btn-sm" onclick="editSite(${site.id})">
                    <i class="fas fa-edit"></i> تعديل
                </button>
                <form onsubmit="submitDeleteSite(event, ${site.id})">
                    <input type="hidden" name="_token" value="${window.csrfToken}">
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> حذف
                    </button>
                </form>
            </div>
        `;

                container.appendChild(wrapper);
            });
        }


        function submitDeleteSite(event, siteId) {
            event.preventDefault();
            if (!confirm('Are you sure you want to delete this site?')) return;

            fetch(`{{ url('/admin/comprehensive/site') }}/${siteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken,
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Failed to delete');
                    return res.json();
                })
                .then(() => {
                    alert('Site deleted successfully.');
                    document.querySelector(`[data-site-id="${siteId}"]`)?.remove();
                })
                .catch(error => {
                    console.error('Error deleting site:', error);
                    alert('Failed to delete site.');
                });
        }



        //         function handleAddSiteClick() {
        //             document.getElementById('addSiteForm').reset(); // Reset modal fields
        //             document.getElementById('site_id').value = '';

        //             // Set hidden date filters
        //             document.getElementById('modalFromDate').value = document.getElementById('from_date').value;
        //             document.getElementById('modalToDate').value = document.getElementById('to_date').value;

        //             const modal = new bootstrap.Modal(document.getElementById('addSiteModal'));
        // modal.show();
        //         }
        function handleAddSiteClick() {
            const fromDate = document.getElementById('from_date').value;
            const toDate = document.getElementById('to_date').value;

            if (!fromDate || !toDate) {
                alert('Please select both From Date and To Date before adding a site.');
                return;
            }

            document.getElementById('addSiteForm').reset(); // Reset modal fields
            document.getElementById('site_id').value = '';

            // Set hidden date filters
            document.getElementById('modalFromDate').value = fromDate;
            document.getElementById('modalToDate').value = toDate;

            const modal = new bootstrap.Modal(document.getElementById('addSiteModal'));
            modal.show();
        }

        function editSite(siteId) {
            document.getElementById('addSiteForm').reset();
            document.getElementById('site_id').value = siteId;

            // Set hidden date filters
            document.getElementById('modalFromDate').value = document.getElementById('from_date').value;
            document.getElementById('modalToDate').value = document.getElementById('to_date').value;

            fetch(`/admin/comprehensive/site/${siteId}`)
                .then(response => response.json())
                .then(data => {
                    document.querySelector('input[name="name"]').value = data.name || '';
                    const modal = new bootstrap.Modal(document.getElementById('addSiteModal'));
                    modal.show();

                    const reportSections = data.report_data || [];

                    reportSections.forEach(entry => {
                        if (!entry.section || !entry.section.name) return;

                        const sectionKey = entry.section.name;
                        let sectionData = {};

                        try {
                            if (typeof entry.data === 'string') {
                                const raw = entry.data.trim();
                                if (raw.startsWith('{') || raw.startsWith('[')) {
                                    sectionData = JSON.parse(raw);
                                } else {
                                    console.warn(`⚠️ البيانات المحفوظة لقسم ${sectionKey} ليست JSON:`, raw);
                                }
                            } else if (typeof entry.data === 'object') {
                                sectionData = entry.data || {};
                            } else {
                                sectionData = {};
                            }
                        } catch (e) {
                            console.warn(`⚠️ فشل تحويل البيانات لقسم ${sectionKey}`, e);
                            return;
                        }

                        switch (sectionKey) {
                            case 'reserved_report':
                                fillReservedProjects(sectionData);
                                break;
                            case 'contracts_report':
                                fillContractedProjects(sectionData);
                                break;
                            case 'status_item':
                                fillUnitCases(sectionData);
                                break;
                            case 'project_summary':
                                fillProjectSummary(sectionData);
                                break;
                            case 'unitStages':
                                fillUnitStages(sectionData);
                                break;
                            case 'unitStatisticsByStage':
                                fillUnitStats(sectionData);
                                break;
                            case 'visits_payments_contracts':
                                fillVisitsPayments(sectionData);
                                break;
                            case 'disinterest_reasons':
                                fillDisinterestReasons(sectionData);
                                break;
                            case 'unit_sales':
                                fillTotalSales(sectionData);
                                break;
                            case 'source_stats':
                                fillSourceStats(sectionData);
                                break;
                            case 'monthly_appointments':
                                fillMonthlyAppointments(sectionData);
                                break;
                            case 'targeted_report':
                                fillTargetedReport(sectionData);
                                break;
                        }
                    });
                });
        }
    </script>
    <script>
        function fillReservedProjects(data) {
            const container = document.getElementById('reservedProjectsContainer');
            container.innerHTML = '';

            // Handle both array and object formats
            const rowData = Array.isArray(data) ? data[0] || {} : data || {};

            const row = document.createElement('tr');
            row.innerHTML = `
        <td><input type="text" class="form-control" name="reserved_projects[0][project_name]" value="${rowData.project_name || ''}"></td>
        <td><input type="text" class="form-control" name="reserved_projects[0][developer]" value="${rowData.developer || ''}"></td>
        <td><input type="text" class="form-control" name="reserved_projects[0][decoration]" value="${rowData.decoration || ''}"></td>
        <td><input type="number" class="form-control" name="reserved_projects[0][units]" value="${rowData.units || ''}"></td>
        <td><input type="number" class="form-control" name="reserved_projects[0][reserved]" value="${rowData.reserved || ''}"></td>
    `;
            container.appendChild(row);
        }

        function fillContractedProjects(data) {
            const container = document.getElementById('contractedProjectsContainer');
            container.innerHTML = '';

            // Handle both array and object formats
            const rowData = Array.isArray(data) ? data[0] || {} : data || {};

            const row = document.createElement('tr');
            row.innerHTML = `
        <td><input type="text" class="form-control" name="contracted_projects[0][project_name]" value="${rowData.project_name || ''}"></td>
        <td><input type="text" class="form-control" name="contracted_projects[0][developer]" value="${rowData.developer || ''}"></td>
        <td><input type="text" class="form-control" name="contracted_projects[0][decoration]" value="${rowData.decoration || ''}"></td>
        <td><input type="number" class="form-control" name="contracted_projects[0][units]" value="${rowData.units || ''}"></td>
        <td><input type="number" class="form-control" name="contracted_projects[0][contracted_units]" value="${rowData.contracted_units || ''}"></td>
    `;
            container.appendChild(row);
        }

        function fillUnitCases(data) {
            // Handle case where data might be null/undefined
            data = data || {};

            document.querySelector('input[name="unit_cases[stage]"]').value = data.stage || '';
            document.querySelector('input[name="unit_cases[units_per_stage]"]').value = data.units_per_stage || '';
            document.querySelector('input[name="unit_cases[reserved_beneficiary]"]').value = data.reserved_beneficiary ||
            '';
            document.querySelector('input[name="unit_cases[reserved_non_beneficiary]"]').value = data
                .reserved_non_beneficiary || '';
            document.querySelector('input[name="unit_cases[contracted_beneficiary]"]').value = data
                .contracted_beneficiary || '';
            document.querySelector('input[name="unit_cases[contracted_non_beneficiary]"]').value = data
                .contracted_non_beneficiary || '';
            document.querySelector('input[name="unit_cases[available_beneficiary]"]').value = data.available_beneficiary ||
                '';
            document.querySelector('input[name="unit_cases[available_non_beneficiary]"]').value = data
                .available_non_beneficiary || '';
            document.querySelector('input[name="unit_cases[hidden_beneficiary]"]').value = data.hidden_beneficiary || '';
            document.querySelector('input[name="unit_cases[hidden_non_beneficiary]"]').value = data
                .hidden_non_beneficiary || '';
        }

        function fillProjectSummary(data) {
            // Handle case where data might be null/undefined
            data = data || {};

            ['A', 'B', 'C', 'D', 'E', 'F'].forEach(key => {
                const modelData = data[key] || {};
                document.querySelector(`input[name="project_summary[${key}][total_units]"]`).value = modelData
                    .total_units || '';
                document.querySelector(`input[name="project_summary[${key}][total_reservations]"]`).value =
                    modelData.total_reservations || '';
            });
        }

        function fillUnitStages(data) {
            // Handle case where data might be null/undefined
            data = data || {};

            // prices
            for (let i = 1; i <= 6; i++) {
                const priceValue = (data.prices && data.prices[i]) ? data.prices[i] : '';
                document.querySelector(`input[name="unit_stages[prices][${i}]"]`).value = priceValue;
            }

            // models
            const models = ['A_Abq', 'B_Ewan', 'C_Najdiyah', 'D_Ruweiq', 'E_Maqam', 'F_Roof'];
            models.forEach(model => {
                const modelData = data[model] || {};
                const fields = ['villas_count', 'reserved', 'contracted', 'blocked', 'available'];
                fields.forEach(field => {
                    const input = document.querySelector(`input[name="unit_stages[${model}][${field}]"]`);
                    if (input) {
                        input.value = modelData[field] || '';
                    }
                });
            });
        }

        function fillUnitStats(data) {
            // Handle case where data might be null/undefined
            data = data || {};

            if (data.phase1) {
                Object.entries(data.phase1).forEach(([model, values]) => {
                    values = values || {};
                    for (let key in values) {
                        const input = document.querySelector(`input[name="unit_stats[phase1][${model}][${key}]"]`);
                        if (input) input.value = values[key] || '';
                    }
                });
            }
        }

        function fillVisitsPayments(data) {
            // Handle case where data might be null/undefined
            data = data || {};

            ['visits', 'payments', 'contracted_units'].forEach(type => {
                const typeData = data[type] || {};
                for (let key in typeData) {
                    const input = document.querySelector(`input[name="visits_payments[${type}][${key}]"]`);
                    if (input) input.value = typeData[key] || '';
                }
            });
        }

        function fillDisinterestReasons(data) {
            const container = document.getElementById('disinterestReasonsContainer');
            container.innerHTML = '';

            // Handle both array and object formats
            const rowData = Array.isArray(data) ? data[0] || {} : data || {};

            const row = `
        <tr>
            <td><input type="text" class="form-control" name="disinterest_reasons[0][reason]" value="${rowData.reason || ''}"></td>
            <td><input type="number" class="form-control clients-count" name="disinterest_reasons[0][clients]" value="${rowData.clients || ''}"></td>
            <td><input type="text" class="form-control percentage" name="disinterest_reasons[0][percentage]" value="${rowData.percentage || ''}" readonly></td>
            <td></td>
        </tr>
    `;
            container.insertAdjacentHTML('beforeend', row);
            if (typeof calculatePercentages === 'function') calculatePercentages();
        }

        function fillTotalSales(data) {
            // Handle case where data might be null/undefined
            data = data || {};

            ['A', 'B', 'C', 'D', 'E', 'F'].forEach(model => {
                const modelData = data[model] || {};
                document.querySelector(`input[name="total_sales[${model}][reserved_area]"]`).value = modelData
                    .reserved_area || '';
                document.querySelector(`input[name="total_sales[${model}][reserved_price]"]`).value = modelData
                    .reserved_price || '';
                document.querySelector(`input[name="total_sales[${model}][contracted_area]"]`).value = modelData
                    .contracted_area || '';
                document.querySelector(`input[name="total_sales[${model}][contracted_price]"]`).value = modelData
                    .contracted_price || '';
            });
        }

        function fillSourceStats(data) {
            const container = document.getElementById('sourceStatsContainer');
            container.innerHTML = '';

            // Handle both array and object formats
            const rowData = Array.isArray(data) ? data[0] || {} : data || {};

            const row = `
        <tr>
            <td><input type="text" class="form-control" name="source_stats[0][source]" value="${rowData.source || ''}"></td>
            <td><input type="number" class="form-control customers-count" name="source_stats[0][customers]" value="${rowData.customers || ''}"></td>
            <td><input type="number" class="form-control visits-count" name="source_stats[0][visits]" value="${rowData.visits || ''}"></td>
            <td><input type="text" class="form-control visit-rate" name="source_stats[0][visit_rate]" value="${rowData.visit_rate || ''}" readonly></td>
            <td><input type="number" class="form-control registrations-count" name="source_stats[0][registrations]" value="${rowData.registrations || ''}"></td>
            <td><input type="text" class="form-control registration-rate" name="source_stats[0][registration_rate]" value="${rowData.registration_rate || ''}" readonly></td>
            <td><input type="number" class="form-control contracted-units" name="source_stats[0][contracted_units]" value="${rowData.contracted_units || ''}"></td>
            <td><input type="text" class="form-control contract-rate" name="source_stats[0][contract_rate]" value="${rowData.contract_rate || ''}" readonly></td>
            <td></td>
        </tr>
    `;
            container.insertAdjacentHTML('beforeend', row);
        }

        function fillMonthlyAppointments(data) {
            // Handle case where data might be null/undefined
            data = data || {};

            document.querySelector('input[name="monthly_appointments[appointments]"]').value = data.appointments || '';
            document.querySelector('input[name="monthly_appointments[visited]"]').value = data.visited || '';
            document.querySelector('input[name="monthly_appointments[success_rate]"]').value = data.success_rate || '';
        }

        function fillTargetedReport(data) {
            const statuses = ['مهتم', 'موعد', 'زيارة', 'حجز', 'إلغاء', 'عقد'];
            statuses.forEach((status, i) => {
                const targetInput = document.querySelector(`[name="target[${i}][target]"]`);
                const achievedInput = document.querySelector(`[name="target[${i}][achieved]"]`);

                if (targetInput) targetInput.value = data[status]?.target ?? 0;
                if (achievedInput) achievedInput.value = data[status]?.count ?? 0;
            });
        }
    </script>

    <!-- Global Loading Overlay -->
    <div id="loadingOverlay" style="display: none;">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
        <span class="mt-3 fs-5 fw-semibold">Loading, please wait...</span>
    </div>
    <script>
        // Show loading overlay when main form is submitted
        document.getElementById('mainSiteForm').addEventListener('submit', function() {
            document.getElementById('loadingOverlay').style.display = 'flex';
        });
    </script>
@endsection
