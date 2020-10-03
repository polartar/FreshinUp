<?php

use App\Models\Foodfleet\Document\Template\Status;
use Illuminate\Database\Seeder;

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
        for ($i = 0; $i < 15; $i++) {
            factory(App\Models\Foodfleet\Document\Template\Template::class)->create([
                'status_id' => $statuses->random()->id
            ]);
        }
    }
}
