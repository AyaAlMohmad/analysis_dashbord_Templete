<div class="main-menu-content">
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

        @if (auth()->user()->hasPermission('view_dashboard'))
        <li class="nav-item {{ Route::is('admin.index') ? 'active' : '' }}">
            <a href="{{ route('admin.index') }}">
                <i class="ft-home"></i>
                <span>{{ __('sidebar.dashboard') }}</span>
            </a>
        </li>
        @endif

        @if (auth()->user()->hasPermission('view_analysis'))
        <li class="nav-item {{ Route::is('admin.Analysis') ? 'active' : '' }}">
            <a href="{{ route('admin.Analysis') }}">
                <i class="ft-bar-chart"></i>
                <span>{{ __('sidebar.analysis') }}</span>
            </a>
        </li>
        @endif

        @if (auth()->user()->hasPermission('view_leads_sources'))
        <li class="nav-item {{ Route::is('admin.leads-sources') ? 'active' : '' }}">
            <a href="{{ route('admin.leads-sources') }}">
                <i class="ft-link"></i>
                <span>{{ __('sidebar.leads_sources') }}</span>
            </a>
        </li>
        @endif

        @if (auth()->user()->hasPermission('view_leads_timeline'))
        <li class="nav-item {{ Route::is('admin.leads') ? 'active' : '' }}">
            <a href="{{ route('admin.leads') }}">
                <i class="ft-clock"></i>
                <span>{{ __('sidebar.leads_timeline') }}</span>
            </a>
        </li>
        @endif

        @if (auth()->user()->hasPermission('view_leads_status'))
        <li class="nav-item {{ Route::is('admin.leads-status') ? 'active' : '' }}">
            <a href="{{ route('admin.leads-status') }}">
                <i class="ft-flag"></i>
                <span>{{ __('sidebar.leads_status') }}</span>
            </a>
        </li>
        @endif

        @if (auth()->user()->hasPermission('view_units'))
        <li class="nav-item {{ Route::is('admin.items') ? 'active' : '' }}">
            <a href="{{ route('admin.items') }}">
                <i class="ft-grid"></i>
                <span>{{ __('sidebar.units') }}</span>
            </a>
        </li>
        @endif

        @if (auth()->user()->hasPermission('view_appointments'))
        <li class="nav-item {{ Route::is('admin.appointment') ? 'active' : '' }}">
            <a href="{{ route('admin.appointment') }}">
                <i class="ft-calendar"></i>
                <span>{{ __('sidebar.appointment') }}</span>
            </a>
        </li>
        @endif

        @if (auth()->user()->hasPermission('view_calls'))
        <li class="nav-item {{ Route::is('admin.call') ? 'active' : '' }}">
            <a href="{{ route('admin.call') }}">
                <i class="ft-phone"></i>
                <span>{{ __('sidebar.call_logs') }}</span>
            </a>
        </li>
        @endif

        @if (auth()->user()->hasPermission('view_campaigns'))
        <li class="nav-item {{ Route::is('admin.campaign.form') ? 'active' : '' }}">
            <a href="{{ route('admin.campaign.form') }}">
                <i class="ft-target"></i>
                <span>{{ __('sidebar.crm_advertising_campaign') }}</span>
            </a>
        </li>
        @endif

        <!-- إدارة النظام مع الصلاحيات -->
        @if (auth()->user()->hasPermission('view_users') || auth()->user()->hasPermission('view_roles'))
        <li class="nav-item has-sub {{ Route::is('admin.users.*') || Route::is('admin.roles.*') ? 'open' : '' }}">
            <a href="#">
                <i class="ft-users"></i>
                <span>{{ __('sidebar.system_management') }}</span>
            </a>
            <ul class="menu-content">
                @if (auth()->user()->hasPermission('view_users'))
                <li class="{{ Route::is('admin.users.index') ? 'active' : '' }}">
                    <a class="menu-item" href="{{ route('admin.users.index') }}">
                        <i class="ft-user"></i>
                        <span>{{ __('sidebar.user_management') }}</span>
                    </a>
                </li>
                @endif

                @if (auth()->user()->hasPermission('view_roles'))
                <li class="{{ Route::is('admin.roles.index') ? 'active' : '' }}">
                    <a class="menu-item" href="{{ route('admin.roles.index') }}">
                        <i class="ft-shield"></i>
                        <span>{{ __('sidebar.roles_permissions') }}</span>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        @if (auth()->user()->hasPermission('view_project_progress'))
        <li class="nav-item {{ Route::is('admin.project-progress.index') ? 'active' : '' }}">
            <a href="{{ route('admin.project-progress.index') }}">
                <i class="ft-trending-up"></i>
                <span>{{ __('sidebar.project_progress') }}</span>
            </a>
        </li>
        @endif

       @if (auth()->user()->hasPermission('view_project_plans'))
