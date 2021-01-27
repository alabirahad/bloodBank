<?php

namespace App\Http\Controllers;

use App\Course;
use App\TrainingYear;
use App\User;
use App\CriteriaWiseWt;
use App\GradingSystem;
use App\TermToCourse;
use App\TermToEvent;
use App\TermToSubEvent;
use App\TermToSubSubEvent;
use App\TermToSubSubSubEvent;
use App\CmBasicProfile;
use App\CmToSyn;
use App\EventAssessmentMarking;
use App\CiObsnMarking;
use App\CiObsnMarkingLock;
use Auth;
use DB;
use Common;
use Validator;
use Illuminate\Http\Request;
use Response;

class CiObsnMarkingController extends Controller {

    public function index(Request $request) {

        //get only active training year
        $activeTrainingYearInfo = TrainingYear::where('status', '1')->first();

        if (empty($activeTrainingYearInfo)) {
            $void['header'] = __('label.OBSN_MARKING');
            $void['body'] = __('label.THERE_IS_NO_ACTIVE_TRAINING_YEAR');
            return view('layouts.void', compact('void'));
        }

        $courseList = ['0' => __('label.SELECT_COURSE_OPT')] + Course::where('training_year_id', $activeTrainingYearInfo->id)
                        ->where('status', '1')->orderBy('id', 'desc')->pluck('name', 'id')->toArray();

        return view('ciObsnMarking.index')->with(compact('activeTrainingYearInfo', 'courseList'));
    }

