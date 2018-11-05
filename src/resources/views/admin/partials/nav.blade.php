<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
    </div>
    <!-- Top Menu Items -->
    <ul class="nav navbar-right top-nav">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?= $user ?> <b
                        class="caret"></b></a>
            <ul class="dropdown-menu">
                <li>
                    <a href="<?= route('icontrol.clearcache') ?>"><i class="fa fa-fw fa-database"></i> Clear Cache</a>
                </li>
                <li>
                    <a href="<?= route('sentinel.logout') ?>"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                </li>
            </ul>
        </li>
    </ul>
</nav>

<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
<div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav side-nav">
        <li class="<?= cleanSegments() == 'icontrol' ? 'active' : ''; ?>">
            <a href="<?= url('icontrol/dashboard'); ?>"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
        </li>
        @if(false && Sentinel::inRole('admin') )
            <li class="">
                <a href="<?= url('icontrol/roles'); ?>">
                    <i class="fa fa-fw fa-user"></i> Roles
                </a>
            </li>
        @endif

        @foreach ($modules as $module)
            @if( Sentinel::hasAccess( strtolower($module) . '.view') || (config('cms.'.$module.'.always_visible_for_admin') === true && Sentinel::inRole('admin')) )
                <li class="{{ $module_type == $module ? 'active' : '' }}">
                    @if(!is_null(config('cms.'.$module.'.nav_mode')) && config('cms.'.$module.'.nav_mode') == 'route')
                        <a href="{{ route(config('cms.'.$module.'.route'), config('cms.'.$module.'.params')) }}">
                    @elseif(!is_null(config('cms.'.$module.'.nav_mode')) && config('cms.'.$module.'.nav_mode') == 'url')
                        <a href="{{ config('cms.'.$module.'.url') }}" target="_blank">
                    @else
                        <a href="{{ url('icontrol/items/' . $module . '/overview') }}">
                    @endif
                        <i class="fa fa-fw fa-cube"></i>
                        {{ config('cms.' . $module . '.description') }}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</div>
<!-- /.navbar-collapse -->