<li class="nav-item has-sub {{ Route::is('admin.project_plan.index,*') ? 'open' : '' }}">
    <a href="#">
        <i class="ft-target"></i>
        <span>{{ __('sidebar.project_plan') }}</span>
    </a>
    <ul class="menu-content">
        <li class="{{ Route::is('admin.project_plan.index',['site' => 'jeddah']) ? 'active' : '' }}">
            <a class="menu-item" href="{{ route('admin.project_plan.index', ['site' => 'jeddah']) }}">
                <i class="ft-target"></i>
                <span>{{ __('sidebar.project_plan_jaddah') }}</span>
            </a>
        </li>
        <li class="{{ Route::is('admin.project_plan.index',['site' => 'dhahran']) ? 'active' : '' }}">
            <a class="menu-item" href="{{ route('admin.project_plan.index', ['site' => 'dhahran']) }}">
                <i class="ft-target"></i>
                <span>{{ __('sidebar.project_plan_dhahran') }}</span>
            </a>
        </li>
        <li class="{{ Route::is('admin.project_plan.index',['site' => 'bashaer']) ? 'active' : '' }}">
            <a class="menu-item" href="{{ route('admin.project_plan.index', ['site' => 'bashaer']) }}">
                <i class="ft-target"></i>
                <span>{{ __('sidebar.project_plan_bashaer') }}</span>
            </a>
        </li>
        <li class="{{ Route::is('admin.project_plan.index',['site' => 'alfursan']) ? 'active' : '' }}">
            <a class="menu-item" href="{{ route('admin.project_plan.index', ['site' => 'alfursan']) }}">
                <i class="ft-target"></i>
                <span>{{ __('sidebar.project_plan_alfursan') }}</span>
            </a>
        </li>
    </ul>
