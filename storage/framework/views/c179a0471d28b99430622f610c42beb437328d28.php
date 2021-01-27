
<div class="form-group">
    <label class="control-label col-md-4" for="subSubEventId"><?php echo app('translator')->get('label.SUB_SUB_EVENT'); ?> :<span class="text-danger"> *</span></label>
    <div class="col-md-4">
        <?php echo Form::select('sub_sub_event_id', $subSubEventList, null, ['class' => 'form-control js-source-states', 'id' => 'subSubEventId']); ?>

    </div>
</div>

<?php /**PATH E:\Xampp\htdocs\afwc\resources\views/comdtModerationMarking/getSubSubEvent.blade.php ENDPATH**/ ?>