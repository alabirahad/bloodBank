<?php

namespace App\Http\Controllers;

use Validator;
use App\TrainingYear;
use App\Term;
use App\MutualAssessmentEvent;
use App\Course;
use App\TermToCourse;
use App\TermToMAEvent;
use App\SynToCourse;
use App\Syndicate;
use App\SubSyndicate;
use App\SynToSubSyn;
use App\CmToSyn;
use App\CmToSubSyn;
use App\CmBasicProfile;
use App\MutualAssessmentMarking;
use App\MutualAssessmentMarkingLock;
use App\MaMksExport;
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

class MutualAssessmentSummaryReportController extends Controller {

    private $controller = 'MutualAssessmentSummaryReport';

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
        $maEventList = ['0' => __('label.SELECT_EVENT_OPT')] + TermToMAEvent::join('mutual_assessment_event', 'mutual_assessment_event.id', '=', 'term_to_ma_event.event_id')
                        ->where('term_to_ma_event.course_id', $request->course_id)
                        ->where('term_to_ma_event.term_id', $request->term_id)
                        ->orderBy('mutual_assessment_event.order', 'asc')
                        ->pluck('mutual_assessment_event.name', 'mutual_assessment_event.id')
                        ->toArray();
        $synList = ['0' => __('label.SELECT_SYN_OPT')] + SynToCourse::join('syndicate', 'syndicate.id', 'syn_to_course.syn_id')
                        ->where('syn_to_course.course_id', $request->course_id)
                        ->orderBy('syndicate.order', 'asc')
                        ->pluck('syndicate.name', 'syndicate.id')
                        ->toArray();
        $subSynList = SynToSubSyn::join('sub_syndicate', 'sub_syndicate.id', 'syn_to_sub_syn.sub_syn_id')
                ->where('syn_to_sub_syn.course_id', $request->course_id)
                ->where('syn_to_sub_syn.syn_id', $request->syn_id)
                ->where('sub_syndicate.status', '1')
                ->orderBy('sub_syndicate.order', 'asc')
                ->pluck('sub_syndicate.name', 'sub_syndicate.id')
                ->toArray();
        $hasSubSyn = !empty($subSynList) ? 1 : 0;
        $subSynList = ['0' => __('label.SELECT_SUB_SYN_OPT')] + $subSynList;

        $sortByList = ['personal_no' => __('label.PERSONAL_NO'), 'position' => __('label.POSITION')];

        $cmArr = $markingCmArr = $markingPositionArr = $totalPositionArr = $totalMarkingCm = [];

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
            $maEventList = ['0' => __('label.SELECT_EVENT_OPT')] + TermToMAEvent::join('mutual_assessment_event', 'mutual_assessment_event.id', '=', 'term_to_ma_event.event_id')
                            ->where('term_to_ma_event.course_id', $request->course_id)
                            ->where('term_to_ma_event.term_id', $request->term_id)
                            ->orderBy('mutual_assessment_event.order', 'asc')
                            ->pluck('mutual_assessment_event.name', 'mutual_assessment_event.id')
                            ->toArray();
            $synList = ['0' => __('label.SELECT_SYN_OPT')] + SynToCourse::join('syndicate', 'syndicate.id', 'syn_to_course.syn_id')
                            ->where('syn_to_course.course_id', $request->course_id)
                            ->orderBy('syndicate.order', 'asc')
                            ->pluck('syndicate.name', 'syndicate.id')
                            ->toArray();
            $subSynList = SynToSubSyn::join('sub_syndicate', 'sub_syndicate.id', 'syn_to_sub_syn.sub_syn_id')
                    ->where('syn_to_sub_syn.course_id', $request->course_id)
                    ->where('syn_to_sub_syn.syn_id', $request->syn_id)
                    ->where('sub_syndicate.status', '1')
                    ->orderBy('sub_syndicate.order', 'asc')
                    ->pluck('sub_syndicate.name', 'sub_syndicate.id')
                    ->toArray();
            $hasSubSyn = !empty($subSynList) ? 1 : 0;
            $subSynList = ['0' => __('label.SELECT_SUB_SYN_OPT')] + $subSynList;

