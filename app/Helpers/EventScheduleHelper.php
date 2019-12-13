<?php

namespace App\Helpers;

use App\Models\Foodfleet\EventSchedule;

class EventScheduleHelper
{
    public static function fakeAnalyzeSchedule(EventSchedule $schedule)
    {
        $interval_unit = $schedule->interval_unit;
        $interval_value = $schedule->interval_value;
        $occurrences = $schedule->occurrences;
        $ends_on = $schedule->ends_on;
        $repeat_on = $schedule->repeat_on;

        $periods = array();
        $periods[] = (object) ['start_at' => date('Y-m-d H:i:s', strtotime('-24 hours')),
            'end_at' => date('Y-m-d H:i:s', strtotime('24 hours'))];
        $periods[] = (object) ['start_at' => date('Y-m-d H:i:s', strtotime('24 hours')),
            'end_at' => date('Y-m-d H:i:s', strtotime('48 hours'))];
        $periods[] = (object) ['start_at' => date('Y-m-d H:i:s', strtotime('48 hours')),
            'end_at' => date('Y-m-d H:i:s', strtotime('72 hours'))];
        return $periods;
    }

    public static function analyzeSchedule(EventSchedule $schedule, $event_start_at, $event_end_at)
    {
        $interval_unit = $schedule->interval_unit;
        $interval_value = $schedule->interval_value;
        $occurrences = $schedule->occurrences;
        $ends_on = $schedule->ends_on;
        $repeat_on = json_decode($schedule->repeat_on, true);

        $event_start_at = date('Y-m-d H:i:s', strtotime($event_start_at));
        $event_end_at = date('Y-m-d H:i:s', strtotime($event_end_at));

        $periods = array();
        $temp_occurrences = 0;
        $ends_on_times = 100;
        $one_day_offset = '+23 hours 59 minutes 59 seconds';
        $first_monday_of_next_month = 'first monday of next month';
        $first_day_of_next_month = 'first day of next month';

        if ($interval_unit == 'Year(s)') {
            if ($ends_on == 'on') {
                $temp_occurrences = $ends_on_times;
            } else {
                $temp_occurrences = $occurrences;
            }

            foreach (range(0, $temp_occurrences - 1) as $number) {
                $start_offset_index = $number * $interval_value;
                $start_offset = $start_offset_index . ' year';
                if ($start_offset_index == 0) {
                    $start_offset = 'now';
                }

                $start_at = date(
                    'Y-m-d H:i:s',
                    strtotime($start_offset, strtotime($event_start_at))
                );

                if ($start_at > $event_end_at) {
                    break;
                }
                $end_at = date(
                    'Y-m-d H:i:s',
                    strtotime($one_day_offset, strtotime($start_at))
                );
                $periods[] = (object) ['start_at' => $start_at, 'end_at' => $end_at];
            }
        } elseif ($interval_unit == 'Month(s)') {
            if ($ends_on == 'on') {
                $temp_occurrences = $ends_on_times;
            } else {
                $temp_occurrences = $occurrences;
            }

            foreach (range(0, $temp_occurrences - 1) as $number) {
                $start_offset_index = $number * $interval_value;
                $start_offset = $start_offset_index . ' month';

                $start_at = $event_start_at;
                if ($start_offset_index == 0) {
                    if ($repeat_on[0]['id'] == 1) {
                        $start_at = date(
                            'Y-m-d H:i:s',
                            strtotime($first_monday_of_next_month, strtotime($event_start_at))
                        );
                    } else {
                        $first_day_of_next_month_at = date(
                            'Y-m-d H:i:s',
                            strtotime($first_day_of_next_month, strtotime($event_start_at))
                        );

                        $start_at = date(
                            'Y-m-d H:i:s',
                            strtotime('+1 day', strtotime($first_day_of_next_month_at))
                        );
                    }
                }

                if ($start_offset_index >= 1) {
                    if ($repeat_on[0]['id'] == 1) {
                        $start_at = date(
                            'Y-m-d H:i:s',
                            strtotime($start_offset, strtotime($event_start_at))
                        );
                        $start_at = date(
                            'Y-m-d H:i:s',
                            strtotime($first_monday_of_next_month, strtotime($start_at))
                        );
                    } else {
                        $start_at = date(
                            'Y-m-d H:i:s',
                            strtotime($start_offset, strtotime($event_start_at))
                        );
                        $first_day_of_next_month_at = date(
                            'Y-m-d H:i:s',
                            strtotime($first_day_of_next_month, strtotime($start_at))
                        );
                        $start_at = date(
                            'Y-m-d H:i:s',
                            strtotime('+1 day', strtotime($first_day_of_next_month_at))
                        );
                    }
                }

                if ($start_at > $event_end_at) {
                    break;
                }
                $end_at = date('Y-m-d H:i:s', strtotime($one_day_offset, strtotime($start_at)));
                $periods[] = (object) ['start_at' => $start_at, 'end_at' => $end_at];
            }
        } else {
            if ($ends_on == 'on') {
                $temp_occurrences = $ends_on_times;
            } else {
                $temp_occurrences = $occurrences;
            }

            foreach (range(0, $temp_occurrences - 1) as $number) {
                $start_offset_index = $number * $interval_value;
                foreach ($repeat_on as $value) {
                    $start_offset = 'now';
                    switch ($value['id']) {
                        case 1:
                            $start_offset = 'this sunday ';
                            break;
                        case 2:
                            $start_offset = 'next monday ';
                            break;
                        case 3:
                            $start_offset = 'next tuesday ';
                            break;
                        case 4:
                            $start_offset = 'next wednesday ';
                            break;
                        case 5:
                            $start_offset = 'next thursday ';
                            break;
                        case 6:
                            $start_offset = 'next friday ';
                            break;
                        case 7:
                            $start_offset = 'next friday '; // bug: strtotime always return last saturday date
                            break;
                        default:
                            break;
                    }
                    if ($start_offset_index > 0) {
                        $start_offset = $start_offset . $start_offset_index . ' week';
                    }

                    $start_at = date('Y-m-d H:i:s', strtotime($start_offset, strtotime($event_start_at)));
                    if ($value['id'] == 7) {
                        $start_at = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($start_at)));
                    }

                    if ($start_at > $event_end_at) {
                        break 2;
                    }
                    $end_at = date('Y-m-d H:i:s', strtotime($one_day_offset, strtotime($start_at)));
                    $periods[] = (object) ['start_at' => $start_at, 'end_at' => $end_at];
                }
            }
        }
        return $periods;
    }
}
