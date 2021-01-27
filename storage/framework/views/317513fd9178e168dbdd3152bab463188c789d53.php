<?php echo Form::select('event_id[]', $eventList, [], ['multiple'=>'multiple', 'class' => 'form-control mt-multiselect', 'id' => 'eventId']); ?>

<span class="text-danger"><?php echo e($errors->first('event_id')); ?></span>

<script src="<?php echo e(asset('public/js/custom.js')); ?>"></script>   
<script>

    $(function () {

        //Start:: Multiselect decorations
        var eventAllSelected = false;
        $('#eventId').multiselect({
            numberDisplayed: 0,
            includeSelectAllOption: true,
            buttonWidth: '100%',
            maxHeight: 250,
            nonSelectedText: "<?php echo app('translator')->get('label.EVENT_OPT'); ?>",
            //        enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            onSelectAll: function () {
                eventAllSelected = true;
            },
            onChange: function () {
                eventAllSelected = false;
            }
        });
    });
    //End:: Multiselect decorations
</script><?php /**PATH E:\Xampp\htdocs\afwc\resources\views/report/trend/armsServiceTrend/getEvent.blade.php ENDPATH**/ ?>