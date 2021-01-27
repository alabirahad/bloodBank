<div class="row">
    <div class="col-md-12 table-responsive">
        <div class="webkit-scrollbar">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center">@lang('label.SL_NO')</th>
                        <th class="text-center">@lang('label.TERM')</th>
                        <th class="text-center">@lang('label.INITIAL_DATE')</th>
                        <th class="text-center">@lang('label.TERMINATION_DATE')</th>
                        <th class="text-center">@lang('label.NUMBER_OF_WEEK')</th>
                        <th class="text-center">@lang('label.ACTIVE')</th>
                        <th class="text-center">@lang('label.STATUS')</th>
                        <th class="text-center">@lang('label.ACTION')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!$activeInactiveTerm->isEmpty())
                    <?php $sl = 0; ?>
                    @foreach($activeInactiveTerm as $termInfo)
                    <?php
                    //            check and show previous value
                    $checked = '';
                    $radioChecked = '';
                    $disabled = '';
                    if (in_array($termInfo->status, ['0', '2'])) {
                        $disabled = 'disabled';
                    } elseif ($termInfo->active == '1') {
                        $radioChecked = 'checked';
                    }
                    ?>
                    <tr>
                        <td class="text-center vcenter">{!! ++$sl !!}</td>
                        <td class="vcenter text-center">{{$termInfo->term_name}}</td>
                        <td class="vcenter text-center">{{Helper::printDate($termInfo->initial_date)}}</td>
                        <td class="vcenter text-center">{{Helper::printDate($termInfo->termination_date)}}</td>
                        <td class="vcenter text-center">{{ $termInfo->number_of_week }}</td>
                        <td class="vcenter">

                            <div class="md-radio-list">
                                <div class="md-radio">
                                    <input class="redioAcIn md-radiobtn" type="radio" id="radio-{{$termInfo->id}}" name="admin_si" value="{{$termInfo->id}}" data-course-id="{!! !empty($termInfo->course_id)?$termInfo->course_id:'' !!}" data-term-id="{!! !empty($termInfo->term_id)?$termInfo->term_id:'' !!}" data-id="{!! !empty($termInfo->id)?$termInfo->id:'' !!}"  data-status="{!! $termInfo->status=='0'?'1':'0'!!}"  {{ $radioChecked }} {{$disabled}}>
                                    <label for="radio-{{$termInfo->id}}">
                                        <span class="inc"></span>
                                        <span class="check"></span>
                                        <span class="box"></span> @lang('label.YES')</label>
                                </div>
                            </div>
                        </td>
                        <td class="vcenter text-center">
                            @if($termInfo->status=='0')
                            <span class="label label-sm label-blue-soft">@lang('label.NOT_INITIATED')</span>
                            @elseif($termInfo->status=='2')
                            <span class="label label-sm label-red-intense">@lang('label.CLOSED')</span>
                            @else
                            <span class="label label-sm label-green-seagreen">@lang('label.INITIATED')</span>
                            @endif
                        </td>
                        <td class="vcenter text-center">
                            @if($termInfo->status=='0')
                            <button class="btn btn-success btn-xs activeIn tooltips activeInactive"  type="button" data-placement="top" data-rel="tooltip" data-course-id="{!! !empty($termInfo->course_id)?$termInfo->course_id:'' !!}" data-term-id="{!! !empty($termInfo->term_id)?$termInfo->term_id:'' !!}" data-id="{!! !empty($termInfo->id)?$termInfo->id:'' !!}"  data-status="{!! $termInfo->status=='0'?'1':'0'!!}" title="Activate/Initiate This Term">
                                <i class="fa fa-play"></i>
                            </button>
                            @elseif($termInfo->status=='1')
                            @if(!empty($closeConditionArr['has_close'][$termInfo->term_id]))
                            <?php
                            $disabled = 'cursor-default';
                            $btnType = '';
                            $btnClass = '';
                            $btnColor = 'grey-mint';
                            $btnLabel = __('label.ALL_MARKING_IS_NOT_FINISHED_YET');
                            if (!empty($closeConditionArr['can_close'][$termInfo->term_id])) {
                                $disabled = '';
                                $btnType = 'type="button"';
                                $btnClass = 'activeIn';
                                $btnColor = 'green-seagreen';
                                $btnLabel = __('label.CLOSE_THIS_TERM');
                            }
                            ?>
                            <!--                            <button class="btn btn-xs {{$btnColor}} {{$btnClass}} tooltips activeInactive {{$disabled}}"  
                                                                {{$btnType}} data-placement="top" data-rel="tooltip" title="{{$btnLabel}}"
                                                                data-course-id="{!! !empty($termInfo->course_id)?$termInfo->course_id:'' !!}" 
                                                                data-term-id="{!! !empty($termInfo->term_id)?$termInfo->term_id:'' !!}"  
                                                                data-id="{!! !empty($termInfo->id)?$termInfo->id:'' !!}" 
                                                                data-status="{!!$termInfo->status=='1'?'2':'1'!!}"  data-original-title="{{$btnLabel}}">
                                                            <i class="fa fa-stop"></i>
                                                        </button>-->
                            @endif
                            @elseif($termInfo->status=='2')
                            <!--                            <button class="btn btn-success btn-xs activeIn tooltips activeInactive"  type="button" 
                                                                data-course-id="{!! !empty($termInfo->course_id)?$termInfo->course_id:'' !!}" 
                                                                data-term-id="{!! !empty($termInfo->term_id)?$termInfo->term_id:'' !!}" 
                                                                data-id="{!! !empty($termInfo->id)?$termInfo->id:'' !!}" 
                                                                data-status="{!!$termInfo->status=='2'?'1':'0'!!}"  title="@lang('label.REACTIVATE_THIS_TERM')">
                                                            <i class="fa fa-fast-forward"></i>
                                                        </button>-->
                            @endif
                            @if($termInfo->status !='0')
                            <!--                            <button class="btn btn-xs purple-wisteria bold tooltips term-marking-status"
                                                                title="@lang('label.CLICK_HERE_TO_VIEW_TERM_STATUS_SUMMARY')" type=" button" data-placement="top"
                                                                data-rel="tooltip" course-id="{!! $termInfo->course_id !!}" term-id="{!! $termInfo->term_id !!}"
                                                                data-original-title="@lang('label.CLICK_HERE_TO_VIEW_TERM_STATUS_SUMMARY')" data-target="#modalInfo" data-toggle="modal">
                                                            <i class="fa fa-info-circle"></i>
                                                        </button>-->
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="10">@lang('label.NO_INITIATED_TERM_FOUND')</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!--Start Course marking status marking info modal -->
<div class="modal fade" id="modalInfo" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showCourseMarkingStatus"></div>
    </div>
