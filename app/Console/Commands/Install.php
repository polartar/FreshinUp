<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use FoodFleet\Seeds\DatabaseSeeder;
use FoodFleet\Seeds\TestDataSeeder;
use Symfony\Component\Console\Input\InputOption;

class Install extends Command
{
    protected $name = 'foodfleet:install';
    protected $description = 'Install FoodFleet';

    public function handle()
    {
        $this->call('fresh-bus:update', ['--dev' => $this->option('dev')]);
        $this->call('fresh-bus:seed', ['--quickstart' => $this->option('dev')]);
        $this->call('db:seed', ['--class' => DatabaseSeeder::class]);
        if ($this->option('dev')) {
            $this->call('db:seed', ['--class' => TestDataSeeder::class]);
        }
        $this->call('passport:install', ['--force' => true]);
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
            ['dev', null, InputOption::VALUE_NONE, 'Developing with FreshBus. Usually used when symlinking'],
        ];
    }
}
