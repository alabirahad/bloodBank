<?php

namespace App\Http\Controllers;

use Validator;
use App\EventGroup;
use App\Service;
use Session;
use Redirect;
use Helper;
use Response;
use App;
use View;
use PDF;
use Auth;
use Input;
use Illuminate\Http\Request;

class EventGroupController extends Controller {

    private $controller = 'EventGroup';

    public function __construct() {

    }

    public function index(Request $request) {
        $nameArr = EventGroup::select('name')->orderBy('order', 'asc')->get();
        
        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = EventGroup::select('event_group.id', 'event_group.name', 'event_group.order', 'event_group.status')
                ->orderBy('event_group.order', 'asc');

        //begin filtering
        $searchText = $request->fil_search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
            $query->where('event_group.name', 'LIKE', '%' . $searchText . '%');
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
            return redirect('/eventGroup?page=' . $page);
        } else {
            return view('eventGroup.index')->with(compact('targetArr', 'qpArr', 'nameArr'));
        }
    }

    public function create(Request $request) {
        //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        $lastOrderNumber = Helper::getLastOrder($this->controller, 1);

        return view('eventGroup.create')->with(compact('qpArr', 'orderList', 'lastOrderNumber'));
    }
    
    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'order' => 'required|not_in:0'
        ]);


        if ($validator->fails()) {
            return redirect('eventGroup/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }


        $target = new EventGroup;
        $target->name = $request->name;
        $target->order = 0;
        $target->status = $request->status;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.EVENT_GROUP_CREATED_SUCCESSFULLY'));
            return redirect('eventGroup');
        } else {
            Session::flash('error', __('label.EVENT_GROUP_COULD_NOT_BE_CREATED'));
            return redirect('eventGroup/create' . $pageNumber);
        }
    }
    
    public function edit(Request $request, $id) {
        $target = EventGroup::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);

        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('eventGroup');
        }

        //passing param for custom function
        $qpArr = $request->all();

        return view('eventGroup.edit')->with(compact('target', 'qpArr', 'orderList'));
    }

    public function update(Request $request, $id) {
        $target = EventGroup::find($id);
        $presentOrder = $target->order;
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //end back same page after update


        $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('eventGroup/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->order = $request->order;
        $target->status = $request->status;

        if ($target->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }
            Session::flash('success', trans('label.EVENT_GROUP_UPDATED_SUCCESSFULLY'));
            return redirect('/eventGroup' . $pageNumber);
        } else {
            Session::flash('error', trans('label.EVENT_GROUP_CUOLD_NOT_BE_UPDATED'));
            return redirect('eventGroup/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = EventGroup::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? $qpArr['page'] : '';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.EVENT_GROUP_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.EVENT_GROUP_COULD_NOT_BE_DELETED'));
        }
        return redirect('eventGroup' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'fil_search=' . $request->fil_search;
        return Redirect::to('eventGroup?' . $url);
    }

}
