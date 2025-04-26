<div class="main-menu-content">
    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
        <li class="nav-item {{ route()->currentRouteNamed('admin.index') ? 'active' : '' }}">
            <a href="{{ route('admin.index') }}">
                <i class="ft-home"></i>
                <span>{{ __('Dashboard') }}</span>
            </a>
        </li>
        <li class="nav-item {{ route()->currentRouteNamed('admin.Analysis') ? 'active' : '' }}">
            <a href="{{ route('admin.Analysis') }}">
                <i class="ft-bar-chart-2"></i>
                <span>{{ __('Analysis') }}</span>
            </a>
        </li>
        <li class="nav-item {{ route()->currentRouteNamed('admin.leads-sources') ? 'active' : '' }}">
            <a href="{{ route('admin.leads-sources') }}">
                <i class="ft-list"></i>
                <span>{{ __('Leads Sources') }}</span>
            </a>
        </li>
        <li class="nav-item {{ route()->currentRouteNamed('admin.leads') ? 'active' : '' }}">
            <a href="{{ route('admin.leads') }}">
                <i class="ft-activity"></i>
                <span>{{ __('Leads Time Line') }}</span>
            </a>
        </li>
        <li class="nav-item {{ route()->currentRouteNamed('admin.leads-status') ? 'active' : '' }}">
            <a href="{{ route('admin.leads-status') }}">
                <i class="ft-flag"></i>
                <span>{{ __('Leads Status') }}</span>
            </a>
        </li>
        <li class="nav-item {{ route()->currentRouteNamed('admin.items') ? 'active' : '' }}">
            <a href="{{ route('admin.items') }}">
                <i class="ft-box"></i>
                <span>{{ __('Units') }}</span>
            </a>
        </li>
        <li class="nav-item {{ route()->currentRouteNamed('admin.appointment') ? 'active' : '' }}">
            <a href="{{ route('admin.appointment') }}">
                <i class="ft-calendar"></i>
                <span>{{ __('Appointment') }}</span>
            </a>
        </li>
        <li class="nav-item {{ route()->currentRouteNamed('admin.call') ? 'active' : '' }}">
            <a href="{{ route('admin.call') }}">
                <i class="ft-phone-call"></i>
                <span>{{ __('Call Logs') }}</span>
            </a>
        </li>
        <li class="nav-item {{ route()->currentRouteNamed('admin.users.index') ? 'active' : '' }}">
            <a href="{{ route('admin.users.index') }}">
                <i class="ft-users"></i>
                <span>{{ __('User Management') }}</span>
            </a>
        </li>
    </ul>
</div>