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
                        <span class="header"><?php echo app('translator')->get('label.PERFORMANCE_ANALYSIS'); ?></span>
                    </div>
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-md-12">
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
                            <?php echo e(__('label.TRAINING_YEAR')); ?> : <strong><?php echo e(!empty($activeTrainingYearList[Request::get('training_year_id')]) && Request::get('training_year_id') != 0 ? $activeTrainingYearList[Request::get('training_year_id')] : __('label.N_A')); ?> |</strong>
                            <?php echo e(__('label.COURSE')); ?> : <strong><?php echo e(!empty($courseList[Request::get('course_id')]) && Request::get('course_id') != 0 ? $courseList[Request::get('course_id')] : __('label.N_A')); ?> |</strong>
                            <?php echo e(__('label.TERM')); ?> : <strong><?php echo e(!empty($termList[Request::get('term_id')]) && Request::get('term_id') != 0 ? $termList[Request::get('term_id')] : __('label.N_A')); ?> </strong>
                            <?php if(!empty($cmList[Request::get('cm_id')]) && Request::get('cm_id') != 0): ?>
                            <strong>|</strong> <?php echo e(__('label.CM')); ?> : <strong><?php echo e($cmList[Request::get('cm_id')]); ?> </strong>
                            <?php endif; ?>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover table-head-fixer-color">
                        <thead>
                            <tr>
                                <th class="text-center vcenter" rowspan="5"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                <th class="vcenter" rowspan="5"><?php echo app('translator')->get('label.PERSONAL_NO'); ?></th>
                                <th class="vcenter" rowspan="5"><?php echo app('translator')->get('label.RANK'); ?></th>
                                <th class="vcenter" rowspan="5"><?php echo app('translator')->get('label.CM'); ?></th>
                                <th class="vcenter" rowspan="5"><?php echo app('translator')->get('label.PHOTO'); ?></th>
                                <!--<th class="vcenter" rowspan="5"><?php echo app('translator')->get('label.SYNDICATE'); ?></th>-->
                                <?php if(!empty($eventMksWtArr['mks_wt'])): ?>
                                <?php $__currentLoopData = $eventMksWtArr['mks_wt']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eventId => $evInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th class="vcenter text-center" rowspan="<?php echo !empty($eventMksWtArr['event'][$eventId]) && sizeof($eventMksWtArr['event'][$eventId]) > 1 ? 1 : 4; ?>"
                                    colspan="<?php echo !empty($rowSpanArr['event'][$eventId]) ? $rowSpanArr['event'][$eventId] * 2 : 2; ?>">
                                    <?php echo !empty($eventMksWtArr['event'][$eventId]['name']) ? $eventMksWtArr['event'][$eventId]['name'] : ''; ?>

                                </th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                <th class="vcenter text-center" colspan="<?php echo !empty($request->cm_id) ? 4 : 5; ?>" rowspan="4"><?php echo app('translator')->get('label.TERM_TOTAL'); ?></th>
                                <th class="vcenter" rowspan="5"><?php echo app('translator')->get('label.PERSONAL_NO'); ?></th>
                                <th class="vcenter" rowspan="5"><?php echo app('translator')->get('label.RANK'); ?></th>
                                <th class="vcenter" rowspan="5"><?php echo app('translator')->get('label.CM'); ?></th>
                                <th class="vcenter" rowspan="5"><?php echo app('translator')->get('label.PHOTO'); ?></th>
                            </tr>
                            <tr>
                                <?php if(!empty($eventMksWtArr['mks_wt'])): ?>
                                <?php $__currentLoopData = $eventMksWtArr['mks_wt']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eventId => $evInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $evInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subEventId => $subEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(!empty($subEventId)): ?>
                                <th class="vcenter text-center" rowspan="<?php echo !empty($eventMksWtArr['event'][$eventId][$subEventId]) && sizeof($eventMksWtArr['event'][$eventId][$subEventId]) > 1 ? 1 : 3; ?>"
                                    colspan="<?php echo !empty($rowSpanArr['sub_event'][$eventId][$subEventId]) ? $rowSpanArr['sub_event'][$eventId][$subEventId] * 2 : 2; ?>">
                                    <?php echo !empty($eventMksWtArr['event'][$eventId][$subEventId]['name']) ? $eventMksWtArr['event'][$eventId][$subEventId]['name'] : ''; ?>

                                </th>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </tr>
                            <tr>
                                <?php if(!empty($eventMksWtArr['mks_wt'])): ?>
                                <?php $__currentLoopData = $eventMksWtArr['mks_wt']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eventId => $evInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $evInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subEventId => $subEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $subEvInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subSubEventId => $subSubEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(!empty($subSubEventId)): ?>
                                <th class="vcenter text-center" rowspan="<?php echo !empty($eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId]) && sizeof($eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId]) > 1 ? 1 : 2; ?>"
                                    colspan="<?php echo !empty($rowSpanArr['sub_sub_event'][$eventId][$subEventId][$subSubEventId]) ? $rowSpanArr['sub_sub_event'][$eventId][$subEventId][$subSubEventId] * 2 : 2; ?>">
                                    <?php echo !empty($eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId]['name']) ? $eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId]['name'] : ''; ?>

                                </th>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </tr>
                            <tr>
                                <?php if(!empty($eventMksWtArr['mks_wt'])): ?>
                                <?php $__currentLoopData = $eventMksWtArr['mks_wt']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eventId => $evInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $evInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subEventId => $subEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $subEvInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subSubEventId => $subSubEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $subSubEvInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subSubSubEventId => $subSubSubEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(!empty($subSubSubEventId)): ?>
                                <th class="vcenter text-center" colspan="2">
                                    <?php echo !empty($eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['name']) ? $eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['name'] : ''; ?>

                                </th>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </tr>
                            <tr>
                                <?php if(!empty($eventMksWtArr['mks_wt'])): ?>
                                <?php $__currentLoopData = $eventMksWtArr['mks_wt']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eventId => $evInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $evInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subEventId => $subEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $subEvInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subSubEventId => $subSubEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $subSubEvInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subSubSubEventId => $subSubSubEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $eventMkslimit = !empty($subSubSubEvInfo['mks_limit']) ? Helper::numberFormat2Digit($subSubSubEvInfo['mks_limit']) : '0.00';
                                $eventHighestMkslimit = !empty($subSubSubEvInfo['highest_mks_limit']) ? Helper::numberFormat2Digit($subSubSubEvInfo['highest_mks_limit']) : '0.00';
                                $eventLowestMkslimit = !empty($subSubSubEvInfo['lowest_mks_limit']) ? Helper::numberFormat2Digit($subSubSubEvInfo['lowest_mks_limit']) : '0.00';
                                $eventWt = !empty($subSubSubEvInfo['wt']) ? Helper::numberFormat2Digit($subSubSubEvInfo['wt']) : '0.00';
                                ?>
                                <th class="vcenter text-center">
                                    <span class="tooltips" data-html="true" title="
                                          <div class='text-left'>
                                          <?php echo app('translator')->get('label.HIGHEST_MKS_LIMIT'); ?>: &nbsp;<?php echo $eventHighestMkslimit; ?><br/>
                                          <?php echo app('translator')->get('label.LOWEST_MKS_LIMIT'); ?>: &nbsp;<?php echo $eventLowestMkslimit; ?><br/>
                                          </div>
                                          ">
                                        <?php echo app('translator')->get('label.MKS'); ?>&nbsp;(<?php echo $eventMkslimit; ?>)
                                    </span>
                                </th>
                                <th class="vcenter text-center">
                                    <?php echo app('translator')->get('label.WT'); ?>&nbsp;(<?php echo $eventWt; ?>)
                                </th>
