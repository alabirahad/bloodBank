
<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i>Home
            </div>
        </div>
        <div class="portlet-body">
            <div class="row margin-top-20">
                <div class="col-md-12">
                    <div class="row term-block-blue-hoki dashboard-stat2">
                        <?php if(!$requestBloodArr->isEmpty()): ?>
                        <div class="col-md-12 margin-top-10">
                            <span class="label label-success">
                                <span>No. of Request: <strong><?php echo !empty($requestBloodArr) ? sizeof($requestBloodArr) : '0'; ?></strong></span>
                            </span>&nbsp;
                        </div>
                        <div class="col-md-12 margin-top-10">
                            <table class="table table-bordered table-hover" id="dataTable">
                                <thead>
                                    <tr>
                                        <th class="vcenter text-center"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                                        <th class="text-center vcenter">Blood Group</th>
                                        <th class="text-center vcenter">Quantity</th>
                                        <th class="text-center vcenter">Place</th>
                                        <th class="text-center vcenter">Require Date</th>
                                        <th class="text-center vcenter">Status</th>
                                        <th class="text-center vcenter">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php $sl = 0; ?>
                                    <?php $__currentLoopData = $requestBloodArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="vcenter text-center"><?php echo ++$sl; ?></td>
                                        <td class="text-center vcenter"><?php echo $target->blood; ?></td>
                                        <td class="text-center vcenter"><?php echo $target->quantity; ?> bag</td>
                                        <td class="text-center vcenter"><?php echo $target->division; ?></td>
                                        <td class="text-center vcenter"><?php echo $target->date; ?></td>
                                        <td class="vcenter text-center">
                                            <?php if($target->status == '0'): ?>
                                            <span class="label label-sm label-blue-hoki">REQUESTED</span>
                                            <?php elseif($target->status == '1'): ?>
                                            <span class="label label-sm label-purple-sharp">PENDING</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="td-actions text-center vcenter">
                                            <div class="width-inherit">
                                                <?php if($target->status == '0'): ?>
                                                <button class="btn btn-xs btn-success tooltips vcenter" id="accepetRequest"
                                                        title="Request Accept" type="button" data-id="<?php echo !empty($target->id) ? $target->id : 0; ?>">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                <?php endif; ?>
                                                
                                                <?php if($target->status == '1'): ?>
                                                <button class="btn btn-xs btn-warning tooltips vcenter" id="pendingRequest"
                                                        title="Pending" type="button" data-id="<?php echo !empty($target->id) ? $target->id : 0; ?>">
                                                    <i class="fa fa-hourglass-start"></i>
                                                </button>
                                                <?php endif; ?>
                                                
                                                <button class="btn btn-xs btn-danger tooltips vcenter" id="cancelRequest"
                                                        title="Cancel" type="button" data-id="<?php echo !empty($target->id) ? $target->id : 0; ?>">
                                                    <i class="fa fa-close"></i>
                                                </button>
                                                <button class="btn btn-xs btn-success tooltips vcenter" id="donetRequest"
                                                        title="Donate" type="button" data-id="<?php echo !empty($target->id) ? $target->id : 0; ?>">
                                                    <i class="fa fa-thumbs-up"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissable">
                                <p><strong><i class="fa fa-bell-o fa-fw"></i> No Blood Request</strong></p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>	
    </div>
</div>

<script type="text/javascript">
    $(function () {
        var options = {
            closeButton: true,
            debug: false,
            positionClass: "toast-bottom-right",
            onclick: null,
        };

        
        $(document).on("click", "#accepetRequest", function (e) {
            e.preventDefault();
            var requestId = $(this).attr('data-id');
            $.ajax({
                url: "<?php echo e(URL::to('homePage/requestAccepet')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    request_id: requestId,
                },
                success: function (res) {
                    toastr.success(res, res.message, options);
                    var delay = 1000;
                    var url = 'homePage'
                    setTimeout(function () {
                        window.location = url;
                    }, delay);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });
        
        $(document).on("click", "#cancelRequest", function (e) {
            e.preventDefault();
            var requestId = $(this).attr('data-id');
            $.ajax({
                url: "<?php echo e(URL::to('homePage/requestCancel')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    request_id: requestId,
                },
                success: function (res) {
                    toastr.success(res, res.message, options);
                    var delay = 1000;
                    var url = 'homePage'
                    setTimeout(function () {
                        window.location = url;
                    }, delay);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });
        
        $(document).on("click", "#donetRequest", function (e) {
            e.preventDefault();
            var requestId = $(this).attr('data-id');
            $.ajax({
                url: "<?php echo e(URL::to('homePage/requestDonet')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    request_id: requestId,
                },
                success: function (res) {
                    toastr.success(res, res.message, options);
                    var delay = 1000;
                    var url = 'homePage'
                    setTimeout(function () {
                        window.location = url;
                    }, delay);
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            }); //ajax
        });
        

    });

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Xampp\htdocs\bloodBank\resources\views/homePage/index.blade.php ENDPATH**/ ?>