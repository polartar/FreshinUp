<?php

namespace App\Console\Commands;

use App\Models\Foodfleet\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use App\Jobs\ImportSquare as ImportSquareJob;

class ImportSquare extends Command
{
    protected $signature = 'import:square {--event=}';
    protected $description = 'Square import';

    public function handle()
    {
        if ($this->option('event')) {
            $events = collect([Event::findOrFail($this->option('event'))]);
        } else {
            // Set event date range in order to check also event with start and end dates in different time zones
            $yesterdayDate = Carbon::yesterday()->startOfDay()->toDateTimeString();
            $tomorrowDate = Carbon::tomorrow()->endOfDay()->toDateTimeString();
            $events = Event::where(function ($query) use ($yesterdayDate, $tomorrowDate) {
                $query->where('start_at', '>=', $yesterdayDate)
                    ->where('start_at', '<=', $tomorrowDate);
            })
            ->orWhere(function ($query) use ($yesterdayDate, $tomorrowDate) {
                $query->where('end_at', '>=', $yesterdayDate)
                    ->where('end_at', '<=', $tomorrowDate);
            })
            ->get();
        }

        foreach ($events as $event) {
            ImportSquareJob::dispatch($event);
        }
    }

    /*
    **
    * Get the console command options.
    *
    * @return array
    */
    protected function getOptions()
    {
        return [
            [
                'event',
                null,
                InputOption::VALUE_NONE,
                'Import square data for a specific event'
            ]
        ];
    }
}
