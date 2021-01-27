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
        <title><?php echo app('translator')->get('label.SINT_AMS_TITLE'); ?></title>
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
            /*@page  { size: landscape; }*/
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
                        <span class="header"><?php echo app('translator')->get('label.EVENT_MKS_WT'); ?></span>
                    </div>
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-md-12">
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
                            <?php echo e(__('label.TRAINING_YEAR')); ?> : <strong><?php echo e(!empty($activeTrainingYearInfo[Request::get('training_year_id')]) && Request::get('training_year_id') != 0 ? $activeTrainingYearInfo[Request::get('training_year_id')] : __('label.N_A')); ?> |</strong>
                            <?php echo e(__('label.COURSE')); ?> : <strong><?php echo e(!empty($courseList[Request::get('course_id')]) && Request::get('course_id') != 0 ? $courseList[Request::get('course_id')] : __('label.N_A')); ?> |</strong>
                            <?php echo e(__('label.TERM')); ?> : <strong><?php echo e(!empty($termList[Request::get('term_id')]) && Request::get('term_id') != 0 ? $termList[Request::get('term_id')] : __('label.N_A')); ?> </strong>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover table-head-fixer-color" id="dataTable">
                        <thead>
                            <tr>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                <th class="vcenter"><?php echo app('translator')->get('label.EVENT'); ?></th>
                                <th class="vcenter"><?php echo app('translator')->get('label.SUB_EVENT'); ?></th>
                                <th class="vcenter"><?php echo app('translator')->get('label.SUB_SUB_EVENT'); ?></th>
                                <th class="vcenter"><?php echo app('translator')->get('label.SUB_SUB_SUB_EVENT'); ?></th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.MKS_LIMIT'); ?></th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.HIGHEST_MKS_LIMIT'); ?></th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.LOWEST_MKS_LIMIT'); ?></th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.WT'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($eventMksWtArr['mks_wt'])): ?>
                            <?php $sl = 0; ?>
                            <?php $__currentLoopData = $eventMksWtArr['mks_wt']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eventId => $evInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="vcenter text-center" rowspan="<?php echo !empty($rowSpanArr['event'][$eventId]) ? $rowSpanArr['event'][$eventId] : 1; ?>"><?php echo ++$sl; ?></td>
                                <td class="vcenter"  rowspan="<?php echo !empty($rowSpanArr['event'][$eventId]) ? $rowSpanArr['event'][$eventId] : 1; ?>">
                                    <?php echo !empty($eventMksWtArr['event'][$eventId]['name']) ? $eventMksWtArr['event'][$eventId]['name'] : ''; ?>

                                </td>

                                <?php if(!empty($evInfo)): ?>
                                <?php $i = 0; ?>
                                <?php $__currentLoopData = $evInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subEventId => $subEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                if ($i > 0) {
                                    echo '<tr>';
                                }
                                ?>
                                <td class="vcenter"  rowspan="<?php echo !empty($rowSpanArr['sub_event'][$eventId][$subEventId]) ? $rowSpanArr['sub_event'][$eventId][$subEventId] : 1; ?>">
                                    <?php echo !empty($eventMksWtArr['event'][$eventId][$subEventId]['name']) ? $eventMksWtArr['event'][$eventId][$subEventId]['name'] : ''; ?>

                                </td>

                                <?php if(!empty($subEvInfo)): ?>
                                <?php $j = 0; ?>
                                <?php $__currentLoopData = $subEvInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subSubEventId => $subSubEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                if ($j > 0) {
                                    echo '<tr>';
                                }
                                ?>
                                <td class="vcenter"  rowspan="<?php echo !empty($rowSpanArr['sub_sub_event'][$eventId][$subEventId][$subSubEventId]) ? $rowSpanArr['sub_sub_event'][$eventId][$subEventId][$subSubEventId] : 1; ?>">
                                    <?php echo !empty($eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId]['name']) ? $eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId]['name'] : ''; ?>

                                </td>

                                <?php if(!empty($subSubEvInfo)): ?>
                                <?php $k = 0; ?>
                                <?php $__currentLoopData = $subSubEvInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subSubSubEventId => $subSubSubEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                if ($k > 0) {
                                    echo '<tr>';
                                }
                                ?>
                                <td class="vcenter">
                                    <?php echo !empty($eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['name']) ? $eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['name'] : ''; ?>

                                </td>
                                <?php
                                $eventMkslimit = !empty($subSubSubEvInfo['mks_limit']) ? Helper::numberFormat2Digit($subSubSubEvInfo['mks_limit']) : '--';
                                $eventHighestMkslimit = !empty($subSubSubEvInfo['highest_mks_limit']) ? Helper::numberFormat2Digit($subSubSubEvInfo['highest_mks_limit']) : '--';
                                $eventLowestMkslimit = !empty($subSubSubEvInfo['lowest_mks_limit']) ? Helper::numberFormat2Digit($subSubSubEvInfo['lowest_mks_limit']) : '--';
                                $eventWt = !empty($subSubSubEvInfo['wt']) ? Helper::numberFormat2Digit($subSubSubEvInfo['wt']) : '--';

                                $eventMkslimitTextAlign = !empty($subSubSubEvInfo['mks_limit']) ? 'right' : 'center';
                                $eventHighestMkslimitTextAlign = !empty($subSubSubEvInfo['highest_mks_limit']) ? 'right' : 'center';
                                $eventLowestMkslimitTextAlign = !empty($subSubSubEvInfo['lowest_mks_limit']) ? 'right' : 'center';
                                $eventWtTextAlign = !empty($subSubSubEvInfo['wt']) ? 'right' : 'center';
                                ?>
                                <td class="text-<?php echo e($eventMkslimitTextAlign); ?> width-80">
                                    <span class="width-inherit bold"><?php echo $eventMkslimit; ?></span>
                                </td>
                                <td class="text-<?php echo e($eventHighestMkslimitTextAlign); ?> width-80">
                                    <span class="width-inherit bold"><?php echo $eventHighestMkslimit; ?></span>
                                </td>
                                <td class="text-<?php echo e($eventLowestMkslimitTextAlign); ?> width-80">
                                    <span class="width-inherit bold"><?php echo $eventLowestMkslimit; ?></span>
                                </td>
                                <td class="text-<?php echo e($eventWtTextAlign); ?> width-80">
                                    <span class="width-inherit bold"><?php echo $eventWt; ?></span>
                                </td>

                                <?php
                                if ($i < ($rowSpanArr['event'][$eventId] - 1)) {
                                    if ($j < ($rowSpanArr['sub_event'][$eventId][$subEventId] - 1)) {
                                        if ($k < ($rowSpanArr['sub_sub_event'][$eventId][$subEventId][$subSubEventId] - 1)) {
                                            echo '</tr>';
                                        }
                                    }
                                }
                                $k++;
                                ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>

                                <?php
                                $j++;
                                ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>

                                <?php
                                $i++;
                                ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-right bold" colspan="5"> <?php echo app('translator')->get('label.TOTAL'); ?> </td>
                                <td class="text-right width-80">
                                    <span class="width-inherit bold"><?php echo !empty($eventMksWtArr['total_mks_limit']) ? Helper::numberFormat2Digit($eventMksWtArr['total_mks_limit']) : '0.00'; ?></span>
                                </td>
                                <td colspan="2"></td>
                                <td class="text-right width-80">
                                    <span class="width-inherit bold"><?php echo !empty($eventMksWtArr['total_wt']) ? Helper::numberFormat2Digit($eventMksWtArr['total_wt']) : '0.00'; ?></span>
                                </td>
                            </tr>
                            <?php else: ?>
                            <tr>
                                <td colspan="9"><?php echo app('translator')->get('label.NO_EVENT_IS_ASSIGNED_TO_THIS_TERM'); ?></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--footer-->
        <table class="no-border margin-top-10">
            <tr>
                <td class="no-border text-left">
                    <?php echo app('translator')->get('label.GENERATED_ON'); ?> <?php echo '<strong>'.Helper::formatDate(date('Y-m-d H:i:s')).'</strong> by <strong>'.Auth::user()->full_name.'</strong>'; ?>.
                </td>
                <td class="no-border text-right">
                    <strong><?php echo app('translator')->get('label.GENERATED_FROM_SINT'); ?></strong>
                </td>
            </tr>
        </table>


        <!--//end of footer-->
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
                <td class="" colspan="9">
                    <img width="500" height="auto" src="public/img/sint_ams_logo.jpg" alt=""/>
                </td>
            </tr>
            <tr>
                <td class="no-border text-center" colspan="9">
                    <strong><?php echo __('label.EVENT_MKS_WT'); ?></strong>
                </td>
            </tr>
        </table>
        <table class="no-border margin-top-10">
            <tr>
                <td class="" colspan="5">
                    <h5 style="padding: 10px;">
                        <?php echo e(__('label.TRAINING_YEAR')); ?> : <strong><?php echo e(!empty($activeTrainingYearInfo[Request::get('training_year_id')]) && Request::get('training_year_id') != 0 ? $activeTrainingYearInfo[Request::get('training_year_id')] : __('label.N_A')); ?> |</strong>
                        <?php echo e(__('label.COURSE')); ?> : <strong><?php echo e(!empty($courseList[Request::get('course_id')]) && Request::get('course_id') != 0 ? $courseList[Request::get('course_id')] : __('label.N_A')); ?> |</strong>
                        <?php echo e(__('label.TERM')); ?> : <strong><?php echo e(!empty($termList[Request::get('term_id')]) && Request::get('term_id') != 0 ? $termList[Request::get('term_id')] : __('label.N_A')); ?> </strong>
                    </h5>
                </td>
            </tr>
        </table>
        <table class="table table-bordered" id="dataTable">
            <thead>
                <tr>
                    <th class="vcenter text-center"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                    <th class="vcenter"><?php echo app('translator')->get('label.EVENT'); ?></th>
                    <th class="vcenter"><?php echo app('translator')->get('label.SUB_EVENT'); ?></th>
                    <th class="vcenter"><?php echo app('translator')->get('label.SUB_SUB_EVENT'); ?></th>
                    <th class="vcenter"><?php echo app('translator')->get('label.SUB_SUB_SUB_EVENT'); ?></th>
                    <th class="vcenter text-center"><?php echo app('translator')->get('label.MKS_LIMIT'); ?></th>
                    <th class="vcenter text-center"><?php echo app('translator')->get('label.HIGHEST_MKS_LIMIT'); ?></th>
                    <th class="vcenter text-center"><?php echo app('translator')->get('label.LOWEST_MKS_LIMIT'); ?></th>
                    <th class="vcenter text-center"><?php echo app('translator')->get('label.WT'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($eventMksWtArr['mks_wt'])): ?>
                <?php $sl = 0; ?>
                <?php $__currentLoopData = $eventMksWtArr['mks_wt']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eventId => $evInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="vcenter text-center" rowspan="<?php echo !empty($rowSpanArr['event'][$eventId]) ? $rowSpanArr['event'][$eventId] : 1; ?>"><?php echo ++$sl; ?></td>
                    <td class="vcenter"  rowspan="<?php echo !empty($rowSpanArr['event'][$eventId]) ? $rowSpanArr['event'][$eventId] : 1; ?>">
                        <?php echo !empty($eventMksWtArr['event'][$eventId]['name']) ? $eventMksWtArr['event'][$eventId]['name'] : ''; ?>

                    </td>

                    <?php if(!empty($evInfo)): ?>
                    <?php $i = 0; ?>
                    <?php $__currentLoopData = $evInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subEventId => $subEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                    if ($i > 0) {
                        echo '<tr>';
                    }
                    ?>
                    <td class="vcenter"  rowspan="<?php echo !empty($rowSpanArr['sub_event'][$eventId][$subEventId]) ? $rowSpanArr['sub_event'][$eventId][$subEventId] : 1; ?>">
                        <?php echo !empty($eventMksWtArr['event'][$eventId][$subEventId]['name']) ? $eventMksWtArr['event'][$eventId][$subEventId]['name'] : ''; ?>

                    </td>

                    <?php if(!empty($subEvInfo)): ?>
                    <?php $j = 0; ?>
                    <?php $__currentLoopData = $subEvInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subSubEventId => $subSubEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                    if ($j > 0) {
                        echo '<tr>';
                    }
                    ?>
                    <td class="vcenter"  rowspan="<?php echo !empty($rowSpanArr['sub_sub_event'][$eventId][$subEventId][$subSubEventId]) ? $rowSpanArr['sub_sub_event'][$eventId][$subEventId][$subSubEventId] : 1; ?>">
                        <?php echo !empty($eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId]['name']) ? $eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId]['name'] : ''; ?>

                    </td>

                    <?php if(!empty($subSubEvInfo)): ?>
                    <?php $k = 0; ?>
                    <?php $__currentLoopData = $subSubEvInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subSubSubEventId => $subSubSubEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                    if ($k > 0) {
                        echo '<tr>';
                    }
                    ?>
                    <td class="vcenter">
                        <?php echo !empty($eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['name']) ? $eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['name'] : ''; ?>

                    </td>
                    <?php
                    $eventMkslimit = !empty($subSubSubEvInfo['mks_limit']) ? Helper::numberFormat2Digit($subSubSubEvInfo['mks_limit']) : '--';
                    $eventHighestMkslimit = !empty($subSubSubEvInfo['highest_mks_limit']) ? Helper::numberFormat2Digit($subSubSubEvInfo['highest_mks_limit']) : '--';
                    $eventLowestMkslimit = !empty($subSubSubEvInfo['lowest_mks_limit']) ? Helper::numberFormat2Digit($subSubSubEvInfo['lowest_mks_limit']) : '--';
                    $eventWt = !empty($subSubSubEvInfo['wt']) ? Helper::numberFormat2Digit($subSubSubEvInfo['wt']) : '--';

                    $eventMkslimitTextAlign = !empty($subSubSubEvInfo['mks_limit']) ? 'right' : 'center';
                    $eventHighestMkslimitTextAlign = !empty($subSubSubEvInfo['highest_mks_limit']) ? 'right' : 'center';
                    $eventLowestMkslimitTextAlign = !empty($subSubSubEvInfo['lowest_mks_limit']) ? 'right' : 'center';
                    $eventWtTextAlign = !empty($subSubSubEvInfo['wt']) ? 'right' : 'center';
                    ?>
                    <td class="text-<?php echo e($eventMkslimitTextAlign); ?> width-80">
                        <span class="width-inherit bold"><?php echo $eventMkslimit; ?></span>
                    </td>
                    <td class="text-<?php echo e($eventHighestMkslimitTextAlign); ?> width-80">
                        <span class="width-inherit bold"><?php echo $eventHighestMkslimit; ?></span>
                    </td>
                    <td class="text-<?php echo e($eventLowestMkslimitTextAlign); ?> width-80">
                        <span class="width-inherit bold"><?php echo $eventLowestMkslimit; ?></span>
                    </td>
                    <td class="text-<?php echo e($eventWtTextAlign); ?> width-80">
                        <span class="width-inherit bold"><?php echo $eventWt; ?></span>
                    </td>

                    <?php
                    if ($i < ($rowSpanArr['event'][$eventId] - 1)) {
                        if ($j < ($rowSpanArr['sub_event'][$eventId][$subEventId] - 1)) {
                            if ($k < ($rowSpanArr['sub_sub_event'][$eventId][$subEventId][$subSubEventId] - 1)) {
                                echo '</tr>';
                            }
                        }
                    }
                    $k++;
                    ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>

                    <?php
                    $j++;
                    ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>

                    <?php
                    $i++;
                    ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="text-right bold" colspan="5"> <?php echo app('translator')->get('label.TOTAL'); ?> </td>
                    <td class="text-right width-80">
                        <span class="width-inherit bold"><?php echo !empty($eventMksWtArr['total_mks_limit']) ? Helper::numberFormat2Digit($eventMksWtArr['total_mks_limit']) : '0.00'; ?></span>
                    </td>
                    <td colspan="2"></td>
                    <td class="text-right width-80">
                        <span class="width-inherit bold"><?php echo !empty($eventMksWtArr['total_wt']) ? Helper::numberFormat2Digit($eventMksWtArr['total_wt']) : '0.00'; ?></span>
                    </td>
                </tr>
                <?php else: ?>
                <tr>
                    <td colspan="9"><?php echo app('translator')->get('label.NO_EVENT_IS_ASSIGNED_TO_THIS_TERM'); ?></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <!--footer-->
        <table class="no-border margin-top-10">
            <tr>
                <td class="no-border text-left" colspan="5">
                    <?php echo app('translator')->get('label.GENERATED_ON'); ?> <?php echo '<strong>'.Helper::formatDate(date('Y-m-d H:i:s')).'</strong> by <strong>'.Auth::user()->full_name.'</strong>'; ?>.
                </td>
                <td class="no-border text-right">
                    <strong><?php echo app('translator')->get('label.GENERATED_FROM_SINT'); ?></strong>
                </td>
            </tr>
        </table>
    </body>
</html>
<?php endif; ?><?php /**PATH E:\Xampp\htdocs\afwc\resources\views/report/eventList/print/index.blade.php ENDPATH**/ ?>