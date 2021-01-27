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
use Carbon\Carbon;
use App\Exports\ExcelExport;
use App\Imports\ExcelImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class MutualAssessmentController extends Controller {

    private $controller = 'MutualAssessment';

    public function markingSheet(Request $request) {
        //get only active training year
        $activeTrainingYearInfo = TrainingYear::where('status', '1')->first();
        if (empty($activeTrainingYearInfo)) {
            $void['header'] = __('label.GENERATE_MARKING_SHEET');
            $void['body'] = __('label.THERE_IS_NO_ACTIVE_TRAINING_YEAR');
            return view('layouts.void', compact('void'));
        }
        $courseList = ['0' => __('label.SELECT_COURSE_OPT')] + Course::where('training_year_id', $activeTrainingYearInfo->id)
                        ->where('status', '1')->orderBy('id', 'desc')->pluck('name', 'id')->toArray();


        return view('mutualAssessment.generateMarkingSheet', compact('activeTrainingYearInfo', 'courseList'));
    }

    public function getTerm(Request $request) {
        $courseId = $request->course_id;
        $eventsList = ['0' => __('label.SELECT_EVENT_OPT')];
        $activeTerm = TermToCourse::join('term', 'term.id', '=', 'term_to_course.term_id')
                        ->select('term.name as name', 'term.id as id')
                        ->where('term_to_course.course_id', $courseId)
                        ->where('term_to_course.active', '1')
                        ->orderBy('term.order', 'asc')->first();
        if (!empty($activeTerm)) {

            $eventsList = $eventsList + TermToMAEvent::join('mutual_assessment_event', 'mutual_assessment_event.id', '=', 'term_to_ma_event.event_id')
                            ->where('term_to_ma_event.course_id', $courseId)->where('term_to_ma_event.term_id', $activeTerm->id)
                            ->orderBy('mutual_assessment_event.order', 'asc')->pluck('mutual_assessment_event.name', 'mutual_assessment_event.id')
                            ->toArray();
        }

        $html = view('mutualAssessment.showActiveTerm', compact('activeTerm', 'eventsList'))->render();


        return Response::json(['success' => true, 'html' => $html], 200);
    }

    public function getSyn(Request $request) {
        $courseId = $request->course_id;
        $syndicateList = ['0' => __('label.SELECT_SYNDICATE_OPT')];
        if (!empty($courseId)) {
            $syndicateList = $syndicateList + SynToCourse::join('syndicate', 'syndicate.id', '=', 'syn_to_course.syn_id')
                            ->where('syn_to_course.course_id', $courseId)
                            ->where('syndicate.status', '1')->orderBy('syndicate.order', 'asc')
                            ->pluck('syndicate.name', 'syndicate.id')->toArray();
        }
        $html = view('mutualAssessment.showSyn', compact('syndicateList'))->render();

        return Response::json(['success' => true, 'html' => $html], 200);
    }

    public function getCmAndSubSyndicate(Request $request) {
        $courseId = $request->course_id;
        $termId = $request->term_id;
        $eventId = $request->event_id;
        $synId = $request->syn_id;
        $subSynId = 0;
        $html = $html1 = '';
        $subSyndicateArr = SynToSubSyn::join('sub_syndicate', 'sub_syndicate.id', '=', 'syn_to_sub_syn.sub_syn_id')
                        ->where('syn_to_sub_syn.course_id', $courseId)
                        ->where('syn_to_sub_syn.syn_id', $synId)
                        ->orderBy('sub_syndicate.order', 'asc')
                        ->pluck('sub_syndicate.name', 'sub_syndicate.id')->toArray();


        $subSyndicateList = ['0' => __('label.SELECT_SUB_SYNDICATE_OPT')] + $subSyndicateArr;

//        $courseName = Course::select('name')->where('id', $courseId)->first();
//        $term = Term::select('name')->where('id', $termId)->first();
//        $eventName = MutualAssessmentEvent:: select('name')->where('id', $eventId)->first();
//        $syndicate = Syndicate::select('name')->where('id', $synId)->first();
        if (sizeof($subSyndicateList) > 1) {
            $html = view('mutualAssessment.showSubSyndicateList', compact('subSyndicateList'))->render();
        } else {
            $exportCmIdArr = MaMksExport::where('course_id', $courseId)
                            ->where('term_id', $termId)
                            ->where('event_id', $eventId)
                            ->where('syn_id', $synId)
                            ->where('sub_syn_id', 0)
                            ->pluck('marking_cm_id', 'id')->toArray();

            $deliverStatusArr = MaMksExport::where('course_id', $courseId)
                            ->where('term_id', $termId)
                            ->where('event_id', $eventId)
                            ->where('syn_id', $synId)
                            ->where('sub_syn_id', 0)
                            ->where('deliver_status', '1')
                            ->pluck('marking_cm_id', 'id')->toArray();
            //print_r($deliverStatusArr); exit;

            $cmList = CmToSyn::join('cm_basic_profile', 'cm_to_syn.cm_id', '=', 'cm_basic_profile.id')
                            ->leftJoin('rank', 'rank.id', '=', 'cm_basic_profile.rank_id')
                            ->select('cm_basic_profile.id as cm_id', 'cm_basic_profile.full_name as full_name', 'cm_basic_profile.personal_no as personal_no', 'rank.name as rank', 'cm_basic_profile.photo as photo')
                            ->where('cm_to_syn.course_id', $courseId)
                            ->where('cm_to_syn.term_id', $termId)
                            ->where('cm_to_syn.syn_id', $synId)
                            ->where('cm_basic_profile.status', '1')
                            ->orderBy('cm_basic_profile.personal_no', 'asc')
                            ->orderBy('rank.order', 'asc')
                            ->orderBy('cm_basic_profile.full_name', 'asc')->get()->toArray();
            //print_r($subSyndicateList);

            $html1 = view('mutualAssessment.showCmList', compact('cmList', 'courseId', 'termId', 'eventId', 'synId', 'subSynId'
                            , 'exportCmIdArr', 'deliverStatusArr'))->render();
        }
        return Response::json(['success' => true, 'subSyndicateList' => $html, 'cmList' => $html1], 200);
    }

    public function getCmbySubSyn(Request $request) {
        $courseId = $request->course_id;
        $termId = $request->term_id;
        $eventId = $request->event_id;
        $synId = $request->syn_id;
        $subSynId = $request->sub_syn_id;
        $cmList = [];
//        $courseName = Course::select('name')->where('id', $courseId)->first();
//        $term = Term::select('name')->where('id', $termId)->first();
//        $eventName = MutualAssessmentEvent:: select('name')->where('id', $eventId)->first();
//        $syndicate = Syndicate::select('name')->where('id', $synId)->first();

        $exportCmIdArr = MaMksExport::where('course_id', $courseId)
                        ->where('term_id', $termId)
                        ->where('event_id', $eventId)
                        ->where('syn_id', $synId)
                        ->where('sub_syn_id', $subSynId)
                        ->pluck('marking_cm_id', 'id')->toArray();

        $deliverStatusArr = MaMksExport::where('course_id', $courseId)
                        ->where('term_id', $termId)
                        ->where('event_id', $eventId)
                        ->where('syn_id', $synId)
                        ->where('sub_syn_id', $subSynId)
                        ->where('deliver_status', '1')
                        ->pluck('marking_cm_id', 'id')->toArray();


        $cmList = CmToSyn::join('cm_basic_profile', 'cm_to_syn.cm_id', '=', 'cm_basic_profile.id')
                        ->join('rank', 'rank.id', '=', 'cm_basic_profile.rank_id')
                        ->select('cm_basic_profile.id as cm_id', 'cm_basic_profile.full_name as full_name', 'cm_basic_profile.personal_no as personal_no', 'rank.name as rank', 'cm_basic_profile.photo as photo')
                        ->where('cm_to_syn.course_id', $courseId)
                        ->where('cm_to_syn.term_id', $termId)
                        ->where('cm_to_syn.syn_id', $synId)
                        ->where('cm_to_syn.sub_syn_id', $subSynId)
                        ->where('cm_basic_profile.status', '1')
                        ->orderBy('cm_basic_profile.personal_no', 'asc')
                        ->orderBy('rank.order', 'asc')
                        ->orderBy('cm_basic_profile.full_name', 'asc')->get()->toArray();
        //print_r($subSyndicateList);

        $cmList = view('mutualAssessment.showCmList', compact('cmList', 'courseId', 'termId', 'eventId', 'synId', 'subSynId', 'exportCmIdArr', 'deliverStatusArr'))->render();
        return Response::json(['success' => true, 'cmList' => $cmList], 200);
    }

    public function getPreviewBtn(Request $request) {

        $courseId = $request->course_id;
        $termId = $request->term_id;
        $synId = $request->syn_id;
        $eventId = $request->event_id;
        $cmId = $request->cm_id;
        $cmList = CmToSyn::join('cm_basic_profile', 'cm_to_syn.cm_id', '=', 'cm_basic_profile.id')
                        ->select('cm_basic_profile.full_name as full_name', 'cm_basic_profile.id as id')
                        ->where('cm_to_syn.course_id', $courseId)
                        ->where('cm_to_syn.term_id', $termId)
                        ->where('cm_to_syn.syn_id', $synId)
                        ->where('cm_basic_profile.status', '1')
                        ->where('cm_basic_profile.id', '!=', $cmId)->count();
        $html = '';
        if (!empty($cmList) && !empty($cmId)) {
            $html = '<div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-8">
                            <button class="btn btn-circle green" type="button" id="previewMarkingSheet">
                                <i class="fa fa-check"></i> ' . __('label.PREVIEW_MARKING_SHEET') .
                    '</button>
                        </div>
                    </div>
                </div>';
        }
        return Response::json(['success' => true, 'html' => $html], 200);
    }

    public function previewMarkingSheet(Request $request) {
        $rules = array(
            'course_id' => 'required',
            'term_id' => 'required',
            'syn_id' => 'required',
            'ma_event_id' => 'required',
            'cm_id' => 'required',
        );
        $messages = array(
            'course_id.required' => __('label.COURSE_MUST_BE_SELECTED'),
            'term_id.required' => __('label.TERM_MUST_BE_SELECTED'),
            'syn_id.required' => __('label.SYNDICATE_MUST_BE_SELECTED'),
            'ma_event_id.required' => __('label.MUTUAL_ASSESSMENT_EVENT_MUST_BE_SELECTED'),
            'cm_id.required' => __('label.COURSE_MEMBER_MUST_BE_SELECTED'),
        );
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $courseId = $request->course_id;
        $termId = $request->term_id;
        $synId = $request->syn_id;
        $subSynId = !empty($request->sub_syn_id) ? $request->sub_syn_id : 0;
        $eventId = $request->ma_event_id;
        $cmId = $request->cm_id;
        //print_r($request->all());
        $courseName = Course::select('name')->where('id', $courseId)->first();
        $term = Term::select('name')->where('id', $termId)->first();
        $eventName = MutualAssessmentEvent:: select('name')->where('id', $eventId)->first();
        $syndicate = Syndicate::select('name')->where('id', $synId)->first();
        $subSyndicate = SubSyndicate::select('name')->where('id', $subSynId)->first();
        $cmName = CmBasicProfile::select('full_name')->where('id', $cmId)->first();

        $cmList = CmToSyn::join('cm_basic_profile', 'cm_to_syn.cm_id', '=', 'cm_basic_profile.id')
                ->join('rank', 'rank.id', '=', 'cm_basic_profile.rank_id')
                ->select('cm_basic_profile.full_name as full_name', 'cm_basic_profile.personal_no as personal_no', 'rank.name as rank', 'cm_basic_profile.photo as photo')
                ->where('cm_to_syn.course_id', $courseId)
                ->where('cm_to_syn.term_id', $termId)
                ->where('cm_to_syn.syn_id', $synId);
        if (!empty($subSynId)) {
            $cmList = $cmList->where('cm_to_syn.sub_syn_id', $subSynId);
        }
        $cmList = $cmList->where('cm_basic_profile.status', '1')
                        ->where('cm_basic_profile.id', '!=', $cmId)
                        ->orderBy('cm_basic_profile.personal_no', 'asc')
                        ->orderBy('rank.order', 'asc')
                        ->orderBy('cm_basic_profile.full_name', 'asc')->get();

        if ($cmList->isEmpty()) {
            return Response::json(['success' => false, 'heading' => 'Has 1 CM Only', 'message' => __('label.THIS_SYN_OR_SUBSYN_HAS_ONE_CM_ONLY')], 401);
        }
        $html = '';
        if (!empty($cmList) && !empty($cmId)) {
            $html = view('mutualAssessment.previewMarkingSheet', compact('cmList', 'courseId', 'termId', 'synId', 'subSynId', 'eventId', 'cmId', 'courseName', 'term', 'syndicate', 'subSyndicate', 'cmName', 'eventName'))->render();
        }
        return Response::json(['success' => true, 'html' => $html], 200);
    }

    public function generate(Request $request) {
        $courseId = $request->course_id;
        $termId = $request->term_id;
        $synId = $request->syn_id;
        $subSynId = !empty($request->sub_syn_id) ? $request->sub_syn_id : 0;
        $eventId = $request->event_id;
        $cmId = $request->cm_id;

        $courseInfo = Course::join('training_year', 'training_year.id', '=', 'course.training_year_id')->select('course.name as course_name', 'training_year.name as training_year_name')->where('course.id', $courseId)->first();
        $courseName = $courseInfo->course_name;
        $term = Term::select('name')->where('id', $termId)->first();
        $eventName = MutualAssessmentEvent:: select('name')->where('id', $eventId)->first();
        $syndicate = Syndicate::select('name')->where('id', $synId)->first();
        $subSyndicate = SubSyndicate::select('name')->where('id', $subSynId)->first();
        $cmName = CmBasicProfile::select('full_name')->where('id', $cmId)->first();

        $cmList = CmToSyn::join('cm_basic_profile', 'cm_to_syn.cm_id', '=', 'cm_basic_profile.id')
                ->leftJoin('rank', 'rank.id', '=', 'cm_basic_profile.rank_id')
                ->select('cm_basic_profile.full_name as full_name', 'cm_basic_profile.personal_no as personal_no', 'rank.name as rank', 'cm_basic_profile.photo as photo')
                ->where('cm_to_syn.course_id', $courseId)
                ->where('cm_to_syn.term_id', $termId)
                ->where('cm_to_syn.syn_id', $synId);
        if (!empty($subSynId)) {
            $cmList = $cmList->where('cm_to_syn.sub_syn_id', $subSynId);
        }
        $cmList = $cmList->where('cm_basic_profile.status', '1')
                        ->where('cm_basic_profile.id', '!=', $cmId)
                        ->orderBy('cm_basic_profile.personal_no', 'asc')
                        ->orderBy('rank.order', 'asc')
                        ->orderBy('cm_basic_profile.full_name', 'asc')->get();



        $exportInfo = MaMksExport::select('id')
                        ->where('course_id', $courseId)
                        ->where('term_id', $termId)
                        ->where('event_id', $eventId)
                        ->where('syn_id', $synId)
                        ->where('sub_syn_id', $subSynId)
                        ->where('marking_cm_id', $cmId)->first();

        $html = '';
        if (!$cmList->isEmpty() && !empty($cmId)) {
            if (empty($exportInfo->id)) {
                MaMksExport::insert([
                    'course_id' => $courseId,
                    'term_id' => $termId,
                    'event_id' => $eventId,
                    'syn_id' => $synId,
                    'sub_syn_id' => $subSynId,
                    'marking_cm_id' => $cmId,
                    'exported_at' => date('Y-m-d H:s:i'),
                    'exported_by' => Auth::user()->id,
                ]);
            } else {
                $export = MaMksExport::find($exportInfo->id);
                $export->exported_at = date('Y-m-d H:s:i');
                $export->exported_by = Auth::user()->id;
                $export->save();
            }
            $viewFile = 'mutualAssessment.excelMarkingSheet';
            $downLoadFileName = $courseInfo->training_year_name . '_'
                    . $courseName . '_'
                    . $term->name . '_'
                    . $eventName->name . '_'
                    . $syndicate->name . '_'
                    . (!empty($subSynId) ? ($subSyndicate->name . '_') : '')
                    . $cmName->full_name
                    . '.xlsx';

            return Excel::download(new ExcelExport($viewFile, compact('cmList', 'courseId', 'termId', 'synId', 'eventId', 'cmId', 'courseName', 'term', 'syndicate', 'subSyndicate', 'cmName', 'eventName')), $downLoadFileName);
        }
    }

    public function changeDeliverStatus(Request $request) {
        $courseId = $request->course_id;
        $termId = $request->term_id;
        $eventId = $request->event_id;
        $synId = $request->syn_id;
        $subSynId = !empty($request->sub_syn_id) ? $request->sub_syn_id : 0;
        $cmId = $request->cm_id;
        // print_r($request->all());
        $exportInfo = MaMksExport::where('course_id', $courseId)
                        ->where('term_id', $termId)
                        ->where('event_id', $eventId)
                        ->where('syn_id', $synId)
                        ->where('sub_syn_id', $subSynId)
                        ->where('marking_cm_id', $cmId)->first();

        if (!empty($exportInfo)) {
            $newStatus = $exportInfo->deliver_status = empty($exportInfo->deliver_status) ? '1' : '0';
            if ($exportInfo->save()) {
                if ($newStatus == 1) {
                    $message = __('label.STATUS_HAS_BEEN_CHANGED_TO_DELIVERED');
                } else {
                    $message = __('label.STATUS_HAS_BEEN_CHANGED_TO_NOT_DELIVERED');
                }

                return Response::json(['success' => true, 'heading' => 'Success', 'message' => $message], 200);
            }
        } else {
            $message = __('label.SOMETING_WRONG');
            return Response::json(['success' => false, 'heading' => 'Error', 'message' => $message], 400);
        }
    }

    public function importMarkingSheet(Request $request) {
        $activeTrainingYearInfo = TrainingYear::where('status', '1')->first();
        if (empty($activeTrainingYearInfo)) {
            $void['header'] = __('label.IMPORT_MARKING_SHEET');
            $void['body'] = __('label.THERE_IS_NO_ACTIVE_TRAINING_YEAR');
            return view('layouts.void', compact('void'));
        }
        $courseList = ['0' => __('label.SELECT_COURSE_OPT')] + Course::where('training_year_id', $activeTrainingYearInfo->id)
                        ->where('status', '1')->orderBy('id', 'desc')->pluck('name', 'id')->toArray();


        return view('mutualAssessment.importMarkingSheet', compact('activeTrainingYearInfo', 'courseList'));
    }

    public function getSubsynAndCmList(Request $request) {
        $courseId = $request->course_id;
        $termId = $request->term_id;
        $synId = $request->syn_id;
        $html = $subSynList = '';
        $subSyndicateArr = SynToSubSyn::join('sub_syndicate', 'sub_syndicate.id', '=', 'syn_to_sub_syn.sub_syn_id')
                        ->where('syn_to_sub_syn.course_id', $courseId)->where('syn_to_sub_syn.syn_id', $synId)
                        ->orderBy('sub_syndicate.order', 'asc')
                        ->pluck('sub_syndicate.name', 'sub_syndicate.id')->toArray();


        $subSyndicateList = ['0' => __('label.SELECT_SUB_SYNDICATE_OPT')] + $subSyndicateArr;

        if (sizeof($subSyndicateList) > 1) {
            $subSynList = view('mutualAssessment.showSubSyndicateList', compact('subSyndicateList'))->render();
        } else {
            $cmList = ['0' => __('label.SELECT_CM_OPT')] + CmToSyn::join('cm_basic_profile', 'cm_to_syn.cm_id', '=', 'cm_basic_profile.id')
                            ->leftJoin('rank', 'rank.id', '=', 'cm_basic_profile.rank_id')
                            ->select('cm_basic_profile.id as cm_id', DB::raw("CONCAT(cm_basic_profile.full_name, ' (',cm_basic_profile.personal_no, ')') as cm_name"))
                            ->where('cm_to_syn.course_id', $courseId)
                            ->where('cm_to_syn.term_id', $termId)
                            ->where('cm_to_syn.syn_id', $synId)
                            ->where('cm_basic_profile.status', '1')
                            ->orderBy('cm_basic_profile.personal_no', 'asc')
                            ->orderBy('rank.order', 'asc')
                            ->orderBy('cm_basic_profile.full_name', 'asc')
                            ->pluck('cm_name', 'cm_id')->toArray();


            $html = view('mutualAssessment.showCmOptions', compact('cmList'))->render();
        }

        return Response::json(['success' => true, 'subSynList' => $subSynList, 'cmList' => $html], 200);
    }

    public function getCmListBySubSyn(Request $request) {
        $courseId = $request->course_id;
        $termId = $request->term_id;
        $synId = $request->syn_id;
        $subSynId = $request->sub_syn_id;

        $cmList = ['0' => __('label.SELECT_CM_OPT')] + CmToSyn::join('cm_basic_profile', 'cm_to_syn.cm_id', '=', 'cm_basic_profile.id')
                        ->leftJoin('rank', 'rank.id', '=', 'cm_basic_profile.rank_id')
                        ->select('cm_basic_profile.id as cm_id', DB::raw("CONCAT(cm_basic_profile.full_name, ' (',cm_basic_profile.personal_no, ')') as cm_name"))
                        ->where('cm_to_syn.course_id', $courseId)
                        ->where('cm_to_syn.term_id', $termId)
                        ->where('cm_to_syn.syn_id', $synId)
                        ->where('cm_to_syn.sub_syn_id', $subSynId)
                        ->where('cm_basic_profile.status', '1')
                        ->orderBy('cm_basic_profile.personal_no', 'asc')
                        ->orderBy('rank.order', 'asc')
                        ->orderBy('cm_basic_profile.full_name', 'asc')
                        ->pluck('cm_name', 'cm_id')->toArray();

        $cmList = view('mutualAssessment.showCmOptions', compact('cmList'))->render();

        return Response::json(['success' => true, 'cmList' => $cmList], 200);
    }

    public function getFileUploader(Request $request) {
        $rules = array(
            'course_id' => 'required',
            'term_id' => 'required',
            'syn_id' => 'required',
            'ma_event_id' => 'required',
            'cm_id' => 'required',
        );
        $messages = array(
            'course_id.required' => __('label.COURSE_MUST_BE_SELECTED'),
            'term_id.required' => __('label.TERM_MUST_BE_SELECTED'),
            'syn_id.required' => __('label.SYNDICATE_MUST_BE_SELECTED'),
            'ma_event_id.required' => __('label.MUTUAL_ASSESSMENT_EVENT_MUST_BE_SELECTED'),
            'cm_id.required' => __('label.COURSE_MEMBER_MUST_BE_SELECTED'),
        );
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

        $courseId = $request->course_id;
        $termId = $request->term_id;
        $eventId = $request->ma_event_id;
        $synId = $request->syn_id;
        $subSynId = !empty($request->sub_syn_id) ? $request->sub_syn_id : 0;
        $cmId = $request->cm_id;

//        $courseName = Course::select('name')->where('id', $courseId)->first();
//        $term = Term::select('name')->where('id', $termId)->first();
//        $eventName = MutualAssessmentEvent:: select('name')->where('id', $eventId)->first();
//        $syndicate = Syndicate::select('name')->where('id', $synId)->first();
//        $subSyndicate = SubSyndicate::select('name')->where('id', $subSynId)->first();
//        $cmName = CmBasicProfile::select('full_name')->where('id', $cmId)->first();

        $cmList = CmToSyn::join('cm_basic_profile', 'cm_to_syn.cm_id', '=', 'cm_basic_profile.id')
                        ->leftJoin('rank', 'rank.id', '=', 'cm_basic_profile.rank_id')
                        ->leftJoin('mutual_assessment_marking', function($join) use($courseId, $termId, $eventId, $synId, $cmId) {
                            $join->on('mutual_assessment_marking.cm_id', '=', 'cm_basic_profile.id');
                            $join->where('mutual_assessment_marking.course_id', '=', $courseId);
                            $join->where('mutual_assessment_marking.term_id', '=', $termId);
                            $join->where('mutual_assessment_marking.event_id', '=', $eventId);
                            $join->where('mutual_assessment_marking.syndicate_id', '=', $synId);
                            $join->where('mutual_assessment_marking.marking_cm_id', '=', $cmId);
                        })
                        ->select('cm_basic_profile.full_name as full_name', 'cm_basic_profile.personal_no as personal_no', 'rank.name as rank', 'cm_basic_profile.photo as photo', 'mutual_assessment_marking.position as position')
                        ->where('cm_to_syn.course_id', $courseId)
                        ->where('cm_to_syn.term_id', $termId)
                        ->where('cm_to_syn.syn_id', $synId)
                        ->where('cm_basic_profile.status', '1')
                        ->where('cm_basic_profile.id', '!=', $cmId)
                        ->orderBy('cm_basic_profile.personal_no', 'asc')
                        ->orderBy('rank.order', 'asc')
                        ->orderBy('cm_basic_profile.full_name', 'asc')->get();

        $lockInfo = MutualAssessmentMarkingLock::where('course_id', $request->course_id)
                        ->where('term_id', $request->term_id)
                        ->where('event_id', $request->ma_event_id)
                        ->where('syndicate_id', $request->syn_id)
                        ->where('marking_cm_id', $request->cm_id)
                        ->where('lock_status', '1')->get();

        if (!empty($subSynId)) {
            $cmList = CmToSyn::join('cm_basic_profile', 'cm_to_syn.cm_id', '=', 'cm_basic_profile.id')
                            ->leftJoin('rank', 'rank.id', '=', 'cm_basic_profile.rank_id')
                            ->leftJoin('mutual_assessment_marking', function($join) use($courseId, $termId, $eventId, $synId, $subSynId, $cmId) {
                                $join->on('mutual_assessment_marking.cm_id', '=', 'cm_basic_profile.id');
                                $join->where('mutual_assessment_marking.course_id', '=', $courseId);
                                $join->where('mutual_assessment_marking.term_id', '=', $termId);
                                $join->where('mutual_assessment_marking.event_id', '=', $eventId);
                                $join->where('mutual_assessment_marking.syndicate_id', '=', $synId);
                                $join->where('mutual_assessment_marking.sub_syndicate_id', '=', $subSynId);
                                $join->where('mutual_assessment_marking.marking_cm_id', '=', $cmId);
                            })
                            ->select('cm_basic_profile.full_name as full_name', 'cm_basic_profile.personal_no as personal_no', 'rank.name as rank', 'cm_basic_profile.photo as photo', 'mutual_assessment_marking.position as position')
                            ->where('cm_to_syn.course_id', $courseId)
                            ->where('cm_to_syn.term_id', $termId)
                            ->where('cm_to_syn.syn_id', $synId)
                            ->where('cm_to_syn.sub_syn_id', $subSynId)
                            ->where('cm_basic_profile.status', '1')
                            ->where('cm_basic_profile.id', '!=', $cmId)
                            ->orderBy('cm_basic_profile.personal_no', 'asc')
                            ->orderBy('rank.order', 'asc')
                            ->orderBy('cm_basic_profile.full_name', 'asc')->get();

            $lockInfo = MutualAssessmentMarkingLock::where('course_id', $courseId)
                            ->where('term_id', $termId)
                            ->where('event_id', $eventId)
                            ->where('syndicate_id', $synId)
                            ->where('sub_syndicate_id', $subSynId)
                            ->where('marking_cm_id', $cmId)
                            ->where('lock_status', '1')->get();
        }


        $markingSheet = $fileUpload = '';
        if (!empty($cmList) && !empty($cmId)) {
            $markingSheet = view('mutualAssessment.showMarkingSheet', compact('cmList', 'courseId', 'termId', 'synId', 'subSynId', 'eventId', 'cmId'))->render();
            if ($lockInfo->isEmpty()) {
                $fileUpload = '<div class="form-group">
                    <div class="row">
                        <label class="control-label col-md-4" for="markingSheet">' . __('label.UPLOAD_MARKING_SHEET') . ':<span class="text-danger"> *</span></label>
                        <div class="col-md-8">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <span class="btn green btn-file">
                                                                <span class="fileinput-new"> Select file </span>
                                                                <span class="fileinput-exists"> Change </span>
                                                                <input type="hidden" value="" name="...">
                                                                <input type="file" name="marking_sheet" id="markingSheet"> </span>
                                                            <span class="fileinput-filename"></span> &nbsp;
                                                            <a href="javascript:;" class="close fileinput-exists" data-dismiss="fileinput"> </a>
                                                        </div>
                          
                        </div>
                    </div>
                </div>
               <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-8">
                            <button class="pull-right btn purple-sharp btn-sm" type="button" id="import">
                                <i class="fa fa-arrow-down" aria-hidden="true"></i> ' . __('label.IMPORT') .
                        '</button>
                        </div>
                    </div>
                </div>';
            }
        }

        return Response::json(['success' => true, 'fileUpload' => $fileUpload, 'markingSheet' => $markingSheet], 200);
    }

    public function import(Request $request) {
        $courseId = $request->course_id;
        $termId = $request->term_id;
        $eventId = $request->ma_event_id;
        $synId = $request->syn_id;
        $subSynId = !empty($request->sub_syn_id) ? $request->sub_syn_id : 0;
        $cmId = $request->cm_id;

        $rules = array(
            'course_id' => 'required',
            'term_id' => 'required',
            'syn_id' => 'required',
            'ma_event_id' => 'required',
            'cm_id' => 'required',
            'marking_sheet' => 'required|max:10000|mimes:xlsx,xls'
        );
        $messages = array(
            'course_id.required' => __('label.COURSE_MUST_BE_SELECTED'),
            'term_id.required' => __('label.TERM_MUST_BE_SELECTED'),
            'syn_id.required' => __('label.SYNDICATE_MUST_BE_SELECTED'),
            'ma_event_id.required' => __('label.MUTUAL_ASSESSMENT_EVENT_MUST_BE_SELECTED'),
            'cm_id.required' => __('label.COURSE_MEMBER_MUST_BE_SELECTED'),
            'marking_sheet.required' => __('label.MARKING_SHEET_MUST_BE_SELECTED'),
            'marking_sheet.mimes' => __('label.INVALID_FILE_FORMAT_EXPECTED_XLSX_XLS'),
        );
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(array('success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()), 400);
        }

//        $courseName = Course::select('name')->where('id', $courseId)->first();
//        $term = Term::select('name')->where('id', $termId)->first();
//        $eventName = MutualAssessmentEvent:: select('name')->where('id', $eventId)->first();
//        //print_r($request->all());exit;
//        $syndicate = Syndicate::select('name')->where('id', $synId)->first();
//        $cmName = CmBasicProfile::select('full_name')->where('id', $cmId)->first();

        $cmList = CmToSyn::join('cm_basic_profile', 'cm_to_syn.cm_id', '=', 'cm_basic_profile.id')
                ->leftJoin('rank', 'rank.id', '=', 'cm_basic_profile.rank_id')
                ->select('cm_basic_profile.id as cm_id', 'cm_basic_profile.full_name as full_name', 'cm_basic_profile.personal_no as personal_no', 'rank.name as rank', 'cm_basic_profile.photo as photo')
                ->where('cm_to_syn.course_id', $courseId)
                ->where('cm_to_syn.term_id', $termId)
                ->where('cm_to_syn.syn_id', $synId);

        if (!empty($subSynId)) {
            $cmList = $cmList->where('cm_to_syn.sub_syn_id', $subSynId);
        }

        $cmList = $cmList->where('cm_basic_profile.status', '1')
                ->where('cm_basic_profile.id', '!=', $cmId)
                ->orderBy('cm_basic_profile.personal_no', 'asc')
                ->orderBy('rank.order', 'asc')
                ->orderBy('cm_basic_profile.full_name', 'asc');

        $cmIdArr = $cmList->pluck('cm_id')->toArray();
        $cmListData = $cmList->get();
        //print_r($cmName->toArray());exit;

        if ($request->hasFile('marking_sheet')) {
            $file = $request->file('marking_sheet');
            $extension = File::extension($file->getClientOriginalName());
            if (in_array($extension, ['xlsx', 'xls'])) {
                $markSheetData = Excel::toArray(new ExcelImport, $file);
            }
        }
        $personalNumberArr = $positionArr = $markingSheetInfo = [];
        $i = $j = $x = 0;
        foreach ($markSheetData[0] as $rowNumber => $rowData) {

            if ($rowNumber == 1) { //get mark sheet info
                foreach ($rowData as $colNumber => $columnData) {
                    if ($colNumber <= 7) {
                        $markingSheetInfo[$x] = $columnData ?? '';
                        $x++;
                    }
                }
            }

            if ($rowNumber > 3) {
                foreach ($rowData as $colNumber => $columnData) {
                    //get cm personal number array
                    if ($colNumber == 1) {
                        $personalNumberArr[$i] = $columnData ?? '';
                        $i++;
                    }
                    //get cm position array
                    if ($colNumber == 5) {
                        $positionArr[$j] = $columnData ?? '';
                        $j++;
                    }
                }
            }
        }

        //print_r($markingSheetInfo);
        $cmIdArrFromExcelsheet = CmBasicProfile::whereIn('personal_no', $personalNumberArr)->pluck('id')->toArray();

        $cmIdAndPositonArr = []; // cmID in key and position in value

        $errorFlag = $errorIndex = 0;
        $errorMessageArr = [];



        if (array_diff($cmIdArr, $cmIdArrFromExcelsheet) != array_diff($cmIdArrFromExcelsheet, $cmIdArr)) {
            $errorFlag = 1;
            $errorMessageArr['cm_not_match'] = __('label.CM_LIST_IN_THE_PROVIDED_MARKING_SHEET_IS_NOT_ACCURATELY_MATCHING');
        } else {
            if (!empty($positionArr)) {
                foreach ($positionArr as $key => $value) {
                    if (empty($value)) {
                        $errorFlag = 1;
                        $errorMessageArr['empty_position'] = __('label.EACH_POSITION_CELL_MUST_HAVE_VALUE');
                    }
                }

                $uniqPositionArr = array_unique($positionArr);
                if (array_diff($positionArr, $uniqPositionArr) != array_diff($uniqPositionArr, $positionArr)) {
                    $errorFlag = 1;
                    $errorMessageArr['positions_not_unique'] = __('label.POSITION_MUST_BE_UNIQUE');
                }

                if (max($positionArr) > sizeof($cmIdArr)) {
                    $errorFlag = 1;
                    $errorMessageArr['max_position'] = __('label.MAX_POSITIION_MUST_NOT_EXCEED_TOTAL_NUMBER_OF_CM');
                }

                if (min($positionArr) != 1) {
                    $errorFlag = 1;
                    $errorMessageArr['min_position'] = __('label.MIN_POSITION_MUST_BE_1');
                }
            }
        }




        if ($errorFlag == 0) {
            foreach ($cmIdArrFromExcelsheet as $key => $id) {
                $cmIdAndPositonArr[$id] = $positionArr[$key];
            }
        } else {
            return Response::json(array('success' => false, 'heading' => 'File Validation Error', 'errormessage' => $errorMessageArr), 401);
        }

//        if($cmIdArr === $cmIdArrFromExcelsheet && sizeof($cmIdArr) == sizeof($positionArr) && $positionArr === array_unique($positionArr) && max($positionArr) == sizeof($cmIdArr) && min($positionArr) == 1){
//            foreach($cmIdArrFromExcelsheet as $key => $id){
//                $cmIdAndPositonArr[$id] = $positionArr[$key];
//            }
//        }
        //print_r($cmIdAndPositonArr);exit;
//        $fileUpload = '<div class="form-group">
//                    <div class="row">
//                        <label class="control-label col-md-4" for="markingSheet">' . __('label.UPLOAD_MARKING_SHEET') . ':<span class="text-danger"> *</span></label>
//                        <div class="col-md-8">
//                           <input type="file" name="marking_sheet" id="markingSheet"/>
//                        </div>
//                    </div>
//                </div>
//               <div class="form-actions">
//                    <div class="row">
//                        <div class="col-md-offset-4 col-md-8">
//                            <button class="btn green pull-right btn-lg" type="button" id="importWithPosition">
//                                <i class="fa fa-chevron-down" aria-hidden="true"></i> ' . __('label.SUBMIT') .
//                '</button>
//                        </div>
//                    </div>
//                </div>';
        $markingSheet = view('mutualAssessment.showMarkingSheetWithPosition', compact('cmIdAndPositonArr', 'cmListData', 'courseId', 'termId', 'synId', 'eventId', 'cmId', 'markingSheetInfo'))->render();
        //return Response::json(['success' => true, 'fileUpload' => $fileUpload, 'markingSheet' => $markingSheet], 200);
        return Response::json(['success' => true, 'markingSheet' => $markingSheet], 200);
    }

    public function saveImportedData(Request $request) {
        $cmIdAndPositon = $request->cm_id_and_position_arr;
        $cmIdAndPositonArr = json_decode($cmIdAndPositon, true);
        $data = $lockData = [];
        $i = 0;

        if (!empty($cmIdAndPositonArr)) {
            foreach ($cmIdAndPositonArr as $cmId => $position) {
                $data[$i]['course_id'] = $request->course_id;
                $data[$i]['term_id'] = $request->term_id;
                $data[$i]['event_id'] = $request->ma_event_id;
                $data[$i]['syndicate_id'] = $request->syn_id;
                $data[$i]['sub_syndicate_id'] = !empty($request->sub_syn_id) ? $request->sub_syn_id : 0;
                $data[$i]['marking_cm_id'] = $request->cm_id;
                $data[$i]['cm_id'] = $cmId;
                $data[$i]['position'] = $position;
                $data[$i]['updated_at'] = date('Y-m-d H:s:i');
                $data[$i]['updated_by'] = Auth::user()->id;
                $i++;
            }
        }


        DB::beginTransaction();

        try {
            $deleMutualAssessmentMarking = MutualAssessmentMarking::where('course_id', $request->course_id)
                    ->where('term_id', $request->term_id)
                    ->where('event_id', $request->ma_event_id)
                    ->where('syndicate_id', $request->syn_id);
            if (!empty($request->sub_syn_id)) {
                $deleMutualAssessmentMarking = $deleMutualAssessmentMarking->where('sub_syndicate_id', $request->sub_syn_id);
            }
            $deleMutualAssessmentMarking = $deleMutualAssessmentMarking->where('marking_cm_id', $request->cm_id)->delete();

            if (MutualAssessmentMarking::insert($data)) {
                $successMsg = __('label.MUTUAL_ASSESSMENT_MARKING_HAS_BEEN_ASSIGNED_SUCCESSFULLY');
                $errorMsg = __('label.MUTUAL_ASSESSMENT_MARKING_COULD_NOT_BE_ASSIGNED');
                if ($request->save_status == '2') {
                    $target = new MutualAssessmentMarkingLock;
                    $target->course_id = $request->course_id;
                    $target->term_id = $request->term_id;
                    $target->event_id = $request->ma_event_id;
                    $target->syndicate_id = $request->syn_id;
                    $target->sub_syndicate_id = !empty($request->sub_syn_id) ? $request->sub_syn_id : 0;
                    $target->marking_cm_id = $request->cm_id;
                    $target->lock_status = '1';
                    $target->locked_at = date('Y-m-d H:i:s');
                    $target->locked_by = Auth::user()->id;
                    $target->save();

                    $successMsg = __('label.MUTUAL_ASSESSMENT_MARKING_HAS_BEEN_ASSIGNED_AND_LOCKED_SUCCESSFULLY');
                    $errorMsg = __('label.MUTUAL_ASSESSMENT_MARKING_COULD_NOT_BE_ASSIGNED_AND_LOCKED');
                }
            }
            DB::commit();
            return Response::json(['success' => true, 'saveStatus' => $request->save_status, 'message' => $successMsg], 200);
        } catch (Exception $ex) {
            DB::rollback();
            return Response::json(['success' => false, 'message' => $errorMsg], 401);
        }
    }

}
