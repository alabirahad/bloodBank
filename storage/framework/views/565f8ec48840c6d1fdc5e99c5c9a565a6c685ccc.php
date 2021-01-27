<?php if(!empty($termToCourseArr)): ?>
<?php $__currentLoopData = $termToCourseArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $courseId => $courseInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php if(!empty($dsDeligationList) && array_key_exists($courseId, $dsDeligationList)): ?>
<?php if(in_array(Auth::user()->group_id, [3]) || $dsDeligationList[$courseId] == Auth::user()->id): ?>

<div class="row margin-bottom-10">
    <div class="col-md-12 text-center">
        <div class="alert alert-info alert-dismissable glow-info">
            <p>
                <strong>
                    <i class="fa fa-gears"></i> <?php echo __('label.CI_ACCOUNT_IS_DELIGATED_TO_DS', ['ds' => $dsApptList[$dsDeligationList[$courseId]]]); ?>.
                    <?php if(in_array(Auth::user()->group_id, [3])): ?>
                    <a class="quick-link-a-tag" href="<?php echo e(URL::to('/deligateCiAcctToDs')); ?>"><?php echo app('translator')->get('label.CLICK_HERE_TO_CHANGE_DELIGARTION'); ?>.</a>
                    <?php endif; ?>
                </strong>
            </p>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>
<div class="row margin-bottom-10">
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h2 class="course-title text-center bold">
            <?php echo app('translator')->get('label.COURSE'); ?>: <?php echo e(!empty($courseInfo['course']) ? $courseInfo['course'] : ''); ?>

        </h2>
        <?php
        $courseTenure = !empty($courseInfo['course_initial_date']) && !empty($courseInfo['course_termination_date']) ? Helper::formatDate($courseInfo['course_initial_date']) . ' - ' . Helper::formatDate($courseInfo['course_termination_date']) : '';
        ?>
        <h6 class="course-tenure text-center bold"><?php echo e($courseTenure); ?></h6>
        <div class="row margin-top-20">
            <?php $__currentLoopData = $courseInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $termId => $termInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(is_int($termId)): ?>
            <?php
            $class = 'default';
            $label = __('label.NOT_INITIATED');
            $percent = $termInfo['percent'];
            if ($termInfo['status'] == '0') {
                $class = 'gray-mint';
                $label = __('label.NOT_INITIATED');
            } else if ($termInfo['status'] == '1') {
                if ($termInfo['active'] == '0') {
                    $class = 'blue-hoki';
                    $label = __('label.INITIATED');
                } else if ($termInfo['active'] == '1') {
                    $class = 'green-sharp';
                    $label = __('label.ACTIVE');
                }
            } else if ($termInfo['status'] == '2') {
                $class = 'red-haze';
                $label = __('label.CLOSED');
            }
            ?>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 tooltips" data-html=true>
                <div class="dashboard-stat2 term-block term-block-<?php echo e($class); ?>">
                    <div class="display">
                        <div class="number">
                            <h4 class="font-<?php echo e($class); ?> bold">
                                <span><?php echo e(!empty($termInfo['term']) ? $termInfo['term'] : ''); ?></span>
                            </h4>
                            <?php
                            $termTenure = !empty($termInfo['initial_date']) && !empty($termInfo['termination_date']) ? Helper::formatDate($termInfo['initial_date']) . ' - ' . Helper::formatDate($termInfo['termination_date']) : '';
                            ?>
                            <span class="font-blue-oleo bold font-size-11"><?php echo e($termTenure); ?></span>
                        </div>
                    </div>
                    <div class="progress-info">
                        <div class="icon  bold text-right">
                            <i class="icon-pie-chart font-<?php echo e($class); ?> font-size-25"></i>
                        </div>
                        <div class="progress" style="background-color:white;" >
                            <span  style="width: <?php echo e($percent); ?>%;"  class="progress-bar progress-bar-success <?php echo e($class); ?>  bg-font-blue-oleo">
                                <span class="sr-only"><?php echo e($percent); ?>% progress</span>
                            </span>
                        </div>
                        <div class="status">
                            <div class="status-title font-<?php echo e($class); ?>"><?php echo e($label); ?></div>
                            <div class="status-number font-<?php echo e($class); ?>"><?php echo e(($percent > 100) ? 100 : $percent); ?>%</div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>


