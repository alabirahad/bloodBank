<div class="row">
    <div class="col-md-12 table-responsive">
        <div class="webkit-scrollbar">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                        <th class="w-8 vcenter">
                            <div class="md-checkbox has-success">
                                <?php echo Form::checkbox('check_all',1,false,['id' => 'checkAll','class'=> 'md-check']); ?> 
                                <label for="checkAll">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                            </div>
                        </th>
                        <th class="text-center vcenter"><?php echo app('translator')->get('label.TERM'); ?></th>
                        <th class="text-center vcenter"><?php echo app('translator')->get('label.INITIAL_DATE'); ?></th>
                        <th class="text-center vcenter"><?php echo app('translator')->get('label.TERMINATION_DATE'); ?></th>
                        <th class="text-center vcenter"><?php echo app('translator')->get('label.NUMBER_OF_WEEK'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($termArr)): ?>
                    <?php $sl = 0; ?>
                    <?php $__currentLoopData = $termArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $termId => $termName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                    $checked = in_array($termId, array_keys($prevData)) ? 'checked' : null;
                    $disabled = in_array($termId, array_keys($prevData)) ? null : 'disabled';
                    $title = $termDisabled = '';
                    if (!empty($prevData[$termId]['active']) && $prevData[$termId]['active'] == '1') {
                        $termDisabled = 'disabled';
                        $title = $termName . ' ' . __('label.IS_ALREADY_ACTIVE');
                    }
                    if (!empty($prevData[$termId]['status']) && $prevData[$termId]['status'] == '2') {
                        $termDisabled = 'disabled';
                        $title = $termName . ' ' . __('label.IS_ALREADY_CLOSED');
                    }
                    ?>
                    <tr>
                        <td class="text-center vcenter"><?php echo ++$sl; ?></td>
                        <td class="text-center  vcenter">
                            <div class="md-checkbox has-success tooltips" title="<?php echo $title ?>">
                                <?php echo Form::checkbox('term_id['.$termId.']', $termId,$checked,['id' => 'term-'.$termId, 'class'=> 'md-check term tooltips ', 'data-term-id' => $termId, $termDisabled]); ?>

                                <label for="term-<?php echo e($termId); ?>">
                                    <span class="inc"></span>
                                    <span class="check mark-caheck"></span>
                                    <span class="box mark-caheck"></span>
                                </label>
                            </div>
                            
                        </td>
                        <td class="text-center  vcenter"><?php echo e($termName); ?></td>
                        <td class="text-center  vcenter">
                            <div class="input-group date datepicker2">
                                <?php echo Form::text('initial_date['.$termId.']', !empty($prevData[$termId])? Helper::formatDate($prevData[$termId]['initial_date']):null, ['id'=> 'initialDate-'.$termId, 'class' =>
                                'form-control term-date initial-date', 'placeholder' => 'DD MM YYYY', 'readonly' => '', 'readonly' => '',$disabled,'data-term-id' => $termId]); ?>

                                <span class="input-group-btn">
                                    <button class="btn default reset-date" id="initialReset_<?php echo e($termId); ?>" data-term-id="<?php echo e($termId); ?>" type="button" remove="initialDate-<?php echo e($termId); ?>" <?php echo e($disabled); ?>>
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button"  <?php echo e($disabled); ?> id="initialSet_<?php echo e($termId); ?>">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                        </td>
                        <td class="text-center  vcenter">
                            <div class="input-group date datepicker2">
                                <?php echo Form::text('termination_date['.$termId.']', !empty($prevData[$termId])? Helper::formatDate($prevData[$termId]['termination_date']):null, ['id'=> 'terminationDate-'.$termId, 'class' =>
                                'form-control term-date termination-date', 'placeholder' => 'DD MM YYYY', 'readonly' => '', 'readonly' => '',$disabled,'data-term-id' => $termId]); ?>

                                <span class="input-group-btn">
                                    <button class="btn default reset-date" id="terminationReset_<?php echo e($termId); ?>" data-term-id="<?php echo e($termId); ?>" type="button" remove="terminationDate-<?php echo e($termId); ?>" <?php echo e($disabled); ?>>
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button class="btn default date-set" type="button" <?php echo e($disabled); ?> id="terminationSet_<?php echo e($termId); ?>">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                        </td>
                        <td class="text-center  vcenter">
                            <div class="col-md-12">
                                <?php echo Form::text('number_of_week['.$termId.']', !empty($prevData[$termId])?$prevData[$termId]['number_of_week']:null, ['id'=> 'noOfWeeks-'.$termId, 'class' => 'form-control number-of-week integer-only', 'readonly',$disabled]); ?>

                                <div>
                                    <span class="text-danger"><?php echo e($errors->first('number_of_week')); ?></span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="10"><?php echo app('translator')->get('label.NO_TERM_FOUND'); ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="form-actions">
            <div class="row">
                <div class="col-md-offset-5 col-md-5">
                    <button class="btn btn-circle green button-submit" type="button" id="buttonSubmit" >
                        <i class="fa fa-check"></i> <?php echo app('translator')->get('label.SUBMIT'); ?>
                    </button>
                    <a href="<?php echo e(URL::to('termToCourse')); ?>" class="btn btn-circle btn-outline grey-salsa"><?php echo app('translator')->get('label.CANCEL'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo e(asset('public/js/custom.js')); ?>"></script>
<script type="text/javascript">
$(document).ready(function () {
    //function for no of weeks
    $(document).on('change', '.term-date', function () {
        var termId = $(this).attr('data-term-id');
        var initialDate = new Date($('#initialDate-' + termId).val());
        var terminationDate = new Date($('#terminationDate-' + termId).val());
        if (terminationDate < initialDate) {
            swal("<?php echo app('translator')->get('label.TERMINATION_DATE_MUST_BE_GREATER_THAN_INITIAL_DATE'); ?>");
            $('#terminationDate-' + termId).val('');
            $('noOfWeeks-' + termId).val('');
            return false;
        }

        var weeks = Math.ceil((terminationDate - initialDate) / (24 * 3600 * 1000 * 7));

        if (isNaN(weeks)) {
            var weeks = '';
        }
        $("#noOfWeeks-" + termId).val(weeks);
    });

    $(document).on('click', '.reset-date', function () {
        var termId = $(this).attr('data-term-id');
        $("#noOfWeeks-" + termId).val('');
    });
    //'check all' change
    $(document).on('click', '#checkAll', function () {
        if (this.checked) {
            $(".initial-date").prop('disabled', false);
            $(".termination-date").prop('disabled', false);
            $(".reset-date").prop('disabled', false);
            $(".date-set").prop('disabled', false);
            $(".number-of-week").prop('disabled', false);
            $('.term').prop('checked', $(this).prop('checked')); //change all 'checkbox' checked status
        } else {
            $(".initial-date").prop('disabled', true);
            $(".termination-date").prop('disabled', true);
            $(".reset-date").prop('disabled', true);
            $(".date-set").prop('disabled', true);
            $(".number-of-week").prop('disabled', true);
            $(".term").removeAttr('checked');
            $(".term-date").prop('disabled', true).val('');
            $(".number-of-week ").prop('disabled', true).val('');

        }
    });

    $(document).on('click', '.term', function () {
        var termId = $(this).data('term-id');
        if (this.checked == false) { //if this item is unchecked
            $("#initialDate-" + termId).prop('disabled', true).val('');
            $("#terminationDate-" + termId).prop('disabled', true).val('');
            $("#initialReset_" + termId).prop('disabled', true);
            $("#terminationReset_" + termId).prop('disabled', true);
            $("#initialSet_" + termId).prop('disabled', true);
            $("#terminationSet_" + termId).prop('disabled', true);
            $("#noOfWeeks-" + termId).prop('disabled', true).val(' ');
//                $('#checkAll')[0].checked = false; //change 'check all' checked status to false
        } else {
            $("#initialDate-" + termId).prop('disabled', false);
            $("#terminationDate-" + termId).prop('disabled', false);
            $("#initialReset_" + termId).prop('disabled', false);
            $("#terminationReset_" + termId).prop('disabled', false);
            $("#initialSet_" + termId).prop('disabled', false);
            $("#terminationSet_" + termId).prop('disabled', false);
            $("#noOfWeeks-" + termId).prop('disabled', false);
//                $('#checkAll')[0].checked = true;
        }
        //check 'check all' if all checkbox items are checked

        allCheck();
    });


//        $(document).on('click', '.term', function () {
//            allCheck();
//        });
    allCheck();


});
function allCheck() {

    if ($('.term:checked').length == $('.term').length) {
        $('#checkAll')[0].checked = true;
    } else {
        $('#checkAll')[0].checked = false;
    }
}

</script><?php /**PATH E:\Xampp\htdocs\afwc\resources\views/termToCourse/courseSchedule.blade.php ENDPATH**/ ?>