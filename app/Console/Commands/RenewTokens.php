<?php

namespace App\Console\Commands;

use App\Models\Foodfleet\Company;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use App\Jobs\RenewToken as RenewTokenJob;

class RenewTokens extends Command
{
    protected $signature = 'renew:square {--supplier=}';
    protected $description = 'Square import';

    public function handle()
    {
        if ($this->option('supplier')) {
            $companies = collect([Company::findOrFail($this->option('supplier'))]);
        } else {
            $companies = Company::whereHas('company_types', function ($q) {
                $q->where('key_id', 'supplier');
            })->whereNotNull('square_refresh_token')->get();
        }

        foreach ($companies as $company) {
            RenewTokenJob::dispatch($company);
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
                InputOption::VALUE_NONE,
                'Renew square token for a specified supplier'
            ]
        ];
    }
}
