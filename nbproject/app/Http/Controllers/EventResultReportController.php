<?php

namespace App\Http\Controllers;

use Validator;
use App\TrainingYear;
use App\Course;
use App\TermToCourse;
use App\TermToEvent;
use App\TermToSubEvent;
use App\TermToSubSubEvent;
use App\TermToSubSubSubEvent;
use App\CmBasicProfile;
use App\CiComdtModerationMarkingLimit;
use App\EventAssessmentMarking;
use App\GradingSystem;
use App\CiModerationMarking;
use App\ComdtModerationMarking;
use App\DsMarkingGroup;
use App\CmToSyn;
use App\User;
use Response;
use Session;
use Redirect;
use Helper;
use PDF;
use Auth;
use File;
use DB;
use Common;
use Carbon\Carbon;
use App\Exports\ExcelExport;
use App\Imports\ExcelImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class EventResultReportController extends Controller {

    private $controller = 'EventResultReport';

    public function index(Request $request) {
        //get only active training year
        $activeTrainingYearList = ['0' => __('label.SELECT_TRAINING_YEAR_OPT')] + TrainingYear::where('status', '<>', '0')
                        ->orderBy('start_date', 'desc')
                        ->pluck('name', 'id')->toArray();

        $courseList = ['0' => __('label.SELECT_COURSE_OPT')] + Course::where('training_year_id', $request->training_year_id)
                        ->where('status', '<>', '0')
                        ->orderBy('training_year_id', 'desc')
                        ->orderBy('id', 'desc')
                        ->pluck('name', 'id')
                        ->toArray();
        $termList = ['0' => __('label.SELECT_TERM_OPT')] + TermToCourse::join('term', 'term.id', '=', 'term_to_course.term_id')
                        ->where('term_to_course.course_id', $request->course_id)
                        ->where('term_to_course.status', '<>', '0')
                        ->orderBy('term.order', 'asc')
                        ->pluck('term.name', 'term.id')
                        ->toArray();
        $eventList = ['0' => __('label.SELECT_EVENT_OPT')] + TermToEvent::join('event', 'event.id', '=', 'term_to_event.event_id')
                        ->where('term_to_event.course_id', $request->course_id)
                        ->where('term_to_event.term_id', $request->term_id)
                        ->orderBy('event.order', 'asc')
                        ->pluck('event.event_code', 'event.id')
                        ->toArray();

        $subEventList = TermToSubEvent::join('sub_event', 'sub_event.id', '=', 'term_to_sub_event.sub_event_id')
                        ->join('event_to_sub_event', 'event_to_sub_event.sub_event_id', '=', 'term_to_sub_event.sub_event_id')
                        ->where('term_to_sub_event.course_id', $request->course_id)
                        ->where('term_to_sub_event.term_id', $request->term_id)
                        ->where('term_to_sub_event.event_id', $request->event_id)
                        ->where('event_to_sub_event.has_sub_sub_event', '1')
                        ->pluck('sub_event.event_code', 'sub_event.id')->toArray();
        $hasSubEvent = !empty($subEventList) ? 1 : 0;
        $subEventList = ['0' => __('label.SELECT_SUB_EVENT_OPT')] + $subEventList;

        $subSubEventList = TermToSubSubEvent::join('sub_sub_event', 'sub_sub_event.id', '=', 'term_to_sub_sub_event.sub_sub_event_id')
                        ->join('event_to_sub_sub_event', 'event_to_sub_sub_event.sub_sub_event_id', '=', 'term_to_sub_sub_event.sub_sub_event_id')
                        ->where('event_to_sub_sub_event.has_sub_sub_sub_event', '1')
                        ->where('term_to_sub_sub_event.course_id', $request->course_id)
                        ->where('term_to_sub_sub_event.term_id', $request->term_id)
                        ->where('term_to_sub_sub_event.event_id', $request->event_id)
                        ->where('term_to_sub_sub_event.sub_event_id', $request->sub_event_id)
                        ->pluck('sub_sub_event.event_code', 'sub_sub_event.id')->toArray();
        $hasSubSubEvent = !empty($subSubEventList) ? 1 : 0;
        $subSubEventList = ['0' => __('label.SELECT_SUB_SUB_EVENT_OPT')] + $subSubEventList;

        $subSubSubEventList = TermToSubSubSubEvent::join('sub_sub_sub_event', 'sub_sub_sub_event.id', '=', 'term_to_sub_sub_sub_event.sub_sub_sub_event_id')
                        ->join('event_to_sub_sub_sub_event', 'event_to_sub_sub_sub_event.sub_sub_sub_event_id', '=', 'term_to_sub_sub_sub_event.sub_sub_sub_event_id')
                        ->where('term_to_sub_sub_sub_event.course_id', $request->course_id)
                        ->where('term_to_sub_sub_sub_event.term_id', $request->term_id)
                        ->where('term_to_sub_sub_sub_event.event_id', $request->event_id)
                        ->where('term_to_sub_sub_sub_event.sub_event_id', $request->sub_event_id)
                        ->where('term_to_sub_sub_sub_event.sub_sub_event_id', $request->sub_sub_event_id)
                        ->pluck('sub_sub_sub_event.event_code', 'sub_sub_sub_event.id')->toArray();
        $hasSubSubSubEvent = !empty($subSubSubEventList) ? 1 : 0;
        $subSubSubEventList = ['0' => __('label.SELECT_SUB_SUB_SUB_EVENT_OPT')] + $subSubSubEventList;

        $sortByList = ['personal_no' => __('label.PERSONAL_NO'), 'position' => __('label.POSITION')];


        $cmArr = $assingedMksWtInfo = $dsMksWtArr = $prevMksWtArr = $comdtMksWtArr = $dsDataList = $avgDsMksWtArr = $ciMksWtArr = [];
        $numOfDs = $comdtMksInfo = $gradeInfo = 0;
        if ($request->generate == 'true') {
            $activeTrainingYearList = ['0' => __('label.SELECT_TRAINING_YEAR_OPT')] + TrainingYear::where('status', '<>', '0')
                            ->orderBy('start_date', 'desc')
                            ->pluck('name', 'id')->toArray();

            $courseList = ['0' => __('label.SELECT_COURSE_OPT')] + Course::where('training_year_id', $request->training_year_id)
                            ->where('status', '<>', '0')
                            ->orderBy('training_year_id', 'desc')
                            ->orderBy('id', 'desc')
                            ->pluck('name', 'id')
                            ->toArray();
            $termList = ['0' => __('label.SELECT_TERM_OPT')] + TermToCourse::join('term', 'term.id', '=', 'term_to_course.term_id')
                            ->where('term_to_course.course_id', $request->course_id)
                            ->where('term_to_course.status', '<>', '0')
                            ->orderBy('term.order', 'asc')
                            ->pluck('term.name', 'term.id')
                            ->toArray();
            $eventList = ['0' => __('label.SELECT_EVENT_OPT')] + TermToEvent::join('event', 'event.id', '=', 'term_to_event.event_id')
                            ->where('term_to_event.course_id', $request->course_id)
                            ->where('term_to_event.term_id', $request->term_id)
                            ->orderBy('event.order', 'asc')
                            ->pluck('event.event_code', 'event.id')
                            ->toArray();
            $subEventList = TermToSubEvent::join('sub_event', 'sub_event.id', '=', 'term_to_sub_event.sub_event_id')
                            ->join('event_to_sub_event', 'event_to_sub_event.sub_event_id', '=', 'term_to_sub_event.sub_event_id')
                            ->where('term_to_sub_event.course_id', $request->course_id)
                            ->where('term_to_sub_event.term_id', $request->term_id)
                            ->where('term_to_sub_event.event_id', $request->event_id)
                            ->where('event_to_sub_event.has_sub_sub_event', '1')
                            ->pluck('sub_event.event_code', 'sub_event.id')->toArray();
            $hasSubEvent = !empty($subEventList) ? 1 : 0;
            $subEventList = ['0' => __('label.SELECT_SUB_EVENT_OPT')] + $subEventList;

            $subSubEventList = TermToSubSubEvent::join('sub_sub_event', 'sub_sub_event.id', '=', 'term_to_sub_sub_event.sub_sub_event_id')
                            ->join('event_to_sub_sub_event', 'event_to_sub_sub_event.sub_sub_event_id', '=', 'term_to_sub_sub_event.sub_sub_event_id')
                            ->where('event_to_sub_sub_event.has_sub_sub_sub_event', '1')
                            ->where('term_to_sub_sub_event.course_id', $request->course_id)
                            ->where('term_to_sub_sub_event.term_id', $request->term_id)
                            ->where('term_to_sub_sub_event.event_id', $request->event_id)
                            ->where('term_to_sub_sub_event.sub_event_id', $request->sub_event_id)
                            ->pluck('sub_sub_event.event_code', 'sub_sub_event.id')->toArray();
            $hasSubSubEvent = !empty($subSubEventList) ? 1 : 0;
            $subSubEventList = ['0' => __('label.SELECT_SUB_SUB_EVENT_OPT')] + $subSubEventList;

            $subSubSubEventList = TermToSubSubSubEvent::join('sub_sub_sub_event', 'sub_sub_sub_event.id', '=', 'term_to_sub_sub_sub_event.sub_sub_sub_event_id')
                            ->join('event_to_sub_sub_sub_event', 'event_to_sub_sub_sub_event.sub_sub_sub_event_id', '=', 'term_to_sub_sub_sub_event.sub_sub_sub_event_id')
                            ->where('term_to_sub_sub_sub_event.course_id', $request->course_id)
                            ->where('term_to_sub_sub_sub_event.term_id', $request->term_id)
                            ->where('term_to_sub_sub_sub_event.event_id', $request->event_id)
                            ->where('term_to_sub_sub_sub_event.sub_event_id', $request->sub_event_id)
                            ->where('term_to_sub_sub_sub_event.sub_sub_event_id', $request->sub_sub_event_id)
                            ->pluck('sub_sub_sub_event.event_code', 'sub_sub_sub_event.id')->toArray();
            $hasSubSubSubEvent = !empty($subSubSubEventList) ? 1 : 0;
            $subSubSubEventList = ['0' => __('label.SELECT_SUB_SUB_SUB_EVENT_OPT')] + $subSubSubEventList;

            $tyName = $request->training_year_id != '0' && !empty($activeTrainingYearList[$request->training_year_id]) ? '_' . $activeTrainingYearList[$request->training_year_id] : '';
            $courseName = $request->course_id != '0' && !empty($courseList[$request->course_id]) ? '_' . $courseList[$request->course_id] : '';
            $termName = $request->term_id != '0' && !empty($termList[$request->term_id]) ? '_' . $termList[$request->term_id] : '';
            $synName = $request->syn_id != '0' && !empty($synList[$request->syn_id]) ? '_' . $synList[$request->syn_id] : '';
            $eventName = $request->maEvent_id != '0' && !empty($maEventList[$request->maEvent_id]) ? '_' . $maEventList[$request->maEvent_id] : '';
            $subSynName = $request->sub_syn_id != '0' && !empty($subSynList[$request->sub_syn_id]) ? '_' . $subSynList[$request->sub_syn_id] : '';
            $fileName = 'Mutual_Assessment_Summary_Report' . $tyName . $courseName . $termName . $synName . $eventName . $subSynName;


//            Start::Event Result Data
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
//            syn List
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
            
//            echo '<pre>';            print_r($cmArr); exit;
            // CI Marking Information
            $comdtMksInfo = CiComdtModerationMarkingLimit::where('course_id', $request->course_id)
                            ->where('term_id', $request->term_id)
                            ->select('comdt_mod')->first();

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



// get ci moderation data
            $ciMksWtDataArr = CiModerationMarking::join('grading_system', 'grading_system.id', 'ci_moderation_marking.grade_id')
                    ->where('ci_moderation_marking.course_id', $request->course_id)
                    ->where('ci_moderation_marking.term_id', $request->term_id)
                    ->where('ci_moderation_marking.event_id', $request->event_id);

            if (!empty($request->sub_event_id)) {
                $ciMksWtDataArr = $ciMksWtDataArr->where('ci_moderation_marking.sub_event_id', $request->sub_event_id);
            }
            if (!empty($request->sub_sub_event_id)) {
                $ciMksWtDataArr = $ciMksWtDataArr->where('ci_moderation_marking.sub_sub_event_id', $request->sub_sub_event_id);
            }
            if (!empty($request->sub_sub_sub_event_id)) {
                $ciMksWtDataArr = $ciMksWtDataArr->where('ci_moderation_marking.sub_sub_sub_event_id', $request->sub_sub_sub_event_id);
            }
            $ciMksWtDataArr = $ciMksWtDataArr->select('ci_moderation_marking.cm_id', 'ci_moderation_marking.ci_moderation', 'ci_moderation_marking.mks'
                            , 'ci_moderation_marking.wt', 'ci_moderation_marking.percentage', 'grading_system.grade_name'
                            , 'grading_system.id as grade_id', 'ci_moderation_marking.updated_by')
                    ->get();
            $ciMksWtArr = [];
            if (!$ciMksWtDataArr->isEmpty()) {
                foreach ($ciMksWtDataArr as $ciMksWtData) {
                    $ciMksWtArr[$ciMksWtData->cm_id] = $ciMksWtData->toArray();
                }
            }

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
//        
            // get previous data
            $prevMksWtDataArr = ComdtModerationMarking::join('grading_system', 'grading_system.id', 'comdt_moderation_marking.grade_id')
                    ->where('comdt_moderation_marking.course_id', $request->course_id)
                    ->where('comdt_moderation_marking.term_id', $request->term_id)
                    ->where('comdt_moderation_marking.event_id', $request->event_id);

            if (!empty($request->sub_event_id)) {
                $prevMksWtDataArr = $prevMksWtDataArr->where('comdt_moderation_marking.sub_event_id', $request->sub_event_id);
            }
            if (!empty($request->sub_sub_event_id)) {
                $prevMksWtDataArr = $prevMksWtDataArr->where('comdt_moderation_marking.sub_sub_event_id', $request->sub_sub_event_id);
            }
            if (!empty($request->sub_sub_sub_event_id)) {
                $prevMksWtDataArr = $prevMksWtDataArr->where('comdt_moderation_marking.sub_sub_sub_event_id', $request->sub_sub_sub_event_id);
            }

            $prevMksWtDataArr = $prevMksWtDataArr->where('comdt_moderation_marking.updated_by', Auth::user()->id)
                    ->select('comdt_moderation_marking.cm_id', 'comdt_moderation_marking.comdt_moderation', 'comdt_moderation_marking.mks'
                            , 'comdt_moderation_marking.wt', 'comdt_moderation_marking.percentage', 'grading_system.grade_name'
                            , 'grading_system.id as grade_id')
                    ->get();

            if (!$prevMksWtDataArr->isEmpty()) {
                foreach ($prevMksWtDataArr as $prevMksWtData) {
                    $prevMksWtArr[$prevMksWtData->cm_id] = $prevMksWtData->toArray();
                }
            }

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

                    $cmArr[$cmId]['final_mks'] = !empty($prevMksWtArr[$cmId]['mks']) ? $prevMksWtArr[$cmId]['mks'] : (!empty($ciMksWtArr[$cmId]['mks']) ? $ciMksWtArr[$cmId]['mks'] : (!empty($avgDsMksWtArr['mks'][$cmId]) ? $avgDsMksWtArr['mks'][$cmId] : 0));
                    $cmArr[$cmId]['final_wt'] = !empty($prevMksWtArr[$cmId]['wt']) ? $prevMksWtArr[$cmId]['wt'] : (!empty($ciMksWtArr[$cmId]['wt']) ? $ciMksWtArr[$cmId]['wt'] : (!empty($avgDsMksWtArr['wt'][$cmId]) ? $avgDsMksWtArr['wt'][$cmId] : 0));
                    $cmArr[$cmId]['final_percentage'] = !empty($prevMksWtArr[$cmId]['percentage']) ? $prevMksWtArr[$cmId]['percentage'] : (!empty($ciMksWtArr[$cmId]['percentage']) ? $ciMksWtArr[$cmId]['percentage'] : (!empty($avgDsMksWtArr['percentage'][$cmId]) ? $avgDsMksWtArr['percentage'][$cmId] : 0));
                    $cmArr[$cmId]['final_grade_name'] = !empty($prevMksWtArr[$cmId]['grade_name']) ? $prevMksWtArr[$cmId]['grade_name'] : (!empty($ciMksWtArr[$cmId]['grade_name']) ? $ciMksWtArr[$cmId]['grade_name'] : (!empty($avgDsMksWtArr['grade_name'][$cmId]) ? $avgDsMksWtArr['grade_name'][$cmId] : 0));
                }
            }
            $cmArr = Common::getPosition($cmArr, 'final_percentage', 'position');
            if (!empty($request->sort) && $request->sort == 'position') {
                if (!empty($cmArr)) {
                    usort($cmArr, function ($item1, $item2) {
                        if (!isset($item1['final_percentage'])) {
                            $item1['final_percentage'] = '';
                        }

                        if (!isset($item2['final_percentage'])) {
                            $item2['final_percentage'] = '';
                        }
                        return $item2['final_percentage'] <=> $item1['final_percentage'];
                    });
                }
            }

//        End:: Average Marking
//            echo '<pre>';            print_r($prevMksWtArr); exit;
        }

        if ($request->view == 'print') {
            return view('report.eventResult.print.index')->with(compact('activeTrainingYearList', 'courseList', 'termList'
                                    , 'eventList', 'subEventList', 'subSubEventList', 'subSubSubEventList'
                                    , 'hasSubEvent', 'hasSubSubEvent', 'hasSubSubSubEvent', 'dsDataList'
                                    , 'numOfDs', 'cmArr', 'comdtMksInfo', 'dsMksWtArr', 'gradeInfo', 'sortByList'
                                    , 'avgDsMksWtArr', 'ciMksWtArr', 'assingedMksWtInfo', 'prevMksWtArr'));
        } elseif ($request->view == 'pdf') {
            $pdf = PDF::loadView('report.eventResult.print.index', compact('activeTrainingYearList', 'courseList', 'termList'
                                    , 'eventList', 'subEventList', 'subSubEventList', 'subSubSubEventList'
                                    , 'hasSubEvent', 'hasSubSubEvent', 'hasSubSubSubEvent', 'dsDataList'
                                    , 'numOfDs', 'cmArr', 'comdtMksInfo', 'dsMksWtArr', 'gradeInfo', 'sortByList'
                                    , 'avgDsMksWtArr', 'ciMksWtArr', 'assingedMksWtInfo', 'prevMksWtArr'))
                    ->setPaper('a4', 'landscape')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download($fileName . '.pdf');
        } elseif ($request->view == 'excel') {
            return Excel::download(new ExcelExport('report.eventResult.print.index', compact('activeTrainingYearList', 'courseList', 'termList'
                                    , 'eventList', 'subEventList', 'subSubEventList', 'subSubSubEventList'
                                    , 'hasSubEvent', 'hasSubSubEvent', 'hasSubSubSubEvent', 'dsDataList'
                                    , 'numOfDs', 'cmArr', 'comdtMksInfo', 'dsMksWtArr', 'gradeInfo', 'sortByList'
                                    , 'avgDsMksWtArr', 'ciMksWtArr', 'assingedMksWtInfo', 'prevMksWtArr')), $fileName . '.xlsx');
        }

        return view('report.eventResult.index', compact('activeTrainingYearList', 'courseList', 'termList'
                        , 'eventList', 'subEventList', 'subSubEventList', 'subSubSubEventList'
                        , 'hasSubEvent', 'hasSubSubEvent', 'hasSubSubSubEvent', 'dsDataList'
                        , 'numOfDs', 'cmArr', 'comdtMksInfo', 'dsMksWtArr', 'gradeInfo', 'sortByList'
                        , 'avgDsMksWtArr', 'ciMksWtArr', 'assingedMksWtInfo', 'prevMksWtArr'));
    }

    public function getCourse(Request $request) {
        $courseList = ['0' => __('label.SELECT_COURSE_OPT')] + Course::where('training_year_id', $request->training_year_id)
                        ->where('status', '<>', '0')
                        ->orderBy('training_year_id', 'desc')
                        ->orderBy('id', 'desc')
                        ->pluck('name', 'id')
                        ->toArray();
        $html = view('report.eventResult.getCourse', compact('courseList'))->render();
        return Response::json(['html' => $html]);
    }

    public function getTerm(Request $request) {

        $termList = ['0' => __('label.SELECT_TERM_OPT')] + TermToCourse::join('term', 'term.id', '=', 'term_to_course.term_id')
                        ->where('term_to_course.course_id', $request->course_id)
                        ->where('term_to_course.status', '<>', '0')
                        ->orderBy('term.order', 'asc')
                        ->pluck('term.name', 'term.id')
                        ->toArray();

        $html = view('report.eventResult.getTerm', compact('termList'))->render();
        return Response::json(['html' => $html]);
    }

    public function getEvent(Request $request) {

        $eventList = ['0' => __('label.SELECT_EVENT_OPT')] + TermToEvent::join('event', 'event.id', '=', 'term_to_event.event_id')
                        ->where('term_to_event.course_id', $request->course_id)
                        ->where('term_to_event.term_id', $request->term_id)
                        ->orderBy('event.order', 'asc')
                        ->pluck('event.event_code', 'event.id')
                        ->toArray();

        $html = view('report.eventResult.getEvent', compact('eventList'))->render();
        return Response::json(['html' => $html]);
    }

    public function getSubEventReport(Request $request) {
//        echo '<pre>';        print_r($request->all()); exit;
        $html = '';
        $subEventList = ['0' => __('label.SELECT_SUB_EVENT_OPT')] + TermToSubEvent::join('sub_event', 'sub_event.id', '=', 'term_to_sub_event.sub_event_id')
                        ->join('event_to_sub_event', 'event_to_sub_event.sub_event_id', '=', 'term_to_sub_event.sub_event_id')
                        ->where('term_to_sub_event.course_id', $request->course_id)
                        ->where('term_to_sub_event.term_id', $request->term_id)
                        ->where('term_to_sub_event.event_id', $request->event_id)
                        ->where('event_to_sub_event.has_sub_sub_event', '1')
                        ->pluck('sub_event.event_code', 'sub_event.id')->toArray();

        if (sizeof($subEventList) > 1) {
            $html = view('report.eventResult.getSubEvent', compact('subEventList'))->render();
        }

        return response()->json(['html' => $html]);
    }

    public function getSubSubEventReport(Request $request) {
        $html = '';
        $subSubEventList = ['0' => __('label.SELECT_SUB_SUB_EVENT_OPT')] + TermToSubSubEvent::join('sub_sub_event', 'sub_sub_event.id', '=', 'term_to_sub_sub_event.sub_sub_event_id')
                        ->join('event_to_sub_sub_event', 'event_to_sub_sub_event.sub_sub_event_id', '=', 'term_to_sub_sub_event.sub_sub_event_id')
                        ->where('event_to_sub_sub_event.has_sub_sub_sub_event', '1')
                        ->where('term_to_sub_sub_event.course_id', $request->course_id)
                        ->where('term_to_sub_sub_event.term_id', $request->term_id)
                        ->where('term_to_sub_sub_event.event_id', $request->event_id)
                        ->where('term_to_sub_sub_event.sub_event_id', $request->sub_event_id)
                        ->pluck('sub_sub_event.event_code', 'sub_sub_event.id')->toArray();

        if (sizeof($subSubEventList) > 1) {
            $html = view('report.eventResult.getSubSubEvent', compact('subSubEventList'))->render();
        }

        return response()->json(['html' => $html]);
    }

    public function getSubSubSubEventReport(Request $request) {
        $html = '';
        $subSubSubEventList = ['0' => __('label.SELECT_SUB_SUB_SUB_EVENT_OPT')] + TermToSubSubSubEvent::join('sub_sub_sub_event', 'sub_sub_sub_event.id', '=', 'term_to_sub_sub_sub_event.sub_sub_sub_event_id')
                        ->join('event_to_sub_sub_sub_event', 'event_to_sub_sub_sub_event.sub_sub_sub_event_id', '=', 'term_to_sub_sub_sub_event.sub_sub_sub_event_id')
                        ->where('term_to_sub_sub_sub_event.course_id', $request->course_id)
                        ->where('term_to_sub_sub_sub_event.term_id', $request->term_id)
                        ->where('term_to_sub_sub_sub_event.event_id', $request->event_id)
                        ->where('term_to_sub_sub_sub_event.sub_event_id', $request->sub_event_id)
                        ->where('term_to_sub_sub_sub_event.sub_sub_event_id', $request->sub_sub_event_id)
                        ->pluck('sub_sub_sub_event.event_code', 'sub_sub_sub_event.id')->toArray();


        if (sizeof($subSubSubEventList) > 1) {
            $html = view('report.eventResult.getSubSubSubEvent', compact('subSubSubEventList'))->render();
        }
        return response()->json(['html' => $html]);
    }

    public function filter(Request $request) {

//        echo '<pre>';        print_r($request->all()); exit;

        $messages = [];
        $rules = [
            'training_year_id' => 'required|not_in:0',
            'course_id' => 'required|not_in:0',
            'term_id' => 'required|not_in:0',
            'event_id' => 'required|not_in:0',
        ];
        $messages = [
            'training_year_id.not_in' => __('label.THE_TRAINING_YEAR_FIELD_IS_REQUIRED'),
            'course_id.not_in' => __('label.THE_COURSE_FIELD_IS_REQUIRED'),
            'term_id.not_in' => __('label.THE_TERM_FIELD_IS_REQUIRED'),
            'event_id.not_in' => __('label.THE_EVENT_FIELD_IS_REQUIRED'),
        ];
        if (!empty($request->has_sub_event)) {
            $rules['sub_event_id'] = 'required|not_in:0';
            $messages['sub_event_id.not_in'] = __('label.THE_SUB_EVENT_FIELD_IS_REQUIRED');
        }
        if (!empty($request->has_sub_sub_event)) {
            $rules['sub_sub_event_id'] = 'required|not_in:0';
            $messages['sub_sub_event_id.not_in'] = __('label.THE_SUB_SUB_EVENT_FIELD_IS_REQUIRED');
        }
        if (!empty($request->has_sub_sub_sub_event)) {
            $rules['sub_sub_sub_event_id'] = 'required|not_in:0';
            $messages['sub_sub_sub_event_id.not_in'] = __('label.THE_SUB_SUB_SUB_EVENT_FIELD_IS_REQUIRED');
        }

        $url = 'training_year_id=' . $request->training_year_id . '&course_id=' . $request->course_id . '&term_id=' . $request->term_id
                . '&event_id=' . $request->event_id . '&sub_event_id=' . $request->sub_event_id . '&sub_sub_event_id=' . $request->sub_sub_event_id
                . '&sub_sub_sub_event_id=' . $request->sub_sub_sub_event_id . '&sort=' . $request->sort;

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('eventResultReport?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }
        return redirect('eventResultReport?generate=true&' . $url);
    }

}
