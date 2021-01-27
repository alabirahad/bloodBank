<?php

namespace App\Http\Controllers;

use Validator;
use App\User; //model class
use App\CmBasicProfile; //model class
use App\UserGroup; //model class
use App\Rank; //model class
use App\CmAppointment; //model class
use App\Wing; //model class
use App\Unit; //model class
use App\Course; //model class
use App\CommissioningCourse; //model class
use App\Religion; //model class
use App\ArmsService; //model class
use App\Appointment; //model class
use App\Formation; //model class
use App\CmBrotherSister; //model class
use App\CmOthers; //model class
use App\Country; //model class
use App\Division; //model class
use App\District; //model class
use App\Thana; //model class
use App\CmPermanentAddress; //model class
use App\CmCivilEducation; //model class
use App\CmServiceRecord; //model class
use App\CmAwardRecord; //model class
use App\CmPunishmentRecord; //model class
use App\CmRelativeInDefence; //model class
use App\CmNextKin; //model class
use App\CmMedicalDetails; //model class
use App\CmWinterCollectiveTraining; //model class
use Session;
use Response;
use Redirect;
use Auth;
use File;
use PDF;
use URL;
use Hash;
use Common;
use DB;
use Helper;
use Illuminate\Http\Request;

class CmController extends Controller {

    public function __construct() {
        
    }

    public function index(Request $request) {
        $nameArr = CmBasicProfile::select('full_name')->where('status', '1')->get();

        //passing param for custom function

        $qpArr = $request->all();
//        $userPermissionArr = ['1' => ['1'], //AHQ Observer
//            '3' => ['1', '2', '3', '4', '5', '6', '7', '8'], //SuperAdmin  
//            '5' => ['6', '7', '8'], //admin
//        ];


        $rankList = array('0' => __('label.SELECT_RANK_OPT')) + Rank::orderBy('order', 'asc')
                        ->where('status', '1')
                        ->where('rank.for_course_member', '1')
                        ->pluck('code', 'id')->toArray();
        $wingList = array('0' => __('label.SELECT_WING_OPT')) + Wing::orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $courseList = array('0' => __('label.SELECT_COURSE_OPT')) + Course::orderBy('training_year_id', 'desc')
                        ->orderBy('id', 'desc')
                        ->pluck('name', 'id')->toArray();
        $commissioningCourseList = array('0' => __('label.SELECT_COMMISSIONING_COURSE_OPT')) + CommissioningCourse::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $commissionTypeList = Common::getCommissionType();
        $bloodGroupList = array('0' => __('label.SELECT_BLOOD_GROUP_OPT')) + Common::getBloodGroup();
        $targetArr = CmBasicProfile::join('rank', 'rank.id', '=', 'cm_basic_profile.rank_id')
                ->join('wing', 'wing.id', '=', 'cm_basic_profile.wing_id')
                ->join('arms_service', 'arms_service.id', '=', 'cm_basic_profile.arms_service_id')
                ->join('course', 'course.id', '=', 'cm_basic_profile.course_id')
                ->join('commissioning_course', 'commissioning_course.id', '=', 'cm_basic_profile.commissioning_course_id')
                ->select('cm_basic_profile.id', 'cm_basic_profile.full_name', 'cm_basic_profile.official_name'
                , 'cm_basic_profile.photo', 'cm_basic_profile.personal_no', 'cm_basic_profile.status'
                , 'course.name as course', 'cm_basic_profile.wing_id', 'rank.code as rank'
                , 'wing.name as wing', 'arms_service.code as arms_service'
                , 'commissioning_course.name as commissioning_course', 'cm_basic_profile.email'
                        , 'cm_basic_profile.number');

//        echo $targetArr->count();exit;
        //begin filtering
        $searchText = $request->fil_search;

        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('cm_basic_profile.full_name', 'LIKE', '%' . $searchText . '%');
            });
        }

        if (!empty($request->fil_wing_id)) {
            $targetArr = $targetArr->where('cm_basic_profile.wing_id', '=', $request->fil_wing_id);
        }

        if (!empty($request->fil_rank_id)) {
            $targetArr = $targetArr->where('cm_basic_profile.rank_id', '=', $request->fil_rank_id);
        }
//
//        if (!empty($request->fil_appointment_id)) {
//            $targetArr = $targetArr->where('users.appointment_id', '=', $request->fil_appointment_id);
//        }
        if (!empty($request->fil_course_id)) {
            $targetArr = $targetArr->where('cm_basic_profile.course_id', '=', $request->fil_course_id);
        }
        if (!empty($request->fil_commissioning_course_id)) {
            $targetArr = $targetArr->where('cm_basic_profile.commissioning_course_id', '=', $request->fil_commissioning_course_id);
        }
        //end filtering
        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/cm?page=' . $page);
        }


        return view('cm.index')->with(compact('qpArr', 'targetArr', 'rankList'
                                , 'nameArr', 'wingList', 'courseList', 'commissioningCourseList'
                                , 'commissionTypeList', 'bloodGroupList'));
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();

//        $userNameArr = User::select('full_name')->where('group_id', 7)->where('status', '1')->get();
        //passing param for custom function
