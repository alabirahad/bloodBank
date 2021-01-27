<?php if(!empty($courseInfo)): ?>
<div class="row margin-top-10">
    <div class="col-md-12">
        <?php $__currentLoopData = $courseInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $courseData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(!empty($termInfo)): ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 course-block">
            <h1 class="page-title text-center"><?php echo app('translator')->get('label.COURSE'); ?>: <?php echo e(!empty($courseData->name) ? $courseData->name : ''); ?></h1>
            <div class="text-center">
                <span class="font-blue-oleo bold font-size-11">
                    <?php echo e(Helper::formatDate($courseData->initial_date)); ?> - <?php echo e(Helper::formatDate($courseData->termination_date)); ?> (No. of Week: <?php echo e($courseData->no_of_weeks); ?>)  
                </span>
            </div>
            
            <div class="row margin-top-60">
                <?php $__currentLoopData = $termInfo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $termData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $class = 'default';
                $label = __('label.NOT_INITIATED');
//                $percent = $info['percent'];
                if ($termData->status == '0') {
                    $class = 'gray-mint';
                    $label = __('label.NOT_INITIATED');
                } else if ($termData->status == '1') {
                    if ($termData->active == '0') {
                        $class = 'blue-hoki';
                        $label = __('label.INITIATED');
                    } else if ($termData->active == '1') {
                        $class = 'green-sharp';
                        $label = __('label.ACTIVE');
                    }
                } else if ($termData->status == '2') {
                    $class = 'red-haze';
                    $label = __('label.CLOSED');
                }
                ?>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 tooltips" data-html=true>
                    <div class="dashboard-stat2 term-block term-block-<?php echo e($class); ?>">
                        <div class="display">
                            <div class="number">
                                <h4 class="font-<?php echo e($class); ?> bold">
                                    <span><?php echo e($termData->name); ?></span>
                                </h4>
                                <span class="font-blue-oleo bold font-size-11">
                                    <?php echo e(Helper::formatDate($termData->initial_date)); ?> - <?php echo e(Helper::formatDate($termData->termination_date)); ?>

                                </span>
                            </div>
                        </div>
                        <div class="progress-info">
                            <div class="icon  bold text-right">
                                <i class="icon-pie-chart font-<?php echo e($class); ?> font-size-25"></i>
                            </div>
                            <div class="progress" style="background-color:white;" >
                                <span style="" class="progress-bar progress-bar-success <?php echo e($class); ?>  bg-font-blue-oleo">
                                    <span class="sr-only">% progress</span>
                                </span>
                            </div>
                            <div class="status">
                                <div class="status-title font-<?php echo e($class); ?>"><?php echo e($label); ?></div>
                                <div class="status-number font-<?php echo e($class); ?>">%</div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>
<script type="text/javascript" src="<?php echo e(asset('public/js/apexcharts.min.js')); ?>"></script><?php /**PATH E:\Xampp\htdocs\afwc\resources\views/admin/commonFeatures/courseBlock.blade.php ENDPATH**/ ?>