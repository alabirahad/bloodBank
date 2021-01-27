<div class="row">
    @if(!empty($assignedObsnInfo))
    @if(empty($openTerms))
    @if(!empty($cmArr))
    <div class="col-md-12 margin-top-10">
        <span class="label label-md bold label-blue-steel">
            @lang('label.TOTAL_NO_OF_CM'):&nbsp;{!! sizeof($cmArr) !!}
        </span>&nbsp;
        <a class = "btn btn-sm bold label-green-seagreen tooltips" title="@lang('label.CLICK_HERE_TO_SEE_COURSE_MARKING_STATUS_SUMMARY')" type="button" href="#modalInfo" data-toggle="modal" id="courseStatusSummaryId">
            @lang('label.COURSE_STATUS_SUMMARY')
        </a>
    </div>
    <div class="col-md-12 margin-top-10">
        <div class="max-height-500 table-responsive webkit-scrollbar">
            <table class="table table-bordered table-hover table-head-fixer-color">
                <thead>
                    <tr>
                        <th class="text-center vcenter" rowspan="5">@lang('label.SL_NO')</th>
                        <th class="vcenter" rowspan="5">@lang('label.PERSONAL_NO')</th>
                        <th class="vcenter" rowspan="5">@lang('label.RANK')</th>
                        <th class="vcenter" rowspan="5">@lang('label.CM')</th>
                        <th class="vcenter" rowspan="5">@lang('label.PHOTO')</th>
                        <th class="vcenter" rowspan="5">@lang('label.SYNDICATE')</th>
                        @if (!empty($eventMksWtArr['mks_wt']))
                        @foreach ($eventMksWtArr['mks_wt'] as $eventId => $evInfo)
                        <th class="vcenter text-center" rowspan="{!! !empty($eventMksWtArr['event'][$eventId]) && sizeof($eventMksWtArr['event'][$eventId]) > 1 ? 1 : 4 !!}"
                            colspan="{!! !empty($rowSpanArr['event'][$eventId]) ? $rowSpanArr['event'][$eventId] * 4 : 4 !!}">
                            {!! !empty($eventMksWtArr['event'][$eventId]['name']) ? $eventMksWtArr['event'][$eventId]['name'] : '' !!}
                        </th>
                        @endforeach
                        @endif
                        <th class="vcenter text-center" rowspan="4" colspan="5">@lang('label.TERM_TOTAL')</th>
                        <th class="vcenter text-center" rowspan="5">@lang('label.CI_OBSN')&nbsp;({!! !empty($assignedObsnInfo->ci_obsn_wt) ? $assignedObsnInfo->ci_obsn_wt : '0.00' !!})</th>
                        {!! Form::hidden('assigned_wt',!empty($assignedObsnInfo->ci_obsn_wt) ? $assignedObsnInfo->ci_obsn_wt : 0,['id' => 'assignedWtId']) !!}
                        <th class="vcenter text-center" rowspan="4" colspan="4">@lang('label.AFTER_CI_OBSN')</th>
                        <th class="vcenter" rowspan="5">@lang('label.PERSONAL_NO')</th>
                        <th class="vcenter" rowspan="5">@lang('label.RANK')</th>
                        <th class="vcenter" rowspan="5">@lang('label.CM')</th>
                        <th class="vcenter" rowspan="5">@lang('label.PHOTO')</th>
                    </tr>
                    <tr>
                        @if (!empty($eventMksWtArr['mks_wt']))
                        @foreach ($eventMksWtArr['mks_wt'] as $eventId => $evInfo)
                        @foreach($evInfo as $subEventId => $subEvInfo)
                        @if(!empty($subEventId))
                        <th class="vcenter text-center" rowspan="{!! !empty($eventMksWtArr['event'][$eventId][$subEventId]) && sizeof($eventMksWtArr['event'][$eventId][$subEventId]) > 1 ? 1 : 3 !!}"
                            colspan="{!! !empty($rowSpanArr['sub_event'][$eventId][$subEventId]) ? $rowSpanArr['sub_event'][$eventId][$subEventId] * 4 : 4 !!}">
                            {!! !empty($eventMksWtArr['event'][$eventId][$subEventId]['name']) ? $eventMksWtArr['event'][$eventId][$subEventId]['name'] : '' !!}
                        </th>
                        @endif
                        @endforeach
                        @endforeach
                        @endif
                    </tr>
                    <tr>
                        @if (!empty($eventMksWtArr['mks_wt']))
                        @foreach ($eventMksWtArr['mks_wt'] as $eventId => $evInfo)
                        @foreach($evInfo as $subEventId => $subEvInfo)
                        @foreach($subEvInfo as $subSubEventId => $subSubEvInfo)
                        @if(!empty($subSubEventId))
                        <th class="vcenter text-center" rowspan="{!! !empty($eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId]) && sizeof($eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId]) > 1 ? 1 : 2 !!}"
                            colspan="{!! !empty($rowSpanArr['sub_sub_event'][$eventId][$subEventId][$subSubEventId]) ? $rowSpanArr['sub_sub_event'][$eventId][$subEventId][$subSubEventId] * 4 : 4 !!}">
                            {!! !empty($eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId]['name']) ? $eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId]['name'] : '' !!}
                        </th>
                        @endif
                        @endforeach
                        @endforeach
                        @endforeach
                        @endif
                    </tr>
                    <tr>
                        @if (!empty($eventMksWtArr['mks_wt']))
                        @foreach ($eventMksWtArr['mks_wt'] as $eventId => $evInfo)
                        @foreach($evInfo as $subEventId => $subEvInfo)
                        @foreach($subEvInfo as $subSubEventId => $subSubEvInfo)
                        @foreach($subSubEvInfo as $subSubSubEventId => $subSubSubEvInfo)
                        @if(!empty($subSubSubEventId))
                        <th class="vcenter text-center" colspan="4">
                            {!! !empty($eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['name']) ? $eventMksWtArr['event'][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['name'] : '' !!}
                        </th>
                        @endif
                        @endforeach
                        @endforeach
                        @endforeach
                        @endforeach
                        @endif
                    </tr>
                    <tr>
                        @if (!empty($eventMksWtArr['mks_wt']))
                        @foreach ($eventMksWtArr['mks_wt'] as $eventId => $evInfo)
                        @foreach($evInfo as $subEventId => $subEvInfo)
                        @foreach($subEvInfo as $subSubEventId => $subSubEvInfo)
                        @foreach($subSubEvInfo as $subSubSubEventId => $subSubSubEvInfo)
                        <?php
                        $eventMkslimit = !empty($subSubSubEvInfo['mks_limit']) ? Helper::numberFormat2Digit($subSubSubEvInfo['mks_limit']) : '0.00';
                        $eventHighestMkslimit = !empty($subSubSubEvInfo['highest_mks_limit']) ? Helper::numberFormat2Digit($subSubSubEvInfo['highest_mks_limit']) : '0.00';
                        $eventLowestMkslimit = !empty($subSubSubEvInfo['lowest_mks_limit']) ? Helper::numberFormat2Digit($subSubSubEvInfo['lowest_mks_limit']) : '0.00';
                        $eventWt = !empty($subSubSubEvInfo['wt']) ? Helper::numberFormat2Digit($subSubSubEvInfo['wt']) : '0.00';
                        ?>
                        <th class="vcenter text-center">
                            <span class="tooltips" data-html="true" title="
                                  <div class='text-left'>
                                  @lang('label.HIGHEST_MKS_LIMIT'): &nbsp;{!! $eventHighestMkslimit !!}<br/>
                                  @lang('label.LOWEST_MKS_LIMIT'): &nbsp;{!! $eventLowestMkslimit !!}<br/>
                                  </div>
                                  ">
                                @lang('label.MKS')&nbsp;({!! $eventMkslimit !!})
                            </span>
                        </th>
                        <th class="vcenter text-center">
                            @lang('label.WT')&nbsp;({!! $eventWt !!})
                        </th>
                        <th class="vcenter text-center">@lang('label.PERCENT')</th>
                        <th class="vcenter text-center">@lang('label.GRADE')</th>
                        @endforeach
                        @endforeach
                        @endforeach
                        @endforeach
                        @endif
                        <th class="vcenter text-center">
                            @lang('label.MKS')&nbsp;({!! !empty($eventMksWtArr['total_mks_limit']) ? Helper::numberFormat2Digit($eventMksWtArr['total_mks_limit']) : '0.00' !!})
                        </th>
                        <th class="vcenter text-center">
                            @lang('label.WT')&nbsp;({!! !empty($eventMksWtArr['total_wt']) ? Helper::numberFormat2Digit($eventMksWtArr['total_wt']) : '0.00' !!})
                        </th>
                        <th class="vcenter text-center">@lang('label.PERCENT')</th>
                        <th class="vcenter text-center">@lang('label.GRADE')</th>
                        <th class="vcenter text-center">@lang('label.POSITION')</th>

                        <th class="vcenter text-center">
                            @lang('label.WT')&nbsp;({!! !empty($eventMksWtArr['total_wt_after_ci']) ? Helper::numberFormat2Digit($eventMksWtArr['total_wt_after_ci']) : '0.00' !!})
                            {!! Form::hidden('total_wt_after_ci',!empty($eventMksWtArr['total_wt_after_ci']) ? $eventMksWtArr['total_wt_after_ci'] : 0,['id' => 'afterCiWtId']) !!}
                        </th>
                        <th class="vcenter text-center">@lang('label.PERCENT')</th>
                        <th class="vcenter text-center">@lang('label.GRADE')</th>
                        <th class="vcenter text-center">@lang('label.POSITION')</th>
                    </tr>

                </thead>

                <tbody>
                    <?php
                    $sl = 0;
                    $readonly = !empty($ciObsnLockInfo) ? 'readonly' : '';
                    $givenWt= !empty($ciObsnLockInfo) ? 'given-wt' : '';
                    ?>
                    @foreach($cmArr as $cmId => $cmInfo)
                    <?php
                    $cmName = (!empty($cmInfo['rank_name']) ? $cmInfo['rank_name'] . ' ' : '') . (!empty($cmInfo['full_name']) ? $cmInfo['full_name'] : '');
                    $synName = (!empty($cmInfo['syn_name']) ? $cmInfo['syn_name'] . ' ' : '') . (!empty($cmInfo['sub_syn_name']) ? '(' . $cmInfo['sub_syn_name'] . ')' : '');
                    ?>
                    <tr>
                        <td class="text-center vcenter">{!! ++$sl !!}</td>
                        <td class="vcenter width-80">
                            <div class="width-inherit">{!! $cmInfo['personal_no'] ?? '' !!}</div>
                        </td>
                        <td class="vcenter width-80">
                            <div class="width-inherit">{!! $cmInfo['rank_name'] ?? '' !!}</div>
                        </td>
                        <td class="vcenter width-150">
                            <div class="width-inherit">{!! $cmInfo['full_name'] ?? '' !!}</div>
                            {!! Form::hidden('cm_name['.$cmId.']',!empty($cmName) ? $cmName : null,['id' => 'cmId'])!!}
                        </td>
                        <td class="vcenter" width="50px">
                            @if(!empty($cmInfo['photo']) && File::exists('public/uploads/cm/' . $cmInfo['photo']))
                            <img width="50" height="60" src="{{URL::to('/')}}/public/uploads/cm/{{$cmInfo['photo']}}" alt="{{ $cmInfo['full_name'] ?? '' }}">
                            @else
                            <img width="50" height="60" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $cmInfo['full_name'] ?? '' }}">
                            @endif
                        </td>
                        <td class="vcenter width-150">
                            <div class="width-inherit">{!! $synName !!}</div>
                        </td>
                        @if (!empty($eventMksWtArr['mks_wt']))
                        @foreach ($eventMksWtArr['mks_wt'] as $eventId => $evInfo)
                        @foreach($evInfo as $subEventId => $subEvInfo)
                        @foreach($subEvInfo as $subSubEventId => $subSubEvInfo)
                        @foreach($subSubEvInfo as $subSubSubEventId => $subSubSubEvInfo)

                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-right">
                                {!! !empty($achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['mks']) ? $achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['mks'] : '' !!} 
                            </span>
                        </td>
                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-right">
                                {!! !empty($achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['wt']) ? $achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['wt'] : '' !!} 
                            </span>
                        </td>
                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-right">
                                {!! !empty($achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['percentage']) ? $achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['percentage'] : '' !!} 
                            </span>
                        </td>
                        <td class="vcenter width-80">
                            <span class="form-control width-inherit text-right">
                                {!! !empty($achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['grade_name']) ? $achieveMksWtArr[$cmId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['grade_name'] : '' !!} 
                            </span>
                        </td>

                        @endforeach
                        @endforeach
                        @endforeach
                        @endforeach
                        @endif
                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-right">
                                {!! !empty($cmInfo['total_term_mks']) ? Helper::numberFormat2Digit($cmInfo['total_term_mks']) : '' !!} 
                            </span>
                        </td>
                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-right" id="totalTermWt_{{$cmId}}">
                                {!! !empty($cmInfo['total_term_wt']) ? Helper::numberFormat2Digit($cmInfo['total_term_wt']) : '' !!} 
                            </span>
                        </td>
                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-right">
                                {!! !empty($cmInfo['total_term_percent']) ? Helper::numberFormat2Digit($cmInfo['total_term_percent']) : '' !!} 
                            </span>
                        </td>
                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-center bold">
                                {!! !empty($cmInfo['grade_after_term_total']) ? $cmInfo['grade_after_term_total'] : '' !!} 
                            </span>
                        </td>
                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-center">
                                {!! !empty($cmInfo['total_term_position']) ? $cmInfo['total_term_position'] : '' !!} 
                            </span>
                        </td>
                        <td class="vcenter width-80">
                            {!! Form::text('wt['.$cmId.'][ci_obsn]',!empty($cmInfo['ci_obsn']) ? $cmInfo['ci_obsn'] : null,['id' => 'ciObsnId_'.$cmId, 'data-key' => $cmId, 'class' => 'form-control integer-decimal-only width-inherit text-right ' . $givenWt,'autocomplete'=>'off',$readonly]) !!} 
                        </td>

                        <td class="vcenter width-80">
                            {!! Form::text('wt['.$cmId.'][total_wt]',!empty($cmInfo['total_wt']) ? $cmInfo['total_wt'] : null,['id' => 'totalWt_'.$cmId, 'data-key' => $cmId, 'class' => 'form-control integer-decimal-only width-inherit text-right','readonly','autocomplete'=>'off']) !!} 
                        </td>
                        <td class="vcenter width-80">
                            {!! Form::text('wt['.$cmId.'][percentage]',!empty($cmInfo['percent']) ? $cmInfo['percent'] : null,['id' => 'percentId_'.$cmId, 'data-key' => $cmId, 'class' => 'form-control integer-decimal-only width-inherit text-right','readonly','autocomplete'=>'off']) !!} 
                        </td>
                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-center bold" id="gradeName_{{$cmId}}">
                                {!! $cmInfo['grade'] ?? '' !!}
                            </span>
                        </td>
                        {!! Form::hidden('wt['.$cmId.'][grade_id]',!empty($cmInfo['grade_id']) ? $cmInfo['grade_id'] : null,['id' => 'gradeId_'.$cmId]) !!}
                        <td class="vcenter width-80">
                            <span class="form-control integer-decimal-only width-inherit text-center">
                                {!! !empty($cmInfo['position_after_ci_obsn']) ? $cmInfo['position_after_ci_obsn'] : '' !!}
                            </span>
                        </td>

                        <td class="vcenter width-80">
                            <div class="width-inherit">{!! $cmInfo['personal_no'] ?? '' !!}</div>
                        </td>
                        <td class="vcenter width-80">
                            <div class="width-inherit">{!! $cmInfo['rank_name'] ?? '' !!}</div>
                        </td>
                        <td class="vcenter width-150">
                            <div class="width-inherit">{!! $cmInfo['full_name'] ?? '' !!}</div>
                        </td>
                        <td class="vcenter" width="50px">
                            @if(!empty($cmInfo['photo']) && File::exists('public/uploads/cm/' . $cmInfo['photo']))
                            <img width="50" height="60" src="{{URL::to('/')}}/public/uploads/cm/{{$cmInfo['photo']}}" alt="{{ $cmInfo['full_name'] ?? '' }}">
                            @else
                            <img width="50" height="60" src="{{URL::to('/')}}/public/img/unknown.png" alt="{{ $cmInfo['full_name'] ?? '' }}">
                            @endif
                        </td>
                        <td class="vcenter width-150">
                            <div class="width-inherit">{!! $synName !!}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-12 margin-top-10">
        <div class="row">
            @if(!empty($ciObsnLockInfo))
            @if($ciObsnLockInfo['status'] == '1')
            <div class="col-md-12 text-center">
                <button class="btn btn-circle label-purple-sharp request-for-unlock" type="button" id="buttonSubmitLock" data-target="#modalUnlockMessage" data-toggle="modal">
                    <i class="fa fa-unlock"></i> @lang('label.REQUEST_FOR_UNLOCK')
                </button>
            </div>
            @elseif($ciObsnLockInfo['status'] == '2')
            <div class="col-md-12">
                <div class="alert alert-danger alert-dismissable">
                    <p><strong><i class="fa fa-unlock"></i> {!! __('label.REQUESTED_TO_COMDT_FOR_UNLOCK') !!}</strong></p>
                </div>
            </div>
            @endif
            @else
            <div class="col-md-12 text-center">
                <button class="btn btn-circle label-blue-steel button-submit" data-id="1" type="button" id="buttonSubmit" >
                    <i class="fa fa-file-text-o"></i> @lang('label.SAVE_AS_DRAFT')
                </button>
                <button class="btn btn-circle green button-submit" data-id="2" type="button" id="buttonSubmitLock" >
                    <i class="fa fa-lock"></i> @lang('label.SAVE_LOCK')
                </button>
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="col-md-12 margin-top-10">
        <div class="alert alert-danger alert-dismissable">
            <p><strong><i class="fa fa-bell-o fa-fw"></i> {!! __('label.NO_CM_IS_ASSIGNED_TO_THIS_COURSE') !!}</strong></p>
        </div>
    </div>
    @endif
    @else
    <div class="col-md-12 margin-top-10">
        <div class="alert alert-danger alert-dismissable">
            <p><strong><i class="fa fa-bell-o fa-fw"></i> {!! __('label.ALL_TERMS_ARE_NOT_CLOSED_YET') !!}</strong></p>
        </div>
    </div>
    @endif
    @else
    <div class="col-md-12">
        <div class="alert alert-danger alert-dismissable">
            <p><strong><i class="fa fa-bell-o fa-fw"></i> {!! __('label.OBSN_WT_IS_NOT_DISTRIBUTED_YET') !!}</strong></p>
        </div>
    </div>
    @endif
</div>
<script src="{{asset('public/js/custom.js')}}"></script>
<script>
$(document).ready(function () {
//table header fix
    $(".table-head-fixer-color").tableHeadFixer();

    $(document).on('keyup', '.given-wt', function () {
        var key = $(this).attr('data-key');
        var givenWt = parseFloat($(this).val());
        var assignedWt = parseFloat($("#assignedWtId").val());
        var totalTermWt = parseFloat($("#totalTermWt_" + key).text());
        var afterCiWt = parseFloat($("#afterCiWtId").val());
        if (totalTermWt == '' || isNaN(totalTermWt)) {
            totalTermWt = 0;
        }
        if (givenWt > assignedWt) {
            swal({
                title: '@lang("label.YOUR_GIVEN_WT_EXCEEDED_FROM_ASSIGNED_WT")',
                type: 'warning',
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Ok',
                closeOnConfirm: true,
            }, function (isConfirm) {
                $("#ciObsnId_" + key).val('');
                $("#totalWt_" + key).val('');
                $("#percentId_" + key).val('');
                $("#gradeId_" + key).val('');
                $("#gradeName_" + key).text('');
                setTimeout(function () {
                    $("#ciObsnId_" + key).focus();
                }, 250);
                return false;
            });
        } else {
            var wt = parseFloat(totalTermWt + givenWt).toFixed(2);
            var wtPercent = parseFloat((wt / afterCiWt) * 100).toFixed(2);
            if (!isNaN(givenWt)) {
                $("#totalWt_" + key).val(wt);
                $("#percentId_" + key).val(wtPercent);
                $("#gradeName_" + key).text(findGradeName(gradeArr, wtPercent));
                $("#gradeId_" + key).val(findGradeId(gradeIdArr, wtPercent));
            } else {
                $("#totalWt_" + key).val('');
                $("#percentId_" + key).val('');
                $("#gradeName_" + key).text('');
                $("#gradeId_" + key).val('');
            }
        }

    });

//start :: produce grade arr for javascript
    var gradeArr = [];
    var gradeIdArr = [];
    var letter = '';
    var letterId = '';
    var startRange = 0;
    var endRange = 0;
<?php
if (!$gradeInfo->isEmpty()) {
    foreach ($gradeInfo as $grade) {
        ?>
            letter = '<?php echo $grade->grade_name; ?>';
            letterId = '<?php echo $grade->id; ?>';
            startRange = <?php echo $grade->marks_from; ?>;
            endRange = <?php echo $grade->marks_to; ?>;
            gradeArr[letter] = [];
            gradeArr[letter]['start'] = startRange;
            gradeArr[letter]['end'] = endRange;

            gradeIdArr[letterId] = [];
            gradeIdArr[letterId]['start'] = startRange;
            gradeIdArr[letterId]['end'] = endRange;
        <?php
    }
}
?>
    function findGradeName(gradeArr, mark) {
        var achievedGrade = '';
        for (var letter in gradeArr) {
            var range = gradeArr[letter];
            if (mark == 100) {
                achievedGrade = "A+";
            }
            if (range['start'] <= mark && mark < range['end']) {
                achievedGrade = letter;
            }
        }

        return achievedGrade;
    }

    function findGradeId(gradeIdArr, mark) {
        var achievedGradeId = '';
        for (var letterId in gradeIdArr) {
            var range = gradeIdArr[letterId];
            if (mark == 100) {
                achievedGradeId = 1;
            }
            if (range['start'] <= mark && mark < range['end']) {
                achievedGradeId = letterId;
            }
        }

        return achievedGradeId;
    }
//end :: produce grade arr for javascript
});
</script>

