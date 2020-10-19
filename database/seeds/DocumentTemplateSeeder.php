<?php

use App\Models\Foodfleet\Document\Template\Status;
use Illuminate\Database\Seeder;
use App\User;

class DocumentTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = Status::get();
        $users = User::get();
        for ($i = 0; $i < 15; $i++) {
            factory(App\Models\Foodfleet\Document\Template\Template::class)->create([
                'status_id' => $statuses->random()->id,
                'updated_by_uuid' => $users->random()->uuid
            ]);
        }
    }
}
