
<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-book"></i>
                <?php if(in_array(Auth::user()->group_id,[2])): ?>
                <?php echo app('translator')->get('label.MODERATION_MARKING'); ?>
                <?php else: ?>
                <?php echo app('translator')->get('label.COMDT_MODERATION_MARKING'); ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="portlet-body">
            <?php echo Form::open(array('group' => 'form', 'url' => '#','class' => 'form-horizontal','id' => 'submitForm')); ?>

            <div class="row">
                <div class="col-md-12">

                    <div class="form-group">
                        <label class="control-label col-md-4" for="trainingYearId"><?php echo app('translator')->get('label.TRAINING_YEAR'); ?> :</label>
                        <div class="col-md-4">
                            <div class="control-label pull-left"> <strong> <?php echo e($activeTrainingYearInfo->name); ?> </strong></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="courseId"><?php echo app('translator')->get('label.COURSE'); ?> :<span class="text-danger"> *</span></label>
                        <div class="col-md-4">
                            <?php echo Form::select('course_id', $courseList, Request::get('course_id'), ['class' => 'form-control js-source-states', 'id' => 'courseId']); ?>

                        </div>
                    </div>
                    <div id="showTermEvent">

                    </div>

                    <div id="showSubEventOrCmList">

                    </div>
                    <div id="showSubSubEventOrCmList">

                    </div>
                    <div id="showSubSubSubEventOrCmList">

                    </div>
                    <div id="showCmList">

                    </div>
                    
                    <!-- DS Marking Summary modal -->
                    <div class="modal fade test" id="modalDsMarkingSummary" tabindex="-1" role="basic" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div id="showDsMarkingSummary"></div>
                        </div>
                    </div>
                    <!-- End DS Marking Summary modal -->
                    
                    <!-- Unlock message modal -->
                    <div class="modal fade test" id="modalUnlockMessage" tabindex="-1" role="basic" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div id="showMessage"></div>
                        </div>
                    </div>
                    <!-- End Unlock message modal -->
                </div>
            </div>
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

        $(document).on("change", "#courseId", function () {
            var courseId = $("#courseId").val();
            if (courseId == '0') {
                $('#showTermEvent').html('');
                $('#showSubEventOrCmList').html('');
                $('#showSubSubEventOrCmList').html('');
                $('#showSubSubSubEventOrCmList').html('');
                $('#showCmList').html('');
                return false;
            }
            $.ajax({
                url: "<?php echo e(URL::to('comdtModerationMarking/getTermEvent')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    course_id: courseId,
                },
                success: function (res) {
                    $('#showTermEvent').html(res.html);
                    $('.js-source-states').select2();
                    App.unblockUI();

                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, 'Error', options);
                    } else {
                        toastr.error('<?php echo app('translator')->get("label.SOMETHING_WENT_WRONG"); ?>', 'Error', options);
                    }
                    App.unblockUI();
                }
            });//ajax
        });

        $(document).on("change", "#eventId", function () {
            var courseId = $("#courseId").val();
            var termId = $("#termId").val();
            var eventId = $("#eventId").val();
            if (eventId == '0') {
                $('#subEventId').html("<select><option value='0'><?php echo app('translator')->get('label.SELECT_SUB_EVENT_OPT'); ?></option></select>");
                $('#showSubEventOrCmList').html('');
                $('#showSubSubEventOrCmList').html('');
                $('#showSubSubSubEventOrCmList').html('');
                $('#showCmList').html('');
                return false;
            }

            $.ajax({
                url: "<?php echo e(URL::to('comdtModerationMarking/getSubEvent')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    course_id: courseId,
                    term_id: termId,
                    event_id: eventId,
                },
                beforeSend: function () {
                    $('#showSubEventOrCmList').html('');
                    $('#showSubSubEventOrCmList').html('');
                    $('#showSubSubSubEventOrCmList').html('');
                    $('#showCmList').html('');
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showSubEventOrCmList').html(res.html);
                    $('.js-source-states').select2();
                    $('.tooltips').tooltip();
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    toastr.error('<?php echo app('translator')->get("label.SOMETHING_WENT_WRONG"); ?>', 'Error', options);
                    App.unblockUI();
                }
            });//ajax
        });

        $(document).on("change", "#subEventId", function () {
            var courseId = $("#courseId").val();
            var termId = $("#termId").val();
            var eventId = $("#eventId").val();
            var subEventId = $("#subEventId").val();
            if (subEventId == '0') {
                $('#subSubEventId').html("<select><option value='0'><?php echo app('translator')->get('label.SELECT_SUB_SUB_EVENT_OPT'); ?></option></select>");
                $('#showSubSubSubEventOrCmList').html('');
                $('#showSubSubEventOrCmList').html('');
                $('#showCmList').html('');
                return false;
            }

            $.ajax({
                url: "<?php echo e(URL::to('comdtModerationMarking/getSubSubEvent')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    course_id: courseId,
                    term_id: termId,
                    event_id: eventId,
                    sub_event_id: subEventId,
                },
                beforeSend: function () {
                    $('#showSubSubSubEventOrCmList').html('');
                    $('#showSubSubEventOrCmList').html('');
                    $('#showCmList').html('');
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showSubSubEventOrCmList').html(res.html);
                    $('.js-source-states').select2();
                    $('.tooltips').tooltip();
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    toastr.error('<?php echo app('translator')->get("label.SOMETHING_WENT_WRONG"); ?>', 'Error', options);
                    App.unblockUI();
                }
            });//ajax
        });

        $(document).on("change", "#subSubEventId", function () {
            var courseId = $("#courseId").val();
            var termId = $("#termId").val();
            var eventId = $("#eventId").val();
            var subEventId = $("#subEventId").val();
            var subSubEventId = $("#subSubEventId").val();
            if (subSubEventId == '0') {
                $('#subSubSubEventId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SUB_SUB_SUB_EVENT_OPT'); ?></option>");
                $('#showCmList').html('');
                $('#showSubSubSubEventOrCmList').html('');
                return false;
            }

            $.ajax({
                url: "<?php echo e(URL::to('comdtModerationMarking/getSubSubSubEvent')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    course_id: courseId,
                    term_id: termId,
                    event_id: eventId,
                    sub_event_id: subEventId,
                    sub_sub_event_id: subSubEventId,
                },
                beforeSend: function () {
                    $('#showSubSubSubEventOrCmList').html('');
                    $('#showCmList').html('');
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showSubSubSubEventOrCmList').html(res.html);
                    $('.js-source-states').select2();
                    $('.tooltips').tooltip();
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    toastr.error('<?php echo app('translator')->get("label.SOMETHING_WENT_WRONG"); ?>', 'Error', options);
                    App.unblockUI();
                }
            });//ajax
        });

        $(document).on("change", "#subSubSubEventId", function () {
            var courseId = $("#courseId").val();
            var termId = $("#termId").val();
            var eventId = $("#eventId").val();
            var subEventId = $("#subEventId").val();
            var subSubEventId = $("#subSubEventId").val();
            var subSubSubEventId = $("#subSubSubEventId").val();
            if (subSubSubEventId == '0') {
                $('#showCmList').html('');
                return false;
            }

            $.ajax({
                url: "<?php echo e(URL::to('comdtModerationMarking/showMarkingCmList')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    course_id: courseId,
                    term_id: termId,
                    event_id: eventId,
                    sub_event_id: subEventId,
                    sub_sub_event_id: subSubEventId,
                    sub_sub_sub_event_id: subSubSubEventId,
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showCmList').html(res.html);
                    $('.js-source-states').select2();
                    $('.tooltips').tooltip();
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    toastr.error('<?php echo app('translator')->get("label.SOMETHING_WENT_WRONG"); ?>', 'Error', options);
                    App.unblockUI();
                }
            });//ajax
        });
        
        //DS Marking Summary Modal
        $(document).on('click', '#buttonDsMarkinSummary', function (e) {
            e.preventDefault();

            var form_data = new FormData($('#submitForm')[0]);
            $.ajax({
                url: "<?php echo e(URL::to('comdtModerationMarking/getDsMarkingSummary')); ?>",
                type: "POST",
                datatype: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                beforeSend: function () {
                    $('#showDsMarkingSummary').html('');
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showDsMarkingSummary').html(res.html);
                    $('.tooltips').tooltip();
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, 'Error', options);
                    } else {
                        toastr.error('<?php echo app('translator')->get("label.SOMETHING_WENT_WRONG"); ?>', 'Error', options);
                    }
                    App.unblockUI();
                }

            });
        });

