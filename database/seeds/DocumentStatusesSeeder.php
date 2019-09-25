<?php

use Illuminate\Database\Seeder;
use App\Models\Foodfleet\DocumentStatus;
use App\Enums\DocumentStatus as DocumentStatusEmums;


class DocumentStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = DocumentStatusEmums::toKeyedSelectArray();

        foreach($statuses as $id => $name) {
            DocumentStatus::updateOrCreate(
                ['id' => $id],
                ['name' => $name]
            );
        }
    }
}
