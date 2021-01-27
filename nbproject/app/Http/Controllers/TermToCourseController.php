<?php

namespace App\Http\Controllers;

use Validator;
use App\TermToCourse;
use App\Course;
use App\TrainingYear;
use App\Term;
use App\User;
use App\MarkWeight;
use App\LockTerm;
use App\ServiceCourse;
use App\TermToEvent;
use App\EventMarkingLock;
use App\TermToParticular;
use App\ParticularMarkingLock;
use App\Event;
use App\SynToCourse;
use App\EventAssessmentMarkingLock;
use App\DsObsnMarkingLock;
use App\MutualAssessmentMarkingLock;
use App\MiscIpftObsnMarkingLock;
use App\DsToSyn;
use Session;
use Redirect;
use Helper;
use Response;
use Auth;
use DB;
use Common;
use Illuminate\Http\Request;

class TermToCourseController extends Controller {

    public function index(Request $request) {
        $activeTrainingYear = TrainingYear::select('id', 'name')->where('status', '1')->first();
        if (empty($activeTrainingYear)) {
            $void['header'] = __('label.TERM_SCHEDULING');
            $void['body'] = __('label.THERE_IS_NO_ACTIVE_TRAINING_YEAR');
            return view('layouts.void', compact('void'));
        }
        $courseList = ['0' => __('label.SELECT_COURSE_OPT')] + Course::where('training_year_id', $activeTrainingYear->id)
                        ->where('status', '1')->orderBy('id', 'desc')->pluck('name', 'id')->toArray();

        return view('termToCourse.index')->with(compact('activeTrainingYear', 'courseList'));
    }

    public function courseSchedule(Request $request) {

        $termArr = Term::where('status', '1')->orderBy('order', 'asc')->pluck('name', 'id')->toArray();

        $previousDataArr = TermToCourse::select('id', 'course_id', 'term_id', 'initial_date', 'termination_date'
                                , 'number_of_week', 'status', 'active')
                        ->where('course_id', $request->course_id)
                        ->get()->toArray();

        $prevData = [];
        if (!empty($previousDataArr)) {
            foreach ($previousDataArr as $item) {
                $prevData[$item['term_id']] = $item;
            }
        }


        $html = view('termToCourse.courseSchedule', compact('termArr', 'prevData'))->render();
        return response()->json(compact('html'));
    }

