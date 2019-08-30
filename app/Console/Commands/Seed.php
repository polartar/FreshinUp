<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\InputOption;

class Seed extends Command
{
    protected $name = 'foodfleet:seed';
    protected $description = 'FoodFleet Seed the Database';

    public function handle()
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $this->error('Could not connect to the database, please check your configuration:' . "\n" . $e);

            return;
        }

        if ($this->hasOption('refresh')) {
            $this->call('migrate:refresh', [
                '--force' => $this->hasOption('force')
            ]);
            $this->call('passport:install', [
                '--force' => $this->hasOption('force')
            ]);
        }

        $this->call('fresh-bus:seed', [
            '--quickstart' =>  $this->hasOption('quickstart')
        ]);

        $this->call('db:seed', [
            '--class' => 'DatabaseSeeder',
            '--force' => $this->hasOption('force')
        ]);

        if ($this->option('quickstart')) {
            $this->call('db:seed', [
                '--class' => 'TestDataSeeder',
                '--force' => $this->hasOption('force')
            ]);
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
                'quickstart',
                null,
                InputOption::VALUE_NONE,
                'Load sample users, companies, teams, inventory, etc. Helpful for local dev and staging environments'
            ],
            [
                'refresh',
                null,
                InputOption::VALUE_NONE,
                'Wipe out existing data and reseed.'
            ],
            [
                'force',
                null,
                InputOption::VALUE_NONE,
                'Force the database change without requesting user interaction / acceptance'
            ]
        ];
    }
}
