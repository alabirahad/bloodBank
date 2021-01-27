<?php

namespace App\Http\Controllers;

use App\Course;
use App\TermToCourse;
use App\TermToMAEvent;
use App\TrainingYear;
use App\MutualAssessmentEvent;
use App\User;
use App\Term;
//use App\Marking;
use Auth;
use DB;
use Illuminate\Http\Request;
use Response;
use Validator;

class TermToMAEventController extends Controller {

    public function index(Request $request) {

        //get only active training year
        $activeTrainingYearInfo = TrainingYear::where('status', '1')->first();

        if (empty($activeTrainingYearInfo)) {
            $void['header'] = __('label.RELATE_TERM_TO_MA_EVENT');
            $void['body'] = __('label.THERE_IS_NO_ACTIVE_TRAINING_YEAR');
            return view('layouts.void', compact('void'));
        }

        $courseList = ['0' => __('label.SELECT_COURSE_OPT')] + Course::where('training_year_id', $activeTrainingYearInfo->id)
//                        ->where('wing_to_course.wing_id', Auth::user()->wing_id)
                        ->where('status', '1')->orderBy('id', 'desc')->pluck('name', 'id')->toArray();

//        $courseList = ['0' => __('label.SELECT_BATCH_OPT')] + CenterToCourse::join('course', 'course.id', '=', 'center_to_course.course_id')
//                        ->where('course.training_year_id', $activeTrainingYearInfo->id)
//                        ->where('center_to_course.center_id', Auth::user()->center_id)
//                        ->where('course.status', '1')
//                        ->orderby('course.id', 'desc')
//                        ->pluck('course.name', 'course.id')->toArray();

        $termList = ['0' => __('label.SELECT_TERM_OPT')];

        return view('termToMAEvent.index')->with(compact('activeTrainingYearInfo', 'courseList', 'termList'));
    }

    public function getTerm(Request $request) {

        $termList = ['0' => __('label.SELECT_TERM_OPT')] + TermToCourse::join('term', 'term.id', '=', 'term_to_course.term_id')
                        ->where('term_to_course.course_id', $request->course_id)
                        ->orderBy('term.order', 'asc')
                        ->where('term.status', '1')->pluck('term.name', 'term.id')->toArray();

        $html = view('termToMAEvent.showTerm', compact('termList'))->render();

        return response()->json(['html' => $html]);
    }

    public function getEvent(Request $request) {

        //get event data 
        $targetArr = MutualAssessmentEvent::select('id', 'name')
                ->where('status', '1')->orderBy('order', 'asc')
                ->get();

        $prevTermToMAEventList = TermToMAEvent::where('course_id', $request->course_id)
                        ->pluck('term_id', 'event_id')->toArray();


        $prevDataArr = TermToMAEvent::where('course_id', $request->course_id)->get();



        $chackPrevDataArr = TermToMAEvent::where('course_id', $request->course_id)
//                ->where('center_id', Auth::user()->center_id)
                ->where('term_id', $request->term_id)
                ->get();

        $termList = Term::pluck('name', 'id')->toArray();
        $prevDataList = [];
        if (!empty($prevDataArr)) {
            foreach ($prevDataArr as $item) {
                $prevDataList[$item->event_id][] = $item->term_id;
            }
        }

        $chackPrevDataList = [];
        if (!empty($chackPrevDataArr)) {
            foreach ($chackPrevDataArr as $item) {
                $chackPrevDataList[$item->event_id] = $item->term_id;
            }
        }
        $markingCheck = $termToParticular = [];
        //dependency check **** if assign marking where Term to Event relationship event disabled
//        $markingCheck = Marking::where('course_id', $request->course_id)
//                        ->where('center_id', Auth::user()->center_id)
//                        ->where('term_id', $request->term_id)
//                        ->pluck('weight', 'event_id')->toArray();
        //event assign term wise term to particular :: dependency check
//        $termToParticular = TermToParticular::where('course_id', $request->course_id)
//                        ->where('center_id', Auth::user()->center_id)
//                        ->where('term_id', $request->term_id)
//                        ->pluck('particular_id', 'event_id')->toArray();
        //ENDOF Dependency

        $html = view('termToMAEvent.getEvent', compact('targetArr', 'termList', 'prevDataArr', 'prevDataList', 'chackPrevDataList'
                        , 'markingCheck', 'termToParticular', 'prevTermToMAEventList', 'request'))->render();
        return response()->json(['html' => $html]);
    }

    public function saveTermToMAEvent(Request $request) {
        $eventArr = $request->event_id;
        if (empty($eventArr)) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => __('label.PLEASE_RELATE_TERM_TO_ATLEAST_ONE_EVENT')), 401);
        }
        $rules = [
            'course_id' => 'required|not_in:0',
            'term_id' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json(['success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()], 400);
        }

        $data = [];
        $i = 0;
        if (!empty($eventArr)) {
            foreach ($eventArr as $eventId => $eventInfo) {
                if (!empty($eventId)) {
                    $data[$i]['course_id'] = $request->course_id;
                    $data[$i]['term_id'] = $request->term_id;
                    $data[$i]['event_id'] = $eventId;
                    $data[$i]['updated_at'] = date('Y-m-d H:i:s');
                    $data[$i]['updated_by'] = Auth::user()->id;
                }
                $i++;
            }
        }

        TermToMAEvent::where('course_id', $request->course_id)
                ->where('term_id', $request->term_id)
                ->delete();

        if (TermToMAEvent::insert($data)) {
            return Response::json(['success' => true], 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.TERM_TO_EVENT_COULD_NOT_BE_ASSIGNED')), 401);
        }
    }

}
