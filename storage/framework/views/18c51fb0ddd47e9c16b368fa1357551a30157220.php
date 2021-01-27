 
<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i><?php echo app('translator')->get('label.EVENT_RESULT_COMBINED'); ?>
            </div>
        </div>
        <div class="portlet-body">
            <?php echo Form::open(array('group' => 'form', 'url' => 'eventResultCombinedReport/filter','class' => 'form-horizontal', 'id' => 'submitForm')); ?>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="trainingYearId"><?php echo app('translator')->get('label.TRAINING_YEAR'); ?> :<span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <?php echo Form::select('training_year_id', $activeTrainingYearList, Request::get('training_year_id'), ['class' => 'form-control js-source-states', 'id' => 'trainingYearId']); ?>

                            <span class="text-danger"><?php echo e($errors->first('training_year_id')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="courseId"><?php echo app('translator')->get('label.COURSE'); ?> :<span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <?php echo Form::select('course_id', $courseList, Request::get('course_id'), ['class' => 'form-control js-source-states', 'id' => 'courseId']); ?>

                            <span class="text-danger"><?php echo e($errors->first('course_id')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <button type="submit" class="btn btn-md green btn-outline filter-btn" id="eventCombineReportId" value="Show Filter Info" data-id="1">
                            <i class="fa fa-search"></i> <?php echo app('translator')->get('label.GENERATE'); ?>
                        </button>
                    </div>
                </div>
            </div>
            <?php if(Request::get('generate') == 'true'): ?>
            <?php if(!empty($cmArr)): ?>
            <div class="row">
                <div class="col-md-12 text-right">
                    <a class="btn btn-md btn-primary vcenter tooltips" title="<?php echo app('translator')->get('label.PRINT'); ?>" target="_blank"  href="<?php echo URL::full().'&view=print'; ?>">
                        <span class=""><i class="fa fa-print"></i> </span> 
                    </a>
                    <a class="btn btn-success vcenter tooltips" title="<?php echo app('translator')->get('label.DOWNLOAD_PDF'); ?>" href="<?php echo URL::full().'&view=pdf'; ?>">
                        <span class=""><i class="fa fa-file-pdf-o"></i></span>
                    </a>
                    <a class="btn btn-warning vcenter tooltips" title="<?php echo app('translator')->get('label.DOWNLOAD_EXCEL'); ?>" href="<?php echo URL::full().'&view=excel'; ?>">
                        <span class=""><i class="fa fa-file-excel-o"></i> </span>
                    </a>
                    <label class="control-label" for="sortBy"><?php echo app('translator')->get('label.SORT_BY'); ?> :</label>&nbsp;

                    <label class="control-label" for="sortBy">
                        <?php echo Form::select('sort', $sortByList, Request::get('sort'),['class' => 'form-control','id'=>'sortBy']); ?>

                    </label>

                    <button class="btn green-jungle filter-btn"  id="sortByHref" type="submit">
                        <i class="fa fa-arrow-right"></i>  <?php echo app('translator')->get('label.GO'); ?>
                    </button>


                </div>
            </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
                            <?php echo e(__('label.TRAINING_YEAR')); ?> : <strong><?php echo e(!empty($activeTrainingYearList[Request::get('training_year_id')]) && Request::get('training_year_id') != 0 ? $activeTrainingYearList[Request::get('training_year_id')] : __('label.N_A')); ?> |</strong>
                            <?php echo e(__('label.COURSE')); ?> : <strong><?php echo e(!empty($courseList[Request::get('course_id')]) && Request::get('course_id') != 0 ? $courseList[Request::get('course_id')] : __('label.N_A')); ?> </strong>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php if(!empty($cmArr)): ?>
                <div class="col-md-12 margin-top-10">
                    <div class="max-height-500 table-responsive webkit-scrollbar">
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
                                    <th class="vcenter text-center" colspan="5" rowspan="4"><?php echo app('translator')->get('label.EVENT_TOTAL'); ?></th>
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
<!--                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.PERCENT'); ?></th>
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
                                        <?php echo Form::hidden('cm_name['.$cmId.']',!empty($cmName) ? $cmName : null,['id' => 'cmId']); ?>

                                    </td>
                                    <td class="vcenter" width="50px">
                                        <?php if(!empty($cmInfo['photo']) && File::exists('public/uploads/cm/' . $cmInfo['photo'])): ?>
                                        <img width="50" height="60" src="<?php echo e(URL::to('/')); ?>/public/uploads/cm/<?php echo e($cmInfo['photo']); ?>" alt="<?php echo e($cmInfo['full_name'] ?? ''); ?>">
                                        <?php else: ?>
                                        <img width="50" height="60" src="<?php echo e(URL::to('/')); ?>/public/img/unknown.png" alt="<?php echo e($cmInfo['full_name'] ?? ''); ?>">
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
<!--                                    <td class="text-<?php echo e($percentageTextAlign); ?> vcenter width-80">
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
                                    <td class="text-center vcenter width-80">
                                        <span class="width-inherit bold"><?php echo !empty($cmInfo['total_term_position']) ? $cmInfo['total_term_position'] : ''; ?> </span>
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
                                        <img width="50" height="60" src="<?php echo e(URL::to('/')); ?>/public/uploads/cm/<?php echo e($cmInfo['photo']); ?>" alt="<?php echo e($cmInfo['full_name'] ?? ''); ?>">
                                        <?php else: ?>
                                        <img width="50" height="60" src="<?php echo e(URL::to('/')); ?>/public/img/unknown.png" alt="<?php echo e($cmInfo['full_name'] ?? ''); ?>">
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php else: ?>
                <div class="col-md-12 margin-top-10">
                    <div class="alert alert-danger alert-dismissable">
                        <p><strong><i class="fa fa-bell-o fa-fw"></i> <?php echo __('label.NO_CM_IS_ASSIGNED_TO_THIS_COURSE'); ?></strong></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <?php echo Form::close(); ?>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null
        };

        //Start::Get Course
        $(document).on("change", "#trainingYearId", function () {
            var trainingYearId = $("#trainingYearId").val();
            if (trainingYearId == 0) {
                $('#courseId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_COURSE_OPT'); ?></option>");
                return false;
            }
            $.ajax({
                url: "<?php echo e(URL::to('eventResultCombinedReport/getCourse')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    training_year_id: trainingYearId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#courseId').html(res.html);
                    $(".js-source-states").select2();
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            });//ajax

        });
        //End::Get Course
    });
</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Xampp\htdocs\afwc\resources\views/report/eventResultCombined/index.blade.php ENDPATH**/ ?>