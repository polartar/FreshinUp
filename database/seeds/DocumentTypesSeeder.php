<?php

use Illuminate\Database\Seeder;
use App\Models\Foodfleet\DocumentType;
use App\Enums\DocumentTypes as DocumentTypesEmums;

class DocumentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = DocumentTypesEmums::toKeyedSelectArray();

        foreach($types as $id => $name) {
            DocumentType::updateOrCreate(
                ['id' => $id],
                ['name' => $name]
            );
        }
    }
}
