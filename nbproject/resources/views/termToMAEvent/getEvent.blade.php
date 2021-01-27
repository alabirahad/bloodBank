<div class="row">
    <div class="col-md-12">
        <span class="label label-sm label-blue-steel">
            @lang('label.TOTAL_NO_OF_EVENTS'):&nbsp;{!! !$targetArr->isEmpty() ? sizeOf($targetArr) : 0 !!}
        </span>&nbsp;
        <span class="label label-sm label-green-seagreen">
            @lang('label.TOTAL_NO_OF_ASSIGNED_EVENTS'):&nbsp;{!! !$prevDataArr->isEmpty() ? sizeOf($prevDataArr) : 0 !!}
        </span>
    </div>
</div>
<div class="row margin-top-10">
    <div class="col-md-12 table-responsive">
        <table class="table table-bordered table-hover" id="dataTable">
            <thead>
                <tr>
                    <th class="text-center vcenter">@lang('label.SL_NO')</th>
                    <th class="vcenter" width="15%">
                        <div class="md-checkbox has-success">
                            {!! Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check']) !!}
                            <label for="checkAll">
                                <span class="inc"></span>
                                <span class="check mark-caheck"></span>
                                <span class="box mark-caheck"></span>
                            </label>&nbsp;&nbsp;
                            <span class="bold">@lang('label.CHECK_ALL')</span>
                        </div>
                    </th>
                    <th class="vcenter">@lang('label.EVENT')</th>
                    <th class="vcenter">@lang('label.ASSIGNED_TERM')</th>

                </tr>
            </thead>
            <tbody>
                @if (!$targetArr->isEmpty())
                <?php $sl = 0; ?>
                @foreach($targetArr as $target)
                <?php
                $checked = '';
                $disabled = '';
                $title = '';
                $class = 'term-to-event';
                $title2 = '';
                $disabled2 = '';
                if (!empty($prevTermToMAEventList)) {
                    $checked = array_key_exists($target->id, $prevTermToMAEventList) ? 'checked' : '';
                    if (!empty($prevTermToMAEventList[$target->id]) && ($request->term_id != $prevTermToMAEventList[$target->id])) {
                        $class = 'term-to-event-not-par';
                        $disabled = 'disabled';
                        $term = !empty($prevTermToMAEventList[$target->id]) && !empty($termList[$prevTermToMAEventList[$target->id]]) ? $termList[$prevTermToMAEventList[$target->id]] : '';
                        $title = __('label.ALREADY_ASSIGNED_TO_THIS_TERM', ['term' => $term]);
                    }
                }

//        if ($target->particular == '0') {
//
//            if (isset($prevDataList[$target->id][0]) && !empty($prevDataList[$target->id][0])) {
//                if ($prevDataList[$target->id][0] != Request::get('term_id')) {
//                    $class = 'term-to-event-not-par';
//                    $checked = '';
//                    $disabled = 'disabled';
//                    $title = isset($termList[$termId]) ? 'Assigned ' . $termList[$termId] : ''; // . $termList[$prevDataList[$target->id][0]];
//                }
//            }
//        }
//        if (!empty($chackPrevDataList[$target->id])) {
//            if (in_array($chackPrevDataList[$target->id], $chackPrevDataList)) {
//                $checked = 'checked';
//            }
//        }
//
//        //if assign marking by pl comdr 
//        $title2 = '';
//        $disabled2 = '';
//        if (array_key_exists($target->id, $markingCheck)) {
//            $disabled2 = 'disabled';
//            $title2 = __('label.WT_ALREADY_HAS_BEEN_SET');
//        } else {
//            if (array_key_exists($target->id, $termToParticular)) {
//                $disabled2 = 'disabled';
//                $title2 = __('label.DETAIL_EVENT_HAS_BEEN_ASSIGNED_TO_THIS_EVENT');
//            }
//        }
                ?>
                <tr>
                    <td class="text-center vcenter">{!! ++$sl !!}</td>
                    <td class="vcenter">
                        <div class="md-checkbox has-success tooltips" title="{!!$title!!}" >
                            {!! Form::checkbox('event_id['.$target->id.']',$target->id, $checked, ['id' => $target->id, 'data-id'=>$target->id,'class'=> 'md-check event '.$class,$disabled]) !!}

                            <label for="{!! $target->id !!}">
                                <span class="inc"></span>
                                <span class="check mark-caheck tooltips"></span>
                                <span class="box mark-caheck tooltips"></span>
                            </label>
                        </div>
                    </td>
                    <td class="vcenter">{!! $target->name!!}</td>
                    <td>
                        @if(!empty($prevDataList[$target->id]))
                        @foreach($prevDataList[$target->id] as $termId)
                        {!! isset($termList[$termId])?$termList[$termId]:''!!}
                        @endforeach
                        @endif
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="10" class="vcenter">@lang('label.NO_EVENT_FOUND')</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
<!-- if submit wt chack Start -->
<div class = "form-actions">
    <div class = "col-md-offset-4 col-md-8">
        <button class = "button-submit btn btn-circle green" type="button">
            <i class = "fa fa-check"></i> @lang('label.SUBMIT')
        </button>
        <a href = "{{ URL::to('termToMAEvent') }}" class = "btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
    </div>
</div>
<!-- if submit wt chack End -->
<script type="text/javascript">
//    CHECK ALL
    $(document).ready(function () {
//        var checkAllDisable = 0;
//        $('.event').each(function () {
//            if (this.disabled == true) {
//                checkAllDisable++;
//            }
//        });
//        if (checkAllDisable > 0) {
//            $('#checkAll').prop('disabled', true);
//        }

        $('#checkAll').change(function () {  //'check all' change
            $('.term-to-event').prop('checked', $(this).prop('checked')); //change all 'checkbox' checked status
        });
        $('.term-to-event').change(function () {
            if (this.checked == false) { //if this item is unchecked
                $('#checkAll').prop('checked', false); //change 'check all' checked status to false
            }
            //check 'check all' if all checkbox items are checked
            if ($('.term-to-event:checked').length == $('.term-to-event').length) {
                $('#checkAll').prop('checked', true); //change 'check all' checked status to true
            }
        });

        //'check all' change
        $(document).on('click', '#checkAll', function () {
            if (this.checked) {
                $('.term-to-event').prop('checked', $(this).prop('checked')); //change all 'checkbox' checked status
            } else {
                $(".term-to-event").removeAttr('checked');
            }
        });

        $(document).on('click', '.term-to-event', function () {
            allCheck();
        });


//        $(document).on('click', '.term', function () {
//            allCheck();
//        });
        allCheck();
    });


    function allCheck() {

        if ($('.term-to-event:checked').length == $('.term-to-event').length) {
            $('#checkAll').prop('checked', true);
        } else {
            $('#checkAll').prop('checked', false);
        }
    }
//    CHECK ALL
</script>
<script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>