<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Country;
use App\Division;
use App\District;
use App\Thana;
use App\GradingSystem;
use App\Course;
use App\EventAssessmentMarking;
use App\DsMarkingGroup;
use App\MarkingGroup;
use App\TrainingYear;
use App\Term;
use App\Event;
use App\SubEvent;
use App\SubSubEvent;
use App\SubSubSubEvent;
use App\EventAssessmentMarkingLock;
use Illuminate\Http\Request;

class Common {

    public static function getPosition($cmArr, $totalWtKey, $positionKey, $mutualAssessment = 0) {
        $positionArr = [];
        if (!empty($cmArr)) {
            foreach ($cmArr as $cmId => $cm) {
                if (!isset($cm[$totalWtKey])) {
                    $cm[$totalWtKey] = 0;
                }
                if (!empty($positionArr)) {
                    if (!in_array($cm[$totalWtKey], $positionArr)) {
                        $positionArr[] = $cm[$totalWtKey];
                    }
                } else {
                    $positionArr[] = $cm[$totalWtKey];
                }
            }
        }
        if (!empty($mutualAssessment)) {
            sort($positionArr);
        } else {
            rsort($positionArr);
        }

        $positionArr2 = [];
        if (!empty($positionArr)) {
            foreach ($positionArr as $ptn => $value) {
                if (!empty($value)) {
                    $positionArr2[strval($value)] = ++$ptn;
                }
            }
        }

        $positionArr = $positionArr2;
        if (!empty($cmArr)) {
            foreach ($cmArr as $cmId => $cm) {
                if (!isset($cm[$totalWtKey])) {
                    $cm[$totalWtKey] = 0;
                }

                if (!empty($positionArr)) {
                    $cmArr[$cmId][$positionKey] = isset($positionArr[strval($cm[$totalWtKey])]) ? $positionArr[strval($cm[$totalWtKey])] : null;
                } else {
                    $cmArr[$cmId][$positionKey] = 0;
                }
            }
        }
        return $cmArr;
    }

    public static function getGradeName($cmArr, $gradeInfo, $wtPercent, $gradeKey) {
        $gradeArr = [];
        if (!$gradeInfo->isEmpty()) {
            foreach ($gradeInfo as $grade) {
                $gradeArr[$grade->grade_name]['start'] = $grade->marks_from;
                $gradeArr[$grade->grade_name]['end'] = $grade->marks_to;
            }
        }
        if (!empty($cmArr)) {
            foreach ($cmArr as $cmId => $cmInfo) {
                $totalWtPercent = !empty($cmInfo[$wtPercent]) ? $cmInfo[$wtPercent] : 0;
                if (!empty($gradeArr)) {
                    foreach ($gradeArr as $letterGrade => $gradeRange) {
                        if ($totalWtPercent == 100) {
                            $cmArr[$cmId][$gradeKey] = "A+";
                        }
                        if ($gradeRange['start'] <= $totalWtPercent && $totalWtPercent < $gradeRange['end']) {
                            $cmArr[$cmId][$gradeKey] = $letterGrade;
                        }
                    }
                }
            }
        }

        return $cmArr;
    }

