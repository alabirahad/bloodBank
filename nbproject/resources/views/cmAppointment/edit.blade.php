@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i>@lang('label.EDIT_CM_APPOINTMENT')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::model($target, ['route' => array('cmAppointment.update', $target->id), 'method' => 'PATCH', 'files'=> true, 'class' => 'form-horizontal'] ) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">

                        <div class="form-group">
                            <label class="control-label col-md-4" for="name">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('name', Request::old('name'), ['id'=> 'name', 'class' => 'form-control', 'autocomplete' => 'off']) !!} 
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="code">@lang('label.SHORT_NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('code', Request::old('code'), ['id'=> 'code', 'class' => 'form-control', 'autocomplete' => 'off']) !!} 
                                <span class="text-danger">{{ $errors->first('code') }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-4" for="isUnique">@lang('label.UNIQUE_TO_SYN') :</label>
                            <div class="col-md-8 checkbox-center md-checkbox has-success">
                                {!! Form::checkbox('is_unique',1,null, ['id' => 'isUnique', 'class'=> 'md-check']) !!}
                                <label for="isUnique">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                                <span class="text-green">@lang('label.PUT_TICK_TO_MARK_APPT_HAS_UNIQUE_TO_SYN')</span>
                            </div>
                        </div>
                        
                        <div  id="order">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="order">@lang('label.ORDER') :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    {!! Form::select('order', $orderList, null, ['class' => 'form-control js-source-states', 'id' => 'order']) !!} 
                                    <span class="text-danger">{{ $errors->first('order') }}</span>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                            <div class="col-md-8">
                                {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], null, ['class' => 'form-control', 'id' => 'status']) !!}
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
                        <a href="{{ URL::to('/cmAppointment'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
    <!-- END BORDERED TABLE PORTLET-->
</div>
<script type="text/javascript">
    $(function() {
        
        $(document).on("change", "#serviceId", function() {
            var serviceId = $("#serviceId").val();
            $.ajax({
                url: "{{URL::to('cmAppointment/getOrder')}}",
                data: {
                    service_id: serviceId,
                    operation: 2,
                },
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#order').html(response.html);
                    $('.js-source-states').select2();
                },
                error: function(jqXhr, ajaxOptions, thrownError) {
                }
            });//ajax
        });


    });
</script>

@stop