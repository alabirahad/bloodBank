<?php

namespace App\Http\Controllers;

use Validator;
use App\SynToCourse;
use App\TrainingYear;
use App\Course;
use App\Syndicate;
use Response;
use Auth;
use Illuminate\Http\Request;

class SynToCourseController extends Controller {

    public function index(Request $request) {
        $activeTrainingYear = TrainingYear::where('status', '1')->first();
        if (empty($activeTrainingYear)) {
            $void['header'] = __('label.RELATE_SYN_TO_COURSE');
            $void['body'] = __('label.THERE_IS_NO_ACTIVE_TRAINING_YEAR');
            return view('layouts.void', compact('void'));
        }

        $courseList = ['0' => __('label.SELECT_COURSE_OPT')] + Course::where('training_year_id', $activeTrainingYear->id)
                        ->orderBy('id', 'desc')->pluck('name', 'id')->toArray();
        return view('synToCourse.index')->with(compact('activeTrainingYear', 'courseList'));
    }

    public function getSyn(Request $request) {

        $targetArr = Syndicate::select('id', 'name')
                        ->where('status', '1')
                        ->orderBy('order', 'asc')->get();
        //checked
        $previousDataArr = SynToCourse::select('syn_id', 'id')
                        ->where('course_id', $request->course_id)
                        ->get()->toArray();
        

        $previousDataList = [];
        if (!empty($previousDataArr)) {
            foreach ($previousDataArr as $previousData) {
                $previousDataList[$previousData['syn_id']] = $previousData['syn_id'];
            }
        }
        //checked
        //Dependency check Disable data
        $disableSyn = [];
        //end
        $html = view('synToCourse.showSyn', compact('targetArr', 'previousDataList', 'disableSyn'))->render();
        return response()->json(['html' => $html]);
    }

    public function saveSyn(Request $request) {

        $synArr = $request->syn_id;

        if (empty($synArr)) {
            return Response::json(array('success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => __('label.PLEASE_RELATE_COURSE_TO_ATLEAST_ONE_SYN')), 401);
        }
        $rules = [
            'syn_id' => 'required|not_in:0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json(['success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()], 400);
        }
        
        
        $data = [];
        if (!empty($request->training_year_id) && !empty($request->course_id)) {
            if (!empty($synArr)) {
                foreach ($synArr as $key => $synId) {
                    $data[$key]['course_id'] = $request->course_id;
                    $data[$key]['syn_id'] = $synId;
                    $data[$key]['updated_by'] = Auth::user()->id;
                    $data[$key]['updated_at'] = date('Y-m-d H:i:s');
                }
            }

            SynToCourse::where('course_id', $request->course_id)
                    ->delete();
        }

        if (SynToCourse::insert($data)) {
            return Response::json(['success' => true], 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.COULD_NOT_SET_SYN')), 401);
        }
    }

}