<!--                                <th class="vcenter text-center"><?php echo app('translator')->get('label.PERCENT'); ?></th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.GRADE'); ?></th>-->
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                <th class="vcenter text-center">
                                    <?php echo app('translator')->get('label.MKS'); ?>&nbsp;(<?php echo !empty($eventMksWtArr['total_mks_limit']) ? Helper::numberFormat2Digit($eventMksWtArr['total_mks_limit']) : '0.00'; ?>)
                                </th>
                                <th class="vcenter text-center">
                                    <?php echo app('translator')->get('label.WT'); ?>&nbsp;(<?php echo !empty($eventMksWtArr['total_wt']) ? Helper::numberFormat2Digit($eventMksWtArr['total_wt']) : '0.00'; ?>)
                                </th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.PERCENT'); ?></th>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.GRADE'); ?></th>
                                <?php if(empty($request->cm_id)): ?>
                                <th class="vcenter text-center"><?php echo app('translator')->get('label.POSITION'); ?></th>
                                <?php endif; ?>

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
                            $cmId = !empty($cmInfo['id']) ? $cmInfo['id'] : 0;
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
                                <?php
                                $totalMks = 0;
                                $totalWt = 0;
                                ?>
                                <?php if(!empty($eventMksWtArr['mks_wt'])): ?>
                                <?php $__currentLoopData = $eventMksWtArr['mks_wt']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eventId => $evInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $evInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subEventId => $subEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $subEvInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subSubEventId => $subSubEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $subSubEvInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subSubSubEventId => $subSubSubEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <?php
                                $mksTextAlign = !empty($achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['mks']) ? 'right' : 'center';
                                $wtTextAlign = !empty($achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['wt']) ? 'right' : 'center';
                                $percentageTextAlign = !empty($achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['percentage']) ? 'right' : 'center';
                                ?>
                                <td class="text-<?php echo e($mksTextAlign); ?> vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['mks']) ? $achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['mks'] : '--'; ?></span>
                                </td>
                                <td class="text-<?php echo e($wtTextAlign); ?> vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['wt']) ? $achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['wt'] : '--'; ?></span>
                                </td>