//        $userPermissionArr = ['1' => ['1'], //AHQ Observer
//            '3' => ['1', '2', '3', '4', '5', '6', '7', '8'], //SuperAdmin  
//            '5' => ['6', '7', '8'], //admin
//        ];

        $wingList = array('0' => __('label.SELECT_WING_OPT')) + Wing::orderBy('order', 'asc')->pluck('name', 'id')->toArray();

        $rankList = array('0' => __('label.SELECT_RANK_OPT')) + Rank::join('wing', 'wing.id', '=', 'rank.wing_id')
                        ->where('rank.wing_id', old('wing_id'))
                        ->where('rank.status', '1')
                        ->where('rank.for_course_member', '1')
                        ->pluck('rank.name', 'rank.id')->toArray();


        $courseList = array('0' => __('label.SELECT_COURSE_OPT')) + Course::orderBy('training_year_id', 'desc')
                        ->orderBy('id', 'desc')
                        ->pluck('name', 'id')->toArray();
        $armsServiceList = array('0' => __('label.SELECT_ARMS_SERVICE_OPT')) + ArmsService::pluck('name', 'id')->toArray();
        $religionList = array('0' => __('label.SELECT_RELIGION_OPT')) + Religion::pluck('name', 'id')->toArray();
        $commissioningCourseList = array('0' => __('label.SELECT_COMMISSIONING_COURSE_OPT')) + CommissioningCourse::orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();
        $commissionTypeList = Common::getCommissionType();

        $bloodGroupList = array('0' => __('label.SELECT_BLOOD_GROUP_OPT')) + Common::getBloodGroup();

        return view('cm.create')->with(compact('qpArr', 'rankList', 'wingList'
                                , 'courseList', 'commissioningCourseList'
                                , 'armsServiceList', 'religionList', 'commissionTypeList'
                                , 'bloodGroupList'));
    }

    public function store(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();


        $pageNumber = $qpArr['filter'];

        $rules = [
            'course_id' => 'required|not_in:0',
            'wing_id' => 'required|not_in:0',
            'rank_id' => 'required|not_in:0',
            'personal_no' => 'required',
            'full_name' => 'required',
            'official_name' => 'required',
            'father_name' => 'required',
            'arms_service_id' => 'required|not_in:0',
            'commissioning_course_id' => 'required|not_in:0',
            'commission_type' => 'required|not_in:0',
            'commisioning_date' => 'required',
            'date_of_birth' => 'required',
            'birth_place' => 'required',
            'religion_id' => 'required|not_in:0',
            'email' => 'required|email',
            'number' => 'required',
        ];

        if (!empty($request->photo)) {
            $rules['photo'] = 'max:1024|mimes:jpeg,png,jpg';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('cm/create')
                            ->withInput($request->except('photo', 'password', 'conf_password'))
                            ->withErrors($validator);
        }

        //file upload
        $file = $request->file('photo');
        if (!empty($file)) {
            $fileName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $uploadSuccess = $file->move('public/uploads/cm', $fileName);
        }

        $target = new CmBasicProfile;

        $target->course_id = $request->course_id;
        $target->wing_id = $request->wing_id;
        $target->rank_id = $request->rank_id;
        $target->personal_no = $request->personal_no;
        $target->full_name = $request->full_name;
        $target->official_name = $request->official_name;
        $target->father_name = $request->father_name;
        $target->status = $request->status;
        $target->photo = !empty($fileName) ? $fileName : '';


        $target->arms_service_id = $request->arms_service_id;

        $target->commission_type = $request->commission_type;
        $target->commissioning_course_id = $request->commissioning_course_id;
        $target->ante_date_seniority = $request->ante_date_seniority ?? null;
        $target->commisioning_date = Helper::dateFormatConvert($request->commisioning_date);

        $target->date_of_birth = Helper::dateFormatConvert($request->date_of_birth);
        $target->birth_place = $request->birth_place;
        $target->religion_id = $request->religion_id;
        $target->email = $request->email;
        $target->number = $request->number;
        $target->blood_group = $request->blood_group;

        if ($target->save()) {
            Session::flash('success', __('label.CM_CREATED_SUCCESSFULLY'));
            return redirect('cm');
        } else {
            Session::flash('error', __('label.CM_COULD_NOT_BE_CREATED'));
            return redirect('cm/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {

        $qpArr = $request->all();
        $target = CmBasicProfile::find($id);

        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('cm');
        }

        $cm = CmBasicProfile::select('course_id', 'commissioning_course_id')->where('id', $id)->first();
        //passing param for custom function
        $wingId = !empty(old('wing_id')) ? old('wing_id') : $target->wing_id;
        $rankList = array('0' => __('label.SELECT_RANK_OPT')) + Rank::join('wing', 'wing.id', '=', 'rank.wing_id')
                        ->where('rank.wing_id', $wingId)
                        ->where('rank.status', '1')
                        ->where('rank.for_course_member', '1')
                        ->pluck('rank.name', 'rank.id')->toArray();

        $wingList = array('0' => __('label.SELECT_WING_OPT')) + Wing::orderBy('order', 'asc')->pluck('name', 'id')->toArray();
        $courseList = array('0' => __('label.SELECT_COURSE_OPT')) + Course::orderBy('training_year_id', 'desc')
                        ->orderBy('id', 'desc')
                        ->pluck('name', 'id')->toArray();
        $armsServiceList = array('0' => __('label.SELECT_ARMS_SERVICE_OPT')) + ArmsService::pluck('name', 'id')->toArray();
        $religionList = array('0' => __('label.SELECT_RELIGION_OPT')) + Religion::pluck('name', 'id')->toArray();
        $commissioningCourseList = array('0' => __('label.SELECT_COMMISSIONING_COURSE_OPT')) + CommissioningCourse::orderBy('name', 'asc')
                        ->pluck('name', 'id')->toArray();
        $commissionTypeList = Common::getCommissionType();

        $bloodGroupList = array('0' => __('label.SELECT_BLOOD_GROUP_OPT')) + Common::getBloodGroup();

        return view('cm.edit')->with(compact('target', 'qpArr', 'cm', 'rankList'
                                , 'wingList', 'courseList', 'commissioningCourseList'
                                , 'armsServiceList', 'religionList', 'commissionTypeList'
                                , 'bloodGroupList'));
    }

    public function update(Request $request, $id) {
        $target = CmBasicProfile::find($id);
        $previousFileName = $target->photo;

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //end back same page after update

        $rules = [
            'course_id' => 'required|not_in:0',
            'wing_id' => 'required|not_in:0',
            'rank_id' => 'required|not_in:0',
            'personal_no' => 'required',
            'full_name' => 'required',
            'official_name' => 'required',
            'father_name' => 'required',
            'arms_service_id' => 'required|not_in:0',
            'commissioning_course_id' => 'required|not_in:0',
            'commission_type' => 'required|not_in:0',
            'commisioning_date' => 'required',
            'date_of_birth' => 'required',
            'birth_place' => 'required',
            'religion_id' => 'required|not_in:0',
            'email' => 'required|email',
            'number' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if (!empty($request->photo)) {
            $validator->photo = 'max:1024|mimes:jpeg,png,gif,jpg';
        }

        if ($validator->fails()) {
            return redirect('cm/' . $id . '/edit' . $pageNumber)
                            ->withInput($request->all)
                            ->withErrors($validator);
        }

        if (!empty($request->photo)) {
            $prevfileName = 'public/uploads/cm/' . $target->photo;

            if (File::exists($prevfileName)) {
                File::delete($prevfileName);
            }
        }

        $file = $request->file('photo');
        if (!empty($file)) {
            $fileName = uniqid() . "_" . Auth::user()->id . "." . $file->getClientOriginalExtension();
            $uploadSuccess = $file->move('public/uploads/cm', $fileName);
//            echo '<pre>';print_r($fileName);exit;
        }
        $target->course_id = $request->course_id;
        $target->wing_id = $request->wing_id;
        $target->rank_id = $request->rank_id;
        $target->personal_no = $request->personal_no;
        $target->full_name = $request->full_name;
        $target->official_name = $request->official_name;
        $target->father_name = $request->father_name;
        $target->status = $request->status;
        $target->photo = !empty($fileName) ? $fileName : $previousFileName;


        $target->arms_service_id = $request->arms_service_id;

        $target->commission_type = $request->commission_type;
        $target->commissioning_course_id = $request->commissioning_course_id;
        $target->ante_date_seniority = $request->ante_date_seniority ?? null;
        $target->commisioning_date = Helper::dateFormatConvert($request->commisioning_date);

        $target->date_of_birth = Helper::dateFormatConvert($request->date_of_birth);
        $target->birth_place = $request->birth_place;
        $target->religion_id = $request->religion_id;
        $target->email = $request->email;
        $target->number = $request->number;
        $target->blood_group = $request->blood_group;


        if ($target->save()) {
            CmBasicProfile::where('id', $id);
            Session::flash('success', __('label.CM_UPDATED_SUCCESSFULLY'));
            return redirect('cm');
        } else {
            Session::flash('error', __('label.CM_COULD_NOT_BE_UPDATED'));
            return redirect('cm/create' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {

        $target = CmBasicProfile::find($id);


        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page=' . $qpArr['page'] : '?page=';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        $dependencyArr = [
            //administrativs dependancyArr
            'Rank' => ['1' => 'created_by', '2' => 'updated_by'],
            'Appointment' => ['1' => 'created_by', '2' => 'updated_by'],
            'TrainingYear' => ['1' => 'created_by', '2' => 'updated_by'],
            'ArmsService' => ['1' => 'created_by', '2' => 'updated_by'],
            'Wing' => ['1' => 'created_by', '2' => 'updated_by'],
            'Term' => ['1' => 'created_by', '2' => 'updated_by'],
//            'Trade' => ['1' => 'created_by', '2' => 'updated_by'],
//            'Module' => ['1' => 'created_by', '2' => 'updated_by'],
//            'Subject' => ['1' => 'created_by', '2' => 'updated_by'],
//            'Event' => ['1' => 'created_by', '2' => 'updated_by'],
//            'MajorEvent' => ['1' => 'created_by', '2' => 'updated_by'],
//            'WingToBatch' => ['1' => 'updated_by'],
//            'CiObservationMarkingLock' => ['1' => 'locked_by'],
//            'CiToWing' => ['1' => 'ci_id', '2' => 'updated_by'],
//            'CourseReport' => ['1' => 'created_by'],
//            'DropCategory' => ['1' => 'created_by', '2' => 'updated_by'],
//            'EventMarkingLock' => ['1' => 'locked_by'],
//            'EventWtDistr' => ['1' => 'updated_by'],
//            'Marking' => ['1' => 'updated_by'],
//            'ModuleWtDistr' => ['1' => 'updated_by'],
//            'ObservationMarking' => ['1' => 'updated_by'],
//            'ObservationMarkingLock' => ['1' => 'oic_locked_by', '2' => 'ci_locked_by'],
//            'ObservationWtDistr' => ['1' => 'updated_by'],
//            'ObservationMarkingLock' => ['1' => 'oic_locked_by', '2' => 'ci_locked_by'],
//            'Particular' => ['1' => 'created_by', '2' => 'updated_by'],
            'Syndicate' => ['1' => 'created_by', '2' => 'updated_by'],
//            'ParticularMarkingLock' => ['1' => 'locked_by'],
//            'ParticularWtDistr' => ['1' => 'updated_by'],
            'SynToCourse' => ['1' => 'updated_by'],
//            'RctState' => ['1' => 'created_by', '2' => 'unlock_request_by'],
            'Course' => ['1' => 'created_by', '2' => 'updated_by'],
//            'RecruitToPlatoon' => ['1' => 'updated_by'],
//            'RecruitToTrade' => ['1' => 'updated_by'],
//            'SubjectWtDistr' => ['1' => 'updated_by'],
//            'TermToCourse' => ['1' => 'updated_by'],
            'TermToEvent' => ['1' => 'updated_by'],
//            'TermToParticular' => ['1' => 'updated_by'],
        ];

        $fileName = 'public/uploads/user/' . $target->photo;
        if (File::exists($fileName)) {
            File::delete($fileName);
        }

        if ($target->delete()) {
            Session::flash('success', __('label.CM_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.CM_COULD_NOT_BE_DELETED'));
        }
        return redirect('cm' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'fil_search=' . $request->fil_search . '&fil_wing_id=' . $request->fil_wing_id
                . '&fil_rank_id=' . $request->fil_rank_id . '&fil_course_id=' . $request->fil_course_id;
        return Redirect::to('cm?' . $url);
    }

    public function getRank(Request $request) {
        $rankList = Rank::orderBy('id', 'asc');
        if ((!empty($request->index_id) && !empty($request->wing_id)) || empty($request->index_id)) {
            $rankList = $rankList->where('wing_id', $request->wing_id)->where('rank.for_course_member', '1');
        }
        $rankList = $rankList->pluck('code', 'id')->toArray();
        $rankList = ['0' => __('label.SELECT_RANK_OPT')] + $rankList;

        $html = view('cm.showRank', compact('rankList'))->render();
        return response()->json(['html' => $html]);
    }

    public function profile(Request $request, $id) {
//        echo '<pre>';        print_r($id); exit;

        $cmInfoData = CmBasicProfile::leftJoin('rank', 'rank.id', '=', 'cm_basic_profile.rank_id')
                ->leftJoin('course', 'course.id', '=', 'cm_basic_profile.course_id')
                ->leftJoin('wing', 'wing.id', '=', 'cm_basic_profile.wing_id')
                ->leftJoin('unit', 'unit.id', '=', 'cm_basic_profile.unit_id')
                ->leftJoin('formation', 'formation.id', '=', 'cm_basic_profile.formation_id')
                ->leftJoin('cm_appointment', 'cm_appointment.id', '=', 'cm_basic_profile.appointment_id')
                ->leftJoin('arms_service', 'arms_service.id', '=', 'cm_basic_profile.arms_service_id')
                ->leftJoin('commissioning_course', 'commissioning_course.id', '=', 'cm_basic_profile.commissioning_course_id')
                ->leftJoin('religion', 'religion.id', '=', 'cm_basic_profile.religion_id')
                ->select('cm_basic_profile.id as cm_basic_profile_id', 'cm_basic_profile.email'
                        , 'cm_basic_profile.photo', 'cm_basic_profile.number', 'cm_basic_profile.full_name'
                        , 'cm_basic_profile.official_name'
                        , DB::raw("CONCAT(rank.code, ' ', cm_basic_profile.full_name, ' (', cm_basic_profile.official_name, ')') as cm_name")
                        , 'cm_basic_profile.appointment_id as cm_appointment_id', 'course.name as course_name'
                        , 'arms_service.name as arms_service_name', 'commissioning_course.name as commissioning_course_name'
                        , 'unit.name as unit_name', 'religion.name as religion_name'
                        , 'formation.name as formation_name', 'cm_basic_profile.*'
                )
                ->where('cm_basic_profile.status', '1')
                ->where('cm_basic_profile.id', $id)
                ->first();

        $brotherSisterInfoData = CmBrotherSister::where('cm_basic_profile_id', !empty($cmInfoData->cm_basic_profile_id) ? $cmInfoData->cm_basic_profile_id : 0)
                ->select('id', 'cm_basic_profile_id', 'brother_sister_info')
                ->first();

        $civilEducationInfoData = CmCivilEducation::where('cm_basic_profile_id', !empty($cmInfoData->cm_basic_profile_id) ? $cmInfoData->cm_basic_profile_id : 0)
                ->select('id', 'cm_basic_profile_id', 'civil_education_info')
                ->first();

        $serviceRecordInfoData = CmServiceRecord::where('cm_basic_profile_id', !empty($cmInfoData->cm_basic_profile_id) ? $cmInfoData->cm_basic_profile_id : 0)
                ->select('id', 'cm_basic_profile_id', 'service_record_info')
                ->first();

        $awardRecordInfoData = CmAwardRecord::where('cm_basic_profile_id', !empty($cmInfoData->cm_basic_profile_id) ? $cmInfoData->cm_basic_profile_id : 0)
                ->select('id', 'cm_basic_profile_id', 'award_record_info')
                ->first();

        $punishmentRecordInfoData = CmPunishmentRecord::where('cm_basic_profile_id', !empty($cmInfoData->cm_basic_profile_id) ? $cmInfoData->cm_basic_profile_id : 0)
                ->select('id', 'cm_basic_profile_id', 'punishment_record_info')
                ->first();

        $defenceRelativeInfoData = CmRelativeInDefence::where('cm_basic_profile_id', !empty($cmInfoData->cm_basic_profile_id) ? $cmInfoData->cm_basic_profile_id : 0)
                ->select('id', 'cm_basic_profile_id', 'cm_relative_info')
                ->first();

        $othersInfoData = CmOthers::where('cm_basic_profile_id', !empty($cmInfoData->cm_basic_profile_id) ? $cmInfoData->cm_basic_profile_id : 0)
                ->select('id', 'cm_basic_profile_id', 'visited_countries_id', 'special_quality', 'professional_computer', 'swimming')
                ->first();

        $religionList = ['0' => __('label.SELECT_RELIGION_OPT')] + Religion::pluck('name', 'id')->toArray();
        $appointmentList = ['0' => __('label.SELECT_APPT_OPT')] + CmAppointment::pluck('code', 'id')->toArray();
        $allAppointmentList = ['0' => __('label.SELECT_APPT_OPT')] + Appointment::pluck('code', 'id')->toArray();
        $armsServiceList = ['0' => __('label.SELECT_ARMS_SERVICE_OPT')] + ArmsService::pluck('code', 'id')->toArray();
        $unitList = ['0' => __('label.SELECT_UNIT_OPT')] + Unit::pluck('code', 'id')->toArray();
        $formationList = ['0' => __('label.SELECT_FORMATION_OPT')] + Formation::pluck('code', 'id')->toArray();
        $maritalStatusList = ['0' => __('label.SELECT_MARITAL_STATUS_OPT')] + Helper::getMaritalStatus();
        $swimmingList = ['0' => __('label.SELECT_SWIMMER_OPT')] + Helper::getSwimming();
        $countriesVisitedList = Country::pluck('name', 'id')->toArray();
        $courseList = ['0' => __('label.SELECT_COURSE_OPT')] + Course::pluck('name', 'id')->toArray();

        //Division District Thana for cm permanent address
        $addressInfo = CmPermanentAddress::where('cm_basic_profile_id', !empty($cmInfoData->cm_basic_profile_id) ? $cmInfoData->cm_basic_profile_id : '0')
                ->select('id', 'cm_basic_profile_id', 'division_id', 'district_id', 'thana_id', 'address_details')
                ->first();


        $divisionList = ['0' => __('label.SELECT_DIVISION_OPT')] + Division::pluck('name', 'id')->toArray();
        $districtList = ['0' => __('label.SELECT_DISTRICT_OPT')] + District::where('division_id', !empty($addressInfo->division_id) ? $addressInfo->division_id : 0)
                        ->pluck('name', 'id')->toArray();
        $thanaList = ['0' => __('label.SELECT_THANA_OPT')] + Thana::where('district_id', !empty($addressInfo->district_id) ? $addressInfo->district_id : 0)
                        ->pluck('name', 'id')->toArray();

        //Division District Thana for next kin
        $nextKinAddressInfo = CmNextKin::where('cm_basic_profile_id', !empty($cmInfoData->cm_basic_profile_id) ? $cmInfoData->cm_basic_profile_id : '0')
                ->select('id', 'cm_basic_profile_id', 'name', 'relation', 'division_id', 'district_id', 'thana_id', 'address_details')
                ->first();
        $nextKinDistrictList = ['0' => __('label.SELECT_DISTRICT_OPT')] + District::where('division_id', !empty($nextKinAddressInfo->division_id) ? $nextKinAddressInfo->division_id : 0)->pluck('name', 'id')->toArray();
        $nextKinThanaList = ['0' => __('label.SELECT_THANA_OPT')] + Thana::where('district_id', !empty($nextKinAddressInfo->district_id) ? $nextKinAddressInfo->district_id : 0)->pluck('name', 'id')->toArray();

        $cmMedicalDetails = CmMedicalDetails::where('cm_basic_profile_id', !empty($cmInfoData->cm_basic_profile_id) ? $cmInfoData->cm_basic_profile_id : '0')
                ->select('id', 'cm_basic_profile_id', 'category', 'blood_group', 'date_of_birth', 'ht_ft', 'ht_inch', 'weight', 'over_under_weight', 'any_disease')
                ->first();

        $cmWinterTrainingInfoData = CmWinterCollectiveTraining::where('cm_basic_profile_id', !empty($cmInfoData->cm_basic_profile_id) ? $cmInfoData->cm_basic_profile_id : '0')
                ->select('id', 'cm_basic_profile_id', 'participated_no', 'training_info')
                ->first();

        return view('cm.details.index')->with(compact('cmInfoData', 'religionList', 'appointmentList', 'allAppointmentList', 'armsServiceList'
                                , 'unitList', 'formationList', 'maritalStatusList', 'brotherSisterInfoData', 'countriesVisitedList'
                                , 'swimmingList', 'othersInfoData', 'addressInfo', 'divisionList', 'districtList', 'thanaList'
                                , 'civilEducationInfoData', 'serviceRecordInfoData', 'awardRecordInfoData', 'punishmentRecordInfoData'
                                , 'defenceRelativeInfoData', 'courseList', 'nextKinAddressInfo', 'nextKinDistrictList', 'nextKinThanaList'
                                , 'cmMedicalDetails', 'cmWinterTrainingInfoData')
        );
    }

    public function updateFamilyInfo(Request $request) {
        $rules = [
            'father_name' => 'required',
            'father_occupation' => 'required',
            'father_address' => 'required',
            'mother_name' => 'required',
            'mother_occupation' => 'required',
            'mother_address' => 'required',
        ];

        $messages = [
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()), 400);
        }
        $cmPrevBasicInfo = CmBasicProfile::select('id')->where('id', $request->cm_basic_profile_id)->first();
        $cmBasicProfile = !empty($cmPrevBasicInfo->id) ? CmBasicProfile::find($cmPrevBasicInfo->id) : new CmBasicProfile;

        $cmBasicProfile->id = $request->cm_basic_profile_id;
        $cmBasicProfile->father_name = $request->father_name;
        $cmBasicProfile->father_occupation = $request->father_occupation;
        $cmBasicProfile->father_address = $request->father_address;
        $cmBasicProfile->mother_name = $request->mother_name;
        $cmBasicProfile->mother_occupation = $request->mother_occupation;
        $cmBasicProfile->mother_address = $request->mother_address;

        if ($cmBasicProfile->save()) {
            return response()->json(['success' => __('label.CM_FAMILY_INFO_UPDATED_SUCCESSFULLY')]);
        }
        //END:: updateFamilyInfo function
    }

    public function updateMaritalStatus(Request $request) {
        $rules = [
            'marital_status' => 'not_in:0',
        ];

        $messages = [
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()), 400);
        }
        $cmPrevBasicInfo = CmBasicProfile::select('id')->where('id', $request->cm_basic_profile_id)->first();
        $cmBasicProfile = !empty($cmPrevBasicInfo->id) ? CmBasicProfile::find($cmPrevBasicInfo->id) : new CmBasicProfile;
//        echo '<pre>';        print_r($cmBasicProfile); exit;

        $cmBasicProfile->marital_status = $request->marital_status;
        if ($request->marital_status == '1') {
            $rules = [
                //'marital_status' => 'not_in:0',
                'date_of_marriage' => 'required',
                'spouse_name' => 'required',
            ];

            $messages = [
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()), 400);
            }

            $cmBasicProfile->date_of_marriage = Helper::dateFormatConvert($request->date_of_marriage);
            $cmBasicProfile->spouse_name = $request->spouse_name;
            $cmBasicProfile->spouse_occupation = $request->spouse_occupation;
            $cmBasicProfile->spouse_work_address = $request->spouse_work_address;
        } else {
            $cmBasicProfile->date_of_marriage = null;
            $cmBasicProfile->spouse_name = null;
            $cmBasicProfile->spouse_occupation = null;
            $cmBasicProfile->spouse_work_address = null;
        }
        if ($cmBasicProfile->save()) {
            return response()->json(['success' => __('label.CM_MARITAL_STATUS_UPDATED_SUCCESSFULLY')]);
        }
        //End updateMaritialStatus function
    }

    public function updateBrotherSisterInfo(Request $request) {
//        echo '<pre>';print_r($request->all());exit;
        $cmBrotherSisterInfo = CmBrotherSister::select('id')->where('cm_basic_profile_id', $request->cm_basic_profile_id)->first();
        $cmBrotherSisterProfile = !empty($cmBrotherSisterInfo->id) ? CmBrotherSister::find($cmBrotherSisterInfo->id) : new CmBrotherSister;

        //Check Validation for Brother/Sister Information
        $rules = $messages = [];
        if (!empty($request->brother_sister)) {
            $row = 1;

            foreach ($request->brother_sister as $key => $brotherSister) {
                $rules['brother_sister.' . $key . '.name'] = 'required';
                $rules['brother_sister.' . $key . '.relation'] = 'required';

                $messages['brother_sister.' . $key . '.name.required'] = __('label.NAME_INPUT_FIELD_EMPTY_MESSAGE', ["counter" => $row]);
                $messages['brother_sister.' . $key . '.relation.required'] = __('label.RELATION_INPUT_FIELD_EMPTY_MESSAGE', ["counter" => $row]);

                $row++;
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()), 400);
        }


        $brotherSisterInfo = json_encode($request->brother_sister);
        $cmBrotherSisterProfile->cm_basic_profile_id = $request->cm_basic_profile_id;
        $cmBrotherSisterProfile->brother_sister_info = $brotherSisterInfo;
        $cmBrotherSisterProfile->updated_at = date('Y-m-d H:i:s');
        $cmBrotherSisterProfile->updated_by = Auth::user()->id;

        //Update Brother/Sister Info in cm_brother_sister_profile
        if ($cmBrotherSisterProfile->save()) {
            return response()->json(['success' => __('label.CM_BROTHER_SISTER_INFO_UPDATED')]);
        } else {
            return response()->json(['failed' => __('label.CM_BROTHER_SISTER_INFO_COULD_NOT_BE_UPDATED')]);
        }
    }

    public function rowAddForBrotherSister() {
        $html = view('cm.details.brotherSisterRowAdd')
                ->render();
        return response()->json(['html' => $html]);
    }

    //For Districts
    public function getDistrict(Request $request) {
        $districtList = ['0' => __('label.SELECT_DISTRICT_OPT')] + District::where('division_id', $request->division_id)
                        ->pluck('name', 'id')->toArray();
        $thanaList = ['0' => __('label.SELECT_THANA_OPT')];
        $htmldistrict = view('cm.details.districts')->with(compact('districtList'))->render();
        $htmlThana = view('cm.details.thana')->with(compact('thanaList'))->render();
        return response()->json(['html' => $htmldistrict, 'htmlThana' => $htmlThana]);
        //End getDistrict function
    }

    //For Thana
    public function getThana(Request $request) {
        $thanaList = ['0' => __('label.SELECT_THANA_OPT')] + THANA::where('district_id', $request->district_id)->pluck('name', 'id')->toArray();
        $htmlThana = view('cm.details.thana')->with(compact('thanaList'))->render();
        return response()->json(['html' => $htmlThana]);
        //End getThana function
    }

    public function updatePermanentAddress(Request $request) {
        //echo '<pre>';        print_r($request->all()); exit;
        $rules = [
            'division_id' => 'required|not_in:0',
            'district_id' => 'required|not_in:0',
            'thana_id' => 'required|not_in:0',
        ];

        $messages = [
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()), 400);
        }
        $cmPermanentAddress = CmPermanentAddress::select('id')->where('cm_basic_profile_id', $request->cm_basic_profile_id)->first();
        $cmPermanentAddressInfo = !empty($cmPermanentAddress->id) ? CmPermanentAddress::find($cmPermanentAddress->id) : new CmPermanentAddress;
        $cmPermanentAddressInfo->cm_basic_profile_id = $request->cm_basic_profile_id;
        $cmPermanentAddressInfo->division_id = $request->division_id;
        $cmPermanentAddressInfo->district_id = $request->district_id;
        $cmPermanentAddressInfo->thana_id = $request->thana_id;
        $cmPermanentAddressInfo->address_details = $request->address_details;
        $cmPermanentAddressInfo->updated_at = date('Y-m-d H:i:s');
        $cmPermanentAddressInfo->updated_by = Auth::user()->id;

        if ($cmPermanentAddressInfo->save()) {
            return response()->json(['success' => __('label.CM_PERMANENT_ADDRESS_UPDATED')]);
        }
        //End updatePermanentAddress function
    }

    public function rowAddForCivilEducation() {
        $html = view('cm.details.civilEducationRowAdd')
                ->render();
        return response()->json(['html' => $html]);

        ////End rowAdd function
    }

    public function updateCivilEducationInfo(Request $request) {
        //Check Validation for Civil Education Information
        $rules = $messages = [];
        if (!empty($request->civil_education)) {
            $row = 1;

            foreach ($request->civil_education as $key => $civilEducation) {
                $rules['civil_education.' . $key . '.institute_name'] = 'required';
                $rules['civil_education.' . $key . '.examination'] = 'required';
                $rules['civil_education.' . $key . '.result'] = 'required';
                $rules['civil_education.' . $key . '.year'] = 'required';

                $messages['civil_education.' . $key . '.institute_name.required'] = __('label.INSTITUTE_NAME_INPUT_FIELD_EMPTY_MESSAGE', ["counter" => $row]);
                $messages['civil_education.' . $key . '.examination.required'] = __('label.EXAMINATION_INPUT_FIELD_EMPTY_MESSAGE', ["counter" => $row]);
                $messages['civil_education.' . $key . '.result.required'] = __('label.RESULT_INPUT_FIELD_EMPTY_MESSAGE', ["counter" => $row]);
                $messages['civil_education.' . $key . '.year.required'] = __('label.RELATION_INPUT_FIELD_EMPTY_MESSAGE', ["counter" => $row]);

                $row++;
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()), 400);
        }
        $civilEducationInfo = CmCivilEducation::select('id')->where('cm_basic_profile_id', $request->cm_basic_profile_id)->first();
        $civilEducationProfile = !empty($civilEducationInfo->id) ? CmCivilEducation::find($civilEducationInfo->id) : new CmCivilEducation;



        $civilEducation = json_encode($request->civil_education);
        $civilEducationProfile->cm_basic_profile_id = $request->cm_basic_profile_id;
        $civilEducationProfile->civil_education_info = $civilEducation;
        $civilEducationProfile->updated_at = date('Y-m-d H:i:s');
        $civilEducationProfile->updated_by = Auth::user()->id;

        //Update cm civil education
        if ($civilEducationProfile->save()) {
            return response()->json(['success' => __('label.CIVIL_EDUCATION_INFO_UPDATED')]);
        }
        //End updateCivilEducationInfo function
    }

    public function rowAddForServiceRecord() {
        $appointmentList = ['0' => __('label.SELECT_APPT_OPT')] + Appointment::pluck('code', 'id')->toArray();
        $unitList = ['0' => __('label.SELECT_UNIT_OPT')] + Unit::pluck('code', 'id')->toArray();

        $html = view('cm.details.serviceRecordRowAdd')->with(compact('appointmentList', 'unitList'))
                ->render();
        return response()->json(['html' => $html]);

        ////End rowAdd function
    }

    public function updateServiceRecordInfo(Request $request) {
        //Check Validation for Service Record Information
        $rules = $messages = [];
        if (!empty($request->service_record)) {
            $row = 1;

            foreach ($request->service_record as $srKey => $serviceRecord) {
                $rules['service_record.' . $srKey . '.unit'] = 'not_in:0';
                $rules['service_record.' . $srKey . '.appointment'] = 'not_in:0';
                $rules['service_record.' . $srKey . '.year'] = 'required';

                $messages['service_record.' . $srKey . '.unit.not_in'] = __('label.UNIT_INPUT_FIELD_EMPTY_MESSAGE', ["counter" => $row]);
                $messages['service_record.' . $srKey . '.appointment.not_in'] = __('label.APPOINTMENT_INPUT_FIELD_EMPTY_MESSAGE', ["counter" => $row]);
                $messages['service_record.' . $srKey . '.year.required'] = __('label.YEAR_INPUT_FIELD_EMPTY_MESSAGE', ["counter" => $row]);

                $row++;
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()), 400);
        }

        $serviceEducationInfo = CmServiceRecord::select('id')->where('cm_basic_profile_id', $request->cm_basic_profile_id)->first();
        $serviceEducationProfile = !empty($serviceEducationInfo->id) ? CmServiceRecord::find($serviceEducationInfo->id) : new CmServiceRecord;

        $serviceRecord = json_encode($request->service_record);
        $serviceEducationProfile->cm_basic_profile_id = $request->cm_basic_profile_id;
        $serviceEducationProfile->service_record_info = $serviceRecord;
        $serviceEducationProfile->updated_at = date('Y-m-d H:i:s');
        $serviceEducationProfile->updated_by = Auth::user()->id;

        //Update cm service record
        if ($serviceEducationProfile->save()) {
            return response()->json(['success' => __('label.SERVICE_RECORD_INFO_UPDATED')]);
        }
        //End updateServiceRecordInfo function
    }

    public function rowAddForAwardRecord() {
        $html = view('cm.details.awardRecordRowAdd')
                ->render();
        return response()->json(['html' => $html]);

        ////End rowAdd function
    }

    public function updateAwardRecordInfo(Request $request) {
        //Check Validation for Award Record Information
        $rules = $messages = [];
        if (!empty($request->award_record)) {
            $row = 1;

            foreach ($request->award_record as $key => $awardRecord) {
                $rules['award_record.' . $key . '.award'] = 'required';
                $rules['award_record.' . $key . '.reason'] = 'required';
                $rules['award_record.' . $key . '.year'] = 'required';

                $messages['award_record.' . $key . '.award.required'] = __('label.AWARD_INPUT_FIELD_EMPTY_MESSAGE', ["counter" => $row]);
                $messages['award_record.' . $key . '.reason.required'] = __('label.REASON_INPUT_FIELD_EMPTY_MESSAGE', ["counter" => $row]);
                $messages['award_record.' . $key . '.year.required'] = __('label.YEAR_INPUT_FIELD_EMPTY_MESSAGE', ["counter" => $row]);

                $row++;
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()), 400);
        }
        $awardRecordInfo = CmAwardRecord::select('id')->where('cm_basic_profile_id', $request->cm_basic_profile_id)->first();
        $awardRecordProfile = !empty($awardRecordInfo->id) ? CmAwardRecord::find($awardRecordInfo->id) : new CmAwardRecord;



        $awardRecord = json_encode($request->award_record);
        $awardRecordProfile->cm_basic_profile_id = $request->cm_basic_profile_id;
        $awardRecordProfile->award_record_info = $awardRecord;
        $awardRecordProfile->updated_at = date('Y-m-d H:i:s');
        $awardRecordProfile->updated_by = Auth::user()->id;

        //Update cm award record
        if ($awardRecordProfile->save()) {
            return response()->json(['success' => __('label.AWARD_RECORD_INFO_UPDATED')]);
        }
        //End updateAwardRecordInfo function
    }

    public function rowAddForPunishmentRecord() {
        $html = view('cm.details.punishmentRecordRowAdd')
                ->render();
        return response()->json(['html' => $html]);

        ////End rowAdd function
    }

    public function updatePunishmentRecordInfo(Request $request) {
        //Check Validation for Punishment Record Information
        $rules = $messages = [];
        if (!empty($request->punishment_record)) {
            $row = 1;

            foreach ($request->punishment_record as $key => $punishmentRecord) {
                $rules['punishment_record.' . $key . '.punishment'] = 'required';
                $rules['punishment_record.' . $key . '.reason'] = 'required';
                $rules['punishment_record.' . $key . '.year'] = 'required';

                $messages['punishment_record.' . $key . '.punishment.required'] = __('label.PUNISHMENT_INPUT_FIELD_EMPTY_MESSAGE', ["counter" => $row]);
                $messages['punishment_record.' . $key . '.reason.required'] = __('label.REASON_INPUT_FIELD_EMPTY_MESSAGE', ["counter" => $row]);
                $messages['punishment_record.' . $key . '.year.required'] = __('label.YEAR_INPUT_FIELD_EMPTY_MESSAGE', ["counter" => $row]);

                $row++;
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()), 400);
        }
        $punishmentRecordInfo = CmPunishmentRecord::select('id')->where('cm_basic_profile_id', $request->cm_basic_profile_id)->first();
        $punishmentRecordProfile = !empty($punishmentRecordInfo->id) ? CmPunishmentRecord::find($punishmentRecordInfo->id) : new CmPunishmentRecord;



        $punishmentRecord = json_encode($request->punishment_record);
        $punishmentRecordProfile->cm_basic_profile_id = $request->cm_basic_profile_id;
        $punishmentRecordProfile->punishment_record_info = $punishmentRecord;
        $punishmentRecordProfile->updated_at = date('Y-m-d H:i:s');
        $punishmentRecordProfile->updated_by = Auth::user()->id;

        //Update cm punishment record
        if ($punishmentRecordProfile->save()) {
            return response()->json(['success' => __('label.PUNISHMENT_RECORD_INFO_UPDATED')]);
        }
        //End updatePunishmentRecordInfo function
    }

    public function rowAddForDefenceRelative() {
        $courseList = ['0' => __('label.SELECT_COURSE_OPT')] + Course::pluck('name', 'id')->toArray();
        $html = view('cm.details.defenceRelativeRowAdd')->with(compact('courseList'))
                ->render();
        return response()->json(['html' => $html]);

        ////End rowAddForDefenceRelative function
    }

    public function updateDefenceRelativeInfo(Request $request) {
        //Check Validation for Punishment Record Information
        $rules = $messages = [];
        if (!empty($request->defence_relative)) {
            $row = 1;

            foreach ($request->defence_relative as $key => $defenceRelative) {
                $rules['defence_relative.' . $key . '.course'] = 'not_in:0';
                $rules['defence_relative.' . $key . '.institute'] = 'required';
                $rules['defence_relative.' . $key . '.grading'] = 'required';
                $rules['defence_relative.' . $key . '.year'] = 'required';

                $messages['defence_relative.' . $key . '.course.not_in'] = __('label.COURSE_INPUT_FIELD_EMPTY_MESSAGE', ["counter" => $row]);
                $messages['defence_relative.' . $key . '.institute.required'] = __('label.INSTITUTE_INPUT_FIELD_EMPTY_MESSAGE', ["counter" => $row]);
                $messages['defence_relative.' . $key . '.grading.required'] = __('label.GRADING_INPUT_FIELD_EMPTY_MESSAGE', ["counter" => $row]);
                $messages['defence_relative.' . $key . '.year.required'] = __('label.YEAR_INPUT_FIELD_EMPTY_MESSAGE', ["counter" => $row]);

                $row++;
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()), 400);
        }
        $defenceRecordInfo = CmRelativeInDefence::select('id')->where('cm_basic_profile_id', $request->cm_basic_profile_id)->first();
        $defenceRecordProfile = !empty($defenceRecordInfo->id) ? CmRelativeInDefence::find($defenceRecordInfo->id) : new CmRelativeInDefence;



        $defenceRecord = json_encode($request->defence_relative);
        $defenceRecordProfile->cm_basic_profile_id = $request->cm_basic_profile_id;
        $defenceRecordProfile->cm_relative_info = $defenceRecord;
        $defenceRecordProfile->updated_at = date('Y-m-d H:i:s');
        $defenceRecordProfile->updated_by = Auth::user()->id;

        //Update cm punishment record
        if ($defenceRecordProfile->save()) {
            return response()->json(['success' => __('label.PUNISHMENT_RECORD_INFO_UPDATED')]);
        }
        //End updatePunishmentRecordInfo function
    }

    public function updateNextKin(Request $request) {
        //echo '<pre>';        print_r($request->all()); exit;
        $rules = [
            'kin_name' => 'required',
            'kin_relation' => 'required',
            'kin_division_id' => 'not_in:0',
            'kin_district_id' => 'not_in:0',
            'kin_thana_id' => 'not_in:0',
        ];

        $messages = [
            'kin_name.required' => 'The name field is required',
            'kin_relation.required' => 'The relation field is required',
            'kin_division_id.not_in' => 'The division field is required',
            'kin_district_id.not_in' => 'The district field is required',
            'kin_thana_id.not_in' => 'The thana field is required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()), 400);
        }
        $cmNextKin = CmNextKin::select('id')->where('cm_basic_profile_id', $request->cm_basic_profile_id)->first();
        $cmNextKinInfo = !empty($cmNextKin->id) ? CmNextKin::find($cmNextKin->id) : new CmNextKin;
        $cmNextKinInfo->cm_basic_profile_id = $request->cm_basic_profile_id;
        $cmNextKinInfo->name = $request->kin_name;
        $cmNextKinInfo->relation = $request->kin_relation;
        $cmNextKinInfo->division_id = $request->kin_division_id;
        $cmNextKinInfo->district_id = $request->kin_district_id;
        $cmNextKinInfo->thana_id = $request->kin_thana_id;
        $cmNextKinInfo->address_details = $request->kin_address_details;
        $cmNextKinInfo->updated_at = date('Y-m-d H:i:s');
        $cmNextKinInfo->updated_by = Auth::user()->id;

        if ($cmNextKinInfo->save()) {
            return response()->json(['success' => __('label.CM_NEXT_KIN_INFO_UPDATED')]);
        }
        //End updatePermanentAddress function
    }

    public function updateMedicalDetails(Request $request) {
        $rules = [
            'category' => 'required',
            'blood_group' => 'required',
            'date_of_birth' => 'required',
        ];

        $messages = [
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()), 400);
        }
        $cmMedicalDetails = CmMedicalDetails::select('id')->where('cm_basic_profile_id', $request->cm_basic_profile_id)->first();
        $cmBasicInfo = CmBasicProfile::select('id', 'ht_ft', 'ht_inch', 'weight')->where('id', $request->cm_basic_profile_id)->first();