//form submit
        $(document).on('click', '.button-submit', function (e) {
            e.preventDefault();
            var dataId = $(this).attr('data-id');
            var form_data = new FormData($('#submitForm')[0]);
            form_data.append('data_id', dataId);

            swal({
                title: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "<?php echo e(URL::to('comdtModerationMarking/saveComdtModerationMarking')); ?>",
                        type: "POST",
                        datatype: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        beforeSend: function () {
                            $('.button-submit').prop('disabled', true);
                            App.blockUI({boxed: true});
                        },
                        success: function (res) {
                            $('.button-submit').prop('disabled', false);
                            toastr.success(res.message, res.heading, options);

                            var courseId = res.loadData.course_id;
                            var termId = res.loadData.term_id;
                            var eventId = res.loadData.event_id;
                            var subEventId = res.loadData.sub_event_id;
                            var subSubEventId = res.loadData.sub_sub_event_id;
                            var subSubSubEventId = res.loadData.sub_sub_sub_event_id;
                            $.ajax({
                                url: "<?php echo e(URL::to('comdtModerationMarking/showMarkingCmList')); ?>",
                                type: "POST",
                                dataType: "json",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    course_id: courseId,
                                    term_id: termId,
                                    event_id: eventId,
                                    sub_event_id: subEventId,
                                    sub_sub_event_id: subSubEventId,
                                    sub_sub_sub_event_id: subSubSubEventId,
                                },
                                beforeSend: function () {
                                    if (subEventId == '0') {
                                        $('#showSubEventOrCmList').html('');
                                    }
                                    if (subSubEventId == '0') {
                                        $('#showSubSubEventOrCmList').html('');
                                    }
                                    if (subSubSubEventId == '0') {
                                        $('#showSubSubSubEventOrCmList').html('');
                                    }
                                    $('#showCmList').html('');
                                    App.blockUI({boxed: true});
                                },
                                success: function (res) {
                                    $('#showCmList').html(res.html);
                                    $('.js-source-states').select2();
                                    App.unblockUI();
                                },
                                error: function (jqXhr, ajaxOptions, thrownError) {
                                    toastr.error('<?php echo app('translator')->get("label.SOMETHING_WENT_WRONG"); ?>', 'Error', options);
                                    App.unblockUI();
                                }
                            });//ajax
                            App.unblockUI();
                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {
                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value[0] + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                                toastr.error(jqXhr.responseJSON.message, 'Error', options);
                            } else {
                                toastr.error('<?php echo app('translator')->get("label.SOMETHING_WENT_WRONG"); ?>', 'Error', options);
                            }
                            $('.button-submit').prop('disabled', false);
                            App.unblockUI();
                        }

                    });
                }
            });
        });

