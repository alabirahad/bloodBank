@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i>@lang('label.RELATE_SUBJECT_TO_COURSE')
            </div>
        </div>

        <div class="portlet-body">
            {!! Form::open(array('group' => 'form', 'url' => '#','class' => 'form-horizontal','id' => 'submitForm')) !!}
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
                            <div id="showBatch" >
                                {!! Form::select('course_id', $courseList, null,  ['class' => 'form-control js-source-states', 'id' => 'courseId']) !!}
                                <span class="text-danger">{{ $errors->first('course_id') }}</span>
                            </div>
                        </div>
                    </div>
                    <!--get Subject data-->
                    <div id="showSubject"></div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

</div>
<script type="text/javascript">
    $(function () {
        //get module
        $(document).on("change", "#courseId", function () {
            var trainingYearId = $("#trainingYearId").val();
            var courseId = $("#courseId").val();

            if (courseId === '0') {
                $('#showSubject').html('');
                return false;
            }
            $.ajax({
                url: "{{ URL::to('subjectToCourse/getSubject')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    course_id: courseId,
                    training_year_id: trainingYearId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#showSubject').html(res.html);
                    $('.tooltips').tooltip();
                    $(".js-source-states").select2();
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
//                    App.unblockUI();
                }
            });//ajax
            App.unblockUI();
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
                        url: "{{URL::to('subjectToCourse/saveSubject')}}",
                        type: "POST",
                        datatype: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function (res) {
                            toastr.success(res, 'Subject has been related with this Batch', options);
                            $(document).trigger("change", "#courseId");
//                            var trainingYearId = $("#trainingYearId").val();
//                            var courseId = $("#courseId").val();
//                            $.ajax({
//                                url: "{{ URL::to('subjectToCourse/getSubject')}}",
//                                type: "POST",
//                                dataType: "json",
//                                headers: {
//                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                                },
//                                data: {
//                                    course_id: courseId,
//                                    training_year_id: trainingYearId
//                                },
//                                beforeSend: function () {
//                                    App.blockUI({boxed: true});
//                                },
//                                success: function (res) {
//                                    $('#showSubject').html(res.html);
//                                    $('.tooltips').tooltip();
//                                    $(".js-source-states").select2();
//                                    App.unblockUI();
//                                },
//                                error: function (jqXhr, ajaxOptions, thrownError) {
////                    App.unblockUI();
//                                }
//                            });//ajax


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
@stop