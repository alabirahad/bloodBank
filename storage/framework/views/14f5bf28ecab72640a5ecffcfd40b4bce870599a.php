
<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i><?php echo app('translator')->get('label.CM_PROFILE'); ?>
            </div>
            <div class="actions">
                <a href="<?php echo e(URL::to('/nominalRollReport'.Helper::queryPageStr($qpArr))); ?>" class="btn btn-sm blue-dark">
                    <i class="fa fa-reply"></i>&nbsp;<?php echo app('translator')->get('label.CLICK_TO_GO_BACK'); ?>
                </a>
                <a class="btn btn-md btn-primary vcenter" target="_blank"  href="<?php echo URL::full().'&view=print'; ?>">
                    <i class="fa fa-print"></i>  <?php echo app('translator')->get('label.PRINT'); ?>
                </a>
            </div>
        </div>
        <div class="portlet-body">
            <!-- START:: User Basic Info -->
            <div class="row">
                <!-- START::User Image -->
                <div class="col-md-2 text-center">
                    <!-- SIDEBAR USER TITLE -->
                    <div class="profile-userpic">
                        <?php if(!empty($cmInfoData->photo) && File::exists('public/uploads/cm/' . $cmInfoData->photo)): ?>
                        <img src="<?php echo e(URL::to('/')); ?>/public/uploads/cm/<?php echo e($cmInfoData->photo); ?>" class="text-center img-responsive pic-bordered border-default recruit-profile-photo-full"
                             alt="<?php echo e(!empty($cmInfoData->cm_name)? $cmInfoData->cm_name:''); ?>" style="width: 100%;height: 100%;" />
                        <?php else: ?> 
                        <img src="<?php echo e(URL::to('/')); ?>/public/img/unknown.png" class="text-center img-responsive pic-bordered border border-default recruit-profile-photo-full"
                             alt="<?php echo e(!empty($cmInfoData->cm_name)? $cmInfoData->cm_name:''); ?>"  style="width: 100%;height: 100%;" />
                        <?php endif; ?>
                    </div>
                    <div class="profile-usertitle">
                        <div class="text-center margin-bottom-10">
                            <b><?php echo e(!empty($cmInfoData->cm_name)? $cmInfoData->cm_name:''); ?></b>
                        </div>
                        <?php
                        $labelColorPN = 'grey-mint';
                        $fontColorPN = 'blue-hoki';

                        if ($cmInfoData->wing_id == 1) {
                            $labelColorPN = 'green-seagreen';
                        } elseif ($cmInfoData->wing_id == 2) {
                            $labelColorPN = 'white';
                            $fontColorPN = 'white';
                        } elseif ($cmInfoData->wing_id == 3) {
                            $labelColorPN = 'blue-madison';
                        }
                        ?>
                        <div class="bold label label-square label-sm font-size-11 label-<?php echo e($labelColorPN); ?>">
                            <span class="bg-font-<?php echo e($fontColorPN); ?>"><?php echo e(!empty($cmInfoData->personal_no)? $cmInfoData->personal_no:''); ?></span>
                        </div>
                    </div>
                </div>
                <!-- END::User Image -->
                <div class="col-md-10">
                    <!--<div class="column sortable ">-->
                    <div class="portlet portlet-sortable box green-color-style">
                        <div class="portlet-title ui-sortable-handle">
                            <div class="caption">
                                <i class="fa fa-info-circle green-color-style-color"></i><?php echo app('translator')->get('label.BASIC_INFORMATION'); ?>
                            </div>
                        </div>
                        <div class="portlet-body" style="padding: 8px !important">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr >
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.COURSE'); ?></td>
                                        <td><?php echo e($cmInfoData->course_name); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.WING'); ?></td>
                                        <td> <?php echo e(!empty($cmInfoData->wing_name) ? $cmInfoData->wing_name: ''); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.COMMISSIONING_COURSE'); ?></td>
                                        <td><?php echo e($cmInfoData->commissioning_course_name); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.ARMS_SERVICES'); ?></td>
                                        <td> <?php echo e(!empty($cmInfoData->arms_service_name) ? $cmInfoData->arms_service_name: ''); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.TYPE_OF_COMMISSION'); ?></td>
                                        <td><?php echo e(!empty($commissionTypeList[$cmInfoData->commission_type]) ? $commissionTypeList[$cmInfoData->commission_type] : ''); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.EMAIL'); ?></td>
                                        <td><?php echo e(!empty($cmInfoData->email) ? $cmInfoData->email: ''); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.COMMISSIONING_DATE'); ?></td>
                                        <td><?php echo e(isset($cmInfoData->commisioning_date) ? Helper::formatDate($cmInfoData->commisioning_date): ''); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.PHONE'); ?></td>
                                        <td><?php echo e(!empty($cmInfoData->number) ? $cmInfoData->number: ''); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.ANTI_DATE_SENIORITY'); ?></td>
                                        <td><?php echo e(!empty($cmInfoData->anti_date_seniority) ? $cmInfoData->anti_date_seniority: __('label.N_A')); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.BLOOD_GROUP'); ?></td>
                                        <td><?php echo e(!empty($bloodGroupList[$cmInfoData->blood_group]) ? $bloodGroupList[$cmInfoData->blood_group]: ''); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.DATE_OF_BIRTH'); ?></td>
                                        <td class="vcenter"><?php echo e(!empty($cmInfoData->date_of_birth) ? Helper::formatDate($cmInfoData->date_of_birth) : ''); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.RELIGION'); ?></td>
                                        <td><?php echo e(!empty($cmInfoData->religion_name) ? $cmInfoData->religion_name: ''); ?></td>
                                    </tr>

                                    <tr>
                                        <?php
                                        $maritalStatus = (!empty($maritalStatusList) && ($cmInfoData->marital_status != '0') && isset($maritalStatusList[$cmInfoData->marital_status])) ? $maritalStatusList[$cmInfoData->marital_status] : __("label.N_A");
                                        ?>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.BIRTH_PLACE'); ?></td>
                                        <td><?php echo e(!empty($cmInfoData->birth_place) ? $cmInfoData->birth_place: ''); ?></td>
                                        <td class = "vcenter fit bold info"><?php echo app('translator')->get('label.MARITIAL_STATUS'); ?></td>
                                        <td> <?php echo e($maritalStatus); ?> </td>
                                    </tr>

                                    <tr>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.EMAIL'); ?></td>
                                        <td class="vcenter"><?php echo e(!empty($cmInfoData->email) ? $cmInfoData->email: ''); ?></td> 
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.PHONE'); ?></td>
                                        <td class="vcenter"><?php echo e(!empty($cmInfoData->number) ? $cmInfoData->number: ''); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.FATHERS_NAME'); ?></td>
                                        <td class="vcenter" colspan="3"><?php echo e(!empty($cmInfoData->father_name) ? $cmInfoData->father_name: ''); ?></td> 
                                    </tr>

                                </table>
                            </div>
                        </div>
                    </div>
                    <!--</div>-->
                </div>
            </div>
            <!-- END:: User Basic Info -->
            <!-- SATRT::Marital Information and  Child Information -->
            <div class="row">
                <div class="col-md-6">
                    <div class="portlet portlet-sortable box green-color-style">
                        <div class="portlet-title ui-sortable-handle">
                            <div class="caption">
                                <i class="fa fa-life-ring green-color-style-color"></i><?php echo app('translator')->get('label.MARITAL_INFORMATION'); ?>
                            </div>
                        </div>
                        <div class="portlet-body" style="padding: 8px !important">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <?php if(!empty($cmInfoData->marital_status) && $cmInfoData->marital_status == '1'): ?>
                                    <tr>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.SPOUSE_NAME'); ?></td>
                                        <td class="vcenter"><?php echo e(!empty($cmInfoData->spouse_name) ? $cmInfoData->spouse_name: ''); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.DATE_OF_MARRIAGE'); ?></td>
                                        <td class="vcenter"> <?php echo e(!empty($cmInfoData->date_of_marriage) ? Helper::formatDate($cmInfoData->date_of_marriage): ''); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.NICK_NAME'); ?></td>
                                        <td class="vcenter"><?php echo e(!empty($cmInfoData->spouse_nick_name) ? $cmInfoData->spouse_nick_name: ''); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.DATE_OF_BIRTH'); ?></td>
                                        <td class="vcenter"> <?php echo e(!empty($cmInfoData->spouse_dob) ? Helper::formatDate($cmInfoData->spouse_dob): ''); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.MOBILE'); ?></td>
                                        <td class="vcenter"><?php echo e(!empty($cmInfoData->spouse_mobile) ? $cmInfoData->spouse_mobile: ''); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.PROFESSION'); ?></td>
                                        <td class="vcenter"><?php echo e(!empty($cmInfoData->spouse_occupation) ? $cmInfoData->spouse_occupation: ''); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.WORK_ADDRESS'); ?></td>
                                        <td class="vcenter" colspan="3"><?php echo e(!empty($cmInfoData->spouse_work_address) ? $cmInfoData->spouse_work_address: ''); ?></td>
                                    </tr>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="vcenter"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <!--<div class="column sortable ">-->
                    <div class="portlet portlet-sortable box green-color-style">
                        <div class="portlet-title ui-sortable-handle">
                            <div class="caption">
                                <i class="fa fa-life-ring green-color-style-color"></i><?php echo app('translator')->get('label.CHILDREN_INFORMATION'); ?>
                            </div>
                        </div>
                        <div class="portlet-body" style="padding: 8px !important">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td class="vcenter text-center fit bold info"><?php echo app('translator')->get('label.SL'); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.NAME'); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.DATE_OF_BIRTH'); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.SCHOOL_PROFESSION'); ?></td>
                                    </tr>
                                    <?php
                                    $childData = !empty($childInfoData) ? json_decode($childInfoData->cm_child_info, TRUE) : null;
                                    $sl = 0;
                                    ?>
                                    <?php if(!empty($childData)): ?>
                                    <?php $__currentLoopData = $childData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cKey => $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="vcenter text-center"><?php echo ++$sl; ?></td>
                                        <td class="vcenter"><?php echo !empty($child['name']) ? $child['name']: ''; ?></td>
                                        <td class="vcenter"><?php echo !empty($child['dob']) ? $child['dob']: ''; ?></td>
                                        <td class="vcenter"><?php echo !empty($child['school']) ? $child['school']: ''; ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="vcenter bold" colspan="4"><?php echo app('translator')->get('label.NO_OF_CHILDREN'); ?>:&nbsp;<?php echo !empty($childInfoData->no_of_child) ? $childInfoData->no_of_child : 0; ?></td>
                                    </tr>
                                    <?php else: ?>
                                    <tr>
                                        <td class="vcenter" colspan="4"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>


                        </div>
                    </div>
                    <!--</div> -->

                </div>


            </div>
            <!--END::Marital Information and  Child Information -->

            <!--Start::Permanent address and present address -->
            <div class="row">
                <!-- Start::Cm Permanent Address-->
                <div class="col-md-6">
                    <div class="portlet portlet-sortable box green-color-style">
                        <div class="portlet-title ui-sortable-handle">
                            <div class="caption">
                                <i class="fa fa-map-marker green-color-style-color"></i> <?php echo app('translator')->get('label.PERMANENT_ADDRESS'); ?>
                            </div>
                        </div>
                        <div class="portlet-body" style="padding: 8px !important">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <?php if(!empty($addressInfo)): ?>
                                    <tr>
                                        <td class="vcenter fit bold info">
                                            <?php echo app('translator')->get('label.DIVISION'); ?>
                                        </td>
                                        <td class="vcenter">
                                            <?php echo e(!empty($addressInfo->division_id) ? $divisionList[$addressInfo->division_id]: __("label.N_A")); ?>

                                        </td>
                                        <td class="vcenter fit bold info">
                                            <?php echo app('translator')->get('label.DISTRICT'); ?>
                                        </td>
                                        <td class="vcenter">
                                            <?php echo e(!empty($addressInfo->district_id) ? $districtList[$addressInfo->district_id]: __("label.N_A")); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="vcenter fit bold info">
                                            <?php echo app('translator')->get('label.THANA'); ?>
                                        </td>
                                        <td class="vcenter">
                                            <?php echo e(!empty($addressInfo->thana_id) ? $thanaList[$addressInfo->thana_id]: __("label.N_A")); ?>

                                        </td>
                                        <td class="vcenter fit bold info">
                                            <?php echo app('translator')->get('label.ADDRESS'); ?>
                                        </td>
                                        <td class="vcenter">
                                            <?php echo e(!empty($addressInfo->address_details) ? $addressInfo->address_details: __("label.N_A")); ?>

                                        </td>
                                    </tr>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="vcenter"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End::Cm Permanent Address-->
                <!-- Start::present address-->
                <div class="col-md-6">
                    <div class="portlet portlet-sortable box green-color-style">
                        <div class="portlet-title ui-sortable-handle">
                            <div class="caption">
                                <i class="fa fa-map-marker green-color-style-color"></i> <?php echo app('translator')->get('label.PRESENT_ADDRESS'); ?>
                            </div>
                        </div>
                        <div class="portlet-body" style="padding: 8px !important">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <?php if(!empty($presentAddressInfo)): ?>
                                    <tr>
                                        <td class="vcenter fit bold info">
                                            <?php echo app('translator')->get('label.DIVISION'); ?>
                                        </td>
                                        <td class="vcenter">
                                            <?php echo e(!empty($presentAddressInfo->division_id) ? $divisionList[$presentAddressInfo->division_id]: ''); ?>

                                        </td>
                                        <td class="vcenter fit bold info">
                                            <?php echo app('translator')->get('label.DISTRICT'); ?>
                                        </td>
                                        <td class="vcenter">
                                            <?php echo e(!empty($presentAddressInfo->district_id) ? $districtList[$presentAddressInfo->district_id]: __("label.N_A")); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="vcenter fit bold info">
                                            <?php echo app('translator')->get('label.THANA'); ?>
                                        </td>
                                        <td class="vcenter">
                                            <?php echo e(!empty($presentAddressInfo->thana_id) ? $thanaList[$presentAddressInfo->thana_id]: __("label.N_A")); ?>

                                        </td>
                                        <td class="vcenter fit bold info">
                                            <?php echo app('translator')->get('label.ADDRESS'); ?>
                                        </td>
                                        <td class="vcenter">
                                            <?php echo e(!empty($presentAddressInfo->address_details) ? $presentAddressInfo->address_details: __("label.N_A")); ?>

                                        </td>
                                    </tr>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="vcenter"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End::present address-->

            </div>
            <!--END::Permanent address and present address -->

            <!-- SATRT::Passport & Bank Information -->
            <div class="row">
                <div class="col-md-6">
                    <div class="portlet portlet-sortable box green-color-style">
                        <div class="portlet-title ui-sortable-handle">
                            <div class="caption">
                                <i class="fa fa-paper-plane green-color-style-color"></i><?php echo app('translator')->get('label.PASSPORT_DETAILS'); ?>
                            </div>
                        </div>
                        <div class="portlet-body" style="padding: 8px !important">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <?php if(!empty($passportInfoData)): ?>
                                    <tr>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.PASSPORT_NO'); ?></td>
                                        <td class="vcenter"><?php echo e(!empty($passportInfoData->passport_no) ? $passportInfoData->passport_no: __('label.N_A')); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.DATE_OF_ISSUE'); ?></td>
                                        <td class="vcenter"> <?php echo e(!empty($passportInfoData->date_of_issue) ? Helper::formatDate($passportInfoData->date_of_issue): __('label.N_A')); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.PLACE_OF_ISSUE'); ?></td>
                                        <td class="vcenter"><?php echo e(!empty($passportInfoData->place_of_issue) ? $passportInfoData->place_of_issue: ''); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.DATE_OF_EXPIRY'); ?></td>
                                        <td class="vcenter"> <?php echo e(!empty($passportInfoData->date_of_expire) ? Helper::formatDate($passportInfoData->date_of_expire): ''); ?></td>
                                    </tr>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="vcenter"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="portlet portlet-sortable box green-color-style">
                        <div class="portlet-title ui-sortable-handle">
                            <div class="caption">
                                <i class="fa fa-bank green-color-style-color"></i><?php echo app('translator')->get('label.BANK_INFO'); ?>
                            </div>
                        </div>
                        <div class="portlet-body" style="padding: 8px !important">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td class="vcenter text-center fit bold info"><?php echo app('translator')->get('label.SL'); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.BANK_NAME'); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.BRANCH'); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.ACCT_NO'); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.ONLINE'); ?></td>
                                    </tr>
                                    <?php
                                    $bankData = !empty($bankInfoData) ? json_decode($bankInfoData->bank_info, TRUE) : null;
                                    $sl = 0;
                                    ?>
                                    <?php if(!empty($bankData)): ?>
                                    <?php $__currentLoopData = $bankData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bKey => $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="vcenter text-center"><?php echo ++$sl; ?></td>
                                        <td class="vcenter"><?php echo !empty($bank['name']) ? $bank['name']: ''; ?></td>
                                        <td class="vcenter"><?php echo !empty($bank['branch']) ? $bank['branch']: ''; ?></td>
                                        <td class="vcenter"><?php echo !empty($bank['account']) ? $bank['account']: ''; ?></td>
                                        <td class="vcenter"><?php echo !empty($bank['is_online']) ? __('label.YES') : __('label.NO'); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                    <tr>
                                        <td class="vcenter" colspan="5"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>


                        </div>
                    </div>
                </div>


            </div>
            <!--END::Passport & Bank Information -->

            <!-- START:: Academic qualification Information-->
            <div class="row"> 
                <div class="col-md-12">
                    <div class="portlet portlet-sortable box green-color-style">
                        <div class="portlet-title ui-sortable-handle">
                            <div class="caption">
                                <i class="fa fa-graduation-cap green-color-style-color"></i><?php echo app('translator')->get('label.ACADEMIC_QUALIFICATION'); ?>
                            </div>
                        </div>
                        <div class="portlet-body" style="padding: 8px !important">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td scope="col" class="vcenter text-center fit bold info"><?php echo app('translator')->get('label.SERIAL'); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.INSTITUTE_NAME'); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.EXAMINATION'); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.FROM'); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.TO'); ?></td>
                                        <td class="vcenter text-center fit bold info"><?php echo app('translator')->get('label.QUAL_ERODE'); ?></td>
                                    </tr>
                                    <?php
                                    $cSlShow = 1;
                                    $civilEducation = !empty($civilEducationInfoData) ? json_decode($civilEducationInfoData->civil_education_info, true) : null;
                                    //echo '<pre>';        print_r($brotherSister);exit;
                                    ?>
                                    <?php if(!empty($civilEducation)): ?>
                                    <?php $__currentLoopData = $civilEducation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ceVar => $civilEducationInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>                               
                                    <tr>
                                        <td class="vcenter text-center"><?php echo e($cSlShow); ?></td>
                                        <td class="vcenter"><?php echo e(!empty($civilEducationInfo['institute_name']) ? $civilEducationInfo['institute_name']: ''); ?></td>
                                        <td class="vcenter"> <?php echo e(!empty($civilEducationInfo['examination']) ? $civilEducationInfo['examination']: ''); ?></td>
                                        <td class="vcenter"><?php echo e(!empty($civilEducationInfo['from']) ? $civilEducationInfo['from']: ''); ?></td>
                                        <td class="vcenter"> <?php echo e(!empty($civilEducationInfo['to']) ? $civilEducationInfo['to']: ''); ?></td>
                                        <td class="vcenter text-center"> <?php echo e(!empty($civilEducationInfo['qual_erode']) ? $civilEducationInfo['qual_erode']: ''); ?></td>
                                    </tr>
                                    <?php
                                    $cSlShow++;
                                    ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="vcenter"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- End:: Academic qualification Information-->
            <!-- START:: Mil qualification Information-->
            <div class="row"> 
                <div class="col-md-12">
                    <div class="portlet portlet-sortable box green-color-style">
                        <div class="portlet-title ui-sortable-handle">
                            <div class="caption">
                                <i class="fa fa-user green-color-style-color"></i><?php echo app('translator')->get('label.MIL_QUALIFICATION'); ?>
                            </div>
                        </div>
                        <div class="portlet-body" style="padding: 8px !important">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td scope="col" class="vcenter text-center fit bold info"><?php echo app('translator')->get('label.SERIAL'); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.INSTITUTE_NAME'); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.COURSE'); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.FROM'); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.TO'); ?></td>
                                        <td class="vcenter text-center fit bold info"><?php echo app('translator')->get('label.RESULT'); ?></td>
                                    </tr>
                                    <?php
                                    $cSlShow = 1;
                                    $milQualification = !empty($defenceRelativeInfoData) ? json_decode($defenceRelativeInfoData->cm_relative_info, true) : null;
                                    ?>
                                    <?php if(!empty($milQualification)): ?>
                                    <?php $__currentLoopData = $milQualification; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mKey => $milInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>                               
                                    <tr>
                                        <td class="vcenter text-center"><?php echo e($cSlShow); ?></td>
                                        <td class="vcenter"><?php echo e(!empty($milInfo['institute_name']) ? $milInfo['institute_name']: ''); ?></td>
                                        <td class="vcenter"> <?php echo e(!empty($milCourseList[$milInfo['course']]) ? $milCourseList[$milInfo['course']]: ''); ?></td>
                                        <td class="vcenter"><?php echo e(!empty($milInfo['from']) ? $milInfo['from']: ''); ?></td>
                                        <td class="vcenter"> <?php echo e(!empty($milInfo['to']) ? $milInfo['to']: ''); ?></td>
                                        <td class="vcenter text-center"> <?php echo e(!empty($milInfo['result']) ? $milInfo['result']: ''); ?></td>
                                    </tr>
                                    <?php
                                    $cSlShow++;
                                    ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="vcenter"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- End:: Mil qualification Information-->

            <!-- Start:: Record of service Information-->
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet portlet-sortable box green-color-style">
                        <div class="portlet-title ui-sortable-handle">
                            <div class="caption">
                                <i class="fa fa fa-cogs green-color-style-color"></i><?php echo app('translator')->get('label.RECORD_OF_SERVICE'); ?>
                            </div>
                        </div>
                        <div class="portlet-body" style="padding: 8px !important">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td class="vcenter text-center fit bold info"><?php echo app('translator')->get('label.SERIAL'); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.FROM'); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.TO'); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.UNIT_FMN_INST'); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.APPT'); ?></td>
                                    </tr>
                                    <?php
                                    $serviceRecord = !empty($serviceRecordInfoData) ? json_decode($serviceRecordInfoData->service_record_info, true) : null;
                                    $srSlShow = 1;
                                    ?>
                                    <?php if(!empty($serviceRecord)): ?>
                                    <?php $__currentLoopData = $serviceRecord; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $srVar => $serviceRecordInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>                               
                                    <tr>
                                        <td class="vcenter text-center width-50"><?php echo e($srSlShow); ?></td>
                                        <td class="vcenter"><?php echo e(!empty($serviceRecordInfo['from']) ? $serviceRecordInfo['from']: ''); ?></td>
                                        <td class="vcenter"><?php echo e(!empty($serviceRecordInfo['to']) ? $serviceRecordInfo['to']: ''); ?></td>
                                        <?php
                                        $unitFmnInst = (!empty($serviceRecordInfo['unit_fmn_inst']) && $serviceRecordInfo['unit_fmn_inst']) != 0 ? (!empty($organizationList[$serviceRecordInfo['unit_fmn_inst']]) ? $organizationList[$serviceRecordInfo['unit_fmn_inst']] : $serviceRecordInfo['unit_fmn_inst']) : '';
                                        $appointment = (!empty($serviceRecordInfo['appointment']) && $serviceRecordInfo['appointment']) != 0 ? (!empty($allAppointmentList[$serviceRecordInfo['appointment']]) ? $allAppointmentList[$serviceRecordInfo['appointment']] : $serviceRecordInfo['appointment']) : '';
                                        ?>
                                        <td class="vcenter"><?php echo e($unitFmnInst); ?></td>
                                        <td class="vcenter"><?php echo e($appointment); ?></td>
                                    </tr>
                                    <?php $srSlShow++; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="vcenter"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- End:: Record of service Information-->

            <!-- Start:: un mission Information-->
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet portlet-sortable box green-color-style">
                        <div class="portlet-title ui-sortable-handle">
                            <div class="caption">
                                <i class="fa fa-globe green-color-style-color"></i><?php echo app('translator')->get('label.UN_MSN'); ?>
                            </div>
                        </div>
                        <div class="portlet-body" style="padding: 8px !important">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td class="vcenter text-center fit bold info" rowspan="2"><?php echo app('translator')->get('label.SERIAL'); ?></td>
                                        <td class="vcenter text-center fit bold info" colspan="2"><?php echo app('translator')->get('label.DURATION'); ?></td>
                                        <td class="vcenter fit bold info" rowspan="2"><?php echo app('translator')->get('label.MSN'); ?></td>
                                        <td class="vcenter fit bold info" rowspan="2"><?php echo app('translator')->get('label.APPT'); ?></td>
                                        <td class="vcenter fit bold info" rowspan="2"><?php echo app('translator')->get('label.REMARKS'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.FROM'); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.TO'); ?></td>
                                    </tr>
                                    <?php
                                    $msnData = !empty($msnDataInfo) ? json_decode($msnDataInfo->msn_info, true) : null;
                                    $srSlShow = 1;
                                    ?>
                                    <?php if(!empty($msnData)): ?>
                                    <?php $__currentLoopData = $msnData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mKey => $msnInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>                               
                                    <tr>
                                        <td class="vcenter text-center width-50"><?php echo e($srSlShow); ?></td>
                                        <td class="vcenter"><?php echo e(!empty($msnInfo['from']) ? $msnInfo['from']: ''); ?></td>
                                        <td class="vcenter"><?php echo e(!empty($msnInfo['to']) ? $msnInfo['to']: ''); ?></td>
                                        <td class="vcenter"><?php echo e(!empty($msnInfo['msn']) ? $msnInfo['msn']: ''); ?></td>
                                        <td class="vcenter"><?php echo e(!empty($msnInfo['remark']) ? $msnInfo['remark']: ''); ?></td>
                                        <?php
                                        $appointment = (!empty($msnInfo['appointment']) && $msnInfo['appointment']) != 0 ? (!empty($allAppointmentList[$msnInfo['appointment']]) ? $allAppointmentList[$msnInfo['appointment']] : $msnInfo['appointment']) : '';
                                        ?>
                                        <td class="vcenter"><?php echo e($appointment); ?></td>
                                    </tr>
                                    <?php $srSlShow++; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="vcenter"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- End:: un mission Information-->

            <!-- Start:: country visited Information-->
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet portlet-sortable box green-color-style">
                        <div class="portlet-title ui-sortable-handle">
                            <div class="caption">
                                <i class="fa fa-plane green-color-style-color"></i><?php echo app('translator')->get('label.COUNTRY_VISITED'); ?>
                            </div>
                        </div>
                        <div class="portlet-body" style="padding: 8px !important">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td class="vcenter text-center fit bold info" rowspan="2"><?php echo app('translator')->get('label.SERIAL'); ?></td>
                                        <td class="vcenter fit bold info" rowspan="2"><?php echo app('translator')->get('label.NAME_OF_COUNTRY'); ?></td>
                                        <td class="vcenter text-center fit bold info" colspan="2"><?php echo app('translator')->get('label.DURATION'); ?></td>
                                        <td class="vcenter fit bold info" rowspan="2"><?php echo app('translator')->get('label.REASONS_FOR_VISIT'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.FROM'); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.TO'); ?></td>
                                    </tr>
                                    <?php
                                    $countryVisitData = !empty($countryVisitDataInfo) ? json_decode($countryVisitDataInfo->visit_info, true) : null;
                                    $srSlShow = 1;
                                    ?>
                                    <?php if(!empty($countryVisitData)): ?>
                                    <?php $__currentLoopData = $countryVisitData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cKey => $countryInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>                               
                                    <tr>
                                        <td class="vcenter text-center width-50"><?php echo e($srSlShow); ?></td>
                                        <td class="vcenter"><?php echo e(!empty($countryInfo['country_name']) ? $countryInfo['country_name']: ''); ?></td>
                                        <td class="vcenter"><?php echo e(!empty($countryInfo['from']) ? $countryInfo['from']: ''); ?></td>
                                        <td class="vcenter"><?php echo e(!empty($countryInfo['to']) ? $countryInfo['to']: ''); ?></td>
                                        <td class="vcenter"><?php echo e(!empty($countryInfo['reason']) ? $countryInfo['reason']: ''); ?></td>
                                    </tr>
                                    <?php $srSlShow++; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="vcenter"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- End:: country visited Information-->

            <div class="row"> 
                <!-- START:: key appt Info -->
                <div class="col-md-5">
                    <div class="portlet portlet-sortable box green-color-style">
                        <div class="portlet-title ui-sortable-handle">
                            <div class="caption">
                                <i class="fa fa-calendar green-color-style-color"></i><?php echo app('translator')->get('label.KEY_APPT'); ?>
                            </div>
                        </div>
                        <div class="portlet-body" style="padding: 8px !important">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <?php if(!empty($keyAppt)): ?>
                                    <?php $sl = 0; ?>
                                    <tr>
                                        <td>
                                            <?php $__currentLoopData = $keyAppt; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php echo ++$sl; ?>.&nbsp;<?php echo !empty($appt) ? $appt : ''; ?><?php echo '<br />'; ?>

                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                    </tr>
                                    <?php else: ?>
                                    <tr>
                                        <td class="vcenter"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END:: key appt Info -->
                <!-- START:: Cm Others Info -->
                <div class="col-md-7">
                    <div class="portlet portlet-sortable box green-color-style">
                        <div class="portlet-title ui-sortable-handle">
                            <div class="caption">
                                <i class="fa fa-cog green-color-style-color"></i> <?php echo app('translator')->get('label.OTHERS'); ?>
                            </div>
                        </div>
                        <div class="portlet-body" style="padding: 8px !important">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.DECORATION_AWARD'); ?></td>
                                        <td class="vcenter fit bold info"><?php echo app('translator')->get('label.HOBBY'); ?></td>
                                    </tr>
                                    <?php if(!empty($othersInfoData)): ?>
                                    <tr>
                                        <?php
                                        $decArr = !empty($othersInfoData->decoration_id) ? explode(',', $othersInfoData->decoration_id) : [];
                                        $hobbyArr = !empty($othersInfoData->hobby_id) ? explode(',', $othersInfoData->hobby_id) : [];
                                        ?>
                                        <td>
                                            <?php if(!empty($decArr)): ?>
                                            <?php $sl = 0; ?>
                                            <?php $__currentLoopData = $decArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php echo ++$sl; ?>.&nbsp;<?php echo !empty($decorationList[$dec]) ? $decorationList[$dec] : ''; ?><?php echo '<br />'; ?>

                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>

                                        </td>
                                        <td>
                                            <?php if(!empty($hobbyArr)): ?>
                                            <?php $sl = 0; ?>
                                            <?php $__currentLoopData = $hobbyArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hobby): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php echo ++$sl; ?>.&nbsp;<?php echo !empty($hobbyList[$hobby]) ? $hobbyList[$hobby] : ''; ?><?php echo '<br />'; ?>

                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>

                                        </td>
                                    </tr>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="vcenter"><?php echo app('translator')->get('label.NO_DATA_FOUND'); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END:: Cm Others Info -->
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Xampp\htdocs\afwc\resources\views/report/nominalRoll/profile.blade.php ENDPATH**/ ?>