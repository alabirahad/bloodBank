<div class="row">
    <div class="col-md-12 table-responsive">
        <div class="webkit-scrollbar">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center vcenter">@lang('label.SL_NO')</th>
                        <th class="w-8 vcenter">
                            <div class="md-checkbox has-success">
                                {!! Form::checkbox('check_all',1,false,['id' => 'checkAll','class'=> 'md-check']) !!} 
                                <label for="checkAll">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                            </div>
                        </th>
                        <th class="text-center vcenter">@lang('label.TERM')</th>
                        <th class="text-center vcenter">@lang('label.INITIAL_DATE')</th>
                        <th class="text-center vcenter">@lang('label.TERMINATION_DATE')</th>
                        <th class="text-center vcenter">@lang('label.NUMBER_OF_WEEK')</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($termArr))
                    <?php $sl = 0; ?>
                    @foreach($termArr as $termId => $termName)
                    <?php
                    $checked = in_array($termId, array_keys($prevData)) ? 'checked' : null;
                    $disabled = in_array($termId, array_keys($prevData)) ? null : 'disabled';
                    $title = $termDisabled = '';
                    if (!empty($prevData[$termId]['active']) && $prevData[$termId]['active'] == '1') {
                        $termDisabled = 'disabled';
                        $title = $termName . ' ' . __('label.IS_ALREADY_ACTIVE');
                    }
                    if (!empty($prevData[$termId]['status']) && $prevData[$termId]['status'] == '2') {
                        $termDisabled = 'disabled';
                        $title = $termName . ' ' . __('label.IS_ALREADY_CLOSED');
                    }
                    ?>
                    <tr>
                        <td class="text-center vcenter">{!! ++$sl !!}</td>
                        <td class="text-center  vcenter">
                            <div class="md-checkbox has-success tooltips" title="<?php echo $title ?>">
                                {!! Form::checkbox('term_id['.$termId.']', $termId,$checked,['id' => 'term-'.$termId, 'class'=> 'md-check term tooltips ', 'data-term-id' => $termId, $termDisabled]) !!}
                                <label for="term-{{ $termId }}">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                            </div>
                            
                        </td>
                        <td class="text-center  vcenter">{{ $termName }}</td>
                        <td class="text-center  vcenter">
                            <div class="input-group date datepicker2">
                                {!! Form::text('initial_date['.$termId.']', !empty($prevData[$termId])? Helper::formatDate($prevData[$termId]['initial_date']):null, ['id'=> 'initialDate-'.$termId, 'class' =>
                                'form-control term-date initial-date', 'placeholder' => 'DD MM YYYY', 'readonly' => '', 'readonly' => '',$disabled,'data-term-id' => $termId]) !!}
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" id="initialReset_{{$termId}}" data-term-id="{{$termId}}" type="button" remove="initialDate-{{$termId}}" {{$disabled}}>
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button"  {{$disabled}} id="initialSet_{{$termId}}">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                        </td>
                        <td class="text-center  vcenter">
                            <div class="input-group date datepicker2">
                                {!! Form::text('termination_date['.$termId.']', !empty($prevData[$termId])? Helper::formatDate($prevData[$termId]['termination_date']):null, ['id'=> 'terminationDate-'.$termId, 'class' =>
                                'form-control term-date termination-date', 'placeholder' => 'DD MM YYYY', 'readonly' => '', 'readonly' => '',$disabled,'data-term-id' => $termId]) !!}
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" id="terminationReset_{{$termId}}" data-term-id="{{$termId}}" type="button" remove="terminationDate-{{$termId}}" {{$disabled}}>
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button" {{$disabled}} id="terminationSet_{{$termId}}">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                        </td>
                        <td class="text-center  vcenter">
                            <div class="col-md-12">
                                {!! Form::text('number_of_week['.$termId.']', !empty($prevData[$termId])?$prevData[$termId]['number_of_week']:null, ['id'=> 'noOfWeeks-'.$termId, 'class' => 'form-control number-of-week integer-only', 'readonly',$disabled]) !!}
                                <div>
                                    <span class="text-danger">{{ $errors->first('number_of_week') }}</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="10">@lang('label.NO_TERM_FOUND')</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="form-actions">
            <div class="row">
                <div class="col-md-offset-5 col-md-5">
                    <button class="btn btn-circle green button-submit" type="button" id="buttonSubmit" >
                        <i class="fa fa-check"></i> @lang('label.SUBMIT')
                    </button>
                    <a href="{{ URL::to('termToCourse') }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('public/js/custom.js') }}"></script>