<div class="row">
    <!--Start :: CM participation (last 5 courses)-->
    <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase font-dark font-size-14">
                        <?php echo app('translator')->get('label.CM_PARTICIPATION_LAST_FIVE_COURSES'); ?>
                    </span>
                    <span class="caption-helper"></span>
                </div>
                <div class="actions">
                    <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                </div>
            </div>
            <div class="portlet-body">
                <div id="cmParticipationLast5Courses" style="width: 100%; height: 400px; margin: 0 auto;"></div>
            </div>
        </div>
    </div>
    <!--End :: CM participation (last 5 courses)-->
    <!--Start :: CM participation (wing wise)-->
    <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase font-dark font-size-14">
                        <?php echo app('translator')->get('label.CURRENT_COURSE_CM_PARTICIPATION_WING_WISE', ['course' => !empty($course->name)?$course->name.' ':'']); ?>
                    </span>
                    <span class="caption-helper"></span>
                </div>
                <div class="actions">
                    <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                </div>
            </div>
            <div class="portlet-body">
                <div id="cmParticipationWingWise" style="width: 100%; height: 400px; margin: 0 auto;"></div>
            </div>
        </div>
    </div>
    <!--End :: CM participation (wing wise)-->
    <!--Start :: CM participation (arms/service wise)-->
    <!--    <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark font-size-14">
                            <?php echo app('translator')->get('label.CURRENT_COURSE_CM_PARTICIPATION_ARMS_SERVICE_WISE', ['course' => !empty($course->name)?$course->name.' ':'']); ?>
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="cmParticipationArmsServiceWise" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>-->
    <!--End :: CM participation (arms/service wise)-->
    <!--Start :: CM participation (commissioning course wise)-->
    <!--    <div class="col-md-6 col-sm-12 col-xs-12 margin-top-10">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject bold uppercase font-dark font-size-14">
                            <?php echo app('translator')->get('label.CURRENT_COURSE_CM_PARTICIPATION_COMMISSIONING_COURSE_WISE', ['course' => !empty($course->name)?$course->name.' ':'']); ?>
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="cmParticipationCommissioningCourseWise" style="width: 100%; height: 400px; margin: 0 auto;"></div>
                </div>
            </div>
        </div>-->
    <!--End :: CM participation (commissioning course wise)-->
</div>


