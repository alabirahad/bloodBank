<div class="row">
    <?php if(!empty($assignedObsnInfo)): ?>
    <?php if(!empty($ciObsnLockInfo)): ?>
    <?php if(!empty($cmArr)): ?>
    <div class="col-md-12 margin-top-10">
        <span class="label label-md bold label-blue-steel">
            <?php echo app('translator')->get('label.TOTAL_NO_OF_CM'); ?>: &nbsp;<?php echo sizeof($cmArr); ?>

        </span>&nbsp;
        <a class = "btn btn-sm bold label-green-seagreen tooltips" title="<?php echo app('translator')->get('label.CLICK_HERE_TO_SEE_COURSE_MARKING_STATUS_SUMMARY'); ?>" type="button" href="#modalInfo" data-toggle="modal" id="courseStatusSummaryId">
            <?php echo app('translator')->get('label.COURSE_STATUS_SUMMARY'); ?>
        </a>
    </div>
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
                        <th class="vcenter" rowspan="5"><?php echo app('translator')->get('label.SYNDICATE'); ?></th>
                        <?php if(!empty($eventMksWtArr['mks_wt'])): ?>
                        <?php $__currentLoopData = $eventMksWtArr['mks_wt']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eventId => $evInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th class="vcenter text-center" rowspan="<?php echo !empty($eventMksWtArr['event'][$eventId]) && sizeof($eventMksWtArr['event'][$eventId]) > 1 ? 1 : 4; ?>"
                            colspan="<?php echo !empty($rowSpanArr['event'][$eventId]) ? $rowSpanArr['event'][$eventId] * 4 : 4; ?>">
                            <?php echo !empty($eventMksWtArr['event'][$eventId]['name']) ? $eventMksWtArr['event'][$eventId]['name'] : ''; ?>

                        </th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        <th class="vcenter text-center" rowspan="4" colspan="5"><?php echo app('translator')->get('label.TERM_TOTAL'); ?></th>
                        <th class="vcenter text-center" rowspan="5"><?php echo app('translator')->get('label.CI_OBSN'); ?>&nbsp;(<?php echo !empty($assignedObsnInfo->ci_obsn_wt) ? $assignedObsnInfo->ci_obsn_wt : '0.00'; ?>)</th>
                        <th class="vcenter text-center" rowspan="4" colspan="4"><?php echo app('translator')->get('label.AFTER_CI_OBSN'); ?></th>
                        <th class="vcenter text-center" rowspan="5"><?php echo app('translator')->get('label.COMDT_OBSN'); ?>&nbsp;(<?php echo !empty($assignedObsnInfo->comdt_obsn_wt) ? $assignedObsnInfo->comdt_obsn_wt : '0.00'; ?>)</th>
                        <?php echo Form::hidden('assigned_wt',!empty($assignedObsnInfo->comdt_obsn_wt) ? $assignedObsnInfo->comdt_obsn_wt : 0,['id' => 'assignedWtId']); ?>

                        <th class="vcenter text-center" rowspan="4" colspan="4"><?php echo app('translator')->get('label.AFTER_COMDT_OBSN'); ?></th>

                        <th class="vcenter" rowspan="5"><?php echo app('translator')->get('label.PERSONAL_NO'); ?></th>
                        <th class="vcenter" rowspan="5"><?php echo app('translator')->get('label.RANK'); ?></th>
                        <th class="vcenter" rowspan="5"><?php echo app('translator')->get('label.CM'); ?></th>
                        <th class="vcenter" rowspan="5"><?php echo app('translator')->get('label.PHOTO'); ?></th>
                        <th class="vcenter" rowspan="5"><?php echo app('translator')->get('label.SYNDICATE'); ?></th>
                    </tr>
                    <tr>
                        <?php if(!empty($eventMksWtArr['mks_wt'])): ?>
                        <?php $__currentLoopData = $eventMksWtArr['mks_wt']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eventId => $evInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $__currentLoopData = $evInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subEventId => $subEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(!empty($subEventId)): ?>
                        <th class="vcenter text-center" rowspan="<?php echo !empty($eventMksWtArr['event'][$eventId][$subEventId]) && sizeof($eventMksWtArr['event'][$eventId][$subEventId]) > 1 ? 1 : 3; ?>"
                            colspan="<?php echo !empty($rowSpanArr['sub_event'][$eventId][$subEventId]) ? $rowSpanArr['sub_event'][$eventId][$subEventId] * 4 : 4; ?>">
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
                            colspan="<?php echo !empty($rowSpanArr['sub_sub_event'][$eventId][$subEventId][$subSubEventId]) ? $rowSpanArr['sub_sub_event'][$eventId][$subEventId][$subSubEventId] * 4 : 4; ?>">
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
                        <th class="vcenter text-center" colspan="4">
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
                        <th class="vcenter text-center"><?php echo app('translator')->get('label.PERCENT'); ?></th>
                        <th class="vcenter text-center"><?php echo app('translator')->get('label.GRADE'); ?></th>
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

                        <th class="vcenter text-center">
                            <?php echo app('translator')->get('label.WT'); ?>&nbsp;(<?php echo !empty($eventMksWtArr['total_wt_after_ci']) ? Helper::numberFormat2Digit($eventMksWtArr['total_wt_after_ci']) : '0.00'; ?>)
                        </th>
                        <th class="vcenter text-center"><?php echo app('translator')->get('label.PERCENT'); ?></th>
                        <th class="vcenter text-center"><?php echo app('translator')->get('label.GRADE'); ?></th>
                        <th class="vcenter text-center"><?php echo app('translator')->get('label.POSITION'); ?></th>

                        <th class="vcenter text-center">
                            <?php echo app('translator')->get('label.WT'); ?>&nbsp;(<?php echo !empty($eventMksWtArr['total_wt_after_comdt']) ? Helper::numberFormat2Digit($eventMksWtArr['total_wt_after_comdt']) : '0.00'; ?>)
                            <?php echo Form::hidden('total_wt_after_comdt',!empty($eventMksWtArr['total_wt_after_comdt']) ? $eventMksWtArr['total_wt_after_comdt'] : 0,['id' => 'afterComdtWtId']); ?>

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
                    $givenWt = !empty($comdtObsnLockInfo) ? '' : 'given-wt';
                    ?>
                    <?php $__currentLoopData = $cmArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cmId => $cmInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                    $cmName = (!empty($cmInfo['rank_name']) ? $cmInfo['rank_name'] . ' ' : '') . (!empty($cmInfo['full_name']) ? $cmInfo['full_name'] : '');
                    $synName = (!empty($cmInfo['syn_name']) ? $cmInfo['syn_name'] . ' ' : '') . (!empty($cmInfo['sub_syn_name']) ? '(' . $cmInfo['sub_syn_name'] . ')' : '');
                    ?>
                    <tr>
                        <td class="text-center vcenter witdh-50">
                            <div class="width-inherit"><?php echo ++$sl; ?></div>
                        </td>
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
                        <td class="vcenter width-150">
                            <div class="width-inherit"><?php echo $synName; ?></div>
                        </td>
                        <?php if(!empty($eventMksWtArr['mks_wt'])): ?>
                        <?php $__currentLoopData = $eventMksWtArr['mks_wt']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eventId => $evInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $__currentLoopData = $evInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subEventId => $subEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $__currentLoopData = $subEvInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subSubEventId => $subSubEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $__currentLoopData = $subSubEvInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subSubSubEventId => $subSubSubEvInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-right">
                                <?php echo !empty($achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['mks']) ? $achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['mks'] : ''; ?> 
                            </span>
                        </td>
                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-right">
                                <?php echo !empty($achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['wt']) ? $achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['wt'] : ''; ?> 
                            </span>
                        </td>
                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-right">
                                <?php echo !empty($achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['percentage']) ? $achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['percentage'] : ''; ?> 
                            </span>
                        </td>
                        <td class="vcenter width-80">
                            <span class="form-control width-inherit text-right">
                                <?php echo !empty($achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['grade_name']) ? $achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['grade_name'] : ''; ?> 
                            </span>
                        </td>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-right">
                                <?php echo !empty($cmInfo['total_term_mks']) ? Helper::numberFormat2Digit($cmInfo['total_term_mks']) : ''; ?> 
                            </span>
                        </td>
                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-right">
                                <?php echo !empty($cmInfo['total_term_wt']) ? Helper::numberFormat2Digit($cmInfo['total_term_wt']) : ''; ?> 
                            </span>
                        </td>
                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-right">
                                <?php echo !empty($cmInfo['total_term_percent']) ? Helper::numberFormat2Digit($cmInfo['total_term_percent']) : ''; ?> 
                            </span>
                        </td>
                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-center bold">
                                <?php echo !empty($cmInfo['grade_after_term_total']) ? $cmInfo['grade_after_term_total'] : ''; ?> 
                            </span>
                        </td>
                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-center">
                                <?php echo !empty($cmInfo['total_term_position']) ? $cmInfo['total_term_position'] : ''; ?> 
                            </span>
                        </td>
                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-right">
                                <?php echo !empty($cmInfo['ci_obsn']) ? $cmInfo['ci_obsn'] : ''; ?> 
                            </span>
                        </td>

                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-right"  id="totalWtAfterCi_<?php echo e($cmId); ?>">
                                <?php echo !empty($cmInfo['total_wt']) ? $cmInfo['total_wt'] : ''; ?> 
                            </span>
                        </td>
                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-right">
                                <?php echo !empty($cmInfo['percent']) ? $cmInfo['percent'] : ''; ?> 
                            </span>
                        </td>
                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-center bold">
                                <?php echo $cmInfo['grade'] ?? ''; ?>

                            </span>
                        </td>
                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-center">
                                <?php echo !empty($cmInfo['position_after_ci_obsn']) ? $cmInfo['position_after_ci_obsn'] : ''; ?>

                            </span>
                        </td>

                        <td class="vcenter width-80">
                            <?php echo Form::text('wt['.$cmId.'][comdt_obsn]',!empty($cmInfo['comdt_obsn']) ? $cmInfo['comdt_obsn'] : null,['id' => 'comdtObsnId_'.$cmId, 'data-key' => $cmId, 'class' => 'form-control integer-decimal-only width-inherit text-right ' . $givenWt,'autocomplete'=>'off',$readonly]); ?> 
                        </td>

                        <td class="vcenter width-80">
                            <?php echo Form::text('wt['.$cmId.'][total_wt]',!empty($cmInfo['total_wt_after_comdt']) ? $cmInfo['total_wt_after_comdt'] : null,['id' => 'totalWt_'.$cmId, 'data-key' => $cmId, 'class' => 'form-control integer-decimal-only width-inherit text-right','readonly','autocomplete'=>'off']); ?> 
                        </td>
                        <td class="vcenter width-80">
                            <?php echo Form::text('wt['.$cmId.'][percentage]',!empty($cmInfo['percent_after_comdt']) ? $cmInfo['percent_after_comdt'] : null,['id' => 'percentId_'.$cmId, 'data-key' => $cmId, 'class' => 'form-control integer-decimal-only width-inherit text-right','readonly','autocomplete'=>'off']); ?> 
                        </td>
                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-center bold" id="gradeName_<?php echo e($cmId); ?>">
                                <?php echo $cmInfo['grade_after_comdt'] ?? ''; ?>

                            </span>
                        </td>
                        <?php echo Form::hidden('wt['.$cmId.'][grade_id]',!empty($cmInfo['grade_id_after_comdt']) ? $cmInfo['grade_id_after_comdt'] : null,['id' => 'gradeId_'.$cmId]); ?>

                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-center">
                                <?php echo !empty($cmInfo['position_after_comdt_obsn']) ? $cmInfo['position_after_comdt_obsn'] : ''; ?>

                            </span>
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
                        <td class="vcenter width-150">
                            <div class="width-inherit"><?php echo $synName; ?></div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-12 margin-top-10">
        <div class="row">
            <?php if(!empty($comdtObsnLockInfo)): ?>
            <?php if($comdtObsnLockInfo['status'] == '1'): ?>
            <div class="col-md-12 text-center">
                <button class="btn btn-circle label-purple-sharp request-for-unlock" type="button" id="buttonSubmitLock" data-target="#modalUnlockMessage" data-toggle="modal">
                    <i class="fa fa-unlock"></i> <?php echo app('translator')->get('label.REQUEST_FOR_UNLOCK'); ?>
                </button>
            </div>
            <?php elseif($comdtObsnLockInfo['status'] == '2'): ?>
            <div class="col-md-12">
                <div class="alert alert-danger alert-dismissable">
                    <p><strong><i class="fa fa-unlock"></i> <?php echo __('label.REQUESTED_TO_SUPER_ADMIN_FOR_UNLOCK'); ?></strong></p>
                </div>
            </div>
            <?php endif; ?>
            <?php else: ?>
            <div class="col-md-12 text-center">
                <button class="btn btn-circle label-blue-steel button-submit" data-id="1" type="button" id="buttonSubmit" >
                    <i class="fa fa-file-text-o"></i> <?php echo app('translator')->get('label.SAVE_AS_DRAFT'); ?>
                </button>
                <button class="btn btn-circle green button-submit" data-id="2" type="button" id="buttonSubmitLock" >
                    <i class="fa fa-lock"></i> <?php echo app('translator')->get('label.SAVE_LOCK'); ?>
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php else: ?>
    <div class="col-md-12 margin-top-10">
        <div class="alert alert-danger alert-dismissable">
            <p><strong><i class="fa fa-bell-o fa-fw"></i> <?php echo __('label.NO_CM_IS_ASSIGNED_TO_THIS_COURSE'); ?></strong></p>
        </div>
    </div>
    <?php endif; ?>
    <?php else: ?>
    <div class="col-md-12 margin-top-10">
        <div class="alert alert-danger alert-dismissable">
            <p><strong><i class="fa fa-bell-o fa-fw"></i> <?php echo __('label.CI_OBSN_MARKING_IS_NOT_LOCKED_YET'); ?></strong></p>
        </div>
    </div>
    <?php endif; ?>
    <?php else: ?>
    <div class="col-md-12 margin-top-10">
        <div class="alert alert-danger alert-dismissable">
            <p><strong><i class="fa fa-bell-o fa-fw"></i> <?php echo __('label.OBSN_WT_IS_NOT_DISTRIBUTED_YET'); ?></strong></p>
        </div>
    </div>
    <?php endif; ?>
