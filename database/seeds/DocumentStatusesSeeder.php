<?php

use Illuminate\Database\Seeder;
use App\Models\Foodfleet\DocumentStatus;

class DocumentStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            1 => 'Pending',
            2 => 'Approved',
            3 => 'Rejected',
            4 => 'Expiring',
            5 => 'Expired',
        ];

        foreach($statuses as $id => $name) {
            DocumentStatus::updateOrCreate(
                ['id' => $id],
                ['name' => $name]
            );
        }
    }
}
