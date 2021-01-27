@extends('layouts.default.master') 
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i>@lang('label.IMPORT_MARKING_SHEET')
            </div>
        </div>
        <div class="portlet-body">
            {!! Form::open(array('group' => 'form', 'url' => '#','class' => 'form-horizontal', 'id' => 'submitForm', 'files' => true)) !!}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="trainingYearId">@lang('label.TRAINING_YEAR') :</label>
                        <div class="col-md-8">
                            <div class="control-label pull-left"> <strong> {{$activeTrainingYearInfo->name}} </strong></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="courseId">@lang('label.COURSE') :<span class="text-danger"> *</span></label>
                        <div class="col-md-4">
                            {!! Form::select('course_id', $courseList, null, ['class' => 'form-control js-source-states', 'id' => 'courseId']) !!}
                        </div>
                    </div>
                    <div id="activeTerm"></div>
                    <div id="synList"></div>
                    <div id="subSyndicateList"></div>
                    <div id="cmList"></div>
                    <div id="fileUpload"></div>
                    <div id="cmContainer"></div>
                    <div id="prvMarkingSheet" class="mt-15"></div>

                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>


<!--Assigned Sub Event list-->
<div class="modal fade" id="markingSheetModal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div id="markingSheetContainer">

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
        $(document).on("change", "#courseId", function () {
            var courseId = $("#courseId").val();
            if (courseId == '0') {
                return false;
            }
            $.ajax({
                url: "{{ URL::to('mutualAssessment/getTerm')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    course_id: courseId,
                },
                beforeSend: function () {
                    $('#activeTerm').html('');
                    $('#cmContainer').html('');
                    $('#synList').html('');
                    $('#subSyndicateList').html('');
                    $('#fileUpload').html('');
                    $('#prvMarkingSheet').html('');
                    $('#cmList').html('');

                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#activeTerm').html(res.html);
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
                        toastr.error('@lang("label.SOMETHING_WENT_WRONG")', 'Error', options);
                    }

                }
            });//ajax
        });


        $(document).on("change", "#synId", function () {
            var courseId = $("#courseId").val();
            var termId = $("#termId").val();
            var synId = $("#synId").val();
            if (synId == 0) {
                $('#cmList').html('');
                $('#subSyndicateList').html('');
                $('#cmContainer').html('');
                $('#fileUpload').html('');
                $('#prvMarkingSheet').html('');
                return false;
            }
            $.ajax({
                url: "{{ URL::to('mutualAssessment/getSubsynAndCmList')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    course_id: courseId,
                    term_id: termId,
                    syn_id: synId,
                },
                beforeSend: function () {
                    $('#subSyndicateList,#cmList').html('');
                    $('#cmContainer').html('');
                    $('#previewBnt').html('');
                    $('#fileUpload').html('');
                    $('#prvMarkingSheet').html('');
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#subSyndicateList').html(res.subSynList);
                    $('#cmList').html(res.cmList);
                    $('.js-source-states').select2();
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    toastr.error('@lang("label.SOMETHING_WENT_WRONG")', 'Error', options);
                    App.unblockUI();
                }
            });//ajax
        });

        $(document).on("change", "#subSyndicateId", function () {
            var courseId = $("#courseId").val();
            var termId = $("#termId").val();
            var synId = $("#synId").val();
            var subSynId = $("#subSyndicateId").val();
            if (subSynId == 0) {
                $('#cmList').html('');
                $('#cmContainer').html('');
                $('#fileUpload').html('');
                $('#prvMarkingSheet').html('');
                return false;
            }
            $.ajax({
                url: "{{ URL::to('mutualAssessment/getCmListBySubSyn')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    course_id: courseId,
                    term_id: termId,
                    syn_id: synId,
                    sub_syn_id: subSynId,
                },
                beforeSend: function () {
                    $('#cmList').html('');
                    $('#cmContainer').html('');
                    $('#previewBnt').html('');
                    $('#fileUpload').html('');
                    $('#prvMarkingSheet').html('');
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#cmList').html(res.cmList);
                    $('.js-source-states').select2();
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    toastr.error('@lang("label.SOMETHING_WENT_WRONG")', 'Error', options);
                    App.unblockUI();
                }
            });//ajax
        });

        $(document).on("change", "#cmId", function () {
            var cmId = $(this).val();
            var courseId = $("#courseId").val();
            var termId = $("#termId").val();
            var eventId = $("#maEventId").val();
            var synId = $("#synId").val();
            var subSynId = $("#subSyndicateId").val();
            $.ajax({
                url: "{{ URL::to('mutualAssessment/getFileUploader')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    course_id: courseId,
                    term_id: termId,
                    ma_event_id: eventId,
                    syn_id: synId,
                    sub_syn_id: subSynId,
                    cm_id: cmId,
                },
                beforeSend: function () {
                    $('#fileUpload').html('');
                    $('#prvMarkingSheet').html('');
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#fileUpload').html(res.fileUpload);
                    $('#prvMarkingSheet').html(res.markingSheet);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    var errorsHtml = '';
                    if (jqXhr.status == 400) {
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, {"closeButton": true});
                    } else {
                        toastr.error('@lang("label.SOMETHING_WENT_WRONG")', 'Error', options);
                    }
                    App.unblockUI();
                }
            });//ajax
        });


        $(document).on("click", "#import", function () {
            var fromData = new FormData($('#submitForm')[0]);
            $.ajax({
                url: "{{ URL::to('mutualAssessment/import')}}",
                type: "POST",
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: fromData,
                beforeSend: function () {
                    $(this).prop("disabled", true);
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#prvMarkingSheet').html(res.markingSheet);
                    $("#import").prop("disabled", false);
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    $("#import").prop("disabled", false);
                    var errorsHtml = '';
                    if (jqXhr.status == 400) {
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        var errors = jqXhr.responseJSON.errormessage;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else {
                        toastr.error('@lang("label.SOMETHING_WENT_WRONG")', 'Error', options);
                    }
                    App.unblockUI();
                }
            });//ajax
        });



        $(document).on("click", ".submit-form", function () {
            var saveStatus = $(this).attr('data-id');
            var formData = new FormData($('#submitForm')[0]);
            formData.append('save_status', saveStatus);
            $.ajax({
                url: "{{ URL::to('mutualAssessment/saveImportedData')}}",
                type: "POST",
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                beforeSend: function () {
                    $(this).prop("disabled", true);
                    App.blockUI({boxed: true});
                },
                success: function (response) {
                    $("#submitImportedData").prop("disabled", false);

//               $("#import").trigger('click');
                    if (response.saveStatus == 2) {
                        $('.buttonHide').hide();
                    }
                    App.unblockUI();
                    toastr.success(response.message, response.heading, options);
                    setInterval(function () {
                        // write statement
                    }, 8000)
//                location.reload();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    var errorsHtml = '';
                    if (jqXhr.status == 400) {
                        var errors = jqXhr.responseJSON.message;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        var errors = jqXhr.responseJSON.errormessage;
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value + '</li>';
                        });
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else {
                        toastr.error('@lang("label.SOMETHING_WENT_WRONG")', 'Error', options);
                    }
                    $("#submitImportedData").prop("disabled", false);
                    App.unblockUI();
                }
            });//ajax
        });

        $(document).on("change", "#maEventId", function () {
            $('#cmList,#fileUpload,#prvMarkingSheet').html('');
            var courseId = $("#courseId").val();
            var maEventId = $("#maEventId").val();
            if (maEventId == 0) {
                $('#synList').html('');
                $('#subSyndicateList').html('');
                return false;
            }

            $.ajax({
                url: "{{ URL::to('mutualAssessment/getSyn')}}",
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
                    $('#cmContainer').html('');
                    $('#markingSheetContainer').html('');
                    $('#synList').html('');
                    $('#subSyndicateList').html('');
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#synList').html(res.html);
                    $('.js-source-states').select2();
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    toastr.error('@lang("label.SOMETHING_WENT_WRONG")', 'Error', options);
                    App.unblockUI();
                }
            });//ajax
        });
    });

</script>


@stop