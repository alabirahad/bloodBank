<?php

namespace App\Http\Controllers;

use Helper;
use Validator;
use App\ArmsService;
use App\Rank;
use Redirect;
use Session;
use App;
use View;
use PDF;
use Auth;
use Illuminate\Http\Request;

class ArmsServiceController extends Controller {

    private $controller = 'ArmsService';

    public function index(Request $request) {

        $nameArr = ArmsService::select('code')->orderBy('order', 'asc')->get();

        //passing param for custom function
        $qpArr = $request->all();

        $targetArr = ArmsService::select('arms_service.id', 'arms_service.name', 'arms_service.code', 'arms_service.order', 'arms_service.status')->orderBy('order', 'asc');

        //begin filtering
        $searchText = $request->fil_search;
        if (!empty($searchText)) {
            $targetArr->where(function ($query) use ($searchText) {
                $query->where('arms_service.name', 'LIKE', '%' . $searchText . '%')
                        ->orWhere('arms_service.code', 'LIKE', '%' . $searchText . '%');
            });
        }
        //end filtering

        if ($request->download == 'pdf') {
            $targetArr = $targetArr->get();
        } else {
            $targetArr = $targetArr->paginate(Session::get('paginatorCount'));
        }

        //change previous page number after delete if no data has current page
        if ($targetArr->isEmpty() && isset($qpArr['page']) && ($qpArr['page'] > 1)) {
            $page = ($qpArr['page'] - 1);
            return redirect('/armsService?page=' . $page);
        }
        if ($request->download == 'pdf') {
            $rankCode = Rank::select('code')->where('id', Auth::user()->rank_id)->first();

            $pdf = PDF::loadView('armsService.printArmsService', compact('targetArr', 'rankCode'))->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download('armsServiceList.pdf');
        } else {
            return view('armsService.index')->with(compact('targetArr', 'qpArr', 'nameArr'));
        }
    }

    public function create(Request $request) { //passing param for custom function
        $qpArr = $request->all();
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 1);
        $lastOrderNumber = Helper::getLastOrder($this->controller, 1);
        return view('armsService.create')->with(compact('qpArr', 'orderList', 'lastOrderNumber'));
    }

    public function store(Request $request) {
        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? $qpArr['page'] : '';
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:arms_service',
                    'code' => 'required|unique:arms_service',
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('armsService/create' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target = new ArmsService;
        $target->name = $request->name;
        $target->code = $request->code;
        $target->order = 0;
        $target->status = $request->status;

        if ($target->save()) {
            Helper :: insertOrder($this->controller, $request->order, $target->id);
            Session::flash('success', __('label.ARMS_SERVICE_CREATED_SUCCESSFULLY'));
            return redirect('armsService');
        } else {
            Session::flash('error', __('label.ARMS_SERVICE_COULD_NOT_BE_CREATED'));
            return redirect('armsService/create' . $pageNumber);
        }
    }

    public function edit(Request $request, $id) {
        $target = ArmsService::find($id);
        $orderList = array('0' => __('label.SELECT_ORDER_OPT')) + Helper::getOrderList($this->controller, 2);
        if (empty($target)) {
            Session::flash('error', trans('label.INVALID_DATA_ID'));
            return redirect('armsService');
        }

        //passing param for custom function
        $qpArr = $request->all();
        return view('armsService.edit')->with(compact('target', 'qpArr', 'orderList'));
    }

    public function update(Request $request, $id) {
        $target = ArmsService::find($id);
        $presentOrder = $target->order;

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = $qpArr['filter'];
        //end back same page after update

        $validator = Validator::make($request->all(), [
                    'name' => 'required|unique:arms_service,name,' . $id,
                    'code' => 'required|unique:arms_service,code,' . $id,
                    'order' => 'required|not_in:0'
        ]);

        if ($validator->fails()) {
            return redirect('armsService/' . $id . '/edit' . $pageNumber)
                            ->withInput()
                            ->withErrors($validator);
        }

        $target->name = $request->name;
        $target->code = $request->code;
        $target->order = $request->order;
        $target->status = $request->status;

        if ($target->save()) {
            if ($request->order != $presentOrder) {
                Helper :: updateOrder($this->controller, $request->order, $target->id, $presentOrder);
            }
            Session::flash('success', __('label.ARMS_SERVICE_UPDATED_SUCCESSFULLY'));
            return redirect('armsService' . $pageNumber);
        } else {
            Session::flash('error', __('label.ARMS_SERVICE_COULD_NOT_BE_UPDATED'));
            return redirect('armsService/' . $id . '/edit' . $pageNumber);
        }
    }

    public function destroy(Request $request, $id) {
        $target = ArmsService::find($id);

        //begin back same page after update
        $qpArr = $request->all();
        $pageNumber = !empty($qpArr['page']) ? $qpArr['page'] : '';
        //end back same page after update

        if (empty($target)) {
            Session::flash('error', __('label.INVALID_DATA_ID'));
        }

        //Check Dependency before deletion
        $dependencyArr = ['CmBasicProfile' => 'arms_service_id'];

        foreach ($dependencyArr as $model => $key) {
            $namespacedModel = '\\App\\' . $model;
            $dependentData = $namespacedModel::where($key, $id)->first();
            if (!empty($dependentData)) {
                Session::flash('error', __('label.COULD_NOT_DELETE_DATA_HAS_RELATION_WITH_MODEL') . $model);
                return redirect('armsService' . $pageNumber);
            }
        }

        if ($target->delete()) {
            Helper :: deleteOrder($this->controller, $target->order);
            Session::flash('error', __('label.ARMS_SERVICE_DELETED_SUCCESSFULLY'));
        } else {
            Session::flash('error', __('label.ARMS_SERVICE_COULD_NOT_BE_DELETED'));
        }
        return redirect('armsService' . $pageNumber);
    }

    public function filter(Request $request) {
        $url = 'fil_search=' . $request->fil_search;
        return Redirect::to('armsService?' . $url);
    }

}
