<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="<?php echo e(url('/homePage')); ?>">
                <img src="<?php echo e(URL::to('/')); ?>/public/img/img.png" class="logo-max-width" height="100" alt="logo" />
            </a>
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
                    <a title="" data-container="body" class="btn-show-hide-link">
                        <i class="btn">
                            <span id="fullMenu" data-fullMenu="1"><i class="fa fa-bars" style="font-size: 20px;"></i></span>
                        </i>
                    </a>
                </li>

                 <li class="dropdown dropdown-user">
                    <?php $user = Auth::user(); //get current user all information?>
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <?php if (!empty($user->photo)) { ?>
                            <img alt="<?php echo e($user['full_name']); ?>" class="img-circle" src="<?php echo e(URL::to('/')); ?>/public/uploads/user/<?php echo e($user->photo); ?>" />
                        <?php } else { ?>
                            <img alt="<?php echo e($user['full_name']); ?>" class="img-circle" src="<?php echo e(URL::to('/')); ?>/public/img/unknown.png" />
                        <?php } ?>

                        <span class="username username-hide-on-mobile tooltips" data-placement="bottom" title="<?php echo e($user->full_name); ?>">
                            <?php echo app('translator')->get('label.WELCOME_LOGIN'); ?> <?php echo e($user->full_name); ?> (<?php echo $user->userGroup->name; ?>)
                        </span>
                        <i class="fa fa-angle-down"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                <i class="icon-logout"></i><?php echo app('translator')->get('label.LOGOUT'); ?>
                            </a>

                            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                <?php echo csrf_field(); ?>
                            </form>
                        </li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
                <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li>
                    <a href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault();
                            document.getElementById('logout-form2').submit();" title="" data-original-title="<?php echo app('translator')->get('label.LOGOUT_'); ?>" data-toggle="tooltip" data-placement="bottom" class="show-tooltip">
                        <i class="icon-logout"></i>
                    </a>
                    <form id="logout-form2" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                        <?php echo csrf_field(); ?>
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
</script><?php /**PATH E:\Xampp\htdocs\bloodBank\resources\views/layouts/default/topNavbar.blade.php ENDPATH**/ ?>