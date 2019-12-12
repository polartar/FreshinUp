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

    public static function analyzeSchedule(EventSchedule $schedule)
    {
        $interval_unit = $schedule->interval_unit;
        $interval_value = $schedule->interval_value;
        $occurrences = $schedule->occurrences;
        $ends_on = $schedule->ends_on;
        $repeat_on = $schedule->repeat_on;

        $periods = array();
        if ($interval_unit == 'Year(s)') {
            if ($ends_on == 'on') {
                $end_offset_index = 1 * $interval_value;
                $end_offset = $end_offset_index . ' year';
                if ($end_offset_index > 1) {
                    $end_offset = $end_offset_index . ' years';
                }
                $periods[] = (object) [
                    'start_at' => date('Y-m-d H:i:s'),
                    'end_at' => date('Y-m-d H:i:s', strtotime($end_offset))
                ];
            } else {
                foreach (range(0, $occurrences - 1) as $number) {
                    $start_offset_index = $number * $interval_value;
                    $end_offset_index = ($number + 1) * $interval_value;
                    $start_offset = $start_offset_index . ' year';
                    $end_offset = $end_offset_index . ' year';
                    if ($start_offset_index == 0) {
                        $start_offset = 'now';
                    } elseif ($start_offset_index > 1) {
                        $start_offset = $start_offset_index . ' years';
                    }
                    if ($end_offset_index > 1) {
                        $end_offset = $end_offset_index . ' years';
                    }
                    $start_at = date('Y-m-d H:i:s', strtotime($start_offset));
                    $end_at = date('Y-m-d H:i:s', strtotime($end_offset));
                    $periods[] = (object) ['start_at' => $start_at, 'end_at' => $end_at];
                }
            }
        } elseif ($interval_unit == 'Month(s)') {
            if ($ends_on == 'on') {
                $start_at = date('Y-m-d H:i:s', strtotime('first monday of next month'));
                if ($repeat_on[0]['id'] == 2) {
                    $start_at = date('Y-m-d H:i:s', strtotime('second day of next month'));
                }
                $end_offset_index = 1 * $interval_value;
                $end_offset = $end_offset_index . ' month';
                if ($end_offset_index > 1) {
                    $end_offset = $end_offset_index . ' months';
                }
                $end_at = date('Y-m-d H:i:s', strtotime($end_offset, strtotime($start_at)));
                $periods[] = (object) ['start_at' => $start_at, 'end_at' => $end_at];
            } else {
                $temp_end_at = date('Y-m-d H:i:s');
                foreach (range(0, $occurrences - 1) as $number) {
                    $start_offset = 'first monday of next month';
                    $start_at = date('Y-m-d H:i:s', strtotime($start_offset));
                    
                    $start_offset_index = $number * $interval_value;
                    $end_offset_index = ($number + 1) * $interval_value;
                    $start_offset = $start_offset_index . ' month';
                    $end_offset = $end_offset_index . ' month';

                    if ($start_offset_index == 0 && $repeat_on[0]['id'] == 2) {
                        $start_offset = 'second day of next month';
                        $start_at = date('Y-m-d H:i:s', strtotime($start_offset));
                    } elseif ($start_offset_index > 1) {
                        $start_offset = $start_offset_index . ' months';
                    }

                    if ($end_offset_index > 1) {
                        $end_offset = $end_offset_index . ' months';
                    }

                    if ($start_offset_index >= 1) {
                        $start_at = date('Y-m-d H:i:s', strtotime($start_offset, strtotime($temp_end_at)));
                    }
                    $end_at = date('Y-m-d H:i:s', strtotime($end_offset, strtotime($start_at)));
                    $periods[] = (object) ['start_at' => $start_at, 'end_at' => $end_at];
                    $temp_end_at = $end_at;
                }
            }
        } else {
            if ($ends_on == 'on') {
                foreach ($repeat_on as $value) {
                    $start_offset = 'now';
                    $end_offset = '+23 hours 59 minutes 59 seconds';
                    switch ($value->id) {
                        case 1:
                            $start_offset = 'this sunday';
                            break;
                        case 2:
                            $start_offset = 'next monday';
                            break;
                        case 3:
                            $start_offset = 'next tuesday';
                            break;
                        case 4:
                            $start_offset = 'next wednesday';
                            break;
                        case 5:
                            $start_offset = 'next thursday';
                            break;
                        case 6:
                            $start_offset = 'next friday';
                            break;
                        case 7:
                            $start_offset = 'next saturday';
                            break;
                        default:
                            break;
                    }
                    $start_at = date('Y-m-d H:i:s', strtotime($start_offset));
                    $end_at = date('Y-m-d H:i:s', strtotime($end_offset, strtotime($start_at)));
                    $periods[] = (object) ['start_at' => $start_at, 'end_at' => $end_at];
                }
            } else {
                foreach (range(0, $occurrences - 1) as $number) {
                    $start_offset_index = $number * $interval_value * 7;
                    foreach ($repeat_on as $value) {
                        $start_offset = 'now';
                        $end_offset = '+23 hours 59 minutes 59 seconds';
                        switch ($value->id) {
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
                                $start_offset = 'next saturday ';
                                break;
                            default:
                                break;
                        }
                        if ($start_offset_index > 0) {
                            $start_offset = $start_offset . $start_offset_index . ' day';
                        }
                        $start_at = date('Y-m-d H:i:s', strtotime($start_offset));
                        $end_at = date('Y-m-d H:i:s', strtotime($end_offset, strtotime($start_at)));
                        $periods[] = (object) ['start_at' => $start_at, 'end_at' => $end_at];
                    }
                }
            }
        }
        return $periods;
    }
}
