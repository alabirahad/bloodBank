<?php if(!empty($activeTermInfo)): ?>

<div class="form-group">
    <label class="control-label col-md-4" for="termId"><?php echo app('translator')->get('label.TERM'); ?> :<span class="text-danger"> *</span></label>
    <div class="col-md-8">
        <div class="control-label pull-left"> <strong> <?php echo e($activeTermInfo->name); ?> </strong></div>
    </div>
    <?php echo Form::hidden('term_id', $activeTermInfo->id, ['id' => 'termId']); ?>

</div> 

<?php if(sizeof($eventList) > 1): ?>
<div class="form-group">
    <label class="control-label col-md-4" for="eventId"><?php echo app('translator')->get('label.EVENT'); ?> :<span class="text-danger"> *</span></label>
    <div class="col-md-4">
        <?php echo Form::select('event_id', $eventList, null, ['class' => 'form-control js-source-states', 'id' => 'eventId']); ?>

    </div>
</div>

<?php else: ?>
<div class="col-md-12">
    <div class="alert alert-danger alert-dismissable">
        <p><i class="fa fa-bell-o fa-fw"></i><?php echo app('translator')->get('label.NO_ACTIVE_EVENT_FOUND'); ?></p>
    </div>
</div>
<?php endif; ?>

<?php else: ?>
<div class="col-md-12">
    <div class="alert alert-danger alert-dismissable">
        <p><i class="fa fa-bell-o fa-fw"></i><?php echo app('translator')->get('label.NO_ACTIVE_TERM_FOUND'); ?></p>
    </div>
</div>
<?php endif; ?><?php /**PATH E:\Xampp\htdocs\afwc\resources\views/apptToCm/showTermEvent.blade.php ENDPATH**/ ?>