<?php

namespace App\Helpers;

use App\Models\Foodfleet\EventSchedule;

class EventScheduleHelper
{
    public static function analyzeSchedule(EventSchedule $schedule)
    {
        $interval_unit = $schedule->interval_unit;
        $interval_value = $schedule->interval_value;
        $occurrences = $schedule->occurrences;
        $ends_on = $schedule->ends_on;
        $repeat_on = $schedule->repeat_on;

        // TODO: analyze schedule to generate occurrences
        $periods = array();
        $periods[] = (object) [ 'start_at' => date('Y-m-d H:i:s',strtotime('-24 hours')), 'end_at' => date('Y-m-d H:i:s',strtotime('24 hours'))];
        $periods[] = (object) [ 'start_at' => date('Y-m-d H:i:s',strtotime('24 hours')), 'end_at' => date('Y-m-d H:i:s',strtotime('48 hours'))];
        $periods[] = (object) [ 'start_at' => date('Y-m-d H:i:s',strtotime('48 hours')), 'end_at' => date('Y-m-d H:i:s',strtotime('72 hours'))];
        $periods[] = (object) [ 'start_at' => date('Y-m-d H:i:s',strtotime('72 hours')), 'end_at' => date('Y-m-d H:i:s',strtotime('96 hours'))];
        return $periods;
    }
}
