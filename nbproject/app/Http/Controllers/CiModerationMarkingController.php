<?php

namespace App\Http\Controllers;

//use App\CenterToCourse;
use App\CmBasicProfile;
use App\Course;
use App\Term;
use App\Event;
use App\SubEvent;
use App\SubSubEvent;
use App\SubSubSubEvent;
use App\TermToCourse;
use App\TrainingYear;
use App\EventAssessmentMarking;
use App\EventAssessmentMarkingLock;
use App\CiModerationMarking;
use App\CiModerationMarkingLock;
use App\ComdtModerationMarking;
use App\TermToEvent;
use App\MarkingGroup;
use App\DsMarkingGroup;
use App\TermToSubEvent;
use App\TermToSubSubEvent;
use App\TermToSubSubSubEvent;
use App\GradingSystem;
use App\CiComdtModerationMarkingLimit;
use Auth;
use Common;
use DB;
use Illuminate\Http\Request;
use Response;
use Validator;

class CiModerationMarkingController extends Controller {

    public function index(Request $request) {
//get only active training year
        $activeTrainingYearInfo = TrainingYear::where('status', '1')->first();
        if (empty($activeTrainingYearInfo)) {
            $void['header'] = __('label.CI_MODERATION_MARKING');
            $void['body'] = __('label.THERE_IS_NO_ACTIVE_TRAINING_YEAR');
            return view('layouts.void', compact('void'));
        }

        $courseList = ['0' => __('label.SELECT_COURSE_OPT')] + Course::where('course.status', '1')
                        ->where('course.training_year_id', $activeTrainingYearInfo->id)
                        ->orderBy('course.id', 'desc')
                        ->pluck('course.name', 'course.id')->toArray();

        return view('ciModerationMarking.index')->with(compact('activeTrainingYearInfo'
                                , 'courseList'));
    }

    public function getTermEvent(Request $request) {
        $activeTermInfo = TermToCourse::join('term', 'term.id', '=', 'term_to_course.term_id')
                ->where('term_to_course.course_id', $request->course_id)
                ->where('term_to_course.active', '1')
                ->where('term_to_course.status', '1')
                ->select('term.id', 'term.name')
                ->first();

        $cmDataArr = CmBasicProfile::where('cm_basic_profile.course_id', $request->course_id)
                ->where('cm_basic_profile.status', '1')
                ->get();

        $eventList = [];
        if (!empty($activeTermInfo)) {
            $eventList = ['0' => __('label.SELECT_EVENT_OPT')] + TermToEvent::join('event', 'event.id', '=', 'term_to_event.event_id')
                            ->where('term_to_event.course_id', $request->course_id)
                            ->where('term_to_event.term_id', $activeTermInfo['id'])
                            ->where('event.has_sub_event', '1')
                            ->orderBy('event.order', 'asc')
                            ->pluck('event.event_code', 'event.id')->toArray();
        }

        $html = view('ciModerationMarking.showTermEvent', compact('activeTermInfo', 'eventList', 'cmDataArr'))->render();
        return response()->json(['html' => $html]);
    }

    public function getSubEvent(Request $request) {

        $subEventList = ['0' => __('label.SELECT_SUB_EVENT_OPT')] + TermToSubEvent::join('sub_event', 'sub_event.id', '=', 'term_to_sub_event.sub_event_id')
                        ->join('event_to_sub_event', 'event_to_sub_event.sub_event_id', '=', 'term_to_sub_event.sub_event_id')
                        ->where('term_to_sub_event.course_id', $request->course_id)
                        ->where('term_to_sub_event.term_id', $request->term_id)
                        ->where('term_to_sub_event.event_id', $request->event_id)
                        ->where('event_to_sub_event.has_sub_sub_event', '1')
                        ->pluck('sub_event.event_code', 'sub_event.id')->toArray();

        if (sizeof($subEventList) > 1) {
            $html = view('ciModerationMarking.getSubEvent', compact('subEventList'))->render();
            return response()->json(['html' => $html]);
        } else {
            return $this->showMarkingCmList($request);
        }
    }

