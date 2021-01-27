<div class="row">
    @if(!empty($assingedMksWtInfo))
    @if(!empty($cmArr))
    <div class="col-md-12 margin-top-10">
        <span class="label label-md bold label-blue-steel">
            @lang('label.TOTAL_NO_OF_CM'): {!! sizeof($cmArr) !!}
        </span>&nbsp;
        <span class="label label-md bold label-green-seagreen">
            @lang('label.HIGHEST_MKS_LIMIT'): {!! $assingedMksWtInfo->highest_mks_limit ?? '0.00' !!}
            {!! Form::hidden('highest_mks', !empty($assingedMksWtInfo->highest_mks_limit) ? $assingedMksWtInfo->highest_mks_limit : null,['id' => 'highestMksId']) !!}
        </span>&nbsp;
        <span class="label label-md bold label-purple-sharp">
            @lang('label.LOWEST_MKS_LIMIT'): {!! $assingedMksWtInfo->lowest_mks_limit ?? '0.00' !!}
            {!! Form::hidden('lowest_mks', !empty($assingedMksWtInfo->lowest_mks_limit) ? $assingedMksWtInfo->lowest_mks_limit : null) !!}
        </span>
    </div>
    <div class="col-md-12 margin-top-10">
        <div class="max-height-500 table-responsive webkit-scrollbar">
            <table class="table table-bordered table-hover table-head-fixer-color">
                <thead>
                    <tr>
                        <th class="text-center vcenter">@lang('label.SL_NO')</th>
                        <th class="vcenter">@lang('label.PERSONAL_NO')</th>
                        <th class="vcenter">@lang('label.RANK')</th>
                        <th class="vcenter">@lang('label.CM')</th>
                        <th class="vcenter">@lang('label.PHOTO')</th>
                        <th class="text-center vcenter">@lang('label.MKS') ({!! !empty($assingedMksWtInfo->mks_limit) ? $assingedMksWtInfo->mks_limit : '0.00' !!})</th>
                        {!! Form::hidden('mks_limit', !empty($assingedMksWtInfo->mks_limit) ? $assingedMksWtInfo->mks_limit : '',['id' => 'mksLimitId']) !!}
                        <th class="text-center vcenter">@lang('label.WT') ({!! !empty($assingedMksWtInfo->wt) ? $assingedMksWtInfo->wt : '0.00' !!})</th>
                        {!! Form::hidden('assigned_wt', !empty($assingedMksWtInfo->wt) ? $assingedMksWtInfo->wt : '',['id' => 'assignedWtId']) !!}
                        <th class="text-center vcenter">@lang('label.PERCENT') </th>
                        <th class="text-center vcenter">@lang('label.GRADE') </th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $sl = 0;
                    $readonly = !empty($eventAssessmentMarkingLockInfo) || !$ciModMarkingInfo->isEmpty() ? 'readonly' : '';
                    $givenMks = !empty($eventAssessmentMarkingLockInfo) || !$ciModMarkingInfo->isEmpty() ? 'given-mks' : '';
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
                        </td>
                        {!! Form::hidden('cm_name['.$cmId.']',!empty($cmName) ? $cmName : null,['id' => 'cmId'])!!}
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
                        <td class="text-center vcenter width-80">
                            {!! Form::text('mks_wt['.$cmId.'][mks]', !empty($prevMksWtArr[$cmId]['mks']) ? $prevMksWtArr[$cmId]['mks'] : null, ['id'=> 'mksId_'.$cmId, 'class' => 'form-control integer-decimal-only width-inherit text-right ' . $givenMks, 'data-key' => $cmId, 'autocomplete' => 'off',$readonly]) !!}
                        </td>
                        <td class="text-center vcenter width-80">
                            {!! Form::text('mks_wt['.$cmId.'][wt]', !empty($prevMksWtArr[$cmId]['wt']) ? $prevMksWtArr[$cmId]['wt'] : null, ['id'=> 'wtId_'.$cmId, 'class' => 'form-control integer-decimal-only width-inherit text-right given-wt', 'data-key' => $cmId, 'autocomplete' => 'off','readonly']) !!}
                        </td>
                        <td class="text-center vcenter width-80">
                            {!! Form::text('mks_wt['.$cmId.'][percent]', !empty($prevMksWtArr[$cmId]['percentage']) ? $prevMksWtArr[$cmId]['percentage'] : null, ['id'=> 'percentId_'.$cmId, 'class' => 'form-control integer-decimal-only width-inherit text-right given-percent', 'data-key' => $cmId, 'autocomplete' => 'off','readonly']) !!}
                        </td>
                        <td class="text-center vcenter width-50">
                            <span id="gradeName_{{$cmId}}" class="form-control integer-decimal-only width-inherit bold text-center">
                                {!! !empty($prevMksWtArr[$cmId]['grade_name']) ? $prevMksWtArr[$cmId]['grade_name'] : '' !!}
                            </span>
                        </td>
                        {!! Form::hidden('mks_wt['.$cmId.'][grade_id]',!empty($prevMksWtArr[$cmId]['grade_id']) ? $prevMksWtArr[$cmId]['grade_id'] : null,['id' => 'gradeId_'.$cmId]) !!}
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-12 margin-top-10">
        <div class="row">
            @if($ciModMarkingInfo->isEmpty())
            @if(!empty($eventAssessmentMarkingLockInfo))
            @if($eventAssessmentMarkingLockInfo['status'] == '1')
            <div class="col-md-12 text-center">
                <button class="btn btn-circle label-purple-sharp request-for-unlock" type="button" id="buttonSubmitLock" data-target="#modalUnlockMessage" data-toggle="modal">
                    <i class="fa fa-unlock"></i> @lang('label.REQUEST_FOR_UNLOCK')
                </button>
            </div>
            @elseif($eventAssessmentMarkingLockInfo['status'] == '2')
            <div class="col-md-12">
                <div class="alert alert-danger alert-dismissable">
                    <p><strong><i class="fa fa-unlock"></i> {!! __('label.REQUESTED_TO_CI_FOR_UNLOCK') !!}</strong></p>
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
            @endif
        </div>
    </div>
    @else
    <div class="col-md-12">
        <div class="alert alert-danger alert-dismissable">
            <p><strong><i class="fa fa-bell-o fa-fw"></i> {!! __('label.NO_CM_IS_ASSIGNED_TO_MARKING_GROUP_TO_DS') !!}</strong></p>
        </div>
    </div>
    @endif
    @else
    <div class="col-md-12">
        <div class="alert alert-danger alert-dismissable">
            <p><strong><i class="fa fa-bell-o fa-fw"></i> {!! __('label.MKS_WT_IS_NOT_DISTRIBUTED_YET') !!}</strong></p>
        </div>
    </div>
    @endif
