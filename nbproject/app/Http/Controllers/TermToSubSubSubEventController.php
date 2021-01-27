<?php

namespace App\Http\Controllers;

use App\Course;
use App\TermToCourse;
use App\TermToEvent;
use App\TermToSubEvent;
use App\TermToSubSubEvent;
use App\TermToSubSubSubEvent;
use App\TrainingYear;
use App\Event;
use App\SubEvent;
use App\SubSubEvent;
use App\EventToSubEvent;
use App\EventToSubSubEvent;
use App\EventToSubSubSubEvent;
use App\Term;
//use App\Marking;
use Auth;
use DB;
use Illuminate\Http\Request;
use Response;
use Validator;

class TermToSubSubSubEventController extends Controller {

    public function index(Request $request) {

        //get only active training year
        $activeTrainingYearInfo = TrainingYear::where('status', '1')->first();

        if (empty($activeTrainingYearInfo)) {
            $void['header'] = __('label.RELATE_TERM_TO_SUB_SUB_SUB_EVENT');
            $void['body'] = __('label.THERE_IS_NO_ACTIVE_TRAINING_YEAR');
            return view('layouts.void', compact('void'));
        }

        $courseList = ['0' => __('label.SELECT_COURSE_OPT')] + Course::where('training_year_id', $activeTrainingYearInfo->id)
                        ->where('status', '1')->orderBy('id', 'desc')->pluck('name', 'id')->toArray();

        $termList = ['0' => __('label.SELECT_TERM_OPT')];
        $eventList = ['0' => __('label.SELECT_EVENT_OPT')];
        $subEventList = ['0' => __('label.SELECT_SUB_EVENT_OPT')];
        $subSubEventList = ['0' => __('label.SELECT_SUB_SUB_EVENT_OPT')];

        return view('termToSubSubSubEvent.index')->with(compact('activeTrainingYearInfo', 'courseList', 'termList'
                                , 'eventList', 'subEventList', 'subSubEventList'));
    }

    public function getTerm(Request $request) {

        $termList = ['0' => __('label.SELECT_TERM_OPT')] + TermToCourse::join('term', 'term.id', '=', 'term_to_course.term_id')
                        ->where('term_to_course.course_id', $request->course_id)
                        ->orderBy('term.order', 'asc')
                        ->where('term.status', '1')->pluck('term.name', 'term.id')->toArray();

        $html = view('termToSubSubSubEvent.showTerm', compact('termList'))->render();

        return response()->json(['html' => $html]);
    }

    public function getEvent(Request $request) {

        $eventList = ['0' => __('label.SELECT_EVENT_OPT')] + TermToEvent::join('event', 'event.id', '=', 'term_to_event.event_id')
                        ->where('term_to_event.course_id', $request->course_id)
                        ->where('term_to_event.term_id', $request->term_id)
                        ->where('event.has_sub_event', '1')
                        ->orderBy('event.order', 'asc')
                        ->pluck('event.event_code', 'event.id')->toArray();

        $html = view('termToSubSubSubEvent.showEvent', compact('eventList'))->render();

        return response()->json(['html' => $html]);
    }

    public function getSubEvent(Request $request) {

        $subEventList = ['0' => __('label.SELECT_SUB_EVENT_OPT')] + TermToSubEvent::join('sub_event', 'sub_event.id', '=', 'term_to_sub_event.sub_event_id')
                        ->join('event_to_sub_event', 'event_to_sub_event.sub_event_id', '=', 'term_to_sub_event.sub_event_id')
                        ->where('term_to_sub_event.course_id', $request->course_id)
                        ->where('term_to_sub_event.term_id', $request->term_id)
                        ->where('term_to_sub_event.event_id', $request->event_id)
                        ->where('event_to_sub_event.has_sub_sub_event', '1')
                        ->orderBy('sub_event.order', 'asc')
                        ->pluck('sub_event.event_code', 'sub_event.id')->toArray();

        $html = view('termToSubSubSubEvent.showSubEvent', compact('subEventList'))->render();

        return response()->json(['html' => $html]);
    }

    public function getSubSubEvent(Request $request) {

        $subSubEventList = ['0' => __('label.SELECT_SUB_SUB_EVENT_OPT')] + TermToSubSubEvent::join('sub_sub_event', 'sub_sub_event.id', '=', 'term_to_sub_sub_event.sub_sub_event_id')
                        ->join('event_to_sub_sub_event', 'event_to_sub_sub_event.sub_sub_event_id', '=', 'term_to_sub_sub_event.sub_sub_event_id')
                        ->where('event_to_sub_sub_event.has_sub_sub_sub_event', '1')
                        ->where('term_to_sub_sub_event.course_id', $request->course_id)
                        ->where('term_to_sub_sub_event.term_id', $request->term_id)
                        ->where('term_to_sub_sub_event.event_id', $request->event_id)
                        ->where('term_to_sub_sub_event.sub_event_id', $request->sub_event_id)
                        ->orderBy('sub_sub_event.order', 'asc')
                        ->pluck('sub_sub_event.event_code', 'sub_sub_event.id')->toArray();

//        echo '<pre>';        print_r($subSubEventList); exit;
        $html = view('termToSubSubSubEvent.showSubSubEvent', compact('subSubEventList'))->render();

        return response()->json(['html' => $html]);
    }