<!--                                <td class="text-<?php echo e($percentageTextAlign); ?> vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['percentage']) ? $achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['percentage'] : '--'; ?></span>
                                </td>
                                <td class="text-center vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['grade_name']) ? $achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['grade_name'] : '--'; ?></span>
                                </td>-->
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                <?php
                                $totalMksTextAlign = !empty($cmInfo['total_term_mks']) ? 'right' : 'center';
                                $totalWtTextAlign = !empty($cmInfo['total_term_wt']) ? 'right' : 'center';
                                $totalPercentageTextAlign = !empty($cmInfo['total_term_percent']) ? 'right' : 'center';
                                ?>
                                <td class="text-<?php echo e($totalMksTextAlign); ?> vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($cmInfo['total_term_mks']) ? Helper::numberFormat2Digit($cmInfo['total_term_mks']) : '--'; ?></span>
                                </td>
                                <td class="text-<?php echo e($totalWtTextAlign); ?> vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($cmInfo['total_term_wt']) ? Helper::numberFormat2Digit($cmInfo['total_term_wt']) : '--'; ?></span>
                                </td>
                                <td class="text-<?php echo e($totalPercentageTextAlign); ?> vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($cmInfo['total_term_percent']) ? Helper::numberFormat2Digit($cmInfo['total_term_percent']) : '--'; ?></span>
                                </td>
                                <td class="text-center vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($cmInfo['grade_after_term_total']) ? $cmInfo['grade_after_term_total'] : ''; ?> </span>
                                </td>
                                <?php if(empty($request->cm_id)): ?>
                                <td class="text-center vcenter width-80">
                                    <span class="width-inherit bold"><?php echo !empty($cmInfo['total_term_position']) ? $cmInfo['total_term_position'] : ''; ?> </span>
                                </td>
                                <?php endif; ?>

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
                    <strong><?php echo __('label.PERFORMANCE_ANALYSIS'); ?></strong>
                </td>
            </tr>
        </table>
        <table class="no-border margin-top-10">
            <tr>
                <td class="" colspan="8">
                    <h5 style="padding: 10px;">
                        <?php echo e(__('label.TRAINING_YEAR')); ?> : <strong><?php echo e(!empty($activeTrainingYearList[Request::get('training_year_id')]) && Request::get('training_year_id') != 0 ? $activeTrainingYearList[Request::get('training_year_id')] : __('label.N_A')); ?> |</strong>
                        <?php echo e(__('label.COURSE')); ?> : <strong><?php echo e(!empty($courseList[Request::get('course_id')]) && Request::get('course_id') != 0 ? $courseList[Request::get('course_id')] : __('label.N_A')); ?> |</strong>
                        <?php echo e(__('label.TERM')); ?> : <strong><?php echo e(!empty($termList[Request::get('term_id')]) && Request::get('term_id') != 0 ? $termList[Request::get('term_id')] : __('label.N_A')); ?> </strong>
                        <?php if(!empty($cmList[Request::get('cm_id')]) && Request::get('cm_id') != 0): ?>
                        <strong>|</strong> <?php echo e(__('label.CM')); ?> : <strong><?php echo e($cmList[Request::get('cm_id')]); ?> </strong>
                        <?php endif; ?>
                    </h5>
                </td>
            </tr>
        </table>
        <table class="table table-bordered table-hover table-head-fixer-color">
            <thead>
                <tr>
                    <th class="text-center vcenter" rowspan="5"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                    <th class="vcenter" rowspan="5"><?php echo app('translator')->get('label.PERSONAL_NO'); ?></th>
                    <th class="vcenter" rowspan="5"><?php echo app('translator')->get('label.RANK'); ?></th>
                    <th class="vcenter" rowspan="5"><?php echo app('translator')->get('label.CM'); ?></th>
                    <th class="vcenter" rowspan="5"><?php echo app('translator')->get('label.PHOTO'); ?></th>
                    <!--<th class="vcenter" rowspan="5"><?php echo app('translator')->get('label.SYNDICATE'); ?></th>-->
                    <?php if(!empty($eventMksWtArr['mks_wt'])): ?>
                    <?php $__currentLoopData = $eventMksWtArr['mks_wt']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eventId => $evInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th class="vcenter text-center" rowspan="<?php echo !empty($eventMksWtArr['event'][$eventId]) && sizeof($eventMksWtArr['event'][$eventId]) > 1 ? 1 : 4; ?>"
                        colspan="<?php echo !empty($rowSpanArr['event'][$eventId]) ? $rowSpanArr['event'][$eventId] * 2 : 2; ?>">
                        <?php echo !empty($eventMksWtArr['event'][$eventId]['name']) ? $eventMksWtArr['event'][$eventId]['name'] : ''; ?>

                    </th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <th class="vcenter text-center" colspan="<?php echo !empty($request->cm_id) ? 4 : 5; ?>" rowspan="4"><?php echo app('translator')->get('label.TERM_TOTAL'); ?></th>
                    <th class="vcenter" rowspan="5"><?php echo app('translator')->get('label.PERSONAL_NO'); ?></th>
                    <th class="vcenter" rowspan="5"><?php echo app('translator')->get('label.RANK'); ?></th>
                    <th class="vcenter" rowspan="5"><?php echo app('translator')->get('label.CM'); ?></th>
                    <th class="vcenter" rowspan="5"><?php echo app('translator')->get('label.PHOTO'); ?></th>
                </tr>
                <tr>
                    <?php if(!empty($eventMksWtArr['mks_wt'])): ?>
                    <?php $__currentLoopData = $eventMksWtArr['mks_wt']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eventId => $evInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $evInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subEventId => $subEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(!empty($subEventId)): ?>
                    <th class="vcenter text-center" rowspan="<?php echo !empty($eventMksWtArr['event'][$eventId][$subEventId]) && sizeof($eventMksWtArr['event'][$eventId][$subEventId]) > 1 ? 1 : 3; ?>"
                        colspan="<?php echo !empty($rowSpanArr['sub_event'][$eventId][$subEventId]) ? $rowSpanArr['sub_event'][$eventId][$subEventId] * 2 : 2; ?>">
                        <?php echo e(!empty($eventMksWtArr['event'][$eventId][$subEventId]['name']) ? $eventMksWtArr['event'][$eventId][$subEventId]['name'] : ''); ?>

                    </th>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </tr>
                <tr>
                    <?php if(!empty($eventMksWtArr['mks_wt'])): ?>
                    <?php $__currentLoopData = $eventMksWtArr['mks_wt']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eventId => $evInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $evInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subEventId => $subEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $subEvInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subSubEventId => $subSubEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(!empty($subSubEventId)): ?>
                    <th class="vcenter text-center" rowspan="<?php echo !empty($eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId]) && sizeof($eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId]) > 1 ? 1 : 2; ?>"
                        colspan="<?php echo !empty($rowSpanArr['sub_sub_event'][$eventId][$subEventId][$subSubEventId]) ? $rowSpanArr['sub_sub_event'][$eventId][$subEventId][$subSubEventId] * 2 : 2; ?>">
                        <?php echo e(!empty($eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId]['name']) ? $eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId]['name'] : ''); ?>

                    </th>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </tr>
                <tr>
                    <?php if(!empty($eventMksWtArr['mks_wt'])): ?>
                    <?php $__currentLoopData = $eventMksWtArr['mks_wt']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eventId => $evInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $evInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subEventId => $subEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $subEvInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subSubEventId => $subSubEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $subSubEvInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subSubSubEventId => $subSubSubEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(!empty($subSubSubEventId)): ?>
                    <th class="vcenter text-center" colspan="2">
                        <?php echo e(!empty($eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['name']) ? $eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['name'] : ''); ?>

                    </th>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </tr>
                <tr>
                    <?php if(!empty($eventMksWtArr['mks_wt'])): ?>
                    <?php $__currentLoopData = $eventMksWtArr['mks_wt']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eventId => $evInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $evInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subEventId => $subEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $subEvInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subSubEventId => $subSubEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $subSubEvInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subSubSubEventId => $subSubSubEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                    $eventMkslimit = !empty($subSubSubEvInfo['mks_limit']) ? Helper::numberFormat2Digit($subSubSubEvInfo['mks_limit']) : '0.00';
                    $eventHighestMkslimit = !empty($subSubSubEvInfo['highest_mks_limit']) ? Helper::numberFormat2Digit($subSubSubEvInfo['highest_mks_limit']) : '0.00';
                    $eventLowestMkslimit = !empty($subSubSubEvInfo['lowest_mks_limit']) ? Helper::numberFormat2Digit($subSubSubEvInfo['lowest_mks_limit']) : '0.00';
                    $eventWt = !empty($subSubSubEvInfo['wt']) ? Helper::numberFormat2Digit($subSubSubEvInfo['wt']) : '0.00';
                    ?>
                    <th class="vcenter text-center">
                        <?php echo app('translator')->get('label.MKS'); ?>&nbsp;(<?php echo $eventMkslimit; ?>)
                        
                    </th>
                    <th class="vcenter text-center">
                        <?php echo app('translator')->get('label.WT'); ?>&nbsp;(<?php echo $eventWt; ?>)
                    </th>
