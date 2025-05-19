<div class="main-menu-content">
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
        <li class="nav-item {{ Route::is('admin.index') ? 'active' : '' }}">
            <a href="{{ route('admin.index') }}">
                <i class="ft-home"></i>
                <span>{{ __('Dashboard') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Route::is('admin.Analysis') ? 'active' : '' }}">
            <a href="{{ route('admin.Analysis') }}">
                <i class="ft-bar-chart-2"></i>
                <span>{{ __('Analysis') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Route::is('admin.leads-sources') ? 'active' : '' }}">
            <a href="{{ route('admin.leads-sources') }}">
                <i class="ft-list"></i>
                <span>{{ __('Leads Sources') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Route::is('admin.leads') ? 'active' : '' }}">
            <a href="{{ route('admin.leads') }}">
                <i class="ft-activity"></i>
                <span>{{ __('Leads Time Line') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Route::is('admin.leads-status') ? 'active' : '' }}">
            <a href="{{ route('admin.leads-status') }}">
                <i class="ft-flag"></i>
                <span>{{ __('Leads Status') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Route::is('admin.items') ? 'active' : '' }}">
            <a href="{{ route('admin.items') }}">
                <i class="ft-box"></i>
                <span>{{ __('Units') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Route::is('admin.appointment') ? 'active' : '' }}">
            <a href="{{ route('admin.appointment') }}">
                <i class="ft-calendar"></i>
                <span>{{ __('Appointment') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Route::is('admin.call') ? 'active' : '' }}">
            <a href="{{ route('admin.call') }}">
                <i class="ft-phone-call"></i>
                <span>{{ __('Call Logs') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Route::is('admin.users.index') ? 'active' : '' }}">
            <a href="{{ route('admin.users.index') }}">
                <i class="ft-users"></i>
                <span>{{ __('User Management') }}</span>
            </a>
        </li>
        <li class="nav-item has-sub {{ Route::is('admin.reports.*') ? 'open' : '' }}">
            <a href="#">
                <i class="ft-bar-chart-2"></i>
                <span>{{ __('Reports')}}</span>
            </a>
            <ul class="menu-content">
                <li class="has-sub {{ Route::is('admin.items.status')  ? 'open' : '' }}">
                    <a href="#">
                        <i class="ft-menu"></i>
                       <span>{{ __('Unit Status Reports')}}</span>
                    </a>
                    <ul class="menu-content">
                        <li class="{{ Route::is('admin.items.status') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.items.status') }}">
                                <i class="ft-box"></i>
                              <span>{{ __('Unit Status Report')}}
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.items.unitStages') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.items.unitStages') }}">
                                <i class="ft-box"></i>
                              <span>{{ __('Unit Stages Report')}}
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.items.unitStatisticsByStage') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.items.unitStatisticsByStage') }}">
                                <i class="ft-box"></i>
                              <span>{{ __('Unit Statistics by Stage')}}
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="has-sub {{ Route::is('admin.reports.teamCategory') || Route::is('admin.reports.sales')  ? 'open' : '' }}">
                    <a href="#">
                        <i class="ft-plus"></i>
                       <span>{{ __('Additional Reports')}}</span>
                    </a>
                    <ul class="menu-content">
                        <li class="{{ Route::is('admin.reports.teamCategory') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.reports.teamCategory') }}">
                                <i class="ft-users"></i>
                              <span>{{ __('   Customer lists, social media, and outreach')}}
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.reports.teamReport') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.reports.teamReport') }}">
                                <i class="ft-bar-chart-2"></i>
                              <span>{{ __('Team Report')}}
                            </a>
                        </li>
                         <li class="{{ Route::is('admin.sales.report') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.sales.report') }}">
                                <i class="ft-shopping-cart"></i>
                                 <span>{{ __(' Sales Report')}}</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.customers.report') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.customers.report') }}">
                                <i class="ft-user"></i>
                                <span>{{ __('Customer Report') }}</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.reports.item') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.reports.item') }}">
                                <i class="ft-box"></i>
                                <span>{{ __('Unit Report') }}</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.reports.contracts') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.reports.contracts') }}">
                                <i class="ft-file"></i>
                                <span>{{ __('Contracts Report') }}</span>
                            </a>
                        </li>
                        <li class="{{ Route::is('admin.reports.source') ? 'active' : '' }}">
                            <a class="menu-item" href="{{ route('admin.reports.source') }}">
                                <i class="ft-list"></i>
                                <span>{{ __('Source Report') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
        <li class="nav-item {{ Route::is('admin.comprehensive.form') ? 'active' : '' }}">
            <a href="{{ route('admin.comprehensive.form') }}">
                <i class="ft-bar-chart-2"></i>
                <span>{{ __('Comprehensive Report') }}</span>
            </a>
        </li>
        
        
    </ul>
</div>
