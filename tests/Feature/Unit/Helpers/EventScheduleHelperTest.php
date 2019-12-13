<?php

namespace Tests\Feature\Unit\Helpers\EventScheduleHelper;

use App\Helpers\EventScheduleHelper;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventSchedule;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventScheduleHelperTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    // About Year(s) tests
    public function testAnalyzeScheduleWithOnYears()
    {
        $event_start_at = '2019-07-12 08:31:00';
        $event_end_at = '2030-07-12 23:59:59';

        $event = factory(Event::class)->create();
        $schedule = factory(EventSchedule::class)->create([
            'event_uuid' => $event->uuid,
            'interval_unit' => 'Year(s)',
            'interval_value' => 2,
            'ends_on' => 'on'
        ]);

        $periods = EventScheduleHelper::analyzeSchedule($schedule, $event_start_at, $event_end_at);

        $this->assertEquals('2019-07-12 08:31:00', $periods[0]->start_at);
        $this->assertEquals('2019-07-13 08:30:59', $periods[0]->end_at);

        $this->assertEquals('2021-07-12 08:31:00', $periods[1]->start_at);
        $this->assertEquals('2021-07-13 08:30:59', $periods[1]->end_at);

        $this->assertEquals('2023-07-12 08:31:00', $periods[2]->start_at);
        $this->assertEquals('2023-07-13 08:30:59', $periods[2]->end_at);

        $this->assertEquals('2025-07-12 08:31:00', $periods[3]->start_at);
        $this->assertEquals('2025-07-13 08:30:59', $periods[3]->end_at);

        $this->assertEquals('2027-07-12 08:31:00', $periods[4]->start_at);
        $this->assertEquals('2027-07-13 08:30:59', $periods[4]->end_at);

        $this->assertEquals('2029-07-12 08:31:00', $periods[5]->start_at);
        $this->assertEquals('2029-07-13 08:30:59', $periods[5]->end_at);
    }

    public function testAnalyzeScheduleWithAfterYears()
    {
        $event_start_at = '2019-07-12 08:31:00';
        $event_end_at = '2030-07-12 23:59:59';

        $event = factory(Event::class)->create();
        $schedule = factory(EventSchedule::class)->create([
            'event_uuid' => $event->uuid,
            'interval_unit' => 'Year(s)',
            'interval_value' => 2,
            'ends_on' => 'after',
            "occurrences" => 2
        ]);

        $periods = EventScheduleHelper::analyzeSchedule($schedule, $event_start_at, $event_end_at);

        $this->assertEquals('2019-07-12 08:31:00', $periods[0]->start_at);
        $this->assertEquals('2019-07-13 08:30:59', $periods[0]->end_at);

        $this->assertEquals('2021-07-12 08:31:00', $periods[1]->start_at);
        $this->assertEquals('2021-07-13 08:30:59', $periods[1]->end_at);
    }

    // About Month(s) tests
    public function testAnalyzeScheduleWithOnMonthsNextFirstMonday()
    {
        $event_start_at = '2019-07-12 08:31:00';
        $event_end_at = '2020-01-10 23:59:59';

        $event = factory(Event::class)->create();
        $repeatOn = array();
        $repeatOn[] = (object) ["id" => 1, "text" => "First Monday on each following month"];
        $schedule = factory(EventSchedule::class)->create([
            'event_uuid' => $event->uuid,
            'interval_unit' => 'Month(s)',
            'interval_value' => 2,
            'ends_on' => 'on',
            'repeat_on' => json_encode($repeatOn)
        ]);

        $periods = EventScheduleHelper::analyzeSchedule($schedule, $event_start_at, $event_end_at);
        
        $this->assertEquals('2019-08-05 00:00:00', $periods[0]->start_at);
        $this->assertEquals('2019-08-05 23:59:59', $periods[0]->end_at);

        $this->assertEquals('2019-10-07 00:00:00', $periods[1]->start_at);
        $this->assertEquals('2019-10-07 23:59:59', $periods[1]->end_at);

        $this->assertEquals('2019-12-02 00:00:00', $periods[2]->start_at);
        $this->assertEquals('2019-12-02 23:59:59', $periods[2]->end_at);
    }

    
    public function testAnalyzeScheduleWithOnMonthsNextSecondDay()
    {
        $event_start_at = '2019-07-12 08:31:00';
        $event_end_at = '2020-01-10 23:59:59';

        $event = factory(Event::class)->create();
        $repeatOn = array();
        $repeatOn[] = (object) ["id" => 2, "text" => "Day 2 on each following month"];
        $schedule = factory(EventSchedule::class)->create([
            'event_uuid' => $event->uuid,
            'interval_unit' => 'Month(s)',
            'interval_value' => 2,
            'ends_on' => 'on',
            'repeat_on' => json_encode($repeatOn)
        ]);

        $periods = EventScheduleHelper::analyzeSchedule($schedule, $event_start_at, $event_end_at);
        
        $this->assertEquals('2019-08-02 08:31:00', $periods[0]->start_at);
        $this->assertEquals('2019-08-03 08:30:59', $periods[0]->end_at);

        $this->assertEquals('2019-10-02 08:31:00', $periods[1]->start_at);
        $this->assertEquals('2019-10-03 08:30:59', $periods[1]->end_at);

        $this->assertEquals('2019-12-02 08:31:00', $periods[2]->start_at);
        $this->assertEquals('2019-12-03 08:30:59', $periods[2]->end_at);
    }

    public function testAnalyzeScheduleWithAfterMonthsNextFirstMonday()
    {
        $event_start_at = '2019-07-12 08:31:00';
        $event_end_at = '2020-01-10 23:59:59';

        $event = factory(Event::class)->create();
        $repeatOn = array();
        $repeatOn[] = (object) ["id" => 1, "text" => "First Monday on each following month"];
        $schedule = factory(EventSchedule::class)->create([
            'event_uuid' => $event->uuid,
            'interval_unit' => 'Month(s)',
            'interval_value' => 2,
            'ends_on' => 'after',
            "occurrences" => 2,
            'repeat_on' => json_encode($repeatOn)
        ]);

        $periods = EventScheduleHelper::analyzeSchedule($schedule, $event_start_at, $event_end_at);
        
        $this->assertEquals('2019-08-05 00:00:00', $periods[0]->start_at);
        $this->assertEquals('2019-08-05 23:59:59', $periods[0]->end_at);

        $this->assertEquals('2019-10-07 00:00:00', $periods[1]->start_at);
        $this->assertEquals('2019-10-07 23:59:59', $periods[1]->end_at);
    }

    public function testAnalyzeScheduleWithAfterMonthsNextSecondDay()
    {
        $event_start_at = '2019-07-12 08:31:00';
        $event_end_at = '2020-01-10 23:59:59';

        $event = factory(Event::class)->create();
        $repeatOn = array();
        $repeatOn[] = (object) ["id" => 2, "text" => "Day 2 on each following month"];
        $schedule = factory(EventSchedule::class)->create([
            'event_uuid' => $event->uuid,
            'interval_unit' => 'Month(s)',
            'interval_value' => 2,
            'ends_on' => 'after',
            "occurrences" => 2,
            'repeat_on' => json_encode($repeatOn)
        ]);

        $periods = EventScheduleHelper::analyzeSchedule($schedule, $event_start_at, $event_end_at);

        $this->assertEquals('2019-08-02 08:31:00', $periods[0]->start_at);
        $this->assertEquals('2019-08-03 08:30:59', $periods[0]->end_at);

        $this->assertEquals('2019-10-02 08:31:00', $periods[1]->start_at);
        $this->assertEquals('2019-10-03 08:30:59', $periods[1]->end_at);
    }

    // About Week(s) tests
    public function testAnalyzeScheduleWithOnWeeks()
    {
        $event_start_at = '2019-07-12 08:31:00';
        $event_end_at = '2019-08-01 23:59:59';

        $event = factory(Event::class)->create();
        $repeatOn = array();
        $repeatOn[] = (object) ["id" => 1, "text" => "Sunday"];
        $repeatOn[] = (object) ["id" => 3, "text" => "Tuesday"];
        $repeatOn[] = (object) ["id" => 7, "text" => "Saturday"];
        $schedule = factory(EventSchedule::class)->create([
            'event_uuid' => $event->uuid,
            'interval_unit' => 'Week(s)',
            'interval_value' => 2,
            'ends_on' => 'on',
            'repeat_on' => json_encode($repeatOn)
        ]);

        $periods = EventScheduleHelper::analyzeSchedule($schedule, $event_start_at, $event_end_at);

        $this->assertEquals('2019-07-14 00:00:00', $periods[0]->start_at);
        $this->assertEquals('2019-07-14 23:59:59', $periods[0]->end_at);

        $this->assertEquals('2019-07-16 00:00:00', $periods[1]->start_at);
        $this->assertEquals('2019-07-16 23:59:59', $periods[1]->end_at);

        $this->assertEquals('2019-07-20 00:00:00', $periods[2]->start_at);
        $this->assertEquals('2019-07-20 23:59:59', $periods[2]->end_at);

        $this->assertEquals('2019-07-28 00:00:00', $periods[3]->start_at);
        $this->assertEquals('2019-07-28 23:59:59', $periods[3]->end_at);

        $this->assertEquals('2019-07-30 00:00:00', $periods[4]->start_at);
        $this->assertEquals('2019-07-30 23:59:59', $periods[4]->end_at);
    }

    public function testAnalyzeScheduleWithAfterWeeks()
    {
        $event_start_at = '2019-07-12 08:31:00';
        $event_end_at = '2019-08-01 23:59:59';

        $event = factory(Event::class)->create();
        $repeatOn = array();
        $repeatOn[] = (object) ["id" => 2, "text" => "Monday"];
        $repeatOn[] = (object) ["id" => 4, "text" => "Wednesday"];
        $schedule = factory(EventSchedule::class)->create([
            'event_uuid' => $event->uuid,
            'interval_unit' => 'Week(s)',
            'interval_value' => 1,
            'ends_on' => 'after',
            "occurrences" => 2,
            'repeat_on' => json_encode($repeatOn)
        ]);

        $periods = EventScheduleHelper::analyzeSchedule($schedule, $event_start_at, $event_end_at);

        $this->assertEquals('2019-07-15 00:00:00', $periods[0]->start_at);
        $this->assertEquals('2019-07-15 23:59:59', $periods[0]->end_at);

        $this->assertEquals('2019-07-17 00:00:00', $periods[1]->start_at);
        $this->assertEquals('2019-07-17 23:59:59', $periods[1]->end_at);

        $this->assertEquals('2019-07-22 00:00:00', $periods[2]->start_at);
        $this->assertEquals('2019-07-22 23:59:59', $periods[2]->end_at);

        $this->assertEquals('2019-07-24 00:00:00', $periods[3]->start_at);
        $this->assertEquals('2019-07-24 23:59:59', $periods[3]->end_at);
    }
}
