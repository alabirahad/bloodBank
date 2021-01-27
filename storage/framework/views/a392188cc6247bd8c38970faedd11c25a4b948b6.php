
<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i><?php echo app('translator')->get('label.USER_LIST'); ?>
            </div>
            <div class="actions">
                <a class="btn btn-default btn-sm create-new" href="<?php echo e(URL::to('user/create'.Helper::queryPageStr($qpArr))); ?>"> <?php echo app('translator')->get('label.CREATE_NEW_USER'); ?>
                    <i class="fa fa-plus create-new"></i>
                </a>
            </div>
        </div>
        <div class="portlet-body">
            <div id="filterOpt">
                <!-- Begin Filter-->
                <?php echo Form::open(array('group' => 'form', 'url' => 'user/filter','class' => 'form-horizontal')); ?>


                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="filSearch"><?php echo app('translator')->get('label.SEARCH'); ?></label>
                            <div class="col-md-8">
                                <?php echo Form::text('fil_search',  Request::get('fil_search'), ['class' => 'form-control tooltips', 'id' => 'filSearch', 'title' => 'Name', 'placeholder' => 'Name', 'list' => 'userName', 'autocomplete' => 'off']); ?> 
                                <datalist id="userName">
                                    <?php if(!$nameArr->isEmpty()): ?>
                                    <?php $__currentLoopData = $nameArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item->full_name); ?>" />
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </datalist>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="groupId"><?php echo app('translator')->get('label.USER_GROUP'); ?></label>
                            <div class="col-md-8">
                                <?php echo Form::select('fil_group_id', $groupList,  Request::get('fil_group_id'), ['class' => 'form-control js-source-states', 'id' => 'groupId']); ?>

                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 text-center">
                        <div class="form">
                            <button type="submit" class="btn btn-md green-seagreen btn-outline filter-submit margin-bottom-20 filter-btn">
                                <i class="fa fa-search"></i> <?php echo app('translator')->get('label.FILTER'); ?>
                            </button>
                        </div>
                    </div>
                </div>
                <?php echo Form::close(); ?>

                <!-- End Filter -->
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.USER_GROUP'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.NAME'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.USERNAME'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.PHOTO'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.EMAIL'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.PHONE'); ?></th>
                            <th class="td-actions text-center vcenter"><?php echo app('translator')->get('label.ACTION'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!$targetArr->isEmpty()): ?>
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="text-center vcenter"><?php echo e(++$sl); ?></td>
                            <td class="vcenter"><?php echo $target->group_name; ?></td>
                            <td class="vcenter"><?php echo $target->full_name; ?></td>
                            <td class="vcenter"><?php echo $target->username; ?></td>
                            <td class="text-center vcenter" width="50px">
                                <?php if (!empty($target->photo) && File::exists('public/uploads/user/'.$target->photo)) { ?>
                                    <img width="50" height="60" src="<?php echo e(URL::to('/')); ?>/public/uploads/user/<?php echo e($target->photo); ?>" alt="<?php echo e($target->full_name); ?>"/>
                                <?php } else { ?>
                                    <img width="50" height="60" src="<?php echo e(URL::to('/')); ?>/public/img/unknown.png" alt="<?php echo e($target->full_name); ?>"/>
                                <?php } ?>
                            </td>
                            <td class="vcenter"><?php echo $target->email; ?></td>
                            <td class="vcenter"><?php echo $target->phone; ?></td>
                            <td class="td-actions text-center vcenter">
                                <div class="width-inherit">
                                    <?php echo Form::open(array('url' => 'user/' . $target->id.'/'.Helper::queryPageStr($qpArr))); ?>

                                    <?php echo Form::hidden('_method', 'DELETE'); ?>


                                    <a class="btn btn-xs btn-primary tooltips vcenter" title="Edit" href="<?php echo URL::to('user/' . $target->id . '/edit'.Helper::queryPageStr($qpArr)); ?>">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    <button class="btn btn-xs btn-danger delete tooltips vcenter" title="Delete" type="submit" data-placement="top" data-rel="tooltip" data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>

                                    <?php echo Form::close(); ?>

                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="13" class="vcenter"><?php echo app('translator')->get('label.NO_USER_FOUND'); ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo $__env->make('layouts.paginator', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>	
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Xampp\htdocs\bloodBank\resources\views/user/index.blade.php ENDPATH**/ ?>