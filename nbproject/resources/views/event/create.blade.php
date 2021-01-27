@extends('layouts.default.master')
@section('data_count')	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i>@lang('label.CREATE_NEW_EVENT')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::open(array('group' => 'form', 'url' => 'event', 'files'=> true, 'class' => 'form-horizontal')) !!}
            {!! Form::hidden('page', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">

                        <div class="form-group">
                            <label class="control-label col-md-4" for="event_code">@lang('label.EVENT_CODE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('event_code',  null, ['id'=> 'event_code', 'class' => 'form-control', 'autocomplete' => 'off']) !!} 
                                <span class="text-danger">{{ $errors->first('event_code') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="name">@lang('label.EVENT_DETAIL') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('event_detail',  null, ['id'=> 'event_detail', 'class' => 'form-control', 'autocomplete' => 'off']) !!} 
                                <span class="text-danger">{{ $errors->first('event_detail') }}</span>
                            </div>
                        </div>

                        <!--Start Check Has Sub Event--> 
                        <div class = "form-group" id="hasSubEvent">
                            <label class = "control-label col-md-4" for="checkSubEvent">@lang('label.HAS_SUB_EVENT')&nbsp;:</label>
                            <div class = "col-md-8 margin-top-8">
                                <div class="md-checkbox">
                                    {!! Form::checkbox('has_sub_event',1,null,['id' => 'checkSubEvent', 'class'=> 'md-check has-sub-event']) !!}

                                    <label for="checkSubEvent">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span>
                                    </label>
                                    <span class="">@lang('label.PUT_TICK_IF_HAS_SUB_EVENT')</span>
                                </div>
                            </div>
                        </div>
                        <!--End Check Has Sub Event--> 

                        <!--Start Check Has Ds Assesment--> 
                        <div class = "form-group" id="hasDsAssesment">
                            <label class = "control-label col-md-4" for="checkDsAssesment">@lang('label.HAS_DS_ASSESMENT')&nbsp;:</label>
                            <div class = "col-md-8 margin-top-8">
                                <div class="md-checkbox">
                                    {!! Form::checkbox('has_ds_assesment',1,true,['id' => 'checkDsAssesment', 'class'=> 'md-check has-ds-assesment']) !!}

                                    <label for="checkDsAssesment">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span>
                                    </label>
                                    <span class="">@lang('label.PUT_TICK_IF_HAS_DS_ASSESMENT')</span>
                                </div>
                            </div>
                        </div>
                        <!--End Check Has Ds Assesment--> 

                        <div id="order">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="order">@lang('label.ORDER') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {!! Form::select('order', $orderList, $lastOrderNumber, ['class' => 'form-control js-source-states', 'id' => 'order']) !!} 
                                    <span class="text-danger">{{ $errors->first('order') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                            <div class="col-md-8">
                                {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], '1', ['class' => 'form-control', 'id' => 'status']) !!}
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="submit">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('/event'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>
<script>
    $(function () {
        $(document).on('click', '.has-sub-event', function () {
            if (this.checked == false) {
                $(".has-ds-assesment").prop('checked', true);
            }
        });
        $(document).on('click', '.has-ds-assesment', function () {
            if (this.checked == false && $(".has-sub-event").prop('checked') == false) {
                swal("@lang('label.EVENT_WITHOUT_CHILD_MUST_HAVE_DS_ASSESSMENT_GROUP')");
                return false;
            }
        });
    });
</script>

@stop