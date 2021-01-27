
<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-calendar"></i><?php echo app('translator')->get('label.COURSE_ID_LIST'); ?>
            </div>
            <div class="actions">
                <a class="btn btn-default btn-sm create-new"
                    href="<?php echo e(URL::to('courseId/create'.Helper::queryPageStr($qpArr))); ?>">
                    <?php echo app('translator')->get('label.CREATE_NEW_COURSE_ID'); ?>
                    <i class="fa fa-plus create-new"></i>
                </a>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <!-- Begin Filter-->
                <?php echo Form::open(array('group' => 'form', 'url' => 'courseId/filter','class' => 'form-horizontal')); ?>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="filSearch"><?php echo app('translator')->get('label.SEARCH'); ?></label>
                            <div class="col-md-8">
                                <?php echo Form::text('fil_search', Request::get('fil_search'), ['class' => 'form-control
                                tooltips', 'id' => 'filSearch', 'title' => 'Name', 'placeholder' => 'Name',
                                'list' => 'courseIdName', 'autocomplete' => 'off']); ?>

                                <datalist id="courseIdName">
                                    <?php if(!$nameArr->isEmpty()): ?>
                                    <?php $__currentLoopData = $nameArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item->name); ?>" />
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </datalist>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form">
                            <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
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
                            <th class="vcenter"><?php echo app('translator')->get('label.TRAINING_YEAR'); ?></th>
                            <th class="vcenter"><?php echo app('translator')->get('label.COURSE_ID'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.TENURE'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.NO_OF_WEEKS'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.SHORT_INFO'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.TOTAL_COURSE_WT'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.EVENT_MKS_LIMIT'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.HIGHEST_MKS_LIMIT'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.LOWEST_MKS_LIMIT'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.STATUS'); ?></th>
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
                            <td class="vcenter"><?php echo e($target->tranining_year_name); ?></td>
                            <td class="vcenter"><?php echo e($target->name); ?></td>
                            <td class="text-center vcenter">
                                <?php echo e(Helper::printDate($target->initial_date) .' '. __('label.TO') .' '.  Helper::printDate($target->termination_date)); ?>

                            </td>
                            <td class="text-center vcenter"><?php echo e($target->no_of_weeks); ?></td>
                            <td class="text-center vcenter"><?php echo e($target->short_info); ?></td>
                            <td class="text-center vcenter"><?php echo e($target->total_course_wt); ?></td>
                            <td class="text-center vcenter"><?php echo e($target->event_mks_limit); ?></td>
                            <td class="text-center vcenter"><?php echo e($target->highest_mks_limit); ?></td>
                            <td class="text-center vcenter"><?php echo e($target->lowest_mks_limit); ?></td>
                            <td class="text-center vcenter">
                                <?php if($target->status == '1'): ?>
                                <span class="label label-sm label-success"><?php echo app('translator')->get('label.ACTIVE'); ?></span>
                                <?php elseif($target->status == '0'): ?>
                                <span class="label label-sm label-info"><?php echo app('translator')->get('label.INACTIVE'); ?></span>
                                <?php else: ?>
                                <span class="label label-sm label-warning"><?php echo app('translator')->get('label.CLOSED'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="td-actions text-center vcenter">
                                <div class="width-inherit">
                                    <?php if($target->status !='2' ): ?>
                                    <?php echo e(Form::open(array('url' => 'courseId/' . $target->id.'/'.Helper::queryPageStr($qpArr)))); ?>

                                    <?php echo e(Form::hidden('_method', 'DELETE')); ?>

                                    <a class="btn btn-xs btn-primary tooltips" title="Edit"
                                        href="<?php echo e(URL::to('courseId/' . $target->id . '/edit'.Helper::queryPageStr($qpArr))); ?>">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button class="btn btn-xs btn-danger delete tooltips" title="Delete"
                                        type="submit" data-placement="top" data-rel="tooltip"
                                        data-original-title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <?php echo e(Form::close()); ?>

                                    <?php if($target->status =='1'): ?>

<!--                                    <button class="btn btn-xs btn-warning close-btn tooltips"
                                        title="<?php echo app('translator')->get('label.CLOSE_THIS_COURSE'); ?>" type=" button" data-placement="top"
                                        data-rel="tooltip" data-id="<?php echo $target->id; ?>"
                                        data-original-title="<?php echo app('translator')->get('label.CLOSE_THIS_COURSE'); ?>">
                                        <i class="fa fa-close"></i>
                                    </button>-->
                                    <?php endif; ?>
                                    <?php endif; ?>

                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="12" class="vcenter"><?php echo app('translator')->get('label.NO_COURSE_ID_FOUND'); ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
    <?php echo $__env->make('layouts.paginator', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(function () {
        $(document).on('click', '.close-btn', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var options = {
                closeButton: true,
                debug: false,
                positionClass: "toast-bottom-right",
                onclick: null,
            };

            swal({
                title: 'Are you sure,You want to Close?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, Close',
                cancelButtonText: 'No, Cancel',
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "<?php echo e(URL::to('courseId/close')); ?>",
                        type: "POST",
                        datatype: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            id: id,
                        },

                        success: function (res) {
                            toastr.success(res.message, 'Success', options);
                            //setTimeout(location.reload.bind(location), 1000);
                        },

                        error: function (jqXhr, ajaxOptions, thrownError) {
                            if (jqXhr.status == 400) {
                                var errorsHtml = '';
                                var errors = jqXhr.responseJSON.message;
                                $.each(errors, function (key, value) {
                                    errorsHtml += '<li>' + value + '</li>';
                                });
                                toastr.error(errorsHtml, jqXhr.responseJSON.heading, options);
                            } else if (jqXhr.status == 401) {
                               //toastr.error(jqXhr.responseJSON.message, '', options);
                                var errors = jqXhr.responseJSON.message;
                                var errorsHtml = 'SI Impr Mks have not been Locked for following Wing :';
                                if (typeof (errors) === 'object') {
                                    $.each(errors, function (key, value) {
                                        errorsHtml += '<li>' + value + '</li>';
                                    });
                                    toastr.error(errorsHtml, '', options);
                                } else {
                                    toastr.error(jqXhr.responseJSON.message, '', options);
                                }
                            } else {
                                toastr.error('Error', 'Something went wrong', options);
                            }
                            App.unblockUI();
                        }
                    });
                }
            });
        });
    });
</script>









































































<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Xampp\htdocs\afwc\resources\views/courseId/index.blade.php ENDPATH**/ ?>