    public function getSubSubEvent(Request $request) {

        $subSubEventList = ['0' => __('label.SELECT_SUB_SUB_EVENT_OPT')] + TermToSubSubEvent::join('sub_sub_event', 'sub_sub_event.id', '=', 'term_to_sub_sub_event.sub_sub_event_id')
                        ->join('event_to_sub_sub_event', 'event_to_sub_sub_event.sub_sub_event_id', '=', 'term_to_sub_sub_event.sub_sub_event_id')
                        ->where('event_to_sub_sub_event.has_sub_sub_sub_event', '1')
                        ->where('term_to_sub_sub_event.course_id', $request->course_id)
                        ->where('term_to_sub_sub_event.term_id', $request->term_id)
                        ->where('term_to_sub_sub_event.event_id', $request->event_id)
                        ->where('term_to_sub_sub_event.sub_event_id', $request->sub_event_id)
                        ->pluck('sub_sub_event.event_code', 'sub_sub_event.id')->toArray();

        if (sizeof($subSubEventList) > 1) {
            $html = view('ciModerationMarking.getSubSubEvent', compact('subSubEventList'))->render();
            return response()->json(['html' => $html]);
        } else {
            return $this->showMarkingCmList($request);
        }
    }

    public function getSubSubSubEvent(Request $request) {

        $subSubSubEventList = ['0' => __('label.SELECT_SUB_SUB_SUB_EVENT_OPT')] + TermToSubSubSubEvent::join('sub_sub_sub_event', 'sub_sub_sub_event.id', '=', 'term_to_sub_sub_sub_event.sub_sub_sub_event_id')
                        ->join('event_to_sub_sub_sub_event', 'event_to_sub_sub_sub_event.sub_sub_sub_event_id', '=', 'term_to_sub_sub_sub_event.sub_sub_sub_event_id')
                        ->where('term_to_sub_sub_sub_event.course_id', $request->course_id)
                        ->where('term_to_sub_sub_sub_event.term_id', $request->term_id)
                        ->where('term_to_sub_sub_sub_event.event_id', $request->event_id)
                        ->where('term_to_sub_sub_sub_event.sub_event_id', $request->sub_event_id)
                        ->where('term_to_sub_sub_sub_event.sub_sub_event_id', $request->sub_sub_event_id)
                        ->pluck('sub_sub_sub_event.event_code', 'sub_sub_sub_event.id')->toArray();

        if (sizeof($subSubSubEventList) > 1) {
            $html = view('ciModerationMarking.getSubSubSubEvent', compact('subSubSubEventList'))->render();
            return response()->json(['html' => $html]);
        } else {
            return $this->showMarkingCmList($request);
        }
    }

