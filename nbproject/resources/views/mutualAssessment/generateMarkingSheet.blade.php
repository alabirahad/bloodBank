@extends('layouts.default.master') 
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i>@lang('label.GENERATE_MARKING_SHEET')
            </div>
        </div>
        <div class="portlet-body">

            <div class="form-horizontal">
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
                        <div id="cmContainer"></div>
                    </div>
                </div>

            </div>
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
                    $('#activeTerm,#subSyndicateList').html('');
                    $('#cmContainer').html('');
                    $('#markingSheetContainer').html('');
                    $('#previewBnt').html('');
                    $('#synList').html('');
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#activeTerm').html(res.html);
                    $('.js-source-states').select2();
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                    
                    if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, options);
                    } else {
                        toastr.error('@lang("label.SOMETHING_WENT_WRONG")', 'Error', options);
                    }

                }
            });//ajax
        });

        $(document).on("change", "#maEventId", function () {
            $('#subSyndicateList,#cmContainer').html('');
            var courseId = $("#courseId").val();
            var maEventId = $("#maEventId").val();
            if (maEventId == 0) {
                $('#synList').html('');
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
                    $('#cmContainer,#subSyndicateList').html('');
                    $('#markingSheetContainer').html('');
                    $('#previewBnt').html('');
                    $('#synList').html('');
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

        $(document).on("change", "#synId", function () {
            var courseId = $("#courseId").val();
            var termId = $("#termId").val();
            var eventId = $("#maEventId").val();
            var synId = $("#synId").val();
            if (synId == 0) {
                $('#cmContainer').html('');
                $('#subSyndicateList').html('');
                return false;
            }
            $.ajax({
                url: "{{ URL::to('mutualAssessment/getCmAndSubSyndicate')}}",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    course_id: courseId,
                    term_id: termId,
                    syn_id: synId,
                    event_id: eventId,
                },
                beforeSend: function () {
                    $('#cmContainer,#subSyndicateList').html('');
                    $('#markingSheetContainer').html('');
                    $('#previewBnt').html('');
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#subSyndicateList').html(res.subSyndicateList);
                    $('#cmContainer').html(res.cmList);
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
            var eventId = $("#maEventId").val();
            var synId = $("#synId").val();
            var subSynId = $("#subSyndicateId").val();
            if (subSynId == 0) {
                $('#cmContainer').html('');
                return false;
            }
            $.ajax({
                url: "{{ URL::to('mutualAssessment/getCmbySubSyn')}}",
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
                    event_id: eventId,
                },
                beforeSend: function () {
                    $('#cmContainer').html('');
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#cmContainer').html(res.cmList);
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
            var subSynId = $("#subSynId").val();

            $.ajax({
                url: "{{ URL::to('mutualAssessment/getPreviewBtn')}}",
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
                    ma_event_id: eventId,
                    cm_id: cmId,
                },
                beforeSend: function () {
                    $('#previewBnt').html('');
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#previewBnt').html(res.html);
                    $('.js-source-states').select2();
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
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

                }
            });//ajax
        });

        $(document).on("click", ".previewMarkingSheet", function () {
            var courseId = $("#courseId").val();
            var termId = $("#termId").val();
            var eventId = $("#maEventId").val();
            var synId = $("#synId").val();
            var subSynId = $("#subSyndicateId").val();
            var cmId = $(this).attr("data-id");
            $.ajax({
                url: "{{ URL::to('mutualAssessment/previewMarkingSheet')}}",
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
                    ma_event_id: eventId,
                    cm_id: cmId,
                },
                beforeSend: function () {
                    $('#markingSheetContainer').html('');
                    $(this).prop("disabled", true);
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $("#markingSheetModal").modal();
                    $('#markingSheetContainer').html(res.html);
                    $("#previewMarkingSheet").prop("disabled", false);
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
                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                    } else if (jqXhr.status == 401) {
                        toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, options);
                    } else {
                        toastr.error('@lang("label.SOMETHING_WENT_WRONG")', 'Error', options);
                    }

                }
            });//ajax
        });

        $(document).on("click", "#generate", function () {
            $("#markingSheetModal").modal('hide');
            const delay = function () {
                var subSynId = $("#subSyndicateId").val();
                if ($("#subSyndicateId").hasClass('sub-syn') && subSynId != 0) {
                    $('#subSyndicateId').trigger('change');
                } else {
                    $('#synId').trigger('change');
                }
            }
            ;
            setTimeout(delay, 1000);

        });

        $(document).on("click", ".deliver-status", function () {
            var courseId = $("#courseId").val();
            var termId = $("#termId").val();
            var eventId = $("#maEventId").val();
            var synId = $("#synId").val();
            var cmId = $(this).attr("data-id");
            var subSynId = $("#subSyndicateId").val();
            var title = '';
            if ($(this).is(':checked')) {
                title = "This marking sheet will be marked as delivered."
            } else {
                title = "This marking sheet will be marked as not delivered."
            }

            swal({
                title: 'Are you sure?',
                text: title,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                cancelButtonColor: 'Crimson',
                confirmButtonText: 'Yes, Confirm',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ URL::to('mutualAssessment/changeDeliverStatus')}}",
                        type: "POST",
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            course_id: courseId,
                            term_id: termId,
                            event_id: eventId,
                            syn_id: synId,
                            sub_syn_id: subSynId,
                            cm_id: cmId,
                        },
                        beforeSend: function () {
                            App.blockUI({boxed: true});
                        },
                        success: function (response) {
                            App.unblockUI();
                            const delay = function () {
                                var subSynId = $("#subSyndicateId").val();
                                if ($("#subSyndicateId").hasClass('sub-syn') && subSynId != 0) {
                                    $('#subSyndicateId').trigger('change');
                                } else {
                                    $('#synId').trigger('change');
                                }
                            };
                            setTimeout(delay, 10);
                            toastr.success(response.message, response.heading, options);
                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {
                            App.unblockUI();
                            if (jqXhr.status == 400) {
                                toastr.error(jqXhr.responseJSON.message, jqXhr.responseJSON.heading, options);
                            } else {
                                toastr.error('@lang("label.SOMETHING_WENT_WRONG")', 'Error', options);
                            }
                        }
                    }); //ajax
                } else {
                    const delay = function () {
                        var subSynId = $("#subSyndicateId").val();
                        if (subSynId != 0) {
                            $('#subSyndicateId').trigger('change');
                        } else {
                            $('#synId').trigger('change');
                        }
                    };
                    setTimeout(delay, 10);
                }
            });
        });
    });
</script>

@stop