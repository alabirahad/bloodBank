<?php

namespace App\Http\Controllers\Admin;

use DB;
use URL;
use Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Course;
use App\Wing;
use App\ArmsService;
use App\CommissioningCourse;
use App\CmBasicProfile;
use App\TrainingYear;
use App\TermToCourse;
use App\TermToEvent;
use App\TermToSubEvent;
use App\TermToSubSubEvent;
use App\TermToSubSubSubEvent;
use App\EventAssessmentMarking;
use App\EventAssessmentMarkingLock;
use App\CiModerationMarkingLock;
use Helper;

class DashboardController extends Controller {

    public function __construct() {
        //$this->middleware('auth');
    }

    public function index(Request $request) {

        //********************* Start :: term progress ******************//
        $activeTrainingYearInfo = TrainingYear::where('status', '1')->first();
        
        $dsApptList = User::where('group_id', 4)->where('status', '1')
                ->pluck('official_name', 'id')->toArray();

        $course = CmBasicProfile::join('course', 'course.id', 'cm_basic_profile.course_id')
                ->select('course.name', 'course.id', DB::raw('COUNT(cm_basic_profile.id) as total_cm'))
                ->groupBy('course.name', 'course.id')
                ->where('course.training_year_id', $activeTrainingYearInfo->id)
                ->where('course.status', '1')
                ->first();
        $courseTotalCm = !empty($course->total_cm) ? $course->total_cm : 0;


        $termToCourseArr = $courseArr = $eventMksWtArr = [];
        if (!empty($activeTrainingYearInfo)) {
            $termInfo = TermToCourse::join('course', 'course.id', '=', 'term_to_course.course_id')
                    ->leftJoin('term', 'term.id', '=', 'term_to_course.term_id')
                    ->where('course.training_year_id', $activeTrainingYearInfo->id)
                    ->where('course.status', '1')
                    ->select('term.name as term', 'term_to_course.initial_date', 'term_to_course.termination_date'
                            , 'term_to_course.number_of_week', 'term_to_course.status', 'term_to_course.active'
                            , 'term_to_course.course_id', 'term_to_course.term_id', 'course.name as course'
                            , 'course.initial_date as course_initial_date', 'course.termination_date as course_termination_date')
                    ->get();

            if (!$termInfo->isEmpty()) {
                foreach ($termInfo as $info) {
                    $termToCourseArr[$info->course_id]['course'] = $info->course;
                    $termToCourseArr[$info->course_id]['course_initial_date'] = $info->course_initial_date;
                    $termToCourseArr[$info->course_id]['course_termination_date'] = $info->course_termination_date;
                    $termToCourseArr[$info->course_id][$info->term_id]['term'] = $info->term;
                    $termToCourseArr[$info->course_id][$info->term_id]['initial_date'] = $info->initial_date;
                    $termToCourseArr[$info->course_id][$info->term_id]['termination_date'] = $info->termination_date;
                    $termToCourseArr[$info->course_id][$info->term_id]['status'] = $info->status;
                    $termToCourseArr[$info->course_id][$info->term_id]['active'] = $info->active;
                    $courseArr[$info->course_id] = $info->course_id;
                }
            }
        }

        $eventInfo = TermToEvent::join('event', 'event.id', '=', 'term_to_event.event_id')
                ->leftJoin('event_mks_wt', 'event_mks_wt.event_id', '=', 'term_to_event.event_id')
                ->whereIn('term_to_event.course_id', $courseArr)
                ->where('event.status', '1')
                ->select('event.id as event_id', 'event_mks_wt.wt', 'event.has_sub_event'
                        , 'term_to_event.course_id', 'term_to_event.term_id')
                ->orderBy('event.order', 'asc')
                ->get();

        if (!$eventInfo->isEmpty()) {
            foreach ($eventInfo as $ev) {
                if (empty($ev->has_sub_event)) {
                    $eventMksWtArr['mks_wt'][$ev->course_id][$ev->term_id][$ev->event_id][0][0][0]['wt'] = !empty($ev->wt) ? $ev->wt : 0;

                    $eventMksWtArr['total_wt'][$ev->course_id][$ev->term_id] = !empty($eventMksWtArr['total_wt'][$ev->course_id][$ev->term_id]) ? $eventMksWtArr['total_wt'][$ev->course_id][$ev->term_id] : 0;
                    $eventMksWtArr['total_wt'][$ev->course_id][$ev->term_id] += !empty($ev->wt) ? $ev->wt : 0;
                }
            }
        }

        //sub event info
        $subEventInfo = TermToSubEvent::join('sub_event', 'sub_event.id', '=', 'term_to_sub_event.sub_event_id')
                ->join('event', 'event.id', '=', 'term_to_sub_event.event_id')
                ->join('event_to_sub_event', function($join) {
                    $join->on('event_to_sub_event.event_id', '=', 'term_to_sub_event.event_id');
                    $join->on('event_to_sub_event.sub_event_id', '=', 'term_to_sub_event.sub_event_id');
                })
                ->leftJoin('sub_event_mks_wt', function($join) {
                    $join->on('sub_event_mks_wt.event_id', '=', 'term_to_sub_event.event_id');
                    $join->on('sub_event_mks_wt.sub_event_id', '=', 'term_to_sub_event.sub_event_id');
                })
                ->whereIn('term_to_sub_event.course_id', $courseArr)
                ->where('sub_event.status', '1')
                ->select('sub_event.id as sub_event_id', 'sub_event_mks_wt.wt', 'event_to_sub_event.has_sub_sub_event'
                        , 'event_to_sub_event.event_id', 'term_to_sub_event.course_id', 'term_to_sub_event.term_id')
                ->orderBy('event.order', 'asc')
                ->orderBy('sub_event.order', 'asc')
                ->get();


        if (!$subEventInfo->isEmpty()) {
            foreach ($subEventInfo as $subEv) {
                if (empty($subEv->has_sub_sub_event)) {
                    $eventMksWtArr['mks_wt'][$subEv->course_id][$subEv->term_id][$subEv->event_id][$subEv->sub_event_id][0][0]['wt'] = !empty($subEv->wt) ? $subEv->wt : 0;

                    $eventMksWtArr['total_wt'][$subEv->course_id][$subEv->term_id] = !empty($eventMksWtArr['total_wt'][$subEv->course_id][$subEv->term_id]) ? $eventMksWtArr['total_wt'][$subEv->course_id][$subEv->term_id] : 0;
                    $eventMksWtArr['total_wt'][$subEv->course_id][$subEv->term_id] += !empty($subEv->wt) ? $subEv->wt : 0;
                }
            }
        }

        //sub sub event info
        $subSubEventInfo = TermToSubSubEvent::join('sub_sub_event', 'sub_sub_event.id', '=', 'term_to_sub_sub_event.sub_sub_event_id')
                ->join('sub_event', 'sub_event.id', '=', 'term_to_sub_sub_event.sub_event_id')
                ->join('event', 'event.id', '=', 'term_to_sub_sub_event.event_id')
                ->join('event_to_sub_sub_event', function($join) {
                    $join->on('event_to_sub_sub_event.event_id', '=', 'term_to_sub_sub_event.event_id');
                    $join->on('event_to_sub_sub_event.sub_event_id', '=', 'term_to_sub_sub_event.sub_event_id');
                    $join->on('event_to_sub_sub_event.sub_sub_event_id', '=', 'term_to_sub_sub_event.sub_sub_event_id');
                })
                ->leftJoin('sub_sub_event_mks_wt', function($join) {
                    $join->on('sub_sub_event_mks_wt.event_id', '=', 'term_to_sub_sub_event.event_id');
                    $join->on('sub_sub_event_mks_wt.sub_event_id', '=', 'term_to_sub_sub_event.sub_event_id');
                    $join->on('sub_sub_event_mks_wt.sub_sub_event_id', '=', 'term_to_sub_sub_event.sub_sub_event_id');
                })
                ->whereIn('term_to_sub_sub_event.course_id', $courseArr)
                ->where('sub_sub_event.status', '1')
                ->select('sub_sub_event.id as sub_sub_event_id', 'sub_sub_event_mks_wt.wt', 'event_to_sub_sub_event.has_sub_sub_sub_event'
                        , 'event_to_sub_sub_event.event_id', 'event_to_sub_sub_event.sub_event_id', 'term_to_sub_sub_event.course_id'
                        , 'term_to_sub_sub_event.term_id')
                ->orderBy('event.order', 'asc')
                ->orderBy('sub_event.order', 'asc')
                ->orderBy('sub_sub_event.order', 'asc')
                ->get();


        if (!$subSubEventInfo->isEmpty()) {
            foreach ($subSubEventInfo as $subSubEv) {
                if (empty($subSubEv->has_sub_sub_sub_event)) {
                    $eventMksWtArr['mks_wt'][$subSubEv->course_id][$subSubEv->term_id][$subSubEv->event_id][$subSubEv->sub_event_id][$subSubEv->sub_sub_event_id][0]['wt'] = !empty($subSubEv->wt) ? $subSubEv->wt : 0;

                    $eventMksWtArr['total_wt'][$subSubEv->course_id][$subSubEv->term_id] = !empty($eventMksWtArr['total_wt'][$subSubEv->course_id][$subSubEv->term_id]) ? $eventMksWtArr['total_wt'][$subSubEv->course_id][$subSubEv->term_id] : 0;
                    $eventMksWtArr['total_wt'][$subSubEv->course_id][$subSubEv->term_id] += !empty($subSubEv->wt) ? $subSubEv->wt : 0;
                }
            }
        }

        //sub sub sub event info
        $subSubSubEventInfo = TermToSubSubSubEvent::join('sub_sub_sub_event', 'sub_sub_sub_event.id', '=', 'term_to_sub_sub_sub_event.sub_sub_sub_event_id')
                ->join('sub_sub_event', 'sub_sub_event.id', '=', 'term_to_sub_sub_sub_event.sub_sub_event_id')
                ->join('sub_event', 'sub_event.id', '=', 'term_to_sub_sub_sub_event.sub_event_id')
                ->join('event', 'event.id', '=', 'term_to_sub_sub_sub_event.event_id')
                ->join('event_to_sub_sub_sub_event', function($join) {
                    $join->on('event_to_sub_sub_sub_event.event_id', '=', 'term_to_sub_sub_sub_event.event_id');
                    $join->on('event_to_sub_sub_sub_event.sub_event_id', '=', 'term_to_sub_sub_sub_event.sub_event_id');
                    $join->on('event_to_sub_sub_sub_event.sub_sub_event_id', '=', 'term_to_sub_sub_sub_event.sub_sub_event_id');
                    $join->on('event_to_sub_sub_sub_event.sub_sub_sub_event_id', '=', 'term_to_sub_sub_sub_event.sub_sub_sub_event_id');
                })
                ->leftJoin('sub_sub_sub_event_mks_wt', function($join) {
                    $join->on('sub_sub_sub_event_mks_wt.event_id', '=', 'term_to_sub_sub_sub_event.event_id');
                    $join->on('sub_sub_sub_event_mks_wt.sub_event_id', '=', 'term_to_sub_sub_sub_event.sub_event_id');
                    $join->on('sub_sub_sub_event_mks_wt.sub_sub_event_id', '=', 'term_to_sub_sub_sub_event.sub_sub_event_id');
                    $join->on('sub_sub_sub_event_mks_wt.sub_sub_sub_event_id', '=', 'term_to_sub_sub_sub_event.sub_sub_sub_event_id');
                })
                ->whereIn('term_to_sub_sub_sub_event.course_id', $courseArr)
                ->where('sub_sub_sub_event.status', '1')
                ->select('sub_sub_sub_event.id as sub_sub_sub_event_id', 'sub_sub_sub_event_mks_wt.wt', 'event_to_sub_sub_sub_event.event_id'
                        , 'event_to_sub_sub_sub_event.sub_event_id', 'event_to_sub_sub_sub_event.sub_sub_event_id'
                        , 'term_to_sub_sub_sub_event.course_id', 'term_to_sub_sub_sub_event.term_id')
                ->orderBy('event.order', 'asc')
                ->orderBy('sub_event.order', 'asc')
                ->orderBy('sub_sub_event.order', 'asc')
                ->orderBy('sub_sub_sub_event.order', 'asc')
                ->get();


        if (!$subSubSubEventInfo->isEmpty()) {
            foreach ($subSubSubEventInfo as $subSubSubEv) {
                $eventMksWtArr['mks_wt'][$subSubSubEv->course_id][$subSubSubEv->term_id][$subSubSubEv->event_id][$subSubSubEv->sub_event_id][$subSubSubEv->sub_sub_event_id][$subSubSubEv->sub_sub_sub_event_id]['wt'] = !empty($subSubSubEv->wt) ? $subSubSubEv->wt : 0;

                $eventMksWtArr['total_wt'][$subSubSubEv->course_id][$subSubSubEv->term_id] = !empty($eventMksWtArr['total_wt'][$subSubSubEv->course_id][$subSubSubEv->term_id]) ? $eventMksWtArr['total_wt'][$subSubSubEv->course_id][$subSubSubEv->term_id] : 0;
                $eventMksWtArr['total_wt'][$subSubSubEv->course_id][$subSubSubEv->term_id] += !empty($subSubSubEv->wt) ? $subSubSubEv->wt : 0;
            }
        }

        $eventAssessmentInfo = EventAssessmentMarking::select('course_id', 'term_id', 'event_id', 'sub_event_id', 'sub_sub_event_id'
                        , 'sub_sub_sub_event_id', DB::raw('COUNT(DISTINCT updated_by) as total'))
                ->groupBy('course_id', 'term_id', 'event_id', 'sub_event_id', 'sub_sub_event_id', 'sub_sub_sub_event_id')
                ->whereIn('course_id', $courseArr)
                ->get();
        $eventStatusArr = [];
        if (!$eventAssessmentInfo->isEmpty()) {
            foreach ($eventAssessmentInfo as $info) {
                $eventStatusArr[$info->course_id][$info->term_id][$info->event_id][$info->sub_event_id][$info->sub_sub_event_id][$info->sub_sub_sub_event_id]['event_marked'] = $info->total;
            }
        }
        $eventAssessmentLockInfo = EventAssessmentMarkingLock::select('course_id', 'term_id', 'event_id', 'sub_event_id', 'sub_sub_event_id'
                        , 'sub_sub_sub_event_id', DB::raw('COUNT(id) as total'))
                ->groupBy('course_id', 'term_id', 'event_id', 'sub_event_id', 'sub_sub_event_id', 'sub_sub_sub_event_id')
                ->whereIn('course_id', $courseArr)
                ->get();

        if (!$eventAssessmentLockInfo->isEmpty()) {
            foreach ($eventAssessmentLockInfo as $info) {
                $eventStatusArr[$info->course_id][$info->term_id][$info->event_id][$info->sub_event_id][$info->sub_sub_event_id][$info->sub_sub_sub_event_id]['event_locked'] = $info->total;
            }
        }


        if (!empty($eventMksWtArr['mks_wt'])) {
            foreach ($eventMksWtArr['mks_wt'] as $courseId => $courseEvInfo) {
                foreach ($courseEvInfo as $termId => $termEvInfo) {
                    foreach ($termEvInfo as $eventId => $evInfo) {
                        foreach ($evInfo as $subEventId => $subEvInfo) {
                            foreach ($subEvInfo as $subSubEventId => $subSubEvInfo) {
                                foreach ($subSubEvInfo as $subSubSubEventId => $subSubSubEvInfo) {
                                    $eventMksWtArr['event_to_be_locked'][$courseId][$termId] = !empty($eventMksWtArr['event_to_be_locked'][$courseId][$termId]) ? $eventMksWtArr['event_to_be_locked'][$courseId][$termId] : 0;
                                    $eventMksWtArr['event_to_be_locked'][$courseId][$termId] += 1;
                                    $eventMksWtArr['event_to_be_moderated'][$courseId][$termId] = !empty($eventMksWtArr['event_to_be_moderated'][$courseId][$termId]) ? $eventMksWtArr['event_to_be_moderated'][$courseId][$termId] : 0;
                                    $eventMksWtArr['event_to_be_moderated'][$courseId][$termId] += 1;
                                    $eventDsMarked = !empty($eventStatusArr[$courseId][$termId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['event_marked']) ? $eventStatusArr[$courseId][$termId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['event_marked'] : 0;
                                    $eventDsLocked = !empty($eventStatusArr[$courseId][$termId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['event_locked']) ? $eventStatusArr[$courseId][$termId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]['event_locked'] : 0;
                                    $eventMksWtArr['event_completed'][$courseId][$termId] = !empty($eventMksWtArr['event_completed'][$courseId][$termId]) ? $eventMksWtArr['event_completed'][$courseId][$termId] : 0;
                                    $eventMksWtArr['event_completed'][$courseId][$termId] += (!empty($eventDsMarked) && !empty($eventDsLocked) && ($eventDsMarked == $eventDsLocked) ? 1 : 0);
                                }
                            }
                        }
                    }
                }
            }
        }



        $ciModLockInfo = CiModerationMarkingLock::select('course_id', 'term_id', 'event_id', 'sub_event_id', 'sub_sub_event_id'
                        , 'sub_sub_sub_event_id', DB::raw('COUNT(id) as total'))
                ->groupBy('course_id', 'term_id', 'event_id', 'sub_event_id', 'sub_sub_event_id', 'sub_sub_sub_event_id')
                ->whereIn('course_id', $courseArr)
                ->get();

        if (!$ciModLockInfo->isEmpty()) {
            foreach ($ciModLockInfo as $info) {
                $eventMksWtArr['event_moderated'][$info->course_id][$info->term_id] = !empty($eventMksWtArr['event_moderated'][$info->course_id][$info->term_id]) ? $eventMksWtArr['event_moderated'][$info->course_id][$info->term_id] : 0;
                $eventMksWtArr['event_moderated'][$info->course_id][$info->term_id] += $info->total;
            }
        }

        if (!empty($termToCourseArr)) {
            foreach ($termToCourseArr as $courseId => $courseInfo) {
                foreach ($courseInfo as $termId => $termInfo) {
                    if (is_int($termId)) {
                        $eventProgress = $modProgress = 0;

                        $totalEventLocked = !empty($eventMksWtArr['event_completed'][$courseId][$termId]) ? $eventMksWtArr['event_completed'][$courseId][$termId] : 0;
                        $totalEventModerated = !empty($eventMksWtArr['event_moderated'][$courseId][$termId]) ? $eventMksWtArr['event_moderated'][$courseId][$termId] : 0;
                        $totalWt = !empty($eventMksWtArr['total_wt'][$courseId][$termId]) ? $eventMksWtArr['total_wt'][$courseId][$termId] : 0;

                        if (!empty($eventMksWtArr['event_to_be_locked'][$courseId][$termId])) {
                            $eventProgress = ($totalEventLocked / $eventMksWtArr['event_to_be_locked'][$courseId][$termId]);
                        }
                        if (!empty($eventMksWtArr['event_to_be_moderated'][$courseId][$termId])) {
                            $modProgress = ($totalEventModerated / $eventMksWtArr['event_to_be_moderated'][$courseId][$termId]);
                        }

                        $termToCourseArr[$courseId][$termId]['percent'] = Helper::numberFormat2Digit(($eventProgress + $modProgress) * 100);
                    }
                }
            }
        }

        //********************* End :: term progress *******************//
        //********************* Start :: participation (last 5 courses) ******************//
        $lastFiveCourseList = Course::join('training_year', 'training_year.id', 'course.training_year_id')
                        ->orderBy('training_year.start_date', 'asc')->orderBy('course.id', 'asc')->limit(5);
        $lastFiveCourseIdList = $lastFiveCourseList->pluck('course.id', 'course.id')->toArray();
        $lastFiveCourseList = $lastFiveCourseList->pluck('course.name', 'course.id')->toArray();

        $courseWiseCmNoList = CmBasicProfile::whereIn('course_id', $lastFiveCourseIdList)
                        ->select('course_id', DB::raw('COUNT(id) as total_cm'))
                        ->groupBy('course_id')->pluck('total_cm', 'course_id')->toArray();
        //********************* End :: participation (last 5 courses) *******************//
        //********************* Start :: participation (wing wise) *******************//

        $wingList = Wing::where('status', '1')->orderBy('order', 'asc')->pluck('code', 'id')->toArray();
        $wingWiseCmNoList = CmBasicProfile::select('wing_id', DB::raw('COUNT(id) as total_cm'))
                        ->groupBy('wing_id')->pluck('total_cm', 'wing_id')->toArray();
        //********************* End :: participation (wing wise) *******************//

        if (Auth::user()->group_id == '1') {
            return view('admin.superAdmin.dashboard')->with(compact('request', 'termToCourseArr', 'lastFiveCourseList', 'courseWiseCmNoList', 'course'
                                    , 'wingList', 'wingWiseCmNoList'));
        } elseif (Auth::user()->group_id == '2') {
            return view('admin.comdt.dashboard')->with(compact('request', 'termToCourseArr', 'lastFiveCourseList', 'courseWiseCmNoList', 'course'
                                    , 'wingList', 'wingWiseCmNoList'));
        } elseif (Auth::user()->group_id == '3') {
            return view('admin.ci.dashboard')->with(compact('request', 'termToCourseArr', 'lastFiveCourseList', 'courseWiseCmNoList', 'course'
                                    , 'wingList', 'wingWiseCmNoList', 'dsApptList'));
        } elseif (Auth::user()->group_id == '4') {
            return view('admin.ds.dashboard')->with(compact('request', 'termToCourseArr', 'lastFiveCourseList', 'courseWiseCmNoList', 'course'
                                    , 'wingList', 'wingWiseCmNoList', 'dsApptList'));
        }
    }

}
