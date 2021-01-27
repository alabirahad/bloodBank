<?php

namespace App\Http\Controllers;

use Validator;
use App\BloodGroup;
use App\RequestBlood;
use App\Division;
use Session;
use Redirect;
use Helper;
use Response;
use Auth;
use DB;
use Common;
use Illuminate\Http\Request;

class RequestBloodController extends Controller {

    public function index(Request $request) {
        
        $bloodGroupList = array('0' => __('label.SELECT_BLOOD_GROUP_OPT')) + BloodGroup::orderBy('order', 'asc')
                        ->pluck('name', 'id')->toArray();
        $divisionList = ['0' => __('label.SELECT_DIVISION_OPT')] + Division::pluck('name', 'id')->toArray();
        
        return view('requestBlood.index')->with(compact('bloodGroupList', 'divisionList'));
    }
    
    public function requestSave(Request $request) {
        $requestArr = $request->cm_id;
        
        $rules = [
            'blood_group_id' => 'required|not_in:0',
            'division_id' => 'required|not_in:0',
            'quantity' => 'required',
            'date' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json(['success' => false, 'heading' => __('label.VALIDATION_ERROR'), 'message' => $validator->errors()], 400);
        }

        $target = new RequestBlood;
        $target->blood_group_id = $request->blood_group_id;
        $target->quantity = $request->quantity;
        $target->division_id = $request->division_id;
        $target->date = Helper::dateFormatConvert($request->date);
        $target->updated_by = Auth::user()->id;
        $target->updated_at = date('Y-m-d H:i:s');


        if ($target->save()) {
            return Response::json(['success' => true], 200);
        } else {
            return Response::json(array('success' => false, 'message' => 'Could not sent request'), 401);
        }
    }
}
