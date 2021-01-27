 
<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i><?php echo app('translator')->get('label.RELATE_EVENT_TO_APPT_MATRIX'); ?>
            </div>
        </div>

        <div class="portlet-body">
            <?php echo Form::open(array('group' => 'form', 'url' => '#','class' => 'form-horizontal','id' => 'submitForm')); ?>

            <div class="row">
                <div class="col-md-12">

                    <div class="form-group">
                        <label class="control-label col-md-4" for="trainingYearId"><?php echo app('translator')->get('label.TRAINING_YEAR'); ?> :</label>
                        <div class="col-md-8">
                            <div class="control-label pull-left"> <strong> <?php echo e($activeTrainingYearInfo->name); ?> </strong></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4" for="courseId"><?php echo app('translator')->get('label.COURSE'); ?> :<span class="text-danger"> *</span></label>
                        <div class="col-md-4">
                            <?php echo Form::select('course_id', $courseList, null, ['class' => 'form-control js-source-states', 'id' => 'courseId']); ?>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4" for="termId"><?php echo app('translator')->get('label.TERM'); ?> :<span class="text-danger"> *</span></label>
                        <div class="col-md-4">
                            <?php echo Form::select('term_id', $termList, null, ['class' => 'form-control js-source-states', 'id' => 'termId']); ?>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4" for="eventId"><?php echo app('translator')->get('label.EVENT'); ?> :<span class="text-danger"> *</span></label>
                        <div class="col-md-4">
                            <?php echo Form::select('event_id', $eventList, null, ['class' => 'form-control js-source-states', 'id' => 'eventId']); ?>

                        </div>
                    </div>

                    <!--get sub event list or Appt Matrix -->
                    <div id="showSubEventApptMatrix"></div>

                    <!--get sub sub event list or Appt Matrix -->
                    <div id="showSubSubEventApptMatrix"></div>

                    <!--get sub sub sub event list or Appt Matrixs -->
                    <div id="showSubSubSubEventApptMatrix"></div>

                    <!--get  Appt Matrix -->
                    <div id="showApptMatrix"></div>

                    

                </div>
            </div>
            <?php echo Form::close(); ?>

        </div>
    </div>

</div>

