<div class="form-group">
    <label class="control-label col-md-4" for="subEventId"><?php echo app('translator')->get('label.SUB_EVENT'); ?> :<span class="text-danger"> *</span></label>
    <div class="col-md-4">
        <?php echo Form::select('sub_event_id', $subEventList, null, ['class' => 'form-control js-source-states', 'id' => 'subEventId']); ?>

    </div>
</div><?php /**PATH E:\Xampp\htdocs\afwc\resources\views/eventToApptMatrix/showSubEvent.blade.php ENDPATH**/ ?>