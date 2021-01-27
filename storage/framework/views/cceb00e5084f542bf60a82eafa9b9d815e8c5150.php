<?php
$currentControllerFunction = Route::currentRouteAction();
$currentCont = preg_match('/([a-z]*)@/i', request()->route()->getActionName(), $currentControllerFunction);
$controllerName = str_replace('controller', '', strtolower($currentControllerFunction[1]));
$routeName = strtolower(Route::getFacadeRoot()->current()->uri());


$iconArr = [
    '1' => 'fa fa-search',
    '2' => 'fa fa-file-text-o',
    '3' => 'fa fa-adjust',
    '4' => 'fa fa-asterisk',
    '5' => 'fa fa-certificate',
    '6' => 'fa fa-check-circle',
    '7' => 'fa fa-cube',
    '8' => 'fa fa-dot-circle-o',
    '9' => 'fa fa-external-link-square',
    '10' => 'fa fa-gear',
    '11' => 'fa fa-globe',
    '12' => 'fa fa-hdd-o',
    '13' => 'fa fa-industry',
    '14' => 'fa fa-inbox',
    '15' => 'fa fa-life-bouy',
    '16' => 'fa fa-square',
    '17' => 'fa fa-sun-o',
    '18' => 'fa fa-tachometer',
    '19' => 'fa fa-tasks',
    '20' => 'fa fa-university',
    '21' => 'fa fa-tag',
    '22' => 'fa fa-clone',
    '23' => 'fa fa-circle',
    '24' => 'fa fa-gg',
    '25' => 'fa fa-users',
];

