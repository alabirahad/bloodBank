<?php

namespace App\Http\Controllers;

use Validator;
use App\Event;
use App\EventToSubEvent;
use App\EventToSubSubEvent;
use App\EventToSubSubSubEvent;
use Session;
use Redirect;
use Helper;
use PDF;
use Auth;
use Illuminate\Http\Request;

class EventController extends Controller {

    private $controller = 'Event';

    public function __construct() {
        
    }

    public function index(Request $request) {

        $nameArr = Event::select('event_code')->orderBy('order', 'asc')->get();

        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = Event::select('event.id', 'event.event_code', 'event.event_detail', 'event.has_sub_event'
                        , 'event.has_ds_assesment', 'event.order', 'event.status')
                ->orderBy('event.order', 'asc');

        //begin filtering
        $searchText = $request->fil_search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('event.event_code', 'LIKE', '%' . $searchText . '%');
            });
        }

        //end filtering

        if ($request->download == 'pdf') {
            $targetArr = $targetArr->get();
        } else {
            $targetArr = $targetArr->paginate(Session::get('paginatorCount'));
        }


        //change page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/event?page=' . $page);
        }

        if ($request->download == 'pdf') {
            $pdf = PDF::loadView('event.printEvent', compact('targetArr', 'qpArr', 'nameArr'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('eventList.pdf');
        } else {
            return view('event.index')->with(compact('targetArr', 'qpArr', 'nameArr'));
        }
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        $lastOrderNumber = Helper::getLastOrder($this->controller, 1);

        return view('event.create')->with(compact('qpArr', 'orderList', 'lastOrderNumber'));
    }

    public function store(Request $request) {
//        echo '<pre>';        print_r($request->all());exit();
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'event_code' => 'required|unique:event',
                    'event_detail' => 'required',
                    'order' => 'required|not_in:0'
        ]);


        if ($validator->fails()) {
            return redirect('event/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }


        $target = new Event;
        $target->event_code = $request->event_code;
        $target->event_detail = $request->event_detail;
        if (!empty($request->has_sub_event)) {
            $target->has_sub_event = $request->has_sub_event ?? '0';
        }
        if (!empty($request->has_ds_assesment)) {
            $target->has_ds_assesment = $request->has_ds_assesment ?? '0';
        }
        $target->order = 0;
        $target->status = $request->status;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.EVENT_CREATED_SUCCESSFULLY'));
            return redirect('event');
        } else {
            Session::flash('error', __('label.EVENT_COULD_NOT_BE_CREATED'));
            return redirect('event/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = Event::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);

        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('event');
        }

        //passing param for custom function
        $qpArr = $request->all();

        return view('event.edit')->with(compact('target', 'qpArr', 'orderList'));
    }

    public function update(Request $request, $id) {

//        echo '<pre>';        print_r($request->all()); exit;
        $target = Event::find($id);
        $presentOrder = $target->order;
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //end back same page after update


        $validator = Validator::make($request->all(), [
                    'event_code' => 'required|unique:event,event_code,' . $id,
                    'event_detail' => 'required',
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('event/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->event_code = $request->event_code;
        $target->event_detail = $request->event_detail;
        if (!empty($request->has_sub_event)) {
            $target->has_sub_event = $request->has_sub_event;
        } else {
            $target->has_sub_event = '0';
        }
        if (!empty($request->has_ds_assesment)) {
            $target->has_ds_assesment = $request->has_ds_assesment;
        } else {
            $target->has_ds_assesment = '0';
        }
        $target->order = $request->order;
        $target->status = $request->status;

        if ($target->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }
            if (!empty($request->has_ds_assesment)) {
                EventToSubEvent::where('event_id', $id)
                        ->update(['has_ds_assesment' => '0']);

                EventToSubSubEvent::where('event_id', $id)
                        ->update(['has_ds_assesment' => '0']);

                EventToSubSubSubEvent::where('event_id', $id)
                        ->update(['has_ds_assesment' => '0']);
            }
            Session::flash('success', trans('label.EVENT_UPDATED_SUCCESSFULLY'));
            return redirect('/event' . $pageNumber);
        } else {
            Session::flash('error', trans('label.EVENT_CUOLD_NOT_BE_UPDATED'));
            return redirect('event/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = Event::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? $qpArr['page'] : '';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

//Check Dependency before deletion
        $dependencyArr = [
            'SynToCourse' => 'syn_id',
            'EventToSubEvent' => 'event_id',
//            , 'EventMarkingLock' => 'syndicate_id', 'Marking' => 'syndicate_id'
//            , 'ParticularMarkingLock' => 'syndicate_id', 'SyndicateToBatch' => 'syndicate_id', 'PlCmdrToSyndicate' => 'syndicate_id'
//            , 'RctState' => 'syndicate_id', 'RecruitToSyndicate' => 'syndicate_id'
        ];

        foreach ($dependencyArr as $model => $key) {
            $namespacedModel = '\\App\\' . $model;
            $dependentData = $namespacedModel::where($key, $id)->first();
            if (!empty($dependentData)) {
                Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model);
                return redirect('syndicate' . $pageNumber);
            }
        }

        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.EVENT_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.EVENT_COULD_NOT_BE_DELETED'));
        }
        return redirect('event' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'fil_search=' . $request->fil_search;
        return Redirect::to('event?' . $url);
    }

}