<!--Assigned Sub Sub Event list-->
<div class="modal fade" id="modalAssignedSubSubSubEvent" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showAssignedSubSubSubEvent">

        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $(document).on("change", "#courseId", function () {

            var courseId = $("#courseId").val();

            $('#showSubEventApptMatrix').html('');
            $('#showSubSubEventApptMatrix').html('');
            $('#showSubSubSubEventApptMatrix').html('');
            $('#showApptMatrix').html('');
            $('#showAppt').html('');
            $('#termId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_TERM_OPT'); ?></option>");
            $('#eventId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_EVENT_OPT'); ?></option>");
            $('#subEventId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SUB_EVENT_OPT'); ?></option>");
            $('#subSubEventId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SUB_SUB_EVENT_OPT'); ?></option>");
            $('#subSubSubEventId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SUB_SUB_SUB_EVENT_OPT'); ?></option>");

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null
            };

            $.ajax({
                url: "<?php echo e(URL::to('eventToApptMatrix/getTerm')); ?>",
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
                    toastr.error('<?php echo app('translator')->get("label.SOMETHING_WENT_WRONG"); ?>', 'Error', options);
                    App.unblockUI();
                }
            });//ajax
        });

        $(document).on("change", "#termId", function () {

            var courseId = $("#courseId").val();
            var termId = $("#termId").val();

            $('#showSubEventApptMatrix').html('');
            $('#showSubSubEventApptMatrix').html('');
            $('#showSubSubSubEventApptMatrix').html('');
            $('#showApptMatrix').html('');
            $('#showAppt').html('');
            $('#eventId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_EVENT_OPT'); ?></option>");
            $('#subEventId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SUB_EVENT_OPT'); ?></option>");
            $('#subSubEventId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SUB_SUB_EVENT_OPT'); ?></option>");
            $('#subSubSubEventId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SUB_SUB_SUB_EVENT_OPT'); ?></option>");
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null
            };

            $.ajax({
                url: "<?php echo e(URL::to('eventToApptMatrix/getEvent')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    course_id: courseId,
                    term_id: termId,
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#eventId').html(res.html);
                    $('.js-source-states').select2();
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    toastr.error('<?php echo app('translator')->get("label.SOMETHING_WENT_WRONG"); ?>', 'Error', options);
                    App.unblockUI();
                }
            });//ajax
        });

        $(document).on("change", "#eventId", function () {

            var courseId = $("#courseId").val();
            var termId = $("#termId").val();
            var eventId = $("#eventId").val();

            $('#showSubEventApptMatrix').html('');
            $('#showSubSubEventApptMatrix').html('');
            $('#showSubSubSubEventApptMatrix').html('');
            $('#showApptMatrix').html('');
            $('#showAppt').html('');
            $('#subEventId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SUB_EVENT_OPT'); ?></option>");
            $('#subSubEventId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SUB_SUB_EVENT_OPT'); ?></option>");
            $('#subSubSubEventId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SUB_SUB_SUB_EVENT_OPT'); ?></option>");

            if (eventId == '0') {
                $('#showApptMatrix').html('');
                return false;
            }

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null
            };

            $.ajax({
                url: "<?php echo e(URL::to('eventToApptMatrix/getSubEventApptMatrix')); ?>",
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
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showSubEventApptMatrix').html(res.html);
                    $('.js-source-states').select2();
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

            $('#showSubSubEventApptMatrix').html('');
            $('#showSubSubSubEventApptMatrix').html('');
            $('#showApptMatrix').html('');
            $('#showAppt').html('');
            $('#subSubEventId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SUB_SUB_EVENT_OPT'); ?></option>");
            $('#subSubSubEventId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SUB_SUB_SUB_EVENT_OPT'); ?></option>");

            if (subEventId == '0') {
                $('#showApptMatrix').html('');
                return false;
            }

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null
            };

            $.ajax({
                url: "<?php echo e(URL::to('eventToApptMatrix/getSubSubEventApptMatrix')); ?>",
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
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showSubSubEventApptMatrix').html(res.html);
                    $('.js-source-states').select2();
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

            $('#showSubSubSubEventApptMatrix').html('');
            $('#showApptMatrix').html('');
            $('#showAppt').html('');
            $('#subSubSubEventId').html("<option value='0'><?php echo app('translator')->get('label.SELECT_SUB_SUB_SUB_EVENT_OPT'); ?></option>");

            if (subSubEventId == '0') {
                $('#showApptMatrix').html('');
                return false;
            }

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null
            };

            $.ajax({
                url: "<?php echo e(URL::to('eventToApptMatrix/getSubSubSubEventApptMatrix')); ?>",
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
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showSubSubSubEventApptMatrix').html(res.html);
                    $('.js-source-states').select2();
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
                $('#showApptMatrix').html('');
                return false;
            }
            
            $('#showAppt').html('');

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null
            };

            $.ajax({
                url: "<?php echo e(URL::to('eventToApptMatrix/getApptMatrix')); ?>",
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
                    $('#showApptMatrix').html(res.html);
                    $('.js-source-states').select2();
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    toastr.error('<?php echo app('translator')->get("label.SOMETHING_WENT_WRONG"); ?>', 'Error', options);
                    App.unblockUI();
                }
            });//ajax
        });

        $(document).on('click', '.button-submit', function (e) {
            e.preventDefault();
            var form_data = new FormData($('#submitForm')[0]);
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null
            };
            swal({
                title: 'Are you sure?',
                imageUrl: "<?php echo e(asset('public/img/decision.jpg')); ?>",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "<?php echo e(URL::to('eventToApptMatrix/saveEventToApptMatrix')); ?>",
                        type: "POST",
                        datatype: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function (res) {
                            toastr.success('<?php echo app('translator')->get("label.APPT_MATRIX_HAS_BEEN_RELATED_TO_THIS_EVENT"); ?>', res, options);
                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {
                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                                toastr.error(jqXhr.responseJSON.message, '', options);
                            } else {
                                toastr.error('Error', 'Something went wrong', options);
                            }
                            App.unblockUI();
                        }
                    });
                }
            });
        });
    });

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Xampp\htdocs\afwc\resources\views/eventToApptMatrix/index.blade.php ENDPATH**/ ?>