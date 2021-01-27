<?php

namespace App\Http\Controllers;

//use App\CenterToCourse;
use App\Syndicate;
use App\DsToSyn;
use App\Cm;
use App\Course;
use App\CmToSyn;
use App\Term;
use App\TermToCourse;
use App\TrainingYear;
use App\EventMarkingLock;
use App\ParticularMarkingLock;
use App\User;
use App\CmBasicProfile;
use App\ArmsService;
use App\Rank;
use App\MarkingDsToSyn;
use App\EventMksWt;
use App\EventAssessmentMarking;
use App\EventAssessmentMarkingLock;
use App\TermToEvent;
use App\MarkingGroup;
use App\CmMarkingGroup;
use App\DsMarkingGroup;
use App\TermToSubEvent;
use App\TermToSubSubEvent;
use App\TermToSubSubSubEvent;
use App\SubEventMksWt;
use App\SubSubEventMksWt;
use App\SubSubSubEventMksWt;
use App\GradingSystem;
use App\CiModerationMarking;
use Auth;
use DB;
use Illuminate\Http\Request;
use Response;
use Validator;

class EventAssessmentMarkingController extends Controller {

    public function index(Request $request) {
//get only active training year
        $activeTrainingYearInfo = TrainingYear::where('status', '1')->first();
        if (empty($activeTrainingYearInfo)) {
            $void['header'] = __('label.EVENT_ASSESSMENT');
            $void['body'] = __('label.THERE_IS_NO_ACTIVE_TRAINING_YEAR');
            return view('layouts.void', compact('void'));
        }

        $courseList = ['0' => __('label.SELECT_COURSE_OPT')] + DsMarkingGroup::join('marking_group', 'marking_group.id', 'ds_marking_group.marking_group_id')
                        ->join('course', 'course.id', '=', 'marking_group.course_id')
                        ->where('course.training_year_id', $activeTrainingYearInfo->id)
                        ->where('ds_marking_group.ds_id', Auth::user()->id)
                        ->where('course.status', '1')->orderBy('course.id', 'desc')
                        ->pluck('course.name', 'course.id')->toArray();

        return view('eventAssessmentMarking.index')->with(compact('activeTrainingYearInfo'
                                , 'courseList'));
    }

    public function getTermEvent(Request $request) {
        $termList = TermToCourse::join('term', 'term.id', '=', 'term_to_course.term_id')
                ->where('term_to_course.course_id', $request->course_id)
                ->where('term_to_course.active', '1')
                ->where('term_to_course.status', '1')
                ->select('term.id', 'term.name')
                ->first();

        $eventList = [];
        if (!empty($termList)) {
            $eventList = ['0' => __('label.SELECT_EVENT_OPT')] + DsMarkingGroup::join('marking_group', 'marking_group.id', 'ds_marking_group.marking_group_id')
                            ->join('event', 'event.id', '=', 'marking_group.event_id')
                            ->where('marking_group.course_id', $request->course_id)
                            ->where('marking_group.term_id', $termList->id)
                            ->where('ds_marking_group.ds_id', Auth::user()->id)
                            ->where('event.status', '1')
                            ->orderBy('event.order', 'asc')
                            ->pluck('event.event_code', 'event.id')
                            ->toArray();
        }

        $html = view('eventAssessmentMarking.showTermEvent', compact('termList', 'eventList'))->render();
        return response()->json(['html' => $html]);
    }

