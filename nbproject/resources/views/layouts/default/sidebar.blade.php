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
            <li <?php $current = ( in_array($controllerName, array('dashboard'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                <a href="{{url('/dashboard')}}" class="nav-link ">
                    <i class="icon-home"></i>
                    <span class="title"> @lang('label.DASHBOARD')</span>
                </a>
            </li>

            @if(Auth::user()->group_id == '1')
            <li <?php
            $current = ( in_array($controllerName, array('trainingyear', 'term', 'trade', 'module', 'subject'
                        , 'syndicate', 'subsyndicate', 'event', 'courseid', 'noofparticular', 'termtocourse'
                        , 'event', 'subevent', 'subsubevent', 'subsubsubevent', 'eventtree', 'eventgroup'
                        , 'gradingsystem', 'factorclassification', 'markingfactors', 'mutualassessmentevent'))) ? 'start active open' : '';
            ?>class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-calendar"></i>
                    <span class="title">@lang('label.TERMS_COURSE_SETUP')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li <?php $current = ( in_array($controllerName, array('trainingyear'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/trainingYear')}}" class="nav-link">
                            <span class="title">@lang('label.TRAINING_YEAR')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('term'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/term')}}" class="nav-link">
                            <span class="title">@lang('label.TERM')</span>
                        </a>
                    </li>
                    <li
                        <?php $current = ( in_array($controllerName, array('courseid'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/courseId')}}" class="nav-link ">
                            <span class="title">@lang('label.COURSE_ID')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('syndicate'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/syndicate')}}" class="nav-link">
                            <span class="title">@lang('label.SYN')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('subsyndicate'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/subSyndicate')}}" class="nav-link">
                            <span class="title">@lang('label.SUB_SYN')</span>
                        </a>
                    </li>

                    <li <?php $current = ( in_array($controllerName, array('termtocourse')) && ($routeName != 'termtocourse/activationorclosing')) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('/termToCourse')}}" class="nav-link ">
                            <span class="title">@lang('label.TERM_SCHEDULING')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('termtocourse')) && ($routeName == 'termtocourse/activationorclosing' )) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('termToCourse/activationOrClosing')}}" class="nav-link ">
                            <span class="title">@lang('label.TERM_SCHEDULING_ACTIVATION_CLOSING')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('event'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/event')}}" class="nav-link">
                            <span class="title">@lang('label.EVENT')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('subevent'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/subEvent')}}" class="nav-link">
                            <span class="title">@lang('label.SUB_EVENT')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('subsubevent'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/subSubEvent')}}" class="nav-link">
                            <span class="title">@lang('label.SUB_SUB_EVENT')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('subsubsubevent'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/subSubSubEvent')}}" class="nav-link">
                            <span class="title">@lang('label.SUB_SUB_SUB_EVENT')</span>
                        </a>
                    </li>
                    <!--                    <li <?php $current = ( in_array($controllerName, array('eventtree'))) ? 'start active open' : ''; ?>
                                            class="nav-item {{$current}}">
                                            <a href="{{url('/eventTree')}}" class="nav-link">
                                                <span class="title">@lang('label.EVENT_TREE')</span>
                                            </a>
                                        </li>-->
                    <li <?php $current = ( in_array($controllerName, array('eventgroup'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/eventGroup')}}" class="nav-link ">
                            <span class="title">@lang('label.EVENT_GROUP')</span>
                        </a>
                    </li>

                    <li <?php $current = ( in_array($controllerName, array('gradingsystem'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/gradingSystem')}}" class="nav-link">
                            <span class="title">@lang('label.GRADING_SYSTEM')</span>
                        </a>
                    </li>


                    <li <?php $current = ( in_array($controllerName, array('factorclassification'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/factorClassification')}}" class="nav-link">
                            <span class="title">@lang('label.FACTOR_CLASSIFICATION')</span>
                        </a>
                    </li>

                    <li <?php $current = ( in_array($controllerName, array('markingfactors'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/markingFactors')}}" class="nav-link">
                            <span class="title">@lang('label.MARKING_FACTORS')</span>
                        </a>
                    </li>

                    <li <?php $current = ( in_array($controllerName, array('mutualassessmentevent'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/mutualAssessmentEvent')}}" class="nav-link">
                            <span class="title">@lang('label.MUTUAL_ASSESSMENT_EVENT')</span>
                        </a>
                    </li>

                </ul>
            </li>
            <li <?php
            $current = ( in_array($controllerName, array('usergroup', 'rank', 'appointment', 'cmappointment', 'armsservice'
                        , 'cmgroup', 'dsgroup', 'wing', 'unit', 'user', 'cm', 'cmprofile', 'commissioningcourse', 'serviceappointment'
                        , 'milcourse', 'corpsregtbr', 'decoration', 'award', 'hobby'))) ? 'start active open' : '';
            ?>class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-cogs"></i>
                    <span class="title">@lang('label.ADMIN_SETUP')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li <?php $current = ( in_array($controllerName, array('usergroup'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/userGroup')}}" class="nav-link ">
                            <span class="title">@lang('label.USER_GROUP')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('rank'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/rank')}}" class="nav-link ">
                            <span class="title">@lang('label.RANK')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('appointment'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/appointment')}}" class="nav-link ">
                            <span class="title">@lang('label.APPOINTMENT')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('cmappointment'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/cmAppointment')}}" class="nav-link ">
                            <span class="title">@lang('label.CM_APPOINTMENT')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('serviceappointment'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/serviceAppointment')}}" class="nav-link ">
                            <span class="title">@lang('label.SERVICE_APPOINTMENT')</span>
                        </a>
                    </li>
                    <li
                        <?php $current = ( in_array($controllerName, array('commissioningcourse'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/commissioningCourse')}}" class="nav-link ">
                            <span class="title">@lang('label.COMMISSIONING_COURSE')</span>
                        </a>
                    </li>
                    <li
                        <?php $current = ( in_array($controllerName, array('milcourse'))) ? 'start active open' : ''; ?>class="nav-item {{$current}} nav-item ">
                        <a href="{{url('/milCourse')}}" class="nav-link ">
                            <span class="title">@lang('label.MIL_COURSE')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('armsservice'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/armsService')}}" class="nav-link ">
                            <span class="title">@lang('label.ARMS_SERVICES')</span>
                        </a>
                    </li>

                    <li <?php $current = ( in_array($controllerName, array('unit'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/unit')}}" class="nav-link ">
                            <span class="title">@lang('label.UNIT_FMN_INST')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('decoration'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/decoration')}}" class="nav-link ">
                            <span class="title">@lang('label.DECORATION')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('award'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/award')}}" class="nav-link ">
                            <span class="title">@lang('label.AWARD')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('hobby'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/hobby')}}" class="nav-link ">
                            <span class="title">@lang('label.HOBBY')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('cmgroup'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/cmGroup')}}" class="nav-link ">
                            <span class="title">@lang('label.CM_GROUP')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('dsgroup'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/dsGroup')}}" class="nav-link ">
                            <span class="title">@lang('label.DS_GROUP')</span>
                        </a>
                    </li>


                    <li <?php $current = ( in_array($controllerName, array('wing'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/wing')}}" class="nav-link">
                            <span class="title">@lang('label.WING')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('user'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/user')}}" class="nav-link ">
                            <span class="title">@lang('label.USER')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('cm'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/cm')}}" class="nav-link ">
                            <span class="title">@lang('label.CM')</span>
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
            <li class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-cog"></i>
                    <span class="title">@lang('label.RELATIONSHIP_SETUP')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">

                    <li <?php $current = ( in_array($controllerName, array('syntocourse'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/synToCourse')}}" class="nav-link ">
                            <span class="title">@lang('label.SYN_TO_COURSE')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('syntosubsyn'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/synToSubSyn')}}" class="nav-link ">
                            <span class="title">@lang('label.SYN_TO_SUB_SYN')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('cmgrouptocourse'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/cmGroupToCourse')}}" class="nav-link ">
                            <span class="title">@lang('label.CM_GROUP_TO_COURSE')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('cmgroupmembertemplate'))) ? 'start active open' : ''; ?> 
                        class="nav-item {{$current}}">
                        <a href="{{url('/cmGroupMemberTemplate')}}" class="nav-link ">
                            <span class="title">@lang('label.CM_GROUP_MEMBER_TEMPLATE')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('dsgrouptocourse'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/dsGroupToCourse')}}" class="nav-link ">
                            <span class="title">@lang('label.DS_GROUP_TO_COURSE')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('dsgroupmembertemplate'))) ? 'start active open' : ''; ?> 
                        class="nav-item {{$current}}">
                        <a href="{{url('/dsGroupMemberTemplate')}}" class="nav-link ">
                            <span class="title">@lang('label.DS_GROUP_MEMBER_TEMPLATE')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('cmtosyn'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/cmToSyn')}}" class="nav-link ">
                            <span class="title">@lang('label.CM_TO_SYN')</span>
                        </a>
                    </li>
                    <!--                    <li <?php $current = ( in_array($controllerName, array('cmtosubsyn'))) ? 'start active open' : ''; ?>
                                            class="nav-item {{$current}}">
                                            <a href="{{url('/cmToSubSyn')}}" class="nav-link ">
                                                <span class="title">@lang('label.CM_TO_SUB_SYN')</span>
                                            </a>
                                        </li>            -->
                    <!--                    <li <?php $current = ( in_array($controllerName, array('citowing'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                                            <a href="{{url('ciToWing')}}" class="nav-link ">
                                                <span class="title">@lang('label.CI_TO_WING')</span>
                                            </a>
                                        </li>-->

<!--                    <li <?php $current = ( in_array($controllerName, array('dstosyn')) && ($routeName == 'dstosyn' )) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
    <a href="{{url('dsToSyn')}}" class="nav-link ">
        <span class="title">@lang('label.DS_TO_SYN')</span>
    </a>
</li>-->

                    <li <?php $current = ( in_array($controllerName, array('eventtosubevent'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('eventToSubEvent')}}" class="nav-link ">
                            <span class="title">@lang('label.EVENT_TO_SUB_EVENT')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('eventtosubsubevent'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('eventToSubSubEvent')}}" class="nav-link ">
                            <span class="title">@lang('label.EVENT_TO_SUB_SUB_EVENT')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('eventtosubsubsubevent'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('eventToSubSubSubEvent')}}" class="nav-link ">
                            <span class="title">@lang('label.EVENT_TO_SUB_SUB_SUB_EVENT')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('eventgrouptocourse'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/eventGroupToCourse')}}" class="nav-link ">
                            <span class="title">@lang('label.EVENT_GROUP_TO_COURSE')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('termtoevent'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('termToEvent')}}" class="nav-link ">
                            <span class="title">@lang('label.TERM_TO_EVENT')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('termtosubevent'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('termToSubEvent')}}" class="nav-link ">
                            <span class="title">@lang('label.TERM_TO_SUB_EVENT')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('termtosubsubevent'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('termToSubSubEvent')}}" class="nav-link ">
                            <span class="title">@lang('label.TERM_TO_SUB_SUB_EVENT')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('termtosubsubsubevent'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('termToSubSubSubEvent')}}" class="nav-link ">
                            <span class="title">@lang('label.TERM_TO_SUB_SUB_SUB_EVENT')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('markinggroup'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('/markingGroup')}}" class="nav-link ">
                            <span class="title">@lang('label.ASSIGN_MARKING_GROUP')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('termtomaevent'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('termToMAEvent')}}" class="nav-link ">
                            <span class="title">@lang('label.RELATE_TERM_TO_MA_EVENT')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('eventtoapptmatrix'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                        <a href="{{url('eventToApptMatrix')}}" class="nav-link ">
                            <span class="title">@lang('label.EVENT_TO_APPT_MATRIX')</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!--Start : Mks & Wt Destribution Menu-->
            <?php
            $current = (in_array($controllerName, array('cicomdtmoderationmarkinglimit', 'criteriawisewt'
                        , 'eventmkswt', 'subeventmkswt', 'subsubeventmkswt', 'subsubsubeventmkswt'))) ? 'start active open' : '';
            ?>
            <li class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-book"></i>
                    <span class="title">@lang('label.MARKS_WT_DISTRIBUTION')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li <?php $current = ( in_array($routeName, array('criteriawisewt'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('criteriaWiseWt')}}" class="nav-link ">
                            <span class="title">@lang('label.CRITERIA_WISE_WT')</span>
                        </a>
                    </li> 
                    <li <?php $current = ( in_array($routeName, array('eventmkswt'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('eventMksWt')}}" class="nav-link ">
                            <span class="title">@lang('label.EVENT_MKS_WT')</span>
                        </a>
                    </li> 
                    <li <?php $current = ( in_array($routeName, array('subeventmkswt'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('subEventMksWt')}}" class="nav-link ">
                            <span class="title">@lang('label.SUB_EVENT_MKS_WT')</span>
                        </a>
                    </li> 
                    <li <?php $current = ( in_array($routeName, array('subsubeventmkswt'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('subSubEventMksWt')}}" class="nav-link ">
                            <span class="title">@lang('label.SUB_SUB_EVENT_MKS_WT')</span>
                        </a>
                    </li> 
                    <li <?php $current = ( in_array($routeName, array('subsubsubeventmkswt'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('subSubSubEventMksWt')}}" class="nav-link ">
                            <span class="title">@lang('label.SUB_SUB_SUB_EVENT_MKS_WT')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($routeName, array('cicomdtmoderationmarkinglimit'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('ciComdtModerationMarkingLimit')}}" class="nav-link ">
                            <span class="title">@lang('label.CI_COMDT_MODERATION_MARKING_LIMIT')</span>
                        </a>
                    </li> 
                </ul>
            </li>
            <!--End : Mks & Wt Destribution Menu-->

            <?php
            $current = (in_array($controllerName, array('mutualassessment'))) ? 'start active open' : '';
            ?>
            <li class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-cog"></i>
                    <span class="title">@lang('label.MUTUAL_ASSESSMENT')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li <?php $current = ( in_array($controllerName, array('mutualassessment')) && ($routeName != 'mutualassessment/importmarkingsheet')) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('mutualAssessment/markingSheet')}}" class="nav-link ">
                            <span class="title">@lang('label.GENERATE_MARKING_SHEET')</span>
                        </a>
                    </li> 
                    <li <?php $current = ( in_array($controllerName, array('mutualassessment')) && ($routeName == 'mutualassessment/importmarkingsheet')) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('mutualAssessment/importMarkingSheet')}}" class="nav-link ">
                            <span class="title">@lang('label.IMPORT_MARKING_SHEET')</span>
                        </a>
                    </li> 
                </ul>
            </li>

            <!--Start : Appt to CM Menu-->
            <li <?php $current = ( in_array($controllerName, array('appttocm'))) ? 'start active open' : ''; ?> class="nav-item {{$current}}">
                <a href="{{url('apptToCm')}}" class="nav-link ">
                    <i class="fa fa-user-secret"></i>
                    <span class="title">@lang('label.APPT_TO_CM')</span>
                </a>
            </li>
            <!--End : Appt to CM Menu-->
            @endif

            <!-- Start:: Ds Access-->
            @if(in_array(Auth::user()->group_id,[4]))
            <li <?php
            $current = ( in_array($controllerName, array('eventassessmentmarking'))) ? 'start active open' : '';
            ?>
                class="nav-item {{$current}}">
                <a href="{{ url('eventAssessmentMarking') }}" class="nav-link">
                    <i class="fa fa-book"></i>
                    <span class="title">@lang('label.EVENT_ASSESSMENT')</span>
                </a>
            </li>
            @endif
            <!-- End:: Ds Access-->

            <!-- Start:: Ci,Comdt Access-->
            @if(in_array(Auth::user()->group_id,[2,3]))
            @if(in_array(Auth::user()->group_id,[3]))
            <li <?php
            $current = ( in_array($controllerName, array('cimoderationmarking'))) ? 'start active open' : '';
            ?>
                class="nav-item {{$current}}">
                <a href="{{ url('ciModerationMarking') }}" class="nav-link">
                    <i class="fa fa-book"></i>
                    <span class="title">@lang('label.MODERATION_MARKING')</span>
                </a>
            </li>
            @endif
            <li <?php
            $current = ( in_array($controllerName, array('comdtmoderationmarking'))) ? 'start active open' : '';
            ?>
                class="nav-item {{$current}}">
                <a href="{{ url('comdtModerationMarking') }}" class="nav-link">
                    <i class="fa fa-book"></i>
                    @if(in_array(Auth::user()->group_id,[2]))
                    <span class="title">@lang('label.MODERATION_MARKING')</span>
                    @elseif(in_array(Auth::user()->group_id,[3]))
                    <span class="title">@lang('label.COMDT_MODERATION_MARKING')</span>
                    @endif
                </a>
            </li>
            @if(in_array(Auth::user()->group_id,[3]))
            <li <?php
            $current = ( in_array($controllerName, array('ciobsnmarking'))) ? 'start active open' : '';
            ?>
                class="nav-item {{$current}}">
                <a href="{{ url('ciObsnMarking') }}" class="nav-link">
                    <i class="fa fa-pencil"></i>
                    <span class="title">@lang('label.OBSN_MARKING')</span>
                </a>
            </li>
            @endif
            <li <?php
            $current = ( in_array($controllerName, array('comdtobsnmarking'))) ? 'start active open' : '';
            ?>
                class="nav-item {{$current}}">
                <a href="{{ url('comdtObsnMarking') }}" class="nav-link">
                    <i class="fa fa-pencil"></i>
                    @if(in_array(Auth::user()->group_id,[2]))
                    <span class="title">@lang('label.OBSN_MARKING')</span>
                    @elseif(in_array(Auth::user()->group_id,[3]))
                    <span class="title">@lang('label.COMDT_OBSN_MARKING')</span>
                    @endif
                </a>
            </li>
            @endif
            <!-- End:: Ci,Comdt Access-->

            <!--Start :: Unlock Request-->
            @if(in_array(Auth::user()->group_id, [1, 2, 3]))
            <li <?php
            $current = ( in_array($controllerName, array('unlockeventassessment', 'unlockcimoderationmarking'
                        , 'unlockcomdtmoderationmarking', 'unlockciobsnmarking', 'unlockcomdtobsnmarking'))) ? 'start active open' : '';
            ?>
                class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-unlock"></i>
                    <span class="title">@lang('label.UNLOCK_REQUEST')</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">

                    @if(in_array(Auth::user()->group_id, [3]))
                    <li <?php $current = ( in_array($controllerName, array('unlockeventassessment'))) ? 'start active open' : '';
            ?> class="nav-item {{$current}}">
                        <a href="{{url('unlockEventAssessment')}}" class="nav-link nav-toggle">
                            <span class="title">@lang('label.UNLOCK_EVENT_ASSESSMENT')</span>
                        </a>   
                    </li>
                    @endif
                    @if(in_array(Auth::user()->group_id, [2, 3]))
                    <li <?php $current = ( in_array($controllerName, array('unlockcimoderationmarking'))) ? 'start active open' : '';
            ?> class="nav-item {{$current}}">
                        <a href="{{url('unlockCiModerationMarking')}}" class="nav-link nav-toggle">
                            <span class="title">@lang('label.UNLOCK_CI_MODERATION_MARKING')</span>
                        </a>   
                    </li>
                    @endif
                    <li <?php $current = ( in_array($controllerName, array('unlockcomdtmoderationmarking'))) ? 'start active open' : ''; ?>class="nav-item {{$current}}">
                        <a href="{{url('unlockComdtModerationMarking')}}" class="nav-link nav-toggle">
                            <span class="title">@lang('label.UNLOCK_COMDT_MODERATION_MARKING')</span>
                        </a>   
                    </li>
                    @if(in_array(Auth::user()->group_id, [2, 3]))
                    <li <?php $current = ( in_array($controllerName, array('unlockciobsnmarking'))) ? 'start active open' : '';
            ?> class="nav-item {{$current}}">
                        <a href="{{url('unlockCiObsnMarking')}}" class="nav-link nav-toggle">
                            <span class="title">@lang('label.UNLOCK_CI_OBSN_MARKING')</span>
                        </a>   
                    </li>
                    @endif
                    <li <?php $current = ( in_array($controllerName, array('unlockcomdtobsnmarking'))) ? 'start active open' : ''; ?>class="nav-item {{$current}}">
                        <a href="{{url('unlockComdtObsnMarking')}}" class="nav-link nav-toggle">
                            <span class="title">@lang('label.UNLOCK_COMDT_OBSN_MARKING')</span>
                        </a>   
                    </li>

                </ul>
            </li> 
            @endif
            <!--End :: Unlock Request-->

            <!-- Start:: Report-->

            <?php
            $current = (in_array($controllerName, array('mutualassessmentdetailedreport', 'mutualassessmentsummaryreport'
                        , 'eventlistreport', 'markinggroupsummaryreport', 'eventresultreport'))) ? 'start active open' : '';
            ?>
            <li class="nav-item {{$current}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-area-chart"></i>
                    <span class="title">@lang('label.REPORT')</span>
                    <span class="arrow"></span>
                </a>
                <!-- Mutual Assessment Report -->

                <ul class="sub-menu">
                    <li <?php $current = ( in_array($controllerName, array('eventlistreport'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('eventListReport')}}" class="nav-link">
                            <span class="title">@lang('label.EVENT_MKS_WT')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('markinggroupsummaryreport'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('markingGroupSummaryReport')}}" class="nav-link ">
                            <span class="title">@lang('label.MARKING_GROUP_SUMMARY')</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('eventresultreport'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('eventResultReport')}}" class="nav-link ">
                            <span class="title">@lang('label.EVENT_RESULT')</span>
                        </a>
                    </li>
                    @if(in_array(Auth::user()->group_id,[1,2,3]))
                    <li <?php $current = ( in_array($controllerName, array('mutualassessmentsummaryreport'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('mutualAssessmentSummaryReport')}}" class="nav-link ">
                            <span class="title">@lang('label.MUTUAL_ASSESSMENT')&nbsp;(@lang('label.SUMMARY'))</span>
                        </a>
                    </li>
                    <li <?php $current = ( in_array($controllerName, array('mutualassessmentdetailedreport'))) ? 'start active open' : ''; ?>
                        class="nav-item {{$current}}">
                        <a href="{{url('mutualAssessmentDetailedReport')}}" class="nav-link ">
                            <span class="title">@lang('label.MUTUAL_ASSESSMENT')&nbsp;(@lang('label.DETAILED'))</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            <!-- End:: Report-->

        </ul>
    </div>
</div>
