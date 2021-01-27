<?php

namespace App\Http\Controllers\Admin;

use DB;
use URL;
use Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\MajorEvent;
use App\User;
use App\CenterToBatch;
use App\Recruit;
use App\RecruitBatch;
use App\TrainingYear;
use App\RctState;
use App\DashboardSetup;
use Helper;

class DashboardController extends Controller {

    public function __construct() {
        //$this->middleware('auth');
    }

    public function index(Request $request) {

        if (Auth::user()->group_id == '1') {
            return view('admin.superAdmin.dashboard')->with(compact('request'));
        } elseif (Auth::user()->group_id == '2') {
            return view('admin.comdt.dashboard')->with(compact('request'));
        } elseif (Auth::user()->group_id == '3') {
            return view('admin.ci.dashboard')->with(compact('request'));
        } elseif (Auth::user()->group_id == '4') {
            return view('admin.ds.dashboard')->with(compact('request'));
        }
    }

    public function getRecruitData(Request $request) {
        // Admin Control
        //all batch recruit data center wise :: active training year wise
        $rectPlanned = CenterToBatch::where('center_id', Auth::user()->center_id);
        if (!empty($request->batch_id)) {
            $rectPlanned = $rectPlanned->where('batch_id', $request->batch_id);
        }
        $rectPlanned = $rectPlanned->sum('planned_rect');


        $rectJoint = Recruit::where('center_id', Auth::user()->center_id);
        if (!empty($request->batch_id)) {
            $rectJoint = $rectJoint->where('batch_id', $request->batch_id);
        }
        $rectJoint = $rectJoint->count();

        $rectCurrent = Recruit::where('center_id', Auth::user()->center_id);
        if (!empty($request->batch_id)) {
            $rectCurrent = $rectCurrent->where('batch_id', $request->batch_id);
        }
        $rectCurrent = $rectCurrent->where('recruit.drop_status', '0')->count();


        // all center & all batches on-parade & absent :: Today Date
        $today = date('Y-m-d');
        $getRctStateDetails = RctState::where('state_date', $today)
                ->where('center_id', Auth::user()->center_id);
        if (!empty($request->batch_id)) {
            $getRctStateDetails = $getRctStateDetails->where('batch_id', $request->batch_id);
        }
        $getRctStateDetails = $getRctStateDetails->select('rct_state.*')->get();

        $RctStateinfoArr = [];
        foreach ($getRctStateDetails as $value) {
            $RctStateinfoArr[] = json_decode($value->info);
        }
        $onParadeArr = $absentArr = [];
        $rectOnParadeCount = $rectAbsentcount = 0;
        foreach ($RctStateinfoArr as $val) {
            foreach ($val as $recruitId => $item) {
                if ($item == 1) {
                    $onParadeArr[] = $item;
                    $rectOnParadeCount = count($onParadeArr);
                } else {
                    $absentArr[] = $item;
                    $rectAbsentcount = count($absentArr);
                }
            }
        }

        if (empty($rectPlanned)) {
            $rectJoint = 0;
            $rectCurrent = 0;
            $rectOnParadeCount = 0;
            $rectAbsentcount = 0;
        }



        //****** active training year wise batch wise all center *******
        // On parade & Absent end

        $view = view('admin.admin.getRecruitData', compact('rectPlanned', 'rectJoint', 'rectCurrent', 'rectOnParadeCount'
                        , 'rectAbsentcount'))->render();
        return response()->json(['html' => $view]);
    }

    //active training year
    public function getBatchActiveYear(Request $request) {

        $activeTrainingYear = TrainingYear::where('status', '1')
                        ->select('id')->first();

        $centerArr = RecruitBatch::join('center_to_batch', 'center_to_batch.batch_id', '=', 'recruit_batch.id')
                ->join('center', 'center.id', '=', 'center_to_batch.center_id')
                ->where('center_to_batch.batch_id', $request->batch_id)
                ->where('recruit_batch.training_year_id', $activeTrainingYear->id)
                ->select('center.id as center_id', 'center.name as center_name', 'center_to_batch.planned_rect')
                ->orderBy('center.id', 'asc')
                ->get();


        $joinedArr = Recruit::join('recruit_batch', 'recruit_batch.id', '=', 'recruit.batch_id')
                        ->join('center', 'center.id', '=', 'recruit.center_id')
                        ->where('recruit_batch.training_year_id', $activeTrainingYear->id)
                        ->where('recruit.batch_id', $request->batch_id)
                        ->groupBy('center.id')
                        ->select('center.id', DB::raw('count(recruit.id) as total'))
                        ->orderBy('center.id', 'asc')
                        ->pluck('total', 'id')->toArray();

        $currentArr = Recruit::join('recruit_batch', 'recruit_batch.id', '=', 'recruit.batch_id')
                        ->join('center', 'center.id', '=', 'recruit.center_id')
                        ->where('recruit_batch.training_year_id', $activeTrainingYear->id)
                        ->where('recruit.batch_id', $request->batch_id)
                        ->where('recruit.drop_status', '0')
                        ->groupBy('center.id')
                        ->select('center.id', DB::raw('count(recruit.id) as total'))
                        ->orderBy('center.id', 'asc')
                        ->pluck('total', 'id')->toArray();


        $targetArr = [];
        if (!$centerArr->isEmpty()) {
            foreach ($centerArr as $item) {
                $targetArr[$item->center_id]['name'] = $item->center_name;
                if (empty($item->planned_rect)) {
                    $targetArr[$item->center_id]['planned'] = 0;
                    $targetArr[$item->center_id]['joined'] = 0;
                    $targetArr[$item->center_id]['current'] = 0;
                } else {
                    $targetArr[$item->center_id]['planned'] = $item->planned_rect;
                    $targetArr[$item->center_id]['joined'] = !empty($joinedArr[$item->center_id]) ? $joinedArr[$item->center_id] : 0;
                    $targetArr[$item->center_id]['current'] = !empty($currentArr[$item->center_id]) ? $currentArr[$item->center_id] : 0;
                }
            }
        }

        $view = view('admin.superAdmin.getBatchData', compact('centerArr', 'targetArr'))->render();
        return response()->json(['html' => $view]);
    }

}
