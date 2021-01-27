<?php if(!$targetArr->isEmpty()): ?>
<div class="row">
    <div class="col-md-12">
        <span class="label label-sm label-blue-steel">
            <?php echo app('translator')->get('label.TOTAL_NO_OF_EVENTS'); ?>:&nbsp;<?php echo !$targetArr->isEmpty() ? sizeOf($targetArr) : 0; ?>

        </span>&nbsp;
        <span class="label label-sm label-green-seagreen">
            <?php echo app('translator')->get('label.TOTAL_NO_OF_ASSIGNED_EVENTS'); ?>:&nbsp;<?php echo !empty($prevDataList) ? sizeOf($prevDataList) : 0; ?>

        </span>&nbsp;
        <button class="label label-sm label-purple btn-label-groove tooltips" href="#modalAssignedEvent" id="assignedEvent"  data-toggle="modal" title="<?php echo app('translator')->get('label.SHOW_ASSIGNED_EVENT'); ?>">
            <?php echo app('translator')->get('label.EVENT_ASSIGNED_TO_THIS_TERM'); ?>:&nbsp;<?php echo !empty($prevCourseWiseTermToEventList) ? sizeOf($prevCourseWiseTermToEventList) : 0; ?>&nbsp; <i class="fa fa-search-plus"></i>
        </button>
    </div>
</div>
<div class="row margin-top-10">
    <div class="col-md-12 table-responsive">
        <table class="table table-bordered table-hover" id="dataTable">
            <thead>
                <tr>
                    <th class="text-center vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                    <th class="vcenter" width="15%">
                        <div class="md-checkbox has-success">
                            <?php echo Form::checkbox('check_all',1,false, ['id' => 'checkAll', 'class'=> 'md-check']); ?>

                            <label for="checkAll">
                                <span class="inc"></span>
                                <span class="check mark-caheck"></span>
                                <span class="box mark-caheck"></span>
                            </label>&nbsp;&nbsp;
                            <span class="bold"><?php echo app('translator')->get('label.CHECK_ALL'); ?></span>
                        </div>
                    </th>
                    <th class="vcenter"><?php echo app('translator')->get('label.EVENT'); ?></th>
                    <th class="vcenter text-center"><?php echo app('translator')->get('label.HAS_SUB_EVENT'); ?></th>
                    <th class="vcenter"><?php echo app('translator')->get('label.ASSIGNED_TERM'); ?></th>

                </tr>
            </thead>
            <tbody>
                <?php $sl = 0 ?>
                <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $checked = '';
                $disabled = '';
                $title = '';
                $class = 'term-to-event';
                if (!empty($prevTermToEventList)) {
                    $checked = array_key_exists($target->id, $prevCourseWiseTermToEventList) ? 'checked' : '';
                    if (!empty($prevTermToEventList[$target->id]) && ($request->term_id != $prevTermToEventList[$target->id])) {
                        if ($target->has_sub_event == '0') {
                            $disabled = 'disabled';
                            $term = !empty($prevTermToEventList[$target->id]) && !empty($termList[$prevTermToEventList[$target->id]]) ? $termList[$prevTermToEventList[$target->id]] : '';
                            $title = __('label.ALREADY_ASSIGNED_TO_THIS_TERM', ['term' => $term]);
                        } 
                    }
                }

                if (!empty($hasChild)) {
                    if (array_key_exists($target->id, $hasChild)) {
                        $disabled = 'disabled';
                        $title = __('label.SUB_EVENT_OF_THE_EVENT_HAS_BEEN_ASSIGNED_TO_TERM');
                    }
                }
                ?>
                <tr>
                    <td class="text-center vcenter"><?php echo ++$sl; ?></td>
                    <td class="vcenter">
                        <div class="md-checkbox has-success tooltips" title="<?php echo $title; ?>" >
                            <?php echo Form::checkbox('event_id['.$target->id.']',$target->id, $checked, ['id' => $target->id, 'data-id'=>$target->id,'class'=> 'md-check '.$class,$disabled]); ?>


                            <?php if($disabled == 'disabled' && $target->has_sub_event == '1'): ?>
                            <?php echo Form::hidden('event_id['.$target->id.']', $target->id); ?>

                            <?php endif; ?>

                            <label for="<?php echo $target->id; ?>">
                                <span class="inc"></span>
                                <span class="check mark-caheck tooltips"></span>
                                <span class="box mark-caheck tooltips"></span>
                            </label>
                        </div>
                    </td>
                    <td class="vcenter"><?php echo $target->event_code; ?></td>
                    <td class="text-center vcenter">
                        <?php if($target->has_sub_event == '1'): ?>
                        <span class="label label-sm label-success"><?php echo app('translator')->get('label.YES'); ?></span>
                        <?php else: ?>
                        <span class="label label-sm label-warning"><?php echo app('translator')->get('label.NO'); ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if(!empty($prevDataList[$target->id])): ?>
                        <?php $__currentLoopData = $prevDataList[$target->id]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $termId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo isset($termList[$termId])?$termList[$termId]:''; ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<!-- if submit wt chack Start -->
<div class = "form-actions">
    <div class = "col-md-offset-4 col-md-8">
        <button class = "button-submit btn btn-circle green" type="button">
            <i class = "fa fa-check"></i> <?php echo app('translator')->get('label.SUBMIT'); ?>
        </button>
        <a href = "<?php echo e(URL::to('termToEvent')); ?>" class = "btn btn-circle btn-outline grey-salsa"><?php echo app('translator')->get('label.CANCEL'); ?></a>
    </div>
</div>

<?php else: ?>
<div class="col-md-12">
    <div class="alert alert-danger alert-dismissable">
        <p><i class="fa fa-bell-o fa-fw"></i><?php echo app('translator')->get('label.NO_EVENT_FOUND'); ?></p>
    </div>
</div>
<?php endif; ?>
<!-- if submit wt chack End -->
<script type="text/javascript">
//    CHECK ALL
    $(document).ready(function () {

        $('#checkAll').change(function () {  //'check all' change
            $('.term-to-event').prop('checked', $(this).prop('checked')); //change all 'checkbox' checked status
        });
        $('.term-to-event').change(function () {
            if (this.checked == false) { //if this item is unchecked
                $('#checkedAll')[0].checked = false; //change 'check all' checked status to false
            }
            //check 'check all' if all checkbox items are checked
            if ($('.term-to-event:checked').length == $('.term-to-event').length) {
                $('#checkedAll')[0].checked = true; //change 'check all' checked status to true
            }
        });

        //'check all' change
        $(document).on('click', '#checkAll', function () {
            if (this.checked) {
                $('.term-to-event').prop('checked', $(this).prop('checked')); //change all 'checkbox' checked status
            } else {
                $(".term-to-event").removeAttr('checked');
            }
        });

        $(document).on('click', '.term-to-event', function () {
            allCheck();
        });


//        $(document).on('click', '.term', function () {
//            allCheck();
//        });
<?php if (!$targetArr->isEmpty()) { ?>
            allCheck();
<?php } ?>
    });


    function allCheck() {

        if ($('.term-to-event:checked').length == $('.term-to-event').length) {
            $('#checkAll')[0].checked = true;
        } else {
            $('#checkAll')[0].checked = false;
        }
    }
//    CHECK ALL
</script>
<script src="<?php echo e(asset('public/js/custom.js')); ?>" type="text/javascript"></script><?php /**PATH E:\Xampp\htdocs\afwc\resources\views/termToEvent/getEvent.blade.php ENDPATH**/ ?>