<!--                    <th class="vcenter text-center"><?php echo app('translator')->get('label.PERCENT'); ?></th>
                    <th class="vcenter text-center"><?php echo app('translator')->get('label.GRADE'); ?></th>-->
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <th class="vcenter text-center">
                        <?php echo app('translator')->get('label.MKS'); ?>&nbsp;(<?php echo !empty($eventMksWtArr['total_mks_limit']) ? Helper::numberFormat2Digit($eventMksWtArr['total_mks_limit']) : '0.00'; ?>)
                    </th>
                    <th class="vcenter text-center">
                        <?php echo app('translator')->get('label.WT'); ?>&nbsp;(<?php echo !empty($eventMksWtArr['total_wt']) ? Helper::numberFormat2Digit($eventMksWtArr['total_wt']) : '0.00'; ?>)
                    </th>
                    <th class="vcenter text-center"><?php echo app('translator')->get('label.PERCENT'); ?></th>
                    <th class="vcenter text-center"><?php echo app('translator')->get('label.GRADE'); ?></th>
                    <?php if(empty($request->cm_id)): ?>
                    <th class="vcenter text-center"><?php echo app('translator')->get('label.POSITION'); ?></th>
                    <?php endif; ?>

                </tr>

            </thead>

            <tbody>
                <?php
                $sl = 0;
                ?>
                <?php $__currentLoopData = $cmArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cmId => $cmInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $cmId = !empty($cmInfo['id']) ? $cmInfo['id'] : 0;
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
                    <?php
                    $totalMks = 0;
                    $totalWt = 0;
                    ?>
                    <?php if(!empty($eventMksWtArr['mks_wt'])): ?>
                    <?php $__currentLoopData = $eventMksWtArr['mks_wt']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eventId => $evInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $evInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subEventId => $subEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $subEvInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subSubEventId => $subSubEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $subSubEvInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subSubSubEventId => $subSubSubEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php
                    $mksTextAlign = !empty($achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['mks']) ? 'right' : 'center';
                    $wtTextAlign = !empty($achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['wt']) ? 'right' : 'center';
                    $percentageTextAlign = !empty($achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['percentage']) ? 'right' : 'center';
                    ?>
                    <td class="text-<?php echo e($mksTextAlign); ?> vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['mks']) ? $achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['mks'] : '--'; ?></span>
                    </td>
                    <td class="text-<?php echo e($wtTextAlign); ?> vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['wt']) ? $achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['wt'] : '--'; ?></span>
                    </td>
