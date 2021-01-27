 
<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i><?php echo app('translator')->get('label.MUTUAL_ASSESSMENT_SUMMARY_REPORT'); ?>
            </div>
        </div>
        <div class="portlet-body">
            <?php echo Form::open(array('group' => 'form', 'url' => 'mutualAssessmentSummaryReport/filter','class' => 'form-horizontal', 'id' => 'submitForm')); ?>

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
                        <label class="control-label col-md-4" for="termId"><?php echo app('translator')->get('label.TERM'); ?> :<span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <?php echo Form::select('term_id', $termList, Request::get('term_id'), ['class' => 'form-control js-source-states', 'id' => 'termId']); ?>

                            <span class="text-danger"><?php echo e($errors->first('term_id')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="maEventId"><?php echo app('translator')->get('label.EVENT'); ?> :<span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <?php echo Form::select('maEvent_id', $maEventList, Request::get('maEvent_id'), ['class' => 'form-control js-source-states', 'id' => 'maEventId']); ?>

                            <span class="text-danger"><?php echo e($errors->first('maEvent_id')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="synId"><?php echo app('translator')->get('label.SYN'); ?> :<span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                            <?php echo Form::select('syn_id', $synList, Request::get('syn_id'), ['class' => 'form-control js-source-states', 'id' => 'synId']); ?>

                            <span class="text-danger"><?php echo e($errors->first('syn_id')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="subSynId"><?php echo app('translator')->get('label.SUB_SYN'); ?> :<span class="text-danger required-show"> <?php echo e(!empty($hasSubSyn) ? '*' : ''); ?></span></label>
                        <div class="col-md-8">
                            <?php echo Form::select('sub_syn_id', $subSynList, Request::get('sub_syn_id'), ['class' => 'form-control js-source-states', 'id' => 'subSynId']); ?>

                            <?php echo Form::hidden('has_sub_syn',$hasSubSyn,['id' => 'hasSubSyn']); ?>

                            <span class="text-danger"><?php echo e($errors->first('sub_syn_id')); ?></span>
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
                            <?php echo e(__('label.TERM')); ?> : <strong><?php echo e(!empty($termList[Request::get('term_id')]) && Request::get('term_id') != 0 ? $termList[Request::get('term_id')] : __('label.N_A')); ?> |</strong>
                            <?php echo e(__('label.EVENT')); ?> : <strong><?php echo e(!empty($maEventList[Request::get('maEvent_id')]) && Request::get('maEvent_id') != 0 ? $maEventList[Request::get('maEvent_id')] : __('label.N_A')); ?> |</strong>
                            <?php echo e(__('label.SYN')); ?> : <strong><?php echo e(!empty($synList[Request::get('syn_id')]) && Request::get('syn_id') != 0 ? $synList[Request::get('syn_id')] : __('label.ALL')); ?> |</strong>
                            <?php if(!empty($subSynList[Request::get('sub_syn_id')]) && Request::get('sub_syn_id') != 0): ?>
                            <?php echo e(__('label.SUB_SYN')); ?> : <strong><?php echo e($subSynList[Request::get('sub_syn_id')]); ?> |</strong>
                            <?php endif; ?>
                            <?php echo e(__('label.TOTAL_NO_OF_CM')); ?> : <strong><?php echo e(!empty($cmArr) ? sizeof($cmArr) : 0); ?></strong>

                        </h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <div class="max-height-500 webkit-scrollbar">
                        <table class="table table-bordered table-hover table-head-fixer-color" id="dataTable">
                            <thead>
                                <tr>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.SL'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.CM'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.AVG'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.POSITION'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($cmArr)): ?>
                                <?php 
                                $sl = 0; 
                                ?>
                                <?php $__currentLoopData = $cmArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cmId => $cm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $cmId = !empty($cm['id']) ? $cm['id'] : 0;
                                $cmName = (!empty($cm['rank_name']) ? $cm['rank_name'] : '') . ' ' . (!empty($cm['full_name']) ? $cm['full_name'] : '') . ' (' . (!empty($cm['personal_no']) ? $cm['personal_no'] : '') . ')';
                                ?>
                                <tr>
                                    <td class="vcenter text-center"><?php echo ++$sl; ?></td>
                                    <td class="vcenter"><?php echo $cmName ?? ''; ?></td>
                                    <td class="vcenter text-<?php echo e(!empty($cm['avg']) ? 'right' : 'center'); ?>"><?php echo $cm['avg'] ?? '--'; ?></td>
                                    <td class="vcenter text-center"><?php echo $cm['position'] ?? ''; ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="4"><strong><?php echo app('translator')->get('label.NO_CM_IS_ASSIGNED_TO_THIS_SYN_OR_SUB_SYN'); ?></strong></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
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
                $('#maEventId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_EVENT_OPT'); ?></option>");
                $('#synId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SYN_OPT'); ?></option>");
                $('#subSynId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SUB_SYN_OPT'); ?></option>");
                $('.required-show').text('');
                $('#hasSubSyn').val(0);
                return false;
            }
            $.ajax({
                url: "<?php echo e(URL::to('mutualAssessmentSummaryReport/getCourse')); ?>",
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
                    $('#maEventId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_EVENT_OPT'); ?></option>");
                    $('#synId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SYN_OPT'); ?></option>");
                    $('#subSynId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SUB_SYN_OPT'); ?></option>");
                    $('.required-show').text('');
                    $('#hasSubSyn').val(0);
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

        $(document).on("change", "#courseId", function () {
            var courseId = $("#courseId").val();
            if (courseId == 0) {
                $('#termId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_TERM_OPT'); ?></option>");
                $('#maEventId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_EVENT_OPT'); ?></option>");
                $('#synId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SYN_OPT'); ?></option>");
                $('#subSynId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SUB_SYN_OPT'); ?></option>");
                $('.required-show').text('');
                $('#hasSubSyn').val(0);
                return false;
            }
            $.ajax({
                url: "<?php echo e(URL::to('mutualAssessmentSummaryReport/getTerm')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    course_id: courseId,
                },
                beforeSend: function () {
                    $('#maEventId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_EVENT_OPT'); ?></option>");
                    $('#subSynId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SUB_SYN_OPT'); ?></option>");
                    $('.required-show').text('');
                    $('#hasSubSyn').val(0);
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#termId').html(res.html);
                    $('#synId').html(res.html1);
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

        $(document).on("change", "#termId", function () {
            var termId = $("#termId").val();
            var courseId = $("#courseId").val();
            if (termId == 0) {
                $('#maEventId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_EVENT_OPT'); ?></option>");
                $('#synId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SYN_OPT'); ?></option>");
                $('#subSynId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SUB_SYN_OPT'); ?></option>");
                $('.required-show').text('');
                $('#hasSubSyn').val(0);
                return false;
            }
            $.ajax({
                url: "<?php echo e(URL::to('mutualAssessmentSummaryReport/getMaEvent')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    term_id: termId,
                    course_id: courseId,
                },
                beforeSend: function () {
                    $('#subSynId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SUB_SYN_OPT'); ?></option>");
                    $('.required-show').text('');
                    $('#hasSubSyn').val(0);
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#maEventId').html(res.html);
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

        $(document).on("change", "#maEventId", function () {
            $('#cmList,#fileUpload,#prvMarkingSheet').html('');
            var courseId = $("#courseId").val();
            var maEventId = $("#maEventId").val();
            if (maEventId == 0) {
                $('#synId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SYN_OPT'); ?></option>");
                $('#subSynId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SUB_SYN_OPT'); ?></option>");
                $('.required-show').text('');
                $('#hasSubSyn').val(0);
                return false;
            }

            $.ajax({
                url: "<?php echo e(URL::to('mutualAssessment/getSyn')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    course_id: courseId,
                    maEvent_id: maEventId
                },
                beforeSend: function () {
                    $('#subSynId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SUB_SYN_OPT'); ?></option>");
                    $('.required-show').text('');
                    $('#hasSubSyn').val(0);
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#synList').html(res.html);
                    $('.js-source-states').select2();
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    toastr.error('<?php echo app('translator')->get("label.SOMETHING_WENT_WRONG"); ?>', 'Error', options);
                    App.unblockUI();
                }
            });//ajax
        });

        $(document).on("change", "#synId", function () {
            var courseId = $("#courseId").val();
            var synId = $("#synId").val();
            if (synId == 0) {
                $('#subSynId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SUB_SYN_OPT'); ?></option>");
                $('.required-show').text('');
                $('#hasSubSyn').val(0);
                return false;
            }
            $.ajax({
                url: "<?php echo e(URL::to('mutualAssessmentSummaryReport/getsubSyn')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    course_id: courseId,
                    syn_id: synId,
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    if (res.html != '') {
                        $('#subSynId').html(res.html);
                        $('.required-show').text('*');
                        $('#hasSubSyn').val(1);
                    } else {
                        $('#subSynId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SUB_SYN_OPT'); ?></option>");
                        $('.required-show').text('');
                        $('#hasSubSyn').val(0);
                    }
                    $('.js-source-states').select2();
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    toastr.error('<?php echo app('translator')->get("label.SOMETHING_WENT_WRONG"); ?>', 'Error', options);
                    App.unblockUI();
                }
            });//ajax
        });


    });
</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Xampp\htdocs\afwc\resources\views/report/mutualAssessmentSummary/index.blade.php ENDPATH**/ ?>