    public function showMarkingCmList(Request $request) {

        $cmArr = $assingedMksWtInfo = $dsMksWtArr = $prevMksWtArr = [];
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

        $dsDataInfo = $dsDataInfo->select('appointment.name as appt', 'users.id as ds_id', 'users.photo'
                        , DB::raw("CONCAT(rank.code, ' ', users.full_name) as ds_name"), 'users.personal_no')
                ->get();

        $dsDataList = [];
        if (!$dsDataInfo->isEmpty()) {
            foreach ($dsDataInfo as $ds) {
                $dsDataList[$ds->ds_id] = $ds->toArray();
            }
        }

//        $dsDataList = $dsDataArr->pluck('ds_marking_group.ds_id', 'ds_marking_group.ds_id')->toArray();
        $numOfDs = !empty($dsDataList) ? sizeof($dsDataList) : '0';
//        $dsAppoinmentList = $dsDataArr->pluck('appointment.name', 'ds_marking_group.ds_id')->toArray();
        //cm List
        $cmDataArr = CmBasicProfile::leftJoin('rank', 'rank.id', 'cm_basic_profile.rank_id')
                ->where('cm_basic_profile.course_id', $request->course_id)
                ->where('cm_basic_profile.status', '1')
                ->select('cm_basic_profile.id', 'cm_basic_profile.photo', 'cm_basic_profile.personal_no'
                        , 'cm_basic_profile.full_name', 'rank.code as rank_name')
                ->orderBy('cm_basic_profile.personal_no', 'asc')
                ->get();
        if (!$cmDataArr->isEmpty()) {
            foreach ($cmDataArr as $cmData) {
                $cmArr[$cmData->id] = $cmData->toArray();
            }
        }
        
        $synArr = CmToSyn::leftJoin('syndicate', 'syndicate.id', '=', 'cm_to_syn.syn_id')
                ->leftJoin('sub_syndicate', 'sub_syndicate.id', '=', 'cm_to_syn.sub_syn_id')
                ->select('syndicate.name as syn_name', 'sub_syndicate.name as sub_syn_name', 'cm_to_syn.cm_id')
                ->where('cm_to_syn.course_id', $request->course_id)
                ->where('cm_to_syn.term_id', $request->term_id)
                ->get();
        if (!$synArr->isEmpty()) {
            foreach ($synArr as $synInfo) {
                $cmArr[$synInfo->cm_id]['syn_name'] = $synInfo->syn_name;
                $cmArr[$synInfo->cm_id]['sub_syn_name'] = $synInfo->sub_syn_name;
            }
        }

        // CI Marking Information
        $ciMksInfo = CiComdtModerationMarkingLimit::where('course_id', $request->course_id)
                        ->where('term_id', $request->term_id)
                        ->select('ci_mod')->first();

        // get ds marking data
        $dsMksWtDataArr = EventAssessmentMarking::join('grading_system', 'grading_system.id', 'event_assessment_marking.grade_id')
                ->where('event_assessment_marking.course_id', $request->course_id)
                ->where('event_assessment_marking.term_id', $request->term_id)
                ->where('event_assessment_marking.event_id', $request->event_id);

        if (!empty($request->sub_event_id)) {
            $dsMksWtDataArr = $dsMksWtDataArr->where('event_assessment_marking.sub_event_id', $request->sub_event_id);
        }
        if (!empty($request->sub_sub_event_id)) {
            $dsMksWtDataArr = $dsMksWtDataArr->where('event_assessment_marking.sub_sub_event_id', $request->sub_sub_event_id);
        }
        if (!empty($request->sub_sub_sub_event_id)) {
            $dsMksWtDataArr = $dsMksWtDataArr->where('event_assessment_marking.sub_sub_sub_event_id', $request->sub_sub_sub_event_id);
        }

        // for compare with lock table
        $totalDsMarkingList = $dsMksWtDataArr->pluck('event_assessment_marking.updated_by', 'event_assessment_marking.updated_by')
                ->toArray();

        $dsMksWtDataArr = $dsMksWtDataArr->select('event_assessment_marking.cm_id', 'event_assessment_marking.mks'
                        , 'event_assessment_marking.wt', 'event_assessment_marking.percentage', 'grading_system.grade_name'
                        , 'grading_system.id as grade_id', 'event_assessment_marking.updated_by')
                ->get();
        $dsMksSum = 0;
        if (!$dsMksWtDataArr->isEmpty()) {
            foreach ($dsMksWtDataArr as $dsMksWtData) {
                $dsMksWtArr[$dsMksWtData->updated_by][$dsMksWtData->cm_id] = $dsMksWtData->toArray();
            }
        }

        $gradeInfo = GradingSystem::select('id', 'marks_from', 'marks_to', 'grade_name')->get();

        $gradeArr = [];
        if (!$gradeInfo->isEmpty()) {
            foreach ($gradeInfo as $grade) {
                $gradeArr[$grade->grade_name]['id'] = $grade->id;
                $gradeArr[$grade->grade_name]['start'] = $grade->marks_from;
                $gradeArr[$grade->grade_name]['end'] = $grade->marks_to;
            }
        }

// for compare with marking table        
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
        $totalDsLockList = $eventAssessmentMarkingLockInfo->pluck('locked_by', 'locked_by')->toArray();




//        Start:: Average Marking
        $avgDsMksWtArr = [];
        if (!empty($cmArr)) {
            foreach ($cmArr as $cmId => $cmInfo) {
                $dsMksSum = $dsWtSum = $dsPercentSum = 0;

                if (!empty($dsDataList)) {
                    foreach ($dsDataList as $dsId => $dsInfo) {
                        $dsMksSum += (!empty($dsMksWtArr[$dsId][$cmId]['mks']) ? $dsMksWtArr[$dsId][$cmId]['mks'] : 0);

                        $dsWtSum += (!empty($dsMksWtArr[$dsId][$cmId]['wt']) ? $dsMksWtArr[$dsId][$cmId]['wt'] : 0);

                        $dsPercentSum += (!empty($dsMksWtArr[$dsId][$cmId]['percentage']) ? $dsMksWtArr[$dsId][$cmId]['percentage'] : 0);
                    }
                }
                if (!empty($totalDsMarkingList)) {
                    $avgDsMksWtArr['mks'][$cmId] = $dsMksSum / (sizeof($totalDsMarkingList));
                }
                if (!empty($totalDsMarkingList)) {
                    $avgDsMksWtArr['wt'][$cmId] = $dsWtSum / (sizeof($totalDsMarkingList));
                }
                if (!empty($totalDsMarkingList)) {
                    $avgDsMksWtArr['percentage'][$cmId] = $dsPercentSum / (sizeof($totalDsMarkingList));
                }

                if (!empty($avgDsMksWtArr['percentage'][$cmId])) {
                    foreach ($gradeArr as $letter => $gradeRange) {
                        if ($avgDsMksWtArr['percentage'][$cmId] == 100) {
                            $avgDsMksWtArr['grade'][$cmId] = "A+";
                            $avgDsMksWtArr['grade_id'][$cmId] = $gradeRange['id'];
                        }
                        if ($gradeRange['start'] <= $avgDsMksWtArr['percentage'][$cmId] && $avgDsMksWtArr['percentage'][$cmId] < $gradeRange['end']) {
                            $avgDsMksWtArr['grade'][$cmId] = $letter;
                            $avgDsMksWtArr['grade_id'][$cmId] = $gradeRange['id'];
                        }
                    }
                }
            }
        }
//        End:: Average Marking
//        Start:: Calculate After CI Moderation
        $assignedMksWtModel = !empty($request->sub_sub_sub_event_id) ? 'SubSubSubEventMksWt' : (!empty($request->sub_sub_event_id) ? 'SubSubEventMksWt' : (!empty($request->sub_event_id) ? 'SubEventMksWt' : 'EventMksWt'));

        $namespacedModel = '\\App\\' . $assignedMksWtModel;
        $assingedMksWtInfo = $namespacedModel::where('course_id', $request->course_id)
                ->where('event_id', $request->event_id);

        if (!empty($request->sub_event_id)) {
            $assingedMksWtInfo = $assingedMksWtInfo->where('sub_event_id', $request->sub_event_id);
        }
        if (!empty($request->sub_sub_event_id)) {
            $assingedMksWtInfo = $assingedMksWtInfo->where('sub_sub_event_id', $request->sub_sub_event_id);
        }
        if (!empty($request->sub_sub_sub_event_id)) {
            $assingedMksWtInfo = $assingedMksWtInfo->where('sub_sub_sub_event_id', $request->sub_sub_sub_event_id);
        }
        $assingedMksWtInfo = $assingedMksWtInfo->select('mks_limit', 'highest_mks_limit', 'lowest_mks_limit', 'wt')
                ->first();
//        End:: Calculate After CI Moderation  
// get previous data
        $prevMksWtDataArr = CiModerationMarking::join('grading_system', 'grading_system.id', 'ci_moderation_marking.grade_id')
                ->where('ci_moderation_marking.course_id', $request->course_id)
                ->where('ci_moderation_marking.term_id', $request->term_id)
                ->where('ci_moderation_marking.event_id', $request->event_id);

        if (!empty($request->sub_event_id)) {
            $prevMksWtDataArr = $prevMksWtDataArr->where('ci_moderation_marking.sub_event_id', $request->sub_event_id);
        }
        if (!empty($request->sub_sub_event_id)) {
            $prevMksWtDataArr = $prevMksWtDataArr->where('ci_moderation_marking.sub_sub_event_id', $request->sub_sub_event_id);
        }
        if (!empty($request->sub_sub_sub_event_id)) {
            $prevMksWtDataArr = $prevMksWtDataArr->where('ci_moderation_marking.sub_sub_sub_event_id', $request->sub_sub_sub_event_id);
        }

        $prevMksWtDataArr = $prevMksWtDataArr->where('ci_moderation_marking.updated_by', Auth::user()->id)
                ->select('ci_moderation_marking.cm_id', 'ci_moderation_marking.ci_moderation', 'ci_moderation_marking.mks'
                        , 'ci_moderation_marking.wt', 'ci_moderation_marking.percentage', 'grading_system.grade_name'
                        , 'grading_system.id as grade_id')
                ->get();

        if (!$prevMksWtDataArr->isEmpty()) {
            foreach ($prevMksWtDataArr as $prevMksWtData) {
                $prevMksWtArr[$prevMksWtData->cm_id] = $prevMksWtData->toArray();
            }
        }



// get lock info
        $ciModerationMarkingLockInfo = CiModerationMarkingLock::where('course_id', $request->course_id)
                ->where('term_id', $request->term_id)
                ->where('event_id', $request->event_id);
        if (!empty($request->sub_event_id)) {
            $ciModerationMarkingLockInfo = $ciModerationMarkingLockInfo->where('sub_event_id', $request->sub_event_id);
        }
        if (!empty($request->sub_sub_event_id)) {
            $ciModerationMarkingLockInfo = $ciModerationMarkingLockInfo->where('sub_sub_event_id', $request->sub_sub_event_id);
        }
        if (!empty($request->sub_sub_sub_event_id)) {
            $ciModerationMarkingLockInfo = $ciModerationMarkingLockInfo->where('sub_sub_sub_event_id', $request->sub_sub_sub_event_id);
        }
        $ciModerationMarkingLockInfo = $ciModerationMarkingLockInfo->where('locked_by', Auth::user()->id)
                        ->select('id', 'status')->first();
        
        //if has comdt mod marking
        $comdtModMarkingInfo = ComdtModerationMarking::select('id')
                ->where('course_id', $request->course_id)
                ->where('term_id', $request->term_id)
                ->where('event_id', $request->event_id);
        if (!empty($request->sub_event_id)) {
            $comdtModMarkingInfo = $comdtModMarkingInfo->where('sub_event_id', $request->sub_event_id);
        }
        if (!empty($request->sub_sub_event_id)) {
            $comdtModMarkingInfo = $comdtModMarkingInfo->where('sub_sub_event_id', $request->sub_sub_event_id);
        }
        if (!empty($request->sub_sub_sub_event_id)) {
            $comdtModMarkingInfo = $comdtModMarkingInfo->where('sub_sub_sub_event_id', $request->sub_sub_sub_event_id);
        }
        $comdtModMarkingInfo = $comdtModMarkingInfo->get();


        $html = view('ciModerationMarking.showMarkingCmList', compact('dsDataList', 'numOfDs', 'cmArr'
                        , 'ciMksInfo', 'dsMksWtArr', 'gradeInfo', 'ciModerationMarkingLockInfo'
                        , 'totalDsMarkingList', 'totalDsLockList', 'avgDsMksWtArr', 'assingedMksWtInfo'
                        , 'prevMksWtArr', 'comdtModMarkingInfo'))->render();
        return response()->json(['html' => $html]);
    }