    public function getSubSubSubEvent(Request $request) {

        //get event data 
        $targetArr = EventToSubSubSubEvent::join('sub_sub_sub_event', 'sub_sub_sub_event.id', '=', 'event_to_sub_sub_sub_event.sub_sub_sub_event_id')
                ->where('event_to_sub_sub_sub_event.event_id', $request->event_id)
                ->where('event_to_sub_sub_sub_event.sub_event_id', $request->sub_event_id)
                ->where('event_to_sub_sub_sub_event.sub_sub_event_id', $request->sub_sub_event_id)
                ->select('sub_sub_sub_event.id', 'sub_sub_sub_event.event_code')
                ->orderBy('sub_sub_sub_event.order', 'asc')
                ->get();

        $prevTermToSubSubSubEventList = TermToSubSubSubEvent::where('course_id', $request->course_id)
                        ->where('term_id', $request->term_id)
                        ->where('event_id', $request->event_id)
                        ->where('sub_event_id', $request->sub_event_id)
                        ->where('sub_sub_event_id', $request->sub_sub_event_id)
                        ->pluck('sub_sub_event_id', 'sub_sub_sub_event_id')->toArray();

        $prevDataArr = TermToSubSubSubEvent::where('course_id', $request->course_id)
                ->where('event_id', $request->event_id)
                ->where('sub_event_id', $request->sub_event_id)
                ->where('sub_sub_event_id', $request->sub_sub_event_id)
                ->get();

        $prevDataList = [];
        if (!empty($prevDataArr)) {
            foreach ($prevDataArr as $item) {
                $prevDataList[$item->sub_sub_sub_event_id][] = $item->term_id;
            }
        }


        $prevSaveTermToSubSubSubEventList = TermToSubSubSubEvent::where('course_id', $request->course_id)
                        ->where('event_id', $request->event_id)
                        ->where('sub_event_id', $request->sub_event_id)
                        ->where('sub_sub_event_id', $request->sub_sub_event_id)
                        ->pluck('term_id', 'sub_sub_sub_event_id')->toArray();


        $termList = Term::pluck('name', 'id')->toArray();

        $html = view('termToSubSubSubEvent.getSubSubSubEvent', compact('targetArr', 'prevDataArr', 'termList', 'prevDataList'
                        , 'prevTermToSubSubSubEventList', 'request', 'prevSaveTermToSubSubSubEventList'))->render();
        return response()->json(['html' => $html]);
    }

    public function saveTermToSubSubSubEvent(Request $request) {
        $subSubSubEventArr = $request->sub_sub_sub_event_id;
        if (empty($subSubSubEventArr)) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => __('label.PLEASE_RELATE_TERM_TO_ATLEAST_ONE_SUB_SUB_SUB_EVENT')), 401);
        }
        $rules = [
            'course_id' => 'required|not_in:0',
            'term_id' => 'required|not_in:0',
            'event_id' => 'required|not_in:0',
            'sub_event_id' => 'required|not_in:0',
            'sub_sub_event_id' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json(['success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()], 400);
        }

        $data = [];
        $i = 0;
        if (!empty($subSubSubEventArr)) {
            foreach ($subSubSubEventArr as $subSubSubEventId => $subSubSubEventInfo) {
                if (!empty($subSubSubEventId)) {
                    $data[$i]['course_id'] = $request->course_id;
                    $data[$i]['term_id'] = $request->term_id;
                    $data[$i]['event_id'] = $request->event_id;
                    $data[$i]['sub_event_id'] = $request->sub_event_id;
                    $data[$i]['sub_sub_event_id'] = $request->sub_sub_event_id;
                    $data[$i]['sub_sub_sub_event_id'] = $subSubSubEventId;
                    $data[$i]['updated_at'] = date('Y-m-d H:i:s');
                    $data[$i]['updated_by'] = Auth::user()->id;
                }
                $i++;
            }
        }

        TermToSubSubSubEvent::where('course_id', $request->course_id)
                ->where('term_id', $request->term_id)
                ->where('event_id', $request->event_id)
                ->where('sub_event_id', $request->sub_event_id)
                ->where('sub_sub_event_id', $request->sub_sub_event_id)
                ->delete();

        if (TermToSubSubSubEvent::insert($data)) {
            return Response::json(['success' => true], 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.TERM_TO_SUB_SUB_SUB_EVENT_COULD_NOT_BE_ASSIGNED')), 401);
        }
    }

    public function getAssignedSubSubSubEvent(Request $request) {

        $courseName = Course::select('name')
                ->where('id', $request->course_id)
                ->first();
        $termName = Term::select('name')
                ->where('id', $request->term_id)
                ->first();

        $eventName = Event::select('event_code')
                ->where('id', $request->event_id)
                ->first();

        $subEventName = SubEvent::select('event_code')
                ->where('id', $request->sub_event_id)
                ->first();

        $subSubEventName = SubSubEvent::select('event_code')
                ->where('id', $request->sub_sub_event_id)
                ->first();

        $assignedSubSubSubEventArr = TermToSubSubSubEvent::join('sub_sub_sub_event', 'sub_sub_sub_event.id', '=', 'term_to_sub_sub_sub_event.sub_sub_sub_event_id')
                ->select('sub_sub_sub_event.id', 'sub_sub_sub_event.event_code')
                ->where('term_to_sub_sub_sub_event.course_id', $request->course_id)
                ->where('term_to_sub_sub_sub_event.term_id', $request->term_id)
                ->where('term_to_sub_sub_sub_event.event_id', $request->event_id)
                ->where('term_to_sub_sub_sub_event.sub_event_id', $request->sub_event_id)
                ->where('term_to_sub_sub_sub_event.sub_sub_event_id', $request->sub_sub_event_id)
                ->orderBy('sub_sub_sub_event.order', 'asc')
                ->get();
//        echo '<pre>';        print_r($assignedSubEventArr->toArray());  exit;

        $view = view('termToSubSubSubEvent.showAssignedSubSubSubEvent', compact('assignedSubSubSubEventArr', 'termName'
                        , 'courseName', 'eventName', 'subEventName', 'subSubEventName'))->render();
        return response()->json(['html' => $view]);
    }

}
