
<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i><?php echo app('translator')->get('label.REQUEST_BLOOD'); ?>
            </div>
        </div>
        <div class="portlet-body">
            <?php echo Form::open(array('group' => 'form', 'url' => '#','class' => 'form-horizontal','id' => 'submitForm')); ?>

            <?php echo e(csrf_field()); ?>

            <div class="row">
                <div class="col-md-12">

                    <div class="form-group">
                        <label class="control-label col-md-4" for="bloodGroupId"><?php echo app('translator')->get('label.BLOOD_GROUP'); ?> :<span class="text-danger hide-mandatory-sign"> *</span></label>
                        <div class="col-md-4">
                            <?php echo Form::select('blood_group_id', $bloodGroupList , null, ['class' => 'form-control js-source-states', 'id' => 'bloodGroupId', 'autocomplete' => 'off']); ?>

                            <span class="text-danger"><?php echo e($errors->first('blood_group_id')); ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4" for="quantity"><?php echo app('translator')->get('label.QUANTITY'); ?> :<span class="text-danger hide-mandatory-sign"> *</span>(Bag)</label>
                        <div class="col-md-4">
                            <?php echo Form::text('quantity', null, ['id'=> 'quantity', 'class' => 'form-control interger-decimal-only', 'autocomplete' => 'off']); ?> 
                            <span class="text-danger"><?php echo e($errors->first('quantity')); ?></span>
                        </div>
                    </div>

                    <div class = "form-group">
                        <label class="control-label col-md-4" for="divisionId">Place :</label>
                        <div class="col-md-4">
                            <?php echo Form::select('division_id', $divisionList , null, ['class' => 'form-control js-source-states', 'id' => 'divisionId', 'autocomplete' => 'off']); ?>

                            <span class="text-danger"><?php echo e($errors->first('division_id')); ?></span>
                        </div>                           
                    </div>

                    <div class = "form-group">
                        <label class = "control-label col-md-4" for="dob">Date :<span class="text-danger hide-mandatory-sign"> *</span></label>
                        <div class="col-md-4">
                            <div class="input-group date datepicker2">
                                <?php echo Form::text('date', null, ['id'=> 'dob', 'class' => 'form-control', 'placeholder' => 'DD MM YYYY', 'readonly' => '']); ?> 
                                <span class="input-group-btn">
                                    <button class="btn default reset-date" type="button" remove="dob">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class="text-danger"><?php echo e($errors->first('date')); ?></span>
                        </div>                              
                    </div>

                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-5 col-md-5">
                                <button class="btn btn-circle green button-submit" type="button" id="buttonSubmit" >
                                    <i class="fa fa-check"></i> Request
                                </button>
                                <a href="<?php echo e(URL::to('requestBlood')); ?>" class="btn btn-circle btn-outline grey-salsa"><?php echo app('translator')->get('label.CANCEL'); ?></a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <?php echo Form::close(); ?>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {

        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };

        $(document).on('click', '#buttonSubmit', function (e) {
            e.preventDefault();
            var form_data = new FormData($('#submitForm')[0]);
            swal({
                title: 'Are you sure?',
                imageUrl: "<?php echo e(asset('public/img/decision.jpg')); ?>",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'No, cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "<?php echo e(URL::to('requestBlood/request')); ?>",
                        type: "POST",
                        datatype: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function (res) {
                            toastr.success(res, "Request has been sent", options);
//                            window.location.href = requestBlood; 
                            
                        },
                        error: function (jqXhr, ajaxOptions, thrownError) {
                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value[0] + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                                //toastr.error(jqXhr.responseJSON.message, '', options);
                                var errors = jqXhr.responseJSON.message;
                                var errorsHtml = '';
                                if (typeof (errors) === 'object') {
                                    $.each(errors, function (key, value) {
                                        errorsHtml += '<li>' + value + '</li>';
                                    });
                                    toastr.error(errorsHtml, '', options);
                                } else {
                                    toastr.error(jqXhr.responseJSON.message, '', options);
                                }
                            } else {
                                toastr.error('Error', 'Something went wrong', options);
                            }
                            App.unblockUI();
                        }
                    });
                }
            });
        });

    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Xampp\htdocs\bloodBank\resources\views/requestBlood/index.blade.php ENDPATH**/ ?>