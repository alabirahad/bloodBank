<?php

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('dashboard');
    } else {
        return view('auth.login');
    }
});
Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', 'Admin\DashboardController@index');

    Route::post('setRecordPerPage', 'UserController@setRecordPerPage');
    Route::get('myProfile', 'UserController@myProfile');
    Route::get('accountSetting', 'UserController@accountSetting');
    Route::get('changePassword', 'UserController@changePassword');
    Route::post('getProfileCenter', 'UserController@getProfileCenter');
    Route::post('updateProfile', 'UserController@updateProfile');
    Route::post('changePassword', 'UserController@updatePassword');

    Route::group(['middleware' => 'superAdmin'], function () {
        //User Group
        Route::post('userGroup/filter', 'UserGroupController@filter');
        Route::post('userGroup/getOrder', 'UserGroupController@getOrder');
        Route::get('userGroup', 'UserGroupController@index')->name('userGroup.index');

        //rank
        Route::post('rank/filter', 'RankController@filter');
        Route::post('rank/getOrder', 'RankController@getOrder');
        Route::get('rank', 'RankController@index')->name('rank.index');
        Route::get('rank/create', 'RankController@create')->name('rank.create');
        Route::post('rank', 'RankController@store')->name('rank.store');
        Route::get('rank/{id}/edit', 'RankController@edit')->name('rank.edit');
        Route::patch('rank/{id}', 'RankController@update')->name('rank.update');
        Route::delete('rank/{id}', 'RankController@destroy')->name('rank.destroy');

        //appointment
        Route::post('appointment/filter', 'AppointmentController@filter');
        Route::post('appointment/getOrder', 'AppointmentController@getOrder');
        Route::get('appointment', 'AppointmentController@index')->name('appointment.index');
        Route::get('appointment/create', 'AppointmentController@create')->name('appointment.create');
        Route::post('appointment', 'AppointmentController@store')->name('appointment.store');
        Route::get('appointment/{id}/edit', 'AppointmentController@edit')->name('appointment.edit');
        Route::patch('appointment/{id}', 'AppointmentController@update')->name('appointment.update');
        Route::delete('appointment/{id}', 'AppointmentController@destroy')->name('appointment.destroy');

        //CM appointment
        Route::post('cmAppointment/filter', 'CmAppointmentController@filter');
        Route::post('cmAppointment/getOrder', 'CmAppointmentController@getOrder');
        Route::get('cmAppointment', 'CmAppointmentController@index')->name('cmAppointment.index');
        Route::get('cmAppointment/create', 'CmAppointmentController@create')->name('cmAppointment.create');
        Route::post('cmAppointment', 'CmAppointmentController@store')->name('cmAppointment.store');
        Route::get('cmAppointment/{id}/edit', 'CmAppointmentController@edit')->name('cmAppointment.edit');
        Route::patch('cmAppointment/{id}', 'CmAppointmentController@update')->name('cmAppointment.update');
        Route::delete('cmAppointment/{id}', 'CmAppointmentController@destroy')->name('cmAppointment.destroy');

        //service appointment
        Route::post('serviceAppointment/filter', 'ServiceAppointmentController@filter');
        Route::post('serviceAppointment/getOrder', 'ServiceAppointmentController@getOrder');
        Route::get('serviceAppointment', 'ServiceAppointmentController@index')->name('serviceAppointment.index');
        Route::get('serviceAppointment/create', 'ServiceAppointmentController@create')->name('serviceAppointment.create');
        Route::post('serviceAppointment', 'ServiceAppointmentController@store')->name('serviceAppointment.store');
        Route::get('serviceAppointment/{id}/edit', 'ServiceAppointmentController@edit')->name('serviceAppointment.edit');
        Route::patch('serviceAppointment/{id}', 'ServiceAppointmentController@update')->name('serviceAppointment.update');
        Route::delete('serviceAppointment/{id}', 'ServiceAppointmentController@destroy')->name('serviceAppointment.destroy');

        //arms & sevice
        Route::post('armsService/filter/', 'ArmsServiceController@filter');
        Route::get('armsService', 'ArmsServiceController@index')->name('armsService.index');
        Route::get('armsService/create', 'ArmsServiceController@create')->name('armsService.create');
        Route::post('armsService', 'ArmsServiceController@store')->name('armsService.store');
        Route::get('armsService/{id}/edit', 'ArmsServiceController@edit')->name('armsService.edit');
        Route::patch('armsService/{id}', 'ArmsServiceController@update')->name('armsService.update');
        Route::delete('armsService/{id}', 'ArmsServiceController@destroy')->name('armsService.destroy');

        //cm group
        Route::post('cmGroup/filter', 'CmGroupController@filter');
        Route::get('cmGroup', 'CmGroupController@index')->name('cmGroup.index');
        Route::get('cmGroup/create', 'CmGroupController@create')->name('cmGroup.create');
        Route::post('cmGroup', 'CmGroupController@store')->name('cmGroup.store');
        Route::get('cmGroup/{id}/edit', 'CmGroupController@edit')->name('cmGroup.edit');
        Route::patch('cmGroup/{id}', 'CmGroupController@update')->name('cmGroup.update');
        Route::delete('cmGroup/{id}', 'CmGroupController@destroy')->name('cmGroup.destroy');

        //cm group member template management
        Route::get('cmGroupMemberTemplate', 'CmGroupMemberTemplateController@index');
        Route::post('cmGroupMemberTemplate/getCmGroup', 'CmGroupMemberTemplateController@getCmGroup');
        Route::post('cmGroupMemberTemplate/cmGroupMember', 'CmGroupMemberTemplateController@cmGroupMember');
        Route::post('cmGroupMemberTemplate/saveCmGroupMember', 'CmGroupMemberTemplateController@saveCmGroupMember');
        Route::post('cmGroupMemberTemplate/getAssignedCm', 'CmGroupMemberTemplateController@getAssignedCm');

        //ds group member template management
        Route::get('dsGroupMemberTemplate', 'DsGroupMemberTemplateController@index');
        Route::post('dsGroupMemberTemplate/getDsGroup', 'DsGroupMemberTemplateController@getDsGroup');
        Route::post('dsGroupMemberTemplate/dsGroupMember', 'DsGroupMemberTemplateController@dsGroupMember');
        Route::post('dsGroupMemberTemplate/saveDsGroupMember', 'DsGroupMemberTemplateController@saveDsGroupMember');
        Route::post('dsGroupMemberTemplate/getAssignedDs', 'DsGroupMemberTemplateController@getAssignedDs');

        //ds group
        Route::post('dsGroup/filter', 'DsGroupController@filter');
        Route::get('dsGroup', 'DsGroupController@index')->name('dsGroup.index');
        Route::get('dsGroup/create', 'DsGroupController@create')->name('dsGroup.create');
        Route::post('dsGroup', 'DsGroupController@store')->name('dsGroup.store');
        Route::get('dsGroup/{id}/edit', 'DsGroupController@edit')->name('dsGroup.edit');
        Route::patch('dsGroup/{id}', 'DsGroupController@update')->name('dsGroup.update');
        Route::delete('dsGroup/{id}', 'DsGroupController@destroy')->name('dsGroup.destroy');

        //training year
        Route::post('trainingYear/changeStatus/', 'TrainingYearController@changeStatus');
        Route::post('trainingYear/filter/', 'TrainingYearController@filter');
        Route::get('trainingYear', 'TrainingYearController@index')->name('trainingYear.index');
        Route::get('trainingYear/create', 'TrainingYearController@create')->name('trainingYear.create');
        Route::post('trainingYear', 'TrainingYearController@store')->name('trainingYear.store');
        Route::get('trainingYear/{id}/edit', 'TrainingYearController@edit')->name('trainingYear.edit');
        Route::patch('trainingYear/{id}', 'TrainingYearController@update')->name('trainingYear.update');
        Route::delete('trainingYear/{id}', 'TrainingYearController@destroy')->name('trainingYear.destroy');

        //wing
        Route::post('wing/filter/', 'WingController@filter');
        Route::get('wing', 'WingController@index')->name('wing.index');
        Route::get('wing/create', 'WingController@create')->name('wing.create');
        Route::post('wing', 'WingController@store')->name('wing.store');
        Route::get('wing/{id}/edit', 'WingController@edit')->name('wing.edit');
        Route::patch('wing/{id}', 'WingController@update')->name('wing.update');
        Route::delete('wing/{id}', 'WingController@destroy')->name('wing.destroy');

        //term
        Route::post('term/filter/', 'TermController@filter');
        Route::get('term', 'TermController@index')->name('term.index');
        Route::get('term/create', 'TermController@create')->name('term.create');
        Route::post('term', 'TermController@store')->name('term.store');
        Route::get('term/{id}/edit', 'TermController@edit')->name('term.edit');
        Route::patch('term/{id}', 'TermController@update')->name('term.update');
        Route::delete('term/{id}', 'TermController@destroy')->name('term.destroy');

        //syndicate
        Route::post('syndicate/filter/', 'SyndicateController@filter');
        Route::get('syndicate', 'SyndicateController@index')->name('syndicate.index');
        Route::get('syndicate/create', 'SyndicateController@create')->name('syndicate.create');
        Route::post('syndicate', 'SyndicateController@store')->name('syndicate.store');
        Route::get('syndicate/{id}/edit', 'SyndicateController@edit')->name('syndicate.edit');
        Route::patch('syndicate/{id}', 'SyndicateController@update')->name('syndicate.update');
        Route::delete('syndicate/{id}', 'SyndicateController@destroy')->name('syndicate.destroy');

        // sub syndicate
        Route::post('subSyndicate/filter/', 'SubSyndicateController@filter');
        Route::get('subSyndicate', 'SubSyndicateController@index')->name('subSyndicate.index');
        Route::get('subSyndicate/create', 'SubSyndicateController@create')->name('subSyndicate.create');
        Route::post('subSyndicate', 'SubSyndicateController@store')->name('subSyndicate.store');
        Route::get('subSyndicate/{id}/edit', 'SubSyndicateController@edit')->name('subSyndicate.edit');
        Route::patch('subSyndicate/{id}', 'SubSyndicateController@update')->name('subSyndicate.update');
        Route::delete('subSyndicate/{id}', 'SubSyndicateController@destroy')->name('subSyndicate.destroy');

        //course Id
        Route::post('courseId/filter', 'CourseIdController@filter');
        Route::get('courseId', 'CourseIdController@index')->name('courseId.index');
        Route::get('courseId/create', 'CourseIdController@create')->name('courseId.create');
        Route::post('courseId', 'CourseIdController@store')->name('courseId.store');
        Route::get('courseId/{id}/edit', 'CourseIdController@edit')->name('courseId.edit');
        Route::patch('courseId/{id}', 'CourseIdController@update')->name('courseId.update');
        Route::delete('courseId/{id}', 'CourseIdController@destroy')->name('courseId.destroy');

        //user
        Route::post('user/filter/', 'UserController@filter');
        Route::post('user/getInstitue', 'UserController@getInstitue');
        Route::post('user/getDteBr', 'UserController@getDteBr');
        Route::post('user/getWing', 'UserController@getWing');
        Route::post('user/getServiceWiseRankAppPnp', 'UserController@getServiceWiseRankAppPnp');
        Route::get('user', 'UserController@index')->name('user.index');
        Route::get('user/create', 'UserController@create')->name('user.create');
        Route::post('user', 'UserController@store')->name('user.store');
        Route::get('user/{id}/edit', 'UserController@edit')->name('user.edit');
        Route::patch('user/{id}', 'UserController@update')->name('user.update');
        Route::delete('user/{id}', 'UserController@destroy')->name('user.destroy');
        Route::post('user/getRank', 'UserController@getRank');

        //cm
        Route::post('cm/filter/', 'CmController@filter');
        Route::post('cm/getRank', 'CmController@getRank');
        Route::get('cm', 'CmController@index')->name('cm.index');
        Route::get('cm/create', 'CmController@create')->name('cm.create');
        Route::post('cm', 'CmController@store')->name('cm.store');
        Route::get('cm/{id}/edit', 'CmController@edit')->name('cm.edit');
        Route::patch('cm/{id}', 'CmController@update')->name('cm.update');
        Route::delete('cm/{id}', 'CmController@destroy')->name('cm.destroy');

        Route::get('cm/{id}/profile', 'CmController@profile')->name('cm.profile');
        Route::post('cm/updateFamilyInfo', 'CmController@updateFamilyInfo')->name('cm.familyInfoUpdate');
        Route::post('cm/updateMaritalStatus', 'CmController@updateMaritalStatus')->name('cm.maritialStatusUpdate');
        Route::post('cm/updateBrotherSisterInfo', 'CmController@updateBrotherSisterInfo')->name('cm.brotherSisterInfoUpdate');
        Route::post('cm/rowAddForBrotherSister', 'CmController@rowAddForBrotherSister')->name('cm.rowAddForBrotherSister');
        Route::post('cm/getDistrict', 'CmController@getDistrict')->name('cm.getDistrict');
        Route::post('cm/getThana', 'CmController@getThana')->name('cm.getThana');
        Route::post('cm/updatePermanentAddress', 'CmController@updatePermanentAddress')->name('cm.permanentAddressUpdate');
        Route::post('cm/rowAddForCivilEducation', 'CmController@rowAddForCivilEducation')->name('cm.rowAddForCivilEducation');
        Route::post('cm/updateCivilEducationInfo', 'CmController@updateCivilEducationInfo')->name('cm.civilEducationInfoUpdate');
        Route::post('cm/rowAddForServiceRecord', 'CmController@rowAddForServiceRecord')->name('cm.rowAddForServiceRecord');
        Route::post('cm/updateServiceRecordInfo', 'CmController@updateServiceRecordInfo')->name('cm.serviceRecordInfoUpdate');
        Route::post('cm/rowAddForAwardRecord', 'CmController@rowAddForAwardRecord')->name('cm.rowAddForAwardRecord');
        Route::post('cm/updateAwardRecordInfo', 'CmController@updateAwardRecordInfo')->name('cm.awardRecordInfoUpdate');
        Route::post('cm/rowAddForPunishmentRecord', 'CmController@rowAddForPunishmentRecord')->name('cm.rowAddForPunishmentRecord');
        Route::post('cm/updatePunishmentRecordInfo', 'CmController@updatePunishmentRecordInfo')->name('cm.punishmentRecordInfoUpdate');
        Route::post('cm/rowAddForDefenceRelative', 'CmController@rowAddForDefenceRelative')->name('cm.rowAddForDefenceRelative');
        Route::post('cm/updateDefenceRelativeInfo', 'CmController@updateDefenceRelativeInfo')->name('cm.defenceRelativeInfoUpdate');
        Route::post('cm/updateNextKin', 'CmController@updateNextKin')->name('cm.nextOfKinInfoUpdate');
        Route::post('cm/updateMedicalDetails', 'CmController@updateMedicalDetails')->name('cm.medicalDetailsUpdate');
        Route::post('cm/updateWinterTraining', 'CmController@updateWinterTraining')->name('cm.winterTrainingUpdate');
        Route::post('cm/updateCmOthersInfo', 'CmController@updateCmOthersInfo')->name('cm.cmOthersUpdate');


        //Mutual Assessment
        Route::get('mutualAssessment/markingSheet', 'MutualAssessmentController@markingSheet');
        Route::post('mutualAssessment/getTerm', 'MutualAssessmentController@getTerm');
        Route::post('mutualAssessment/getCmAndSubSyndicate', 'MutualAssessmentController@getCmAndSubSyndicate');
        Route::post('mutualAssessment/getSyn', 'MutualAssessmentController@getSyn');
        Route::post('mutualAssessment/getCmbySubSyn', 'MutualAssessmentController@getCmbySubSyn');
        Route::post('mutualAssessment/changeDeliverStatus', 'MutualAssessmentController@changeDeliverStatus');
        Route::post('mutualAssessment/getPreviewBtn', 'MutualAssessmentController@getPreviewBtn');
        Route::post('mutualAssessment/previewMarkingSheet', 'MutualAssessmentController@previewMarkingSheet');
        Route::post('mutualAssessment/generate', 'MutualAssessmentController@generate');

        Route::get('mutualAssessment/importMarkingSheet', 'MutualAssessmentController@importMarkingSheet');
        Route::post('mutualAssessment/getSubsynAndCmList', 'MutualAssessmentController@getSubsynAndCmList');
        Route::post('mutualAssessment/getCmListBySubSyn', 'MutualAssessmentController@getCmListBySubSyn');
        Route::post('mutualAssessment/getFileUploader', 'MutualAssessmentController@getFileUploader');
        Route::post('mutualAssessment/import', 'MutualAssessmentController@import');
        Route::post('mutualAssessment/saveImportedData', 'MutualAssessmentController@saveImportedData');

        //Marking Group management
        Route::get('markingGroup', 'MarkingGroupController@index');
        Route::post('markingGroup/getTerm', 'MarkingGroupController@getTerm');
        Route::post('markingGroup/getEvent', 'MarkingGroupController@getEvent');
        Route::post('markingGroup/getSubEventCmDs', 'MarkingGroupController@getSubEventCmDs');
        Route::post('markingGroup/getSubSubEventCmDs', 'MarkingGroupController@getSubSubEventCmDs');
        Route::post('markingGroup/getSubSubSubEventCmDs', 'MarkingGroupController@getSubSubSubEventCmDs');
        Route::post('markingGroup/getCmDs', 'MarkingGroupController@getCmDs');
        Route::post('markingGroup/getCmDsSelection', 'MarkingGroupController@getCmDsSelection');
        Route::post('markingGroup/getSubSyn', 'MarkingGroupController@getSubSyn');
        Route::post('markingGroup/getGroupTemplateWiseSearchCm', 'MarkingGroupController@getGroupTemplateWiseSearchCm');
        Route::post('markingGroup/setCm', 'MarkingGroupController@setCm');
        Route::post('markingGroup/getFilterIndividualFullCm', 'MarkingGroupController@getFilterIndividualFullCm');
        Route::post('markingGroup/getFilterIndividualCm', 'MarkingGroupController@getFilterIndividualCm');
        Route::post('markingGroup/getSynWiseSearchCm', 'MarkingGroupController@getSynWiseSearchCm');
        Route::post('markingGroup/getGroupTemplateWiseSearchDs', 'MarkingGroupController@getGroupTemplateWiseSearchDs');
        Route::post('markingGroup/getFilterIndividualDs', 'MarkingGroupController@getFilterIndividualDs');
        Route::post('markingGroup/setDs', 'MarkingGroupController@setDs');
        Route::post('markingGroup/saveMarkingGroup', 'MarkingGroupController@saveMarkingGroup');

        //syn to sub syn
        Route::get('synToSubSyn', 'SynToSubSynController@index');
        Route::post('synToSubSyn/getSyn', 'SynToSubSynController@getSyn');
        Route::post('synToSubSyn/getSubSyn', 'SynToSubSynController@getSubSyn');
        Route::post('synToSubSyn/saveSubSyn', 'SynToSubSynController@saveSubSyn');

        //syn to course
        Route::post('synToCourse/getSyn', 'SynToCourseController@getSyn');
        Route::post('synToCourse/saveSyn', 'SynToCourseController@saveSyn');
        Route::get('synToCourse', 'SynToCourseController@index');


        //cm group to course
        Route::post('cmGroupToCourse/getCmGroup', 'CmGroupToCourseController@getCmGroup');
        Route::post('cmGroupToCourse/saveCmGroup', 'CmGroupToCourseController@saveCmGroup');
        Route::get('cmGroupToCourse', 'CmGroupToCourseController@index');

        // commissioning course
        Route::post('commissioningCourse/close', 'CommissioningCourseController@close');
        Route::post('commissioningCourse/filter', 'CommissioningCourseController@filter');
        Route::get('commissioningCourse', 'CommissioningCourseController@index')->name('commissioningCourse.index');
        Route::get('commissioningCourse/create', 'CommissioningCourseController@create')->name('commissioningCourse.create');
        Route::post('commissioningCourse', 'CommissioningCourseController@store')->name('commissioningCourse.store');
        Route::get('commissioningCourse/{id}/edit', 'CommissioningCourseController@edit')->name('commissioningCourse.edit');
        Route::patch('commissioningCourse/{id}', 'CommissioningCourseController@update')->name('commissioningCourse.update');
        Route::delete('commissioningCourse/{id}', 'CommissioningCourseController@destroy')->name('commissioningCourse.destroy');



        //ds group to course
        Route::post('dsGroupToCourse/getDsGroup', 'DsGroupToCourseController@getDsGroup');
        Route::post('dsGroupToCourse/saveDsGroup', 'DsGroupToCourseController@saveDsGroup');
        Route::get('dsGroupToCourse', 'DsGroupToCourseController@index');


        //event group
        Route::post('eventGroup/filter', 'EventGroupController@filter');
        Route::get('eventGroup', 'EventGroupController@index')->name('eventGroup.index');
        Route::get('eventGroup/create', 'EventGroupController@create')->name('eventGroup.create');
        Route::post('eventGroup', 'EventGroupController@store')->name('eventGroup.store');
        Route::get('eventGroup/{id}/edit', 'EventGroupController@edit')->name('eventGroup.edit');
        Route::patch('eventGroup/{id}', 'EventGroupController@update')->name('eventGroup.update');
        Route::delete('eventGroup/{id}', 'EventGroupController@destroy')->name('eventGroup.destroy');

        //event group to course
        Route::post('eventGroupToCourse/getEventGroup', 'EventGroupToCourseController@getEventGroup');
        Route::post('eventGroupToCourse/saveEventGroup', 'EventGroupToCourseController@saveEventGroup');
        Route::get('eventGroupToCourse', 'EventGroupToCourseController@index');

        //term to course (term scheduling & activation/closing)
        Route::post('termToCourse/courseSchedule', 'TermToCourseController@courseSchedule');
        Route::post('termToCourse/getActiveOrClose', 'TermToCourseController@getActiveOrClose');
        Route::get('termToCourse/activationOrClosing', 'TermToCourseController@activationOrClosing');
        Route::post('termToCourse/activeInactive', 'TermToCourseController@activeInactive');
        Route::post('termToCourse/redioAcIn', 'TermToCourseController@redioAcIn');
        Route::post('termToCourse/getTerm', 'TermToCourseController@getTerm');
        Route::post('termToCourse/saveCourse', 'TermToCourseController@saveCourse');
        Route::get('termToCourse', 'TermToCourseController@index');

        //event
        Route::post('event/filter/', 'EventController@filter');
        Route::get('event', 'EventController@index')->name('event.index');
        Route::get('event/create', 'EventController@create')->name('event.create');
        Route::post('event', 'EventController@store')->name('event.store');
        Route::get('event/{id}/edit', 'EventController@edit')->name('event.edit');
        Route::patch('event/{id}', 'EventController@update')->name('event.update');
        Route::delete('event/{id}', 'EventController@destroy')->name('event.destroy');
        Route::post('event/getCheckEntrance', 'EventController@getCheckEntrance');

        //sub event
        Route::post('subEvent/filter/', 'SubEventController@filter');
        Route::get('subEvent', 'SubEventController@index')->name('subEvent.index');
        Route::get('subEvent/create', 'SubEventController@create')->name('subEvent.create');
        Route::post('subEvent', 'SubEventController@store')->name('subEvent.store');
        Route::get('subEvent/{id}/edit', 'SubEventController@edit')->name('subEvent.edit');
        Route::patch('subEvent/{id}', 'SubEventController@update')->name('subEvent.update');
        Route::delete('subEvent/{id}', 'SubEventController@destroy')->name('subEvent.destroy');

        //sub sub event
        Route::post('subSubEvent/filter/', 'SubSubEventController@filter');
        Route::get('subSubEvent', 'SubSubEventController@index')->name('subSubEvent.index');
        Route::get('subSubEvent/create', 'SubSubEventController@create')->name('subSubEvent.create');
        Route::post('subSubEvent', 'SubSubEventController@store')->name('subSubEvent.store');
        Route::get('subSubEvent/{id}/edit', 'SubSubEventController@edit')->name('subSubEvent.edit');
        Route::patch('subSubEvent/{id}', 'SubSubEventController@update')->name('subSubEvent.update');
        Route::delete('subSubEvent/{id}', 'SubSubEventController@destroy')->name('subSubEvent.destroy');

        //sub sub sub event
        Route::post('subSubSubEvent/filter/', 'SubSubSubEventController@filter');
        Route::get('subSubSubEvent', 'SubSubSubEventController@index')->name('subSubSubEvent.index');
        Route::get('subSubSubEvent/create', 'SubSubSubEventController@create')->name('subSubSubEvent.create');
        Route::post('subSubSubEvent', 'SubSubSubEventController@store')->name('subSubSubEvent.store');
        Route::get('subSubSubEvent/{id}/edit', 'SubSubSubEventController@edit')->name('subSubSubEvent.edit');
        Route::patch('subSubSubEvent/{id}', 'SubSubSubEventController@update')->name('subSubSubEvent.update');
        Route::delete('subSubSubEvent/{id}', 'SubSubSubEventController@destroy')->name('subSubSubEvent.destroy');

        //event tree
        Route::post('eventTree/saveEventTree', 'EventTreeController@saveEventTree');
        Route::get('eventTree', 'EventTreeController@index');
        Route::post('eventTree/getPrevEvent', 'EventTreeController@getPrevEvent');
        Route::post('eventTree/getSubEvent', 'EventTreeController@getSubEvent');
        Route::post('eventTree/getSubSubEvent', 'EventTreeController@getSubSubEvent');
        Route::post('eventTree/getSubSubSubEvent', 'EventTreeController@getSubSubSubEvent');


        //grading System Management
        Route::post('gradingSystem/filter/', 'GradingSystemController@filter');
        Route::get('gradingSystem', 'GradingSystemController@index')->name('gradingSystem.index');
        Route::get('gradingSystem/create', 'GradingSystemController@create')->name('gradingSystem.create');
        Route::post('gradingSystem', 'GradingSystemController@store')->name('gradingSystem.store');
        Route::get('gradingSystem/{id}/edit', 'GradingSystemController@edit')->name('gradingSystem.edit');
        Route::patch('gradingSystem/{id}', 'GradingSystemController@update')->name('gradingSystem.update');
        Route::delete('gradingSystem/{id}', 'GradingSystemController@destroy')->name('gradingSystem.destroy');
        Route::post('gradingSystem/getCheckEntrance', 'GradingSystemController@getCheckEntrance');


        //marking Factors Management
        Route::post('markingFactors/filter/', 'MarkingFactorsController@filter');
        Route::get('markingFactors', 'MarkingFactorsController@index')->name('markingFactors.index');
        Route::get('markingFactors/create', 'MarkingFactorsController@create')->name('markingFactors.create');
        Route::post('markingFactors', 'MarkingFactorsController@store')->name('markingFactors.store');
        Route::get('markingFactors/{id}/edit', 'MarkingFactorsController@edit')->name('markingFactors.edit');
        Route::patch('markingFactors/{id}', 'MarkingFactorsController@update')->name('markingFactors.update');
        Route::delete('markingFactors/{id}', 'MarkingFactorsController@destroy')->name('markingFactors.destroy');
        Route::post('markingFactors/getCheckEntrance', 'MarkingFactorsController@getCheckEntrance');



        // mutual Assessment Event Management
        Route::post('mutualAssessmentEvent/filter/', 'MutualAssessmentEventController@filter');
        Route::get('mutualAssessmentEvent', 'MutualAssessmentEventController@index')->name('mutualAssessmentEvent.index');
        Route::get('mutualAssessmentEvent/create', 'MutualAssessmentEventController@create')->name('mutualAssessmentEvent.create');
        Route::post('mutualAssessmentEvent', 'MutualAssessmentEventController@store')->name('mutualAssessmentEvent.store');
        Route::get('mutualAssessmentEvent/{id}/edit', 'MutualAssessmentEventController@edit')->name('mutualAssessmentEvent.edit');
        Route::patch('mutualAssessmentEvent/{id}', 'MutualAssessmentEventController@update')->name('mutualAssessmentEvent.update');
        Route::delete('mutualAssessmentEvent/{id}', 'MutualAssessmentEventController@destroy')->name('mutualAssessmentEvent.destroy');
        Route::post('mutualAssessmentEvent/getCheckEntrance', 'MutualAssessmentEventController@getCheckEntrance');

        //term to MA event
        Route::get('termToMAEvent', 'TermToMAEventController@index');
        Route::post('termToMAEvent/getTerm', 'TermToMAEventController@getTerm');
        Route::post('termToMAEvent/getEvent', 'TermToMAEventController@getEvent');
        Route::post('termToMAEvent/saveTermToMAEvent', 'TermToMAEventController@saveTermToMAEvent');

        //event to sub event
        Route::get('eventToSubEvent', 'EventToSubEventController@index');
        Route::post('eventToSubEvent/getSubEvent', 'EventToSubEventController@getSubEvent');
        Route::post('eventToSubEvent/saveEventToSubEvent', 'EventToSubEventController@saveEventToSubEvent');
        Route::post('eventToSubEvent/getAssignedSubEvent', 'EventToSubEventController@getAssignedSubEvent');

        //event to sub sub event
        Route::get('eventToSubSubEvent', 'EventToSubSubEventController@index');
        Route::post('eventToSubSubEvent/getSubEvent', 'EventToSubSubEventController@getSubEvent');
        Route::post('eventToSubSubEvent/getSubSubEvent', 'EventToSubSubEventController@getSubSubEvent');
        Route::post('eventToSubSubEvent/saveEventToSubSubEvent', 'EventToSubSubEventController@saveEventToSubSubEvent');
        Route::post('eventToSubSubEvent/getAssignedSubSubEvent', 'EventToSubSubEventController@getAssignedSubSubEvent');

        //event to sub sub sub event
        Route::get('eventToSubSubSubEvent', 'EventToSubSubSubEventController@index');
        Route::post('eventToSubSubSubEvent/getSubEvent', 'EventToSubSubSubEventController@getSubEvent');
        Route::post('eventToSubSubSubEvent/getSubSubEvent', 'EventToSubSubSubEventController@getSubSubEvent');
        Route::post('eventToSubSubSubEvent/getSubSubSubEvent', 'EventToSubSubSubEventController@getSubSubSubEvent');
        Route::post('eventToSubSubSubEvent/saveEventToSubSubSubEvent', 'EventToSubSubSubEventController@saveEventToSubSubSubEvent');
        Route::post('eventToSubSubSubEvent/getAssignedSubSubSubEvent', 'EventToSubSubSubEventController@getAssignedSubSubSubEvent');

        //term to event
        Route::get('termToEvent', 'TermToEventController@index');
        Route::post('termToEvent/getTerm', 'TermToEventController@getTerm');
        Route::post('termToEvent/getEvent', 'TermToEventController@getEvent');
        Route::post('termToEvent/saveTermToEvent', 'TermToEventController@saveTermToEvent');
        Route::post('termToEvent/getAssignedEvent', 'TermToEventController@getAssignedEvent');

        //term to sub event
        Route::get('termToSubEvent', 'TermToSubEventController@index');
        Route::post('termToSubEvent/getTerm', 'TermToSubEventController@getTerm');
        Route::post('termToSubEvent/getEvent', 'TermToSubEventController@getEvent');
        Route::post('termToSubEvent/getSubEvent', 'TermToSubEventController@getSubEvent');
        Route::post('termToSubEvent/saveTermToSubEvent', 'TermToSubEventController@saveTermToSubEvent');
        Route::post('termToSubEvent/getAssignedSubEvent', 'TermToSubEventController@getAssignedSubEvent');

        //term to sub sub event
        Route::get('termToSubSubEvent', 'TermToSubSubEventController@index');
        Route::post('termToSubSubEvent/getTerm', 'TermToSubSubEventController@getTerm');
        Route::post('termToSubSubEvent/getEvent', 'TermToSubSubEventController@getEvent');
        Route::post('termToSubSubEvent/getSubEvent', 'TermToSubSubEventController@getSubEvent');
        Route::post('termToSubSubEvent/getSubSubEvent', 'TermToSubSubEventController@getSubSubEvent');
        Route::post('termToSubSubEvent/saveTermToSubSubEvent', 'TermToSubSubEventController@saveTermToSubSubEvent');
        Route::post('termToSubSubEvent/getAssignedSubSubEvent', 'TermToSubSubEventController@getAssignedSubSubEvent');

        //term to sub sub sub event
        Route::get('termToSubSubSubEvent', 'TermToSubSubSubEventController@index');
        Route::post('termToSubSubSubEvent/getTerm', 'TermToSubSubSubEventController@getTerm');
        Route::post('termToSubSubSubEvent/getEvent', 'TermToSubSubSubEventController@getEvent');
        Route::post('termToSubSubSubEvent/getSubEvent', 'TermToSubSubSubEventController@getSubEvent');
        Route::post('termToSubSubSubEvent/getSubSubEvent', 'TermToSubSubSubEventController@getSubSubEvent');
        Route::post('termToSubSubSubEvent/getSubSubSubEvent', 'TermToSubSubSubEventController@getSubSubSubEvent');
        Route::post('termToSubSubSubEvent/saveTermToSubSubSubEvent', 'TermToSubSubSubEventController@saveTermToSubSubSubEvent');
        Route::post('termToSubSubSubEvent/getAssignedSubSubSubEvent', 'TermToSubSubSubEventController@getAssignedSubSubSubEvent');

        //cm to syn
        Route::get('cmToSyn', 'CmToSynController@index');
        Route::post('cmToSyn/getTerm', 'CmToSynController@getTerm');
        Route::post('cmToSyn/getSyn', 'CmToSynController@getSyn');
        Route::post('cmToSyn/getSubSynCm', 'CmToSynController@getSubSynCm');
        Route::post('cmToSyn/getCm', 'CmToSynController@getCm');
        Route::post('cmToSyn/saveCmToSyn', 'CmToSynController@saveCmToSyn');
        Route::post('cmToSyn/assignedCm', 'CmToSynController@assignedCmDetails');
        Route::post('cmToSyn/getAssignedCm', 'CmToSynController@getAssignedCm');

        //cm to sub syn
//        Route::get('cmToSubSyn', 'CmToSubSynController@index');
//        Route::post('cmToSubSyn/getTerm', 'CmToSubSynController@getTerm');
//        Route::post('cmToSubSyn/getSyn', 'CmToSubSynController@getSyn');
//        Route::post('cmToSubSyn/getSubSyn', 'CmToSubSynController@getSubSyn');
//        Route::post('cmToSubSyn/getCm', 'CmToSubSynController@getCm');
//        Route::post('cmToSubSyn/saveCmToSubSyn', 'CmToSubSynController@saveCmToSubSyn');
//        Route::post('cmToSubSyn/assignedCm', 'CmToSubSynController@assignedCmDetails');
//        Route::post('cmToSubSyn/getAssignedCm', 'CmToSubSynController@getAssignedCm');
        //unit
        Route::post('unit/filter', 'UnitController@filter');
        Route::post('unit/getOrder', 'UnitController@getOrder');
        Route::get('unit', 'UnitController@index')->name('unit.index');
        Route::get('unit/create', 'UnitController@create')->name('unit.create');
        Route::post('unit', 'UnitController@store')->name('unit.store');
        Route::get('unit/{id}/edit', 'UnitController@edit')->name('unit.edit');
        Route::patch('unit/{id}', 'UnitController@update')->name('unit.update');
        Route::delete('unit/{id}', 'UnitController@destroy')->name('unit.destroy');

        //formation
        Route::post('formation/filter', 'FormationController@filter');
        Route::post('formation/getOrder', 'FormationController@getOrder');
        Route::get('formation', 'FormationController@index')->name('formation.index');
        Route::get('formation/create', 'FormationController@create')->name('formation.create');
        Route::post('formation', 'FormationController@store')->name('formation.store');
        Route::get('formation/{id}/edit', 'FormationController@edit')->name('formation.edit');
        Route::patch('formation/{id}', 'FormationController@update')->name('formation.update');
        Route::delete('formation/{id}', 'FormationController@destroy')->name('formation.destroy');

        // Mil course
        Route::post('milCourse/close', 'MilCourseController@close');
        Route::post('milCourse/filter', 'MilCourseController@filter');
        Route::get('milCourse', 'MilCourseController@index')->name('milCourse.index');
        Route::get('milCourse/create', 'MilCourseController@create')->name('milCourse.create');
        Route::post('milCourse', 'MilCourseController@store')->name('milCourse.store');
        Route::get('milCourse/{id}/edit', 'MilCourseController@edit')->name('milCourse.edit');
        Route::patch('milCourse/{id}', 'MilCourseController@update')->name('milCourse.update');
        Route::delete('milCourse/{id}', 'MilCourseController@destroy')->name('milCourse.destroy');

        //corps/regt/br
        Route::post('corpsRegtBr/filter', 'CorpsRegtBrController@filter');
        Route::post('corpsRegtBr/getOrder', 'CorpsRegtBrController@getOrder');
        Route::get('corpsRegtBr', 'CorpsRegtBrController@index')->name('corpsRegtBr.index');
        Route::get('corpsRegtBr/create', 'CorpsRegtBrController@create')->name('corpsRegtBr.create');
        Route::post('corpsRegtBr', 'CorpsRegtBrController@store')->name('corpsRegtBr.store');
        Route::get('corpsRegtBr/{id}/edit', 'CorpsRegtBrController@edit')->name('corpsRegtBr.edit');
        Route::patch('corpsRegtBr/{id}', 'CorpsRegtBrController@update')->name('corpsRegtBr.update');
        Route::delete('corpsRegtBr/{id}', 'CorpsRegtBrController@destroy')->name('corpsRegtBr.destroy');

        //decoration
        Route::post('decoration/filter', 'DecorationController@filter');
        Route::post('decoration/getOrder', 'DecorationController@getOrder');
        Route::get('decoration', 'DecorationController@index')->name('decoration.index');
        Route::get('decoration/create', 'DecorationController@create')->name('decoration.create');
        Route::post('decoration', 'DecorationController@store')->name('decoration.store');
        Route::get('decoration/{id}/edit', 'DecorationController@edit')->name('decoration.edit');
        Route::patch('decoration/{id}', 'DecorationController@update')->name('decoration.update');
        Route::delete('decoration/{id}', 'DecorationController@destroy')->name('decoration.destroy');

        //award
        Route::post('award/filter', 'AwardController@filter');
        Route::post('award/getOrder', 'AwardController@getOrder');
        Route::get('award', 'AwardController@index')->name('award.index');
        Route::get('award/create', 'AwardController@create')->name('award.create');
        Route::post('award', 'AwardController@store')->name('award.store');
        Route::get('award/{id}/edit', 'AwardController@edit')->name('award.edit');
        Route::patch('award/{id}', 'AwardController@update')->name('award.update');
        Route::delete('award/{id}', 'AwardController@destroy')->name('award.destroy');

        //hobby
        Route::post('hobby/filter', 'HobbyController@filter');
        Route::post('hobby/getOrder', 'HobbyController@getOrder');
        Route::get('hobby', 'HobbyController@index')->name('hobby.index');
        Route::get('hobby/create', 'HobbyController@create')->name('hobby.create');
        Route::post('hobby', 'HobbyController@store')->name('hobby.store');
        Route::get('hobby/{id}/edit', 'HobbyController@edit')->name('hobby.edit');
        Route::patch('hobby/{id}', 'HobbyController@update')->name('hobby.update');
        Route::delete('hobby/{id}', 'HobbyController@destroy')->name('hobby.destroy');

        //Factor Classification
        Route::post('factorClassification/filter', 'FactorClassificationController@filter');
        Route::post('factorClassification/getOrder', 'FactorClassificationController@getOrder');
        Route::get('factorClassification', 'FactorClassificationController@index')->name('factorClassification.index');
        Route::get('factorClassification/create', 'FactorClassificationController@create')->name('factorClassification.create');
        Route::post('factorClassification', 'FactorClassificationController@store')->name('factorClassification.store');
        Route::get('factorClassification/{id}/edit', 'FactorClassificationController@edit')->name('factorClassification.edit');
        Route::patch('factorClassification/{id}', 'FactorClassificationController@update')->name('factorClassification.update');
        Route::delete('factorClassification/{id}', 'FactorClassificationController@destroy')->name('factorClassification.destroy');

        //event to appt matrix
        Route::get('eventToApptMatrix', 'EventToApptMatrixController@index');
        Route::post('eventToApptMatrix/getTerm', 'EventToApptMatrixController@getTerm');
        Route::post('eventToApptMatrix/getEvent', 'EventToApptMatrixController@getEvent');
        Route::post('eventToApptMatrix/getSubEventApptMatrix', 'EventToApptMatrixController@getSubEventApptMatrix');
        Route::post('eventToApptMatrix/getSubSubEventApptMatrix', 'EventToApptMatrixController@getSubSubEventApptMatrix');
        Route::post('eventToApptMatrix/getSubSubSubEventApptMatrix', 'EventToApptMatrixController@getSubSubSubEventApptMatrix');
        Route::post('eventToApptMatrix/getApptMatrix', 'EventToApptMatrixController@getApptMatrix');
        Route::post('eventToApptMatrix/getAppt', 'EventToApptMatrixController@getAppt');
        Route::post('eventToApptMatrix/saveEventToApptMatrix', 'EventToApptMatrixController@saveEventToApptMatrix');

        //appt to cm
        Route::get('apptToCm', 'ApptToCmController@index');
        Route::post('apptToCm/getTermEvent', 'ApptToCmController@getTermEvent');
        Route::post('apptToCm/getSubEventCmAppt', 'ApptToCmController@getSubEventCmAppt');
        Route::post('apptToCm/getSubSubEventCmAppt', 'ApptToCmController@getSubSubEventCmAppt');
        Route::post('apptToCm/getSubSubSubEventCmAppt', 'ApptToCmController@getSubSubSubEventCmAppt');
        Route::post('apptToCm/getCmAppt', 'ApptToCmController@getCmAppt');
        Route::post('apptToCm/getAppt', 'ApptToCmController@getAppt');
        Route::post('apptToCm/saveApptToCm', 'ApptToCmController@saveApptToCm');
        Route::post('apptToCm/getAssignedAppt', 'ApptToCmController@getAssignedAppt');

        //Criteria wise wt distribution
        Route::get('criteriaWiseWt', 'CriteriaWiseWtController@index');
        Route::post('criteriaWiseWt/getCriteriaWt', 'CriteriaWiseWtController@getCriteriaWt');
        Route::post('criteriaWiseWt/saveCriteriaWt', 'CriteriaWiseWtController@saveCriteriaWt');

        //Event MKS & WT Distribution
        Route::get('eventMksWt', 'EventMksWtController@index');
        Route::post('eventMksWt/getEventMksWt', 'EventMksWtController@getEventMksWt');
        Route::post('eventMksWt/saveEventMksWt', 'EventMksWtController@saveEventMksWt');

        //Sub Event MKS & WT Distribution
        Route::get('subEventMksWt', 'SubEventMksWtController@index');
        Route::post('subEventMksWt/getEvent', 'SubEventMksWtController@getEvent');
        Route::post('subEventMksWt/getSubEventMksWt', 'SubEventMksWtController@getSubEventMksWt');
        Route::post('subEventMksWt/saveSubEventMksWt', 'SubEventMksWtController@saveSubEventMksWt');

        //Sub Sub Event MKS & WT Distribution
        Route::get('subSubEventMksWt', 'SubSubEventMksWtController@index');
        Route::post('subSubEventMksWt/getEvent', 'SubSubEventMksWtController@getEvent');
        Route::post('subSubEventMksWt/getSubEvent', 'SubSubEventMksWtController@getSubEvent');
        Route::post('subSubEventMksWt/getSubSubEventMksWt', 'SubSubEventMksWtController@getSubSubEventMksWt');
        Route::post('subSubEventMksWt/saveSubSubEventMksWt', 'SubSubEventMksWtController@saveSubSubEventMksWt');

        //Sub Sub Sub Event MKS & WT Distribution
        Route::get('subSubSubEventMksWt', 'SubSubSubEventMksWtController@index');
        Route::post('subSubSubEventMksWt/getEvent', 'SubSubSubEventMksWtController@getEvent');
        Route::post('subSubSubEventMksWt/getSubEvent', 'SubSubSubEventMksWtController@getSubEvent');
        Route::post('subSubSubEventMksWt/getSubSubEvent', 'SubSubSubEventMksWtController@getSubSubEvent');
        Route::post('subSubSubEventMksWt/getSubSubSubEventMksWt', 'SubSubSubEventMksWtController@getSubSubSubEventMksWt');
        Route::post('subSubSubEventMksWt/saveSubSubSubEventMksWt', 'SubSubSubEventMksWtController@saveSubSubSubEventMksWt');

        // CI/Comdt Moderation Marking Limit
        Route::get('ciComdtModerationMarkingLimit', 'CiComdtModerationMarkingLimitController@index');
        Route::post('ciComdtModerationMarkingLimit/getMarkingLimit', 'CiComdtModerationMarkingLimitController@getMarkingLimit');
        Route::post('ciComdtModerationMarkingLimit/saveMarkingLimit', 'CiComdtModerationMarkingLimitController@saveMarkingLimit');
    });

    //Event List Report
    Route::get('eventListReport', 'EventListReportController@index');
    Route::post('eventListReport/getCourse', 'EventListReportController@getCourse');
    Route::post('eventListReport/getTerm', 'EventListReportController@getTerm');
    Route::post('eventListReport/filter', 'EventListReportController@eventFilter');

    // event result Report
    Route::get('eventResultReport', 'EventResultReportController@index');
    Route::post('eventResultReport/getCourse', 'EventResultReportController@getCourse');
    Route::post('eventResultReport/getTerm', 'EventResultReportController@getTerm');
    Route::post('eventResultReport/getEvent', 'EventResultReportController@getEvent');
    Route::post('eventResultReport/getSubEventReport', 'EventResultReportController@getSubEventReport');
    Route::post('eventResultReport/getSubSubEventReport', 'EventResultReportController@getSubSubEventReport');
    Route::post('eventResultReport/getSubSubSubEventReport', 'EventResultReportController@getSubSubSubEventReport');
    Route::post('eventResultReport/getsubSyn', 'EventResultReportController@getsubSyn');
    Route::post('eventResultReport/filter', 'EventResultReportController@filter');

    // Start:: DS Access
    Route::group(['middleware' => 'ds'], function() {
        // Event Assessment
        Route::get('eventAssessmentMarking', 'EventAssessmentMarkingController@index');
        Route::post('eventAssessmentMarking/getTermEvent', 'EventAssessmentMarkingController@getTermEvent');
        Route::post('eventAssessmentMarking/getSubEvent', 'EventAssessmentMarkingController@getSubEvent');
        Route::post('eventAssessmentMarking/getSubSubEvent', 'EventAssessmentMarkingController@getSubSubEvent');
        Route::post('eventAssessmentMarking/getSubSubSubEvent', 'EventAssessmentMarkingController@getSubSubSubEvent');
        Route::post('eventAssessmentMarking/showMarkingCmList', 'EventAssessmentMarkingController@showMarkingCmList');
        Route::post('eventAssessmentMarking/saveEventAssessmentMarking', 'EventAssessmentMarkingController@saveEventAssessmentMarking');
        Route::post('eventAssessmentMarking/saveRequestForUnlock', 'EventAssessmentMarkingController@saveRequestForUnlock');
        Route::post('eventAssessmentMarking/getRequestForUnlockModal', 'EventAssessmentMarkingController@getRequestForUnlockModal');
    });
    // End:: DS Access
    // Start:: CI Access
    Route::group(['middleware' => 'ci'], function() {
        //CI Moderation Marking
        Route::get('ciModerationMarking', 'CiModerationMarkingController@index');
        Route::post('ciModerationMarking/getTermEvent', 'CiModerationMarkingController@getTermEvent');
        Route::post('ciModerationMarking/getSubEvent', 'CiModerationMarkingController@getSubEvent');
        Route::post('ciModerationMarking/getSubSubEvent', 'CiModerationMarkingController@getSubSubEvent');
        Route::post('ciModerationMarking/getSubSubSubEvent', 'CiModerationMarkingController@getSubSubSubEvent');
        Route::post('ciModerationMarking/showMarkingCmList', 'CiModerationMarkingController@showMarkingCmList');
        Route::post('ciModerationMarking/saveciModerationMarking', 'CiModerationMarkingController@saveciModerationMarking');
        Route::post('ciModerationMarking/saveRequestForUnlock', 'CiModerationMarkingController@saveRequestForUnlock');
        Route::post('ciModerationMarking/getRequestForUnlockModal', 'CiModerationMarkingController@getRequestForUnlockModal');

        // CI Obsn Marking
        Route::get('ciObsnMarking', 'CiObsnMarkingController@index');
        Route::post('ciObsnMarking/showCmMarkingList', 'CiObsnMarkingController@showCmMarkingList');
        Route::post('ciObsnMarking/saveObsnMarking', 'CiObsnMarkingController@saveObsnMarking');
        Route::post('ciObsnMarking/getRequestForUnlockModal', 'CiObsnMarkingController@getRequestForUnlockModal');
        Route::post('ciObsnMarking/saveRequestForUnlock', 'CiObsnMarkingController@saveRequestForUnlock');
        Route::post('ciObsnMarking/requestCourseSatatusSummary', 'CiObsnMarkingController@requestCourseSatatusSummary');
        Route::post('ciObsnMarking/getDsMarkingSummary', 'CiObsnMarkingController@getDsMarkingSummary');


        //Unlock Event Assessment  
        Route::get('unlockEventAssessment', 'UnlockEventAssessmentController@index');
        Route::post('unlockEventAssessment/unlockRequest', 'UnlockEventAssessmentController@unlock');
        Route::post('unlockEventAssessment/denyRequest', 'UnlockEventAssessmentController@deny');
        Route::post('unlockEventAssessment/filter', 'UnlockEventAssessmentController@filter');
    });
    // End:: CI Access
    // Start:: CI,COMDT Access
    Route::group(['middleware' => 'ciComdt'], function() {
        //Comdt Moderation Marking
        Route::get('comdtModerationMarking', 'ComdtModerationMarkingController@index');
        Route::post('comdtModerationMarking/getTermEvent', 'ComdtModerationMarkingController@getTermEvent');
        Route::post('comdtModerationMarking/getSubEvent', 'ComdtModerationMarkingController@getSubEvent');
        Route::post('comdtModerationMarking/getSubSubEvent', 'ComdtModerationMarkingController@getSubSubEvent');
        Route::post('comdtModerationMarking/getSubSubSubEvent', 'ComdtModerationMarkingController@getSubSubSubEvent');
        Route::post('comdtModerationMarking/showMarkingCmList', 'ComdtModerationMarkingController@showMarkingCmList');
        Route::post('comdtModerationMarking/saveComdtModerationMarking', 'ComdtModerationMarkingController@saveComdtModerationMarking');
        Route::post('comdtModerationMarking/saveRequestForUnlock', 'ComdtModerationMarkingController@saveRequestForUnlock');
        Route::post('comdtModerationMarking/getRequestForUnlockModal', 'ComdtModerationMarkingController@getRequestForUnlockModal');
        Route::post('comdtModerationMarking/getDsMarkingSummary', 'ComdtModerationMarkingController@getDsMarkingSummary');

        // Comdt Obsn Marking
        Route::get('comdtObsnMarking', 'ComdtObsnMarkingController@index');
        Route::post('comdtObsnMarking/showCmMarkingList', 'ComdtObsnMarkingController@showCmMarkingList');
        Route::post('comdtObsnMarking/saveObsnMarking', 'ComdtObsnMarkingController@saveObsnMarking');
        Route::post('comdtObsnMarking/getRequestForUnlockModal', 'ComdtObsnMarkingController@getRequestForUnlockModal');
        Route::post('comdtObsnMarking/saveRequestForUnlock', 'ComdtObsnMarkingController@saveRequestForUnlock');
        Route::post('comdtObsnMarking/requestCourseSatatusSummary', 'ComdtObsnMarkingController@requestCourseSatatusSummary');
        Route::post('comdtObsnMarking/getDsMarkingSummary', 'ComdtObsnMarkingController@getDsMarkingSummary');

        //Unlock CI moderation marking  
        Route::get('unlockCiModerationMarking', 'UnlockCiModerationMarkingController@index');
        Route::post('unlockCiModerationMarking/unlockRequest', 'UnlockCiModerationMarkingController@unlock');
        Route::post('unlockCiModerationMarking/denyRequest', 'UnlockCiModerationMarkingController@deny');
        Route::post('unlockCiModerationMarking/filter', 'UnlockCiModerationMarkingController@filter');

        //Unlock CI obsn marking  
        Route::get('unlockCiObsnMarking', 'UnlockCiObsnMarkingController@index');
        Route::post('unlockCiObsnMarking/unlockRequest', 'UnlockCiObsnMarkingController@unlock');
        Route::post('unlockCiObsnMarking/denyRequest', 'UnlockCiObsnMarkingController@deny');
        Route::post('unlockCiObsnMarking/filter', 'UnlockCiObsnMarkingController@filter');
    });
    // End:: CI,COMDT Access
    // Marking Group Summary Report
    Route::get('markingGroupSummaryReport', 'MarkingGroupSummaryReportController@index');
    Route::post('markingGroupSummaryReport/getCourse', 'MarkingGroupSummaryReportController@getCourse');
    Route::post('markingGroupSummaryReport/getTerm', 'MarkingGroupSummaryReportController@getTerm');
    Route::post('markingGroupSummaryReport/getEvent', 'MarkingGroupSummaryReportController@getEvent');
    Route::post('markingGroupSummaryReport/getSubEventReport', 'MarkingGroupSummaryReportController@getSubEventReport');
    Route::post('markingGroupSummaryReport/getSubSubEventReport', 'MarkingGroupSummaryReportController@getSubSubEventReport');
    Route::post('markingGroupSummaryReport/getSubSubSubEventReport', 'MarkingGroupSummaryReportController@getSubSubSubEventReport');
    Route::post('markingGroupSummaryReport/getsubSyn', 'MarkingGroupSummaryReportController@getsubSyn');
    Route::post('markingGroupSummaryReport/filter', 'MarkingGroupSummaryReportController@filter');

    // Start:: Report Routes
    Route::group(['middleware' => 'superAdminCiComdt'], function() {


        //Unlock Comdt moderation marking  
        Route::get('unlockComdtModerationMarking', 'UnlockComdtModerationMarkingController@index');
        Route::post('unlockComdtModerationMarking/unlockRequest', 'UnlockComdtModerationMarkingController@unlock');
        Route::post('unlockComdtModerationMarking/denyRequest', 'UnlockComdtModerationMarkingController@deny');
        Route::post('unlockComdtModerationMarking/filter', 'UnlockComdtModerationMarkingController@filter');

        //Unlock Comdt obsn marking  
        Route::get('unlockComdtObsnMarking', 'UnlockComdtObsnMarkingController@index');
        Route::post('unlockComdtObsnMarking/unlockRequest', 'UnlockComdtObsnMarkingController@unlock');
        Route::post('unlockComdtObsnMarking/denyRequest', 'UnlockComdtObsnMarkingController@deny');
        Route::post('unlockComdtObsnMarking/filter', 'UnlockComdtObsnMarkingController@filter');

        // Mutual Assessment (Detailed) Report
        Route::get('mutualAssessmentDetailedReport', 'MutualAssessmentDetailedReportController@index');
        Route::post('mutualAssessmentDetailedReport/getCourse', 'MutualAssessmentDetailedReportController@getCourse');
        Route::post('mutualAssessmentDetailedReport/getTerm', 'MutualAssessmentDetailedReportController@getTerm');
        Route::post('mutualAssessmentDetailedReport/getMaEvent', 'MutualAssessmentDetailedReportController@getMaEvent');
        Route::post('mutualAssessmentDetailedReport/getsubSyn', 'MutualAssessmentDetailedReportController@getsubSyn');
        Route::post('mutualAssessmentDetailedReport/filter', 'MutualAssessmentDetailedReportController@filter');

        // Mutual Assessment (Summary) Report
        Route::get('mutualAssessmentSummaryReport', 'MutualAssessmentSummaryReportController@index');
        Route::post('mutualAssessmentSummaryReport/getCourse', 'MutualAssessmentSummaryReportController@getCourse');
        Route::post('mutualAssessmentSummaryReport/getTerm', 'MutualAssessmentSummaryReportController@getTerm');
        Route::post('mutualAssessmentSummaryReport/getMaEvent', 'MutualAssessmentSummaryReportController@getMaEvent');
        Route::post('mutualAssessmentSummaryReport/getsubSyn', 'MutualAssessmentSummaryReportController@getsubSyn');
        Route::post('mutualAssessmentSummaryReport/filter', 'MutualAssessmentSummaryReportController@filter');
    });
    // End:: Report Routes
});