            $cmDataArr = CmToSyn::join('cm_basic_profile', 'cm_basic_profile.id', 'cm_to_syn.cm_id')
                    ->leftJoin('rank', 'rank.id', 'cm_basic_profile.rank_id')
                    ->join('course', 'course.id', 'cm_basic_profile.course_id')
                    ->where('course.training_year_id', $request->training_year_id)
                    ->where('cm_to_syn.course_id', $request->course_id)
                    ->where('cm_to_syn.term_id', $request->term_id)
                    ->where('cm_to_syn.syn_id', $request->syn_id)
                    ->where('cm_basic_profile.status', '1');
            if (!empty($request->sub_syn_id)) {
                $cmDataArr = $cmDataArr->where('cm_to_syn.sub_syn_id', $request->sub_syn_id);
            }
            $cmDataArr = $cmDataArr->select('cm_basic_profile.id', 'cm_basic_profile.full_name'
                            , 'rank.code as rank_name', 'cm_basic_profile.personal_no')
                    ->orderBy('cm_basic_profile.personal_no', 'asc');
            $cmDataArr = $cmDataArr->get();

            if (!$cmDataArr->isEmpty()) {
                foreach ($cmDataArr as $cmData) {
                    $cmArr[$cmData->id] = $cmData->toArray();
                    $markingCmArr[$cmData->id] = $cmData->toArray();
                }
            }

            $markingDataArr = MutualAssessmentMarking::join('cm_basic_profile', 'cm_basic_profile.id', 'mutual_assessment_marking.marking_cm_id')
                    ->join('course', 'course.id', 'cm_basic_profile.course_id')
                    ->where('course.training_year_id', $request->training_year_id)
                    ->where('mutual_assessment_marking.course_id', $request->course_id)
                    ->where('mutual_assessment_marking.term_id', $request->term_id)
                    ->where('mutual_assessment_marking.event_id', $request->maEvent_id)
                    ->where('mutual_assessment_marking.syndicate_id', $request->syn_id)
                    ->where('cm_basic_profile.status', '1');

            if (!empty($request->sub_syn_id)) {
                $markingDataArr = $markingDataArr->where('mutual_assessment_marking.sub_syndicate_id', $request->sub_syn_id);
            }
            $markingDataArr = $markingDataArr->select('mutual_assessment_marking.marking_cm_id', 'mutual_assessment_marking.cm_id'
                                    , 'mutual_assessment_marking.position')
                            ->orderBy('cm_basic_profile.personal_no', 'asc')->get();

            if (!$markingDataArr->isEmpty()) {
                foreach ($markingDataArr as $markingData) {
                    $markingArr[$markingData->marking_cm_id][$markingData->cm_id] = $markingData->toArray();
                }
            }

            if (!empty($markingArr)) {
                foreach ($markingArr as $markingCmId => $marking) {
                    foreach ($marking as $cmId => $info) {
                        if ($markingCmId != $cmArr[$cmId]) {
                            $markingPositionArr[$markingCmId][$cmId]['pos'] = $info['position'];
                            $totalPositionArr[$cmId]['total'] = !empty($totalPositionArr[$cmId]['total']) ? $totalPositionArr[$cmId]['total'] : 0;
                            $totalPositionArr[$cmId]['total'] += !empty($markingPositionArr[$markingCmId][$cmId]['pos']) ? $markingPositionArr[$markingCmId][$cmId]['pos'] : 0;

                            $totalMarkingCm[$cmId] = !empty($totalMarkingCm[$cmId]) ? $totalMarkingCm[$cmId] : 0;
                            $totalMarkingCm[$cmId] += 1;
                            $cmArr[$cmId]['avg'] = Helper::numberFormat2Digit((!empty($totalPositionArr[$cmId]['total']) ? $totalPositionArr[$cmId]['total'] : 0) / (!empty($totalMarkingCm[$cmId]) ? $totalMarkingCm[$cmId] : 1));
                        }
                    }
                }
            }
            $cmArr = Common::getPosition($cmArr, 'avg', 'position', 1);

