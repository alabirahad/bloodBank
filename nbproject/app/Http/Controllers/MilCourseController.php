<?php

namespace App\Http\Controllers;

use App;
use App\MilCourse;
use App\TrainingYear;
use App\Wing;
use Illuminate\Http\Request;
use Redirect;
use Response;
use Session;
use Validator;
use View;

class MilCourseController extends Controller {

    public function index(Request $request) {

        //passing param for custom function
        $qpArr = $request->all();
        $nameArr = MilCourse::select('name')->orderBy('name', 'asc')->get();
        
        $targetArr = MilCourse::select('id', 'name', 'short_info', 'status')
                ->orderBy('id', 'asc');

        //begin filtering
        $searchText = $request->fil_search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%');
            });
        }
        //end filtering
        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/milCourse?page=' . $page);
        }

        return view('milCourse.index')->with(compact('targetArr', 'qpArr', 'nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        return view('milCourse.create')->with(compact('qpArr'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? $qpArr['page'] : '';
        //end back same page after update
        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:mil_course',
                        ]);

        if ($validator->fails()) {
            return redirect('milCourse/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new MilCourse;
        $target->name = $request->name;
        $target->short_info = $request->short_info;
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.MIL_COURSE_CREATED_SUCCESSFULLY'));
            return redirect('milCourse');
        } else {
            Session::flash('error', __('label.MIL_COURSE_COULD_NOT_BE_CREATED'));
            return redirect('milCourse/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = MilCourse::find($id);

        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('milCourse');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('milCourse.edit')->with(compact('target', 'qpArr'));
    }

    public function update(Request $request, $id) {
        $target = MilCourse::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //end back same page after update
         $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:mil_course,name,' . $id,
                        ]);

        if ($validator->fails()) {
            return redirect('milCourse/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->short_info = $request->short_info;
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.MIL_COURSE_UPDATED_SUCCESSFULLY'));
            return redirect('milCourse' . $pageNumber);
        } else {
            Session::flash('error', __('label.MIL_COURSE_COULD_NOT_BE_UPDATED'));
            return redirect('milCourse/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = MilCourse::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? '?page='.$qpArr['page'] : '';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //Check Dependency before deletion
        $dependencyArr = ['CenterToBatch' => 'batch_id', 'CiObservationMarkingLock' => 'batch_id', 'CiToBatch' => 'batch_id'
            , 'MilCourseReport' => 'batch_id', 'EventMarkingLock' => 'batch_id','EventWtDistr' => 'batch_id', 'Marking' => 'batch_id'
            ,'ModuleWtDistr' => 'event_id', 'ObservationMarking' => 'batch_id', 'ObservationMarkingLock' => 'batch_id'
            ,'ObservationWtDistr' => 'batch_id','OicObservationMarkingLock' => 'batch_id', 'OicToBatch' => 'batch_id'
            , 'ParticularMarkingLock' => 'batch_id', 'ParticularWtDistr' => 'batch_id'
            , 'PlatoonToBatch' => 'batch_id', 'PlCmdrToPlatoon' => 'batch_id', 'RctState' => 'batch_id'
            , 'Recruit' => 'batch_id', 'RecruitToPlatoon' => 'batch_id', 'RecruitToTrade' => 'batch_id'
            , 'ModuleWtDistr' => 'batch_id', 'TermToBatch' => 'batch_id', 'TermToEvent' => 'batch_id'
            , 'TermToParticular' => 'batch_id'];


//        foreach ($dependencyArr as $model => $key) {
//            $namespacedModel = '\\App\\' . $model;
//            $dependentData = $namespacedModel::where($key, $id)->first();
//            if (!empty($dependentData)) {
//                Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model);
//                return redirect('milCourse' . $pageNumber);
//            }
//        }

        if ($target->delete()) {
            Session::flash('error', __('label.MIL_COURSE_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.MIL_COURSE_COULD_NOT_BE_DELETED'));
        }
        return redirect('milCourse' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'fil_search=' . $request->fil_search;
        return Redirect::to('milCourse?' . $url);
    }

    public function close(Request $request) {

        $target = MilCourse::find($request->id);
        if (empty($target)) {
            return Response::json(array('success' => false, 'message' => __('label.INVALID_DATA_ID')), 401);
        }
        $target->status = '2';

        if ($target->save()) {
            return Response::json(['success' => true, 'message' => $target->name . ' ' . __('label.HAS_BEEN_CLOSED')], 200);
        } else {
            return Response::json(array('success' => false, 'message' => __('label.BATCH_COULD_NOT_BE_CLOSED')), 401);
        }
    }

}
