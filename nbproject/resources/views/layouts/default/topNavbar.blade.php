<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="{{url('/dashboard')}}">
                <img src="{{URL::to('/')}}/public/img/sint_ams_logo.png" class="logo-max-width" height="100" alt="logo" />
            </a>
<!--            <div class="menu-toggler sidebar-toggler">
                <a title="" data-container="body" class="btn-show-hide-link">
                    <i class="btn">
                        <span id="fullMenu" data-fullMenu="1"><i class="fa fa-bars" style="font-size: 20px;"></i></span>
                    </i>
                </a>
            </div>-->
        </div>
        <!-- END LOGO 
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
            <span></span>
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu top-menu-style-1">
            <ul class="nav navbar-nav pull-right">

                <li class="show-hide-side-menu">
                    <!--<a title="" data-container="body" class="show-tooltip" data-original-title="{!! (Session::has('hideMenu')) ? __('label.SHOW_MENU') : __('label.HIDE_MENU') !!}" data-toggle="tooltip" data-placement="bottom"  href="{{ URL::to('changeMenuView') }}">
                        <i class="fa fa-exchange"></i>
                    </a>-->
                    <a title="" data-container="body" class="btn-show-hide-link">
                        <i class="btn">
                            <span id="fullMenu" data-fullMenu="1"><i class="fa fa-bars" style="font-size: 20px;"></i></span>
                        </i>
                    </a>
                </li>

                <!--                <li class="show-hide-side-menu">
                                    <a title="" data-container="body" class="btn-show-hide-link">
                                        <i class="btn">
                                            <span id="fullMenu" data-fullMenu="1">{!! __('label.FULL_SCREEN') !!}</span> 
                                        </i>
                                    </a>
                                </li>-->

                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-user">
                    <?php $user = Auth::user(); //get current user all information?>
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <?php if (!empty($user->photo)) { ?>
                            <img alt="{{$user['full_name']}}" class="img-circle" src="{{URL::to('/')}}/public/uploads/user/{{$user->photo}}" />
                        <?php } else { ?>
                            <img alt="{{$user['full_name']}}" class="img-circle" src="{{URL::to('/')}}/public/img/unknown.png" />
                        <?php } ?>

                        <span class="username username-hide-on-mobile">@lang('label.WELCOME_LOGIN') {!!$user->userGroup->name !!} ({{$user->full_name}})</span>
                        <i class="fa fa-angle-down"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-default">
<!--                        <li>
                            <a href="{{url('myProfile')}}">
                                <i class="icon-user"></i>@lang('label.MY_PROFILE')</a>
                        </li>-->
                        <li>
                            <a href="{{url('changePassword')}}">
                                <i class="icon-key"></i>@lang('label.CHANGE_PASSWORD')</a>
                        </li>
                        <!--<li class="divider"> </li>-->
                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                <i class="icon-logout"></i>@lang('label.LOGOUT')
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
                <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form2').submit();" title="" data-original-title="@lang('label.LOGOUT_')" data-toggle="tooltip" data-placement="bottom" class="show-tooltip">
                        <i class="icon-logout"></i>
                    </a>
                    <form id="logout-form2" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
                <!-- END QUICK SIDEBAR TOGGLER -->
            </ul>


        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.show-tooltip').tooltip();
    })
</script>