    public function saveCourse(Request $request) {
        $termArr = $request->term_id;
        $initialDateArr = $request->initial_date;
        $terminationDateArr = $request->termination_date;
        $numberOfWeekArr = $request->number_of_week;
        $rules = $messages = [];
        $termNameArr = Term::pluck('name', 'id')->toArray();

        if (!empty($termArr)) {
            foreach ($termArr as $termId) {
                $rules['initial_date.' . $termId] = 'required|date';
                $rules['termination_date.' . $termId] = 'required|date|after:initial_date.' . $termId;
                $rules['number_of_week.' . $termId] = 'required|numeric';

                $messages['initial_date.' . $termId . '.required'] = 'Intial Date is Required for ' . $termNameArr[$termId];
                $messages['initial_date.' . $termId . '.date'] = 'Intial Date is Supported Only Date for ' . $termNameArr[$termId];
                $messages['termination_date.' . $termId . '.date'] = 'Termination  Date is Supported Only Date for ' . $termNameArr[$termId];
                $messages['termination_date.' . $termId . '.required'] = 'Termination Date  is Required  for ' . $termNameArr[$termId];
                $messages['termination_date.' . $termId . '.after'] = 'Termination Date  must be greater than Initial Date  for ' . $termNameArr[$termId];
                $messages['number_of_week.' . $termId . '.required'] = 'Number of Week is Required for ' . $termNameArr[$termId];
                $messages['number_of_week.' . $termId . '.numeric'] = 'Number of Week must be Date for ' . $termNameArr[$termId];
            }
        } else {
            return Response::json(['success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => __('label.NO_TERM_HAS_BEEN_ASSIGNED_WITH_THIS_COURSE')], 401);
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json(['success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()], 400);
        }

        $data = [];
        $i = 0;
        if (!empty($termArr)) {
            foreach ($termArr as $termId => $value) {
                $data[$i]['course_id'] = $request->course_id;
                $data[$i]['term_id'] = $termId;
                $data[$i]['initial_date'] = Helper::dateFormatConvert($initialDateArr[$termId]);
                $data[$i]['termination_date'] = Helper::dateFormatConvert($terminationDateArr[$termId]);
                $data[$i]['number_of_week'] = $numberOfWeekArr[$termId];
                $data[$i]['updated_by'] = Auth::user()->id;
                $data[$i]['updated_at'] = date('Y-m-d H:i:s');
                $i++;
            }
        }

        TermToCourse::where('course_id', $request->course_id)
                ->whereIn('status', ['0', '1'])->where('active', '0')
                ->delete();

        if (TermToCourse::insert($data)) {
            return Response::json(['success' => true], 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.NO_TERM_HAS_BEEN_ASSIGNED_WITH_THIS_COURSE')), 401);
        }
    }

    public function activationOrClosing() {
        $activeTrainingYear = TrainingYear::select('id', 'name')->where('status', '1')->first();
        if (empty($activeTrainingYear)) {
            $void['header'] = __('label.TERM_SCHEDULING_ACTIVATION_CLOSING');
            $void['body'] = __('label.THERE_IS_NO_ACTIVE_TRAINING_YEAR');
            return view('layouts.void', compact('void'));
        }
        $trainingYearList = ['0' => __('label.SELECT_TRAINING_YEAR_OPT')] + TrainingYear::where('status', '1')->pluck('name', 'id')->toArray();

        $courseList = ['0' => __('label.SELECT_COURSE_OPT')] + Course::where('training_year_id', $activeTrainingYear->id)
                        ->where('status', '1')->orderBy('id', 'desc')->pluck('name', 'id')->toArray();


        return view('termToCourse.activationOrClosing', compact('activeTrainingYear', 'trainingYearList', 'courseList'));
    }

    public function getActiveOrClose(Request $request) {

        $activeInactiveTerm = TermToCourse::join('term', 'term.id', '=', 'term_to_course.term_id')
                ->select('term_to_course.*', 'term.name as term_name')
                ->where('term_to_course.course_id', $request->course_id)
                ->where('term.status', '1')->orderBy('term.order', 'asc')
                ->get();

        
//        echo '<pre>';
//        print_r($closeConditionArr);
//        exit;

        $html = view('termToCourse.getActiveOrClose', compact('activeInactiveTerm'))->render();
        return response()->json(compact('html'));
    }

    public function activeInactive(Request $request) {

        $termInfo = Term::select('term.name')
                        ->join('term_to_course', 'term_to_course.term_id', '=', 'term.id')
                        ->where('term_to_course.course_id', $request->course_id)
                        ->where('term_to_course.term_id', $request->term_id)
                        ->where('term_to_course.id', $request->id)
                        ->where('term.status', '1')->first();

        if ($request->status == '1') {

            //Find out if there is already any active term under this RecruitCourse
            $alreadyActiveTerm = Term::select('term.name')
                    ->join('term_to_course', 'term_to_course.term_id', '=', 'term.id')
                    ->where('term_to_course.status', '1')
                    ->where('term_to_course.course_id', $request->course_id)
                    ->first();

            if (!empty($alreadyActiveTerm)) {
                //if any active term found, don't proceed further
//                return Response::json(array('success' => false, 'message' => $alreadyActiveTerm->name . ' ' . __('label.IS_ALREADY_ACTIVE')), 401);
            }

            $update = TermToCourse::where('term_id', $request->term_id)
                    ->where('course_id', $request->course_id)
                    ->where('id', $request->id)
                    ->update(['status' => $request->status, 'active' => '1']);

            $update1 = TermToCourse::where('course_id', $request->course_id)
                    ->where('id', '!=', $request->id)
                    ->update(['active' => '0']);

            if ($update) {
                return Response::json(['success' => true, 'message' => $termInfo->name . ' ' . __('label.HAS_BEEN_ACTIVATED')], 200);
            } else {
                return Response::json(array('success' => false, 'message' => __('label.TERM_COULD_NOT_BE_ACTIVATED')), 401);
            }
        } else {
            $update = TermToCourse::where('id', $request->id)
                    ->update(['status' => $request->status]);
            if ($update) {
                return Response::json(['success' => true, 'message' => $termInfo->name . ' ' . __('label.HAS_BEEN_CLOSED')], 200);
            } else {
                return Response::json(array('success' => false, 'message' => __('label.TERM_COULD_NOT_BE_CLOSED')), 401);
            }
        }
    }

    public function redioAcIn(Request $request) {

        $termInfo = Term::select('term.name')
                        ->join('term_to_course', 'term_to_course.term_id', '=', 'term.id')
                        ->where('term_to_course.course_id', $request->course_id)
                        ->where('term_to_course.term_id', $request->term_id)
                        ->where('term_to_course.id', $request->id)
                        ->where('term.status', '1')->first();
        $checkTermActive = TermToCourse::where('term_id', $request->term_id)
                        ->where('course_id', $request->course_id)
                        ->where('id', $request->id)
                        ->where('active', '1')->first();

        if (!empty($checkTermActive)) {
            return Response::json(['success' => true, 'message' => $termInfo->name . ' ' . __('label.IS_ALREADY_ACTIVE')], 200);
        }

        $update = TermToCourse::where('term_id', $request->term_id)
                ->where('course_id', $request->course_id)
                ->where('id', $request->id)
                ->update(['active' => '1']);

        $update1 = TermToCourse::where('course_id', $request->course_id)
                ->where('id', '!=', $request->id)
                ->update(['active' => '0']);

        if ($update) {
            return Response::json(['success' => true, 'message' => $termInfo->name . ' ' . __('label.HAS_BEEN_ACTIVATED')], 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.TERM_COULD_NOT_BE_ACTIVATED')), 401);
        }
    }
}