    public function showCmMarkingList(Request $request) {

        // get assigned ci obsn wt
        $assignedObsnInfo = CriteriaWiseWt::select('ci_obsn_wt')->where('course_id', $request->course_id)->first();

        // get grade system
        $gradeInfo = GradingSystem::select('id', 'marks_from', 'marks_to', 'grade_name')->get();

        // check all terms are closed 
        $openTerms = TermToCourse::select('id')->where('status', '1')->where('course_id', $request->course_id)->count();

        $eventMksWtArr = $rowSpanArr = $cmArr = $achieveMksWtArr = [];
        //event info
        $eventInfo = TermToEvent::join('event', 'event.id', '=', 'term_to_event.event_id')
                ->leftJoin('event_mks_wt', 'event_mks_wt.event_id', '=', 'term_to_event.event_id')
                ->where('term_to_event.course_id', $request->course_id)
                ->where('event.status', '1')
                ->select('event.event_code', 'event.id as event_id', 'event_mks_wt.mks_limit', 'event_mks_wt.highest_mks_limit'
                        , 'event_mks_wt.lowest_mks_limit', 'event_mks_wt.wt', 'event.has_sub_event')
                ->get();

        if (!$eventInfo->isEmpty()) {
            foreach ($eventInfo as $ev) {
                $eventMksWtArr['event'][$ev->event_id]['name'] = $ev->event_code ?? '';
                if (empty($ev->has_sub_event)) {
                    $eventMksWtArr['mks_wt'][$ev->event_id][0][0][0]['mks_limit'] = !empty($ev->mks_limit) ? $ev->mks_limit : 0;
                    $eventMksWtArr['mks_wt'][$ev->event_id][0][0][0]['highest_mks_limit'] = !empty($ev->highest_mks_limit) ? $ev->highest_mks_limit : 0;
                    $eventMksWtArr['mks_wt'][$ev->event_id][0][0][0]['lowest_mks_limit'] = !empty($ev->lowest_mks_limit) ? $ev->lowest_mks_limit : 0;
                    $eventMksWtArr['mks_wt'][$ev->event_id][0][0][0]['wt'] = !empty($ev->wt) ? $ev->wt : 0;

                    $eventMksWtArr['total_wt'] = !empty($eventMksWtArr['total_wt']) ? $eventMksWtArr['total_wt'] : 0;
                    $eventMksWtArr['total_wt'] += !empty($ev->wt) ? $ev->wt : 0;
                    $eventMksWtArr['total_mks_limit'] = !empty($eventMksWtArr['total_mks_limit']) ? $eventMksWtArr['total_mks_limit'] : 0;
                    $eventMksWtArr['total_mks_limit'] += !empty($ev->mks_limit) ? $ev->mks_limit : 0;

                    $eventMksWtArr['total_wt_after_ci'] = $eventMksWtArr['total_wt'] + (!empty($assignedObsnInfo->ci_obsn_wt) ? $assignedObsnInfo->ci_obsn_wt : 0);
                }
            }
        }

        //sub event info
        $subEventInfo = TermToSubEvent::join('sub_event', 'sub_event.id', '=', 'term_to_sub_event.sub_event_id')
                ->join('event', 'event.id', '=', 'term_to_sub_event.event_id')
                ->join('event_to_sub_event', function($join) {
                    $join->on('event_to_sub_event.event_id', '=', 'term_to_sub_event.event_id');
                    $join->on('event_to_sub_event.sub_event_id', '=', 'term_to_sub_event.sub_event_id');
                })
                ->join('sub_event_mks_wt', function($join) {
                    $join->on('sub_event_mks_wt.event_id', '=', 'term_to_sub_event.event_id');
                    $join->on('sub_event_mks_wt.sub_event_id', '=', 'term_to_sub_event.sub_event_id');
                })
                ->where('term_to_sub_event.course_id', $request->course_id)
                ->where('sub_event.status', '1')
                ->select('sub_event.event_code as sub_event_code', 'sub_event.id as sub_event_id', 'sub_event_mks_wt.mks_limit', 'sub_event_mks_wt.highest_mks_limit'
                        , 'sub_event_mks_wt.lowest_mks_limit', 'sub_event_mks_wt.wt', 'event_to_sub_event.has_sub_sub_event'
                        , 'event_to_sub_event.event_id', 'event.event_code')
                ->get();


        if (!$subEventInfo->isEmpty()) {
            foreach ($subEventInfo as $subEv) {
                $eventMksWtArr['event'][$subEv->event_id]['name'] = $subEv->event_code ?? '';
                $eventMksWtArr['event'][$subEv->event_id][$subEv->sub_event_id]['name'] = $subEv->sub_event_code ?? '';
                if (empty($subEv->has_sub_sub_event)) {
                    $eventMksWtArr['mks_wt'][$subEv->event_id][$subEv->sub_event_id][0][0]['mks_limit'] = !empty($subEv->mks_limit) ? $subEv->mks_limit : 0;
                    $eventMksWtArr['mks_wt'][$subEv->event_id][$subEv->sub_event_id][0][0]['highest_mks_limit'] = !empty($subEv->highest_mks_limit) ? $subEv->highest_mks_limit : 0;
                    $eventMksWtArr['mks_wt'][$subEv->event_id][$subEv->sub_event_id][0][0]['lowest_mks_limit'] = !empty($subEv->lowest_mks_limit) ? $subEv->lowest_mks_limit : 0;
                    $eventMksWtArr['mks_wt'][$subEv->event_id][$subEv->sub_event_id][0][0]['wt'] = !empty($subEv->wt) ? $subEv->wt : 0;

                    $eventMksWtArr['total_wt'] = !empty($eventMksWtArr['total_wt']) ? $eventMksWtArr['total_wt'] : 0;
                    $eventMksWtArr['total_wt'] += !empty($subEv->wt) ? $subEv->wt : 0;
                    $eventMksWtArr['total_mks_limit'] = !empty($eventMksWtArr['total_mks_limit']) ? $eventMksWtArr['total_mks_limit'] : 0;
                    $eventMksWtArr['total_mks_limit'] += !empty($subEv->mks_limit) ? $subEv->mks_limit : 0;

                    $eventMksWtArr['total_wt_after_ci'] = $eventMksWtArr['total_wt'] + (!empty($assignedObsnInfo->ci_obsn_wt) ? $assignedObsnInfo->ci_obsn_wt : 0);
                }
            }
        }

        //sub sub event info
        $subSubEventInfo = TermToSubSubEvent::join('sub_sub_event', 'sub_sub_event.id', '=', 'term_to_sub_sub_event.sub_sub_event_id')
                ->join('sub_event', 'sub_event.id', '=', 'term_to_sub_sub_event.sub_event_id')
                ->join('event', 'event.id', '=', 'term_to_sub_sub_event.event_id')
                ->join('event_to_sub_sub_event', function($join) {
                    $join->on('event_to_sub_sub_event.event_id', '=', 'term_to_sub_sub_event.event_id');
                    $join->on('event_to_sub_sub_event.sub_event_id', '=', 'term_to_sub_sub_event.sub_event_id');
                    $join->on('event_to_sub_sub_event.sub_sub_event_id', '=', 'term_to_sub_sub_event.sub_sub_event_id');
                })
                ->leftJoin('sub_sub_event_mks_wt', function($join) {
                    $join->on('sub_sub_event_mks_wt.event_id', '=', 'term_to_sub_sub_event.event_id');
                    $join->on('sub_sub_event_mks_wt.sub_event_id', '=', 'term_to_sub_sub_event.sub_event_id');
                    $join->on('sub_sub_event_mks_wt.sub_sub_event_id', '=', 'term_to_sub_sub_event.sub_sub_event_id');
                })
                ->where('term_to_sub_sub_event.course_id', $request->course_id)
                ->where('sub_sub_event.status', '1')
                ->select('sub_sub_event.event_code as sub_sub_event_code', 'sub_sub_event.id as sub_sub_event_id', 'sub_sub_event_mks_wt.mks_limit', 'sub_sub_event_mks_wt.highest_mks_limit'
                        , 'sub_sub_event_mks_wt.lowest_mks_limit', 'sub_sub_event_mks_wt.wt', 'event_to_sub_sub_event.has_sub_sub_sub_event'
                        , 'event_to_sub_sub_event.event_id', 'event_to_sub_sub_event.sub_event_id'
                        , 'sub_event.event_code as sub_event_code', 'event.event_code')
                ->get();


        if (!$subSubEventInfo->isEmpty()) {
            foreach ($subSubEventInfo as $subSubEv) {
                $eventMksWtArr['event'][$subSubEv->event_id]['name'] = $subSubEv->event_code ?? '';
                $eventMksWtArr['event'][$subSubEv->event_id][$subSubEv->sub_event_id]['name'] = $subSubEv->sub_event_code ?? '';
                $eventMksWtArr['event'][$subSubEv->event_id][$subSubEv->sub_event_id][$subSubEv->sub_sub_event_id]['name'] = $subSubEv->sub_sub_event_code ?? '';
                if (empty($subSubEv->has_sub_sub_sub_event)) {
                    $eventMksWtArr['mks_wt'][$subSubEv->event_id][$subSubEv->sub_event_id][$subSubEv->sub_sub_event_id][0]['mks_limit'] = !empty($subSubEv->mks_limit) ? $subSubEv->mks_limit : 0;
                    $eventMksWtArr['mks_wt'][$subSubEv->event_id][$subSubEv->sub_event_id][$subSubEv->sub_sub_event_id][0]['highest_mks_limit'] = !empty($subSubEv->highest_mks_limit) ? $subSubEv->highest_mks_limit : 0;
                    $eventMksWtArr['mks_wt'][$subSubEv->event_id][$subSubEv->sub_event_id][$subSubEv->sub_sub_event_id][0]['lowest_mks_limit'] = !empty($subSubEv->lowest_mks_limit) ? $subSubEv->lowest_mks_limit : 0;
                    $eventMksWtArr['mks_wt'][$subSubEv->event_id][$subSubEv->sub_event_id][$subSubEv->sub_sub_event_id][0]['wt'] = !empty($subSubEv->wt) ? $subSubEv->wt : 0;

                    $eventMksWtArr['total_wt'] = !empty($eventMksWtArr['total_wt']) ? $eventMksWtArr['total_wt'] : 0;
                    $eventMksWtArr['total_wt'] += !empty($subSubEv->wt) ? $subSubEv->wt : 0;
                    $eventMksWtArr['total_mks_limit'] = !empty($eventMksWtArr['total_mks_limit']) ? $eventMksWtArr['total_mks_limit'] : 0;
                    $eventMksWtArr['total_mks_limit'] += !empty($subSubEv->mks_limit) ? $subSubEv->mks_limit : 0;

                    $eventMksWtArr['total_wt_after_ci'] = $eventMksWtArr['total_wt'] + (!empty($assignedObsnInfo->ci_obsn_wt) ? $assignedObsnInfo->ci_obsn_wt : 0);
                }
            }
        }

        //sub sub sub event info
        $subSubSubEventInfo = TermToSubSubSubEvent::join('sub_sub_sub_event', 'sub_sub_sub_event.id', '=', 'term_to_sub_sub_sub_event.sub_sub_sub_event_id')
                ->join('sub_sub_event', 'sub_sub_event.id', '=', 'term_to_sub_sub_sub_event.sub_sub_event_id')
                ->join('sub_event', 'sub_event.id', '=', 'term_to_sub_sub_sub_event.sub_event_id')
                ->join('event', 'event.id', '=', 'term_to_sub_sub_sub_event.event_id')
                ->join('event_to_sub_sub_sub_event', function($join) {
                    $join->on('event_to_sub_sub_sub_event.event_id', '=', 'term_to_sub_sub_sub_event.event_id');
                    $join->on('event_to_sub_sub_sub_event.sub_event_id', '=', 'term_to_sub_sub_sub_event.sub_event_id');
                    $join->on('event_to_sub_sub_sub_event.sub_sub_event_id', '=', 'term_to_sub_sub_sub_event.sub_sub_event_id');
                    $join->on('event_to_sub_sub_sub_event.sub_sub_sub_event_id', '=', 'term_to_sub_sub_sub_event.sub_sub_sub_event_id');
                })
                ->leftJoin('sub_sub_sub_event_mks_wt', function($join) {
                    $join->on('sub_sub_sub_event_mks_wt.event_id', '=', 'term_to_sub_sub_sub_event.event_id');
                    $join->on('sub_sub_sub_event_mks_wt.sub_event_id', '=', 'term_to_sub_sub_sub_event.sub_event_id');
                    $join->on('sub_sub_sub_event_mks_wt.sub_sub_event_id', '=', 'term_to_sub_sub_sub_event.sub_sub_event_id');
                    $join->on('sub_sub_sub_event_mks_wt.sub_sub_sub_event_id', '=', 'term_to_sub_sub_sub_event.sub_sub_sub_event_id');
                })
                ->where('term_to_sub_sub_sub_event.course_id', $request->course_id)
                ->where('sub_sub_sub_event.status', '1')
                ->select('sub_sub_sub_event.event_code as sub_sub_sub_event_code', 'sub_sub_sub_event.id as sub_sub_sub_event_id', 'sub_sub_sub_event_mks_wt.mks_limit', 'sub_sub_sub_event_mks_wt.highest_mks_limit'
                        , 'sub_sub_sub_event_mks_wt.lowest_mks_limit', 'sub_sub_sub_event_mks_wt.wt', 'event_to_sub_sub_sub_event.event_id'
                        , 'event_to_sub_sub_sub_event.sub_event_id', 'event_to_sub_sub_sub_event.sub_sub_event_id'
                        , 'sub_sub_event.event_code as sub_sub_event_code', 'sub_event.event_code as sub_event_code', 'event.event_code')
                ->get();


        if (!$subSubSubEventInfo->isEmpty()) {
            foreach ($subSubSubEventInfo as $subSubSubEv) {
                $eventMksWtArr['event'][$subSubSubEv->event_id]['name'] = $subSubSubEv->event_code ?? '';
                $eventMksWtArr['event'][$subSubSubEv->event_id][$subSubSubEv->sub_event_id]['name'] = $subSubSubEv->sub_event_code ?? '';
                $eventMksWtArr['event'][$subSubSubEv->event_id][$subSubSubEv->sub_event_id][$subSubSubEv->sub_sub_event_id]['name'] = $subSubSubEv->sub_sub_event_code ?? '';
                $eventMksWtArr['event'][$subSubSubEv->event_id][$subSubSubEv->sub_event_id][$subSubSubEv->sub_sub_event_id][$subSubSubEv->sub_sub_sub_event_id]['name'] = $subSubSubEv->sub_sub_sub_event_code ?? '';

                $eventMksWtArr['mks_wt'][$subSubSubEv->event_id][$subSubSubEv->sub_event_id][$subSubSubEv->sub_sub_event_id][$subSubSubEv->sub_sub_sub_event_id]['mks_limit'] = !empty($subSubSubEv->mks_limit) ? $subSubSubEv->mks_limit : 0;
                $eventMksWtArr['mks_wt'][$subSubSubEv->event_id][$subSubSubEv->sub_event_id][$subSubSubEv->sub_sub_event_id][$subSubSubEv->sub_sub_sub_event_id]['highest_mks_limit'] = !empty($subSubSubEv->highest_mks_limit) ? $subSubSubEv->highest_mks_limit : 0;
                $eventMksWtArr['mks_wt'][$subSubSubEv->event_id][$subSubSubEv->sub_event_id][$subSubSubEv->sub_sub_event_id][$subSubSubEv->sub_sub_sub_event_id]['lowest_mks_limit'] = !empty($subSubSubEv->lowest_mks_limit) ? $subSubSubEv->lowest_mks_limit : 0;
                $eventMksWtArr['mks_wt'][$subSubSubEv->event_id][$subSubSubEv->sub_event_id][$subSubSubEv->sub_sub_event_id][$subSubSubEv->sub_sub_sub_event_id]['wt'] = !empty($subSubSubEv->wt) ? $subSubSubEv->wt : 0;

                $eventMksWtArr['total_wt'] = !empty($eventMksWtArr['total_wt']) ? $eventMksWtArr['total_wt'] : 0;
                $eventMksWtArr['total_wt'] += !empty($subSubSubEv->wt) ? $subSubSubEv->wt : 0;
                $eventMksWtArr['total_mks_limit'] = !empty($eventMksWtArr['total_mks_limit']) ? $eventMksWtArr['total_mks_limit'] : 0;
                $eventMksWtArr['total_mks_limit'] += !empty($subSubSubEv->mks_limit) ? $subSubSubEv->mks_limit : 0;

                $eventMksWtArr['total_wt_after_ci'] = $eventMksWtArr['total_wt'] + (!empty($assignedObsnInfo->ci_obsn_wt) ? $assignedObsnInfo->ci_obsn_wt : 0);
            }
        }


        if (!empty($eventMksWtArr['mks_wt'])) {
            foreach ($eventMksWtArr['mks_wt'] as $eventId => $evInfo) {
                foreach ($evInfo as $subEventId => $subEvInfo) {
                    foreach ($subEvInfo as $subSubEventId => $subSubEvInfo) {
                        foreach ($subSubEvInfo as $subSubSubEventId => $subSubSubEvInfo) {
                            $rowSpanArr['event'][$eventId] = !empty($rowSpanArr['event'][$eventId]) ? $rowSpanArr['event'][$eventId] : 0;
                            $rowSpanArr['event'][$eventId] += 1;

                            $rowSpanArr['sub_event'][$eventId][$subEventId] = !empty($rowSpanArr['sub_event'][$eventId][$subEventId]) ? $rowSpanArr['sub_event'][$eventId][$subEventId] : 0;
                            $rowSpanArr['sub_event'][$eventId][$subEventId] += 1;

                            $rowSpanArr['sub_sub_event'][$eventId][$subEventId][$subSubEventId] = !empty($rowSpanArr['sub_sub_event'][$eventId][$subEventId][$subSubEventId]) ? $rowSpanArr['sub_sub_event'][$eventId][$subEventId][$subSubEventId] : 0;
                            $rowSpanArr['sub_sub_event'][$eventId][$subEventId][$subSubEventId] += 1;

                            $rowSpanArr['sub_sub_sub_event'][$eventId][$subEventId][$subSubEventId][$subSubSubEventId] = !empty($rowSpanArr['sub_sub_sub_event'][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]) ? $rowSpanArr['sub_sub_sub_event'][$eventId][$subEventId][$subSubEventId][$subSubSubEventId] : 0;
                            $rowSpanArr['sub_sub_sub_event'][$eventId][$subEventId][$subSubEventId][$subSubSubEventId] += 1;
                        }
                    }
                }
            }
        }

        $cmDataArr = CmBasicProfile::leftJoin('rank', 'rank.id', 'cm_basic_profile.rank_id')
                ->where('cm_basic_profile.status', '1')
                ->where('cm_basic_profile.course_id', $request->course_id)
                ->select('cm_basic_profile.id', 'cm_basic_profile.photo', 'cm_basic_profile.personal_no'
                        , 'cm_basic_profile.full_name', 'rank.code as rank_name')
                ->orderBy('cm_basic_profile.personal_no', 'asc')
                ->get();
        if (!$cmDataArr->isEmpty()) {
            foreach ($cmDataArr as $cmData) {
                $cmArr[$cmData->id] = $cmData->toArray();
            }
        }

        $synArr = CmToSyn::leftJoin('term_to_course', 'term_to_course.term_id', '=', 'cm_to_syn.term_id')
                ->leftJoin('syndicate', 'syndicate.id', '=', 'cm_to_syn.syn_id')
                ->leftJoin('sub_syndicate', 'sub_syndicate.id', '=', 'cm_to_syn.sub_syn_id')
                ->select('syndicate.name as syn_name', 'sub_syndicate.name as sub_syn_name', 'cm_to_syn.cm_id')
                ->where('term_to_course.status', '2')
                ->where('term_to_course.active', '1')
                ->where('cm_to_syn.course_id', $request->course_id)
                ->get();
        if (!$synArr->isEmpty()) {
            foreach ($synArr as $synInfo) {
                $cmArr[$synInfo->cm_id]['syn_name'] = $synInfo->syn_name;
                $cmArr[$synInfo->cm_id]['sub_syn_name'] = $synInfo->sub_syn_name;
            }
        }

        $achieveEventMksWtDataArr = EventAssessmentMarking::leftJoin('grading_system', 'grading_system.id', 'event_assessment_marking.grade_id')
                ->select('event_assessment_marking.event_id', 'event_assessment_marking.sub_event_id', 'event_assessment_marking.sub_sub_event_id'
                        , 'event_assessment_marking.sub_sub_sub_event_id', 'event_assessment_marking.cm_id'
                        , 'event_assessment_marking.mks', 'event_assessment_marking.wt', 'event_assessment_marking.percentage'
                        , 'grading_system.grade_name')
                ->where('course_id', $request->course_id)
                ->get();

        if (!$achieveEventMksWtDataArr->isEmpty()) {
            foreach ($achieveEventMksWtDataArr as $mwInfo) {
                $achieveMksWtArr[$mwInfo->cm_id][$mwInfo->event_id][$mwInfo->sub_event_id][$mwInfo->sub_sub_event_id][$mwInfo->sub_sub_sub_event_id] = $mwInfo->toArray();

                $cmArr[$mwInfo->cm_id]['total_term_mks'] = !empty($cmArr[$mwInfo->cm_id]['total_term_mks']) ? $cmArr[$mwInfo->cm_id]['total_term_mks'] : 0;
                $cmArr[$mwInfo->cm_id]['total_term_mks'] += $mwInfo->mks;

                $cmArr[$mwInfo->cm_id]['total_term_wt'] = !empty($cmArr[$mwInfo->cm_id]['total_term_wt']) ? $cmArr[$mwInfo->cm_id]['total_term_wt'] : 0;
                $cmArr[$mwInfo->cm_id]['total_term_wt'] += $mwInfo->wt;

                $cmArr[$mwInfo->cm_id]['total_term_percent'] = ($cmArr[$mwInfo->cm_id]['total_term_wt'] * 100) / (!empty($eventMksWtArr['total_wt']) ? $eventMksWtArr['total_wt'] : 1);
            }
        }
        
        // get grade after term total
        $cmArr = Common::getGradeName($cmArr,$gradeInfo,'total_term_percent','grade_after_term_total');

        // get postion after term total
        $cmArr = Common::getPosition($cmArr, 'total_term_percent', 'total_term_position');

        // get previous data

        $ciObsnDataArr = CiObsnMarking::leftJoin('grading_system', 'grading_system.id', 'ci_obsn_marking.grade_id')
                ->select('ci_obsn_marking.cm_id', 'ci_obsn_marking.ci_obsn', 'ci_obsn_marking.wt'
                        , 'ci_obsn_marking.percentage', 'grading_system.grade_name as after_ci_grade'
                        , 'grading_system.id as grade_id')
                ->where('ci_obsn_marking.course_id', $request->course_id)
                ->get();
        if (!$ciObsnDataArr->isEmpty()) {
            foreach ($ciObsnDataArr as $ciObsnData) {
                $cmArr[$ciObsnData->cm_id]['ci_obsn'] = $ciObsnData->ci_obsn ?? '';
                $cmArr[$ciObsnData->cm_id]['total_wt'] = $ciObsnData->wt ?? '';
                $cmArr[$ciObsnData->cm_id]['percent'] = $ciObsnData->percentage ?? '';
                $cmArr[$ciObsnData->cm_id]['grade'] = $ciObsnData->after_ci_grade ?? '';
                $cmArr[$ciObsnData->cm_id]['grade_id'] = $ciObsnData->grade_id ?? 0;
            }
        }

        // get position after ci obsn
        $cmArr = Common::getPosition($cmArr, 'percent', 'position_after_ci_obsn');

        // lock info
        $ciObsnLockInfo = CiObsnMarkingLock::select('status')->where('course_id', $request->course_id)->first();

        
//        echo '<pre>';
////        print_r($sdf);
//        print_r($cmArr);
//        exit;

        $html = view('ciObsnMarking.showCmMarkingList', compact('assignedObsnInfo', 'gradeInfo', 'openTerms'
                        , 'eventMksWtArr', 'cmArr', 'rowSpanArr', 'achieveMksWtArr', 'ciObsnLockInfo'))->render();
        return response()->json(['html' => $html]);
    }

