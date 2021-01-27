
<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i><?php echo app('translator')->get('label.EDIT_USER'); ?>
            </div>
        </div>
        <div class="portlet-body form">
            <?php echo Form::model($target, ['route' => array('user.update', $target->id), 'method' => 'PATCH', 'files'=> true, 'class' => 'form-horizontal'] ); ?>

            <?php echo Form::hidden('filter', Helper::queryPageStr($qpArr)); ?>

            <?php echo e(csrf_field()); ?>

            <div class="form-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="groupId"><?php echo app('translator')->get('label.USER_GROUP'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::select('group_id', $groupList, null, ['class' => 'form-control js-source-states', 'id' => 'groupId']); ?>

                                <span class="text-danger"><?php echo e($errors->first('group_id')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="fullName"><?php echo app('translator')->get('label.NAME'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::text('full_name', null, ['id'=> 'fullName', 'class' => 'form-control', 'list' => 'userFullName']); ?> 
                                <datalist id="userFullName">
                                    <?php if(!$userNameArr->isEmpty()): ?>
                                    <?php $__currentLoopData = $userNameArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->full_name); ?>" />
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </datalist>
                                <span class="text-danger"><?php echo e($errors->first('full_name')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="username"><?php echo app('translator')->get('label.USERNAME'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::text('username', null, ['id'=> 'username', 'class' => 'form-control']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('username')); ?></span>
                                <div class="clearfix margin-top-10">
                                    <span class="label label-danger"><?php echo app('translator')->get('label.NOTE'); ?></span> <?php echo app('translator')->get('label.USERNAME_DESCRIPTION'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="phone"><?php echo app('translator')->get('label.PHONE'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::text('phone', null, ['id'=> 'phone', 'class' => 'form-control interger-decimal-only']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('phone')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="alternativePhone"><?php echo app('translator')->get('label.ALTERNATIVE_PHONE'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::text('alternative_phone', null, ['id'=> 'alternativePhone', 'class' => 'form-control interger-decimal-only', 'autocomplete' => 'off']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('alternative_phone')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="userEmail"><?php echo app('translator')->get('label.EMAIL'); ?> :</label>
                            <div class="col-md-8">
                                <?php echo Form::email('email', null, ['id'=> 'userEmail', 'class' => 'form-control']); ?>

                                <span class="text-danger"><?php echo e($errors->first('email')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="bloodGroupId"><?php echo app('translator')->get('label.BLOOD_GROUP'); ?> :<span class="text-danger hide-mandatory-sign"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::select('blood_group_id', $bloodGroupList , null, ['class' => 'form-control js-source-states', 'id' => 'bloodGroupId', 'autocomplete' => 'off']); ?>

                                <span class="text-danger"><?php echo e($errors->first('blood_group_id')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="religion"><?php echo app('translator')->get('label.RELIGION'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::text('religion', null, ['id'=> 'religion', 'class' => 'form-control', 'autocomplete' => 'off']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('religion')); ?></span>
                            </div>
                        </div>

                        <div class = "form-group">
                            <label class = "control-label col-md-4" for="dob"><?php echo app('translator')->get('label.DATE_OF_BIRTH'); ?> :<span class="text-danger hide-mandatory-sign"> *</span></label>
                            <div class="col-md-8">
                                <div class="input-group date datepicker2">
                                    <?php echo Form::text('date_of_birth', null, ['id'=> 'dob', 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '']); ?> 
                                    <span class="input-group-btn">
                                        <button class="btn default reset-date" type="button" remove="dob">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <button class="btn default date-set" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                                <span class="text-danger"><?php echo e($errors->first('date_of_birth')); ?></span>
                            </div>                              
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="weight"><?php echo app('translator')->get('label.WEIGHT'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::text('weight', null, ['id'=> 'weight', 'class' => 'form-control interger-decimal-only', 'autocomplete' => 'off']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('weight')); ?></span>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                <?php if(!empty($target->photo)): ?>
                                <img src="<?php echo e(URL::to('/')); ?>/public/uploads/user/<?php echo e($target->photo); ?>" alt="<?php echo e($target->full_name); ?>"/>
                                <?php endif; ?>
                            </div>
                            <div>
                                <span class="btn green-seagreen btn-outline btn-file">
                                    <span class="fileinput-new"> Select image </span>
                                    <span class="fileinput-exists"> Change </span>
                                    <?php echo Form::file('photo', null, ['id'=> 'photo']); ?>

                                </span>
                                <?php if(!empty($target->photo)): ?>
                                <a href="javascript:;" class="btn green-seagreen" data-dismiss="fileinput"> Remove </a>
                                <?php else: ?>
                                <a href="javascript:;" class="btn green-seagreen fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="clearfix margin-top-10">
                            <span class="label label-danger"><?php echo app('translator')->get('label.NOTE'); ?></span> <?php echo app('translator')->get('label.USER_IMAGE_FOR_IMAGE_DESCRIPTION'); ?>
                        </div>
                    </div>
                    
                     <!--address-->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="row margin-bottom-10">
                                <div class="col-md-12">
                                    <span class="col-md-12 border-bottom-1-green-seagreen">
                                        <strong><?php echo app('translator')->get('label.ADDRESS'); ?></strong>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="row">
                                <div class="col-md-12 margin-bottom-10 text-center">
                                    <strong><?php echo app('translator')->get('label.PRESENT_ADDRESS'); ?></strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class = "form-group">
                                    <label class="control-label col-md-4" for="divisionId"><?php echo app('translator')->get('label.DIVISION'); ?> :</label>
                                    <div class="col-md-8">
                                        <?php echo Form::select('present_division_id', $divisionList , null, ['class' => 'form-control js-source-states', 'id' => 'divisionId', 'autocomplete' => 'off']); ?>

                                        <span class="text-danger"><?php echo e($errors->first('present_division_id')); ?></span>
                                    </div>                           
                                </div>

                                <div class = "form-group">
                                    <label class="control-label col-md-4" for="districtId"><?php echo app('translator')->get('label.DISTRICT'); ?> :</label>
                                    <div class="col-md-8">
                                        <?php echo Form::select('present_district_id', $districtList , null, ['class' => 'form-control js-source-states', 'id' => 'districtId', 'autocomplete' => 'off']); ?>

                                        <span class="text-danger"><?php echo e($errors->first('present_district_id')); ?></span>
                                    </div>                           
                                </div>

                                <div class = "form-group">
                                    <label class="control-label col-md-4" for="thanaId"><?php echo app('translator')->get('label.THANA'); ?> :</label>
                                    <div class="col-md-8">
                                        <?php echo Form::select('present_thana_id', $thanaList , null, ['class' => 'form-control js-source-states', 'id' => 'thanaId', 'autocomplete' => 'off']); ?>

                                        <span class="text-danger"><?php echo e($errors->first('present_thana_id')); ?></span>
                                    </div>                           
                                </div>

                                <div class = "form-group">
                                    <label class="control-label col-md-4" for="addressDetails"><?php echo app('translator')->get('label.ADDRESS'); ?> :</label>
                                    <div class="col-md-8">
                                        <?php echo Form::text('present_address_details',  null, ['class' => 'form-control', 'id' => 'addressDetails', 'autocomplete' => 'off']); ?>

                                        <span class="text-danger"><?php echo e($errors->first('present_address_details')); ?></span>
                                    </div>                           
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="row">
                                <div class="col-md-12 margin-bottom-10 checkbox-center md-checkbox has-success text-center">
                                    <strong><?php echo app('translator')->get('label.PERMANENT_ADDRESS'); ?></strong>(<span class="custom-check-mark">
                                        <?php
                                        $checked = !empty($addressInfo->same_as_present) ? 'checked' : '';
                                        ?>
                                        <input id="forPermanentAddr" class="md-check" name="for_addr" type="checkbox" <?php echo e($checked); ?> value="<?php echo e(!empty($addressInfo->same_as_present) ? $addressInfo->same_as_present : null); ?>">
                                        <label for="forPermanentAddr" class="course-member">
                                            <span class="inc"></span>
                                            <span class="check mark-caheck"></span>
                                            <span class="box mark-caheck"></span>
                                        </label>
                                        <span class="text-green"><?php echo app('translator')->get('label.SAME_AS_PRESENT_ADDRESS'); ?></span>
                                    </span>)
                                </div>
                            </div>
                            <div class="row permanent-address-block">
                                <div class = "form-group">
                                    <label class="control-label col-md-4" for="perDivisionId"><?php echo app('translator')->get('label.DIVISION'); ?> :</label>
                                    <div class="col-md-8">
                                        <?php echo Form::select('permanent_division_id', $divisionList , null, ['class' => 'form-control js-source-states', 'id' => 'perDivisionId', 'autocomplete' => 'off']); ?>

                                        <span class="text-danger"><?php echo e($errors->first('permanent_division_id')); ?></span>
                                    </div>                           
                                </div>

                                <div class = "form-group">
                                    <label class="control-label col-md-4" for="perDistrictId"><?php echo app('translator')->get('label.DISTRICT'); ?> :</label>
                                    <div class="col-md-8">
                                        <?php echo Form::select('permanent_district_id', $districtList , null, ['class' => 'form-control js-source-states', 'id' => 'perDistrictId', 'autocomplete' => 'off']); ?>

                                        <span class="text-danger"><?php echo e($errors->first('permanent_district_id')); ?></span>
                                    </div>                           
                                </div>

                                <div class = "form-group">
                                    <label class="control-label col-md-4" for="perThanaId"><?php echo app('translator')->get('label.THANA'); ?> :</label>
                                    <div class="col-md-8">
                                        <?php echo Form::select('permanent_thana_id', $thanaList , null, ['class' => 'form-control js-source-states', 'id' => 'perThanaId', 'autocomplete' => 'off']); ?>

                                        <span class="text-danger"><?php echo e($errors->first('permanent_thana_id')); ?></span>
                                    </div>                           
                                </div>

                                <div class = "form-group">
                                    <label class="control-label col-md-4" for="perAddressDetails"><?php echo app('translator')->get('label.ADDRESS'); ?> :</label>
                                    <div class="col-md-8">
                                        <?php echo Form::text('permanent_address_details',  null, ['class' => 'form-control', 'id' => 'perAddressDetails', 'autocomplete' => 'off']); ?>

                                        <span class="text-danger"><?php echo e($errors->first('permanent_address_details')); ?></span>
                                    </div>                           
                                </div>
                            </div>

                            <div class="col-md-12 permanent-address-present">
                                <div class="alert alert-success alert-dismissable">
                                    <p><strong><i class="fa fa-map-marker"></i> <?php echo __('label.PERMANENT_ADDRESS_IS_SAME_AS_PRESENT_ADDRESS'); ?></strong></p>
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
                            <i class="fa fa-check"></i> <?php echo app('translator')->get('label.SUBMIT'); ?>
                        </button>
                        <a href="<?php echo e(URL::to('/user'.Helper::queryPageStr($qpArr))); ?>" class="btn btn-circle btn-outline grey-salsa"><?php echo app('translator')->get('label.CANCEL'); ?></a>
                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>

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
            url: "<?php echo e(url('user/getDistrict')); ?>",
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
            url: "<?php echo e(url('user/getDistrict')); ?>",
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
            url: "<?php echo e(url('user/getThana')); ?>",
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
            url: "<?php echo e(url('user/getThana')); ?>",
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Xampp\htdocs\bloodBank\resources\views/user/edit.blade.php ENDPATH**/ ?>