    public function getSubEvent(Request $request) {

        $subEventList = ['0' => __('label.SELECT_SUB_EVENT_OPT')] + DsMarkingGroup::join('marking_group', 'marking_group.id', 'ds_marking_group.marking_group_id')
                        ->join('sub_event', 'sub_event.id', '=', 'marking_group.sub_event_id')
                        ->where('marking_group.course_id', $request->course_id)
                        ->where('marking_group.term_id', $request->term_id)
                        ->where('marking_group.event_id', $request->event_id)
                        ->where('ds_marking_group.ds_id', Auth::user()->id)
                        ->where('sub_event.status', '1')
                        ->orderBy('sub_event.order', 'asc')
                        ->pluck('sub_event.event_code', 'sub_event.id')
                        ->toArray();

        if (sizeof($subEventList) > 1) {
            $html = view('eventAssessmentMarking.getSubEvent', compact('subEventList'))->render();
            return response()->json(['html' => $html]);
        } else {
            return $this->showMarkingCmList($request);
        }
    }

    public function getSubSubEvent(Request $request) {

        $subSubEventList = ['0' => __('label.SELECT_SUB_SUB_EVENT_OPT')] + DsMarkingGroup::join('marking_group', 'marking_group.id', 'ds_marking_group.marking_group_id')
                        ->join('sub_sub_event', 'sub_sub_event.id', '=', 'marking_group.sub_sub_event_id')
                        ->where('marking_group.course_id', $request->course_id)
                        ->where('marking_group.term_id', $request->term_id)
                        ->where('marking_group.event_id', $request->event_id)
                        ->where('marking_group.sub_event_id', $request->sub_event_id)
                        ->where('ds_marking_group.ds_id', Auth::user()->id)
                        ->where('sub_sub_event.status', '1')
                        ->orderBy('sub_sub_event.order', 'asc')
                        ->pluck('sub_sub_event.event_code', 'sub_sub_event.id')
                        ->toArray();

        if (sizeof($subSubEventList) > 1) {
            $html = view('eventAssessmentMarking.getSubSubEvent', compact('subSubEventList'))->render();
            return response()->json(['html' => $html]);
        } else {
            return $this->showMarkingCmList($request);
        }
    }

    public function getSubSubSubEvent(Request $request) {

        $subSubSubEventList = ['0' => __('label.SELECT_SUB_SUB_EVENT_OPT')] + DsMarkingGroup::join('marking_group', 'marking_group.id', 'ds_marking_group.marking_group_id')
                        ->join('sub_sub_sub_event', 'sub_sub_sub_event.id', '=', 'marking_group.sub_sub_sub_event_id')
                        ->where('marking_group.course_id', $request->course_id)
                        ->where('marking_group.term_id', $request->term_id)
                        ->where('marking_group.event_id', $request->event_id)
                        ->where('marking_group.sub_event_id', $request->sub_event_id)
                        ->where('marking_group.sub_sub_event_id', $request->sub_sub_event_id)
                        ->where('ds_marking_group.ds_id', Auth::user()->id)
                        ->where('sub_sub_sub_event.status', '1')
                        ->orderBy('sub_sub_sub_event.order', 'asc')
                        ->pluck('sub_sub_sub_event.event_code', 'sub_sub_sub_event.id')
                        ->toArray();

        if (sizeof($subSubSubEventList) > 1) {
            $html = view('eventAssessmentMarking.getSubSubSubEvent', compact('subSubSubEventList'))->render();
            return response()->json(['html' => $html]);
        } else {
            return $this->showMarkingCmList($request);
        }
    }

