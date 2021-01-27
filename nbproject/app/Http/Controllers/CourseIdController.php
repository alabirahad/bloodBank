<?php

namespace App\Http\Controllers;

use App;
use App\Course;
use App\TrainingYear;
use Illuminate\Http\Request;
use Redirect;
use Response;
use Session;
use Validator;
use Helper;
use View;

class CourseIdController extends Controller {

    public function index(Request $request) {

        //passing param for custom function
        $qpArr = $request->all();
        $nameArr = Course::select('name')->orderBy('name', 'asc')->get();

        $targetArr = Course::join('training_year', 'training_year.id', '=', 'course.training_year_id')
                ->select('course.id', 'course.name', 'course.initial_date', 'course.termination_date'
                        , 'course.no_of_weeks', 'course.short_info', 'course.status', 'course.total_course_wt'
                        , 'course.event_mks_limit', 'course.highest_mks_limit', 'course.lowest_mks_limit'
                        , 'training_year.name as tranining_year_name')
                ->orderBy('training_year.id', 'desc')
                ->orderBy('course.id', 'desc');

        //begin filtering
        $searchText = $request->fil_search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('course.name', 'LIKE', '%' . $searchText . '%');
            });
        }
        //end filtering
        $targetArr = $targetArr->paginate(Session::get('paginatorCount'));

        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/courseId?page=' . $page);
        }

        return view('courseId.index')->with(compact('targetArr', 'qpArr', 'nameArr'));
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $trainingYearList = TrainingYear::select('id', 'name')->where('status', '1')->first();
        if (empty($trainingYearList)) {
            $void['header'] = __('label.CREATE_COURSE');
            $void['body'] = __('label.THERE_IS_NO_ACTIVE_TRAINING_YEAR');
            return view('layouts.void', compact('void'));
        }
        return view('courseId.create')->with(compact('qpArr', 'trainingYearList'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? $qpArr['page'] : '';
        //end back same page after update
        $messages = array(
            
        );
        $validator = Validator::make($request->all(), [
                    'training_year_id' => 'required|not_in:0',
                    'name' => 'required|unique:course',
                    'initial_date' => 'required|date',
                    'termination_date' => 'required|date|after_or_equal:initial_date',
                    'no_of_weeks' => 'required',
                    'total_course_wt' => 'required',
                    'event_mks_limit' => 'required',
                    'highest_mks_limit' => 'required|lt:event_mks_limit',
                    'lowest_mks_limit' => 'required|lt:highest_mks_limit',
                        ], $messages);

        if ($validator->fails()) {
            return redirect('courseId/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new Course;
        $target->training_year_id = $request->training_year_id;
        $target->name = $request->name;
        $target->initial_date = Helper::dateFormatConvert($request->initial_date);
        $target->termination_date = Helper::dateFormatConvert($request->termination_date);
        $target->no_of_weeks = $request->no_of_weeks;
        $target->short_info = $request->short_info;
        $target->total_course_wt = $request->total_course_wt;
        $target->event_mks_limit = $request->event_mks_limit;
        $target->highest_mks_limit = $request->highest_mks_limit;
        $target->lowest_mks_limit = $request->lowest_mks_limit;
        $target->status = $request->status;

        if ($target->save()) {
            Session::flash('success', __('label.COURSE_ID_CREATED_SUCCESSFULLY'));
            return redirect('courseId');
        } else {
            Session::flash('error', __('label.COURSE_ID_COULD_NOT_BE_CREATED'));
            return redirect('courseId/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Course::find($id);

        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('courseId');
        }

        //passing param for custom function
        $qpArr = $request->all();
        $trainingYearList = TrainingYear::select('id', 'name')->where('id', $target->training_year_id)->first();
        if (empty($trainingYearList)) {
            $void['header'] = __('label.CREATE_COURSE');
            $void['body'] = __('label.THERE_IS_NO_ACTIVE_TRAINING_YEAR');
            return view('layouts.void', compact('void'));
        }
        return view('courseId.edit')->with(compact('target', 'trainingYearList', 'qpArr'));
    }

    public function update(Request $request, $id) {
        $target = Course::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //end back same page after update

        $messages = array(
            'name.required' => 'The name field is required.',
        );
        
        $validator = Validator::make($request->all(), [
                    'training_year_id' => 'required|not_in:0',
                    'name' => 'required|unique:course,id,'.$id,
                    'initial_date' => 'required|date',
                    'termination_date' => 'required|date|after_or_equal:initial_date',
                    'no_of_weeks' => 'required',
                    'total_course_wt' => 'required',
                    'event_mks_limit' => 'required',
                    'highest_mks_limit' => 'required|lt:event_mks_limit',
                    'lowest_mks_limit' => 'required|lt:highest_mks_limit',
                        ], $messages);

        if ($validator->fails()) {
            return redirect('courseId/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }
        $target->training_year_id = $request->training_year_id;
        $target->name = $request->name;
        $target->initial_date = Helper::dateFormatConvert($request->initial_date);
        $target->termination_date = Helper::dateFormatConvert($request->termination_date);
        $target->no_of_weeks = $request->no_of_weeks;
        $target->short_info = $request->short_info;
        $target->total_course_wt = $request->total_course_wt;
        $target->event_mks_limit = $request->event_mks_limit;
        $target->highest_mks_limit = $request->highest_mks_limit;
        $target->lowest_mks_limit = $request->lowest_mks_limit;
        $target->status = $request->status;
        
       

        if ($target->save()) {
            Session::flash('success', __('label.COURSE_ID_UPDATED_SUCCESSFULLY'));
            return redirect('courseId' . $pageNumber);
        } else {
            Session::flash('error', __('label.COURSE_ID_COULD_NOT_BE_UPDATED'));
            return redirect('courseId/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Course::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? $qpArr['page'] : '';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //Check Dependency before deletion
        $dependencyArr = ['CenterToBatch' => 'batch_id', 'CiObservationMarkingLock' => 'batch_id', 'CiToBatch' => 'batch_id'
            , 'CourseReport' => 'batch_id', 'EventMarkingLock' => 'batch_id', 'EventWtDistr' => 'batch_id', 'Marking' => 'batch_id'
            , 'ModuleWtDistr' => 'event_id', 'ObservationMarking' => 'batch_id', 'ObservationMarkingLock' => 'batch_id'
            , 'ObservationWtDistr' => 'batch_id', 'OicObservationMarkingLock' => 'batch_id', 'OicToBatch' => 'batch_id'
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
//                return redirect('course' . $pageNumber);
//            }
//        }

        if ($target->delete()) {
            Session::flash('error', __('label.COURSE_ID_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.COURSE_ID_COULD_NOT_BE_DELETED'));
        }
        return redirect('courseId' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'fil_search=' . $request->fil_search;
        return Redirect::to('courseId?' . $url);
    }

    public function close(Request $request) {

        $target = Course::find($request->id);
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
