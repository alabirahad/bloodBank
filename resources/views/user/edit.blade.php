@extends('layouts.default.master')
@section('data_count')
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>@lang('label.EDIT_USER')
            </div>
        </div>
        <div class="portlet-body form">
            {!! Form::model($target, ['route' => array('user.update', $target->id), 'method' => 'PATCH', 'files'=> true, 'class' => 'form-horizontal'] ) !!}
            {!! Form::hidden('filter', Helper::queryPageStr($qpArr)) !!}
            {{csrf_field()}}
            <div class="form-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="groupId">@lang('label.USER_GROUP') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('group_id', $groupList, null, ['class' => 'form-control js-source-states', 'id' => 'groupId']) !!}
                                <span class="text-danger">{{ $errors->first('group_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="fullName">@lang('label.NAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('full_name', null, ['id'=> 'fullName', 'class' => 'form-control', 'list' => 'userFullName']) !!} 
                                <datalist id="userFullName">
                                    @if (!$userNameArr->isEmpty())
                                    @foreach($userNameArr as $user)
                                    <option value="{{$user->full_name}}" />
                                    @endforeach
                                    @endif
                                </datalist>
                                <span class="text-danger">{{ $errors->first('full_name') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="username">@lang('label.USERNAME') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('username', null, ['id'=> 'username', 'class' => 'form-control']) !!} 
                                <span class="text-danger">{{ $errors->first('username') }}</span>
                                <div class="clearfix margin-top-10">
                                    <span class="label label-danger">@lang('label.NOTE')</span> @lang('label.USERNAME_DESCRIPTION')
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="phone">@lang('label.PHONE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('phone', null, ['id'=> 'phone', 'class' => 'form-control interger-decimal-only']) !!} 
                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="alternativePhone">@lang('label.ALTERNATIVE_PHONE') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('alternative_phone', null, ['id'=> 'alternativePhone', 'class' => 'form-control interger-decimal-only', 'autocomplete' => 'off']) !!} 
                                <span class="text-danger">{{ $errors->first('alternative_phone') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="userEmail">@lang('label.EMAIL') :</label>
                            <div class="col-md-8">
                                {!! Form::email('email', null, ['id'=> 'userEmail', 'class' => 'form-control']) !!}
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="bloodGroupId">@lang('label.BLOOD_GROUP') :<span class="text-danger hide-mandatory-sign"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::select('blood_group_id', $bloodGroupList , null, ['class' => 'form-control js-source-states', 'id' => 'bloodGroupId', 'autocomplete' => 'off']) !!}
                                <span class="text-danger">{{ $errors->first('blood_group_id') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="religion">@lang('label.RELIGION') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('religion', null, ['id'=> 'religion', 'class' => 'form-control', 'autocomplete' => 'off']) !!} 
                                <span class="text-danger">{{ $errors->first('religion') }}</span>
                            </div>
                        </div>

                        <div class = "form-group">
                            <label class = "control-label col-md-4" for="dob">@lang('label.DATE_OF_BIRTH') :<span class="text-danger hide-mandatory-sign"> *</span></label>
                            <div class="col-md-8">
                                <div class="input-group date datepicker2">
                                    {!! Form::text('date_of_birth', null, ['id'=> 'dob', 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '']) !!} 
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

                        <div class="form-group">
                            <label class="control-label col-md-4" for="weight">@lang('label.WEIGHT') :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                {!! Form::text('weight', null, ['id'=> 'weight', 'class' => 'form-control interger-decimal-only', 'autocomplete' => 'off']) !!} 
                                <span class="text-danger">{{ $errors->first('weight') }}</span>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                @if(!empty($target->photo))
                                <img src="{{URL::to('/')}}/public/uploads/user/{{$target->photo}}" alt="{{ $target->full_name}}"/>
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
                    
                     <!--address-->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="row margin-bottom-10">
                                <div class="col-md-12">
                                    <span class="col-md-12 border-bottom-1-green-seagreen">
                                        <strong>@lang('label.ADDRESS')</strong>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="row">
                                <div class="col-md-12 margin-bottom-10 text-center">
                                    <strong>@lang('label.PRESENT_ADDRESS')</strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class = "form-group">
                                    <label class="control-label col-md-4" for="divisionId">@lang('label.DIVISION') :</label>
                                    <div class="col-md-8">
                                        {!! Form::select('present_division_id', $divisionList , null, ['class' => 'form-control js-source-states', 'id' => 'divisionId', 'autocomplete' => 'off']) !!}
                                        <span class="text-danger">{{ $errors->first('present_division_id') }}</span>
                                    </div>                           
                                </div>

                                <div class = "form-group">
                                    <label class="control-label col-md-4" for="districtId">@lang('label.DISTRICT') :</label>
                                    <div class="col-md-8">
                                        {!! Form::select('present_district_id', $districtList , null, ['class' => 'form-control js-source-states', 'id' => 'districtId', 'autocomplete' => 'off']) !!}
                                        <span class="text-danger">{{ $errors->first('present_district_id') }}</span>
                                    </div>                           
                                </div>

                                <div class = "form-group">
                                    <label class="control-label col-md-4" for="thanaId">@lang('label.THANA') :</label>
                                    <div class="col-md-8">
                                        {!! Form::select('present_thana_id', $thanaList , null, ['class' => 'form-control js-source-states', 'id' => 'thanaId', 'autocomplete' => 'off']) !!}
                                        <span class="text-danger">{{ $errors->first('present_thana_id') }}</span>
                                    </div>                           
                                </div>

                                <div class = "form-group">
                                    <label class="control-label col-md-4" for="addressDetails">@lang('label.ADDRESS') :</label>
                                    <div class="col-md-8">
                                        {!! Form::text('present_address_details',  null, ['class' => 'form-control', 'id' => 'addressDetails', 'autocomplete' => 'off']) !!}
                                        <span class="text-danger">{{ $errors->first('present_address_details') }}</span>
                                    </div>                           
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="row">
                                <div class="col-md-12 margin-bottom-10 checkbox-center md-checkbox has-success text-center">
                                    <strong>@lang('label.PERMANENT_ADDRESS')</strong>(<span class="custom-check-mark">
                                        <?php
                                        $checked = !empty($addressInfo->same_as_present) ? 'checked' : '';
                                        ?>
                                        <input id="forPermanentAddr" class="md-check" name="for_addr" type="checkbox" {{$checked}} value="{{!empty($addressInfo->same_as_present) ? $addressInfo->same_as_present : null}}">
                                        <label for="forPermanentAddr" class="course-member">
                                            <span class="inc"></span>
                                            <span class="check mark-caheck"></span>
                                            <span class="box mark-caheck"></span>
                                        </label>
                                        <span class="text-green">@lang('label.SAME_AS_PRESENT_ADDRESS')</span>
                                    </span>)
                                </div>
                            </div>
                            <div class="row permanent-address-block">
                                <div class = "form-group">
                                    <label class="control-label col-md-4" for="perDivisionId">@lang('label.DIVISION') :</label>
                                    <div class="col-md-8">
                                        {!! Form::select('permanent_division_id', $divisionList , null, ['class' => 'form-control js-source-states', 'id' => 'perDivisionId', 'autocomplete' => 'off']) !!}
                                        <span class="text-danger">{{ $errors->first('permanent_division_id') }}</span>
                                    </div>                           
                                </div>

                                <div class = "form-group">
                                    <label class="control-label col-md-4" for="perDistrictId">@lang('label.DISTRICT') :</label>
                                    <div class="col-md-8">
                                        {!! Form::select('permanent_district_id', $districtList , null, ['class' => 'form-control js-source-states', 'id' => 'perDistrictId', 'autocomplete' => 'off']) !!}
                                        <span class="text-danger">{{ $errors->first('permanent_district_id') }}</span>
                                    </div>                           
                                </div>

                                <div class = "form-group">
                                    <label class="control-label col-md-4" for="perThanaId">@lang('label.THANA') :</label>
                                    <div class="col-md-8">
                                        {!! Form::select('permanent_thana_id', $thanaList , null, ['class' => 'form-control js-source-states', 'id' => 'perThanaId', 'autocomplete' => 'off']) !!}
                                        <span class="text-danger">{{ $errors->first('permanent_thana_id') }}</span>
                                    </div>                           
                                </div>

                                <div class = "form-group">
                                    <label class="control-label col-md-4" for="perAddressDetails">@lang('label.ADDRESS') :</label>
                                    <div class="col-md-8">
                                        {!! Form::text('permanent_address_details',  null, ['class' => 'form-control', 'id' => 'perAddressDetails', 'autocomplete' => 'off']) !!}
                                        <span class="text-danger">{{ $errors->first('permanent_address_details') }}</span>
                                    </div>                           
                                </div>
                            </div>

                            <div class="col-md-12 permanent-address-present">
                                <div class="alert alert-success alert-dismissable">
                                    <p><strong><i class="fa fa-map-marker"></i> {!! __('label.PERMANENT_ADDRESS_IS_SAME_AS_PRESENT_ADDRESS') !!}</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End address-->
                    
                    
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="submit">
                            <i class="fa fa-check"></i> @lang('label.SUBMIT')
                        </button>
                        <a href="{{ URL::to('/user'.Helper::queryPageStr($qpArr)) }}" class="btn btn-circle btn-outline grey-salsa">@lang('label.CANCEL')</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>	
    </div>
</div>

<script type="text/javascript">
<?php if (Request::old('group_id') <= 3) { ?>
        $('#centerHide').hide();
    <?php
}
if (empty(Request::old('group_id'))) {
    if ($target->group_id >= 4) {
        ?>
            $('#centerHide').show();
    <?php }
} ?>

if ($('#forPermanentAddr').prop('checked')) {
        $('.permanent-address-block').hide();
        $('.permanent-address-present').show();
    } else {
        $('.permanent-address-block').show();
        $('.permanent-address-present').hide();
    }

    $(document).on('click', '#forPermanentAddr', function () {
        if (this.checked == true) {
            $('.permanent-address-block').hide(300);
            $('#forPermanentAddr').val(1);
            $('.permanent-address-present').show(300);
        } else {
            $('.permanent-address-block').show(300);
            $('#forPermanentAddr').val(0);
            $('.permanent-address-present').hide(300);
        }
    });

    //GET district when click division
    $(document).on('change', '#divisionId', function () {
        var divisionId = $(this).val();
        $.ajax({
            type: 'POST',
            url: "{{url('user/getDistrict')}}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                division_id: divisionId
            },
            success: function (result) {
                $('#districtId').html(result.html);
                $('#thanaId').html(result.htmlThana);
            }
        });
    });
    $(document).on('change', '#perDivisionId', function () {
        var divisionId = $(this).val();
        $.ajax({
            type: 'POST',
            url: "{{url('user/getDistrict')}}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                division_id: divisionId
            },
            success: function (result) {
                $('#perDistrictId').html(result.html);
                $('#perThanaId').html(result.htmlThana);
            }
        });
    });

    //GET thana when click district
    $(document).on('change', '#districtId', function () {
        var districtId = $(this).val();
        $.ajax({
            type: 'POST',
            url: "{{url('user/getThana')}}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                district_id: districtId
            },
            success: function (result) {
                $('#thanaId').html(result.html);
            }
        });
    });
    $(document).on('change', '#perDistrictId', function () {
        var districtId = $(this).val();
        $.ajax({
            type: 'POST',
            url: "{{url('user/getThana')}}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                district_id: districtId
            },
            success: function (result) {
                $('#perThanaId').html(result.html);
            }
        });
    });
    //End:: Division District Thana 

</script>
@stop