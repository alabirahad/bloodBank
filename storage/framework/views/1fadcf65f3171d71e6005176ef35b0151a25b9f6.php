<div class="row margin-top-10">
    <div class="col-md-7 table-responsive webkit-scrollbar col-md-offset-2">
        <table class="table table-bordered table-hover" id="dataTable">
            <thead>
                <tr>
                    <th class="text-center vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                    <th class="vcenter"><?php echo app('translator')->get('label.TERM'); ?></th>
<!--                    <th class="text-center vcenter"><?php echo app('translator')->get('label.MODERATION_MARKING_LIMIT'); ?></th>
                </tr>
                <tr>-->
                    <th class="text-center vcenter"><?php echo app('translator')->get('label.CI_MODERAION'); ?></th>
                    <!--<th class="text-center vcenter"><?php echo app('translator')->get('label.COMDT_MODERAION'); ?></th>-->
                </tr>
            </thead>
            <tbody>
                <?php if(!$termArr->isEmpty()): ?>
                <?php $sl = 0; ?>
                <?php $__currentLoopData = $termArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $term): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $ciMod = !empty($prevDataArr[$term->id]['ci_mod']) ? $prevDataArr[$term->id]['ci_mod'] : null;
                $comdtMod = !empty($prevDataArr[$term->id]['comdt_mod']) ? $prevDataArr[$term->id]['comdt_mod'] : null;
                ?>
                <tr>
                    <td class="text-center vcenter width-80"><?php echo ++$sl; ?></td>
                    <td class="vcenter width-250">
                        <div class="width-inherit"><?php echo $term->name ?? ''; ?></div>
                    </td>
                    <td class="text-center vcenter width-150">
                        <div class="input-group bootstrap-touchspin width-inherit">
                            <span class="input-group-addon bootstrap-touchspin-prefix bold">&plusmn;</span>
                            <?php echo Form::text('mod['.$term->id.'][ci_mod]', $ciMod, ['id'=> 'ciMod_' . $term->id, 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per ci-mod ci-mod-' . $term->id, 'data-term-id' => $term->id]); ?>

                            <span class="input-group-addon bootstrap-touchspin-postfix bold">%</span>
                        </div>
                    </td>
<!--                    <td class="text-center vcenter width-150">
                        <div class="input-group bootstrap-touchspin width-inherit">
                            <span class="input-group-addon bootstrap-touchspin-prefix bold">&plusmn;</span>
                            <?php echo Form::text('mod['.$term->id.'][comdt_mod]', $comdtMod, ['id'=> 'comdtMod_' . $term->id, 'class' => 'form-control text-right integer-decimal-only text-input-width-100-per comdt-mod comdt-mod-' . $term->id, 'data-term-id' => $term->id]); ?>

                            <span class="input-group-addon bootstrap-touchspin-postfix bold">%</span>
                        </div>
                    </td>-->
                </tr>
                <?php echo Form::hidden('mod['.$term->id.'][term_name]', $term->name ?? '', ['id' => 'termName_' . $term->id]); ?>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                <tr>
                    <td colspan="4"><?php echo app('translator')->get('label.NO_TERM_IS_ASSIGNED_TO_THIS_COURSE'); ?></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div class = "form-actions margin-top-10">
    <div class = "col-md-offset-4 col-md-8">
        <button class = "button-submit btn btn-circle green" type="button">
            <i class = "fa fa-check"></i> <?php echo app('translator')->get('label.SUBMIT'); ?>
        </button>
        <a href = "<?php echo e(URL::to('moderationMarkingLimit')); ?>" class = "btn btn-circle btn-outline grey-salsa"><?php echo app('translator')->get('label.CANCEL'); ?></a>
    </div>
</div>

<script src="<?php echo e(asset('public/js/custom.js')); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(function () {

    //start: for CI mod
    $(document).on('keyup', '.ci-mod', function () {
        modRange(this);
    });
    //start: for CI mod
    
    //start: for Comdt mod
    $(document).on('keyup', '.comdt-mod', function () {
        modRange(this);
    });
    //start: for Comdt mod
});

function modRange(modClass){
    var mod = $(modClass).val();
        if (mod > 100) {
            swal({
                title: "<?php echo app('translator')->get('label.PLEASE_PUT_A_VALUE_UP_TO_100'); ?> ",
                text: "",
                type: "warning",
                showCancelButton: false,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "<?php echo app('translator')->get('label.OK'); ?>",
                closeOnConfirm: true,
                closeOnCancel: true,
            }, function (isConfirm) {
                if (isConfirm) {
                    $(modClass).val('');
                }
            });
            return false;
        }
}
</script>

<?php /**PATH E:\Xampp\htdocs\afwc\resources\views/moderationMarkingLimit/showMarkingLimit.blade.php ENDPATH**/ ?>