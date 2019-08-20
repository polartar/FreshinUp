<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class Install extends Command
{
    protected $name = 'foodfleet:install';
    protected $description = 'Install FoodFleet';

    public function handle()
    {
        $this->setupLaravelEnvConfig();
        $this->call('migrate');
        $this->call('passport:install', [
            '--force' => true
        ]);
        $this->call('fresh-bus:update', [
            '--dev' => $this->option('dev')
        ]);
        $this->call('fresh-bus:seed');
        $this->call('csm:seed');
        passthru('yarn install' . ($this->option('dev') ? '' : '--production'));
        passthru('yarn ' . ($this->option('dev') ? 'dev' : 'prod'));
    }

    protected function setupLaravelEnvConfig()
    {
        if (!file_exists('.env')) {
            copy('.env.example', '.env');
            $this->call('key:generate');
        } else {
            $this->info('skipping key:generate and .env creation. .env already exists');
        }

        $envKeys = [
            'DB_PORT' => $this->ask('Database port', env('DB_PORT')),
            'DB_DATABASE' => $this->ask('Database name', env('DB_DATABASE')),
            'DB_HOST' => $this->ask('Database host', env('DB_HOST')),
            'DB_PASSWORD' => $this->ask('Database password', env('DB_PASSWORD')),
            'MAIL_DRIVER' => 'log',
            'APP_URL' => $this->ask('Local url for application', env('APP_URL')),
        ];
        foreach ($envKeys as $key => $value) {
            $this->call('env:set', [
                'key' => $key,
                'value' =>  $value
            ]);
            putenv($key . '=' . $value);
        }
        config(['database.connections.' . env('DB_CONNECTION') . '.port' =>  env('DB_PORT')]);
        config(['database.connections.' . env('DB_CONNECTION') . '.database' =>  env('DB_DATABASE')]);
        config(['database.connections.' . env('DB_CONNECTION') . '.host' =>  env('DB_HOST')]);
        config(['database.connections.' . env('DB_CONNECTION') . '.password' =>  env('DB_PASSWORD')]);
        $this->call('cache:clear');
        $this->call('config:clear');
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
            ['dev', null, InputOption::VALUE_NONE, 'Developing with FreshBus. Usually used when symlinking']
        ];
    }
}