    public function saveCiModerationMarking(Request $request) {

// Validation
        $rules = $message = $errors = [];
        $rules = [
            'course_id' => 'required|not_in:0',
            'term_id' => 'required|not_in:0',
            'event_id' => 'required|not_in:0',
        ];

        $lowestModeration = $request->lowest_moderation;
        $cmName = $request->cm_name;
        if (!empty($request->mks_wt)) {
            foreach ($request->mks_wt as $key => $mksWtInfo) {
                if ($request->data_id == '2') {
                    $rules['mks_wt.' . $key . '.mks'] = 'required';
                    $message['mks_wt.' . $key . '.mks' . '.required'] = __('label.MKS_WT_AFTER_MODERATION_FIELD_IS_REQUIRED_FOR', ['cm_name' => $cmName[$key], 'attr' => __('label.MKS')]);
                    $rules['mks_wt.' . $key . '.wt'] = 'required';
                    $message['mks_wt.' . $key . '.wt' . '.required'] = __('label.MKS_WT_AFTER_MODERATION_FIELD_IS_REQUIRED_FOR', ['cm_name' => $cmName[$key], 'attr' => __('label.WT')]);
                    $rules['mks_wt.' . $key . '.percent'] = 'required';
                    $message['mks_wt.' . $key . '.percent' . '.required'] = __('label.MKS_WT_AFTER_MODERATION_FIELD_IS_REQUIRED_FOR', ['cm_name' => $cmName[$key], 'attr' => '%']);
                    $rules['mks_wt.' . $key . '.grade_id'] = 'required';
                    $message['mks_wt.' . $key . '.grade_id' . '.required'] = __('label.MKS_WT_AFTER_MODERATION_FIELD_IS_REQUIRED_FOR', ['cm_name' => $cmName[$key], 'attr' => __('label.GRADE')]);
                }
            }
        }
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()), 400);
        }

        if (!empty($errors)) {
            return Response::json(array('success' => false, 'message' => $errors), 400);
        }
