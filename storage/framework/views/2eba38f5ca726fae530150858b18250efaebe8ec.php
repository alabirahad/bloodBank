<div class="page-bar">
    <ul class="page-breadcrumb margin-top-10">
        <li>
            <a href="<?php echo e(url('dashboard')); ?>"><?php echo app('translator')->get('label.HOME'); ?></a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span><?php echo app('translator')->get('label.DASHBOARD'); ?></span>
        </li>
    </ul>
    <div class="page-toolbar margin-top-15">
        <h5 class="dashboard-date font-green-sharp"><span class="icon-calendar"></span> <?php echo app('translator')->get('label.TODAY_IS'); ?> <span class="font-green-sharp"><?php echo date('d F Y'); ?></span> </h5>   
    </div>
</div>
<?php /**PATH E:\Xampp\htdocs\afwc\resources\views/admin/commonFeatures/all.blade.php ENDPATH**/ ?>