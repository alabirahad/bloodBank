<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Country;
use App\Division;
use App\District;
use App\Thana;
use App\GradingSystem;
use App\Course;
use App\EventAssessmentMarking;
use App\DsMarkingGroup;
use App\MarkingGroup;
use App\TrainingYear;
use App\Term;
use App\Event;
use App\SubEvent;
use App\SubSubEvent;
use App\SubSubSubEvent;
use App\EventAssessmentMarkingLock;
use App\CmBasicProfile;
use App\Rank;
use App\ServiceAppointment;
use App\CommissioningCourse;
use App\Religion;
use App\ArmsService;
use App\Appointment;
use App\CmOthers;
use App\CmCountryVisit;
use App\CmPermanentAddress;
use App\CmCivilEducation;
use App\CmServiceRecord;
use App\CmRelativeInDefence;
use App\CmChild;
use App\CmPresentAddress;
use App\CmPassport;
use App\MilCourse;
use App\CmMission;
use App\CmBank;
use App\Decoration;
use App\Hobby;
use App\Award;
use App\Wing;
use App\Unit;
use App\CiModerationMarkingLock;
use App\ComdtModerationMarkingLock;
use App\CiModerationMarking;
use App\ComdtModerationMarking;
use App\CiObsnMarking;
use App\CiObsnMarkingLock;
use App\ComdtObsnMarking;
use App\ComdtObsnMarkingLock;
use App\DeligateCiAcctToDs;
use Illuminate\Http\Request;

class Common {

    public static function getPosition($cmArr, $totalWtKey, $positionKey, $mutualAssessment = 0) {
        $positionArr = [];
        if (!empty($cmArr)) {
            foreach ($cmArr as $cmId => $cm) {
                if (!isset($cm[$totalWtKey])) {
                    $cm[$totalWtKey] = 0;
                }
                if (!empty($positionArr)) {
                    if (!in_array($cm[$totalWtKey], $positionArr)) {
                        $positionArr[] = $cm[$totalWtKey];
                    }
                } else {
                    $positionArr[] = $cm[$totalWtKey];
                }
            }
        }
        if (!empty($mutualAssessment)) {
            sort($positionArr);
        } else {
            rsort($positionArr);
        }

        $positionArr2 = [];
        if (!empty($positionArr)) {
            foreach ($positionArr as $ptn => $value) {
                if (!empty($value)) {
                    $positionArr2[strval($value)] = ++$ptn;
                }
            }
        }

        $positionArr = $positionArr2;
        if (!empty($cmArr)) {
            foreach ($cmArr as $cmId => $cm) {
                if (!isset($cm[$totalWtKey])) {
                    $cm[$totalWtKey] = 0;
                }

                if (!empty($positionArr)) {
                    $cmArr[$cmId][$positionKey] = isset($positionArr[strval($cm[$totalWtKey])]) ? $positionArr[strval($cm[$totalWtKey])] : null;
                } else {
                    $cmArr[$cmId][$positionKey] = 0;
                }
            }
        }
        return $cmArr;
    }

    public static function getGradeName($cmArr, $gradeInfo, $wtPercent, $gradeKey) {
        $gradeArr = [];
        if (!$gradeInfo->isEmpty()) {
            foreach ($gradeInfo as $grade) {
                $gradeArr[$grade->grade_name]['start'] = $grade->marks_from;
                $gradeArr[$grade->grade_name]['end'] = $grade->marks_to;
            }
        }
        if (!empty($cmArr)) {
            foreach ($cmArr as $cmId => $cmInfo) {
                $totalWtPercent = !empty($cmInfo[$wtPercent]) ? $cmInfo[$wtPercent] : 0;
                if (!empty($gradeArr)) {
                    foreach ($gradeArr as $letterGrade => $gradeRange) {
                        if ($totalWtPercent == 100) {
                            $cmArr[$cmId][$gradeKey] = "A+";
                        }
                        if ($gradeRange['start'] <= $totalWtPercent && $totalWtPercent < $gradeRange['end']) {
                            if (is_int($cmId)) {
                                $cmArr[$cmId][$gradeKey] = !empty($totalWtPercent) ? $letterGrade : '';
                            }
                        }
                    }
                }
            }
        }

        return $cmArr;
    }