<script type="text/javascript">
$(document).ready(function () {
    //function for no of weeks
    $(document).on('change', '.term-date', function () {
        var termId = $(this).attr('data-term-id');
        var initialDate = new Date($('#initialDate-' + termId).val());
        var terminationDate = new Date($('#terminationDate-' + termId).val());
        if (terminationDate < initialDate) {
            swal("@lang('label.TERMINATION_DATE_MUST_BE_GREATER_THAN_INITIAL_DATE')");
            $('#terminationDate-' + termId).val('');
            $('noOfWeeks-' + termId).val('');
            return false;
        }

        var weeks = Math.ceil((terminationDate - initialDate) / (24 * 3600 * 1000 * 7));

        if (isNaN(weeks)) {
            var weeks = '';
        }
        $("#noOfWeeks-" + termId).val(weeks);
    });

    $(document).on('click', '.reset-date', function () {
        var termId = $(this).attr('data-term-id');
        $("#noOfWeeks-" + termId).val('');
    });
    //'check all' change
    $(document).on('click', '#checkAll', function () {
        if (this.checked) {
            $(".initial-date").prop('disabled', false);
            $(".termination-date").prop('disabled', false);
            $(".reset-date").prop('disabled', false);
            $(".date-set").prop('disabled', false);
            $(".number-of-week").prop('disabled', false);
            $('.term').prop('checked', $(this).prop('checked')); //change all 'checkbox' checked status
        } else {
            $(".initial-date").prop('disabled', true);
            $(".termination-date").prop('disabled', true);
            $(".reset-date").prop('disabled', true);
            $(".date-set").prop('disabled', true);
            $(".number-of-week").prop('disabled', true);
            $(".term").removeAttr('checked');
            $(".term-date").prop('disabled', true).val('');
            $(".number-of-week ").prop('disabled', true).val('');

        }
    });

    $(document).on('click', '.term', function () {
        var termId = $(this).data('term-id');
        if (this.checked == false) { //if this item is unchecked
            $("#initialDate-" + termId).prop('disabled', true).val('');
            $("#terminationDate-" + termId).prop('disabled', true).val('');
            $("#initialReset_" + termId).prop('disabled', true);
            $("#terminationReset_" + termId).prop('disabled', true);
            $("#initialSet_" + termId).prop('disabled', true);
            $("#terminationSet_" + termId).prop('disabled', true);
            $("#noOfWeeks-" + termId).prop('disabled', true).val(' ');
//                $('#checkAll')[0].checked = false; //change 'check all' checked status to false
        } else {
            $("#initialDate-" + termId).prop('disabled', false);
            $("#terminationDate-" + termId).prop('disabled', false);
            $("#initialReset_" + termId).prop('disabled', false);
            $("#terminationReset_" + termId).prop('disabled', false);
            $("#initialSet_" + termId).prop('disabled', false);
            $("#terminationSet_" + termId).prop('disabled', false);
            $("#noOfWeeks-" + termId).prop('disabled', false);
//                $('#checkAll')[0].checked = true;
        }
        //check 'check all' if all checkbox items are checked

        allCheck();
    });


//        $(document).on('click', '.term', function () {
//            allCheck();
//        });
    allCheck();


});
function allCheck() {

    if ($('.term:checked').length == $('.term').length) {
        $('#checkAll')[0].checked = true;
    } else {
        $('#checkAll')[0].checked = false;
    }
}

</script>