    public function showMarkingCmList(Request $request) {

        $cmArr = $assingedMksWtInfo = $prevMksWtArr = [];
        $cmDataArr = CmMarkingGroup::join('marking_group', 'marking_group.id', 'cm_marking_group.marking_group_id')
                ->join('cm_basic_profile', 'cm_basic_profile.id', 'cm_marking_group.cm_id')
                ->join('ds_marking_group', 'ds_marking_group.marking_group_id', 'marking_group.id')
                ->leftJoin('rank', 'rank.id', 'cm_basic_profile.rank_id')
                ->where('marking_group.course_id', $request->course_id)
                ->where('marking_group.term_id', $request->term_id)
                ->where('marking_group.event_id', $request->event_id);
        if (!empty($request->sub_event_id)) {
            $cmDataArr = $cmDataArr->where('marking_group.sub_event_id', $request->sub_event_id);
        }
        if (!empty($request->sub_sub_event_id)) {
            $cmDataArr = $cmDataArr->where('marking_group.sub_sub_event_id', $request->sub_sub_event_id);
        }
        if (!empty($request->sub_sub_sub_event_id)) {
            $cmDataArr = $cmDataArr->where('marking_group.sub_sub_sub_event_id', $request->sub_sub_sub_event_id);
        }
        $cmDataArr = $cmDataArr->where('ds_marking_group.ds_id', Auth::user()->id)
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

// get previous data
        $prevMksWtDataArr = EventAssessmentMarking::join('grading_system', 'grading_system.id', 'event_assessment_marking.grade_id')
                ->where('event_assessment_marking.course_id', $request->course_id)
                ->where('event_assessment_marking.term_id', $request->term_id)
                ->where('event_assessment_marking.event_id', $request->event_id);

        if (!empty($request->sub_event_id)) {
            $prevMksWtDataArr = $prevMksWtDataArr->where('event_assessment_marking.sub_event_id', $request->sub_event_id);
        }
        if (!empty($request->sub_sub_event_id)) {
            $prevMksWtDataArr = $prevMksWtDataArr->where('event_assessment_marking.sub_sub_event_id', $request->sub_sub_event_id);
        }
        if (!empty($request->sub_sub_sub_event_id)) {
            $prevMksWtDataArr = $prevMksWtDataArr->where('event_assessment_marking.sub_sub_sub_event_id', $request->sub_sub_sub_event_id);
        }

        $prevMksWtDataArr = $prevMksWtDataArr->where('event_assessment_marking.updated_by', Auth::user()->id)
                ->select('event_assessment_marking.cm_id', 'event_assessment_marking.mks'
                        , 'event_assessment_marking.wt', 'event_assessment_marking.percentage', 'grading_system.grade_name'
                        , 'grading_system.id as grade_id')
                ->get();

        if (!$prevMksWtDataArr->isEmpty()) {
            foreach ($prevMksWtDataArr as $prevMksWtData) {
                $prevMksWtArr[$prevMksWtData->cm_id] = $prevMksWtData->toArray();
            }
        }

        $gradeInfo = GradingSystem::select('id', 'marks_from', 'marks_to', 'grade_name')->get();

// get lock info
        $eventAssessmentMarkingLockInfo = EventAssessmentMarkingLock::select('id', 'status')
                ->where('course_id', $request->course_id)
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
        $eventAssessmentMarkingLockInfo = $eventAssessmentMarkingLockInfo->where('locked_by', Auth::user()->id)->first();
        
        // if has CI mod marking
        $ciModMarkingInfo = CiModerationMarking::select('id')
                ->where('course_id', $request->course_id)
                ->where('term_id', $request->term_id)
                ->where('event_id', $request->event_id);
        if (!empty($request->sub_event_id)) {
            $ciModMarkingInfo = $ciModMarkingInfo->where('sub_event_id', $request->sub_event_id);
        }
        if (!empty($request->sub_sub_event_id)) {
            $ciModMarkingInfo = $ciModMarkingInfo->where('sub_sub_event_id', $request->sub_sub_event_id);
        }
        if (!empty($request->sub_sub_sub_event_id)) {
            $ciModMarkingInfo = $ciModMarkingInfo->where('sub_sub_sub_event_id', $request->sub_sub_sub_event_id);
        }
        $ciModMarkingInfo = $ciModMarkingInfo->get();

        $html = view('eventAssessmentMarking.showMarkingCmList', compact('cmArr', 'assingedMksWtInfo', 'prevMksWtArr'
                        , 'gradeInfo', 'eventAssessmentMarkingLockInfo', 'ciModMarkingInfo'))->render();
        return response()->json(['html' => $html]);
    }