    public static function requestCourseSatatusSummary($request, $loadView) {
        $courseName = Course::select('name')->where('id', $request->course_id)->first();

        $termName = !empty($request->term_id) ? Term::select('name')->where('id', $request->term_id)->first() : [];
        $eventMksWtArr = [];
        //event info
        $eventInfo = MarkingGroup::join('event', 'event.id', '=', 'marking_group.event_id')
                ->join('term', 'term.id', 'marking_group.term_id')
                ->leftJoin('sub_event', 'sub_event.id', 'marking_group.sub_event_id')
                ->leftJoin('sub_sub_event', 'sub_sub_event.id', 'marking_group.sub_sub_event_id')
                ->leftJoin('sub_sub_sub_event', 'sub_sub_sub_event.id', 'marking_group.sub_sub_sub_event_id')
                ->where('marking_group.course_id', $request->course_id);

        if (!empty($request->term_id)) {
            $eventInfo = $eventInfo->where('marking_group.term_id', $request->term_id);
        }

        $eventInfo = $eventInfo->select('event.event_code as event_name', 'event.id as event_id', 'marking_group.term_id'
                        , 'sub_event.event_code as sub_event_name', 'marking_group.sub_event_id'
                        , 'sub_sub_event.event_code as sub_sub_event_name', 'marking_group.sub_sub_event_id'
                        , 'sub_sub_sub_event.event_code as sub_sub_sub_event_name', 'marking_group.sub_sub_sub_event_id'
                        , 'term.name as term_name')
                ->orderBy('term.order', 'asc')
                ->orderBy('event.order', 'asc')
                ->orderBy('sub_event.order', 'asc')
                ->orderBy('sub_sub_event.order', 'asc')
                ->orderBy('sub_sub_sub_event.order', 'asc')
                ->get();

        if (!$eventInfo->isEmpty()) {
            foreach ($eventInfo as $ev) {
                $eventMksWtArr['event'][$ev->term_id]['name'] = $ev->term_name ?? '';
                $eventMksWtArr['event'][$ev->term_id][$ev->event_id]['name'] = $ev->event_name ?? '';
                $eventMksWtArr['event'][$ev->term_id][$ev->event_id][$ev->sub_event_id]['name'] = $ev->sub_event_name ?? '';
                $eventMksWtArr['event'][$ev->term_id][$ev->event_id][$ev->sub_event_id][$ev->sub_sub_event_id]['name'] = $ev->sub_sub_event_name ?? '';
                $eventMksWtArr['event'][$ev->term_id][$ev->event_id][$ev->sub_event_id][$ev->sub_sub_event_id][$ev->sub_sub_sub_event_id]['name'] = $ev->sub_sub_sub_event_name ?? '';
            }
        }

        $totalMarkingDsInfo = DsMarkingGroup::join('marking_group', 'marking_group.id', 'ds_marking_group.marking_group_id')
                ->select('marking_group.term_id', 'marking_group.event_id', 'marking_group.sub_event_id', 'marking_group.sub_sub_event_id'
                        , 'marking_group.sub_sub_sub_event_id', DB::raw("COUNT(ds_marking_group.ds_id) as ds_id"))
                ->where('course_id', $request->course_id);

        if (!empty($request->term_id)) {
            $totalMarkingDsInfo = $totalMarkingDsInfo->where('marking_group.term_id', $request->term_id);
        }

        $totalMarkingDsInfo = $totalMarkingDsInfo->groupBy('marking_group.term_id', 'marking_group.event_id', 'marking_group.sub_event_id', 'marking_group.sub_sub_event_id'
                        , 'marking_group.sub_sub_sub_event_id')
                ->get();
        $totalDsArr = $totalLockedDsArr = $rowSpanArr = [];


        $totalLockedDsInfo = EventAssessmentMarkingLock::select('term_id', 'event_id', 'sub_event_id', 'sub_sub_event_id'
                        , 'sub_sub_sub_event_id', DB::raw("COUNT(locked_by) as locked_ds"))
                ->where('course_id', $request->course_id);

        if (!empty($request->term_id)) {
            $totalLockedDsInfo = $totalLockedDsInfo->where('term_id', $request->term_id);
        }

        $totalLockedDsInfo = $totalLockedDsInfo->groupBy('term_id', 'event_id', 'sub_event_id', 'sub_sub_event_id'
                        , 'sub_sub_sub_event_id')
                ->get();

        if (!$totalLockedDsInfo->isEmpty()) {
            foreach ($totalLockedDsInfo as $lockInfo) {
                $eventMksWtArr['mks_wt'][$lockInfo->term_id][$lockInfo->event_id][$lockInfo->sub_event_id][$lockInfo->sub_sub_event_id][$lockInfo->sub_sub_sub_event_id]['forwarded'] = $lockInfo->locked_ds;
            }
        }
        if (!$totalMarkingDsInfo->isEmpty()) {
            foreach ($totalMarkingDsInfo as $dsInfo) {
                $forwarded = !empty($eventMksWtArr['mks_wt'][$dsInfo->term_id][$dsInfo->event_id][$dsInfo->sub_event_id][$dsInfo->sub_sub_event_id][$dsInfo->sub_sub_sub_event_id]['forwarded']) ? $eventMksWtArr['mks_wt'][$dsInfo->term_id][$dsInfo->event_id][$dsInfo->sub_event_id][$dsInfo->sub_sub_event_id][$dsInfo->sub_sub_sub_event_id]['forwarded'] : 0;

                $eventMksWtArr['mks_wt'][$dsInfo->term_id][$dsInfo->event_id][$dsInfo->sub_event_id][$dsInfo->sub_sub_event_id][$dsInfo->sub_sub_sub_event_id]['total'] = $dsInfo->ds_id;
                $eventMksWtArr['mks_wt'][$dsInfo->term_id][$dsInfo->event_id][$dsInfo->sub_event_id][$dsInfo->sub_sub_event_id][$dsInfo->sub_sub_sub_event_id]['not_forwarded'] = $dsInfo->ds_id - $forwarded;
            }
        }

        // ci mod check
        $ciModInfo = CiModerationMarking::select('term_id', 'event_id', 'sub_event_id', 'sub_sub_event_id'
                        , 'sub_sub_sub_event_id')
                ->where('course_id', $request->course_id);

        if (!empty($request->term_id)) {
            $ciModInfo = $ciModInfo->where('term_id', $request->term_id);
        }

        $ciModInfo = $ciModInfo->get();
        $ciModLockInfo = CiModerationMarkingLock::select('term_id', 'event_id', 'sub_event_id', 'sub_sub_event_id'
                        , 'sub_sub_sub_event_id')
                ->where('course_id', $request->course_id);

        if (!empty($request->term_id)) {
            $ciModLockInfo = $ciModLockInfo->where('term_id', $request->term_id);
        }

        $ciModLockInfo = $ciModLockInfo->get();

        if (!$ciModInfo->isEmpty()) {
            foreach ($ciModInfo as $ciInfo) {
                $eventMksWtArr['mks_wt'][$ciInfo->term_id][$ciInfo->event_id][$ciInfo->sub_event_id][$ciInfo->sub_sub_event_id][$ciInfo->sub_sub_sub_event_id]['ci_mod'] = 1;
            }
        }
        if (!$ciModLockInfo->isEmpty()) {
            foreach ($ciModLockInfo as $ciLockInfo) {
                $eventMksWtArr['mks_wt'][$ciLockInfo->term_id][$ciLockInfo->event_id][$ciLockInfo->sub_event_id][$ciLockInfo->sub_sub_event_id][$ciLockInfo->sub_sub_sub_event_id]['ci_mod_lock'] = 1;
            }
        }

        //comdt mod check
        $comdtModInfo = ComdtModerationMarking::select('term_id', 'event_id', 'sub_event_id', 'sub_sub_event_id'
                        , 'sub_sub_sub_event_id')
                ->where('course_id', $request->course_id);

        if (!empty($request->term_id)) {
            $comdtModInfo = $comdtModInfo->where('term_id', $request->term_id);
        }

        $comdtModInfo = $comdtModInfo->get();
        $comdtModLockInfo = ComdtModerationMarkingLock::select('term_id', 'event_id', 'sub_event_id', 'sub_sub_event_id'
                        , 'sub_sub_sub_event_id')
                ->where('course_id', $request->course_id);

        if (!empty($request->term_id)) {
            $comdtModLockInfo = $comdtModLockInfo->where('term_id', $request->term_id);
        }

        $comdtModLockInfo = $comdtModLockInfo->get();

        if (!$comdtModInfo->isEmpty()) {
            foreach ($comdtModInfo as $comdtInfo) {
                $eventMksWtArr['mks_wt'][$comdtInfo->term_id][$comdtInfo->event_id][$comdtInfo->sub_event_id][$comdtInfo->sub_sub_event_id][$comdtInfo->sub_sub_sub_event_id]['comdt_mod'] = 1;
            }
        }
        if (!$comdtModLockInfo->isEmpty()) {
            foreach ($comdtModLockInfo as $comdtLockInfo) {
                $eventMksWtArr['mks_wt'][$comdtLockInfo->term_id][$comdtLockInfo->event_id][$comdtLockInfo->sub_event_id][$comdtLockInfo->sub_sub_event_id][$comdtLockInfo->sub_sub_sub_event_id]['comdt_mod_lock'] = 1;
            }
        }

        if (!empty($eventMksWtArr['mks_wt'])) {
            foreach ($eventMksWtArr['mks_wt'] as $termId => $evMksWtInfo) {
                foreach ($evMksWtInfo as $eventId => $evInfo) {
                    foreach ($evInfo as $subEventId => $subEvInfo) {
                        foreach ($subEvInfo as $subSubEventId => $subSubEvInfo) {
                            foreach ($subSubEvInfo as $subSubSubEventId => $subSubSubEvInfo) {

                                $rowSpanArr['event'][$termId][$eventId] = !empty($rowSpanArr['event'][$termId][$eventId]) ? $rowSpanArr['event'][$termId][$eventId] : 0;
                                $rowSpanArr['event'][$termId][$eventId] += 1;

                                $rowSpanArr['sub_event'][$termId][$eventId][$subEventId] = !empty($rowSpanArr['sub_event'][$termId][$eventId][$subEventId]) ? $rowSpanArr['sub_event'][$termId][$eventId][$subEventId] : 0;
                                $rowSpanArr['sub_event'][$termId][$eventId][$subEventId] += 1;

                                $rowSpanArr['sub_sub_event'][$termId][$eventId][$subEventId][$subSubEventId] = !empty($rowSpanArr['sub_sub_event'][$termId][$eventId][$subEventId][$subSubEventId]) ? $rowSpanArr['sub_sub_event'][$termId][$eventId][$subEventId][$subSubEventId] : 0;
                                $rowSpanArr['sub_sub_event'][$termId][$eventId][$subEventId][$subSubEventId] += 1;

                                $rowSpanArr['sub_sub_sub_event'][$termId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId] = !empty($rowSpanArr['sub_sub_sub_event'][$termId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]) ? $rowSpanArr['sub_sub_sub_event'][$termId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId] : 0;
                                $rowSpanArr['sub_sub_sub_event'][$termId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId] += 1;
                            }
                        }
                    }
                }
            }
        }

        $ciObsnMarking = CiObsnMarking::select('id')->where('course_id', $request->course_id)->get();
        $ciObsnMarkingLock = CiObsnMarkingLock::select('id')->where('course_id', $request->course_id)->first();
        $comdtObsnMarking = ComdtObsnMarking::select('id')->where('course_id', $request->course_id)->get();
        $comdtObsnMarkingLock = ComdtObsnMarkingLock::select('id')->where('course_id', $request->course_id)->first();

//        echo '<pre>';
//        print_r($eventMksWtArr['mks_wt']);
//        exit;
        $html = view($loadView, compact('courseName', 'request', 'eventMksWtArr', 'rowSpanArr', 'termName', 'ciObsnMarking'
                        , 'ciObsnMarkingLock', 'comdtObsnMarking', 'comdtObsnMarkingLock'))->render();

        return response()->json(['html' => $html]);
    }

