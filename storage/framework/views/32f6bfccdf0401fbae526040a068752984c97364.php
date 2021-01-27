
<!--get  Appt  -->
<div id="showAppt">
    <?php if(!$targetArr->isEmpty()): ?>

    <div class = "form-group">
        <label class = "control-label col-md-4" for = "moduleId"><?php echo app('translator')->get('label.CHOOSE_APPT'); ?> :<span class = "text-danger"> *</span></label>
        <div class = "col-md-8 margin-top-8">
            <div class="md-checkbox">
                <input type="checkbox" id="checkedAll" class="md-check">
                <label for="checkedAll">
                    <span></span>
                    <span class="check"></span>
                    <span class="box"></span>
                </label>
                <span class="bold"><?php echo app('translator')->get('label.CHECK_ALL'); ?></span>
            </div>
            <div class="form-group form-md-line-input">
                <div class="col-md-10">
                    <div class="md-checkbox-list">
                        <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <?php
                        $checked = !empty($apptStringToArr) && in_array($item->id, $apptStringToArr) ? 'checked' : '';
                        ?>

                        <div class="md-checkbox">
                            <?php echo Form::checkbox('appt_id['.$item->id.']',$item->id, $checked, ['id' => $item->id, 'data-id'=>$item->id,'class'=> 'md-check event-to-appt-check']); ?>

                            <span class = "text-danger"><?php echo e($errors->first('appt_id')); ?></span>
                            <label for="<?php echo e($item->id); ?>">
                                <span></span>
                                <span class="check tooltips"></span>
                                <span class="box tooltips"></span><?php echo e($item->name); ?>

                            </label>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class = "form-actions">
        <div class = "col-md-offset-4 col-md-8">
            <button class = "button-submit btn btn-circle green" type="button">
                <i class = "fa fa-check"></i> <?php echo app('translator')->get('label.SUBMIT'); ?>
            </button>
            <a href = "<?php echo e(URL::to('eventToApptMatrix')); ?>" class = "btn btn-circle btn-outline grey-salsa"><?php echo app('translator')->get('label.CANCEL'); ?></a>
        </div>
    </div>
    <?php else: ?>
    <div class="col-md-12">
        <div class="alert alert-danger alert-dismissable">
            <p><i class="fa fa-bell-o fa-fw"></i><?php echo app('translator')->get('label.NO_MODULE_FOUND'); ?></p>
        </div>
    </div>
    <?php endif; ?>
    <script type="text/javascript">
//    CHECK ALL
        $(document).ready(function () {
            // this code for  database 'check all' if all checkbox items are checked
            if ($('.event-to-appt-check:checked').length == $('.event-to-appt-check').length) {
                $('#checkedAll')[0].checked = true; //change 'check all' checked status to true
            }

            $("#checkedAll").change(function () {
                if (this.checked) {
                    $(".md-check").each(function () {
                        if (!this.hasAttribute("disabled")) {
                            this.checked = true;
                        }
                    });
                } else {
                    $(".md-check").each(function () {
                        this.checked = false;
                    });
                }
            });

            $('.event-to-appt-check').change(function () {
                if (this.checked == false) { //if this item is unchecked
                    $('#checkedAll')[0].checked = false; //change 'check all' checked status to false
                }

                //check 'check all' if all checkbox items are checked
                if ($('.event-to-appt-check:checked').length == $('.event-to-appt-check').length) {
                    $('#checkedAll')[0].checked = true; //change 'check all' checked status to true
                }
            });

        });
//    CHECK ALL
    </script>
    <script src="<?php echo e(asset('public/js/custom.js')); ?>" type="text/javascript"></script>

</div>

<?php /**PATH E:\Xampp\htdocs\afwc\resources\views/eventToApptMatrix/showApptMatrix.blade.php ENDPATH**/ ?>