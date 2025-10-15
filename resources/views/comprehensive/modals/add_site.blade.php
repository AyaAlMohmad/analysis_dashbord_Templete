<style>
    #unit-cases .table-responsive {
    max-width: 100%;
    overflow-x: auto;
    padding-bottom: 1rem;
}

</style>
<div class="modal fade" id="addSiteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('components.add_site') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <form id="addSiteForm" method="POST" action="{{ route('admin.comprehensive.site.storeOrUpdate') }}"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="site_id" id="site_id">

                <input type="hidden" name="filter_from_date" id="modalFromDate">
                <input type="hidden" name="filter_to_date" id="modalToDate">

                <div class="modal-body">
                    <ul class="nav nav-tabs" id="siteTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic"
                                type="button" role="tab">{{ __('components.basic_info') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="map-tab" data-bs-toggle="tab" data-bs-target="#map"
                                type="button" role="tab">{{ __('components.map_image') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reserved-tab" data-bs-toggle="tab" data-bs-target="#reserved"
                                type="button" role="tab">{{ __('components.reserved') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contracted-tab" data-bs-toggle="tab"
                                data-bs-target="#contracted" type="button"
                                role="tab">{{ __('components.contracted_progress') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="unit-cases-tab" data-bs-toggle="tab"
                                data-bs-target="#unit-cases" type="button"
                                role="tab">{{ __('components.unit_cases') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="project-summary-tab" data-bs-toggle="tab"
                                data-bs-target="#project-summary" type="button"
                                role="tab">{{ __('components.project_summary') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="unit-stages-tab" data-bs-toggle="tab"
                                data-bs-target="#unit-stages" type="button"
                                role="tab">{{ __('components.unit_stages') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="unit-stats-tab" data-bs-toggle="tab"
                                data-bs-target="#unit-stats" type="button"
                                role="tab">{{ __('components.unit_statistics') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="visits-payments-tab" data-bs-toggle="tab"
                                data-bs-target="#visits-payments" type="button"
                                role="tab">{{ __('components.visits_payments') }}</button>
                        </li>
                        <!-- <li class="nav-item" role="presentation">
                            <button class="nav-link" id="disinterest-reasons-tab" data-bs-toggle="tab"
                                data-bs-target="#disinterest-reasons" type="button"
                                role="tab">{{ __('components.disinterest_reasons') }}</button>
                        </li> -->
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="total-sales-tab" data-bs-toggle="tab"
                                data-bs-target="#total-sales" type="button"
                                role="tab">{{ __('components.total_sales') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="source-stats-tab" data-bs-toggle="tab"
                                data-bs-target="#source-stats" type="button"
                                role="tab">{{ __('components.source_stats') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="monthly-appointments-tab" data-bs-toggle="tab"
                                data-bs-target="#monthly-appointments" type="button"
                                role="tab">{{ __('components.monthly_appointments') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="target-tab" data-bs-toggle="tab" data-bs-target="#target"
                                type="button" role="tab">{{ __('components.target') }}</button>
                        </li>

                    </ul>

                    <div class="tab-content p-3">
                        <!-- Basic Info Tab -->
                        <div class="tab-pane fade show active" id="basic" role="tabpanel">
                            <div class="mb-3">
                                <label class="form-label">{{ __('components.site_name') }}</label>
                                <input type="text" class="form-control" name="name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('components.white_logo') }}</label>
                                <input type="file" class="form-control" name="logo_path_white" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('components.regular_logo') }}</label>
                                <input type="file" class="form-control" name="logo_path" accept="image/*">
                            </div>
                        </div>

                        <!-- Map Image Tab -->
                        <div class="tab-pane fade" id="map" role="tabpanel">
                            <div class="mb-3">
                                <label class="form-label">{{ __('components.map_image') }}</label>
                                <input type="file" class="form-control" name="map_path" accept="image/*">
                            </div>
                        </div>

                        <!-- Reserved Tab -->
                        <div class="tab-pane fade" id="reserved" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>

                                            <th>{{ __('components.project_name') }}</th>
                                            <th>{{ __('components.developer') }}</th>
                                            <th>{{ __('components.units') }}</th>
                                            <th>{{ __('components.reserved') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="reservedProjectsContainer">
                                        <tr>

                                            <td><input type="text" class="form-control"
                                                    name="reserved_projects[0][project_name]"></td>
                                            <td><input type="text" class="form-control"
                                                    name="reserved_projects[0][developer]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="reserved_projects[0][units]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="reserved_projects[0][reserved]"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-sm btn-success" onclick="addReservedProject()">
                                    {{ __('components.add_project') }}
                                </button>
                            </div>
                        </div>

                        <!-- Contracted Progress Tab -->
                        <div class="tab-pane fade" id="contracted" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('components.project_name') }}</th>
                                            <th>{{ __('components.developer') }}</th>
                                            <th>{{ __('components.units') }}</th>
                                            <th>{{ __('components.contracted_units') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="contractedProjectsContainer">
                                        <tr>

                                            <td><input type="text" class="form-control"
                                                    name="contracted_projects[0][project_name]"></td>
                                            <td><input type="text" class="form-control"
                                                    name="contracted_projects[0][developer]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="contracted_projects[0][units]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="contracted_projects[0][contracted_units]">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-sm btn-success"
                                    onclick="addContractedProject()">
                                    {{ __('components.add_project') }}
                                </button>
                            </div>
                        </div>

                        <!-- Unit Cases Tab -->
                        <div class="tab-pane fade" id="unit-cases" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle" style="min-width: 1200px;">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" style="min-width: 160px;">{{ __('components.stage') }}</th>
                                            <th rowspan="2" style="min-width: 120px;">{{ __('components.units_per_stage') }}</th>
                                            <th colspan="2">{{ __('components.reserved') }}</th>
                                            <th colspan="2">{{ __('components.contracted') }}</th>
                                            <th colspan="2">{{ __('components.available') }}</th>
                                            <th colspan="2">{{ __('components.hidden') }}</th>
                                            <th rowspan="2">{{ __('components.actions') }}</th>
                                        </tr>
                                        <tr>
                                            <th>{{ __('components.beneficiary') }}</th>
                                            <th>{{ __('components.non_beneficiary') }}</th>
                                            <th>{{ __('components.beneficiary') }}</th>
                                            <th>{{ __('components.non_beneficiary') }}</th>
                                            <th>{{ __('components.beneficiary') }}</th>
                                            <th>{{ __('components.non_beneficiary') }}</th>
                                            <th>{{ __('components.beneficiary') }}</th>
                                            <th>{{ __('components.non_beneficiary') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="unitCasesRows">
                                        {{-- الصف الأساسي --}}
                                        <tr>
                                            <td><input type="text" class="form-control" name="unit_cases[stage]"></td>
                                            <td><input type="number" class="form-control" name="unit_cases[units_per_stage]"></td>
                                            <td><input type="number" class="form-control" name="unit_cases[reserved_beneficiary]"></td>
                                            <td><input type="number" class="form-control" name="unit_cases[reserved_non_beneficiary]"></td>
                                            <td><input type="number" class="form-control" name="unit_cases[contracted_beneficiary]"></td>
                                            <td><input type="number" class="form-control" name="unit_cases[contracted_non_beneficiary]"></td>
                                            <td><input type="number" class="form-control" name="unit_cases[available_beneficiary]"></td>
                                            <td><input type="number" class="form-control" name="unit_cases[available_non_beneficiary]"></td>
                                            <td><input type="number" class="form-control" name="unit_cases[hidden_beneficiary]"></td>
                                            <td><input type="number" class="form-control" name="unit_cases[hidden_non_beneficiary]"></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary add-row-btn">
                                                    {{ __('components.add_row') }}
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>



                        <!-- Project Summary Tab -->
                        <div class="tab-pane fade" id="project-summary" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('components.data') }}</th>
                                           <th><input type="text" class="header-input" value="A"></th>
                    <th><input type="text" class="header-input" value="B"></th>
                    <th><input type="text" class="header-input" value="C"></th>
                    <th><input type="text" class="header-input" value="D"></th>
                    <th><input type="text" class="header-input" value="E"></th>
                    <th><input type="text" class="header-input" value="F"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ __('components.total_units') }}</td>
                                            <td><input type="number" class="form-control"
                                                    name="project_summary[A][total_units]">
                                            </td>
                                            <td><input type="number" class="form-control"
                                                    name="project_summary[B][total_units]">
                                            </td>
                                            <td><input type="number" class="form-control"
                                                    name="project_summary[C][total_units]">
                                            </td>
                                            <td><input type="number" class="form-control"
                                                    name="project_summary[D][total_units]">
                                            </td>
                                            <td><input type="number" class="form-control"
                                                    name="project_summary[E][total_units]">
                                            </td>
                                            <td><input type="number" class="form-control"
                                                    name="project_summary[F][total_units]">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('components.total_reservations') }}</td>
                                            <td><input type="number" class="form-control"
                                                    name="project_summary[A][total_reservations]">
                                            </td>
                                            <td><input type="number" class="form-control"
                                                    name="project_summary[B][total_reservations]">
                                            </td>
                                            <td><input type="number" class="form-control"
                                                    name="project_summary[C][total_reservations]">
                                            </td>
                                            <td><input type="number" class="form-control"
                                                    name="project_summary[D][total_reservations]">
                                            </td>
                                            <td><input type="number" class="form-control"
                                                    name="project_summary[E][total_reservations]">
                                            </td>
                                            <td><input type="number" class="form-control"
                                                    name="project_summary[F][total_reservations]">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Unit Stages Tab -->
                        <div class="tab-pane fade" id="unit-stages" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">{{ __('components.decoration') }}</th>
                                            <th colspan="6">{{ __('components.prices') }}</th>
                                        </tr>
                                        <tr>
                                            <th><input type="text" class="form-control"
                                                    name="unit_stages[prices][1]" placeholder="Price 1"></th>
                                            <th><input type="text" class="form-control"
                                                    name="unit_stages[prices][2]" placeholder="Price 2"></th>
                                            <th><input type="text" class="form-control"
                                                    name="unit_stages[prices][3]" placeholder="Price 3"></th>
                                            <th><input type="text" class="form-control"
                                                    name="unit_stages[prices][4]" placeholder="Price 4"></th>
                                            <th><input type="text" class="form-control"
                                                    name="unit_stages[prices][5]" placeholder="Price 5"></th>
                                            <th><input type="text" class="form-control"
                                                    name="unit_stages[prices][6]" placeholder="Price 6"></th>
                                        </tr>
                                        <tr>
                                            <th>{{ __('components.models') }}</th>
                                             <th><input type="text" class="header-input" value="A Abq"></th>
                    <th><input type="text" class="header-input" value="B Ewan"></th>
                    <th><input type="text" class="header-input" value="C Najdiyah"></th>
                    <th><input type="text" class="header-input" value="D Ruweiq"></th>
                    <th><input type="text" class="header-input" value="E Maqam"></th>
                    <th><input type="text" class="header-input" value="F Roof"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Villas Count</td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[A_Abq][villas_count]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[B_Ewan][villas_count]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[C_Najdiyah][villas_count]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[D_Ruweiq][villas_count]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[E_Maqam][villas_count]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[F_Roof][villas_count] "></td>
                                        </tr>
                                        <tr>
                                            <td>Reserved</td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[A_Abq][reserved]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[B_Ewan][reserved]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[C_Najdiyah][reserved]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[D_Ruweiq][reserved]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[E_Maqam][reserved]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[F_Roof][reserved]"></td>
                                        </tr>
                                        <tr>
                                            <td>Contracted</td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[A_Abq][contracted]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[B_Ewan][contracted]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[C_Najdiyah][contracted]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[D_Ruweiq][contracted]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[E_Maqam][contracted]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[F_Roof][contracted]"></td>
                                        </tr>
                                        <tr>
                                            <td>Blocked</td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[A_Abq][blocked]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[B_Ewan][blocked]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[C_Najdiyah][blocked]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[D_Ruweiq][blocked]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[E_Maqam][blocked]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[F_Roof][blocked]"></td>
                                        </tr>
                                        <tr>
                                            <td>Available</td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[A_Abq][available]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[B_Ewan][available]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[C_Najdiyah][available]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[D_Ruweiq][available]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[E_Maqam][available]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_stages[F_Roof][available]"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Unit Statistics by Stage Tab -->
                        <div class="tab-pane fade" id="unit-stats" role="tabpanel">
                            <div class="table-responsive">
                                <h5>Phase No. 1</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('components.model') }}</th>
                                            <th>{{ __('components.units') }}</th>
                                            <th>{{ __('components.sold_available') }}</th>
                                            <th>{{ __('components.from') }}</th>
                                            <th>{{ __('components.to') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       <tr>
                            <td><input type="text" class="model-input" value="Abq (A)"></td>
                            <td><input type="number" class="form-control" name="unit_stats[phase1][Abq (A)][units]"></td>
                            <td><input type="text" class="form-control" name="unit_stats[phase1][Abq (A)][sold_available]"></td>
                            <td><input type="text" class="form-control" name="unit_stats[phase1][Abq (A)][from]" value="--"></td>
                            <td><input type="text" class="form-control" name="unit_stats[phase1][Abq (A)][to]" value="--"></td>
                        </tr>
                        <tr>
                            <td><input type="text" class="model-input" value="Ewan (B)"></td>
                            <td><input type="number" class="form-control" name="unit_stats[phase1][Ewan (B)][units]"></td>
                            <td><input type="text" class="form-control" name="unit_stats[phase1][Ewan (B)][sold_available]"></td>
                            <td><input type="text" class="form-control" name="unit_stats[phase1][Ewan (B)][from]" value="--"></td>
                            <td><input type="text" class="form-control" name="unit_stats[phase1][Ewan (B)][to]" value="--"></td>
                        </tr>
                        <tr>
                            <td><input type="text" class="model-input" value="Najdiyah (C)"></td>
                            <td><input type="number" class="form-control" name="unit_stats[phase1][Najdiyah (C)][units]"></td>
                            <td><input type="text" class="form-control" name="unit_stats[phase1][Najdiyah (C)][sold_available]"></td>
                            <td><input type="text" class="form-control" name="unit_stats[phase1][Najdiyah (C)][from]" value="--"></td>
                            <td><input type="text" class="form-control" name="unit_stats[phase1][Najdiyah (C)][to]" value="--"></td>
                        </tr>
                        <tr>
                            <td><input type="text" class="model-input" value="Ruweiq (D)"></td>
                            <td><input type="number" class="form-control" name="unit_stats[phase1][Ruweiq (D)][units]"></td>
                            <td><input type="text" class="form-control" name="unit_stats[phase1][Ruweiq (D)][sold_available]"></td>
                            <td><input type="text" class="form-control" name="unit_stats[phase1][Ruweiq (D)][from]" value="--"></td>
                            <td><input type="text" class="form-control" name="unit_stats[phase1][Ruweiq (D)][to]" value="--"></td>
                        </tr>
                        <tr>
                            <td><input type="text" class="model-input" value="Maqam (E)"></td>
                            <td><input type="number" class="form-control" name="unit_stats[phase1][Maqam (E)][units]"></td>
                            <td><input type="text" class="form-control" name="unit_stats[phase1][Maqam (E)][sold_available]"></td>
                            <td><input type="text" class="form-control" name="unit_stats[phase1][Maqam (E)][from]" value="--"></td>
                            <td><input type="text" class="form-control" name="unit_stats[phase1][Maqam (E)][to]" value="--"></td>
                        </tr>
                                    </tbody>
                                </table>

                                <!-- Button to add new phase -->
                                <button type="button" class="btn btn-sm btn-primary mt-3" id="addPhaseBtn">
                                    {{ __('components.add_phase') }}
                                </button>

                                <!-- Container for additional phases (hidden by default) -->
                                <div id="additionalPhasesContainer" style="display: none;">
                                    <!-- Additional phases will be added here dynamically -->
                                </div>
                            </div>
                        </div>
                        <!-- Visits & Payments & Contracts Tab -->
                        <div class="tab-pane fade" id="visits-payments" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>{{ __('components.current_month') }}</th>
                                            <!--<th>{{ __('components.last_month') }}</th>
                                            <th>{{ __('components.two_months_ago') }}</th>
                                            @for ($i = 1; $i <= 5; $i++)
                                                <th>{{ __('components.week') }} {{ $i }}</th>
                                            @endfor-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ __('components.visits') }}</td>
                                            <td><input type="number" class="form-control"
                                                    name="visits_payments[visits][current_month]">
                                            <!-- </td>
                                            <td><input type="number" class="form-control"
                                                    name="visits_payments[visits][last_month]">
                                            </td>
                                            <td><input type="number" class="form-control"
                                                    name="visits_payments[visits][two_months_ago]">
                                            </td>
                                            @for ($i = 1; $i <= 5; $i++)
                                                <td><input type="number" class="form-control"
                                                        name="visits_payments[visits][week{{ $i }}]">
                                                </td>
                                            @endfor -->
                                        </tr>
                                        <tr>
                                            <td>{{ __('components.payments') }}</td>
                                            <td><input type="number" class="form-control"
                                                    name="visits_payments[payments][current_month]"></td>
                                            <!-- <td><input type="number" class="form-control"
                                                    name="visits_payments[payments][last_month]">
                                            </td>
                                            <td><input type="number" class="form-control"
                                                    name="visits_payments[payments][two_months_ago]"></td>
                                            @for ($i = 1; $i <= 5; $i++)
                                                <td><input type="number" class="form-control"
                                                        name="visits_payments[payments][week{{ $i }}]">
                                                </td>
                                            @endfor
                                        </tr> -->
                                        <tr>
                                            <td>{{ __('components.contracted_units') }}</td>
                                            <td><input type="number" class="form-control"
                                                    name="visits_payments[contracted_units][current_month]"></td>
                                            <!-- <td><input type="number" class="form-control"
                                                    name="visits_payments[contracted_units][last_month]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="visits_payments[contracted_units][two_months_ago]"></td>
                                            @for ($i = 1; $i <= 5; $i++)
                                                <td><input type="number" class="form-control"
                                                        name="visits_payments[contracted_units][week{{ $i }}]">
                                                </td>
                                            @endfor -->
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Disinterest Reasons Tab -->
                        <!-- <div class="tab-pane fade" id="disinterest-reasons" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('components.disinterest_reasons') }}</th>
                                            <th>{{ __('components.number_of_clients') }}</th>
                                            <th>{{ __('components.percentage_of_total') }}</th>
                                            <th>{{ __('components.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="disinterestReasonsContainer">
                                        <tr>
                                            <td><input type="text" class="form-control"
                                                    name="disinterest_reasons[0][reason]"></td>
                                            <td><input type="number" class="form-control clients-count"
                                                    name="disinterest_reasons[0][clients]"></td>
                                            <td><input type="text" class="form-control percentage"
                                                    name="disinterest_reasons[0][percentage]" readonly></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-success add-reason-btn">
                                                    {{ __('components.add_reason') }}
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>{{ __('components.total') }}</th>
                                            <th id="totalClients">0</th>
                                            <th id="totalPercentage">100%</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div> -->

                        <!-- Total Sales Tab -->
                        <div class="tab-pane fade" id="total-sales" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                       <tr>
                            <th rowspan="2"></th>
                            <th colspan="2"><input type="text" class="model-input" value="Abq A" placeholder="اسم النموذج"></th>
                            <th colspan="2"><input type="text" class="model-input" value="Ewan B" placeholder="اسم النموذج"></th>
                            <th colspan="2"><input type="text" class="model-input" value="Najdiyah C" placeholder="اسم النموذج"></th>
                            <th colspan="2"><input type="text" class="model-input" value="Ruweiq D" placeholder="اسم النموذج"></th>
                            <th colspan="2"><input type="text" class="model-input" value="Maqam E" placeholder="اسم النموذج"></th>
                            <th colspan="2"><input type="text" class="model-input" value="Roof F" placeholder="اسم النموذج"></th>
                        </tr>
                                        <tr>
                                            @foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $model)
                                                <th>{{ __('components.build_area') }}</th>
                                                <th>{{ __('components.price') }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ __('components.reserved') }}</td>
                                            @foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $model)
                                                <td><input type="text" class="form-control"
                                                        name="total_sales[{{ $model }}][reserved_area]">
                                                </td>
                                                <td><input type="number" class="form-control"
                                                        name="total_sales[{{ $model }}][reserved_price]">
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td>{{ __('components.contracted') }}</td>
                                            @foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $model)
                                                <td><input type="text" class="form-control"
                                                        name="total_sales[{{ $model }}][contracted_area]">
                                                </td>
                                                <td><input type="number" class="form-control"
                                                        name="total_sales[{{ $model }}][contracted_price]">
                                                </td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Source Statistics Tab -->
                        <div class="tab-pane fade" id="source-stats" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('components.source') }}</th>
                                            <th>{{ __('components.number_of_customers') }}</th>
                                            <th>{{ __('components.visits') }}</th>
                                            <th>{{ __('components.visit_rate') }}</th>
                                            <th>{{ __('components.registrations') }}</th>
                                            <th>{{ __('components.registration_rate') }}</th>
                                            <th>{{ __('components.contracted_units') }}</th>
                                            <th>{{ __('components.contract_rate') }}</th>
                                            <th>{{ __('components.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="sourceStatsContainer">
                                        <tr>
                                            <td><input type="text" class="form-control"
                                                    name="source_stats[0][source]"></td>
                                            <td><input type="number" class="form-control customers-count"
                                                    name="source_stats[0][customers]"></td>
                                            <td><input type="number" class="form-control visits-count"
                                                    name="source_stats[0][visits]"></td>
                                            <td><input type="text" class="form-control visit-rate"
                                                    name="source_stats[0][visit_rate]" readonly></td>
                                            <td><input type="number" class="form-control registrations-count"
                                                    name="source_stats[0][registrations]"></td>
                                            <td><input type="text" class="form-control registration-rate"
                                                    name="source_stats[0][registration_rate]" readonly></td>
                                            <td><input type="number" class="form-control contracted-units"
                                                    name="source_stats[0][contracted_units]"></td>
                                            <td><input type="text" class="form-control contract-rate"
                                                    name="source_stats[0][contract_rate]" readonly></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-success add-source-btn">
                                                    {{ __('components.add_source') }}
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <!-- Monthly Appointments Tab -->
                        <div class="tab-pane fade" id="monthly-appointments" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>{{ __('components.appointments') }}</th>
                                                <th>{{ __('components.visited') }}</th>
                                                <th>{{ __('components.success_rate') }}</th>
                                                <th>{{ __('components.external_visit') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><input type="number" class="form-control"
                                                        name="monthly_appointments[appointments]">
                                                </td>
                                                <td><input type="number" class="form-control"
                                                        name="monthly_appointments[visited]">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                        name="monthly_appointments[success_rate]">
                                                </td>
                                                <td><input type="number" class="form-control"
                                                        name="monthly_appointments[external_rate]">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Target Tab -->
                        <div class="tab-pane fade" id="target" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('components.status') }}</th>
                                            <th>{{ __('components.target') }}</th>
                                            <th>{{ __('components.achieved') }}</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $statuses = ['مهتم', 'موعد', 'زيارة', 'حجز', 'إلغاء', 'عقد'];
                                            $targetData = $sections['targeted_report']['data'] ?? [];
                                        @endphp

                                        @foreach ($statuses as $i => $status)
                                            <tr>
                                                <td>
                                                    <input type="text" class="form-control"
                                                        name="target[{{ $i }}][status]"
                                                        value="{{ $status }}" readonly>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control"
                                                        name="target[{{ $i }}][target]"
                                                        value="{{ $targetData[$status]['target'] ?? 0 }}">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control"
                                                        name="target[{{ $i }}][achieved]"
                                                        value="{{ $targetData[$status]['count'] ?? 0 }}">
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>



                    </div>
                </div>

                <div class="modal-footer">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        {{ __('components.close') }}
                    </button>

                    <button type="submit" class="btn btn-primary">{{ __('components.save_site') }}</button>
                </div>
            </form>
        </div>
    </div>

<script>
    let reservedProjectId = 0;
    let contractedProjectId = 0;

    function addReservedProject() {
        reservedProjectId++;
        const container = document.getElementById('reservedProjectsContainer');
        const row = document.createElement('tr');
        row.innerHTML = `

            <td><input type="text" class="form-control" name="reserved_projects[${reservedProjectId}][project_name]" ></td>
            <td><input type="text" class="form-control" name="reserved_projects[${reservedProjectId}][developer]" ></td>
            <td><input type="number" class="form-control" name="reserved_projects[${reservedProjectId}][units]" ></td>
            <td><input type="number" class="form-control" name="reserved_projects[${reservedProjectId}][reserved]" ></td>
        `;
        container.appendChild(row);
    }

    function addContractedProject() {
        contractedProjectId++;
        const container = document.getElementById('contractedProjectsContainer');
        const row = document.createElement('tr');
        row.innerHTML = `

            <td><input type="text" class="form-control" name="contracted_projects[${contractedProjectId}][project_name]" ></td>
            <td><input type="text" class="form-control" name="contracted_projects[${contractedProjectId}][developer]" ></td>
            <td><input type="number" class="form-control" name="contracted_projects[${contractedProjectId}][units]" ></td>
            <td><input type="number" class="form-control" name="contracted_projects[${contractedProjectId}][contracted_units]" ></td>
        `;
        container.appendChild(row);
    }
</script>
<script>
    document.getElementById('addPhaseBtn').addEventListener('click', function() {
        const container = document.getElementById('additionalPhasesContainer');
        // Start counting from 2 by adding 1 to the length
        const phaseCount = document.querySelectorAll('.phase-table').length + 2;

        // Create new phase table
        const newPhase = document.createElement('div');
        newPhase.className = 'phase-table mt-4';
        newPhase.innerHTML = `
            <h5>Phase No. ${phaseCount}</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('components.model') }}</th>
                        <th>{{ __('components.units') }}</th>
                        <th>{{ __('components.sold_available') }}</th>
                        <th>{{ __('components.from') }}</th>
                        <th>{{ __('components.to') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (['Abq (A)', 'Ewan (B)', 'Najdiyah (C)', 'Ruweiq (D)', 'Maqam (E)', 'Roof (F)'] as $model)
                        <tr>
                            <td>{{ $model }}</td>
                            <td><input type="number" class="form-control"
                                    name="unit_stats[phase${phaseCount}][{{ $model }}][units]">
                            </td>
                            <td><input type="text" class="form-control"
                                    name="unit_stats[phase${phaseCount}][{{ $model }}][sold_available]">
                            </td>
                            <td><input type="text" class="form-control"
                                    name="unit_stats[phase${phaseCount}][{{ $model }}][from]"
                                    value="--"></td>
                            <td><input type="text" class="form-control"
                                    name="unit_stats[phase${phaseCount}][{{ $model }}][to]"
                                    value="--"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="button" class="btn btn-sm btn-danger remove-phase-btn">{{ __('components.remove_phase') }}</button>
        `;

        // Add the new phase to container
        container.appendChild(newPhase);

        // Show container if it was hidden
        if (container.style.display === 'none') {
            container.style.display = 'block';
        }
    });

    // Event delegation for remove buttons (since they're dynamically added)
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-phase-btn')) {
            e.target.closest('.phase-table').remove();

            // Hide container if no phases left
            const container = document.getElementById('additionalPhasesContainer');
            if (container.children.length === 0) {
                container.style.display = 'none';
            }
        }
    });
</script>

<script>
 let rowCount = 0;

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('add-row-btn')) {
        rowCount++;
        const tbody = document.getElementById('unitCasesRows');

        const rowHTML = `
            <tr class="additional-row">
                <td><input type="text" class="form-control" name="unit_cases[additional][${rowCount}][stage]"></td>
                <td><input type="number" class="form-control" name="unit_cases[additional][${rowCount}][units_per_stage]"></td>
                <td><input type="number" class="form-control" name="unit_cases[additional][${rowCount}][reserved_beneficiary]"></td>
                <td><input type="number" class="form-control" name="unit_cases[additional][${rowCount}][reserved_non_beneficiary]"></td>
                <td><input type="number" class="form-control" name="unit_cases[additional][${rowCount}][contracted_beneficiary]"></td>
                <td><input type="number" class="form-control" name="unit_cases[additional][${rowCount}][contracted_non_beneficiary]"></td>
                <td><input type="number" class="form-control" name="unit_cases[additional][${rowCount}][available_beneficiary]"></td>
                <td><input type="number" class="form-control" name="unit_cases[additional][${rowCount}][available_non_beneficiary]"></td>
                <td><input type="number" class="form-control" name="unit_cases[additional][${rowCount}][hidden_beneficiary]"></td>
                <td><input type="number" class="form-control" name="unit_cases[additional][${rowCount}][hidden_non_beneficiary]"></td>
                <td><button type="button" class="btn btn-sm btn-danger remove-row-btn">{{ __('components.remove_row') }}</button></td>
            </tr>`;

        tbody.insertAdjacentHTML('beforeend', rowHTML);
    }

    if (e.target.classList.contains('remove-row-btn')) {
        e.target.closest('tr').remove();
    }
});

</script>

<script>
    let reasonCount = 0;

    // Add new reason row
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('add-reason-btn')) {
            reasonCount++;
            const newRow = `
                <tr>
                    <td><input type="text" class="form-control"
                              name="disinterest_reasons[${reasonCount}][reason]" ></td>
                    <td><input type="number" class="form-control clients-count"
                              name="disinterest_reasons[${reasonCount}][clients]" ></td>
                    <td><input type="text" class="form-control percentage"
                              name="disinterest_reasons[${reasonCount}][percentage]" readonly></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-reason-btn">
                            {{ __('components.remove_reason') }}
                        </button>
                    </td>
                </tr>
            `;
            document.getElementById('disinterestReasonsContainer').insertAdjacentHTML('beforeend', newRow);
        }

        // Remove reason row
        if (e.target && e.target.classList.contains('remove-reason-btn')) {
            e.target.closest('tr').remove();
            calculatePercentages();
        }
    });

    // Calculate percentages when client counts change
    document.addEventListener('input', function(e) {
        if (e.target && e.target.classList.contains('clients-count')) {
            calculatePercentages();
        }
    });

    function calculatePercentages() {
        let total = 0;
        const clientInputs = document.querySelectorAll('.clients-count');

        // Calculate total clients
        clientInputs.forEach(input => {
            const value = parseFloat(input.value) || 0;
            total += value;
        });

        document.getElementById('totalClients').textContent = total;

        // Calculate percentages for each row
        clientInputs.forEach(input => {
            const value = parseFloat(input.value) || 0;
            const percentage = total > 0 ? ((value / total) * 100).toFixed(2) + '%' : '0%';
            input.closest('tr').querySelector('.percentage').value = percentage;
        });

        document.getElementById('totalPercentage').textContent = '100%';
    }
</script>