//Rquest for unlock
        $(document).on('click', '.request-for-unlock', function (e) {
            e.preventDefault();

            var form_data = new FormData($('#submitForm')[0]);

            $.ajax({
                url: "<?php echo e(URL::to('comdtModerationMarking/getRequestForUnlockModal')); ?>",
                type: "POST",
                datatype: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                beforeSend: function () {
                    $('#showMessage').html('');
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showMessage').html(res.html);
                    $('.tooltips').tooltip();
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    if (jqXhr.status == 400) {
                        var errorsHtml = '';
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, 'Error', options);
                    } else {
                        toastr.error('<?php echo app('translator')->get("label.SOMETHING_WENT_WRONG"); ?>', 'Error', options);
                    }
                    App.unblockUI();
                }

            });
        });

        $(document).on('click', '.save-request-for-unlock', function (e) {
            e.preventDefault();
            var unlockMessage = $("#unlockMsgId").val();
//            alert(unlockMessage); return false;
            var form_data = new FormData($('#submitForm')[0]);
            form_data.append('unlock_message', unlockMessage);

            swal({
                title: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "<?php echo e(URL::to('comdtModerationMarking/saveRequestForUnlock')); ?>",
                        type: "POST",
                        datatype: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function (res) {
                            $('.modal').modal('hide');
                            toastr.success(res, '<?php echo app('translator')->get("label.REQUEST_FOR_UNLOCK_HAS_BEEN_SENT_TO_COMDT_SUCCESSFULLY"); ?>', options);
                            var courseId = res.loadData.course_id;
                            var termId = res.loadData.term_id;
                            var eventId = res.loadData.event_id;
                            var subEventId = res.loadData.sub_event_id;
                            var subSubEventId = res.loadData.sub_sub_event_id;
                            var subSubSubEventId = res.loadData.sub_sub_sub_event_id;
                            $.ajax({
                                url: "<?php echo e(URL::to('comdtModerationMarking/showMarkingCmList')); ?>",
                                type: "POST",
                                dataType: "json",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    course_id: courseId,
                                    term_id: termId,
                                    event_id: eventId,
                                    sub_event_id: subEventId,
                                    sub_sub_event_id: subSubEventId,
                                    sub_sub_sub_event_id: subSubSubEventId,
                                },
                                beforeSend: function () {
                                    if (subEventId == '0') {
                                        $('#showSubEventOrCmList').html('');
                                    }
                                    if (subSubEventId == '0') {
                                        $('#showSubSubEventOrCmList').html('');
                                    }
                                    if (subSubSubEventId == '0') {
                                        $('#showSubSubSubEventOrCmList').html('');
                                    }
                                    $('#showCmList').html('');
                                    App.blockUI({boxed: true});
                                },
                                success: function (res) {
                                    $('#showCmList').html(res.html);
                                    $('.js-source-states').select2();
                                    App.unblockUI();
                                },
                                error: function (jqXhr, ajaxOptions, thrownError) {
                                    toastr.error('<?php echo app('translator')->get("label.SOMETHING_WENT_WRONG"); ?>', 'Error', options);
                                    App.unblockUI();
                                }
                            });//ajax

                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {
                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value[0] + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                                toastr.error(jqXhr.responseJSON.message, 'Error', options);
                            } else {
                                toastr.error('<?php echo app('translator')->get("label.SOMETHING_WENT_WRONG"); ?>', 'Error', options);
                            }
                            App.unblockUI();
                        }

                    });
                }
            });
        });
    });
</script>
<script src="<?php echo e(asset('public/js/custom.js')); ?>" type="text/javascript"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Xampp\htdocs\afwc\resources\views/comdtModerationMarking/index.blade.php ENDPATH**/ ?>