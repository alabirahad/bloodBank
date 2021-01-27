
<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i><?php echo app('translator')->get('label.EVENT_MKS_WT'); ?>
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            <?php echo Form::open(array('group' => 'form', 'url' => 'markingGroupSummaryReport/filter','class' => 'form-horizontal')); ?>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="trainingYearId"><?php echo app('translator')->get('label.TRAINING_YEAR'); ?> <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <?php echo Form::select('training_year_id', !empty($activeTrainingYearInfo)?$activeTrainingYearInfo:null,  Request::get('training_year_id'), ['class' => 'form-control js-source-states', 'id' => 'trainingYearId']); ?>

                            <span class="text-danger"><?php echo e($errors->first('training_year_id')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="courseId"><?php echo app('translator')->get('label.COURSE'); ?> <span class="text-danger">*</span></label>
                        <div class="col-md-8" id="showCourse">
                            <?php echo Form::select('course_id', !empty($courseList)?$courseList:null,  Request::get('course_id'), ['class' => 'form-control js-source-states', 'id' => 'courseId']); ?>

                            <span class="text-danger"><?php echo e($errors->first('course_id')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="termId"><?php echo app('translator')->get('label.TERM'); ?> <span class="text-danger">*</span></label>
                        <div class="col-md-8" id="showTerm">
                            <?php echo Form::select('term_id', $termList,  Request::get('term_id'), ['class' => 'form-control js-source-states', 'id' => 'termId']); ?>

                            <span class="text-danger"><?php echo e($errors->first('term_id')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <button type="submit" class="btn btn-md green btn-outline filter-btn margin-bottom-20" id="generateId" value="Show Filter Info" data-mode="1">
                                <i class="fa fa-search"></i> <?php echo app('translator')->get('label.GENERATE'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>

            <!--filter form close-->
            <?php if($request->generate == 'true'): ?>
            <?php if(!empty($eventMksWtArr['mks_wt'])): ?>
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
                </div>
            </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
                            <?php echo e(__('label.TRAINING_YEAR')); ?> : <strong><?php echo e(!empty($activeTrainingYearInfo[Request::get('training_year_id')]) && Request::get('training_year_id') != 0 ? $activeTrainingYearInfo[Request::get('training_year_id')] : __('label.N_A')); ?> |</strong>
                            <?php echo e(__('label.COURSE')); ?> : <strong><?php echo e(!empty($courseList[Request::get('course_id')]) && Request::get('course_id') != 0 ? $courseList[Request::get('course_id')] : __('label.N_A')); ?> |</strong>
                            <?php echo e(__('label.TERM')); ?> : <strong><?php echo e(!empty($termList[Request::get('term_id')]) && Request::get('term_id') != 0 ? $termList[Request::get('term_id')] : __('label.N_A')); ?> |</strong>
                            <?php echo e(__('label.TOTAL_MKS_LIMIT')); ?> : <strong><?php echo e(!empty($eventMksWtArr['total_mks_limit']) ? Helper::numberFormat2Digit($eventMksWtArr['total_mks_limit']) : '0.00'); ?> |</strong>
                            <?php echo e(__('label.TOTAL_WT')); ?> : <strong><?php echo e(!empty($eventMksWtArr['total_wt']) ? Helper::numberFormat2Digit($eventMksWtArr['total_wt']) : '0.00'); ?> </strong>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 ">
                    <div class="table-responsive max-height-500 webkit-scrollbar">
                        <table class="table table-bordered table-hover table-head-fixer-color" id="dataTable">
                            <thead>
                                <tr>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.EVENT'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.SUB_EVENT'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.SUB_SUB_EVENT'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.SUB_SUB_SUB_EVENT'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.MARKING_GROUP'); ?></th>
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

                                    <td class="vcenter">
                                    <?php if(!empty($markingGroupDataArr[$eventId][$subEventId][$subSubEventId][$subSubSubEventId])): ?>
                                    <?php $__currentLoopData = $markingGroupDataArr[$eventId][$subEventId][$subSubEventId][$subSubSubEventId]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $markingGroupId => $markingGroupName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($markingGroupName); ?> </br>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
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
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="<?php echo e(asset('/public/js/custom.js')); ?>"></script>
<script type="text/javascript">

$(function () {
    //Start::Get Course
    $(document).on("change", "#trainingYearId", function () {
        var trainingYearId = $("#trainingYearId").val();

        $.ajax({
            url: "<?php echo e(URL::to('eventListReport/getCourse')); ?>",
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
                $('#showCourse').html(res.view);
                $(".js-source-states").select2();
                App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            }
        });//ajax
    });
    //End::Get Course
    //Start::Get Term
    $(document).on("change", "#courseId", function () {
        var courseId = $("#courseId").val();
        $.ajax({
            url: "<?php echo e(URL::to('eventListReport/getTerm')); ?>",
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                course_id: courseId
            },
            beforeSend: function () {
                App.blockUI({boxed: true});
            },
            success: function (res) {
                $('#showTerm').html(res.view);
                $(".js-source-states").select2();
                App.unblockUI();
            },
            error: function (jqXhr, ajaxOptions, thrownError) {
            }
        });//ajax

    });
    //End::Get Term
});

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Xampp\htdocs\afwc\resources\views/report/markingGroupSummary/index.blade.php ENDPATH**/ ?>