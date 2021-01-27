<?php if(!empty($cmDataArr)): ?>
<?php if(!empty($activeTermInfo)): ?>
<div class="form-group">
    <label class="control-label col-md-4" for="termId"><?php echo app('translator')->get('label.TERM'); ?> :<span class="text-danger"> *</span></label>
    <div class="col-md-4">
        <div class="control-label pull-left"> <strong> <?php echo e($activeTermInfo->name); ?> </strong></div>
    </div>
</div>
<?php echo Form::hidden('term_id',$activeTermInfo->id,['id'=>'termId']); ?>

<?php if(sizeof($eventList) > 1): ?>
<div class="form-group">
    <label class="control-label col-md-4" for="eventId"><?php echo app('translator')->get('label.EVENT'); ?> :<span class="text-danger"> *</span></label>
    <div class="col-md-4">
        <?php echo Form::select('event_id', $eventList, null, ['class' => 'form-control js-source-states', 'id' => 'eventId']); ?>

    </div>
</div>
<?php else: ?>
<div class="row">
    <div class="col-md-offset-2 col-md-7">
        <div class="alert alert-danger alert-dismissable">
            <p><strong><i class="fa fa-bell-o fa-fw"></i> <?php echo __('label.NO_EVENT_IS_ASSIGNED_TO_THIS_TERM'); ?></strong></p>
        </div>
    </div>
</div>
<?php endif; ?>
<?php else: ?>
<div class="row">
    <div class="col-md-offset-2 col-md-7">
        <div class="alert alert-danger alert-dismissable">
            <p><strong><i class="fa fa-bell-o fa-fw"></i> <?php echo __('label.NO_ACTIVE_TERM_FOUND'); ?></strong></p>
        </div>
    </div>
</div>
<?php endif; ?>
<?php else: ?>
<div class="row">
    <div class="col-md-offset-2 col-md-7">
        <div class="alert alert-danger alert-dismissable">
            <p><strong><i class="fa fa-bell-o fa-fw"></i> <?php echo __('label.NO_CM_IS_ASSIGNED_TO_THIS_COURSE'); ?></strong></p>
        </div>
    </div>
</div>
<?php endif; ?><?php /**PATH E:\Xampp\htdocs\afwc\resources\views/comdtModerationMarking/showTermEvent.blade.php ENDPATH**/ ?>