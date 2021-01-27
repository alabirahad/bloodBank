<?php
$basePath = URL::to('/');
?>
<?php if(Request::get('view') == 'pdf' || Request::get('view') == 'print'): ?> 
<?php
if (Request::get('view') == 'pdf') {
    $basePath = base_path();
}
?>
<html>
    <head>
        <title><?php echo app('translator')->get('label.NDC_AMS_TITLE'); ?></title>
        <link rel="shortcut icon" href="<?php echo e($basePath); ?>/public/img/favicon_sint.png" />
        <meta charset="UTF-8">
        <link href="<?php echo e(asset('public/fonts/css.css?family=Open Sans')); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo e(asset('public/assets/global/plugins/font-awesome/css/font-awesome.min.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/plugins/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/css/components.min.css')); ?>" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/plugins/morris/morris.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/plugins/jqvmap/jqvmap/jqvmap.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/css/plugins.min.css')); ?>" rel="stylesheet" type="text/css" />


        <!--BEGIN THEME LAYOUT STYLES--> 
        <!--<link href="<?php echo e(asset('public/assets/layouts/layout/css/layout.min.css')); ?>" rel="stylesheet" type="text/css" />-->
        <link href="<?php echo e(asset('public/assets/layouts/layout/css/custom.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/css/custom.css')); ?>" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link href="<?php echo e(asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')); ?>" rel="stylesheet" type="text/css" /> 
        <link href="<?php echo e(asset('public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css')); ?>" rel="stylesheet" type="text/css" /> 

        <style type="text/css" media="print">
            @page  { size: landscape; }
            * {
                -webkit-print-color-adjust: exact !important; 
                color-adjust: exact !important; 
            }
        </style>

        <script src="<?php echo e(asset('public/assets/global/plugins/jquery.min.js')); ?>" type="text/javascript"></script>
    </head>
    <body>
        <div class="portlet-body">
            <div class="row text-center">
                <div class="col-md-12 text-center">
                    <img width="500" height="auto" src="<?php echo e($basePath); ?>/public/img/sint_ams_logo.jpg" alt=""/>
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-md-12">
                    <div class="text-center bold uppercase">
                        <span class="header"><?php echo app('translator')->get('label.MARKING_GROUP_SUMMARY'); ?></span>
                    </div>
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-md-12">
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
                            <?php echo e(__('label.TRAINING_YEAR')); ?> : <strong><?php echo e(!empty($activeTrainingYearList[Request::get('training_year_id')]) && Request::get('training_year_id') != 0 ? $activeTrainingYearList[Request::get('training_year_id')] : __('label.N_A')); ?> |</strong>
                            <?php echo e(__('label.COURSE')); ?> : <strong><?php echo e(!empty($courseList[Request::get('course_id')]) && Request::get('course_id') != 0 ? $courseList[Request::get('course_id')] : __('label.N_A')); ?> |</strong>
                            <?php echo e(__('label.TERM')); ?> : <strong><?php echo e(!empty($termList[Request::get('term_id')]) && Request::get('term_id') != 0 ? $termList[Request::get('term_id')] : __('label.N_A')); ?> |</strong>
                            <?php echo e(__('label.EVENT')); ?> : <strong><?php echo e(!empty($eventList[Request::get('event_id')]) && Request::get('event_id') != 0 ? $eventList[Request::get('event_id')] : __('label.N_A')); ?> |</strong>
                            <?php if(!empty($subEventList[Request::get('sub_event_id')]) && Request::get('sub_event_id') != 0): ?>
                            <?php echo e(__('label.SUB_EVENT')); ?> : <strong><?php echo e($subEventList[Request::get('sub_event_id')]); ?> |</strong>
                            <?php endif; ?>
                            <?php if(!empty($subSubEventList[Request::get('sub_sub_event_id')]) && Request::get('sub_sub_event_id') != 0): ?>
                            <?php echo e(__('label.SUB_SUB_EVENT')); ?> : <strong><?php echo e($subSubEventList[Request::get('sub_sub_event_id')]); ?> |</strong>
                            <?php endif; ?>
                            <?php if(!empty($subSubSubEventList[Request::get('sub_sub_sub_event_id')]) && Request::get('sub_sub_sub_event_id') != 0): ?>
                            <?php echo e(__('label.SUB_SUB_SUB_EVENT')); ?> : <strong><?php echo e($subSubSubEventList[Request::get('sub_sub_sub_event_id')]); ?> |</strong>
                            <?php endif; ?>
                            <?php echo e(__('label.TOTAL_NO_OF_CM')); ?> : <strong><?php echo e(!empty($cmArr) ? sizeof($cmArr) : 0); ?> |</strong>
                            <?php echo e(__('label.TOTAL_NO_OF_DS')); ?> : <strong><?php echo e(!empty($dsArr) ? sizeof($dsArr) : 0); ?></strong>

                        </h5>
                    </div>
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover table-head-fixer-color" id="dataTable">
                        <thead>
                            <tr>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.SL'); ?></th>
                                <th class="vcenter"><?php echo app('translator')->get('label.MARKING_GROUP'); ?></th>
                                <th class="vcenter"><?php echo app('translator')->get('label.ASSIGNED_CM'); ?></th>
                                <th class="vcenter"><?php echo app('translator')->get('label.ASSIGNED_DS'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($markingGroupArr)): ?>
                            <?php
                            $sl = 0;
                            ?>
                            <?php $__currentLoopData = $markingGroupArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $markingGroupId => $markingGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <tr>
                                <td class="text-center"><?php echo ++$sl; ?></td>
                                <td class=""><?php echo $markingGroup; ?></td>
                                <td class="vcenter">
                                    <?php if(!empty($cmArr[$markingGroupId])): ?>
                                    <?php
                                    $cmSl = 0;
                                    ?>
                                    <?php $__currentLoopData = $cmArr[$markingGroupId]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cmId => $cm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    $cmName = $cm['cm_name'] ?? null;
                                    $cmPhoto = $cm['photo'] ?? null;
                                    ?>

                                    <div class="margin-bottom-2">
                                        <span><?php echo e(++$cmSl); ?>. </span>
                                        <?php
                                        if (!empty($cmPhoto && File::exists('public/uploads/cm/' . $cmPhoto))) {
                                            ?>
                                            <img width="22" height="25" src="<?php echo e($basePath); ?>/public/uploads/cm/<?php echo e($cmPhoto); ?>" alt="<?php echo e($cmName); ?>"/>&nbsp;&nbsp;
                                        <?php } else { ?>
                                            <img width="22" height="25" src="<?php echo e($basePath); ?>/public/img/unknown.png" alt="<?php echo e($cmName); ?>"/>&nbsp;&nbsp;
                                            <?php
                                        }
                                        ?>  
                                        <?php echo $cm['cm_name'] ?? ''; ?>

                                    </div>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if(!empty($dsArr[$markingGroupId])): ?>
                                    <?php
                                    $dsSl = 0;
                                    ?>
                                    <?php $__currentLoopData = $dsArr[$markingGroupId]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dsId => $ds): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                    <?php
                                    $dsName = $ds['ds_name'] ?? null;
                                    $dsPhoto = $ds['photo'] ?? null;
                                    ?>
                                    <div class="margin-bottom-2">
                                        <span><?php echo e(++$dsSl); ?>. </span>
                                        <?php
                                        if (!empty($dsPhoto && File::exists('public/uploads/user/' . $dsPhoto))) {
                                            ?>
                                            <img width="22" height="25" src="<?php echo e($basePath); ?>/public/uploads/user/<?php echo e($dsPhoto); ?>" alt="<?php echo e($dsName); ?>"/>&nbsp;&nbsp;
                                        <?php } else { ?>
                                            <img width="22" height="25" src="<?php echo e($basePath); ?>/public/img/unknown.png" alt="<?php echo e($dsName); ?>"/>&nbsp;&nbsp;
                                            <?php
                                        }
                                        ?>
                                        <?php echo $ds['ds_name'] ?? ''; ?>

                                    </div>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="4"><strong><?php echo app('translator')->get('label.NO_MARKING_GROUP_IS_ASSIGNED_TO_THIS_EVENT'); ?></strong></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
        <table class="no-border margin-top-10">
            <tr>
                <td class="no-border text-left">
                    <?php echo app('translator')->get('label.GENERATED_ON'); ?> <?php echo '<strong>'.Helper::formatDate(date('Y-m-d H:i:s')).'</strong> by <strong>'.Auth::user()->full_name.'</strong>'; ?>.
                </td>
                <td class="no-border text-right">
                    <strong><?php echo app('translator')->get('label.GENERATED_FROM_AFWC'); ?></strong>
                </td>
            </tr>
        </table>

        <script src="<?php echo e(asset('public/assets/global/plugins/bootstrap/js/bootstrap.min.js')); ?>"  type="text/javascript"></script>
        <script src="<?php echo e(asset('public/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')); ?>" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->


        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?php echo e(asset('public/assets/global/scripts/app.min.js')); ?>" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->

        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <!--<script src="<?php echo e(asset('public/assets/layouts/layout/scripts/layout.min.js')); ?>" type="text/javascript"></script>-->



        <!--<script src="<?php echo e(asset('public/js/apexcharts.min.js')); ?>" type="text/javascript"></script>-->


        <script>
