
<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i><?php echo app('translator')->get('label.TERM_SCHEDULING'); ?>
            </div>
        </div>
        <div class="portlet-body">
            <?php echo Form::open(array('group' => 'form', 'url' => '#','class' => 'form-horizontal','id' => 'submitForm')); ?>

            <?php echo e(csrf_field()); ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="trainingYearId"><?php echo app('translator')->get('label.TRAINING_YEAR'); ?> :</label>
                        <div class="col-md-8">
                            <div class="control-label pull-left"> <strong> <?php echo e($activeTrainingYear->name); ?> </strong>
                                <?php echo Form::hidden('training_year_id', $activeTrainingYear->id,['id'=>'trainingYearId']); ?>

                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4" for="courseId"><?php echo app('translator')->get('label.COURSE'); ?> :<span class="text-danger"> *</span></label>
                        <div class="col-md-4">
                            <?php echo Form::select('course_id', $courseList, null, ['class' => 'form-control js-source-states', 'id' => 'courseId']); ?>

                        </div>
                    </div>
                    <div id="courseSchedule">

                    </div>
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
            onclick: null,
        };

        //function for no of weeks
        $(document).on('change', '.term-date', function () {
            var termId = $(this).data("term-id");
            var initialDate = new Date($('#initialDate-' + termId).val());
            var terminationDate = new Date($('#terminationDate-' + termId).val());
            if (terminationDate < initialDate) {
                swal({
                    title: "<?php echo app('translator')->get('label.TERMINATION_DATE_MUST_BE_GREATER_THAN_INITIAL_DATE'); ?> ",
                    text: "",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "<?php echo app('translator')->get('label.OK'); ?>",
                    closeOnConfirm: true,
                    closeOnCancel: true,
                }, function (isConfirm) {
                    if (isConfirm) {
                        $('#terminationDate-' + termId).val('');
                        $('noOfWeeks-' + termId).val('');
                    }
                });
                return false;
            }

            var weeks = Math.ceil((terminationDate - initialDate) / (24 * 3600 * 1000 * 7));

            if (isNaN(weeks)) {
                var weeks = '';
            }
            $("#noOfWeeks-" + termId).val(weeks);
        });




        $(document).on('click', '#buttonSubmit', function (e) {
            e.preventDefault();
            var form_data = new FormData($('#submitForm')[0]);
            swal({
                title: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'No, cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "<?php echo e(URL::to('termToCourse/saveCourse')); ?>",
                        type: "POST",
                        datatype: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function (res) {
                            toastr.success(res, "<?php echo app('translator')->get('label.TERM_HAS_BEEN_SCHEDULED_TO_THIS_COURSE'); ?>", options);
                            //App.blockUI({ boxed: false });
                            //setTimeout(location.reload.bind(location), 1000);

                            var trainingYearId = $("#trainingYearId").val();
                            var courseId = $("#courseId").val();
                            if (courseId == '0' || trainingYearId == '0') {
                                $('#courseSchedule').html('');
                                return false;
                            }
                            $.ajax({
                                url: "<?php echo e(URL::to('termToCourse/courseSchedule')); ?>",
                                type: "POST",
                                datatype: 'json',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    training_year_id: trainingYearId,
                                    course_id: courseId,

                                },
                                beforeSend: function () {
                                    App.blockUI({boxed: true});
                                },
                                success: function (res) {
                                    $("#courseSchedule").html(res.html);
                                    $(".previnfo").html('');
                                    $('.tooltips').tooltip();
                                    App.unblockUI();
                                },
                                error: function (jqXhr, ajaxOptions, thrownError) {
                                    // error
                                }
                            });
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
                                //toastr.error(jqXhr.responseJSON.message, '', options);
                                var errors = jqXhr.responseJSON.message;
                                var errorsHtml = '';
                                if (typeof (errors) === 'object') {
                                    $.each(errors, function (key, value) {
                                        errorsHtml += '<li>' + value + '</li>';
                                    });
                                    toastr.error(errorsHtml, '', options);
                                } else {
                                    toastr.error(jqXhr.responseJSON.message, '', options);
                                }
                            } else {
                                toastr.error('Error', 'Something went wrong', options);
                            }
                            App.unblockUI();
                        }
                    });
                }
            });
        });

        $(document).on('change', '#courseId', function () {
            var trainingYearId = $("#trainingYearId").val();
            var courseId = $("#courseId").val();
            if (courseId == '0' || trainingYearId == '0') {
                $('#courseSchedule').html('');
                return false;
            }
            $.ajax({
                url: "<?php echo e(URL::to('termToCourse/courseSchedule')); ?>",
                type: "POST",
                datatype: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    training_year_id: trainingYearId,
                    course_id: courseId,

                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $("#courseSchedule").html(res.html);
                    $(".previnfo").html('');
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
                        toastr.error(jqXhr.responseJSON.message, '', options);
                    } else {
                        toastr.error('Error', 'Something went wrong', options);
                    }
                    App.unblockUI();
                }
            });
        });

       
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Xampp\htdocs\afwc\resources\views/termToCourse/index.blade.php ENDPATH**/ ?>