    public static function getDsMarkingSummary($request, $loadView) {
//        echo '<pre>';        print_r($request->all()); exit;
        $activeTrainingYearInfo = TrainingYear::where('status', '1')->first();
        $course = Course::where('id', $request->course_id)->first();
        $term = Term::where('id', $request->term_id)->first();
        $event = Event::where('id', $request->event_id)->first();
        $subEvent = SubEvent::where('id', $request->sub_event_id)->first();
        $subSubEvent = SubSubEvent::where('id', $request->sub_sub_event_id)->first();
        $subSubSubEvent = SubSubSubEvent::where('id', $request->sub_sub_sub_event_id)->first();

        //Lock Table
        $eventAssessmentMarkingLockInfo = EventAssessmentMarkingLock::where('course_id', $request->course_id)
                ->where('term_id', $request->term_id)
                ->where('event_id', $request->event_id);
        if (!empty($request->sub_event_id)) {
            $eventAssessmentMarkingLockInfo = $eventAssessmentMarkingLockInfo->where('sub_event_id', $request->sub_event_id);
        }
        if (!empty($request->sub_sub_event_id)) {
            $eventAssessmentMarkingLockInfo = $eventAssessmentMarkingLockInfo->where('sub_sub_event_id', $request->sub_sub_event_id);
        }
        if (!empty($request->sub_sub_sub_event_id)) {
            $eventAssessmentMarkingLockInfo = $eventAssessmentMarkingLockInfo->where('sub_sub_sub_event_id', $request->sub_sub_sub_event_id);
        }
        $eventAssessmentMarkingLockInfo = $eventAssessmentMarkingLockInfo->pluck('locked_by', 'locked_by')->toArray();


        $dsDataInfo = DsMarkingGroup::join('marking_group', 'marking_group.id', 'ds_marking_group.marking_group_id')
                ->join('users', 'users.id', 'ds_marking_group.ds_id')->join('rank', 'rank.id', 'users.rank_id')
                ->leftJoin('appointment', 'appointment.id', 'ds_marking_group.ds_appt_id')
                ->where('marking_group.course_id', $request->course_id)
                ->where('marking_group.term_id', $request->term_id)
                ->where('marking_group.event_id', $request->event_id);
        if (!empty($request->sub_event_id)) {
            $dsDataInfo = $dsDataInfo->where('marking_group.sub_event_id', $request->sub_event_id);
        }
        if (!empty($request->sub_sub_event_id)) {
            $dsDataInfo = $dsDataInfo->where('marking_group.sub_sub_event_id', $request->sub_sub_event_id);
        }
        if (!empty($request->sub_sub_sub_event_id)) {
            $dsDataInfo = $dsDataInfo->where('marking_group.sub_sub_sub_event_id', $request->sub_sub_sub_event_id);
        }

        if (!empty($request->data_id)) {
            if ($request->data_id == '1') {
                $dsDataInfo = $dsDataInfo->whereIn('ds_marking_group.ds_id', $eventAssessmentMarkingLockInfo);
            } elseif ($request->data_id == '2') {
                $dsDataInfo = $dsDataInfo->whereNotIn('ds_marking_group.ds_id', $eventAssessmentMarkingLockInfo);
            }
        }

        $dsDataInfo = $dsDataInfo->select('appointment.code as appt', 'users.id as ds_id', 'users.photo'
                        , DB::raw("CONCAT(rank.code, ' ', users.full_name) as ds_name"), 'users.personal_no')
                ->orderBy('rank.order', 'asc')
                ->orderBy('users.personal_no', 'asc')
                ->get();

        $dsDataList = [];
        if (!$dsDataInfo->isEmpty()) {
            foreach ($dsDataInfo as $ds) {
                $dsDataList[$ds->ds_id] = $ds->toArray();
            }
        }
        // assessment marking data
        $eventAssessmentMarkingInfo = EventAssessmentMarking::join('grading_system', 'grading_system.id', 'event_assessment_marking.grade_id')
                ->where('event_assessment_marking.course_id', $request->course_id)
                ->where('event_assessment_marking.term_id', $request->term_id)
                ->where('event_assessment_marking.event_id', $request->event_id);

        if (!empty($request->sub_event_id)) {
            $eventAssessmentMarkingInfo = $eventAssessmentMarkingInfo->where('event_assessment_marking.sub_event_id', $request->sub_event_id);
        }
        if (!empty($request->sub_sub_event_id)) {
            $eventAssessmentMarkingInfo = $eventAssessmentMarkingInfo->where('event_assessment_marking.sub_sub_event_id', $request->sub_sub_event_id);
        }
        if (!empty($request->sub_sub_sub_event_id)) {
            $eventAssessmentMarkingInfo = $eventAssessmentMarkingInfo->where('event_assessment_marking.sub_sub_sub_event_id', $request->sub_sub_sub_event_id);
        }
        $eventAssessmentMarkingInfo = $eventAssessmentMarkingInfo->pluck('event_assessment_marking.updated_by', 'event_assessment_marking.updated_by')
                ->toArray();



//        echo '<pre>';
//        print_r($dsDataList);
//        exit;

        $view = view($loadView, compact('request', 'activeTrainingYearInfo', 'course', 'term', 'event', 'subEvent', 'subSubEvent', 'subSubSubEvent'
                        , 'dsDataList', 'eventAssessmentMarkingLockInfo', 'eventAssessmentMarkingInfo'))->render();
        return response()->json(['html' => $view]);
    }

