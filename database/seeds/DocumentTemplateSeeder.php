<?php

use App\Models\Foodfleet\Document\Template\Status;
use App\Models\Foodfleet\Document\Template\Template;
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
        Template::getClientAgreement();

        $statuses = Status::get();
        $users = User::get();
        if (Template::count() > 15) {
            return;
        }
        if ($users->count() == 0) {
            return;
        }
        if ($statuses->count() == 0) {
            return;
        }
        for ($i = 0; $i < 15; $i++) {
            factory(App\Models\Foodfleet\Document\Template\Template::class)->create([
                'status_id' => $statuses->random()->id,
                'updated_by_uuid' => $users->random()->uuid
            ]);
        }
    }
}