</div>
<script src="{{asset('public/js/custom.js')}}"></script>
<script>
$(document).ready(function () {
    //table header fix
    $(".table-head-fixer-color").tableHeadFixer();

    $(document).on('keyup', '.given-mks', function () {
        var key = $(this).attr('data-key');
        var givenMks = parseFloat($("#mksId_" + key).val());
        var $highestMks = parseFloat($("#highestMksId").val());
        var assignedWt = parseFloat($("#assignedWtId").val());
        var mksLimit = parseFloat($("#mksLimitId").val());
        if (givenMks > $highestMks) {
            swal({
                title: '@lang("label.YOUR_GIVEN_MKS_EXCEEDED_FROM_HIGHEST_MKS")',
                type: 'warning',
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Ok',
                closeOnConfirm: true,
            }, function (isConfirm) {
                $("#mksId_" + key).val('');
                $("#wtId_" + key).val('');
                $("#percentId_" + key).val('');
                $("#gradeId_" + key).val('');
                $("#gradeName_" + key).text('');
                setTimeout(function () {
                    $("#mksId_" + key).focus();
                }, 250);
                return false;
            });
        } else {
            var wt = parseFloat((assignedWt / mksLimit) * givenMks).toFixed(2);
            var wtPercent = parseFloat((wt / assignedWt) * 100).toFixed(2);
            if (!isNaN(givenMks)) {
                $("#wtId_" + key).val(wt);
                $("#percentId_" + key).val(wtPercent);
                $("#gradeName_" + key).text(findGradeName(gradeArr, wtPercent));
                $("#gradeId_" + key).val(findGradeId(gradeIdArr, wtPercent));
            } else {
                $("#wtId_" + key).val('');
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