<!--                    <td class="text-<?php echo e($percentageTextAlign); ?> vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['percentage']) ? $achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['percentage'] : '--'; ?></span>
                    </td>
                    <td class="text-center vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['grade_name']) ? $achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['grade_name'] : '--'; ?></span>
                    </td>-->
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <?php
                    $totalMksTextAlign = !empty($cmInfo['total_term_mks']) ? 'right' : 'center';
                    $totalWtTextAlign = !empty($cmInfo['total_term_wt']) ? 'right' : 'center';
                    $totalPercentageTextAlign = !empty($cmInfo['total_term_percent']) ? 'right' : 'center';
                    ?>
                    <td class="text-<?php echo e($totalMksTextAlign); ?> vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($cmInfo['total_term_mks']) ? Helper::numberFormat2Digit($cmInfo['total_term_mks']) : '--'; ?></span>
                    </td>
                    <td class="text-<?php echo e($totalWtTextAlign); ?> vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($cmInfo['total_term_wt']) ? Helper::numberFormat2Digit($cmInfo['total_term_wt']) : '--'; ?></span>
                    </td>
                    <td class="text-<?php echo e($totalPercentageTextAlign); ?> vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($cmInfo['total_term_percent']) ? Helper::numberFormat2Digit($cmInfo['total_term_percent']) : '--'; ?></span>
                    </td>
                    <td class="text-center vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($cmInfo['grade_after_term_total']) ? $cmInfo['grade_after_term_total'] : ''; ?> </span>
                    </td>
                    <?php if(empty($request->cm_id)): ?>
                    <td class="text-center vcenter width-80">
                        <span class="width-inherit bold"><?php echo !empty($cmInfo['total_term_position']) ? $cmInfo['total_term_position'] : ''; ?> </span>
                    </td>
                    <?php endif; ?>


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
<?php /**PATH E:\Xampp\htdocs\afwc\resources\views/report/performanceAnalysis/print/index.blade.php ENDPATH**/ ?>