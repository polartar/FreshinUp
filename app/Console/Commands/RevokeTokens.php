<?php

namespace App\Console\Commands;

use App\Models\Foodfleet\Company;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use App\Jobs\RevokeToken as RevokeTokenJob;

class RevokeTokens extends Command
{
    protected $signature = 'revoke:square {--supplier=}';
    protected $description = 'Square revoke';

    public function handle()
    {
        if ($this->option('supplier')) {
            $companies = collect([Company::findOrFail($this->option('supplier'))]);
        } else {
            // Set event date range in order to check also event with start and end dates in different time zones
            $yesterdayDate = Carbon::yesterday()->startOfDay()->toDateTimeString();
            $companies = Company::whereNotNull('square_access_token')
                ->whereHas('stores', function ($q) use ($yesterdayDate) {
                    $q->whereDoesntHave('events', function ($q) use ($yesterdayDate) {
                        $q->where('end_at', '>=', $yesterdayDate);
                    });
                })->get();
        }

        foreach ($companies as $company) {
            RevokeTokenJob::dispatch($company);
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
                'supplier',
                null,
                InputOption::VALUE_OPTIONAL,
                'Revoke square token for a specified supplier'
            ]
        ];
    }
}
