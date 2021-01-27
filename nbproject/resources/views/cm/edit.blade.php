@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    @include('layouts.flash')
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.EDIT_CM')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::model($target, ['route' => array('cm.update', $target->id), 'method' => 'PATCH', 'files'=> true, 'class' => 'form-horizontal'] ) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="courseId">@lang('label.COURSE') :<span class="text-danger hide-mandatory-sign"> *</span></label>
                            <div class="col-md-8">
                                <div id="courseHolder">
                                    {!! Form::select('course_id', $courseList, null, ['class' => 'form-control js-source-states', 'id' => 'courseId', 'autocomplete' => 'off']) !!}
                                </div>                                
                                <span class="text-danger">{{ $errors->first('course_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="wingId">@lang('label.WING') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('wing_id', $wingList, null, ['class' => 'form-control js-source-states', 'id' => 'wingId']) !!}
                                <span class="text-danger">{{ $errors->first('wing_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group" id="showRank">
                            <label class="control-label col-md-4" for="rankId">@lang('label.RANK') :<span class="text-danger hide-mandatory-sign"> *</span></label>
                            <div class="col-md-8">
                                <div id="rankHolder">
                                    {!! Form::select('rank_id', $rankList, null, ['class' => 'form-control js-source-states', 'id' => 'rankId']) !!}
                                </div> 

                                <span class="text-danger">{{ $errors->first('rank_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class = "control-label col-md-4" for="armsServiceId">@lang('label.ARMS_SERVICE') :<span class="text-danger hide-mandatory-sign"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('arms_service_id', $armsServiceList, null,  ['class' => 'form-control js-source-states', 'id' => 'armsServiceId']) !!}
                                <span class="text-danger">{{ $errors->first('arms_service_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="personalNo">@lang('label.PERSONAL_NO') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('personal_no', null, ['id'=> 'personalNo', 'class' => 'form-control', 'autocomplete' => 'off']) !!} 
                                <span class="text-danger">{{ $errors->first('personal_no') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="fullName">@lang('label.FULL_NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('full_name', null, ['id'=> 'fullName', 'class' => 'form-control', 'autocomplete' => 'off']) !!} 
                                <span class="text-danger">{{ $errors->first('full_name') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="officialName">@lang('label.OFFICIAL_NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('official_name', null, ['id'=> 'officialName', 'class' => 'form-control', 'autocomplete' => 'off']) !!} 
                                <span class="text-danger">{{ $errors->first('official_name') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="fatherName">@lang('label.FATHERS_NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('father_name', null, ['id'=> 'fatherName', 'class' => 'form-control', 'autocomplete' => 'off']) !!} 
                                <span class="text-danger">{{ $errors->first('father_name') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4" for="status">@lang('label.STATUS') :</label>
                            <div class="col-md-8">
                                {!! Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], '1', ['class' => 'form-control js-source-states-2', 'id' => 'status']) !!}
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            </div>
                        </div>
                    </div>

                    <!--Start image part-->
                    <div class="col-md-4">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                @if(!empty($target->photo))
                                <img src="{{URL::to('/')}}/public/uploads/cm/{{$target->photo}}" alt="{{ $target->full_name}}"/>
                                @endif
                            </div>
                            <div>
                                <span class="btn green-seagreen btn-outline btn-file">
                                    <span class="fileinput-new"> Select image </span>
                                    <span class="fileinput-exists"> Change </span>
                                    {!! Form::file('photo', null, ['id'=> 'photo']) !!}
                                </span>
                                @if(!empty($target->photo))
                                <a href="javascript:;" class="btn green-seagreen" data-dismiss="fileinput"> Remove </a>
                                @else
                                <a href="javascript:;" class="btn green-seagreen fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                @endif
                            </div>
                        </div>
                        <div class="clearfix margin-top-10">
                            <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.USER_IMAGE_FOR_IMAGE_DESCRIPTION')
                        </div>
                    </div>
                    <!--End Image-->

                    <!--Start Course in Academy Information-->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="row margin-bottom-10">
                                <div class="col-md-12">
                                    <span class="col-md-12 border-bottom-1-green-seagreen">
                                        <strong>@lang('label.COURSE_IN_ACADEMY_INFORMATION')</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="control-label col-md-5" for="commissioningCourseId">@lang('label.COMMISSIONING_COURSE') :<span class="text-danger hide-mandatory-sign"> *</span></label>
                                    <div class="col-md-7">
                                        {!! Form::select('commissioning_course_id', $commissioningCourseList, null, ['class' => 'form-control js-source-states', 'id' => 'commissioningCourseId', 'autocomplete' => 'off']) !!}
                                        <span class="text-danger">{{ $errors->first('commissioning_course_id') }}</span>
                                    </div>
                                </div>

                                <div class = "form-group">
                                    <label class = "control-label col-md-5" for="commisioningDate">@lang('label.COMMISSIONING_DATE') :<span class="text-danger hide-mandatory-sign"> *</span></label>
                                    <div class="col-md-7">
                                        <div class="input-group date datepicker2">
                                            {!! Form::text('commisioning_date', !empty($target->commisioning_date) ? Helper::formatDate($target->commisioning_date) : null, ['id'=> 'commisioningDate', 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '']) !!} 
                                            <span class="input-group-btn">
                                                <button class="btn default reset-date" type="button" remove="commisioningDate">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                <button class="btn default date-set" type="button">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </span>
                                        </div>
                                        <span class="text-danger">{{ $errors->first('commisioning_date') }}</span>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="control-label col-md-5" for="commissionType">@lang('label.TYPE_OF_COMMISSION') :<span class="text-danger hide-mandatory-sign"> *</span></label>
                                    <div class="col-md-7">
                                        {!! Form::select('commission_type', $commissionTypeList, null, ['class' => 'form-control js-source-states', 'id' => 'commissionType', 'autocomplete' => 'off']) !!}
                                        <span class="text-danger">{{ $errors->first('commission_type') }}</span>
                                    </div>
                                </div>

                                <div class = "form-group">
                                    <label class = "control-label col-md-5" for="anteDateSeniority">@lang('label.ANTE_DATE_SENIORITY') :</label>
                                    <div class="col-md-7">
                                        {!! Form::text('ante_date_seniority', null, ['id'=> 'antiDateSeniority', 'class' => 'form-control']) !!}
                                        <span class="text-danger">{{ $errors->first('ante_date_seniority') }}</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!--End Course in information-->

                    <!--Start Basic Information-->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="row margin-bottom-10">
                                <div class="col-md-12">
                                    <span class="col-md-12 border-bottom-1-green-seagreen">
                                        <strong>@lang('label.BASIC_INFORMATION')</strong>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="row">

                                <div class = "form-group">
                                    <label class = "control-label col-md-5" for="dob">@lang('label.DATE_OF_BIRTH') :<span class="text-danger hide-mandatory-sign"> *</span></label>
                                    <div class="col-md-7">
                                        <div class="input-group date datepicker2">
                                            {!! Form::text('date_of_birth', !empty($target->date_of_birth) ? Helper::formatDate($target->date_of_birth) : null, ['id'=> 'dob', 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '']) !!} 
                                            <span class="input-group-btn">
                                                <button class="btn default reset-date" type="button" remove="dob">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                <button class="btn default date-set" type="button">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </span>
                                        </div>
                                        <span class="text-danger">{{ $errors->first('date_of_birth') }}</span>
                                    </div>                              
                                </div>

                                <div class = "form-group">
                                    <label class = "control-label col-md-5" for="birthPlace">@lang('label.BIRTH_PLACE') :<span class="text-danger"> *</span></label>
                                    <div class="col-md-7">
                                        {!! Form::text('birth_place', null, ['id'=> 'birthPlace', 'class' => 'form-control']) !!}
                                        <span class="text-danger">{{ $errors->first('birth_place') }}</span>
                                    </div>                                
                                </div>

                                <div class = "form-group">
                                    <label class = "control-label col-md-5" for="religionId">@lang('label.RELIGION') :<span class="text-danger"> *</span></label>
                                    <div class="col-md-7">
                                        {!! Form::select('religion_id', $religionList, null,  ['class' => 'form-control js-source-states', 'id' => 'religionId']) !!}
                                        <span class="text-danger">{{ $errors->first('religion_id') }}</span>
                                    </div>                                
                                </div>

                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="row">

                                <div class="form-group">
                                    <label class="control-label col-md-5" for="email">@lang('label.EMAIL') :<span class="text-danger"> *</span></label>
                                    <div class="col-md-7">
                                        {!! Form::email('email', null, ['id'=> 'email', 'class' => 'form-control', 'autocomplete' => 'off']) !!} 

                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-5" for="number">@lang('label.PHONE') :<span class="text-danger"> *</span></label>
                                    <div class="col-md-7">
                                        {!! Form::text('number', null, ['id'=> 'number', 'class' => 'form-control', 'autocomplete' => 'off']) !!} 
                                        <span class="text-danger">{{ $errors->first('number') }}</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-5" for="bloodGroup">@lang('label.BLOOD_GROUP') :</label>
                                    <div class="col-md-7">
                                        {!! Form::select('blood_group', $bloodGroupList, null,  ['class' => 'form-control js-source-states', 'id' => 'bloodGroup']) !!}
                                        <span class="text-danger">{{ $errors->first('blood_group') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End Basic Information-->

                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="submit">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('/cm'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>

<script>
    $(document).on('change', '#wingId', function () {
        var wingId = $('#wingId').val();

        $.ajax({
            url: "{{URL::to('cm/getRank')}}",
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                wing_id: wingId
            },
            success: function (res) {
                $('#rankId').html(res.html);
            },
        });
    });
</script>
@stop