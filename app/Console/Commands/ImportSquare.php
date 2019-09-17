<?php

namespace App\Console\Commands;

use App\Models\Foodfleet\Company;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use App\Jobs\ImportSquare as ImportSquareJob;

class ImportSquare extends Command
{
    protected $signature = 'import:square {--contractor=}';
    protected $description = 'Square import';

    public function handle()
    {
        if ($this->hasOption('contractor')) {
            $companies = Company::where('id', $this->option('contractor'))->get();
        } else {
            $companies = Company::whereHas('company_types', function ($q) {
                $q->where('key_id', 'contractor');
            });
        }

        foreach ($companies as $company) {
            //ImportSquareJob::dispatch($company);
            $job = new ImportSquareJob($company);
            $job->handle();
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
                'contractor',
                null,
                InputOption::VALUE_NONE,
                'Import square data for a specific contractor'
            ]
        ];
    }
}