// End validation
        $subEventId = !empty($request->sub_event_id) ? $request->sub_event_id : 0;
        $subSubEventId = !empty($request->sub_sub_event_id) ? $request->sub_sub_event_id : 0;
        $subSubSubEventId = !empty($request->sub_sub_sub_event_id) ? $request->sub_sub_sub_event_id : 0;


        $data = [];
        $i = 0;
        if (!empty($request->mks_wt)) {
            foreach ($request->mks_wt as $cmId => $mksWtInfo) {
                $data[$i]['course_id'] = $request->course_id;
                $data[$i]['term_id'] = $request->term_id;
                $data[$i]['event_id'] = $request->event_id;
                $data[$i]['sub_event_id'] = $subEventId;
                $data[$i]['sub_sub_event_id'] = $subSubEventId;
                $data[$i]['sub_sub_sub_event_id'] = $subSubSubEventId;
                $data[$i]['cm_id'] = $cmId ?? 0;
                $data[$i]['ci_moderation'] = $mksWtInfo['moderation'] ?? null;
                $data[$i]['mks'] = $mksWtInfo['mks'] ?? null;
                $data[$i]['wt'] = $mksWtInfo['wt'] ?? null;
                $data[$i]['percentage'] = $mksWtInfo['percent'] ?? null;
                $data[$i]['grade_id'] = $mksWtInfo['grade_id'] ?? 0;
                $data[$i]['updated_at'] = date('Y-m-d H:i:s');
                $data[$i]['updated_by'] = Auth::user()->id;
                $i++;
            }
        }

