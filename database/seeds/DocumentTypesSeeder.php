<?php

use Illuminate\Database\Seeder;
use App\Models\Foodfleet\DocumentType;

class DocumentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            1 => 'From Template',
            2 => 'Downloadable',
        ];

        foreach($types as $id => $name) {
            DocumentType::updateOrCreate(
                ['id' => $id],
                ['name' => $name]
            );
        }
    }
}
