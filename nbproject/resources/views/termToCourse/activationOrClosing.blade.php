@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i>@lang('label.TERM_SCHEDULING_ACTIVATION_CLOSING')
            </div>
        </div>
        <div class="portlet-body">
            {!! Form::open(array('group' => 'form', 'url' => '#','class' => 'form-horizontal','id' => 'submitForm')) !!}
            {{csrf_field()}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="trainingYearId">@lang('label.TRAINING_YEAR') :</label>
                        <div class="col-md-8">
                            <div class="control-label pull-left"> <strong> {{$activeTrainingYear->name}} </strong>
                                {!! Form::hidden('training_year_id', $activeTrainingYear->id,['id'=>'trainingYearId']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="courseId">@lang('label.COURSE') :<span class="text-danger"> *</span></label>
                        <div class="col-md-4">
                            {!! Form::select('course_id', $courseList, null, ['class' => 'form-control js-source-states', 'id' => 'courseId']) !!}
                        </div>
                    </div>
                    <div id="termSchedule">

                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('change', '#courseId', function () {
            var trainingYearId = $("#trainingYearId").val();
            var courseId = $("#courseId").val();
            if (courseId == '0' || trainingYearId == '0') {
                $('#termSchedule').html('');
                return false;
            }
            $.ajax({
                url: "{{URL::to('termToCourse/getActiveOrClose')}}",
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
                    $("#termSchedule").html(res.html);
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


        $(document).on('click', '.activeInactive', function (e) {
            e.preventDefault();
            var termId = $(this).data('term-id');
            var courseId = $(this).data('course-id');
            var status = $(this).data('status');
            var id = $(this).data('id');
            var confirm = 'Activate/Initiate';
            if (status == '2') {
                confirm = 'Close';
            }
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            swal({
                title: 'Are you sure?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, ' + confirm,
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{URL::to('termToCourse/activeInactive')}}",
                        type: "POST",
                        datatype: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            course_id: courseId,
                            term_id: termId,
                            status: status,
                            id: id,
                        },
                        success: function (res) {
                            //console.log(res);return false;
                            toastr.success(res.message, 'Success', options);
                            // setTimeout(location.reload.bind(location), 2000);
                            var trainingYearId = $("#trainingYearId").val();
                            var courseId = $("#courseId").val();
                            if (courseId == '0' || trainingYearId == '0') {
                                $('#termSchedule').html('');
                                return false;
                            }
                            $.ajax({
                                url: "{{URL::to('termToCourse/getActiveOrClose')}}",
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
                                    $("#termSchedule").html(res.html);
                                    $(".previnfo").html('');
                                    $('.tooltips').tooltip();
                                    App.unblockUI();
                                },
                                error: function (jqXhr, ajaxOptions, thrownError) {
                                    toastr.error('Error', 'Something went wrong', options);
                                    App.unblockUI();
                                }
                            });

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


        $(document).on('click', '.redioAcIn', function (e) {
            e.preventDefault();
            var termId = $(this).data('term-id');
            var courseId = $(this).data('course-id');
            var status = $(this).data('status');
            var id = $(this).data('id');

            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };
            $.ajax({
                url: "{{URL::to('termToCourse/redioAcIn')}}",
                type: "POST",
                datatype: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    course_id: courseId,
                    term_id: termId,
                    status: status,
                    id: id,
                },
                success: function (res) {
                    //console.log(res);return false;
                    toastr.success(res.message, 'Success', options);
                    // setTimeout(location.reload.bind(location), 2000);
                    var trainingYearId = $("#trainingYearId").val();
                    var courseId = $("#courseId").val();
                    if (courseId == '0' || trainingYearId == '0') {
                        $('#termSchedule').html('');
                        return false;
                    }
                    $.ajax({
                        url: "{{URL::to('termToCourse/getActiveOrClose')}}",
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
                            $("#termSchedule").html(res.html);
                            $(".previnfo").html('');
                            $('.tooltips').tooltip();
                            App.unblockUI();
                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {
                            toastr.error('Error', 'Something went wrong', options);
                            App.unblockUI();
                        }
                    });

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
        });

    });

</script>
@stop