    public function saveEventAssessmentMarking(Request $request) {
// Validation
        $rules = $message = $errors = [];
        $rules = [
            'course_id' => 'required|not_in:0',
            'term_id' => 'required|not_in:0',
            'event_id' => 'required|not_in:0',
        ];

        $lowestMks = $request->lowest_mks;
        $cmName = $request->cm_name;
        if (!empty($request->mks_wt)) {
            foreach ($request->mks_wt as $key => $mksWtInfo) {
                if ($request->data_id == '2') {
                    $rules['mks_wt.' . $key . '.mks'] = 'required';
                    $message['mks_wt.' . $key . '.mks' . '.required'] = __('label.MKS_FIELD_IS_REQUIRED_FOR', ['CM_name' => $cmName[$key]]);
                }

                if (!empty($mksWtInfo['mks']) && $mksWtInfo['mks'] < $lowestMks) {
                    $errors[][] = __('label.YOUR_GIVEN_MKS_CAN_NOT_LESS_THAN_LOWEST_MKS_FOR', ['cm_name' => $cmName[$key]]);
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
                $data[$i]['mks'] = $mksWtInfo['mks'] ?? null;
                $data[$i]['wt'] = $mksWtInfo['wt'] ?? null;
                $data[$i]['percentage'] = $mksWtInfo['percent'] ?? null;
                $data[$i]['grade_id'] = $mksWtInfo['grade_id'] ?? 0;
                $data[$i]['updated_at'] = date('Y-m-d H:i:s');
                $data[$i]['updated_by'] = Auth::user()->id;
                $i++;
            }
        }

        $loadData['course_id'] = $request->course_id;
        $loadData['term_id'] = $request->term_id;
        $loadData['event_id'] = $request->event_id;
        $loadData['sub_event_id'] = $subEventId;
        $loadData['sub_sub_event_id'] = $subSubEventId;
        $loadData['sub_sub_sub_event_id'] = $subSubSubEventId;
// Save data

        DB::beginTransaction();

        try {
            EventAssessmentMarking::where('course_id', $request->course_id)
                    ->where('term_id', $request->term_id)
                    ->where('event_id', $request->event_id)
                    ->where('sub_event_id', $subEventId)
                    ->where('sub_sub_event_id', $subSubEventId)
                    ->where('sub_sub_sub_event_id', $subSubSubEventId)
                    ->where('updated_by', Auth::user()->id)
                    ->delete();
            if (EventAssessmentMarking::insert($data)) {
                $successMsg = __('label.EVENT_ASSESSMENT_HAS_BEEN_ASSIGNED_SUCCESSFULLY');
                $errorMsg = __('label.EVENT_ASSESSMENT_CUOLD_NOT_BE_ASSIGNED');

                if ($request->data_id == '2') {
                    $target = new EventAssessmentMarkingLock;

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

                    $successMsg = __('label.EVENT_ASSESSMENT_HAS_BEEN_ASSIGNED_AND_LOCKED_SUCCESSFULLY');
                    $errorMsg = __('label.EVENT_ASSESSMENT_COULD_NOT_BE_ASSIGNED_AND_LOCKED');
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
        $view = view('eventAssessmentMarking.showRequestForUnlockModal')->render();
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
        $eventAssessmentMarkingLockInfo = EventAssessmentMarkingLock::select('id')
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

        if (!empty($eventAssessmentMarkingLockInfo)) {
            $target = EventAssessmentMarkingLock::where('id', $eventAssessmentMarkingLockInfo->id)
                    ->update(['status' => '2', 'unlock_message' => $request->unlock_message]);
            if ($target) {
                return Response::json(['success' => true, 'loadData' => $loadData], 200);
            } else {
                return Response::json(array('success' => false, 'message' => __('label.REQUEST_FOR_UNLOCK_COULD_NOT_BE_SENT_TO_CI')), 401);
            }
        }
    }

}