//        echo '<pre>';
//        print_r($data);
//        exit;

        $loadData['course_id'] = $request->course_id;
        $loadData['term_id'] = $request->term_id;
        $loadData['event_id'] = $request->event_id;
        $loadData['sub_event_id'] = $subEventId;
        $loadData['sub_sub_event_id'] = $subSubEventId;
        $loadData['sub_sub_sub_event_id'] = $subSubSubEventId;
// Save data

        DB::beginTransaction();

        try {
            CiModerationMarking::where('course_id', $request->course_id)
                    ->where('term_id', $request->term_id)
                    ->where('event_id', $request->event_id)
                    ->where('sub_event_id', $subEventId)
                    ->where('sub_sub_event_id', $subSubEventId)
                    ->where('sub_sub_sub_event_id', $subSubSubEventId)
                    ->delete();
            if (CiModerationMarking::insert($data)) {
                $successMsg = __('label.CI_MODERATION_HAS_BEEN_ASSIGNED_SUCCESSFULLY');
                $errorMsg = __('label.CI_MODERATION_CUOLD_NOT_BE_ASSIGNED');

                if ($request->data_id == '2') {
                    $target = new CiModerationMarkingLock;

                    $target->course_id = $request->course_id;
                    $target->term_id = $request->term_id;
                    $target->event_id = $request->event_id;
                    $target->sub_event_id = $subEventId;
                    $target->sub_sub_event_id = $subSubEventId;
                    $target->sub_sub_sub_event_id = $subSubSubEventId;
                    $target->status = 1;
                    $target->locked_at = date('Y-m-d H:i:s');
                    $target->locked_by = Auth::user()->id;
                    $target->save();

                    $successMsg = __('label.CI_MODERATION_HAS_BEEN_ASSIGNED_AND_LOCKED_SUCCESSFULLY');
                    $errorMsg = __('label.CI_MODERATION_COULD_NOT_BE_ASSIGNED_AND_LOCKED');
                }
            }
            DB::commit();
            return Response::json(['success' => true, 'message' => $successMsg, 'loadData' => $loadData], 200);
        } catch (Exception $ex) {
            DB::rollback();
            return Response::json(['success' => false, 'message' => $errorMsg], 401);
        }
    }

    public function getRequestForUnlockModal(Request $request) {
        $view = view('ciModerationMarking.showRequestForUnlockModal')->render();
        return response()->json(['html' => $view]);
    }

    public function saveRequestForUnlock(Request $request) {

// validation
        $rules = [
            'unlock_message' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()), 400);
        }