</div>
<script src="<?php echo e(asset('public/js/custom.js')); ?>"></script>
<script>
$(document).ready(function () {
//table header fix
    $(".table-head-fixer-color").tableHeadFixer();

    $(document).on('keyup', '.given-wt', function () {
        var key = $(this).attr('data-key');
        var givenWt = parseFloat($(this).val());
        var assignedWt = parseFloat($("#assignedWtId").val());
        var totalWtAfterCiObsn = parseFloat($("#totalWtAfterCi_" + key).text());
        var afterComdtWt = parseFloat($("#afterComdtWtId").val());
        if (totalWtAfterCiObsn == '' || isNaN(totalWtAfterCiObsn)) {
            totalWtAfterCiObsn = 0;
        }
        if (givenWt > assignedWt) {
            swal({
                title: '<?php echo app('translator')->get("label.YOUR_GIVEN_WT_EXCEEDED_FROM_ASSIGNED_WT"); ?>',
                type: 'warning',
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Ok',
                closeOnConfirm: true,
            }, function (isConfirm) {
                $("#comdtObsnId_" + key).val('');
                $("#totalWt_" + key).val('');
                $("#percentId_" + key).val('');
                $("#gradeId_" + key).val('');
                $("#gradeName_" + key).text('');
                setTimeout(function () {
                    $("#comdtObsnId_" + key).focus();
                }, 250);
                return false;
            });
        } else if(givenWt == 0) {
            swal({
                title: '<?php echo app('translator')->get("label.YOUR_GIVEN_WT_MUST_NOT_BE_0"); ?>',
                type: 'warning',
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Ok',
                closeOnConfirm: true,
            }, function (isConfirm) {
                $("#comdtObsnId_" + key).val('');
                $("#totalWt_" + key).val('');
                $("#percentId_" + key).val('');
                $("#gradeId_" + key).val('');
                $("#gradeName_" + key).text('');
                setTimeout(function () {
                    $("#comdtObsnId_" + key).focus();
                }, 250);
                return false;
            });
        } else {
            var wt = parseFloat(totalWtAfterCiObsn + givenWt).toFixed(2);
            var wtPercent = parseFloat((wt / afterComdtWt) * 100).toFixed(2);
            if (!isNaN(givenWt)) {
                $("#totalWt_" + key).val(wt);
                $("#percentId_" + key).val(wtPercent);
                $("#gradeName_" + key).text(findGradeName(gradeArr, wtPercent));
                $("#gradeId_" + key).val(findGradeId(gradeIdArr, wtPercent));
            } else {
                $("#totalWt_" + key).val('');
                $("#percentId_" + key).val('');
                $("#gradeName_" + key).text('');
                $("#gradeId_" + key).val('');
            }
        }

    });

//start :: produce grade arr for javascript
    var gradeArr = [];
    var gradeIdArr = [];
    var letter = '';
    var letterId = '';
    var startRange = 0;
    var endRange = 0;
<?php
if (!$gradeInfo->isEmpty()) {
    foreach ($gradeInfo as $grade) {
        ?>
            letter = '<?php echo $grade->grade_name; ?>';
            letterId = '<?php echo $grade->id; ?>';
            startRange = <?php echo $grade->marks_from; ?>;
            endRange = <?php echo $grade->marks_to; ?>;
            gradeArr[letter] = [];
            gradeArr[letter]['start'] = startRange;
            gradeArr[letter]['end'] = endRange;

            gradeIdArr[letterId] = [];
            gradeIdArr[letterId]['start'] = startRange;
            gradeIdArr[letterId]['end'] = endRange;
        <?php
    }
}
?>
    function findGradeName(gradeArr, mark) {
        var achievedGrade = '';
        for (var letter in gradeArr) {
            var range = gradeArr[letter];
            if (mark == 100) {
                achievedGrade = "A+";
            }
            if (range['start'] <= mark && mark < range['end']) {
                achievedGrade = letter;
            }
        }

        return achievedGrade;
    }

    function findGradeId(gradeIdArr, mark) {
        var achievedGradeId = '';
        for (var letterId in gradeIdArr) {
            var range = gradeIdArr[letterId];
            if (mark == 100) {
                achievedGradeId = 1;
            }
            if (range['start'] <= mark && mark < range['end']) {
                achievedGradeId = letterId;
            }
        }

        return achievedGradeId;
    }
//end :: produce grade arr for javascript
});
</script>


<?php /**PATH E:\Xampp\htdocs\afwc\resources\views/comdtObsnMarking/showCmMarkingList.blade.php ENDPATH**/ ?>