    public function saveObsnMarking(Request $request) {
// Validation
        $rules = $message = $errors = [];
        $rules = [
            'course_id' => 'required|not_in:0',
        ];
        $cmName = $request->cm_name;
        if (!empty($request->wt)) {
            foreach ($request->wt as $key => $wtInfo) {
                if ($request->data_id == '2') {
                    $rules['wt.' . $key . '.ci_obsn'] = 'required';
                    $message['wt.' . $key . '.ci_obsn' . '.required'] = __('label.WT_FIELD_IS_REQUIRED_FOR', ['cm_name' => $cmName[$key]]);
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
//echo '<pre>';
//print_r($request->wt);
//exit;
        $data = [];
        $i = 0;
        if (!empty($request->wt)) {
            foreach ($request->wt as $cmId => $wtInfo) {
                $data[$i]['course_id'] = $request->course_id;
                $data[$i]['cm_id'] = $cmId ?? 0;
                $data[$i]['ci_obsn'] = $wtInfo['ci_obsn'] ?? null;
                $data[$i]['wt'] = $wtInfo['total_wt'] ?? null;
                $data[$i]['percentage'] = $wtInfo['percentage'] ?? null;
                $data[$i]['grade_id'] = $wtInfo['grade_id'] ?? 0;
                $data[$i]['updated_at'] = date('Y-m-d H:i:s');
                $data[$i]['updated_by'] = Auth::user()->id;
                $i++;
            }
        }
// Save data

        DB::beginTransaction();

        try {
            CiObsnMarking::where('course_id', $request->course_id)->delete();
            if (CiObsnMarking::insert($data)) {
                $successMsg = __('label.OBSN_WT_HAS_BEEN_ASSIGNED_SUCCESSFULLY');
                $errorMsg = __('label.OBSN_WT_CUOLD_NOT_BE_ASSIGNED');

                if ($request->data_id == '2') {
                    $target = new CiObsnMarkingLock;

                    $target->course_id = $request->course_id;
                    $target->status = 1;
                    $target->locked_at = date('Y-m-d H:i:s');
                    $target->locked_by = Auth::user()->id;
                    $target->save();

                    $successMsg = __('label.OBSN_WT_HAS_BEEN_ASSIGNED_AND_LOCKED_SUCCESSFULLY');
                    $errorMsg = __('label.OBSN_WT_COULD_NOT_BE_ASSIGNED_AND_LOCKED');
                }
            }
            DB::commit();
            return Response::json(['success' => true, 'message' => $successMsg], 200);
        } catch (Exception $ex) {
            DB::rollback();
            return Response::json(['success' => false, 'message' => $errorMsg], 401);
        }
    }

    public function getRequestForUnlockModal(Request $request) {
        $view = view('ciObsnMarking.showRequestForUnlockModal')->render();
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
        $ciObsnLockInfo = CiObsnMarkingLock::select('id')->where('course_id', $request->course_id)->first();

        if (!empty($ciObsnLockInfo)) {
            $target = CiObsnMarkingLock::where('id', $ciObsnLockInfo->id)
                    ->update(['status' => '2', 'unlock_message' => $request->unlock_message]);
            if ($target) {
                return Response::json(['success' => true], 200);
            } else {
                return Response::json(array('success' => false, 'message' => __('label.REQUEST_FOR_UNLOCK_COULD_NOT_BE_SENT_TO_COMDT')), 401);
            }
        }
    }
    
    public function requestCourseSatatusSummary(Request $request){
        $loadView = 'ciObsnMarking.showCourseStatusSummary';
        return Common::requestCourseSatatusSummary($request,$loadView);
    }
    public function getDsMarkingSummary(Request $request){
        $loadView = 'ciObsnMarking.showDsMarkingSummaryModal';
        return Common::getDsMarkingSummary($request,$loadView);
    }

}
