<?php

use App\User;
use App\Models\Foodfleet\Document;
use Illuminate\Database\Seeder;

class DocumentsTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = User::get();

        for ($i = 0; $i < 50; $i++) {
            $event = factory(Document::class)->create([
                'created_by_uuid' => $users->random()->uuid
            ]);
        }
    }
}

