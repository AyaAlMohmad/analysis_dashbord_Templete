<div class="main-menu-content">
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

        <li class="nav-item {{ Route::is('admin.index') ? 'active' : '' }}">
            <a href="{{ route('admin.index') }}">
                <i class="ft-home"></i>
                <span>{{ __('sidebar.dashboard') }}</span>
            </a>
        </li>

        <li class="nav-item {{ Route::is('admin.Analysis') ? 'active' : '' }}">
            <a href="{{ route('admin.Analysis') }}">
                <i class="ft-bar-chart"></i>
                <span>{{ __('sidebar.analysis') }}</span>
            </a>
        </li>

        <li class="nav-item {{ Route::is('admin.leads-sources') ? 'active' : '' }}">
            <a href="{{ route('admin.leads-sources') }}">
                <i class="ft-link"></i>
                <span>{{ __('sidebar.leads_sources') }}</span>
            </a>
        </li>

        <li class="nav-item {{ Route::is('admin.leads') ? 'active' : '' }}">
            <a href="{{ route('admin.leads') }}">
                <i class="ft-clock"></i>
                <span>{{ __('sidebar.leads_timeline') }}</span>
            </a>
        </li>

        <li class="nav-item {{ Route::is('admin.leads-status') ? 'active' : '' }}">
            <a href="{{ route('admin.leads-status') }}">
                <i class="ft-flag"></i>
                <span>{{ __('sidebar.leads_status') }}</span>
            </a>
        </li>

        <li class="nav-item {{ Route::is('admin.items') ? 'active' : '' }}">
            <a href="{{ route('admin.items') }}">
                <i class="ft-grid"></i>
                <span>{{ __('sidebar.units') }}</span>
            </a>
        </li>

        <li class="nav-item {{ Route::is('admin.appointment') ? 'active' : '' }}">
            <a href="{{ route('admin.appointment') }}">
                <i class="ft-calendar"></i>
                <span>{{ __('sidebar.appointment') }}</span>
            </a>
        </li>

        <li class="nav-item {{ Route::is('admin.call') ? 'active' : '' }}">
            <a href="{{ route('admin.call') }}">
                <i class="ft-phone"></i>
                <span>{{ __('sidebar.call_logs') }}</span>
            </a>
        </li>

        <li class="nav-item {{ Route::is('admin.users.index') ? 'active' : '' }}">
            <a href="{{ route('admin.users.index') }}">
                <i class="ft-users"></i>
                <span>{{ __('sidebar.user_management') }}</span>
            </a>
        </li>

        {{-- التقارير --}}
        <li class="nav-item has-sub {{ Route::is('admin.reports.*') ? 'open' : '' }}">
            <a href="#">
                <i class="ft-pie-chart"></i>
                <span>{{ __('sidebar.reports') }}</span>
            </a>
            <ul class="menu-content">

                {{-- تقارير الوحدات --}}
                <li class="has-sub {{ Route::is('admin.items.status')  ? 'open' : '' }}">
                    <a href="#"><i class="ft-layers"></i><span>{{ __('sidebar.unit_status_reports') }}</span></a>
                    <ul class="menu-content">
                        <li class="{{ Route::is('admin.items.status') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.items.status') }}">
                                <i class="ft-bar-chart-2"></i>
                                <span>{{ __('sidebar.unit_status_report') }}</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.items.unitStages') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.items.unitStages') }}">
                                <i class="ft-trending-up"></i>
                                <span>{{ __('sidebar.unit_stages_report') }}</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.items.unitStatisticsByStage') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.items.unitStatisticsByStage') }}">
                                <i class="ft-bar-chart"></i>
                                <span>{{ __('sidebar.unit_statistics_by_stage') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
     <li class="has-sub {{ Route::is('admin.reports.teamCategory') || Route::is('admin.reports.sales') ? 'open' : '' }}">
                    <a href="#"><i class="ft-plus-circle"></i><span>{{ __('sidebar.additional_reports') }}</span></a>
                    <ul class="menu-content">
                        <li class="{{ Route::is('admin.reports.teamCategory') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.reports.teamCategory') }}">
                                <i class="ft-user-check"></i>
                                <span>{{ __('sidebar.customer_social_outreach') }}</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.reports.teamReport') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.reports.teamReport') }}">
                                <i class="ft-users"></i>
                                <span>{{ __('sidebar.team_report') }}</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.sales.report') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.sales.report') }}">
                                <i class="ft-shopping-cart"></i>
                                <span>{{ __('sidebar.sales_report') }}</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.customers.report') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.customers.report') }}">
                                <i class="ft-user"></i>
                                <span>{{ __('sidebar.customer_report') }}</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.reports.item') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.reports.item') }}">
                                <i class="ft-box"></i>
                                <span>{{ __('sidebar.unit_report') }}</span>
                            </a>
                        </li>
                        {{-- <li class="{{ Route::is('admin.reports.contracts') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.reports.contracts') }}">
                                <i class="ft-file-text"></i>
                                <span>{{ __('sidebar.contracts_report') }}</span>
                            </a>
                        </li> --}}
                        <li class="{{ Route::is('admin.reports.source') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.reports.source') }}">
                                <i class="ft-list"></i>
                                <span>{{ __('sidebar.source_report') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>

        <li class="nav-item {{ Route::is('admin.comprehensive.form') ? 'active' : '' }}">
            <a href="{{ route('admin.comprehensive.form') }}">
                <i class="ft-layers"></i>
                <span>{{ __('sidebar.comprehensive_report') }}</span>
            </a>
        </li>

    </ul>
</div>