    public static function requestCourseSatatusSummary($request, $loadView) {
        $courseName = Course::select('name')->where('id', $request->course_id)->first();
        $eventMksWtArr = [];
        //event info
        $eventInfo = MarkingGroup::join('event', 'event.id', '=', 'marking_group.event_id')
                ->join('term', 'term.id', 'marking_group.term_id')
                ->leftJoin('sub_event', 'sub_event.id', 'marking_group.sub_event_id')
                ->leftJoin('sub_sub_event', 'sub_sub_event.id', 'marking_group.sub_sub_event_id')
                ->leftJoin('sub_sub_sub_event', 'sub_sub_sub_event.id', 'marking_group.sub_sub_sub_event_id')
                ->where('marking_group.course_id', $request->course_id)
                ->select('event.event_code as event_name', 'event.id as event_id', 'marking_group.term_id'
                        , 'sub_event.event_code as sub_event_name', 'marking_group.sub_event_id'
                        , 'sub_sub_event.event_code as sub_sub_event_name', 'marking_group.sub_sub_event_id'
                        , 'sub_sub_sub_event.event_code as sub_sub_sub_event_name', 'marking_group.sub_sub_sub_event_id'
                        , 'term.name as term_name')
                ->get();

        if (!$eventInfo->isEmpty()) {
            foreach ($eventInfo as $ev) {
                $eventMksWtArr['event'][$ev->term_id]['name'] = $ev->term_name ?? '';
                $eventMksWtArr['event'][$ev->term_id][$ev->event_id]['name'] = $ev->event_name ?? '';
                $eventMksWtArr['event'][$ev->term_id][$ev->event_id][$ev->sub_event_id]['name'] = $ev->sub_event_name ?? '';
                $eventMksWtArr['event'][$ev->term_id][$ev->event_id][$ev->sub_event_id][$ev->sub_sub_event_id]['name'] = $ev->sub_sub_event_name ?? '';
                $eventMksWtArr['event'][$ev->term_id][$ev->event_id][$ev->sub_event_id][$ev->sub_sub_event_id][$ev->sub_sub_sub_event_id]['name'] = $ev->sub_sub_sub_event_name ?? '';
            }
        }

        $totalMarkingDsInfo = DsMarkingGroup::join('marking_group', 'marking_group.id', 'ds_marking_group.marking_group_id')
                ->select('marking_group.term_id', 'marking_group.event_id', 'marking_group.sub_event_id', 'marking_group.sub_sub_event_id'
                        , 'marking_group.sub_sub_sub_event_id', DB::raw("COUNT(ds_marking_group.ds_id) as ds_id"))
                ->where('course_id', $request->course_id)
                ->groupBy('marking_group.term_id', 'marking_group.event_id', 'marking_group.sub_event_id', 'marking_group.sub_sub_event_id'
                        , 'marking_group.sub_sub_sub_event_id')
                ->get();
        $totalDsArr = $totalLockedDsArr = $rowSpanArr = [];


        $totalLockedDsInfo = EventAssessmentMarkingLock::select('term_id', 'event_id', 'sub_event_id', 'sub_sub_event_id'
                        , 'sub_sub_sub_event_id', DB::raw("COUNT(locked_by) as locked_ds"))
                ->where('course_id', $request->course_id)
                ->groupBy('term_id', 'event_id', 'sub_event_id', 'sub_sub_event_id'
                        , 'sub_sub_sub_event_id')
                ->get();

        if (!$totalLockedDsInfo->isEmpty()) {
            foreach ($totalLockedDsInfo as $lockInfo) {
                $eventMksWtArr['mks_wt'][$lockInfo->term_id][$lockInfo->event_id][$lockInfo->sub_event_id][$lockInfo->sub_sub_event_id][$lockInfo->sub_sub_sub_event_id]['forwarded'] = $lockInfo->locked_ds;
            }
        }
        if (!$totalMarkingDsInfo->isEmpty()) {
            foreach ($totalMarkingDsInfo as $dsInfo) {
                $forwarded = !empty($eventMksWtArr['mks_wt'][$dsInfo->term_id][$dsInfo->event_id][$dsInfo->sub_event_id][$dsInfo->sub_sub_event_id][$dsInfo->sub_sub_sub_event_id]['forwarded']) ? $eventMksWtArr['mks_wt'][$dsInfo->term_id][$dsInfo->event_id][$dsInfo->sub_event_id][$dsInfo->sub_sub_event_id][$dsInfo->sub_sub_sub_event_id]['forwarded'] : 0;

                $eventMksWtArr['mks_wt'][$dsInfo->term_id][$dsInfo->event_id][$dsInfo->sub_event_id][$dsInfo->sub_sub_event_id][$dsInfo->sub_sub_sub_event_id]['total'] = $dsInfo->ds_id;
                $eventMksWtArr['mks_wt'][$dsInfo->term_id][$dsInfo->event_id][$dsInfo->sub_event_id][$dsInfo->sub_sub_event_id][$dsInfo->sub_sub_sub_event_id]['not_forwarded'] = $dsInfo->ds_id - $forwarded;
            }
        }
        if (!empty($eventMksWtArr['mks_wt'])) {
            foreach ($eventMksWtArr['mks_wt'] as $termId => $evMksWtInfo) {
                foreach ($evMksWtInfo as $eventId => $evInfo) {
                    foreach ($evInfo as $subEventId => $subEvInfo) {
                        foreach ($subEvInfo as $subSubEventId => $subSubEvInfo) {
                            foreach ($subSubEvInfo as $subSubSubEventId => $subSubSubEvInfo) {

                                $rowSpanArr['event'][$termId][$eventId] = !empty($rowSpanArr['event'][$termId][$eventId]) ? $rowSpanArr['event'][$termId][$eventId] : 0;
                                $rowSpanArr['event'][$termId][$eventId] += 1;

                                $rowSpanArr['sub_event'][$termId][$eventId][$subEventId] = !empty($rowSpanArr['sub_event'][$termId][$eventId][$subEventId]) ? $rowSpanArr['sub_event'][$termId][$eventId][$subEventId] : 0;
                                $rowSpanArr['sub_event'][$termId][$eventId][$subEventId] += 1;

                                $rowSpanArr['sub_sub_event'][$termId][$eventId][$subEventId][$subSubEventId] = !empty($rowSpanArr['sub_sub_event'][$termId][$eventId][$subEventId][$subSubEventId]) ? $rowSpanArr['sub_sub_event'][$termId][$eventId][$subEventId][$subSubEventId] : 0;
                                $rowSpanArr['sub_sub_event'][$termId][$eventId][$subEventId][$subSubEventId] += 1;

                                $rowSpanArr['sub_sub_sub_event'][$termId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId] = !empty($rowSpanArr['sub_sub_sub_event'][$termId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId]) ? $rowSpanArr['sub_sub_sub_event'][$termId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId] : 0;
                                $rowSpanArr['sub_sub_sub_event'][$termId][$eventId][$subEventId][$subSubEventId][$subSubSubEventId] += 1;
                            }
                        }
                    }
                }
            }
        }
//        echo '<pre>';
//        print_r($eventMksWtArr['mks_wt']);
//        exit;
        $html = view($loadView, compact('courseName', 'eventMksWtArr', 'rowSpanArr'))->render();

        return response()->json(['html' => $html]);
    }

