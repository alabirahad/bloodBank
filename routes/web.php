<?php

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('user');
    } else {
        return view('auth.login');
    }
});
Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', 'HomePageController@index');

    Route::group(['middleware' => 'superAdmin'], function () {
        
        //Home Page
        Route::get('homePage', 'HomePageController@index')->name('homePage.index');
        Route::post('homePage/requestCancel', 'HomePageController@requestCancel');
        Route::post('homePage/requestAccepet', 'HomePageController@requestAccepet');
        Route::post('homePage/requestDonet', 'HomePageController@requestDonet');
        
        //User Group
        Route::post('userGroup/filter', 'UserGroupController@filter');
        Route::post('userGroup/getOrder', 'UserGroupController@getOrder');
        Route::get('userGroup', 'UserGroupController@index')->name('userGroup.index');

        //blood Group
        Route::post('bloodGroup/filter', 'BloodGroupController@filter');
        Route::post('bloodGroup/getOrder', 'BloodGroupController@getOrder');
        Route::get('bloodGroup', 'BloodGroupController@index')->name('bloodGroup.index');

        //user
        Route::post('user/filter/', 'UserController@filter');
        Route::get('user', 'UserController@index')->name('user.index');
        Route::get('user/create', 'UserController@create')->name('user.create');
        Route::post('user', 'UserController@store')->name('user.store');
        Route::get('user/{id}/edit', 'UserController@edit')->name('user.edit');
        Route::patch('user/{id}', 'UserController@update')->name('user.update');
        Route::delete('user/{id}', 'UserController@destroy')->name('user.destroy');
        Route::post('user/getDistrict', 'UserController@getDistrict');
        Route::post('user/getThana', 'UserController@getThana');

        // request blood 
        Route::get('requestBlood', 'RequestBloodController@index');
        Route::post('requestBlood/request', 'RequestBloodController@requestSave');
        
        //available blood
        Route::get('availableBlood', 'AvailableBloodController@index');

       
    });


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
        //deligate CI account to DS
        Route::get('deligateCiAcctToDs', 'DeligateCiAcctToDsController@index');
        Route::post('deligateCiAcctToDs/getDsList', 'DeligateCiAcctToDsController@getDsList');
        Route::post('deligateCiAcctToDs/getDsInfo', 'DeligateCiAcctToDsController@getDsInfo');
        Route::post('deligateCiAcctToDs/setDeligation', 'DeligateCiAcctToDsController@setDeligation');
        Route::post('deligateCiAcctToDs/cancelDeligation', 'DeligateCiAcctToDsController@cancelDeligation');
    });
    // End:: CI Access
    // Start:: deligated Ds,CI Access
    Route::group(['middleware' => 'deligatedDsCi'], function() {
        //CI Moderation Marking
        Route::get('ciModerationMarking', 'CiModerationMarkingController@index');
        Route::post('ciModerationMarking/getTermEvent', 'CiModerationMarkingController@getTermEvent');
        Route::post('ciModerationMarking/getSubEvent', 'CiModerationMarkingController@getSubEvent');
        Route::post('ciModerationMarking/getSubSubEvent', 'CiModerationMarkingController@getSubSubEvent');
        Route::post('ciModerationMarking/getSubSubSubEvent', 'CiModerationMarkingController@getSubSubSubEvent');
        Route::post('ciModerationMarking/showMarkingCmList', 'CiModerationMarkingController@showMarkingCmList');
        Route::post('ciModerationMarking/saveCiModerationMarking', 'CiModerationMarkingController@saveCiModerationMarking');
        Route::post('ciModerationMarking/saveRequestForUnlock', 'CiModerationMarkingController@saveRequestForUnlock');
        Route::post('ciModerationMarking/getRequestForUnlockModal', 'CiModerationMarkingController@getRequestForUnlockModal');
        Route::post('ciModerationMarking/getDsMarkingSummary', 'CiModerationMarkingController@getDsMarkingSummary');

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
    // End:: deligated Ds,CI Access
    // Start:: deligated Ds,CI,COMDT Access
    Route::group(['middleware' => 'deligatedDsCiComdt'], function() {
        //Comdt Moderation Marking
//        Route::get('comdtModerationMarking', 'ComdtModerationMarkingController@index');
//        Route::post('comdtModerationMarking/getTermEvent', 'ComdtModerationMarkingController@getTermEvent');
//        Route::post('comdtModerationMarking/getSubEvent', 'ComdtModerationMarkingController@getSubEvent');
//        Route::post('comdtModerationMarking/getSubSubEvent', 'ComdtModerationMarkingController@getSubSubEvent');
//        Route::post('comdtModerationMarking/getSubSubSubEvent', 'ComdtModerationMarkingController@getSubSubSubEvent');
//        Route::post('comdtModerationMarking/showMarkingCmList', 'ComdtModerationMarkingController@showMarkingCmList');
//        Route::post('comdtModerationMarking/saveComdtModerationMarking', 'ComdtModerationMarkingController@saveComdtModerationMarking');
//        Route::post('comdtModerationMarking/saveRequestForUnlock', 'ComdtModerationMarkingController@saveRequestForUnlock');
//        Route::post('comdtModerationMarking/getRequestForUnlockModal', 'ComdtModerationMarkingController@getRequestForUnlockModal');
//        Route::post('comdtModerationMarking/getDsMarkingSummary', 'ComdtModerationMarkingController@getDsMarkingSummary');
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

        //Unlock Comdt moderation marking  
//        Route::get('unlockComdtModerationMarking', 'UnlockComdtModerationMarkingController@index');
//        Route::post('unlockComdtModerationMarking/unlockRequest', 'UnlockComdtModerationMarkingController@unlock');
//        Route::post('unlockComdtModerationMarking/denyRequest', 'UnlockComdtModerationMarkingController@deny');
//        Route::post('unlockComdtModerationMarking/filter', 'UnlockComdtModerationMarkingController@filter');
        //Unlock Comdt obsn marking  
        Route::get('unlockComdtObsnMarking', 'UnlockComdtObsnMarkingController@index');
        Route::post('unlockComdtObsnMarking/unlockRequest', 'UnlockComdtObsnMarkingController@unlock');
        Route::post('unlockComdtObsnMarking/denyRequest', 'UnlockComdtObsnMarkingController@deny');
        Route::post('unlockComdtObsnMarking/filter', 'UnlockComdtObsnMarkingController@filter');
    });
    // End:: deligated Ds,CI,COMDT Access
    // Start:: Report Routes
    // Nominal Roll Report
    Route::get('nominalRollReport', 'NominalRollReportController@index');
    Route::post('nominalRollReport/getCourse', 'NominalRollReportController@getCourse');
    Route::post('nominalRollReport/getTerm', 'NominalRollReportController@getTerm');
    Route::post('nominalRollReport/filter', 'NominalRollReportController@filter');
    Route::get('nominalRollReport/{id}/profile', 'NominalRollReportController@profile');


    //Event List Report
    Route::get('eventListReport', 'EventListReportController@index');
    Route::post('eventListReport/getCourse', 'EventListReportController@getCourse');
    Route::post('eventListReport/getTerm', 'EventListReportController@getTerm');
    Route::post('eventListReport/filter', 'EventListReportController@eventFilter');

    // Arms & Svc Wise Event Trend Report
    Route::get('armsServiceWiseEventTrendReport', 'ArmsServiceWiseEventTrendReportController@index');
    Route::post('armsServiceWiseEventTrendReport/getCourse', 'ArmsServiceWiseEventTrendReportController@getCourse');
    Route::post('armsServiceWiseEventTrendReport/getCourseWiseArmsServiceEvent', 'ArmsServiceWiseEventTrendReportController@getCourseWiseArmsServiceEvent');
    Route::post('armsServiceWiseEventTrendReport/filter', 'ArmsServiceWiseEventTrendReportController@filter');


    // Wing Wise Event Trend Report
    Route::get('wingWiseEventTrendReport', 'WingWiseEventTrendReportController@index');
    Route::post('wingWiseEventTrendReport/getCourse', 'WingWiseEventTrendReportController@getCourse');
    Route::post('wingWiseEventTrendReport/getCourseWiseWingEvent', 'WingWiseEventTrendReportController@getCourseWiseWingEvent');
    Route::post('wingWiseEventTrendReport/filter', 'WingWiseEventTrendReportController@filter');

    // Commissioning Course Wise Event Trend Report
    Route::get('commissioningCourseWiseEventTrendReport', 'CommissioningCourseWiseEventTrendReportController@index');
    Route::post('commissioningCourseWiseEventTrendReport/getCourse', 'CommissioningCourseWiseEventTrendReportController@getCourse');
    Route::post('commissioningCourseWiseEventTrendReport/getCourseWiseCommissioningCourseEvent', 'CommissioningCourseWiseEventTrendReportController@getCourseWiseCommissioningCourseEvent');
    Route::post('commissioningCourseWiseEventTrendReport/filter', 'CommissioningCourseWiseEventTrendReportController@filter');

    // CM group Wise Event Trend Report
    Route::get('cmGroupWiseEventTrendReport', 'CmGroupWiseEventTrendReportController@index');
    Route::post('cmGroupWiseEventTrendReport/getCourse', 'CmGroupWiseEventTrendReportController@getCourse');
    Route::post('cmGroupWiseEventTrendReport/getCourseWiseCmGroupEvent', 'CmGroupWiseEventTrendReportController@getCourseWiseCmGroupEvent');
    Route::post('cmGroupWiseEventTrendReport/filter', 'CmGroupWiseEventTrendReportController@filter');

    // Arms & Svc Wise Sub Event Trend Report
    Route::get('armsServiceWiseSubEventTrendReport', 'ArmsServiceWiseSubEventTrendReportController@index');
    Route::post('armsServiceWiseSubEventTrendReport/getCourse', 'ArmsServiceWiseSubEventTrendReportController@getCourse');
    Route::post('armsServiceWiseSubEventTrendReport/getCourseWiseArmsServiceEvent', 'ArmsServiceWiseSubEventTrendReportController@getCourseWiseArmsServiceEvent');
    Route::post('armsServiceWiseSubEventTrendReport/getSubEvent', 'ArmsServiceWiseSubEventTrendReportController@getSubEvent');
    Route::post('armsServiceWiseSubEventTrendReport/filter', 'ArmsServiceWiseSubEventTrendReportController@filter');


    // Wing Wise Sub Event Trend Report
    Route::get('wingWiseSubEventTrendReport', 'WingWiseSubEventTrendReportController@index');
    Route::post('wingWiseSubEventTrendReport/getCourse', 'WingWiseSubEventTrendReportController@getCourse');
    Route::post('wingWiseSubEventTrendReport/getCourseWiseWingEvent', 'WingWiseSubEventTrendReportController@getCourseWiseWingEvent');
    Route::post('wingWiseSubEventTrendReport/getSubEvent', 'WingWiseSubEventTrendReportController@getSubEvent');
    Route::post('wingWiseSubEventTrendReport/filter', 'WingWiseSubEventTrendReportController@filter');

    // Commissioning Course Wise Sub Event Trend Report
    Route::get('commissioningCourseWiseSubEventTrendReport', 'CommissioningCourseWiseSubEventTrendReportController@index');
    Route::post('commissioningCourseWiseSubEventTrendReport/getCourse', 'CommissioningCourseWiseSubEventTrendReportController@getCourse');
    Route::post('commissioningCourseWiseSubEventTrendReport/getCourseWiseCommissioningCourseEvent', 'CommissioningCourseWiseSubEventTrendReportController@getCourseWiseCommissioningCourseEvent');
    Route::post('commissioningCourseWiseSubEventTrendReport/getSubEvent', 'CommissioningCourseWiseSubEventTrendReportController@getSubEvent');
    Route::post('commissioningCourseWiseSubEventTrendReport/filter', 'CommissioningCourseWiseSubEventTrendReportController@filter');

    // CM group Wise Sub Event Trend Report
    Route::get('cmGroupWiseSubEventTrendReport', 'CmGroupWiseSubEventTrendReportController@index');
    Route::post('cmGroupWiseSubEventTrendReport/getCourse', 'CmGroupWiseSubEventTrendReportController@getCourse');
    Route::post('cmGroupWiseSubEventTrendReport/getCourseWiseCmGroupEvent', 'CmGroupWiseSubEventTrendReportController@getCourseWiseCmGroupEvent');
    Route::post('cmGroupWiseSubEventTrendReport/getSubEvent', 'CmGroupWiseSubEventTrendReportController@getSubEvent');
    Route::post('cmGroupWiseSubEventTrendReport/filter', 'CmGroupWiseSubEventTrendReportController@filter');

    Route::group(['middleware' => 'dsCiSuperAdmin'], function() {
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

        // event result combined Report
        Route::get('eventResultCombinedReport', 'EventResultCombinedReportController@index');
        Route::post('eventResultCombinedReport/getCourse', 'EventResultCombinedReportController@getCourse');
        Route::post('eventResultCombinedReport/filter', 'EventResultCombinedReportController@filter');

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
    // term Report
    Route::get('termResultReport', 'TermResultReportController@index');
    Route::post('termResultReport/getCourse', 'TermResultReportController@getCourse');
    Route::post('termResultReport/getTerm', 'TermResultReportController@getTerm');
    Route::post('termResultReport/filter', 'TermResultReportController@filter');

    // Performace Analysis
    Route::get('performanceAnalysisReport', 'PerformanceAnalysisReportController@index');
    Route::post('performanceAnalysisReport/getCourse', 'PerformanceAnalysisReportController@getCourse');
    Route::post('performanceAnalysisReport/getTerm', 'PerformanceAnalysisReportController@getTerm');
    Route::post('performanceAnalysisReport/getCm', 'PerformanceAnalysisReportController@getCm');
    Route::post('performanceAnalysisReport/filter', 'PerformanceAnalysisReportController@filter');



    // course Progressive Result Report
    Route::get('courseProgressiveResultReport', 'CourseProgressiveResultReportController@index');
    Route::post('courseProgressiveResultReport/getCourse', 'CourseProgressiveResultReportController@getCourse');
    Route::post('courseProgressiveResultReport/getTerm', 'CourseProgressiveResultReportController@getTerm');
    Route::post('courseProgressiveResultReport/filter', 'CourseProgressiveResultReportController@filter');

    // course Result Report
    Route::get('courseResultReport', 'CourseResultReportController@index');
    Route::post('courseResultReport/getCourse', 'CourseResultReportController@getCourse');
    Route::post('courseResultReport/filter', 'CourseResultReportController@filter');

    // Individual Profile Report
    Route::get('individualProfileReport', 'IndividualProfileReportController@index');
    Route::post('individualProfileReport/getCourse', 'IndividualProfileReportController@getCourse');
    Route::post('individualProfileReport/getCm', 'IndividualProfileReportController@getCm');
    Route::post('individualProfileReport/filter', 'IndividualProfileReportController@filter');
    Route::get('individualProfileReport/{id}/profile', 'IndividualProfileReportController@profile');


    // End:: Report Routes
});