document.addEventListener("DOMContentLoaded", function (event) {
    window.print();
});
        </script>
    </body>
</html>
<?php else: ?>
<html>
    <head>
        <link href="<?php echo e(asset('public/fonts/css.css?family=Open Sans')); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo e(asset('public/assets/global/plugins/font-awesome/css/font-awesome.min.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/plugins/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/css/components.min.css')); ?>" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/plugins/morris/morris.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/plugins/jqvmap/jqvmap/jqvmap.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/assets/global/css/plugins.min.css')); ?>" rel="stylesheet" type="text/css" />


        <!--BEGIN THEME LAYOUT STYLES--> 
        <!--<link href="<?php echo e(asset('public/assets/layouts/layout/css/layout.min.css')); ?>" rel="stylesheet" type="text/css" />-->
        <link href="<?php echo e(asset('public/assets/layouts/layout/css/custom.css')); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo e(asset('public/css/custom.css')); ?>" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link href="<?php echo e(asset('public/assets/layouts/layout/css/downloadPdfPrint/print.css')); ?>" rel="stylesheet" type="text/css" /> 
        <link href="<?php echo e(asset('public/assets/layouts/layout/css/downloadPdfPrint/printInvoice.css')); ?>" rel="stylesheet" type="text/css" /> 
    </head>
    <body>
        <table class="no-border margin-top-10">
            <tr>
                <td class="" colspan="8">
                    <img width="500" height="auto" src="public/img/sint_ams_logo.jpg" alt=""/>
                </td>
            </tr>
            <tr>
                <td class="no-border text-center" colspan="8">
                    <strong><?php echo __('label.MARKING_GROUP_SUMMARY'); ?></strong>
                </td>
            </tr>
        </table>
        <table class="no-border margin-top-10">
            <tr>
                <td class="" colspan="8">
                    <h5 style="padding: 10px;">
                        <?php echo e(__('label.TRAINING_YEAR')); ?> : <strong><?php echo e(!empty($activeTrainingYearList[Request::get('training_year_id')]) && Request::get('training_year_id') != 0 ? $activeTrainingYearList[Request::get('training_year_id')] : __('label.N_A')); ?> |</strong>
                        <?php echo e(__('label.COURSE')); ?> : <strong><?php echo e(!empty($courseList[Request::get('course_id')]) && Request::get('course_id') != 0 ? $courseList[Request::get('course_id')] : __('label.N_A')); ?> |</strong>
                        <?php echo e(__('label.TERM')); ?> : <strong><?php echo e(!empty($termList[Request::get('term_id')]) && Request::get('term_id') != 0 ? $termList[Request::get('term_id')] : __('label.N_A')); ?> |</strong>
                        <?php echo e(__('label.EVENT')); ?> : <strong><?php echo e(!empty($eventList[Request::get('event_id')]) && Request::get('event_id') != 0 ? $eventList[Request::get('event_id')] : __('label.N_A')); ?> |</strong>
                        <?php if(!empty($subEventList[Request::get('sub_event_id')]) && Request::get('sub_event_id') != 0): ?>
                        <?php echo e(__('label.SUB_EVENT')); ?> : <strong><?php echo e($subEventList[Request::get('sub_event_id')]); ?> |</strong>
                        <?php endif; ?>
                        <?php if(!empty($subSubEventList[Request::get('sub_sub_event_id')]) && Request::get('sub_sub_event_id') != 0): ?>
                        <?php echo e(__('label.SUB_SUB_EVENT')); ?> : <strong><?php echo e($subSubEventList[Request::get('sub_sub_event_id')]); ?> |</strong>
                        <?php endif; ?>
                        <?php if(!empty($subSubSubEventList[Request::get('sub_sub_sub_event_id')]) && Request::get('sub_sub_sub_event_id') != 0): ?>
                        <?php echo e(__('label.SUB_SUB_SUB_EVENT')); ?> : <strong><?php echo e($subSubSubEventList[Request::get('sub_sub_sub_event_id')]); ?> |</strong>
                        <?php endif; ?>
                        </br>
                        <?php echo e(__('label.TOTAL_NO_OF_CM')); ?> : <strong><?php echo e(!empty($cmArr) ? sizeof($cmArr) : 0); ?> |</strong>
                        <?php echo e(__('label.TOTAL_NO_OF_DS')); ?> : <strong><?php echo e(!empty($dsArr) ? sizeof($dsArr) : 0); ?></strong>

                    </h5>
                </td>
            </tr>
        </table>
        <table class="table table-bordered table-hover table-head-fixer-color" id="dataTable">
            <thead>
                <tr>
                    <th class="vcenter text-center"><?php echo app('translator')->get('label.SL'); ?></th>
                    <th class="vcenter"><?php echo app('translator')->get('label.MARKING_GROUP'); ?></th>
                    <th class="vcenter"><?php echo app('translator')->get('label.CM'); ?></th>
                    <th class="vcenter"><?php echo app('translator')->get('label.DS'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($markingGroupArr)): ?>
                <?php
                $sl = 0;
                ?>
                <?php $__currentLoopData = $markingGroupArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $markingGroupId => $markingGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <tr>
                    <td class="text-center"><?php echo ++$sl; ?></td>
                    <td class=""><?php echo $markingGroup; ?></td>
                    <td class="vcenter">
                        <?php if(!empty($cmArr[$markingGroupId])): ?>
                        <?php
                        $cmSl = 0;
                        ?>
                        <?php $__currentLoopData = $cmArr[$markingGroupId]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cmId => $cm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $cmName = $cm['cm_name'] ?? null;
                        $cmPhoto = $cm['photo'] ?? null;
                        ?>

                        <div class="margin-bottom-2">
                            <span><?php echo e(++$cmSl); ?>. </span>
                            <?php
                            if (!empty($cmPhoto && File::exists('public/uploads/cm/' . $cmPhoto))) {
                                ?>
                                <img width="22" height="25" src="<?php echo e(URL::to('/')); ?>/public/uploads/cm/<?php echo e($cmPhoto); ?>" alt="<?php echo e($cmName); ?>"/>&nbsp;&nbsp;
                            <?php } else { ?>
                                <img width="22" height="25" src="<?php echo e(URL::to('/')); ?>/public/img/unknown.png" alt="<?php echo e($cmName); ?>"/>&nbsp;&nbsp;
                                <?php
                            }
                            ?>  
                            <?php echo $cm['cm_name'] ?? ''; ?>

                        </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if(!empty($dsArr[$markingGroupId])): ?>
                        <?php
                        $dsSl = 0;
                        ?>
                        <?php $__currentLoopData = $dsArr[$markingGroupId]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dsId => $ds): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <?php
                        $dsName = $ds['ds_name'] ?? null;
                        $dsPhoto = $ds['photo'] ?? null;
                        ?>
                        <div class="margin-bottom-2">
                            <span><?php echo e(++$dsSl); ?>. </span>
                            <?php
                            if (!empty($dsPhoto && File::exists('public/uploads/user/' . $dsPhoto))) {
                                ?>
                                <img width="22" height="25" src="<?php echo e(URL::to('/')); ?>/public/uploads/user/<?php echo e($dsPhoto); ?>" alt="<?php echo e($dsName); ?>"/>&nbsp;&nbsp;
                            <?php } else { ?>
                                <img width="22" height="25" src="<?php echo e(URL::to('/')); ?>/public/img/unknown.png" alt="<?php echo e($dsName); ?>"/>&nbsp;&nbsp;
                                <?php
                            }
                            ?>
                            <?php echo $ds['ds_name'] ?? ''; ?>

                        </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                <tr>
                    <td colspan="4"><strong><?php echo app('translator')->get('label.NO_MARKING_GROUP_IS_ASSIGNED_TO_THIS_EVENT'); ?></strong></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <table class="no-border margin-top-10">
            <tr>
                <td class="no-border text-left">
                    <?php echo app('translator')->get('label.GENERATED_ON'); ?> <?php echo '<strong>'.Helper::formatDate(date('Y-m-d H:i:s')).'</strong> by <strong>'.Auth::user()->full_name.'</strong>'; ?>.
                </td>
                <td class="no-border text-right">
                    <strong><?php echo app('translator')->get('label.GENERATED_FROM_AFWC'); ?></strong>
                </td>
            </tr>
        </table>
    </body>
</html>
<?php endif; ?>
<?php /**PATH E:\Xampp\htdocs\afwc\resources\views/report/markingGroupSummary/print/index.blade.php ENDPATH**/ ?>