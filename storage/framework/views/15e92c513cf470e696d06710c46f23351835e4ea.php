
<div class="form-group">
    <label class="control-label col-md-4" for="subSubSubEventId"><?php echo app('translator')->get('label.SUB_SUB_SUB_EVENT'); ?> :<span class="text-danger"> *</span></label>
    <div class="col-md-4">
        <?php echo Form::select('sub_sub_sub_event_id', $subSubSubEventList, null, ['class' => 'form-control js-source-states', 'id' => 'subSubSubEventId']); ?>

    </div>
</div>

<?php /**PATH E:\Xampp\htdocs\afwc\resources\views/comdtModerationMarking/getSubSubSubEvent.blade.php ENDPATH**/ ?>