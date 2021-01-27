
<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-line-chart"></i><?php echo app('translator')->get('label.NOMINAL_ROLL'); ?>
            </div>
        </div>
        <div class="portlet-body">
            <!-- Begin Filter-->
            <?php echo Form::open(array('group' => 'form', 'url' => 'nominalRollReport/filter','class' => 'form-horizontal')); ?>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="trainingYearId"><?php echo app('translator')->get('label.TRAINING_YEAR'); ?> <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <?php echo Form::select('training_year_id', $activeTrainingYearList,  Request::get('training_year_id'), ['class' => 'form-control js-source-states', 'id' => 'trainingYearId']); ?>

                            <span class="text-danger"><?php echo e($errors->first('training_year_id')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="courseId"><?php echo app('translator')->get('label.COURSE'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('course_id', $courseList,  Request::get('course_id'), ['class' => 'form-control js-source-states', 'id' => 'courseId']); ?>

                            <span class="text-danger"><?php echo e($errors->first('course_id')); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="termId"><?php echo app('translator')->get('label.TERM'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('term_id', $termList,  Request::get('term_id'), ['class' => 'form-control js-source-states', 'id' => 'termId']); ?>

                            <span class="text-danger"><?php echo e($errors->first('term_id')); ?></span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="form-group">
                        <!--<label class="control-label col-md-4" for="">&nbsp;</label>-->
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-md green btn-outline filter-btn" id="modeController" value="Show Filter Info" data-mode="1">
                                <i class="fa fa-search"></i> <?php echo app('translator')->get('label.GENERATE'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>

            <!--filter form close-->

            <?php if($request->generate == 'true'): ?>
            <?php if(!$targetArr->isEmpty()): ?>
            <div class="row">
                <div class="col-md-12 text-right">
                    <a class="btn btn-md btn-primary vcenter" target="_blank"  href="<?php echo URL::full().'&view=print'; ?>">
                        <span class="tooltips" title="<?php echo app('translator')->get('label.PRINT'); ?>"><i class="fa fa-print"></i> </span> 
                    </a>
                    <a class="btn btn-success vcenter" href="<?php echo URL::full().'&view=pdf'; ?>">
                        <span class="tooltips" title="<?php echo app('translator')->get('label.DOWNLOAD_PDF'); ?>"><i class="fa fa-file-pdf-o"></i></span>
                    </a>
                    <a class="btn btn-warning vcenter" href="<?php echo URL::full().'&view=excel'; ?>">
                        <span class="tooltips" title="<?php echo app('translator')->get('label.DOWNLOAD_EXCEL'); ?>"><i class="fa fa-file-excel-o"></i> </span>
                    </a>
                </div>
            </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="bg-blue-hoki bg-font-blue-hoki">
                        <h5 style="padding: 10px;">
                            <?php echo e(__('label.TRAINING_YEAR')); ?> : <strong><?php echo e(!empty($activeTrainingYearList[Request::get('training_year_id')]) && Request::get('training_year_id') != 0 ? $activeTrainingYearList[Request::get('training_year_id')] : __('label.N_A')); ?> |</strong>
                            <?php echo e(__('label.COURSE')); ?> : <strong><?php echo e(!empty($courseList[Request::get('course_id')]) && Request::get('course_id') != 0 ? $courseList[Request::get('course_id')] : __('label.ALL')); ?> |</strong>
                            <?php echo e(__('label.TERM')); ?> : <strong><?php echo e(!empty($termList[Request::get('term_id')]) && Request::get('term_id') != 0 ? $termList[Request::get('term_id')] : __('label.ALL')); ?> |</strong>
                            <?php if(Auth::user()->group_id != 4): ?>
                            <?php if(Request::get('term_id') == 0): ?>
                            <?php echo e(__('label.SYN')); ?> : <strong><?php echo e(!empty($synList[Request::get('syn_id')]) && Request::get('syn_id') != 0 ? $synList[Request::get('syn_id')] : __('label.ALL')); ?> |</strong>
                            <?php endif; ?>
                            <?php endif; ?>
                            <?php echo e(__('label.TOTAL_NO_OF_CM')); ?> : <strong><?php echo e(!empty($targetArr) ? sizeof($targetArr) : 0); ?></strong>

                        </h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <div class="max-height-500 webkit-scrollbar">
                        <table class="table table-bordered table-hover table-head-fixer-color" id="dataTable">
                            <thead>
                                <tr>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.SERIAL'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.PERSONAL_NO'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.RANK'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.FULL_NAME'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.OFFICIAL_NAME'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.PHOTO'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.ARMS_SERVICE'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.COMMISSIONING_COURSE'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.EMAIL'); ?></th>
                                    <th class="vcenter"><?php echo app('translator')->get('label.MOBILE'); ?></th>
                                    <th class="vcenter text-center"><?php echo app('translator')->get('label.ACTION'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!$targetArr->isEmpty()): ?>
                                <?php
                                $sl = 0;
                                $synId = null;
                                $subSynId = null;
                                ?>
                                <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $synCondition = !empty(Request::get('term_id')) ? 1 : 0;

                                $synName = '';
                                if ($synCondition != 0) {
                                    if ($target->sub_syn_id != $subSynId) {
                                        $synId = $target->syn_id;
                                        $subSynId = $target->sub_syn_id;
                                        $synName = (!empty($synList[$target->syn_id]) ? $synList[$target->syn_id] : '') . (!empty($subSynList[$target->sub_syn_id]) ? ' ('.$subSynList[$target->sub_syn_id] .')' : '');
                                        ?>
                                        <tr class="bg-grey-steel">
                                            <th colspan="11" class="text-center"><?php echo $synName; ?></th>
                                        </tr>
                                        <?php
                                    } elseif ($target->syn_id != $synId) {
                                        $synId = $target->syn_id;
                                        $synName = !empty($synList[$target->syn_id]) ? $synList[$target->syn_id] : '';
                                        ?>
                                        <tr class="bg-grey-steel">
                                            <th colspan="11" class="text-center"><?php echo $synName; ?></th>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                <tr>
                                    <td class="vcenter text-center"><?php echo ++$sl; ?></td>
                                    <td class="vcenter"><?php echo !empty($target->personal_no) ? $target->personal_no:''; ?></td>
                                    <td class="vcenter"><?php echo $target->rank?? ''; ?></td>
                                    <td class="vcenter"><?php echo $target->full_name??''; ?></td>
                                    <td class="vcenter"><?php echo $target->official_name??''; ?></td>
                                    <td class="vcenter text-center" width="50px">
                                        <?php if(!empty($target->photo) && File::exists('public/uploads/cm/' . $target->photo)): ?>
                                        <img width="50" height="60" src="<?php echo e(URL::to('/')); ?>/public/uploads/cm/<?php echo e($target->photo); ?>" alt="<?php echo e($target->official_name?? ''); ?>"/>
                                        <?php else: ?>
                                        <img width="50" height="60" src="<?php echo e(URL::to('/')); ?>/public/img/unknown.png" alt="<?php echo e($target->official_name?? ''); ?>"/>
                                        <?php endif; ?>
                                    </td>
                                    <td class="vcenter"><?php echo $target->arms_service_name ?? ''; ?></td>
                                    <td class="vcenter"><?php echo $target->comm_course_name ?? ''; ?></td>
                                    <td class="vcenter"><?php echo $target->email ?? ''; ?></td>
                                    <td class="vcenter"><?php echo $target->number ?? ''; ?></td>
                                    <td class="td-actions text-center vcenter">
                                        <div class="width-inherit">
                                            <a class="btn btn-xs green-seagreen tooltips vcenter" title="<?php echo app('translator')->get('label.CLICK_HERE_TO_VIEW_PROFILE'); ?>" href="<?php echo URL::to('nominalRollReport/' . $target->id . '/profile'.Helper::queryPageStr($qpArr)); ?>">
                                                <i class="fa fa-user"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="11"><?php echo app('translator')->get('label.NO_CM_FOUND'); ?></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<script type="text/javascript">

    $(function () {
        //table header fix
        $(".table-head-fixer-color").tableHeadFixer('');
        //Start::Get Course
        $(document).on("change", "#trainingYearId", function () {
            var trainingYearId = $("#trainingYearId").val();
            if (trainingYearId == '0') {
                $("#courseId").html("<option value='0'><?php echo app('translator')->get('label.SELECT_COURSE_OPT'); ?></option>");
                $("#termId").html("<option value='0'><?php echo app('translator')->get('label.SELECT_TERM_OPT'); ?></option>");
                return false;
            }
            $.ajax({
                url: "<?php echo e(URL::to('nominalRollReport/getCourse')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    training_year_id: trainingYearId
                },
                beforeSend: function () {
                    $("#termId").html("<option value='0'><?php echo app('translator')->get('label.SELECT_TERM_OPT'); ?></option>");
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#courseId').html(res.html);
                    $(".js-source-states").select2();
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            });//ajax

        });
        //End::Get Course
        //Start::Get Term
        $(document).on("change", "#courseId", function () {


            var courseId = $("#courseId").val();
            if (courseId == '0') {
                $("#termId").html("<option value='0'><?php echo app('translator')->get('label.SELECT_TERM_OPT'); ?></option>");
                return false;
            }

            $.ajax({
                url: "<?php echo e(URL::to('nominalRollReport/getTerm')); ?>",
                type: "POST",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    course_id: courseId
                },
                beforeSend: function () {
                    App.blockUI({boxed: true});
                },
                success: function (res) {
                    $('#termId').html(res.html);
                    $(".js-source-states").select2();
                    App.unblockUI();
                },
                error: function (jqXhr, ajaxOptions, thrownError) {
                }
            });//ajax

        });
        //End::Get Term


    });

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Xampp\htdocs\afwc\resources\views/report/nominalRoll/index.blade.php ENDPATH**/ ?>