</div>
<!--End Course marking status marking modal -->
<!--Start Marked unmarked syn modal -->
<div class="modal fade" id="showEventSynSummary" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showMarkedUnmarkedSyn"></div>
    </div>
</div>
<!-- End Marked unmarked syn modal -->

<!--Start DS obsn Summary marked unmarked modal -->
<div class="modal fade" id="showDsObsnSummary" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showDsObsn"></div>
    </div>
</div>
<!-- End DS obsn Summary marked unmarked modal -->

<!--Start Mutual Assessment Summary marked unmarked modal -->
<div class="modal fade" id="showMutualAssessmentSummary" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showMutualAssessment"></div>
    </div>
</div>
<!-- End Mutual Assessment Summary marked unmarked modal -->

<!--Start IPFT Summary marked unmarked modal -->
<div class="modal fade" id="showIpftSummary" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showIpft"></div>
    </div>
</div>
<!-- End IPFT Summary marked unmarked modal -->

<script>
    $(document).ready(function () {
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };
        //Start:: Request for course marking status info 
        $(document).on('click', '.term-marking-status', function (e) {
            e.preventDefault();
            var courseId = $(this).attr('course-id');
            var termId = $(this).attr('term-id');
            $.ajax({
                url: "{{URL::to('termToCourse/requestCourseMarkingStatusSummary')}}",
                type: "POST",
                datatype: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    course_id: courseId,
                    term_id: termId,
                },
                success: function (res) {
                    $('#showCourseMarkingStatus').html(res.html);
                    $('.tooltips').tooltip();
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
                        toastr.error('Error', '@lang("label.SOMETHING_WENT_WRONG")', options);
                    }
                    App.unblockUI();
                }
            });
        });
        //end:: Request course marking status info

        $(document).on('click', '.event-syn-summary', function (e) {
            e.preventDefault();
            var courseId = $(this).attr('course-id');
            var markId = $(this).attr('data-mark-id');
            var termId = $(this).attr('data-term-id');
            var eventId = $(this).attr('data-event-id');
            $.ajax({
                url: "{{URL::to('termToCourse/showMarkedUnmarkedSynSummary')}}",
                type: "POST",
                datatype: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    course_id: courseId,
                    mark_id: markId,
                    term_id: termId,
                    event_id: eventId,
                },
                success: function (res) {
                    $('#showMarkedUnmarkedSyn').html(res.html);
                    $('.tooltips').tooltip();
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
                        toastr.error('Error', '@lang("label.SOMETHING_WENT_WRONG")', options);
                    }
                    App.unblockUI();
                }
            });
        });

        $(document).on('click', '.ds-obsn-summary', function (e) {
            e.preventDefault();
            var courseId = $(this).attr('course-id');
            var markId = $(this).attr('data-mark-id');
            var termId = $(this).attr('data-term-id');
            var weekId = $(this).attr('data-week-id');
            $.ajax({
                url: "{{URL::to('termToCourse/showDsObsnSummary')}}",
                type: "POST",
                datatype: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    course_id: courseId,
                    mark_id: markId,
                    term_id: termId,
                    week_id: weekId,
                },
                success: function (res) {
                    $('#showDsObsn').html(res.html);
                    $('.tooltips').tooltip();
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
                        toastr.error('Error', '@lang("label.SOMETHING_WENT_WRONG")', options);
                    }
                    App.unblockUI();
                }
            });
        });
        //Start::Request mutual assessment Summary marked unmarked
        $(document).on('click', '.mutual-assessment-summary', function (e) {
            e.preventDefault();
            var courseId = $(this).attr('course-id');
            var markId = $(this).attr('data-mark-id');
            var termId = $(this).attr('data-term-id');
            var weekId = $(this).attr('data-week-id');
            $.ajax({
                url: "{{URL::to('termToCourse/showMutualAssessmentSummary')}}",
                type: "POST",
                datatype: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    course_id: courseId,
                    mark_id: markId,
                    term_id: termId,
                    week_id: weekId,
                },
                success: function (res) {
                    $('#showMutualAssessment').html(res.html);
                    $('.tooltips').tooltip();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
//                    if (jqXhr.status == 400) {
//                        var errorsHtml = '';
//                        var errors = jqXhr.responseJSON.message;
//                        $.each(errors, function (key, value) {
//                            errorsHtml += '<li>' + value + '</li>';
//                        });
//                        toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
//                    } else if (jqXhr.status == 401) {
//                        toastr.error(jqXhr.responseJSON.message, '', options);
//                    } else {
//                        toastr.error('Error', '@lang("label.SOMETHING_WENT_WRONG")', options);
//                    }
                    App.unblockUI();
                }
            });
        });
        //end:: Request mutual assessment Summary marked unmarked

        //Start::Request ipft Summary marked unmarked
        $(document).on('click', '.ipft-summary', function (e) {
            e.preventDefault();
            var courseId = $(this).attr('course-id');
            var markId = $(this).attr('data-mark-id');
            var termId = $(this).attr('data-term-id');
            $.ajax({
                url: "{{URL::to('termToCourse/showIpftSummary')}}",
                type: "POST",
                datatype: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    course_id: courseId,
                    mark_id: markId,
                    term_id: termId,
                },
                success: function (res) {
                    $('#showIpft').html(res.html);
                    $('.tooltips').tooltip();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                    App.unblockUI();
                }
            });
        });
        //end:: Request ipft Summary marked unmarked
    });
</script>