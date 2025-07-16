
<div class="modal fade" id="editSiteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('components.edit_site') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="{{ __('components.close') }}"></button>
            </div>
            <form id="editSiteForm" method="POST" data-route="{{ route('comprehensive.site.update', '--ID--') }}">
                @csrf
                @method('PUT')

                <input type="hidden" name="id" id="editSiteId">


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
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="disinterest-reasons-tab" data-bs-toggle="tab"
                                data-bs-target="#disinterest-reasons" type="button"
                                role="tab">{{ __('components.disinterest_reasons') }}</button>
                        </li>
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
                                <input type="text" class="form-control"  id="editSiteName" name="name" >
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
                                <input type="file" class="form-control" name="map_path" accept="image/*"
                                    >
                            </div>
                        </div>

                        <!-- Reserved Tab -->
                        <div class="tab-pane fade" id="reserved" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('components.decoration') }}</th>
                                            <th>{{ __('components.project_name') }}</th>
                                            <th>{{ __('components.developer') }}</th>
                                            <th>{{ __('components.units') }}</th>
                                            <th>{{ __('components.reserved') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="reservedProjectsContainer">
                                        <tr>
                                            <td><input type="text" class="form-control"
                                                    name="reserved_projects[0][decoration]"></td>
                                            <td><input type="text" class="form-control"
                                                    name="reserved_projects[0][project_name]" ></td>
                                            <td><input type="text" class="form-control"
                                                    name="reserved_projects[0][developer]" ></td>
                                            <td><input type="number" class="form-control"
                                                    name="reserved_projects[0][units]" ></td>
                                            <td><input type="number" class="form-control"
                                                    name="reserved_projects[0][reserved]" ></td>
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
                                            <th>{{ __('components.decoration') }}</th>
                                            <th>{{ __('components.project_name') }}</th>
                                            <th>{{ __('components.developer') }}</th>
                                            <th>{{ __('components.units') }}</th>
                                            <th>{{ __('components.contracted_units') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="contractedProjectsContainer">
                                        <tr>
                                            <td><input type="text" class="form-control"
                                                    name="contracted_projects[0][decoration]"></td>
                                            <td><input type="text" class="form-control"
                                                    name="contracted_projects[0][project_name]" ></td>
                                            <td><input type="text" class="form-control"
                                                    name="contracted_projects[0][developer]" ></td>
                                            <td><input type="number" class="form-control"
                                                    name="contracted_projects[0][units]" ></td>
                                            <td><input type="number" class="form-control"
                                                    name="contracted_projects[0][contracted_units]" >
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
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">{{ __('components.units_per_stage') }}</th>
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
                                    <tbody>
                                        <tr>
                                            <td><input type="number" class="form-control"
                                                    name="unit_cases[units_per_stage]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_cases[reserved_beneficiary]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_cases[reserved_non_beneficiary]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_cases[contracted_beneficiary]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_cases[contracted_non_beneficiary]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_cases[available_beneficiary]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_cases[available_non_beneficiary]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_cases[hidden_beneficiary]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="unit_cases[hidden_non_beneficiary]"></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary add-row-btn">
                                                    {{ __('components.add_row') }}
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Container for additional rows -->
                                <div id="additionalRowsContainer" class="mt-3"></div>
                            </div>
                        </div>

                        <!-- Project Summary Tab -->
                        <div class="tab-pane fade" id="project-summary" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('components.data') }}</th>
                                            <th>A</th>
                                            <th>B</th>
                                            <th>C</th>
                                            <th>D</th>
                                            <th>E</th>
                                            <th>F</th>
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
                                            <th>A Abq</th>
                                            <th>B Ewan</th>
                                            <th>C Najdiyah</th>
                                            <th>D Ruweiq</th>
                                            <th>E Maqam</th>
                                            <th>F Roof</th>
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
                                        @foreach (['Abq (A)', 'Ewan (B)', 'Najdiyah (C)', 'Ruweiq (D)', 'Maqam (E)', 'Roof (F)'] as $model)
                                            <tr>
                                                <td>{{ $model }}</td>
                                                <td><input type="number" class="form-control"
                                                        name="unit_stats[phase1][{{ $model }}][units]">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                        name="unit_stats[phase1][{{ $model }}][sold_available]">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                        name="unit_stats[phase1][{{ $model }}][from]"
                                                        value="--"></td>
                                                <td><input type="text" class="form-control"
                                                        name="unit_stats[phase1][{{ $model }}][to]"
                                                        value="--"></td>
                                            </tr>
                                        @endforeach
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
                                            <th>{{ __('components.last_month') }}</th>
                                            <th>{{ __('components.two_months_ago') }}</th>
                                            @for ($i = 1; $i <= 5; $i++)
                                                <th>{{ __('components.week') }} {{ $i }}</th>
                                            @endfor
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ __('components.visits') }}</td>
                                            <td><input type="number" class="form-control"
                                                    name="visits_payments[visits][current_month]">
                                            </td>
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
                                            @endfor
                                        </tr>
                                        <tr>
                                            <td>{{ __('components.payments') }}</td>
                                            <td><input type="number" class="form-control"
                                                    name="visits_payments[payments][current_month]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="visits_payments[payments][last_month]">
                                            </td>
                                            <td><input type="number" class="form-control"
                                                    name="visits_payments[payments][two_months_ago]"></td>
                                            @for ($i = 1; $i <= 5; $i++)
                                                <td><input type="number" class="form-control"
                                                        name="visits_payments[payments][week{{ $i }}]">
                                                </td>
                                            @endfor
                                        </tr>
                                        <tr>
                                            <td>{{ __('components.contracted_units') }}</td>
                                            <td><input type="number" class="form-control"
                                                    name="visits_payments[contracted_units][current_month]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="visits_payments[contracted_units][last_month]"></td>
                                            <td><input type="number" class="form-control"
                                                    name="visits_payments[contracted_units][two_months_ago]"></td>
                                            @for ($i = 1; $i <= 5; $i++)
                                                <td><input type="number" class="form-control"
                                                        name="visits_payments[contracted_units][week{{ $i }}]">
                                                </td>
                                            @endfor
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Disinterest Reasons Tab -->
                        <div class="tab-pane fade" id="disinterest-reasons" role="tabpanel">
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
                                                    name="disinterest_reasons[0][reason]" ></td>
                                            <td><input type="number" class="form-control clients-count"
                                                    name="disinterest_reasons[0][clients]" ></td>
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
                        </div>

                        <!-- Total Sales Tab -->
                        <div class="tab-pane fade" id="total-sales" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th rowspan="2"></th>
                                            <th colspan="2">Abq A</th>
                                            <th colspan="2">Ewan B</th>
                                            <th colspan="2">Najdiyah C</th>
                                            <th colspan="2">Ruweiq D</th>
                                            <th colspan="2">Maqam E</th>
                                            <th colspan="2">Roof F</th>
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
                                                    name="source_stats[0][source]" ></td>
                                            <td><input type="number" class="form-control customers-count"
                                                    name="source_stats[0][customers]" ></td>
                                            <td><input type="number" class="form-control visits-count"
                                                    name="source_stats[0][visits]" ></td>
                                            <td><input type="text" class="form-control visit-rate"
                                                    name="source_stats[0][visit_rate]" readonly></td>
                                            <td><input type="number" class="form-control registrations-count"
                                                    name="source_stats[0][registrations]" ></td>
                                            <td><input type="text" class="form-control registration-rate"
                                                    name="source_stats[0][registration_rate]" readonly></td>
                                            <td><input type="number" class="form-control contracted-units"
                                                    name="source_stats[0][contracted_units]" ></td>
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
                                            <th>{{ __('components.percentage') }}</th>
                                            <th>{{ __('components.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="targetContainer">
                                        <tr>
                                            <td><input type="text" class="form-control" name="target[0][status]"
                                                    ></td>
                                            <td><input type="number" class="form-control target-value"
                                                    name="target[0][target]" ></td>
                                            <td><input type="number" class="form-control achieved-value"
                                                    name="target[0][achieved]" ></td>
                                            <td><input type="text" class="form-control percentage-value"
                                                    name="target[0][percentage]" readonly></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-success add-target-btn">
                                                    {{ __('components.add_target') }}
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('components.close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('components.save_site') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

async function editSite(siteId, event) {
    event.preventDefault();

    // Check if dates are selected
    const fromDate = document.getElementById('from_date').value;
    const toDate = document.getElementById('to_date').value;

    if (!fromDate || !toDate) {
        alert('Please select both "From" and "To" dates first.');
        return;
    }

    try {
        const response = await fetch(`/admin/comprehensive/site/${siteId}`);
        if (!response.ok) throw new Error('Failed to fetch site data');
        form.action = form.dataset.route.replace('--ID--', siteId);
        const site = await response.json();

        // Set the form action with the correct route and site ID
        const form = document.getElementById('editSiteForm');


        // Fill the hidden ID field
        document.getElementById('editSiteId').value = site.id;

        // Fill other form fields as needed
        // document.getElementById('editSiteName').value = site.name || '';

        new bootstrap.Modal(document.getElementById('editSiteModal')).show();
    } catch (error) {
        console.error('Error:', error);
        alert('Error loading site data');
    }
}
</script>
<script>
(function() {
    // Initialize counters
    let reservedProjectId = 0;
    let contractedProjectId = 0;
    let rowCount = 0;
    let reasonCount = 0;
    let sourceCount = 0;
    let targetCount = 0;

    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Only proceed if the modal exists
        if (!document.getElementById('editSiteModal')) return;

        // Set form action if site exists
        const editSiteForm = document.getElementById('editSiteForm');
if (editSiteForm && typeof site !== 'undefined' && site.id) {
    editSiteForm.action = `/admin/comprehensive/site/${site.id}`;
}

        // Set up event listeners using delegation
        setupEventListeners();

        // Initialize calculations
        calculatePercentages();
    });

    function setupEventListeners() {
        // Button clicks
        document.addEventListener('click', function(e) {
            // Reserved Projects
            if (e.target.matches('.add-reserved-project-btn')) {
                addReservedProject();
            }
            // Contracted Projects
            else if (e.target.matches('.add-contracted-project-btn')) {
                addContractedProject();
            }
            // Unit Cases
            else if (e.target.matches('.add-row-btn')) {
                addUnitCaseRow();
            }
            else if (e.target.matches('.remove-row-btn')) {
                e.target.closest('.additional-row').remove();
            }
            // Disinterest Reasons
            else if (e.target.matches('.add-reason-btn')) {
                addDisinterestReason();
            }
            else if (e.target.matches('.remove-reason-btn')) {
                e.target.closest('tr').remove();
                calculatePercentages();
            }
            // Source Stats
            else if (e.target.matches('.add-source-btn')) {
                addSourceStat();
            }
            else if (e.target.matches('.remove-source-btn')) {
                e.target.closest('tr').remove();
            }
            // Targets
            else if (e.target.matches('.add-target-btn')) {
                addTarget();
            }
            else if (e.target.matches('.remove-target-btn')) {
                e.target.closest('tr').remove();
            }
            // Phases
            else if (e.target.matches('#addPhaseBtn')) {
                addPhase();
            }
            else if (e.target.matches('.remove-phase-btn')) {
                const container = document.getElementById('additionalPhasesContainer');
                e.target.closest('.phase-table').remove();
                if (container.children.length === 0) {
                    container.style.display = 'none';
                }
            }
        });

        // Input changes
        document.addEventListener('input', function(e) {
            // Disinterest Reasons
            if (e.target.matches('.clients-count')) {
                calculatePercentages();
            }
            // Source Stats
            else if (e.target.matches('.customers-count, .visits-count, .registrations-count, .contracted-units')) {
                const row = e.target.closest('tr');
                calculateRates(row);
            }
            // Targets
            else if (e.target.matches('.target-value, .achieved-value')) {
                const row = e.target.closest('tr');
                calculatePercentage(row);
            }
        });
    }

    // Reserved Projects Functions
    function addReservedProject() {
        reservedProjectId++;
        const container = document.getElementById('reservedProjectsContainer');
        if (!container) return;

        const row = document.createElement('tr');
        row.innerHTML = `
            <td><input type="text" class="form-control" name="reserved_projects[${reservedProjectId}][decoration]"></td>
            <td><input type="text" class="form-control" name="reserved_projects[${reservedProjectId}][project_name]"></td>
            <td><input type="text" class="form-control" name="reserved_projects[${reservedProjectId}][developer]"></td>
            <td><input type="number" class="form-control" name="reserved_projects[${reservedProjectId}][units]"></td>
            <td><input type="number" class="form-control" name="reserved_projects[${reservedProjectId}][reserved]"></td>
        `;
        container.appendChild(row);
    }

    // Contracted Projects Functions
    function addContractedProject() {
        contractedProjectId++;
        const container = document.getElementById('contractedProjectsContainer');
        if (!container) return;

        const row = document.createElement('tr');
        row.innerHTML = `
            <td><input type="text" class="form-control" name="contracted_projects[${contractedProjectId}][decoration]"></td>
            <td><input type="text" class="form-control" name="contracted_projects[${contractedProjectId}][project_name]"></td>
            <td><input type="text" class="form-control" name="contracted_projects[${contractedProjectId}][developer]"></td>
            <td><input type="number" class="form-control" name="contracted_projects[${contractedProjectId}][units]"></td>
            <td><input type="number" class="form-control" name="contracted_projects[${contractedProjectId}][contracted_units]"></td>
        `;
        container.appendChild(row);
    }

    // Unit Cases Functions
    function addUnitCaseRow() {
        rowCount++;
        const container = document.getElementById('additionalRowsContainer');
        if (!container) return;

        const newRow = document.createElement('div');
        newRow.className = 'additional-row mb-3';
        newRow.innerHTML = `
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td><input type="number" class="form-control"
                                   name="unit_cases[additional][${rowCount}][units_per_stage]"></td>
                        <td><input type="number" class="form-control"
                                   name="unit_cases[additional][${rowCount}][reserved_beneficiary]"></td>
                        <td><input type="number" class="form-control"
                                   name="unit_cases[additional][${rowCount}][reserved_non_beneficiary]"></td>
                        <td><input type="number" class="form-control"
                                   name="unit_cases[additional][${rowCount}][contracted_beneficiary]"></td>
                        <td><input type="number" class="form-control"
                                   name="unit_cases[additional][${rowCount}][contracted_non_beneficiary]"></td>
                        <td><input type="number" class="form-control"
                                   name="unit_cases[additional][${rowCount}][available_beneficiary]"></td>
                        <td><input type="number" class="form-control"
                                   name="unit_cases[additional][${rowCount}][available_non_beneficiary]"></td>
                        <td><input type="number" class="form-control"
                                   name="unit_cases[additional][${rowCount}][hidden_beneficiary]"></td>
                        <td><input type="number" class="form-control"
                                   name="unit_cases[additional][${rowCount}][hidden_non_beneficiary]"></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger remove-row-btn">
                                Remove Row
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        `;
        container.appendChild(newRow);
    }

    // Disinterest Reasons Functions
    function addDisinterestReason() {
        reasonCount++;
        const container = document.getElementById('disinterestReasonsContainer');
        if (!container) return;

        const newRow = `
            <tr>
                <td><input type="text" class="form-control"
                          name="disinterest_reasons[${reasonCount}][reason]"></td>
                <td><input type="number" class="form-control clients-count"
                          name="disinterest_reasons[${reasonCount}][clients]"></td>
                <td><input type="text" class="form-control percentage"
                          name="disinterest_reasons[${reasonCount}][percentage]" readonly></td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-reason-btn">
                        Remove Reason
                    </button>
                </td>
            </tr>
        `;
        container.insertAdjacentHTML('beforeend', newRow);
    }

    function calculatePercentages() {
        let total = 0;
        const clientInputs = document.querySelectorAll('.clients-count');
        const totalClientsEl = document.getElementById('totalClients');
        const totalPercentageEl = document.getElementById('totalPercentage');

        if (!clientInputs.length || !totalClientsEl || !totalPercentageEl) return;

        clientInputs.forEach(input => {
            const value = parseFloat(input.value) || 0;
            total += value;
        });

        totalClientsEl.textContent = total;

        clientInputs.forEach(input => {
            const value = parseFloat(input.value) || 0;
            const percentage = total > 0 ? ((value / total) * 100).toFixed(2) + '%' : '0%';
            input.closest('tr').querySelector('.percentage').value = percentage;
        });

        totalPercentageEl.textContent = '100%';
    }

    // Source Stats Functions
    function addSourceStat() {
        sourceCount++;
        const container = document.getElementById('sourceStatsContainer');
        if (!container) return;

        const newRow = `
            <tr>
                <td><input type="text" class="form-control" name="source_stats[${sourceCount}][source]"></td>
                <td><input type="number" class="form-control customers-count" name="source_stats[${sourceCount}][customers]"></td>
                <td><input type="number" class="form-control visits-count" name="source_stats[${sourceCount}][visits]"></td>
                <td><input type="text" class="form-control visit-rate" name="source_stats[${sourceCount}][visit_rate]" readonly></td>
                <td><input type="number" class="form-control registrations-count" name="source_stats[${sourceCount}][registrations]"></td>
                <td><input type="text" class="form-control registration-rate" name="source_stats[${sourceCount}][registration_rate]" readonly></td>
                <td><input type="number" class="form-control contracted-units" name="source_stats[${sourceCount}][contracted_units]"></td>
                <td><input type="text" class="form-control contract-rate" name="source_stats[${sourceCount}][contract_rate]" readonly></td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-source-btn">
                        Remove Source
                    </button>
                </td>
            </tr>
        `;
        container.insertAdjacentHTML('beforeend', newRow);
    }

    function calculateRates(row) {
        if (!row) return;

        const customers = parseFloat(row.querySelector('.customers-count')?.value) || 0;
        const visits = parseFloat(row.querySelector('.visits-count')?.value) || 0;
        const registrations = parseFloat(row.querySelector('.registrations-count')?.value) || 0;
        const contracted = parseFloat(row.querySelector('.contracted-units')?.value) || 0;

        const visitRate = customers > 0 ? ((visits / customers) * 100).toFixed(2) + '%' : '0%';
        row.querySelector('.visit-rate').value = visitRate;

        const registrationRate = visits > 0 ? ((registrations / visits) * 100).toFixed(2) + '%' : '0%';
        row.querySelector('.registration-rate').value = registrationRate;

        const contractRate = registrations > 0 ? ((contracted / registrations) * 100).toFixed(2) + '%' : '0%';
        row.querySelector('.contract-rate').value = contractRate;
    }

    // Target Functions
    function addTarget() {
        targetCount++;
        const container = document.getElementById('targetContainer');
        if (!container) return;

        const newRow = `
            <tr>
                <td><input type="text" class="form-control" name="target[${targetCount}][status]"></td>
                <td><input type="number" class="form-control target-value" name="target[${targetCount}][target]"></td>
                <td><input type="number" class="form-control achieved-value" name="target[${targetCount}][achieved]"></td>
                <td><input type="text" class="form-control percentage-value" name="target[${targetCount}][percentage]" readonly></td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-target-btn">
                        Remove Target
                    </button>
                </td>
            </tr>
        `;
        container.insertAdjacentHTML('beforeend', newRow);
    }

    function calculatePercentage(row) {
        if (!row) return;

        const target = parseFloat(row.querySelector('.target-value')?.value) || 0;
        const achieved = parseFloat(row.querySelector('.achieved-value')?.value) || 0;
        const percentage = target > 0 ? ((achieved / target) * 100).toFixed(2) + '%' : '0%';
        row.querySelector('.percentage-value').value = percentage;
    }

    // Phase Functions
    function addPhase() {
        const container = document.getElementById('additionalPhasesContainer');
        if (!container) return;

        const phaseCount = document.querySelectorAll('.phase-table').length + 2;

        const newPhase = document.createElement('div');
        newPhase.className = 'phase-table mt-4';
        newPhase.innerHTML = `
            <h5>Phase No. ${phaseCount}</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Model</th>
                        <th>Units</th>
                        <th>Sold/Available</th>
                        <th>From</th>
                        <th>To</th>
                    </tr>
                </thead>
                <tbody>
                    ${['Abq (A)', 'Ewan (B)', 'Najdiyah (C)', 'Ruweiq (D)', 'Maqam (E)', 'Roof (F)'].map(model => `
                        <tr>
                            <td>${model}</td>
                            <td><input type="number" class="form-control"
                                    name="unit_stats[phase${phaseCount}][${model}][units]">
                            </td>
                            <td><input type="text" class="form-control"
                                    name="unit_stats[phase${phaseCount}][${model}][sold_available]">
                            </td>
                            <td><input type="text" class="form-control"
                                    name="unit_stats[phase${phaseCount}][${model}][from]"
                                    value="--"></td>
                            <td><input type="text" class="form-control"
                                    name="unit_stats[phase${phaseCount}][${model}][to]"
                                    value="--"></td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
            <button type="button" class="btn btn-sm btn-danger remove-phase-btn">Remove Phase</button>
        `;

        container.appendChild(newPhase);
        container.style.display = 'block';
    }
})();
</script>
