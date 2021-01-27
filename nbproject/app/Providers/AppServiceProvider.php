<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Auth;
use Route;

class AppServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        view()->composer('*', function ($view) {

            if (Auth::check()) {

                // $activeTrainingYearInfo = TrainingYear::where('status', '1')->first();
//                $assignedCmdrInfo = PlCmdrToPlatoon::join('recruit_batch', 'recruit_batch.id', '=', 'pl_cmdr_to_platoon.batch_id')
//                        ->join('training_year', 'training_year.id', '=', 'recruit_batch.training_year_id')
//                        ->where('training_year.status', '1')
//                        ->where('pl_cmdr_to_platoon.center_id', Auth::user()->center_id)
//                        ->where('pl_cmdr_to_platoon.pl_cmdr_id', Auth::user()->id)
//                        ->first();
//                // Get Slider :: START
//                $sliderArr = Slider::where('status', '1')
//                        ->orderBy('order', 'asc')
//                        ->select('*')
//                        ->get();
//                // Get Slider :: END
                $currentControllerFunction = Route::currentRouteAction();
                $controllerName = $currentCont = '';
                if (!empty($currentControllerFunction[1])) {
                    $currentCont = preg_match('/([a-z]*)@/i', request()->route()->getActionName(), $currentControllerFunction);
                    $controllerName = str_replace('controller', '', strtolower($currentControllerFunction[1]));
                }


                //************************** top nav bar circle Total Planned Strength DATA *********
                //*******Total recruit & today absent on parade Recruit data *******  User Wise *****
                //****** active training year wise batch wise all center *******
//                $activeTrainingYear = TrainingYear::where('status', '1')
//                                ->select('id')->first();
//                $recruitPlanned = 0;
//                if (!empty($activeTrainingYear)) {
//                    //all batch recruit planned active training year wise
//                    $recruitPlanned = RecruitBatch::join('center_to_batch', 'center_to_batch.batch_id', '=', 'recruit_batch.id');
//                    if (!empty(Auth::user()->center_id)) {
//                        $recruitPlanned = $recruitPlanned->where('center_to_batch.center_id', Auth::user()->center_id);
//                    }
//                    $recruitPlanned = $recruitPlanned->where('recruit_batch.training_year_id', $activeTrainingYear->id)
//                            ->sum('planned_rect');
//
//                    // Total Recruit Count 
//                    $recruitJoint = Recruit::join('recruit_batch', 'recruit_batch.id', '=', 'recruit.batch_id');
//                    if (!empty(Auth::user()->center_id)) {
//                        $recruitJoint = $recruitJoint->where('recruit.center_id', Auth::user()->center_id);
//                    }
//                    $recruitJoint = $recruitJoint->where('recruit_batch.training_year_id', $activeTrainingYear->id)
//                            ->count();
//
//                    $recruitCurrent = Recruit::join('recruit_batch', 'recruit_batch.id', '=', 'recruit.batch_id');
//                    if (!empty(Auth::user()->center_id)) {
//                        $recruitCurrent = $recruitCurrent->where('recruit.center_id', Auth::user()->center_id);
//                    }
//                    $recruitCurrent = $recruitCurrent->where('recruit_batch.training_year_id', $activeTrainingYear->id)
//                            ->where('recruit.drop_status', '0')
//                            ->count();
//                }
//                // all center & all batches on-parade & absent :: Today Date
//                $today = date('Y-m-d');
//                $getRctStateDetails = RctState::where('state_date', $today);
//                if (!empty(Auth::user()->center_id)) {
//                    $getRctStateDetails = $getRctStateDetails->where('center_id', Auth::user()->center_id);
//                }
//                $getRctStateDetails = $getRctStateDetails->select('rct_state.*')->get();
//
//
//
//                $RctStateinfoArr = [];
//                foreach ($getRctStateDetails as $value) {
//                    $RctStateinfoArr[] = json_decode($value->info);
//                }
//
//
//
//                $onParadeArr = $absentArr = $otherAbsentArr = [];
//                $onParadeCount = $absentcount = 0;
//                foreach ($RctStateinfoArr as $val) {
//                    foreach ($val as $recruitId => $item) {
//                        if ($item == 1) {
//                            $onParadeArr[] = $item;
//                            $onParadeCount = count($onParadeArr);
//                        } else {
//                            $otherAbsentArr[$item][] = $recruitId;
//                            $absentArr[] = $item;
//                            $absentcount = count($absentArr);
//                        }
//                    }
//                }
//
//                if (empty($recruitPlanned)) {
//                    $recruitJoint = 0;
//                    $recruitCurrent = 0;
//                    $onParadeCount = 0;
//                    $absentcount = 0;
//                }
                //**************Endof Top nav bar data **********

                $view->with([
                    'controllerName' => $controllerName /*, 'sliderArr' => $sliderArr
                    , 'assignedCmdrInfo' => $assignedCmdrInfo, 'recruitPlanned' => $recruitPlanned
                    , 'recruitJoint' => $recruitJoint, 'recruitCurrent' => $recruitCurrent
                    , 'onParadeCount' => $onParadeCount, 'absentcount' => $absentcount*/
                ]);
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

}