            if (!empty($request->sort) && $request->sort == 'position') {
                if (!empty($cmArr)) {
                    usort($cmArr, function ($item1, $item2) {
                        if (!isset($item1['avg'])) {
                            $item1['avg'] = '';
                        }

                        if (!isset($item2['avg'])) {
                            $item2['avg'] = '';
                        }
                        return $item1['avg'] <=> $item2['avg'];
                    });
                }
            }
//            echo '<pre>';
//            print_r($cmArr);
//            print_r($markingCmArr);
//            exit;

            $tyName = $request->training_year_id != '0' && !empty($activeTrainingYearList[$request->training_year_id]) ? '_' . $activeTrainingYearList[$request->training_year_id] : '';
            $courseName = $request->course_id != '0' && !empty($courseList[$request->course_id]) ? '_' . $courseList[$request->course_id] : '';
            $termName = $request->term_id != '0' && !empty($termList[$request->term_id]) ? '_' . $termList[$request->term_id] : '';
            $synName = $request->syn_id != '0' && !empty($synList[$request->syn_id]) ? '_' . $synList[$request->syn_id] : '';
            $eventName = $request->maEvent_id != '0' && !empty($maEventList[$request->maEvent_id]) ? '_' . $maEventList[$request->maEvent_id] : '';
            $subSynName = $request->sub_syn_id != '0' && !empty($subSynList[$request->sub_syn_id]) ? '_' . $subSynList[$request->sub_syn_id] : '';
            $fileName = 'Mutual_Assessment_Summary_Report' . $tyName . $courseName . $termName . $synName . $eventName . $subSynName;
        }

        if ($request->view == 'print') {
            return view('report.mutualAssessmentSummary.print.index')->with(compact('activeTrainingYearList', 'courseList', 'termList'
                                    , 'maEventList', 'synList', 'subSynList', 'hasSubSyn', 'cmArr', 'markingPositionArr'
                                    , 'totalPositionArr', 'markingCmArr', 'sortByList'));
        } elseif ($request->view == 'pdf') {
            $pdf = PDF::loadView('report.mutualAssessmentSummary.print.index', compact('activeTrainingYearList', 'courseList', 'termList'
                                    , 'maEventList', 'synList', 'subSynList', 'hasSubSyn', 'cmArr', 'markingPositionArr'
                                    , 'totalPositionArr', 'markingCmArr', 'sortByList'))
                    ->setPaper('a4', 'landscape')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download($fileName . '.pdf');
        } elseif ($request->view == 'excel') {
            return Excel::download(new ExcelExport('report.mutualAssessmentSummary.print.index', compact('activeTrainingYearList', 'courseList', 'termList'
                                    , 'maEventList', 'synList', 'subSynList', 'hasSubSyn', 'cmArr', 'markingPositionArr'
                                    , 'totalPositionArr', 'markingCmArr', 'sortByList')), $fileName . '.xlsx');
        }

        return view('report.mutualAssessmentSummary.index', compact('activeTrainingYearList', 'courseList', 'termList'
                        , 'maEventList', 'synList', 'subSynList', 'hasSubSyn', 'cmArr', 'markingPositionArr'
                        , 'totalPositionArr', 'markingCmArr', 'sortByList'));
    }

    public function getCourse(Request $request) {
        $courseList = ['0' => __('label.SELECT_COURSE_OPT')] + Course::where('training_year_id', $request->training_year_id)
                        ->where('status', '<>', '0')
                        ->orderBy('training_year_id', 'desc')
                        ->orderBy('id', 'desc')
                        ->pluck('name', 'id')
                        ->toArray();
        $html = view('report.mutualAssessmentSummary.getCourse', compact('courseList'))->render();
        return Response::json(['html' => $html]);
    }

    public function getTerm(Request $request) {

        $termList = ['0' => __('label.SELECT_TERM_OPT')] + TermToCourse::join('term', 'term.id', '=', 'term_to_course.term_id')
                        ->where('term_to_course.course_id', $request->course_id)
                        ->where('term_to_course.status', '<>', '0')
                        ->orderBy('term.order', 'asc')
                        ->pluck('term.name', 'term.id')
                        ->toArray();

        $synList = ['0' => __('label.SELECT_SYN_OPT')] + SynToCourse::join('syndicate', 'syndicate.id', 'syn_to_course.syn_id')
                        ->where('syn_to_course.course_id', $request->course_id)
                        ->orderBy('syndicate.order', 'asc')
                        ->pluck('syndicate.name', 'syndicate.id')
                        ->toArray();

        $html = view('report.mutualAssessmentSummary.getTerm', compact('termList'))->render();
        $html1 = view('report.mutualAssessmentSummary.getSyn', compact('synList'))->render();
        return Response::json(['html' => $html, 'html1' => $html1]);
    }

    public function getMaEvent(Request $request) {

        $maEventList = ['0' => __('label.SELECT_EVENT_OPT')] + TermToMAEvent::join('mutual_assessment_event', 'mutual_assessment_event.id', '=', 'term_to_ma_event.event_id')
                        ->where('term_to_ma_event.course_id', $request->course_id)
                        ->where('term_to_ma_event.term_id', $request->term_id)
                        ->orderBy('mutual_assessment_event.order', 'asc')
                        ->pluck('mutual_assessment_event.name', 'mutual_assessment_event.id')
                        ->toArray();

        $html = view('report.mutualAssessmentSummary.getMaEvent', compact('maEventList'))->render();
        return Response::json(['html' => $html]);
    }

    public function getsubSyn(Request $request) {

        $html = '';
        $subSynList = ['0' => __('label.SELECT_SUB_SYN_OPT')] + SynToSubSyn::join('sub_syndicate', 'sub_syndicate.id', 'syn_to_sub_syn.sub_syn_id')
                        ->where('syn_to_sub_syn.course_id', $request->course_id)
                        ->where('syn_to_sub_syn.syn_id', $request->syn_id)
                        ->where('sub_syndicate.status', '1')
                        ->orderBy('sub_syndicate.order', 'asc')
                        ->pluck('sub_syndicate.name', 'sub_syndicate.id')
                        ->toArray();
        if (sizeof($subSynList) > 1) {
            $html = view('report.mutualAssessmentSummary.getSubSyn', compact('subSynList'))->render();
        }
        return Response::json(['html' => $html]);
    }

    public function filter(Request $request) {

        $messages = [];
        $rules = [
            'training_year_id' => 'required|not_in:0',
            'course_id' => 'required|not_in:0',
            'term_id' => 'required|not_in:0',
            'maEvent_id' => 'required|not_in:0',
            'syn_id' => 'required|not_in:0',
        ];
        $messages = [
            'training_year_id.not_in' => __('label.THE_TRAINING_YEAR_FIELD_IS_REQUIRED'),
            'course_id.not_in' => __('label.THE_COURSE_FIELD_IS_REQUIRED'),
            'term_id.not_in' => __('label.THE_TERM_FIELD_IS_REQUIRED'),
            'maEvent_id.not_in' => __('label.THE_EVENT_FIELD_IS_REQUIRED'),
            'syn_id.not_in' => __('label.THE_SYN_FIELD_IS_REQUIRED'),
        ];
        if (!empty($request->has_sub_syn)) {
            $rules['sub_syn_id'] = 'required|not_in:0';
            $messages['sub_syn_id.not_in'] = __('label.THE_SUB_SYN_FIELD_IS_REQUIRED');
        }

        $url = 'training_year_id=' . $request->training_year_id . '&course_id=' . $request->course_id . '&term_id=' . $request->term_id
                . '&maEvent_id=' . $request->maEvent_id . '&syn_id=' . $request->syn_id . '&sub_syn_id=' . $request->sub_syn_id
                . '&sort=' . $request->sort;

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect('mutualAssessmentSummaryReport?generate=false&' . $url)
                            ->withInput()
                            ->withErrors($validator);
        }
        return redirect('mutualAssessmentSummaryReport?generate=true&' . $url);
    }

}