    public static function getOrganizationType($flagType = 0) {

        if ($flagType == 1) {
            $organizationList = [
                '1' => 'Unit',
                '2' => 'Formation',
                '3' => 'Institute',
            ];
        } else {
            $organizationList = [
                '1' => 'Corps',
                '2' => 'Regt',
                '3' => 'Br',
            ];
        }

        return $organizationList;
    }
    public static function getDsDeligationList() {

        $dsDeligationList = DeligateCiAcctToDs::join('course', 'course.id', 'deligate_ci_acct_to_ds.course_id')
                        ->join('training_year', 'training_year.id', 'course.training_year_id')
                        ->where('training_year.status', '1')->where('course.status', '1')
                        ->pluck('ds_id', 'course_id')->toArray();

        return $dsDeligationList;
    }

    public static function getCommissionType() {
        $commissionTypeList = [
            '1' => __('label.REGULAR'),
            '2' => __('label.REGULAR'),
        ];

        return $commissionTypeList;
    }

    public static function getMilCourseCategory() {
        $categoryList = [
            '0' => __('label.SELECT_CATEGORY_OPT'),
            '1' => __('label.HOME_COURSE'),
            '2' => __('label.FOREIGN_COURSE'),
        ];

        return $categoryList;
    }

    public static function getBloodGroup() {
        $bloodGroupList = [
            '1' => __('label.A_POS'),
            '2' => __('label.A_NEG'),
            '3' => __('label.B_POS'),
            '4' => __('label.B_NEG'),
            '5' => __('label.AB_POS'),
            '6' => __('label.AB_NEG'),
            '7' => __('label.O_POS'),
            '8' => __('label.O_NEG'),
        ];

        return $bloodGroupList;
    }

