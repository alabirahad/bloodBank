<?php

namespace App\Http\Controllers;

use App\EventToSubEvent;
use App\EventToSubSubEvent;
use App\EventToSubSubSubEvent;
use App\Event;
use App\SubEvent;
use App\SubSubEvent;
//use App\Marking;
use Auth;
use DB;
use Illuminate\Http\Request;
use Response;
use Validator;

class EventToSubSubEventController extends Controller {

    public function index(Request $request) {

        $eventList = ['0' => __('label.SELECT_EVENT_OPT')] + Event::where('status', '1')
                        ->where('has_sub_event', '1')
                        ->orderBy('order', 'asc')
                        ->pluck('event_code', 'id')->toArray();

        $subEventList = ['0' => __('label.SELECT_SUB_EVENT_OPT')];

        return view('eventToSubSubEvent.index')->with(compact('eventList', 'subEventList'));
    }

    public function getSubEvent(Request $request) {

        $subEventList = ['0' => __('label.SELECT_SUB_EVENT_OPT')] + EventToSubEvent::join('sub_event', 'sub_event.id', '=', 'event_to_sub_event.sub_event_id')
                        ->where('event_to_sub_event.event_id', $request->event_id)
                        ->where('event_to_sub_event.has_sub_sub_event', '1')
                        ->orderBy('sub_event.order', 'asc')
                        ->pluck('sub_event.event_code', 'sub_event.id')->toArray();

        $html = view('eventToSubSubEvent.showSubEvent', compact('subEventList'))->render();

        return response()->json(['html' => $html]);
    }

    public function getSubSubEvent(Request $request) {

        //get event data 
        $targetArr = SubSubEvent::select('sub_sub_event.id', 'sub_sub_event.event_code')
                ->where('sub_sub_event.status', '1')
                ->orderBy('sub_sub_event.order', 'asc')
                ->get();

        $prevEventToSubSubEventList = EventToSubSubEvent::where('event_id', $request->event_id)
                ->where('sub_event_id', $request->sub_event_id)
                ->pluck('sub_event_id', 'sub_sub_event_id')
                ->toArray();

        $prevDataArr = EventToSubSubEvent::where('event_id', $request->event_id)
                ->where('sub_event_id', $request->sub_event_id)
                ->get();

        $eventList = Event::where('status', '1')
                        ->where('has_sub_event', '1')
                        ->orderBy('order', 'asc')
                        ->pluck('event_code', 'id')->toArray();


        $checkHasSubSubSubEvent = $checkHasDsAssesment = [];
        $i = 0;
        if (!empty($prevDataArr)) {
            foreach ($prevDataArr as $item) {
                if ($item->has_sub_sub_sub_event == 1) {
                    $checkHasSubSubSubEvent[$i] = $item->sub_sub_event_id;
                    $i++;
                }
                if ($item->has_ds_assesment == 1) {
                    $checkHasDsAssesment[$i] = $item->sub_sub_event_id;
                    $i++;
                }
            }
        }
        
        $prevDsAssesment = Event::where('id', $request->event_id)
                ->select('has_ds_assesment')
                ->first();
        
        $prevDsAssesment1 = EventToSubEvent::where('event_id', $request->event_id)
                ->where('sub_event_id', $request->sub_event_id)
                ->select('has_ds_assesment')
                ->first();
//        echo '<pre>';        print_r($prevDsAssesment->toArray());exit;
        $markingCheck = $termToParticular = [];

        $html = view('eventToSubSubEvent.getSubSubEvent', compact('targetArr', 'prevDataArr', 'eventList'
                        , 'markingCheck', 'termToParticular', 'prevEventToSubSubEventList', 'checkHasDsAssesment'
                        , 'request', 'checkHasSubSubSubEvent', 'prevDsAssesment','prevDsAssesment1'))->render();
        return response()->json(['html' => $html]);
    }

    public function saveEventToSubSubEvent(Request $request) {
        $subSubEventArr = $request->sub_sub_event_id;
        $hasSubSubSubEventArr = $request->has_sub_sub_sub_event;
        $hasDsAssesmentArr = $request->has_ds_assesment;

        if (empty($subSubEventArr)) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => __('label.PLEASE_RELATE_EVENT_TO_ATLEAST_ONE_SUB_SUB_EVENT')), 401);
        }
        $rules = [
            'sub_sub_event_id' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json(['success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()], 400);
        }

        $data = [];
        if (!empty($subSubEventArr)) {
            foreach ($subSubEventArr as $key => $subSubEventId) {
                $data[$key]['event_id'] = $request->event_id;
                $data[$key]['sub_event_id'] = $request->sub_event_id;
                $data[$key]['sub_sub_event_id'] = $subSubEventId;
                $data[$key]['has_sub_sub_sub_event'] = !empty($hasSubSubSubEventArr[$subSubEventId]) ? $hasSubSubSubEventArr[$subSubEventId] : '0';
                $data[$key]['has_ds_assesment'] = !empty($hasDsAssesmentArr[$subSubEventId]) ? $hasDsAssesmentArr[$subSubEventId] : '0';
                $data[$key]['updated_at'] = date('Y-m-d H:i:s');
                $data[$key]['updated_by'] = Auth::user()->id;

                if (!empty($hasDsAssesmentArr[$subSubEventId])) {
                    EventToSubSubSubEvent::where('event_id', $request->event_id)
                            ->where('sub_event_id', $request->sub_event_id)
                            ->where('sub_sub_event_id', $subSubEventId)
                            ->update(['has_ds_assesment' => '0']);
                }
            }
        }
        EventToSubSubEvent::where('event_id', $request->event_id)
                ->where('sub_event_id', $request->sub_event_id)
                ->delete();

        if (EventToSubSubEvent::insert($data)) {
            return Response::json(['success' => true], 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.EVENT_TO_SUB_SUB_EVENT_COULD_NOT_BE_ASSIGNED')), 401);
        }
    }

    public function getAssignedSubSubEvent(Request $request) {

        $eventName = Event::select('event_code')
                ->where('id', $request->event_id)
                ->first();

        $subEventName = SubEvent::select('event_code')
                ->where('id', $request->sub_event_id)
                ->first();

        $assignedSubSubEventArr = EventToSubSubEvent::join('sub_sub_event', 'sub_sub_event.id', '=', 'event_to_sub_sub_event.sub_sub_event_id')
                ->select('sub_sub_event.id', 'sub_sub_event.event_code', 'event_to_sub_sub_event.has_sub_sub_sub_event'
                        , 'event_to_sub_sub_event.has_ds_assesment')
                ->where('event_to_sub_sub_event.event_id', $request->event_id)
                ->where('event_to_sub_sub_event.sub_event_id', $request->sub_event_id)
                ->orderBy('sub_sub_event.order', 'asc')
                ->get();

        $prevDsAssesment = EventToSubEvent::where('event_id', $request->event_id)
                ->where('sub_event_id', $request->sub_event_id)
                ->select('has_ds_assesment')
                ->first();

        $view = view('eventToSubSubEvent.showAssignedSubSubEvent', compact('assignedSubSubEventArr', 'eventName'
                        , 'subEventName', 'prevDsAssesment'))->render();
        return response()->json(['html' => $view]);
    }

}