//        echo '<pre>';        print_r($cmBasicInfo); exit;
        $cmMedicalDetailsInfo = !empty($cmMedicalDetails->id) ? CmMedicalDetails::find($cmMedicalDetails->id) : new CmMedicalDetails;
        $cmMedicalDetailsInfo->cm_basic_profile_id = $request->cm_basic_profile_id;
        $cmMedicalDetailsInfo->category = $request->category;
        $cmMedicalDetailsInfo->blood_group = $request->blood_group;
        $cmMedicalDetailsInfo->date_of_birth = !empty($request->date_of_birth) ? Helper::dateFormatConvert($request->date_of_birth) : null;
        $cmBasicInfo->ht_ft = $request->ht_ft;
        $cmBasicInfo->ht_inch = $request->ht_inch;
        $cmBasicInfo->weight = $request->weight;
        $cmMedicalDetailsInfo->over_under_weight = $request->over_under_weight;
        $cmMedicalDetailsInfo->any_disease = $request->any_disease;
        $cmMedicalDetailsInfo->updated_at = date('Y-m-d H:i:s');
        $cmMedicalDetailsInfo->updated_by = Auth::user()->id;

        if ($cmMedicalDetailsInfo->save() && $cmBasicInfo->save()) {
            return response()->json(['success' => __('label.CM_MEDICAL_DETAILS_INFO_UPDATED')]);
        }
        //End updateMedicalDetails function
    }

    public function updateWinterTraining(Request $request) {
        $rules = $messages = [];
        $rules['participated_no'] = 'required';
        if (!empty($request->winter_training)) {
            $row = 1;

            foreach ($request->winter_training as $key => $winterTraining) {
                $rules['winter_training.' . $key . '.exercise'] = 'required';
                $rules['winter_training.' . $key . '.year'] = 'required';

                $messages['winter_training.' . $key . '.exercise.required'] = __('label.EXERCISE_INPUT_FIELD_EMPTY_MESSAGE', ["counter" => $row]);
                $messages['winter_training.' . $key . '.year.required'] = __('label.YEAR_INPUT_FIELD_EMPTY_MESSAGE', ["counter" => $row]);

                $row++;
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()), 400);
        }
        $cmWinterTraining = CmWinterCollectiveTraining::select('id')->where('cm_basic_profile_id', $request->cm_basic_profile_id)->first();
        $cmWinterTrainingInfo = !empty($cmWinterTraining->id) ? CmWinterCollectiveTraining::find($cmWinterTraining->id) : new CmWinterCollectiveTraining;

        $winterTraining = json_encode($request->winter_training);
        $cmWinterTrainingInfo->cm_basic_profile_id = $request->cm_basic_profile_id;
        $cmWinterTrainingInfo->participated_no = $request->participated_no;
        $cmWinterTrainingInfo->training_info = $winterTraining;
        $cmWinterTrainingInfo->updated_at = date('Y-m-d H:i:s');
        $cmWinterTrainingInfo->updated_by = Auth::user()->id;

        if ($cmWinterTrainingInfo->save()) {
            return response()->json(['success' => __('label.CM_WINTER_TRAINING_INFO_UPDATED')]);
        }
        //End updatePermanentAddress function
    }

    public function updateCmOthersInfo(Request $request) {
        $rules = [
            'swimming' => 'not_in:0',
        ];


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()), 400);
        }

        $cmOthersInfo = CmOthers::select('id')->where('cm_basic_profile_id', $request->cm_basic_profile_id)->first();
        $cmOthersProfile = !empty($cmOthersInfo->id) ? CmOthers::find($cmOthersInfo->id) : new CmOthers;

        $othersInfo = json_encode($request->visited_countries_id);
        $cmOthersProfile->cm_basic_profile_id = $request->cm_basic_profile_id;
        $cmOthersProfile->visited_countries_id = $othersInfo;
        $cmOthersProfile->special_quality = $request->special_quality;
        $cmOthersProfile->swimming = $request->swimming;
        $cmOthersProfile->professional_computer = $request->professional_computer;
        $cmOthersProfile->updated_at = date('Y-m-d H:i:s');
        $cmOthersProfile->updated_by = Auth::user()->id;

        if ($cmOthersProfile->save()) {
            return response()->json(['success' => __('label.CM_BROTHER_SISTER_INFO_UPDATED')]);
        }
        //End updateCmOthersInfo function
    }

}
