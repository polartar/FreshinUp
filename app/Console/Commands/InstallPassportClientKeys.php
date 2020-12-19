<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InstallPassportClientKeys extends Command
{
    protected $name = "passport-client:update-env";

    protected $description = "Install the Password Passport Client on .env file.";

    public function handle()
    {
        $name = config('app.name') . ' Password Grant Client';

        $this->call("passport:client", [
                '--password' => true,
                '--name' => $name
        ]);

        $client = DB::table('oauth_clients')->where('name', $name)->latest()->first();

        $id = $client->id;
        $secret = $client->secret;

        $file = ".env";

        if (app()->environment('testing')) {
            config(['services.vue_client.id' => (int) $id]);
            config(['services.vue_client.secret' => $secret]);
            //honestly embarrassed I'm putting this section of code here
        }

        $this->call("env:set", [
            'key' => 'VUE_CLIENT_ID',
            'value' => (int) $id,
            'env_file' => $file
        ]);

        $this->call("env:set", [
            'key' => 'VUE_CLIENT_SECRET',
            'value' => $secret,
            'env_file' => $file,
        ]);

        $this->info("Vue SPA password client keys set successfully.");
    }
}
