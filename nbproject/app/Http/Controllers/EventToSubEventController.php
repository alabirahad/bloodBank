<?php

namespace App\Http\Controllers;

use App\EventToSubEvent;
use App\EventToSubSubEvent;
use App\EventToSubSubSubEvent;
use App\Event;
use App\SubEvent;
//use App\Marking;
use Auth;
use DB;
use Illuminate\Http\Request;
use Response;
use Validator;

class EventToSubEventController extends Controller {

    public function index(Request $request) {

        $eventList = ['0' => __('label.SELECT_EVENT_OPT')] + Event::where('status', '1')
                        ->where('has_sub_event', '1')
                        ->orderBy('order', 'asc')
                        ->pluck('event_code', 'id')->toArray();

        return view('eventToSubEvent.index')->with(compact('eventList'));
    }

    public function getSubEvent(Request $request) {

        //get event data 
        $targetArr = SubEvent::select('sub_event.id', 'sub_event.event_code')
                ->where('status', '1')->orderBy('sub_event.order', 'asc')
                ->get();

        $prevEventToSubEventList = EventToSubEvent::where('event_id', $request->event_id)
                        ->pluck('event_id', 'sub_event_id')->toArray();

        $prevDataArr = EventToSubEvent::where('event_id', $request->event_id)->get();

        $eventList = Event::where('status', '1')
                        ->where('has_sub_event', '1')
                        ->pluck('event_code', 'id')->toArray();

        
        $checkHasSubSubEvent = $checkHasDsAssesment = [];
        $i = 0;
        if (!empty($prevDataArr)) {
            foreach ($prevDataArr as $item) {
                if($item->has_sub_sub_event == 1){
                   $checkHasSubSubEvent[$i] = $item->sub_event_id;
                   $i++;
                }
                if($item->has_ds_assesment == 1){
                   $checkHasDsAssesment[$i] = $item->sub_event_id;
                   $i++;
                }
                
            }
        }
        
        $prevDsAssesment = Event::where('id', $request->event_id)
                ->select('has_ds_assesment')
                ->first();
//        echo '<pre>';        print_r($prevHasSubSubEventList);exit;

        $html = view('eventToSubEvent.getSubEvent', compact('targetArr', 'prevDataArr', 'eventList'
                        , 'prevEventToSubEventList', 'checkHasDsAssesment'
                        , 'request','checkHasSubSubEvent', 'prevDsAssesment'))->render();
        return response()->json(['html' => $html]);
    }

    public function saveEventToSubEvent(Request $request) {
        $subEventArr = $request->sub_event_id;
        $hasSubSubEventArr = $request->has_sub_sub_event;
        $hasDsAssesmentArr = $request->has_ds_assesment;

        if (empty($subEventArr)) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => __('label.PLEASE_RELATE_EVENT_TO_ATLEAST_ONE_SUB_EVENT')), 401);
        }
        $rules = [
            'sub_event_id' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json(['success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()], 400);
        }

        $data = [];
        if (!empty($subEventArr)) {
            foreach ($subEventArr as $key => $subEventId) {
                $data[$key]['event_id'] = $request->event_id;
                $data[$key]['sub_event_id'] = $subEventId;
                $data[$key]['has_sub_sub_event'] = !empty($hasSubSubEventArr[$subEventId]) ? $hasSubSubEventArr[$subEventId] : '0';
                $data[$key]['has_ds_assesment'] = !empty($hasDsAssesmentArr[$subEventId]) ? $hasDsAssesmentArr[$subEventId] : '0';
                $data[$key]['updated_at'] = date('Y-m-d H:i:s');
                $data[$key]['updated_by'] = Auth::user()->id;
                
                if(!empty($hasDsAssesmentArr[$subEventId])) {
                    EventToSubSubEvent::where('event_id', $request->event_id)
                            ->where('sub_event_id', $subEventId)
                            ->update(['has_ds_assesment' => '0']);
                    
                    EventToSubSubSubEvent::where('event_id', $request->event_id)
                            ->where('sub_event_id', $subEventId)
                            ->update(['has_ds_assesment' => '0']);
                }
                
            }
        }
//        echo '<pre>';        print_r($data);exit;
        EventToSubEvent::where('event_id', $request->event_id)
                ->delete();

        if (EventToSubEvent::insert($data)) {
            return Response::json(['success' => true], 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.EVENT_TO_SUB_EVENT_COULD_NOT_BE_ASSIGNED')), 401);
        }
    }
    
    
    public function getAssignedSubEvent(Request $request) {

        $eventName = Event::select('event_code')
                ->where('id', $request->event_id)
                ->first();
        
        $assignedSubEventArr = EventToSubEvent::join('sub_event', 'sub_event.id', '=', 'event_to_sub_event.sub_event_id')
                ->select('sub_event.id', 'sub_event.event_code', 'event_to_sub_event.has_sub_sub_event'
                        , 'event_to_sub_event.has_ds_assesment')
                ->where('event_to_sub_event.event_id', $request->event_id)
                ->orderBy('sub_event.order', 'asc')
                ->get();

        $view = view('eventToSubEvent.showAssignedSubEvent', compact('assignedSubEventArr', 'eventName'))->render();
        return response()->json(['html' => $view]);
    }

}
