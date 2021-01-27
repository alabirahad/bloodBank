<?php
$currentControllerFunction = Route::currentRouteAction();
$currentCont = preg_match('/([a-z]*)@/i', request()->route()->getActionName(), $currentControllerFunction);
$controllerName = str_replace('controller', '', strtolower($currentControllerFunction[1]));
$routeName = strtolower(Route::getFacadeRoot()->current()->uri());
?>
<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul id="addsidebarFullMenu" class="page-sidebar-menu  page-header-fixed" data-keep-expanded="false"
            data-auto-scroll="true" data-slide-speed="200" style="padding-top: 10px">
            @if(Auth::user()->group_id == '1')
            <li <?php
            $current = ( in_array($controllerName, array('usergroup', 'bloodgroup', 'user', 'requestblood'
                , 'homepage', 'availableblood'))) ? 'start active open' : '';
            ?>class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-cogs"></i>
                    <span class="title">@lang('label.ADMIN_SETUP')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li <?php $current = ( in_array($controllerName, array('homepage'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/homePage')}}" class="nav-link ">
                            <span class="title">Home</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('usergroup'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/userGroup')}}" class="nav-link ">
                            <span class="title">@lang('label.USER_GROUP')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('bloodgroup'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/bloodGroup')}}" class="nav-link ">
                            <span class="title">@lang('label.BLOOD_GROUP')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('user'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/user')}}" class="nav-link ">
                            <span class="title">@lang('label.USER')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('requestblood'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/requestBlood')}}" class="nav-link ">
                            <span class="title">@lang('label.REQUEST_BLOOD')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('availableblood'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/availableBlood')}}" class="nav-link ">
                            <span class="title">Available Blood</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
        </ul>
    </div>
</div>