    public static function getProfile(Request $request, $id, $loadView, $prinLloadView) {
        $keyAppt = [];
        $qpArr = $request->all();
        $cmInfoData = CmBasicProfile::leftJoin('rank', 'rank.id', '=', 'cm_basic_profile.rank_id')
                ->leftJoin('course', 'course.id', '=', 'cm_basic_profile.course_id')
                ->leftJoin('wing', 'wing.id', '=', 'cm_basic_profile.wing_id')
                ->leftJoin('arms_service', 'arms_service.id', '=', 'cm_basic_profile.arms_service_id')
                ->leftJoin('commissioning_course', 'commissioning_course.id', '=', 'cm_basic_profile.commissioning_course_id')
                ->leftJoin('religion', 'religion.id', '=', 'cm_basic_profile.religion_id')
                ->select('cm_basic_profile.id as cm_basic_profile_id', 'cm_basic_profile.email'
                        , 'cm_basic_profile.photo', 'cm_basic_profile.number', 'cm_basic_profile.full_name'
                        , 'cm_basic_profile.official_name'
                        , DB::raw("CONCAT(rank.code, ' ', cm_basic_profile.full_name, ' (', cm_basic_profile.official_name, ')') as cm_name")
                        , 'course.name as course_name'
                        , 'arms_service.name as arms_service_name', 'commissioning_course.name as commissioning_course_name'
                        , 'religion.name as religion_name'
                        , 'cm_basic_profile.*', 'wing.code as wing_name')
                ->where('cm_basic_profile.status', '1')
                ->where('cm_basic_profile.id', $id)
                ->first();

        $civilEducationInfoData = CmCivilEducation::where('cm_basic_profile_id', !empty($cmInfoData->cm_basic_profile_id) ? $cmInfoData->cm_basic_profile_id : 0)
                ->select('id', 'cm_basic_profile_id', 'civil_education_info')
                ->first();

        $serviceRecordInfoData = CmServiceRecord::where('cm_basic_profile_id', !empty($cmInfoData->cm_basic_profile_id) ? $cmInfoData->cm_basic_profile_id : 0)
                ->select('id', 'cm_basic_profile_id', 'service_record_info')
                ->first();

        $msnDataInfo = CmMission::where('cm_basic_profile_id', !empty($cmInfoData->cm_basic_profile_id) ? $cmInfoData->cm_basic_profile_id : 0)
                ->select('id', 'cm_basic_profile_id', 'msn_info')
                ->first();
        if (!empty($serviceRecordInfoData)) {
            $serviceRecordInfo = json_decode($serviceRecordInfoData->service_record_info, TRUE);
            if (!empty($serviceRecordInfo)) {
                foreach ($serviceRecordInfo as $skey => $serviceRecord) {
                    $keyAppt[$serviceRecord['appointment']] = $serviceRecord['appointment'];
                }
            }
        }
        if (!empty($msnDataInfo)) {
            $msnData = json_decode($msnDataInfo->msn_info, TRUE);
            if (!empty($msnData)) {
                foreach ($msnData as $mkey => $msn) {
                    $keyAppt[$msn['appointment']] = $msn['appointment'];
                }
            }
        }


        $countryVisitDataInfo = CmCountryVisit::where('cm_basic_profile_id', !empty($cmInfoData->cm_basic_profile_id) ? $cmInfoData->cm_basic_profile_id : 0)
                ->select('id', 'cm_basic_profile_id', 'visit_info')
                ->first();

        $bankInfoData = CmBank::where('cm_basic_profile_id', !empty($cmInfoData->cm_basic_profile_id) ? $cmInfoData->cm_basic_profile_id : 0)
                ->select('id', 'cm_basic_profile_id', 'bank_info')
                ->first();

        $childInfoData = CmChild::where('cm_basic_profile_id', !empty($cmInfoData->cm_basic_profile_id) ? $cmInfoData->cm_basic_profile_id : 0)
                ->select('id', 'cm_basic_profile_id', 'cm_child_info', 'no_of_child')
                ->first();

        $defenceRelativeInfoData = CmRelativeInDefence::where('cm_basic_profile_id', !empty($cmInfoData->cm_basic_profile_id) ? $cmInfoData->cm_basic_profile_id : 0)
                ->select('id', 'cm_basic_profile_id', 'cm_relative_info')
                ->first();

        $othersInfoData = CmOthers::where('cm_basic_profile_id', !empty($cmInfoData->cm_basic_profile_id) ? $cmInfoData->cm_basic_profile_id : 0)
                ->select('id', 'cm_basic_profile_id', 'decoration_id', 'hobby_id')
                ->first();
        $passportInfoData = CmPassport::where('cm_basic_profile_id', !empty($cmInfoData->cm_basic_profile_id) ? $cmInfoData->cm_basic_profile_id : 0)
                ->select('id', 'cm_basic_profile_id', 'passport_no', 'place_of_issue', 'date_of_issue', 'date_of_expire')
                ->first();

        $commissionTypeList = Common::getCommissionType();
        $bloodGroupList = Common::getBloodGroup();

        $decorationList = Decoration::where('status', '1')->orderBy('order', 'asc')
                        ->pluck('code', 'id')->toArray();
        $awardList = Award::where('status', '1')->orderBy('order', 'asc')
                        ->pluck('code', 'id')->toArray();
        $hobbyList = Hobby::where('status', '1')->orderBy('order', 'asc')
                        ->pluck('code', 'id')->toArray();

        $religionList = ['0' => __('label.SELECT_RELIGION_OPT')] + Religion::pluck('name', 'id')->toArray();
        $appointmentList = array('0' => __('label.SELECT_APPOINTMENT_OPT')) + ServiceAppointment::orderBy('order', 'asc')
                        ->where('status', '1')->pluck('code', 'id')->toArray();
        $allAppointmentList = array('0' => __('label.SELECT_APPOINTMENT_OPT')) + ServiceAppointment::orderBy('order', 'asc')
                        ->where('status', '1')->pluck('code', 'id')->toArray();
        $armsServiceList = ['0' => __('label.SELECT_ARMS_SERVICE_OPT')] + ArmsService::pluck('code', 'id')->toArray();
        $unitList = ['0' => __('label.SELECT_UNIT_OPT')] + Unit::pluck('code', 'id')->toArray();
        $maritalStatusList = ['0' => __('label.SELECT_MARITAL_STATUS_OPT')] + Helper::getMaritalStatus();
        $countriesVisitedList = Country::pluck('name', 'id')->toArray();
        $courseList = ['0' => __('label.SELECT_COURSE_OPT')] + Course::pluck('name', 'id')->toArray();
        $organizationList = ['0' => __('label.SELECT_UNIT_FMN_INST_OPT')] + Unit::where('status', '1')->orderBy('order', 'asc')
                        ->pluck('code', 'id')->toArray();
        $milCourseList = ['0' => __('label.SELECT_COURSE_OPT')] + MilCourse::where('status', '1')
                        ->pluck('short_info', 'id')->toArray();

        //Division District Thana for cm permanent address
        $addressInfo = CmPermanentAddress::where('cm_basic_profile_id', !empty($cmInfoData->cm_basic_profile_id) ? $cmInfoData->cm_basic_profile_id : '0')
                ->select('id', 'cm_basic_profile_id', 'division_id', 'district_id', 'thana_id', 'address_details', 'same_as_present')
                ->first();
        $presentAddressInfo = CmPresentAddress::where('cm_basic_profile_id', !empty($cmInfoData->cm_basic_profile_id) ? $cmInfoData->cm_basic_profile_id : '0')
                ->select('id', 'cm_basic_profile_id', 'division_id', 'district_id', 'thana_id', 'address_details')
                ->first();

        $presentDistrictList = ['0' => __('label.SELECT_DISTRICT_OPT')] + District::where('division_id', !empty($presentAddressInfo->division_id) ? $presentAddressInfo->division_id : 0)
                        ->pluck('name', 'id')->toArray();
        $presentThanaList = ['0' => __('label.SELECT_THANA_OPT')] + Thana::where('district_id', !empty($presentAddressInfo->district_id) ? $presentAddressInfo->district_id : 0)
                        ->pluck('name', 'id')->toArray();

        $divisionList = ['0' => __('label.SELECT_DIVISION_OPT')] + Division::pluck('name', 'id')->toArray();
        $districtList = ['0' => __('label.SELECT_DISTRICT_OPT')] + District::where('division_id', !empty($addressInfo->division_id) ? $addressInfo->division_id : 0)
                        ->pluck('name', 'id')->toArray();
        $thanaList = ['0' => __('label.SELECT_THANA_OPT')] + Thana::where('district_id', !empty($addressInfo->district_id) ? $addressInfo->district_id : 0)
                        ->pluck('name', 'id')->toArray();

        if ($request->view == 'print') {
            return view($prinLloadView)->with(compact('cmInfoData', 'religionList', 'appointmentList', 'allAppointmentList', 'armsServiceList'
                                    , 'unitList', 'maritalStatusList', 'countriesVisitedList', 'othersInfoData', 'addressInfo'
                                    , 'divisionList', 'districtList', 'thanaList', 'civilEducationInfoData', 'serviceRecordInfoData'
                                    , 'defenceRelativeInfoData', 'courseList', 'organizationList', 'passportInfoData'
                                    , 'presentAddressInfo', 'presentDistrictList', 'presentThanaList', 'milCourseList'
                                    , 'msnDataInfo', 'countryVisitDataInfo', 'bankInfoData', 'decorationList', 'awardList'
                                    , 'hobbyList', 'childInfoData', 'qpArr', 'commissionTypeList', 'bloodGroupList', 'keyAppt'));
        }


        return view($loadView)->with(compact('cmInfoData', 'religionList', 'appointmentList', 'allAppointmentList', 'armsServiceList'
                                , 'unitList', 'maritalStatusList', 'countriesVisitedList', 'othersInfoData', 'addressInfo'
                                , 'divisionList', 'districtList', 'thanaList', 'civilEducationInfoData', 'serviceRecordInfoData'
                                , 'defenceRelativeInfoData', 'courseList', 'organizationList', 'passportInfoData'
                                , 'presentAddressInfo', 'presentDistrictList', 'presentThanaList', 'milCourseList'
                                , 'msnDataInfo', 'countryVisitDataInfo', 'bankInfoData', 'decorationList', 'awardList'
                                , 'hobbyList', 'childInfoData', 'qpArr', 'commissionTypeList', 'bloodGroupList', 'keyAppt')
        );
    }

}
