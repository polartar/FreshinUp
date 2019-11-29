<?php
use Illuminate\Database\Seeder;
use App\Models\Foodfleet\StoreStatus;
use App\Enums\StoreStatus as StoreStatusEmums;
class StoreStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = StoreStatusEmums::toKeyedSelectArray();
        foreach($statuses as $id => $name) {
            StoreStatus::updateOrCreate(
                ['id' => $id],
                ['name' => $name]
            );
        }
    }
}