    public static function getDsMarkingSummary($request, $loadView) {
//        echo '<pre>';        print_r($request->all()); exit;
        $activeTrainingYearInfo = TrainingYear::where('status', '1')->first();
        $course = Course::where('id', $request->course_id)->first();
        $term = Term::where('id', $request->term_id)->first();
        $event = Event::where('id', $request->event_id)->first();
        $subEvent = SubEvent::where('id', $request->sub_event_id)->first();
        $subSubEvent = SubSubEvent::where('id', $request->sub_sub_event_id)->first();
        $subSubSubEvent = SubSubSubEvent::where('id', $request->sub_sub_sub_event_id)->first();


        $dsDataInfo = DsMarkingGroup::join('marking_group', 'marking_group.id', 'ds_marking_group.marking_group_id')
                ->join('users', 'users.id', 'ds_marking_group.ds_id')->join('rank', 'rank.id', 'users.rank_id')
                ->leftJoin('appointment', 'appointment.id', 'ds_marking_group.ds_appt_id')
                ->where('marking_group.course_id', $request->course_id)
                ->where('marking_group.term_id', $request->term_id)
                ->where('marking_group.event_id', $request->event_id);
        if (!empty($request->sub_event_id)) {
            $dsDataInfo = $dsDataInfo->where('marking_group.sub_event_id', $request->sub_event_id);
        }
        if (!empty($request->sub_sub_event_id)) {
            $dsDataInfo = $dsDataInfo->where('marking_group.sub_sub_event_id', $request->sub_sub_event_id);
        }
        if (!empty($request->sub_sub_sub_event_id)) {
            $dsDataInfo = $dsDataInfo->where('marking_group.sub_sub_sub_event_id', $request->sub_sub_sub_event_id);
        }

        $dsDataInfo = $dsDataInfo->select('appointment.name as appt', 'users.id as ds_id', 'users.photo'
                        , DB::raw("CONCAT(rank.code, ' ', users.full_name) as ds_name"), 'users.personal_no')
                ->get();

        $dsDataList = [];
        if (!$dsDataInfo->isEmpty()) {
            foreach ($dsDataInfo as $ds) {
                $dsDataList[$ds->ds_id] = $ds->toArray();
            }
        }
        // assessment marking data
        $eventAssessmentMarkingInfo = EventAssessmentMarking::join('grading_system', 'grading_system.id', 'event_assessment_marking.grade_id')
                ->where('event_assessment_marking.course_id', $request->course_id)
                ->where('event_assessment_marking.term_id', $request->term_id)
                ->where('event_assessment_marking.event_id', $request->event_id);

        if (!empty($request->sub_event_id)) {
            $eventAssessmentMarkingInfo = $eventAssessmentMarkingInfo->where('event_assessment_marking.sub_event_id', $request->sub_event_id);
        }
        if (!empty($request->sub_sub_event_id)) {
            $eventAssessmentMarkingInfo = $eventAssessmentMarkingInfo->where('event_assessment_marking.sub_sub_event_id', $request->sub_sub_event_id);
        }
        if (!empty($request->sub_sub_sub_event_id)) {
            $eventAssessmentMarkingInfo = $eventAssessmentMarkingInfo->where('event_assessment_marking.sub_sub_sub_event_id', $request->sub_sub_sub_event_id);
        }
        $eventAssessmentMarkingInfo = $eventAssessmentMarkingInfo->pluck('event_assessment_marking.updated_by', 'event_assessment_marking.updated_by')
                ->toArray();
        //Lock Table
        $eventAssessmentMarkingLockInfo = EventAssessmentMarkingLock::where('course_id', $request->course_id)
                ->where('term_id', $request->term_id)
                ->where('event_id', $request->event_id);
        if (!empty($request->sub_event_id)) {
            $eventAssessmentMarkingLockInfo = $eventAssessmentMarkingLockInfo->where('sub_event_id', $request->sub_event_id);
        }
        if (!empty($request->sub_sub_event_id)) {
            $eventAssessmentMarkingLockInfo = $eventAssessmentMarkingLockInfo->where('sub_sub_event_id', $request->sub_sub_event_id);
        }
        if (!empty($request->sub_sub_sub_event_id)) {
            $eventAssessmentMarkingLockInfo = $eventAssessmentMarkingLockInfo->where('sub_sub_sub_event_id', $request->sub_sub_sub_event_id);
        }
        $eventAssessmentMarkingLockInfo = $eventAssessmentMarkingLockInfo->pluck('locked_by', 'locked_by')->toArray();


//        echo '<pre>';
//        print_r($dsDataList);
//        exit;

        $view = view($loadView, compact('activeTrainingYearInfo'
                        , 'course', 'term', 'event', 'subEvent', 'subSubEvent', 'subSubSubEvent'
                        , 'dsDataList', 'eventAssessmentMarkingLockInfo', 'eventAssessmentMarkingInfo'))->render();
        return response()->json(['html' => $view]);
    }

    public static function getOrganizationType($flagType=0) {
        
        if ($flagType == 1) {
            $organizationList = [
                '1' => 'Unit',
                '2' => 'Formation',
                '3' => 'Institute',
            ];
        } else {
            $organizationList = [
                '1' => 'Corps',
                '2' => 'Regt',
                '3' => 'Br',
            ];
        }

        return $organizationList;
    }
    public static function getCommissionType() {
        $commissionTypeList = [
                '1' => 'Regular',
                '2' => 'Permanent',
            ];

        return $commissionTypeList;
    }
    public static function getBloodGroup() {
        $bloodGroupList = [
                '1' => 'A (+ve)',
                '2' => 'A (-ve)',
                '3' => 'B (+ve)',
                '4' => 'B (-ve)',
                '5' => 'AB (+ve)',
                '6' => 'AB (-ve)',
                '7' => 'O (+ve)',
                '8' => 'O (-ve)',
            ];

        return $bloodGroupList;
    }

}
