 
<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i><?php echo app('translator')->get('label.COURSE_PROGRESSIVE_RESULT'); ?>
            </div>
        </div>
        <div class="portlet-body">
            <?php echo Form::open(array('group' => 'form', 'url' => 'courseProgressiveResultReport/filter','class' => 'form-horizontal', 'id' => 'submitForm')); ?>

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
                        <label class="control-label col-md-4" for="termId"><?php echo app('translator')->get('label.TERM'); ?> :<span class="text-danger"></span></label>
                        <div class="col-md-8">
                            <?php echo Form::select('term_id', $termList, Request::get('term_id'), ['class' => 'form-control js-source-states', 'id' => 'termId']); ?>

                            <span class="text-danger"><?php echo e($errors->first('term_id')); ?></span>
                        </div>
                    </div>
                </div> 
            </div>
            <div class="row"> 
                <div class="col-md-12 text-center">
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
                            <?php echo e(__('label.COURSE')); ?> : <strong><?php echo e(!empty($courseList[Request::get('course_id')]) && Request::get('course_id') != 0 ? $courseList[Request::get('course_id')] : __('label.N_A')); ?> |</strong>
                            <?php echo e(__('label.TERM')); ?> : <strong><?php echo e(!empty($termList[Request::get('term_id')]) && Request::get('term_id') != 0 ? $termList[Request::get('term_id')] : __('label.N_A')); ?> </strong>
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
                                    <?php if(empty($request->term_id)): ?>
                                    <th class="vcenter text-center" rowspan="2"><?php echo app('translator')->get('label.CI_OBSN'); ?>&nbsp;(<?php echo !empty($assignedObsnInfo->ci_obsn_wt) ? $assignedObsnInfo->ci_obsn_wt : '0.00'; ?>)</th>
                                    <th class="vcenter text-center" rowspan="2"><?php echo app('translator')->get('label.COMDT_OBSN'); ?>&nbsp;(<?php echo !empty($assignedObsnInfo->comdt_obsn_wt) ? $assignedObsnInfo->comdt_obsn_wt : '0.00'; ?>)</th>
                                    <th class="vcenter text-center" colspan="4"><?php echo app('translator')->get('label.FINAL'); ?></th>
                                    <?php endif; ?>
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
                                    <?php if(empty($request->term_id)): ?>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.WT'); ?>&nbsp;(<?php echo Helper::numberFormat2Digit($finalWtLimit); ?>)</th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.PERCENT'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.GRADE'); ?></th>
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
                                        <?php echo Form::hidden('cm_name['.$cmId.']',!empty($cmName) ? $cmName : null,['id' => 'cmId']); ?>

                                    </td>
                                    <td class="vcenter" width="50px">
                                        <?php if(!empty($cmInfo['photo']) && File::exists('public/uploads/cm/' . $cmInfo['photo'])): ?>
                                        <img width="50" height="60" src="<?php echo e(URL::to('/')); ?>/public/uploads/cm/<?php echo e($cmInfo['photo']); ?>" alt="<?php echo e($cmInfo['full_name'] ?? ''); ?>">
                                        <?php else: ?>
                                        <img width="50" height="60" src="<?php echo e(URL::to('/')); ?>/public/img/unknown.png" alt="<?php echo e($cmInfo['full_name'] ?? ''); ?>">
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
                                    
                                    <?php if(empty($request->term_id)): ?>
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
                $('#termId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_TERM_OPT'); ?></option>");
                return false;
            }
            $.ajax({
                url: "<?php echo e(URL::to('courseProgressiveResultReport/getCourse')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    training_year_id: trainingYearId
                },
                beforeSend: function () {
                    $('#termId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_TERM_OPT'); ?></option>");
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

        //Start::Get Term
        $(document).on("change", "#courseId", function () {
            var courseId = $("#courseId").val();
            if (courseId == 0) {
                $('#termId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_TERM_OPT'); ?></option>");
                return false;
            }
            $.ajax({
                url: "<?php echo e(URL::to('courseProgressiveResultReport/getTerm')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    course_id: courseId,
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#termId').html(res.html);
                    $('.js-source-states').select2();

                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                    $("#previewMarkingSheet").prop("disabled", false);
                    var errorsHtml = '';
                    if (jqXhr.status == 400) {
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, {"closeButton": true});
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, {"closeButton": true});
                    } else {
                        toastr.error('<?php echo app('translator')->get("label.SOMETHING_WENT_WRONG"); ?>', 'Error', options);
                    }

                }
            });//ajax
        });
        //End::Get Term
    });
</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Xampp\htdocs\afwc\resources\views/report/courseProgressiveResult/index.blade.php ENDPATH**/ ?>