<?php

namespace App\Http\Controllers;

use App\Course;
use App\SynToCourse;
use App\DsToSyn;
use App\TermToCourse;
use App\TrainingYear;
use App\User;
use App\RecruitToSyn;
use App\EventCriteria;
use App\CriteriaWiseWt;
use App\Event;
use App\EventMksWt;
use App\TermToEvent;
use App\TermToSubEvent;
use App\SubEventMksWt;
use Auth;
use DB;
use Validator;
use Illuminate\Http\Request;
use Response;

class SubEventMksWtController extends Controller {

    public function index(Request $request) {

        //get only active training year
        $activeTrainingYearInfo = TrainingYear::where('status', '1')->first();

        if (empty($activeTrainingYearInfo)) {
            $void['header'] = __('label.SUB_EVENT_MKS_WT_DISTRIBUTION');
            $void['body'] = __('label.THERE_IS_NO_ACTIVE_TRAINING_YEAR');
            return view('layouts.void', compact('void'));
        }

        $courseList = ['0' => __('label.SELECT_COURSE_OPT')] + Course::where('training_year_id', $activeTrainingYearInfo->id)
                        ->where('status', '1')->orderBy('id', 'desc')->pluck('name', 'id')->toArray();

        return view('subEventMksWt.index')->with(compact('activeTrainingYearInfo', 'courseList'));
    }

    public function getEvent(Request $request) {

        $eventList = ['0' => __('label.SELECT_EVENT_OPT')] + TermToEvent::join('event', 'event.id', '=', 'term_to_event.event_id')
                        ->where('term_to_event.course_id', $request->course_id)
                        ->where('event.status', '1')
                        ->orderBy('event.order', 'asc')
                        ->pluck('event.event_code', 'event.id')
                        ->toArray();
        $html = view('subEventMksWt.getEvent', compact('eventList'))->render();
        return response()->json(['html' => $html]);
    }

    public function getSubEventMksWt(Request $request) {
        
        $assignedMksWtArr = EventMksWt::where('course_id', $request->course_id)
                ->where('event_id', $request->event_id)
                ->select('mks_limit', 'highest_mks_limit', 'lowest_mks_limit', 'wt')
                ->first();

        // get sub event
        $subEventArr = TermToSubEvent::join('sub_event', 'sub_event.id', '=', 'term_to_sub_event.sub_event_id')
                ->where('term_to_sub_event.course_id', $request->course_id)
                ->where('term_to_sub_event.event_id', $request->event_id)
                ->where('sub_event.status', '1')
                ->orderBy('sub_event.order', 'asc')
                ->pluck('sub_event.event_code', 'sub_event.id')
                ->toArray();


        // get previous data
        $subEventMksWtDataArr = SubEventMksWt::where('course_id', $request->course_id)
                ->where('event_id', $request->event_id)
                ->select('mks_limit', 'highest_mks_limit', 'lowest_mks_limit', 'sub_event_id', 'wt')
                ->get();
        $subEventMksWtArr = [];
        $total = 0;
        if (!$subEventMksWtDataArr->isEmpty()) {
            foreach ($subEventMksWtDataArr as $subEventData) {
                $subEventMksWtArr[$subEventData->sub_event_id] = $subEventData->toArray();
                $total += $subEventData->wt;
            }
        } else {
            $subEventMksWtArr['mks_limit'] = $assignedMksWtArr->mks_limit ?? null;
            $subEventMksWtArr['highest_mks_limit'] = $assignedMksWtArr->highest_mks_limit ?? null;
            $subEventMksWtArr['lowest_mks_limit'] = $assignedMksWtArr->lowest_mks_limit ?? null;
        }
        



        $html = view('subEventMksWt.showSubEventMksWt', compact('subEventMksWtArr', 'assignedMksWtArr'
                        , 'subEventArr', 'total'))->render();
        return response()->json(['html' => $html]);
    }

    public function saveSubEventMksWt(Request $request) {

        $totalEventWt = $request->total_event_wt;
        // Validation
        $rules = $message = $errors = [];
        if ($request->total_event_wt != $request->total_wt) {
            $errors[] = __('label.THE_TOTAL_WT_MUST_BE_EQUAL_TO', ['total_event_wt' => $totalEventWt]);
        }

        $row = 1;
        if (!empty($request->event_mks_wt)) {
            foreach ($request->event_mks_wt as $eventId => $eInfo) {
                $rules['event_mks_wt.' . $eventId . '.mks'] = 'required';
                $rules['event_mks_wt.' . $eventId . '.highest'] = 'required';
                $rules['event_mks_wt.' . $eventId . '.lowest'] = 'required';
                $rules['event_mks_wt.' . $eventId . '.wt'] = 'required';
                $message['event_mks_wt.' . $eventId . '.mks' . '.required'] = __('label.MKS_IS_REQUIRED_FOR_SER', ['row' => $row]);
                $message['event_mks_wt.' . $eventId . '.highest' . '.required'] = __('label.HIGHEST_MKS_IS_REQUIRED_FOR_SER', ['row' => $row]);
                $message['event_mks_wt.' . $eventId . '.lowest' . '.required'] = __('label.LOWEST_MKS_IS_REQUIRED_FOR_SER', ['row' => $row]);
                $message['event_mks_wt.' . $eventId . '.wt' . '.required'] = __('label.WT_IS_REQUIRED_FOR_SER', ['row' => $row]);
                $row++;
            }
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json(['success' => false, 'heading' => 'Validation Error', 'message' => $validator->errors()], 400);
        }

        if (!empty($errors)) {
            return Response::json(array('success' => false, 'message' => $errors), 400);
        }
        // End validation
        // Delete previous record for this course_id
        SubEventMksWt::where('course_id', $request->course_id)->where('event_id', $request->event_id)->delete();

        $i = 0;
        if (!empty($request->event_mks_wt)) {
            foreach ($request->event_mks_wt as $subEventId => $mksWtInfo) {
                $data[$i]['course_id'] = $request->course_id;
                $data[$i]['event_id'] = $request->event_id;
                $data[$i]['sub_event_id'] = $subEventId;
                $data[$i]['mks_limit'] = $mksWtInfo['mks'];
                $data[$i]['highest_mks_limit'] = $mksWtInfo['highest'];
                $data[$i]['lowest_mks_limit'] = $mksWtInfo['lowest'];
                $data[$i]['wt'] = !empty($mksWtInfo['wt']) ? $mksWtInfo['wt'] : 0.00;
                $data[$i]['updated_at'] = date('Y-m-d H:i:s');
                $data[$i]['updated_by'] = Auth::user()->id;
                $i++;
            }
        }
//echo '<pre>';
//                print_r($request->course_id);
//        exit;
        if (SubEventMksWt::insert($data)) {
            return Response::json(['success' => true], 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.WT_COULD_NOT_BE_DISTRIBUTED')), 401);
        }
    }

}
