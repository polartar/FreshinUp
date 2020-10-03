<?php

use App\Enums\DocumentTemplateStatus;
use App\Models\Foodfleet\Document\Template\Status;
use Illuminate\Database\Seeder;

class DocumentTemplateStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = DocumentTemplateStatus::toKeyedSelectArray();
        foreach ($statuses as $id => $name) {
            Status::updateOrCreate(
                ['id' => $id],
                ['name' => $name]
            );
        }
    }
}