//echo '<pre>';print_r($permittedReportArr);echo '</pre>';
?>
<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul id="addsidebarFullMenu" class="page-sidebar-menu  page-header-fixed" data-keep-expanded="false"
            data-auto-scroll="true" data-slide-speed="200" style="padding-top: 10px">
            <!--li class="sidebar-toggler-wrapper hide">
            <div class="sidebar-toggler">
                <span></span>
            </div>
        </li-->

            <!-- start dashboard menu -->
            <li <?php $current = ( in_array($controllerName, array('dashboard'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                <a href="<?php echo e(url('/dashboard')); ?>" class="nav-link ">
                    <i class="icon-home"></i>
                    <span class="title"> <?php echo app('translator')->get('label.DASHBOARD'); ?></span>
                </a>
            </li>

            <?php if(Auth::user()->group_id == '1'): ?>
            <li <?php
            $current = ( in_array($controllerName, array('trainingyear', 'term', 'trade', 'module', 'subject'
                        , 'syndicate', 'subsyndicate', 'event', 'courseid', 'noofparticular', 'termtocourse'
                        , 'event', 'subevent', 'subsubevent', 'subsubsubevent', 'eventtree', 'eventgroup'
                        , 'gradingsystem', 'factorclassification', 'markingfactors', 'mutualassessmentevent'))) ? 'start active open' : '';
            ?>class="nav-item <?php echo e($current); ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-calendar"></i>
                    <span class="title"><?php echo app('translator')->get('label.TERMS_COURSE_SETUP'); ?></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li <?php $current = ( in_array($controllerName, array('trainingyear'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/trainingYear')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.TRAINING_YEAR'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('term'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/term')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.TERM'); ?></span>
                        </a>
                    </li>
                    <li
                        <?php $current = ( in_array($controllerName, array('courseid'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('/courseId')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.COURSE_ID'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('syndicate'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/syndicate')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.SYN'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('subsyndicate'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/subSyndicate')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.SUB_SYN'); ?></span>
                        </a>
                    </li>

                    <li <?php $current = ( in_array($controllerName, array('termtocourse')) && ($routeName != 'termtocourse/activationorclosing')) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/termToCourse')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.TERM_SCHEDULING'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('termtocourse')) && ($routeName == 'termtocourse/activationorclosing' )) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('termToCourse/activationOrClosing')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.TERM_SCHEDULING_ACTIVATION_CLOSING'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('event'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/event')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.EVENT'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('subevent'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/subEvent')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.SUB_EVENT'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('subsubevent'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/subSubEvent')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.SUB_SUB_EVENT'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('subsubsubevent'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/subSubSubEvent')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.SUB_SUB_SUB_EVENT'); ?></span>
                        </a>
                    </li>
                    <!--                    <li <?php $current = ( in_array($controllerName, array('eventtree'))) ? 'start active open' : ''; ?>
                                            class="nav-item <?php echo e($current); ?>">
                                            <a href="<?php echo e(url('/eventTree')); ?>" class="nav-link">
                                                <span class="title"><?php echo app('translator')->get('label.EVENT_TREE'); ?></span>
                                            </a>
                                        </li>-->
                    <li <?php $current = ( in_array($controllerName, array('eventgroup'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/eventGroup')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.EVENT_GROUP'); ?></span>
                        </a>
                    </li>

                    <li <?php $current = ( in_array($controllerName, array('gradingsystem'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/gradingSystem')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.GRADING_SYSTEM'); ?></span>
                        </a>
                    </li>


                    <li <?php $current = ( in_array($controllerName, array('factorclassification'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/factorClassification')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.FACTOR_CLASSIFICATION'); ?></span>
                        </a>
                    </li>

                    <li <?php $current = ( in_array($controllerName, array('markingfactors'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/markingFactors')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.MARKING_FACTORS'); ?></span>
                        </a>
                    </li>

                    <li <?php $current = ( in_array($controllerName, array('mutualassessmentevent'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/mutualAssessmentEvent')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.MUTUAL_ASSESSMENT_EVENT'); ?></span>
                        </a>
                    </li>

                </ul>
            </li>
            <li <?php
            $current = ( in_array($controllerName, array('usergroup', 'rank', 'appointment', 'cmappointment', 'armsservice'
                        , 'cmgroup', 'dsgroup', 'wing', 'unit', 'user', 'cm', 'cmprofile', 'commissioningcourse', 'serviceappointment'
                        , 'milcourse', 'corpsregtbr', 'decoration', 'award', 'hobby'))) ? 'start active open' : '';
            ?>class="nav-item <?php echo e($current); ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-cogs"></i>
                    <span class="title"><?php echo app('translator')->get('label.ADMIN_SETUP'); ?></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li <?php $current = ( in_array($controllerName, array('usergroup'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/userGroup')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.USER_GROUP'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('rank'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/rank')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.RANK'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('appointment'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/appointment')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.APPOINTMENT'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('cmappointment'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/cmAppointment')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.CM_APPOINTMENT'); ?></span>
                        </a>
                    </li>
                    <!--                    <li <?php $current = ( in_array($controllerName, array('serviceappointment'))) ? 'start active open' : ''; ?>
                                            class="nav-item <?php echo e($current); ?>">
                                            <a href="<?php echo e(url('/serviceAppointment')); ?>" class="nav-link ">
                                                <span class="title"><?php echo app('translator')->get('label.SERVICE_APPOINTMENT'); ?></span>
                                            </a>
                                        </li>-->
                    <li
                        <?php $current = ( in_array($controllerName, array('commissioningcourse'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('/commissioningCourse')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.COMMISSIONING_COURSE'); ?></span>
                        </a>
                    </li>
                    <li
                        <?php $current = ( in_array($controllerName, array('milcourse'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                        <a href="<?php echo e(url('/milCourse')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.MIL_COURSE'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('armsservice'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/armsService')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.ARMS_SERVICES'); ?></span>
                        </a>
                    </li>

                    <!--                    <li <?php $current = ( in_array($controllerName, array('unit'))) ? 'start active open' : ''; ?>
                                            class="nav-item <?php echo e($current); ?>">
                                            <a href="<?php echo e(url('/unit')); ?>" class="nav-link ">
                                                <span class="title"><?php echo app('translator')->get('label.UNIT_FMN_INST'); ?></span>
                                            </a>
                                        </li>-->
                    <li <?php $current = ( in_array($controllerName, array('decoration'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/decoration')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.DECORATION'); ?></span>
                        </a>
                    </li>
                    <!--                    <li <?php $current = ( in_array($controllerName, array('award'))) ? 'start active open' : ''; ?>
                                            class="nav-item <?php echo e($current); ?>">
                                            <a href="<?php echo e(url('/award')); ?>" class="nav-link ">
                                                <span class="title"><?php echo app('translator')->get('label.AWARD'); ?></span>
                                            </a>
                                        </li>-->
                    <li <?php $current = ( in_array($controllerName, array('hobby'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/hobby')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.HOBBY'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('cmgroup'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/cmGroup')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.CM_GROUP'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('dsgroup'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/dsGroup')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.DS_GROUP'); ?></span>
                        </a>
                    </li>


                    <li <?php $current = ( in_array($controllerName, array('wing'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/wing')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.WING'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('user'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/user')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.USER'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('cm'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/cm')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.CM'); ?></span>
                        </a>
                    </li>


                </ul>
            </li>

            <?php
            $current = (in_array($controllerName, array('syntosubsyn', 'syntocourse', 'cmgrouptocourse', 'dsgrouptocourse', 'cmtosyn', 'citowing'
                        , 'dstosyn', 'termtoevent', 'termtosubevent', 'termtosubsubevent', 'termtosubsubsubevent'
                        , 'cmtosyn', 'cmtosubsyn', 'eventtosubevent', 'eventtosubsubevent', 'eventgrouptocourse'
                        , 'eventtosubsubsubevent', 'maeventtocourse', 'termtomaevent', 'eventtoapptmatrix'
                        , 'markinggroup', 'cmgroupmembertemplate', 'dsgroupmembertemplate'))) ? 'start active open' : '';
            ?>
            <li class="nav-item <?php echo e($current); ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-cog"></i>
                    <span class="title"><?php echo app('translator')->get('label.RELATIONSHIP_SETUP'); ?></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">

                    <li <?php $current = ( in_array($controllerName, array('syntocourse'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/synToCourse')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.SYN_TO_COURSE'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('syntosubsyn'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/synToSubSyn')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.SYN_TO_SUB_SYN'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('cmgrouptocourse'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/cmGroupToCourse')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.CM_GROUP_TO_COURSE'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('cmgroupmembertemplate'))) ? 'start active open' : ''; ?> 
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/cmGroupMemberTemplate')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.CM_GROUP_MEMBER_TEMPLATE'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('dsgrouptocourse'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/dsGroupToCourse')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.DS_GROUP_TO_COURSE'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('dsgroupmembertemplate'))) ? 'start active open' : ''; ?> 
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/dsGroupMemberTemplate')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.DS_GROUP_MEMBER_TEMPLATE'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('cmtosyn'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/cmToSyn')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.CM_TO_SYN'); ?></span>
                        </a>
                    </li>
                    <!--                    <li <?php $current = ( in_array($controllerName, array('cmtosubsyn'))) ? 'start active open' : ''; ?>
                                            class="nav-item <?php echo e($current); ?>">
                                            <a href="<?php echo e(url('/cmToSubSyn')); ?>" class="nav-link ">
                                                <span class="title"><?php echo app('translator')->get('label.CM_TO_SUB_SYN'); ?></span>
                                            </a>
                                        </li>            -->
                    <!--                    <li <?php $current = ( in_array($controllerName, array('citowing'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                                            <a href="<?php echo e(url('ciToWing')); ?>" class="nav-link ">
                                                <span class="title"><?php echo app('translator')->get('label.CI_TO_WING'); ?></span>
                                            </a>
                                        </li>-->

<!--                    <li <?php $current = ( in_array($controllerName, array('dstosyn')) && ($routeName == 'dstosyn' )) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
    <a href="<?php echo e(url('dsToSyn')); ?>" class="nav-link ">
        <span class="title"><?php echo app('translator')->get('label.DS_TO_SYN'); ?></span>
    </a>
</li>-->

                    <li <?php $current = ( in_array($controllerName, array('eventtosubevent'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('eventToSubEvent')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.EVENT_TO_SUB_EVENT'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('eventtosubsubevent'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('eventToSubSubEvent')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.EVENT_TO_SUB_SUB_EVENT'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('eventtosubsubsubevent'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('eventToSubSubSubEvent')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.EVENT_TO_SUB_SUB_SUB_EVENT'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('eventgrouptocourse'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/eventGroupToCourse')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.EVENT_GROUP_TO_COURSE'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('termtoevent'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('termToEvent')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.TERM_TO_EVENT'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('termtosubevent'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('termToSubEvent')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.TERM_TO_SUB_EVENT'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('termtosubsubevent'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('termToSubSubEvent')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.TERM_TO_SUB_SUB_EVENT'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('termtosubsubsubevent'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('termToSubSubSubEvent')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.TERM_TO_SUB_SUB_SUB_EVENT'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('markinggroup'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('/markingGroup')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.ASSIGN_MARKING_GROUP'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('termtomaevent'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('termToMAEvent')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.RELATE_TERM_TO_MA_EVENT'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('eventtoapptmatrix'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('eventToApptMatrix')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.EVENT_TO_APPT_MATRIX'); ?></span>
                        </a>
                    </li>
                </ul>
            </li>

            <!--Start : Mks & Wt Destribution Menu-->
            <?php
            $current = (in_array($controllerName, array('cicomdtmoderationmarkinglimit', 'criteriawisewt'
                        , 'eventmkswt', 'subeventmkswt', 'subsubeventmkswt', 'subsubsubeventmkswt'))) ? 'start active open' : '';
            ?>
            <li class="nav-item <?php echo e($current); ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-book"></i>
                    <span class="title"><?php echo app('translator')->get('label.MARKS_WT_DISTRIBUTION'); ?></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li <?php $current = ( in_array($routeName, array('criteriawisewt'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('criteriaWiseWt')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.CRITERIA_WISE_WT'); ?></span>
                        </a>
                    </li> 
                    <li <?php $current = ( in_array($routeName, array('eventmkswt'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('eventMksWt')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.EVENT_MKS_WT'); ?></span>
                        </a>
                    </li> 
                    <li <?php $current = ( in_array($routeName, array('subeventmkswt'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('subEventMksWt')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.SUB_EVENT_MKS_WT'); ?></span>
                        </a>
                    </li> 
                    <li <?php $current = ( in_array($routeName, array('subsubeventmkswt'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('subSubEventMksWt')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.SUB_SUB_EVENT_MKS_WT'); ?></span>
                        </a>
                    </li> 
                    <li <?php $current = ( in_array($routeName, array('subsubsubeventmkswt'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('subSubSubEventMksWt')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.SUB_SUB_SUB_EVENT_MKS_WT'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($routeName, array('cicomdtmoderationmarkinglimit'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('ciComdtModerationMarkingLimit')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.CI_COMDT_MODERATION_MARKING_LIMIT'); ?></span>
                        </a>
                    </li> 
                </ul>
            </li>
            <!--End : Mks & Wt Destribution Menu-->

            <?php
            $current = (in_array($controllerName, array('mutualassessment'))) ? 'start active open' : '';
            ?>
            <li class="nav-item <?php echo e($current); ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-cog"></i>
                    <span class="title"><?php echo app('translator')->get('label.MUTUAL_ASSESSMENT'); ?></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li <?php $current = ( in_array($controllerName, array('mutualassessment')) && ($routeName != 'mutualassessment/importmarkingsheet')) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('mutualAssessment/markingSheet')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.GENERATE_MARKING_SHEET'); ?></span>
                        </a>
                    </li> 
                    <li <?php $current = ( in_array($controllerName, array('mutualassessment')) && ($routeName == 'mutualassessment/importmarkingsheet')) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('mutualAssessment/importMarkingSheet')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.IMPORT_MARKING_SHEET'); ?></span>
                        </a>
                    </li> 
                </ul>
            </li>

            <!--Start : Appt to CM Menu-->
            <li <?php $current = ( in_array($controllerName, array('appttocm'))) ? 'start active open' : ''; ?> class="nav-item <?php echo e($current); ?>">
                <a href="<?php echo e(url('apptToCm')); ?>" class="nav-link ">
                    <i class="fa fa-user-secret"></i>
                    <span class="title"><?php echo app('translator')->get('label.APPT_TO_CM'); ?></span>
                </a>
            </li>
            <!--End : Appt to CM Menu-->
            <?php endif; ?>

            <!-- Start:: Ds Access-->
            <?php if(in_array(Auth::user()->group_id,[4])): ?>
            <li <?php
            $current = ( in_array($controllerName, array('eventassessmentmarking'))) ? 'start active open' : '';
            ?>
                class="nav-item <?php echo e($current); ?>">
                <a href="<?php echo e(url('eventAssessmentMarking')); ?>" class="nav-link">
                    <i class="fa fa-book"></i>
                    <span class="title"><?php echo app('translator')->get('label.EVENT_ASSESSMENT'); ?></span>
                </a>
            </li>
            <?php endif; ?>
            <!-- End:: Ds Access-->
            <!-- Start:: Ci Access-->
            <?php if(in_array(Auth::user()->group_id,[3])): ?>
            <li <?php
            $current = ( in_array($controllerName, array('deligateciaccttods'))) ? 'start active open' : '';
            ?>
                class="nav-item <?php echo e($current); ?>">
                <a href="<?php echo e(url('deligateCiAcctToDs')); ?>" class="nav-link">
                    <i class="fa fa-gears"></i>
                    <span class="title"><?php echo app('translator')->get('label.DELIGATE_CI_ACCOUNT_TO_DS'); ?></span>
                </a>
            </li>
            <?php endif; ?>
            <!-- End:: Ci,Comdt Access-->

            <!-- Start:: deligated DS,Ci,Comdt Access-->
            <?php if(in_array(Auth::user()->group_id,[2,3]) || in_array(Auth::user()->id, $dsDeligationList)): ?>
            <?php if(in_array(Auth::user()->group_id,[3]) || in_array(Auth::user()->id, $dsDeligationList)): ?>
            <li <?php
            $current = ( in_array($controllerName, array('cimoderationmarking'))) ? 'start active open' : '';
            ?>
                class="nav-item <?php echo e($current); ?>">
                <a href="<?php echo e(url('ciModerationMarking')); ?>" class="nav-link">
                    <i class="fa fa-book"></i>
                    <?php if(in_array(Auth::user()->group_id,[3])): ?>
                    <span class="title"><?php echo app('translator')->get('label.MODERATION_MARKING'); ?></span>
                    <?php elseif(in_array(Auth::user()->id, $dsDeligationList)): ?>
                    <span class="title"><?php echo app('translator')->get('label.CI_MODERATION_MARKING'); ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <?php endif; ?>
            <!--            <li <?php
            $current = ( in_array($controllerName, array('comdtmoderationmarking'))) ? 'start active open' : '';
            ?>
                            class="nav-item <?php echo e($current); ?>">
                            <a href="<?php echo e(url('comdtModerationMarking')); ?>" class="nav-link">
                                <i class="fa fa-book"></i>
                                <?php if(in_array(Auth::user()->group_id,[2])): ?>
                                <span class="title"><?php echo app('translator')->get('label.MODERATION_MARKING'); ?></span>
                                <?php elseif(in_array(Auth::user()->group_id,[3]) || in_array(Auth::user()->id, $dsDeligationList)): ?>
                                <span class="title"><?php echo app('translator')->get('label.COMDT_MODERATION_MARKING'); ?></span>
                                <?php endif; ?>
                            </a>
                        </li>-->

            <?php if(in_array(Auth::user()->group_id,[3]) || in_array(Auth::user()->id, $dsDeligationList)): ?>
            <li <?php
            $current = ( in_array($controllerName, array('ciobsnmarking'))) ? 'start active open' : '';
            ?>
                class="nav-item <?php echo e($current); ?>">
                <a href="<?php echo e(url('ciObsnMarking')); ?>" class="nav-link">
                    <i class="fa fa-pencil"></i>
                    <?php if(in_array(Auth::user()->group_id,[3])): ?>
                    <span class="title"><?php echo app('translator')->get('label.OBSN_MARKING'); ?></span>
                    <?php elseif(in_array(Auth::user()->id, $dsDeligationList)): ?>
                    <span class="title"><?php echo app('translator')->get('label.CI_OBSN_MARKING'); ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <?php endif; ?>
            <li <?php
            $current = ( in_array($controllerName, array('comdtobsnmarking'))) ? 'start active open' : '';
            ?>
                class="nav-item <?php echo e($current); ?>">
                <a href="<?php echo e(url('comdtObsnMarking')); ?>" class="nav-link">
                    <i class="fa fa-pencil"></i>
                    <?php if(in_array(Auth::user()->group_id,[2])): ?>
                    <span class="title"><?php echo app('translator')->get('label.OBSN_MARKING'); ?></span>
                    <?php elseif(in_array(Auth::user()->group_id,[3]) || in_array(Auth::user()->id, $dsDeligationList)): ?>
                    <span class="title"><?php echo app('translator')->get('label.COMDT_OBSN_MARKING'); ?></span>
                    <?php endif; ?>
                </a>
            </li>

            <!--Start :: Unlock Request-->
            <li <?php
            $current = ( in_array($controllerName, array('unlockeventassessment', 'unlockcimoderationmarking'
                        , 'unlockcomdtmoderationmarking', 'unlockciobsnmarking', 'unlockcomdtobsnmarking'))) ? 'start active open' : '';
            ?>
                class="nav-item <?php echo e($current); ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-unlock"></i>
                    <span class="title"><?php echo app('translator')->get('label.UNLOCK_REQUEST'); ?></span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">

                    <?php if(in_array(Auth::user()->group_id, [3]) || in_array(Auth::user()->id, $dsDeligationList)): ?>
                    <li <?php $current = ( in_array($controllerName, array('unlockeventassessment'))) ? 'start active open' : '';
            ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('unlockEventAssessment')); ?>" class="nav-link nav-toggle">
                            <span class="title"><?php echo app('translator')->get('label.UNLOCK_EVENT_ASSESSMENT'); ?></span>
                        </a>   
                    </li>
                    <?php endif; ?>
                    <li <?php $current = ( in_array($controllerName, array('unlockcimoderationmarking'))) ? 'start active open' : '';
            ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('unlockCiModerationMarking')); ?>" class="nav-link nav-toggle">
                            <span class="title"><?php echo app('translator')->get('label.UNLOCK_CI_MODERATION_MARKING'); ?></span>
                        </a>   
                    </li>
<!--                    <li <?php $current = ( in_array($controllerName, array('unlockcomdtmoderationmarking'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('unlockComdtModerationMarking')); ?>" class="nav-link nav-toggle">
                            <span class="title"><?php echo app('translator')->get('label.UNLOCK_COMDT_MODERATION_MARKING'); ?></span>
                        </a>   
                    </li>-->
                    <li <?php $current = ( in_array($controllerName, array('unlockciobsnmarking'))) ? 'start active open' : '';
            ?> class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('unlockCiObsnMarking')); ?>" class="nav-link nav-toggle">
                            <span class="title"><?php echo app('translator')->get('label.UNLOCK_CI_OBSN_MARKING'); ?></span>
                        </a>   
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('unlockcomdtobsnmarking'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('unlockComdtObsnMarking')); ?>" class="nav-link nav-toggle">
                            <span class="title"><?php echo app('translator')->get('label.UNLOCK_COMDT_OBSN_MARKING'); ?></span>
                        </a>   
                    </li>

                </ul>
            </li> 
            <!--End :: Unlock Request-->

            <?php endif; ?>
            <!-- End:: deligated DS,Ci,Comdt Access-->



            <!-- Start:: Report-->

            <?php
            $current = (in_array($controllerName, array('mutualassessmentdetailedreport', 'mutualassessmentsummaryreport'
                        , 'eventlistreport', 'markinggroupsummaryreport', 'eventresultreport', 'eventresultcombinedreport'
                        , 'termresultreport', 'performanceanalysisreport', 'nominalrollreport', 'individualprofilereport'
                        , 'courseprogressiveresultreport', 'courseresultreport', 'armsservicewiseeventtrendreport'
                        , 'wingwiseeventtrendreport', 'commissioningcoursewiseeventtrendreport', 'cmgroupwiseeventtrendreport'
                        , 'armsservicewisesubeventtrendreport', 'wingwisesubeventtrendreport', 'commissioningcoursewisesubeventtrendreport'))) ? 'start active open' : '';
            ?>
            <li class="nav-item <?php echo e($current); ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-area-chart"></i>
                    <span class="title"><?php echo app('translator')->get('label.REPORT'); ?></span>
                    <span class="arrow"></span>
                </a>

                <ul class="sub-menu">
                    <li <?php $current = ( in_array($controllerName, array('nominalrollreport'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('nominalRollReport')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.NOMINAL_ROLL'); ?></span>
                        </a>
                    </li>
                    <?php if(in_array(Auth::user()->group_id,[1,3,4])): ?>
                    <li <?php $current = ( in_array($controllerName, array('mutualassessmentsummaryreport'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('mutualAssessmentSummaryReport')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.MUTUAL_ASSESSMENT'); ?>&nbsp;(<?php echo app('translator')->get('label.SUMMARY'); ?>)</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('mutualassessmentdetailedreport'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('mutualAssessmentDetailedReport')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.MUTUAL_ASSESSMENT'); ?>&nbsp;(<?php echo app('translator')->get('label.DETAILED'); ?>)</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <li <?php $current = ( in_array($controllerName, array('eventlistreport'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('eventListReport')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.EVENT_MKS_WT'); ?></span>
                        </a>
                    </li>
                    <?php if(in_array(Auth::user()->group_id,[1,3,4])): ?>
                    <li <?php $current = ( in_array($controllerName, array('markinggroupsummaryreport'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('markingGroupSummaryReport')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.MARKING_GROUP_SUMMARY'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('eventresultreport'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('eventResultReport')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.EVENT_RESULT'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('eventresultcombinedreport'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('eventResultCombinedReport')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.EVENT_RESULT_COMBINED'); ?></span>
                        </a>
                    </li>


                    <li <?php $current = ( in_array($controllerName, array('performanceanalysisreport'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('performanceAnalysisReport')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.PERFORMANCE_ANALYSIS'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <!--Start :: Event Trend Analysis-->
                    <li <?php
                    $current = ( in_array($controllerName, array('armsservicewiseeventtrendreport', 'wingwiseeventtrendreport'
                                , 'commissioningcoursewiseeventtrendreport'))) ? 'start active open' : '';
                    ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title"><?php echo app('translator')->get('label.EVENT_TREND_ANALYSIS'); ?></span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li <?php $current = ( in_array($controllerName, array('armsservicewiseeventtrendreport'))) ? 'start active open' : ''; ?>
                                class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('armsServiceWiseEventTrendReport')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.ARMS_SERVICE_WISE'); ?></span>
                                </a>
                            </li>
                            <li <?php $current = ( in_array($controllerName, array('wingwiseeventtrendreport'))) ? 'start active open' : ''; ?>
                                class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('wingWiseEventTrendReport')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.WING_WISE'); ?></span>
                                </a>
                            </li>
                            <li <?php $current = ( in_array($controllerName, array('commissioningcoursewiseeventtrendreport'))) ? 'start active open' : ''; ?>
                                class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('commissioningCourseWiseEventTrendReport')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.COMMISSIONING_COURSE_WISE'); ?></span>
                                </a>
                            </li>
                            <li <?php $current = ( in_array($controllerName, array('cmgroupwiseeventtrendreport'))) ? 'start active open' : ''; ?>
                                class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('cmGroupWiseEventTrendReport')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.CM_GROUP_WISE'); ?></span>
                                </a>
                            </li>
                        </ul>
                    </li> 
                    <!--End :: Event Trend Analysis-->
                    <!--Start :: Sub Event Trend Analysis-->
                    <li <?php
                    $current = ( in_array($controllerName, array('armsservicewisesubeventtrendreport', 'wingwisesubeventtrendreport'
                                , 'commissioningcoursewisesubeventtrendreport'))) ? 'start active open' : '';
                    ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <span class="title"><?php echo app('translator')->get('label.SUB_EVENT_TREND_ANALYSIS'); ?></span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li <?php $current = ( in_array($controllerName, array('armsservicewisesubeventtrendreport'))) ? 'start active open' : ''; ?>
                                class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('armsServiceWiseSubEventTrendReport')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.ARMS_SERVICE_WISE'); ?></span>
                                </a>
                            </li>
                            <li <?php $current = ( in_array($controllerName, array('wingwisesubeventtrendreport'))) ? 'start active open' : ''; ?>
                                class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('wingWiseSubEventTrendReport')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.WING_WISE'); ?></span>
                                </a>
                            </li>
                            <li <?php $current = ( in_array($controllerName, array('commissioningcoursewisesubeventtrendreport'))) ? 'start active open' : ''; ?>
                                class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('commissioningCourseWiseSubEventTrendReport')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.COMMISSIONING_COURSE_WISE'); ?></span>
                                </a>
                            </li>
                            <li <?php $current = ( in_array($controllerName, array('cmgroupwisesubeventtrendreport'))) ? 'start active open' : ''; ?>
                                class="nav-item <?php echo e($current); ?>">
                                <a href="<?php echo e(url('cmGroupWiseSubEventTrendReport')); ?>" class="nav-link ">
                                    <span class="title"><?php echo app('translator')->get('label.CM_GROUP_WISE'); ?></span>
                                </a>
                            </li>
                        </ul>
                    </li> 
                    <!--End :: Sub Event Trend Analysis-->
                    <li <?php $current = ( in_array($controllerName, array('termresultreport'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('termResultReport')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.TERM_RESULT'); ?></span>
                        </a>
                    </li>
                    <!-- Individual Profile Report -->
                    <li <?php $current = ( in_array($controllerName, array('individualprofilereport'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('individualProfileReport')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.INDIVIDUAL_PROFILE'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('courseprogressiveresultreport'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('courseProgressiveResultReport')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.COURSE_PROGRESSIVE_RESULT'); ?></span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('courseresultreport'))) ? 'start active open' : ''; ?>
                        class="nav-item <?php echo e($current); ?>">
                        <a href="<?php echo e(url('courseResultReport')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.COURSE_RESULT'); ?></span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- End:: Report-->

        </ul>
    </div>
</div>
<?php /**PATH E:\Xampp\htdocs\afwc\resources\views/layouts/default/sidebar.blade.php ENDPATH**/ ?>