<script type="text/javascript" src="<?php echo e(asset('public/js/apexcharts.min.js')); ?>"></script>
<script>
$(function(){
//***************start :: cm participation last 5 courses**********//
var cmParticipationLast5CoursesOptions = {
chart: {
type: 'bar',
        height: 400,
        toolbar: {
        show: false
        }
},
        series: [{
        name: "<?php echo app('translator')->get('label.COURSES'); ?>",
                data: [
<?php
if (!empty($lastFiveCourseList)) {
    foreach ($lastFiveCourseList as $courseId => $courseName) {
        $noOfCm = !empty($courseWiseCmNoList[$courseId]) ? $courseWiseCmNoList[$courseId] : 0;
        ?>
                        "<?php echo e($noOfCm); ?>",
        <?php
    }
}
?>
                ]
        }],
        plotOptions: {
        bar: {
        horizontal: false,
                columnWidth: '15%',
                endingShape: 'rounded',
                distributed: true,
                dataLabels: {
                position: 'top', // top, center, bottom
                },
        },
        },
        colors: [ '#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555'],
        dataLabels: {
        enabled: false,
                enabledOnSeries: undefined,
                formatter: function (val) {
                return val
                },
                textAnchor: 'middle',
                distributed: true,
                offsetX: 0,
                offsetY: - 10,
                style: {
                fontSize: '12px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'bold',
                        colors: ['#E08283', '#C49F47', '#5E738B', '#7F6084', '#4B77BE', '#E35B5A', '#1BA39C', '#F2784B', '#369EAD', '#5E738B', '#9A12B3', '#E87E04', '#D91E18', '#8E44AD', '#555555']
                },
                background: {
                enabled: true,
                        foreColor: '#fff',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#fff',
                        opacity: 0.9,
                        dropShadow: {
                        enabled: false,
                                top: 1,
                                left: 1,
                                blur: 1,
                                color: '#000',
                                opacity: 0.45
                        }
                },
                dropShadow: {
                enabled: false,
                        top: 1,
                        left: 1,
                        blur: 1,
                        color: '#000',
                        opacity: 0.45
                }
        },
        legend: {
        show: false
        },
        stroke: {
        show: true,
                width: 2,
                colors: ['transparent']
        },
        xaxis: {
        labels: {
        show: true,
//                rotate: - 60,
//                rotateAlways: true,
//                hideOverlappingLabels: true,
                showDuplicates: false,
                trim: false,
                minHeight: undefined,
                maxHeight: 180,
                offsetX: 0,
                offsetY: 0,
                formatter: function (val) {
                return val;
                },
                format: undefined,
        },
                categories: [
<?php
if (!empty($lastFiveCourseList)) {
    foreach ($lastFiveCourseList as $courseId => $courseName) {
        echo "'$courseName', ";
    }
}
?>
                ],
                title: {
                text: "<?php echo app('translator')->get('label.COURSES'); ?>",
                        offsetX: 0,
                        offsetY: 0,
                        style: {
                        color: undefined,
                                fontSize: '11px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 700,
                                cssClass: 'apexcharts-xaxis-title',
                        },
                },
        },
        yaxis: {
        title: {
        text: "<?php echo app('translator')->get('label.NO_OF_CM'); ?>",
                offsetX: 0,
                offsetY: 0,
                style: {
                color: undefined,
                        fontSize: '11px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 700,
                        cssClass: 'apexcharts-xaxis-title',
                },
        }
        },
        fill: {
        type: 'gradient',
                gradient: {
                shade: 'light',
                        type: "horizontal",
                        shadeIntensity: 0.20,
                        gradientToColors: undefined,
                        inverseColors: true,
                        opacityFrom: 0.85,
                        opacityTo: 1.85,
                        stops: [85, 50, 100]
                },
        },
};
var cmParticipationLast5Courses = new ApexCharts(document.querySelector("#cmParticipationLast5Courses"), cmParticipationLast5CoursesOptions);
cmParticipationLast5Courses.render();
//***************end :: cm participation last 5 courses**********//
//***************start :: cm participation wing wise**********//
var cmParticipationWingWiseOptions = {
series: [
<?php
if (!empty($wingList)) {
    foreach ($wingList as $wingId => $wingName) {
        $noOfCm = !empty($wingWiseCmNoList[$wingId]) ? $wingWiseCmNoList[$wingId] : 0.00;
        echo $noOfCm . ',';
    }
}
?>
],
        labels: [
<?php
if (!empty($wingList)) {
    foreach ($wingList as $wingId => $wingName) {
        echo "'$wingName', ";
    }
}
?>
        ],
        chart: {
        width: 415,
                type: 'donut',
        },
        plotOptions: {
        pie: {
        startAngle: - 90,
                endAngle: 270
        }
        },
        colors: ["#1BA39C", "#203354", "#5C9BD1", "#8E44AD", "#525E64"],
        dataLabels: {
        enabled: true
        },
        fill: {
        type: 'gradient',
        },
        legend: {
        formatter: function(val, opts) {
        return val + ": " + opts.w.globals.series[opts.seriesIndex]
        }
        },
        title: {
        text: ''
        },
        responsive: [{
        breakpoint: 480,
                options: {
                chart: {
                width: 200
                },
                        legend: {
                        position: 'bottom'
                        }
                }
        }]
        };
var cmParticipationWingWise = new ApexCharts(document.querySelector("#cmParticipationWingWise"), cmParticipationWingWiseOptions);
cmParticipationWingWise.render();
//***************end :: cm participation wing wise**********//

});
</script><?php /**PATH E:\Xampp\htdocs\bloodBank\resources\views/admin/commonFeatures/courseBlock.blade.php ENDPATH**/ ?>