// End validation
        $subEventId = !empty($request->sub_event_id) ? $request->sub_event_id : 0;
        $subSubEventId = !empty($request->sub_sub_event_id) ? $request->sub_sub_event_id : 0;
        $subSubSubEventId = !empty($request->sub_sub_sub_event_id) ? $request->sub_sub_sub_event_id : 0;
// get lock info
        $ciModerationMarkingLockInfo = CiModerationMarkingLock::select('id')
                        ->where('course_id', $request->course_id)
                        ->where('term_id', $request->term_id)
                        ->where('event_id', $request->event_id)
                        ->where('sub_event_id', $subEventId)
                        ->where('sub_sub_event_id', $subSubEventId)
                        ->where('sub_sub_sub_event_id', $subSubSubEventId)
                        ->where('locked_by', Auth::user()->id)->first();

        $loadData['course_id'] = $request->course_id;
        $loadData['term_id'] = $request->term_id;
        $loadData['event_id'] = $request->event_id;
        $loadData['sub_event_id'] = $subEventId;
        $loadData['sub_sub_event_id'] = $subSubEventId;
        $loadData['sub_sub_sub_event_id'] = $subSubSubEventId;

        if (!empty($ciModerationMarkingLockInfo)) {
            $target = CiModerationMarkingLock::where('id', $ciModerationMarkingLockInfo->id)
                    ->update(['status' => '2', 'unlock_message' => $request->unlock_message]);
            if ($target) {
                return Response::json(['success' => true, 'loadData' => $loadData], 200);
            } else {
                return Response::json(array('success' => false, 'message' => __('label.REQUEST_FOR_UNLOCK_COULD_NOT_BE_SENT_TO_COMDT')), 401);
            }
        }
    }

    public function getDsMarkingSummary(Request $request){
        $loadView = 'ciModerationMarking.showDsMarkingSummaryModal';
        return Common::getDsMarkingSummary($request, $loadView);
    }

}