</li>
@endif

        @if (auth()->user()->hasPermission('view_reports'))
        <li class="nav-item has-sub {{ Route::is('admin.reports.*') ? 'open' : '' }}">
            <a href="#">
                <i class="ft-pie-chart"></i>
                <span>{{ __('sidebar.reports') }}</span>
            </a>
            <ul class="menu-content">

                @if (auth()->user()->hasPermission('view_unit_status_reports'))
                <li class="has-sub {{ Route::is('admin.items.status') || Route::is('admin.items.unitStages') || Route::is('admin.items.unitStatisticsByStage') ? 'open' : '' }}">
                    <a href="#">
                        <i class="ft-layers"></i>
                        <span>{{ __('sidebar.unit_status_reports') }}</span>
                    </a>
                    <ul class="menu-content">
                        @if (auth()->user()->hasPermission('view_unit_status_reports'))
                        <li class="{{ Route::is('admin.items.status') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.items.status') }}">
                                <i class="ft-bar-chart-2"></i>
                                <span>{{ __('sidebar.unit_status_report') }}</span>
                            </a>
                        </li>
                        @endif

                        @if (auth()->user()->hasPermission('view_unit_stages_reports'))
                        <li class="{{ Route::is('admin.items.unitStages') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.items.unitStages') }}">
                                <i class="ft-trending-up"></i>
                                <span>{{ __('sidebar.unit_stages_report') }}</span>
                            </a>
                        </li>
                        @endif

                        @if (auth()->user()->hasPermission('view_unit_statistics_reports'))
                        <li class="{{ Route::is('admin.items.unitStatisticsByStage') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.items.unitStatisticsByStage') }}">
                                <i class="ft-bar-chart"></i>
                                <span>{{ __('sidebar.unit_statistics_by_stage') }}</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if (auth()->user()->hasPermission('view_additional_reports'))
                <li class="has-sub {{ Route::is('admin.reports.item') || Route::is('admin.reports.source') ? 'open' : '' }}">
                    <a href="#">
                        <i class="ft-plus-circle"></i>
                        <span>{{ __('sidebar.additional_reports') }}</span>
                    </a>
                    <ul class="menu-content">
                        <li class="{{ Route::is('admin.reports.item') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.reports.item') }}">
                                <i class="ft-box"></i>
                                <span>{{ __('sidebar.unit_report') }}</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.reports.source') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.reports.source') }}">
                                <i class="ft-list"></i>
                                <span>{{ __('sidebar.source_report') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
            </ul>
        </li>
        @endif

        @if (auth()->user()->hasPermission('view_comprehensive_reports'))
        <li class="nav-item has-sub {{ Route::is('admin.comprehensive.*') ? 'open' : '' }}">
            <a href="#">
                <i class="ft-layers"></i>
                <span>{{ __('sidebar.comprehensive_report') }}</span>
            </a>
            <ul class="menu-content">
                <li class="{{ Route::is('admin.comprehensive.form') ? 'active' : '' }}">
                    <a class="menu-item" href="{{ route('admin.comprehensive.form') }}">
                        <i class="ft-layers"></i>
                        <span>{{ __('sidebar.report_form') }}</span>
                    </a>
                </li>

                <li class="has-sub {{ Route::is('admin.comprehensive.*') ? 'open' : '' }}">
                    <a href="#">
                        <i class="ft-copy"></i>
                        <span>{{ __('sidebar.report_parts') }}</span>
                    </a>
                    <ul class="menu-content">
                        <li class="{{ Route::is('admin.comprehensive.map.form') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.comprehensive.map.form') }}">
                                <i class="ft-map"></i>
                                <span>{{ __('sidebar.colored_map') }}</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.comprehensive.appointments.form') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.comprehensive.appointments.form') }}">
                                <i class="ft-bookmark"></i>
                                <span>{{ __('sidebar.appointments_report') }}</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.comprehensive.project-summary.form') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.comprehensive.project-summary.form') }}">
                                <i class="ft-file-text"></i>
                                <span>{{ __('sidebar.summary_report') }}</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.comprehensive.unit-stages.process') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.comprehensive.unit-stages.process') }}">
                                <i class="ft-layers"></i>
                                <span>{{ __('sidebar.unit_satage_report') }}</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.comprehensive.status.form') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.comprehensive.status.form') }}">
                                <i class="ft-activity"></i>
                                <span>{{ __('sidebar.status_report') }}</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.comprehensive.unit-details.form') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.comprehensive.unit-details.form') }}">
                                <i class="ft-grid"></i>
                                <span>{{ __('sidebar.unit_details_report') }}</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.comprehensive.vpc.form') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.comprehensive.vpc.form') }}">
                                <i class="ft-credit-card"></i>
                                <span>{{ __('sidebar.vpc_report') }}</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.comprehensive.unit-statistics.form') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.comprehensive.unit-statistics.form') }}">
                                <i class="ft-bar-chart"></i>
                                <span>{{ __('sidebar.unit_statistics_report') }}</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.comprehensive.targeted.form') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.comprehensive.targeted.form') }}">
                                <i class="ft-target"></i>
                                <span>{{ __('sidebar.targeted_report') }}</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.comprehensive.unit-sales.form') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.comprehensive.unit-sales.form') }}">
                                <i class="ft-shopping-cart"></i>
                                <span>{{ __('sidebar.unit_sales_report') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
        @endif

    </ul>
</div>
