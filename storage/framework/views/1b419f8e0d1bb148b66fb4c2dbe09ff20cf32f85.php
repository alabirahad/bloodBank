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
                        <span class="header"><?php echo app('translator')->get('label.COURSE_RESULT'); ?></span>
                    </div>
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-md-12">
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
                            <?php echo e(__('label.TRAINING_YEAR')); ?> : <strong><?php echo e(!empty($activeTrainingYearList[Request::get('training_year_id')]) && Request::get('training_year_id') != 0 ? $activeTrainingYearList[Request::get('training_year_id')] : __('label.N_A')); ?> |</strong>
                            <?php echo e(__('label.COURSE')); ?> : <strong><?php echo e(!empty($courseList[Request::get('course_id')]) && Request::get('course_id') != 0 ? $courseList[Request::get('course_id')] : __('label.N_A')); ?> </strong>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover table-head-fixer-color">
                        <thead>
                            <tr>
                                <th class="text-center vcenter" rowspan="2"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.PERSONAL_NO'); ?></th>
                                <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.RANK'); ?></th>
                                <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.CM'); ?></th>
                                <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.PHOTO'); ?></th>
                                <?php if(!empty($termDataArr)): ?>
                                <?php $__currentLoopData = $termDataArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $termId => $termName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th class="text-center vcenter" colspan="5">
                                    <?php echo !empty($termName) ? $termName : ''; ?>

                                </th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                <th class="vcenter text-center" colspan="5"><?php echo app('translator')->get('label.TERM_AGGREGATED_TOTAL'); ?></th>
                                <th class="vcenter text-center" rowspan="2"><?php echo app('translator')->get('label.CI_OBSN'); ?></th>
                                <th class="vcenter text-center" rowspan="2"><?php echo app('translator')->get('label.COMDT_OBSN'); ?></th>
                                <th class="vcenter text-center" colspan="4"><?php echo app('translator')->get('label.FINAL'); ?></th>
                                <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.PERSONAL_NO'); ?></th>
                                <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.RANK'); ?></th>
                                <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.CM'); ?></th>
                                <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.PHOTO'); ?></th>
                            </tr>
                            <tr>
                                <?php if(!empty($termDataArr)): ?>
                                <?php $__currentLoopData = $termDataArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $termId => $termName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $termAggWtTotal = !empty($termAggWtTotal) ? $termAggWtTotal : 0;
                                $termAggWtTotal += $eventMksWtArr['total_wt'][$termId];
                                $finalWtLimit = $termAggWtTotal + (!empty($assignedObsnInfo->ci_obsn_wt) ? $assignedObsnInfo->ci_obsn_wt : '0.00') + (!empty($assignedObsnInfo->comdt_obsn_wt) ? $assignedObsnInfo->comdt_obsn_wt : '0.00');
                                ?>
                                <th class="vcenter text-center">
                                    <?php echo app('translator')->get('label.MKS'); ?>&nbsp;(<?php echo !empty($eventMksWtArr['total_mks_limit'][$termId]) ? Helper::numberFormat2Digit($eventMksWtArr['total_mks_limit'][$termId]) : '0.00'; ?>)
                                </th>
                                <th class="vcenter text-center">
                                    <?php echo app('translator')->get('label.WT'); ?>&nbsp;(<?php echo !empty($eventMksWtArr['total_wt'][$termId]) ? Helper::numberFormat2Digit($eventMksWtArr['total_wt'][$termId]) : '0.00'; ?>)
                                </th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.PERCENT'); ?></th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.GRADE'); ?></th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.POSITION'); ?></th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                <!--term aggregated total-->
                                <th class="vcenter text-center">
                                    <?php echo app('translator')->get('label.WT'); ?>&nbsp;(<?php echo !empty($eventMksWtArr['agg_total_mks_limit']) ? Helper::numberFormat2Digit($eventMksWtArr['agg_total_mks_limit']) : '0.00'; ?>)
                                </th>
                                <th class="vcenter text-center">
                                    <?php echo app('translator')->get('label.WT'); ?>&nbsp;(<?php echo !empty($eventMksWtArr['agg_total_wt_limit']) ? Helper::numberFormat2Digit($eventMksWtArr['agg_total_wt_limit']) : '0.00'; ?>)
                                </th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.PERCENT'); ?></th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.GRADE'); ?></th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.POSITION'); ?></th>

                                <!--final-->
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.WT'); ?>&nbsp;(<?php echo Helper::numberFormat2Digit($finalWtLimit); ?>)</th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.PERCENT'); ?></th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.GRADE'); ?></th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.POSITION'); ?></th>

                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $sl = 0;
                            $readonly = !empty($comdtObsnLockInfo) ? 'readonly' : '';
                            $givenWt = !empty($comdtObsnLockInfo) ? 'given-wt' : '';
                            ?>
                            <?php $__currentLoopData = $cmArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cmId => $cmInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            $cmName = (!empty($cmInfo['rank_name']) ? $cmInfo['rank_name'] . ' ' : '') . (!empty($cmInfo['full_name']) ? $cmInfo['full_name'] : '');
                            $synName = (!empty($cmInfo['syn_name']) ? $cmInfo['syn_name'] . ' ' : '') . (!empty($cmInfo['sub_syn_name']) ? '(' . $cmInfo['sub_syn_name'] . ')' : '');
                            ?>
                            <tr>
                                <td class="text-center vcenter"><?php echo ++$sl; ?></td>
                                <td class="vcenter width-80">
                                    <div class="width-inherit"><?php echo $cmInfo['personal_no'] ?? ''; ?></div>
                                </td>
                                <td class="vcenter width-80">
                                    <div class="width-inherit"><?php echo $cmInfo['rank_name'] ?? ''; ?></div>
                                </td>
                                <td class="vcenter width-150">
                                    <div class="width-inherit"><?php echo $cmInfo['full_name'] ?? ''; ?></div>
                                </td>
                                <td class="vcenter" width="50px">
                                    <?php if(!empty($cmInfo['photo']) && File::exists('public/uploads/cm/' . $cmInfo['photo'])): ?>
                                    <img width="50" height="60" src="<?php echo e($basePath); ?>/public/uploads/cm/<?php echo e($cmInfo['photo']); ?>" alt="<?php echo e($cmInfo['full_name'] ?? ''); ?>">
                                    <?php else: ?>
                                    <img width="50" height="60" src="<?php echo e($basePath); ?>/public/img/unknown.png" alt="<?php echo e($cmInfo['full_name'] ?? ''); ?>">
                                    <?php endif; ?>
                                </td>


                                <?php if(!empty($termDataArr)): ?>
                                <?php $__currentLoopData = $termDataArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $termId => $termName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $totalMksTextAlign = !empty($cmInfo['term_total'][$termId]['total_mks']) ? 'right' : 'center';
                                $totalWtTextAlign = !empty($cmInfo['term_total'][$termId]['total_wt']) ? 'right' : 'center';
                                $totalPercentageTextAlign = !empty($cmInfo['term_total'][$termId]['percentage']) ? 'right' : 'center';
                                ?>
                                <td class="text-<?php echo e($totalMksTextAlign); ?> vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($cmInfo['term_total'][$termId]['total_mks']) ? Helper::numberFormat2Digit($cmInfo['term_total'][$termId]['total_mks']) : '--'; ?></span>
                                </td>
                                <td class="text-<?php echo e($totalWtTextAlign); ?> vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($cmInfo['term_total'][$termId]['total_wt']) ? Helper::numberFormat2Digit($cmInfo['term_total'][$termId]['total_wt']) : '--'; ?></span>
                                </td>
                                <td class="text-<?php echo e($totalPercentageTextAlign); ?> vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($cmInfo['term_total'][$termId]['percentage']) ? Helper::numberFormat2Digit($cmInfo['term_total'][$termId]['percentage']) : '--'; ?></span>
                                </td>
                                <td class="text-center vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($cmInfo['term_total'][$termId]['total_grade']) ? $cmInfo['term_total'][$termId]['total_grade'] : ''; ?> </span>
                                </td>
                                <td class="text-center vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($cmInfo['term_total'][$termId]['position']) ? $cmInfo['term_total'][$termId]['position'] : ''; ?> </span>
                                </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>


                                <?php
                                $totalMksTextAlign = !empty($cmInfo['term_agg_total_mks']) ? 'right' : 'center';
                                $totalWtTextAlign = !empty($cmInfo['term_agg_total_wt']) ? 'right' : 'center';
                                $totalPercentageTextAlign = !empty($cmInfo['term_agg_percentage']) ? 'right' : 'center';
                                ?>
                                <td class="text-<?php echo e($totalMksTextAlign); ?> vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($cmInfo['term_agg_total_mks']) ? Helper::numberFormat2Digit($cmInfo['term_agg_total_mks']) : '--'; ?></span>
                                </td>
                                <td class="text-<?php echo e($totalWtTextAlign); ?> vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($cmInfo['term_agg_total_wt']) ? Helper::numberFormat2Digit($cmInfo['term_agg_total_wt']) : '--'; ?></span>
                                </td>
                                <td class="text-<?php echo e($totalPercentageTextAlign); ?> vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($cmInfo['term_agg_percentage']) ? Helper::numberFormat2Digit($cmInfo['term_agg_percentage']) : '--'; ?></span>
                                </td>
                                <td class="text-center vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($cmInfo['term_agg_total_grade']) ? $cmInfo['term_agg_total_grade'] : ''; ?> </span>
                                </td>
                                <td class="text-center vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($cmInfo['total_term_agg_position']) ? $cmInfo['total_term_agg_position'] : ''; ?> </span>
                                </td>

                                <!--ci comdt obsn-->
                                <?php
                                $ciObsnTextAlign = !empty($cmInfo['ci_obsn']) ? 'right' : 'center';
                                $comdtObsnTextAlign = !empty($cmInfo['comdt_obsn']) ? 'right' : 'center';
                                ?>
                                <td class="text-<?php echo e($ciObsnTextAlign); ?> vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($cmInfo['ci_obsn']) ? Helper::numberFormat2Digit($cmInfo['ci_obsn']) : '--'; ?></span>
                                </td>
                                <td class="text-<?php echo e($comdtObsnTextAlign); ?> vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($cmInfo['comdt_obsn']) ? Helper::numberFormat2Digit($cmInfo['comdt_obsn']) : '--'; ?></span>
                                </td>

                                <!--final-->
                                <?php
                                $finalWtTextAlign = !empty($cmInfo['final_wt']) ? 'right' : 'center';
                                $finalPerTextAlign = !empty($cmInfo['final_percentage']) ? 'right' : 'center';
                                ?>
                                <td class="text-<?php echo e($finalWtTextAlign); ?> vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($cmInfo['final_wt']) ? Helper::numberFormat2Digit($cmInfo['final_wt']) : '--'; ?></span>
                                </td>
                                <td class="text-<?php echo e($finalPerTextAlign); ?> vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($cmInfo['final_percentage']) ? Helper::numberFormat2Digit($cmInfo['final_percentage']) : '--'; ?></span>
                                </td>
                                <td class="text-center vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($cmInfo['final_grade']) ? $cmInfo['final_grade'] : ''; ?> </span>
                                </td>
                                <td class="text-center vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($cmInfo['final_position']) ? $cmInfo['final_position'] : ''; ?> </span>
                                </td>

                                <td class="vcenter width-80">
                                    <div class="width-inherit"><?php echo $cmInfo['personal_no'] ?? ''; ?></div>
                                </td>
                                <td class="vcenter width-80">
                                    <div class="width-inherit"><?php echo $cmInfo['rank_name'] ?? ''; ?></div>
                                </td>
                                <td class="vcenter width-150">
                                    <div class="width-inherit"><?php echo $cmInfo['full_name'] ?? ''; ?></div>
                                </td>
                                <td class="vcenter" width="50px">
                                    <?php if(!empty($cmInfo['photo']) && File::exists('public/uploads/cm/' . $cmInfo['photo'])): ?>
                                    <img width="50" height="60" src="<?php echo e($basePath); ?>/public/uploads/cm/<?php echo e($cmInfo['photo']); ?>" alt="<?php echo e($cmInfo['full_name'] ?? ''); ?>">
                                    <?php else: ?>
                                    <img width="50" height="60" src="<?php echo e($basePath); ?>/public/img/unknown.png" alt="<?php echo e($cmInfo['full_name'] ?? ''); ?>">
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <strong><?php echo __('label.COURSE_RESULT'); ?></strong>
                </td>
            </tr>
        </table>
        <table class="no-border margin-top-10">
            <tr>
                <td class="" colspan="8">
                    <h5 style="padding: 10px;">
                        <?php echo e(__('label.TRAINING_YEAR')); ?> : <strong><?php echo e(!empty($activeTrainingYearList[Request::get('training_year_id')]) && Request::get('training_year_id') != 0 ? $activeTrainingYearList[Request::get('training_year_id')] : __('label.N_A')); ?> |</strong>
                        <?php echo e(__('label.COURSE')); ?> : <strong><?php echo e(!empty($courseList[Request::get('course_id')]) && Request::get('course_id') != 0 ? $courseList[Request::get('course_id')] : __('label.N_A')); ?> </strong>
                    </h5>
                </td>
            </tr>
        </table>
        <table class="table table-bordered table-hover table-head-fixer-color">
            <thead>
                <tr>
                    <th class="text-center vcenter" rowspan="2"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                    <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.PERSONAL_NO'); ?></th>
                    <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.RANK'); ?></th>
                    <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.CM'); ?></th>
                    <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.PHOTO'); ?></th>
                    <?php if(!empty($termDataArr)): ?>
                    <?php $__currentLoopData = $termDataArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $termId => $termName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th class="text-center vcenter" colspan="5">
                        <?php echo !empty($termName) ? $termName : ''; ?>

                    </th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <th class="vcenter text-center" colspan="5"><?php echo app('translator')->get('label.TERM_AGGREGATED_TOTAL'); ?></th>
                    <th class="vcenter text-center" rowspan="2"><?php echo app('translator')->get('label.CI_OBSN'); ?></th>
                    <th class="vcenter text-center" rowspan="2"><?php echo app('translator')->get('label.COMDT_OBSN'); ?></th>
                    <th class="vcenter text-center" colspan="4"><?php echo app('translator')->get('label.FINAL'); ?></th>
                    <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.PERSONAL_NO'); ?></th>
                    <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.RANK'); ?></th>
                    <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.CM'); ?></th>
                    <th class="vcenter" rowspan="2"><?php echo app('translator')->get('label.PHOTO'); ?></th>
                </tr>
                <tr>
                    <?php if(!empty($termDataArr)): ?>
                    <?php $__currentLoopData = $termDataArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $termId => $termName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                    $termAggWtTotal = !empty($termAggWtTotal) ? $termAggWtTotal : 0;
                    $termAggWtTotal += $eventMksWtArr['total_wt'][$termId];
                    $finalWtLimit = $termAggWtTotal + (!empty($assignedObsnInfo->ci_obsn_wt) ? $assignedObsnInfo->ci_obsn_wt : '0.00') + (!empty($assignedObsnInfo->comdt_obsn_wt) ? $assignedObsnInfo->comdt_obsn_wt : '0.00');
                    ?>
                    <th class="vcenter text-center">
                        <?php echo app('translator')->get('label.MKS'); ?>&nbsp;(<?php echo !empty($eventMksWtArr['total_mks_limit'][$termId]) ? Helper::numberFormat2Digit($eventMksWtArr['total_mks_limit'][$termId]) : '0.00'; ?>)
                    </th>
                    <th class="vcenter text-center">
                        <?php echo app('translator')->get('label.WT'); ?>&nbsp;(<?php echo !empty($eventMksWtArr['total_wt'][$termId]) ? Helper::numberFormat2Digit($eventMksWtArr['total_wt'][$termId]) : '0.00'; ?>)
                    </th>
                    <th class="vcenter text-center"><?php echo app('translator')->get('label.PERCENT'); ?></th>
                    <th class="vcenter text-center"><?php echo app('translator')->get('label.GRADE'); ?></th>
                    <th class="vcenter text-center"><?php echo app('translator')->get('label.POSITION'); ?></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <!--term aggregated total-->
                    <th class="vcenter text-center">
                        <?php echo app('translator')->get('label.WT'); ?>&nbsp;(<?php echo !empty($eventMksWtArr['agg_total_mks_limit']) ? Helper::numberFormat2Digit($eventMksWtArr['agg_total_mks_limit']) : '0.00'; ?>)
                    </th>
                    <th class="vcenter text-center">
                        <?php echo app('translator')->get('label.WT'); ?>&nbsp;(<?php echo !empty($eventMksWtArr['agg_total_wt_limit']) ? Helper::numberFormat2Digit($eventMksWtArr['agg_total_wt_limit']) : '0.00'; ?>)
                    </th>
                    <th class="vcenter text-center"><?php echo app('translator')->get('label.PERCENT'); ?></th>
                    <th class="vcenter text-center"><?php echo app('translator')->get('label.GRADE'); ?></th>
                    <th class="vcenter text-center"><?php echo app('translator')->get('label.POSITION'); ?></th>

                    <!--final-->
                    <th class="vcenter text-center"><?php echo app('translator')->get('label.WT'); ?>&nbsp;(<?php echo Helper::numberFormat2Digit($finalWtLimit); ?>)</th>
                    <th class="vcenter text-center"><?php echo app('translator')->get('label.PERCENT'); ?></th>
                    <th class="vcenter text-center"><?php echo app('translator')->get('label.GRADE'); ?></th>
                    <th class="vcenter text-center"><?php echo app('translator')->get('label.POSITION'); ?></th>

                </tr>
            </thead>

            <tbody>
                <?php
                $sl = 0;
                $readonly = !empty($comdtObsnLockInfo) ? 'readonly' : '';
                $givenWt = !empty($comdtObsnLockInfo) ? 'given-wt' : '';
                ?>
                <?php $__currentLoopData = $cmArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cmId => $cmInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $cmName = (!empty($cmInfo['rank_name']) ? $cmInfo['rank_name'] . ' ' : '') . (!empty($cmInfo['full_name']) ? $cmInfo['full_name'] : '');
                $synName = (!empty($cmInfo['syn_name']) ? $cmInfo['syn_name'] . ' ' : '') . (!empty($cmInfo['sub_syn_name']) ? '(' . $cmInfo['sub_syn_name'] . ')' : '');
                ?>
                <tr>
                    <td class="text-center vcenter"><?php echo ++$sl; ?></td>
                    <td class="vcenter width-80">
                        <div class="width-inherit"><?php echo $cmInfo['personal_no'] ?? ''; ?></div>
                    </td>
                    <td class="vcenter width-80">
                        <div class="width-inherit"><?php echo $cmInfo['rank_name'] ?? ''; ?></div>
                    </td>
                    <td class="vcenter width-150">
                        <div class="width-inherit"><?php echo $cmInfo['full_name'] ?? ''; ?></div>
                    </td>
                    <td class="vcenter">
                        <?php if(!empty($cmInfo['photo']) && File::exists('public/uploads/cm/' . $cmInfo['photo'])): ?>
                        <img width="50" height="60" src="public/uploads/cm/<?php echo e($cmInfo['photo']); ?>" alt="<?php echo e($cmInfo['full_name'] ?? ''); ?>">
                        <?php else: ?>
                        <img width="50" height="60" src="public/img/unknown.png" alt="<?php echo e($cmInfo['full_name'] ?? ''); ?>">
                        <?php endif; ?>
                    </td>


                    <?php if(!empty($termDataArr)): ?>
                    <?php $__currentLoopData = $termDataArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $termId => $termName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                    $totalMksTextAlign = !empty($cmInfo['term_total'][$termId]['total_mks']) ? 'right' : 'center';
                    $totalWtTextAlign = !empty($cmInfo['term_total'][$termId]['total_wt']) ? 'right' : 'center';
                    $totalPercentageTextAlign = !empty($cmInfo['term_total'][$termId]['percentage']) ? 'right' : 'center';
                    ?>
                    <td class="text-<?php echo e($totalMksTextAlign); ?> vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($cmInfo['term_total'][$termId]['total_mks']) ? Helper::numberFormat2Digit($cmInfo['term_total'][$termId]['total_mks']) : '--'; ?></span>
                    </td>
                    <td class="text-<?php echo e($totalWtTextAlign); ?> vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($cmInfo['term_total'][$termId]['total_wt']) ? Helper::numberFormat2Digit($cmInfo['term_total'][$termId]['total_wt']) : '--'; ?></span>
                    </td>
                    <td class="text-<?php echo e($totalPercentageTextAlign); ?> vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($cmInfo['term_total'][$termId]['percentage']) ? Helper::numberFormat2Digit($cmInfo['term_total'][$termId]['percentage']) : '--'; ?></span>
                    </td>
                    <td class="text-center vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($cmInfo['term_total'][$termId]['total_grade']) ? $cmInfo['term_total'][$termId]['total_grade'] : ''; ?> </span>
                    </td>
                    <td class="text-center vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($cmInfo['term_total'][$termId]['position']) ? $cmInfo['term_total'][$termId]['position'] : ''; ?> </span>
                    </td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>


                    <?php
                    $totalMksTextAlign = !empty($cmInfo['term_agg_total_mks']) ? 'right' : 'center';
                    $totalWtTextAlign = !empty($cmInfo['term_agg_total_wt']) ? 'right' : 'center';
                    $totalPercentageTextAlign = !empty($cmInfo['term_agg_percentage']) ? 'right' : 'center';
                    ?>
                    <td class="text-<?php echo e($totalMksTextAlign); ?> vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($cmInfo['term_agg_total_mks']) ? Helper::numberFormat2Digit($cmInfo['term_agg_total_mks']) : '--'; ?></span>
                    </td>
                    <td class="text-<?php echo e($totalWtTextAlign); ?> vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($cmInfo['term_agg_total_wt']) ? Helper::numberFormat2Digit($cmInfo['term_agg_total_wt']) : '--'; ?></span>
                    </td>
                    <td class="text-<?php echo e($totalPercentageTextAlign); ?> vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($cmInfo['term_agg_percentage']) ? Helper::numberFormat2Digit($cmInfo['term_agg_percentage']) : '--'; ?></span>
                    </td>
                    <td class="text-center vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($cmInfo['term_agg_total_grade']) ? $cmInfo['term_agg_total_grade'] : ''; ?> </span>
                    </td>
                    <td class="text-center vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($cmInfo['total_term_agg_position']) ? $cmInfo['total_term_agg_position'] : ''; ?> </span>
                    </td>

                    <!--ci comdt obsn-->
                    <?php
                    $ciObsnTextAlign = !empty($cmInfo['ci_obsn']) ? 'right' : 'center';
                    $comdtObsnTextAlign = !empty($cmInfo['comdt_obsn']) ? 'right' : 'center';
                    ?>
                    <td class="text-<?php echo e($ciObsnTextAlign); ?> vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($cmInfo['ci_obsn']) ? Helper::numberFormat2Digit($cmInfo['ci_obsn']) : '--'; ?></span>
                    </td>
                    <td class="text-<?php echo e($comdtObsnTextAlign); ?> vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($cmInfo['comdt_obsn']) ? Helper::numberFormat2Digit($cmInfo['comdt_obsn']) : '--'; ?></span>
                    </td>

                    <!--final-->
                    <?php
                    $finalWtTextAlign = !empty($cmInfo['final_wt']) ? 'right' : 'center';
                    $finalPerTextAlign = !empty($cmInfo['final_percentage']) ? 'right' : 'center';
                    ?>
                    <td class="text-<?php echo e($finalWtTextAlign); ?> vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($cmInfo['final_wt']) ? Helper::numberFormat2Digit($cmInfo['final_wt']) : '--'; ?></span>
                    </td>
                    <td class="text-<?php echo e($finalPerTextAlign); ?> vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($cmInfo['final_percentage']) ? Helper::numberFormat2Digit($cmInfo['final_percentage']) : '--'; ?></span>
                    </td>
                    <td class="text-center vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($cmInfo['final_grade']) ? $cmInfo['final_grade'] : ''; ?> </span>
                    </td>
                    <td class="text-center vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($cmInfo['final_position']) ? $cmInfo['final_position'] : ''; ?> </span>
                    </td>


                    <td class="vcenter width-80">
                        <div class="width-inherit"><?php echo $cmInfo['personal_no'] ?? ''; ?></div>
                    </td>
                    <td class="vcenter width-80">
                        <div class="width-inherit"><?php echo $cmInfo['rank_name'] ?? ''; ?></div>
                    </td>
                    <td class="vcenter width-150">
                        <div class="width-inherit"><?php echo $cmInfo['full_name'] ?? ''; ?></div>
                    </td>
                    <td class="vcenter">
                        <?php if(!empty($cmInfo['photo']) && File::exists('public/uploads/cm/' . $cmInfo['photo'])): ?>
                        <img width="50" height="60" src="public/uploads/cm/<?php echo e($cmInfo['photo']); ?>" alt="<?php echo e($cmInfo['full_name'] ?? ''); ?>">
                        <?php else: ?>
                        <img width="50" height="60" src="public/img/unknown.png" alt="<?php echo e($cmInfo['full_name'] ?? ''); ?>">
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php /**PATH E:\Xampp\htdocs\afwc\resources\views/report/courseResult/print/index.blade.php ENDPATH**/ ?>