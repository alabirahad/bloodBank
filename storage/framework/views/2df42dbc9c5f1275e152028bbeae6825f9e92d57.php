
<?php $__env->startSection('data_count'); ?>	
<div class="col-md-12">
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i><?php echo app('translator')->get('label.CREATE_DECORATION'); ?>
            </div>
        </div>
        <div class="portlet-body form">
            <?php echo Form::open(array('group' => 'form', 'url' => 'decoration', 'files'=> true, 'class' => 'form-horizontal')); ?>

            <?php echo Form::hidden('page', Helper::queryPageStr($qpArr)); ?>

            <?php echo e(csrf_field()); ?>

            <div class="form-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="name"><?php echo app('translator')->get('label.NAME'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::text('name',  null, ['id'=> 'name', 'class' => 'form-control', 'autocomplete' => 'off']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('name')); ?></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="code"><?php echo app('translator')->get('label.SHORT_INFO'); ?> :<span class="text-danger"> *</span></label>
                            <div class="col-md-8">
                                <?php echo Form::text('code', null, ['id'=> 'code', 'class' => 'form-control', 'autocomplete' => 'off']); ?> 
                                <span class="text-danger"><?php echo e($errors->first('code')); ?></span>
                            </div>
                        </div>

                        <div id="order">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="order"><?php echo app('translator')->get('label.ORDER'); ?> :<span class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    <?php echo Form::select('order', $orderList, $lastOrderNumber, ['class' => 'form-control js-source-states', 'id' => 'order']); ?> 
                                    <span class="text-danger"><?php echo e($errors->first('order')); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4" for="status"><?php echo app('translator')->get('label.STATUS'); ?> :</label>
                            <div class="col-md-8">
                                <?php echo Form::select('status', ['1' => __('label.ACTIVE'), '2' => __('label.INACTIVE')], '1', ['class' => 'form-control', 'id' => 'status']); ?>

                                <span class="text-danger"><?php echo e($errors->first('status')); ?></span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        <button class="btn btn-circle green" type="submit">
                            <i class="fa fa-check"></i> <?php echo app('translator')->get('label.SUBMIT'); ?>
                        </button>
                        <a href="<?php echo e(URL::to('/decoration'.Helper::queryPageStr($qpArr))); ?>" class="btn btn-circle btn-outline grey-salsa"><?php echo app('translator')->get('label.CANCEL'); ?></a>
                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>

        </div>	
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $(document).on("change", "#serviceId", function () {
            var serviceId = $("#serviceId").val();
            $.ajax({
                url: "<?php echo e(URL::to('decoration/getOrder')); ?>",
                data: {
                    service_id: serviceId,
                    operation: 1,
                },
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    $('#order').html(response.html);
                    $('.js-source-states').select2();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            });//ajax
        });


    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Xampp\htdocs\afwc\resources\views/decoration/create.blade.php ENDPATH**/ ?>