<?php

use App\User;
use App\Models\Foodfleet\Document;
use App\Models\Foodfleet\Store;
use App\Models\Foodfleet\Event;

use App\Models\Foodfleet\DocumentType;
use App\Models\Foodfleet\DocumentStatus;
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
        $stores = Store::get();
        $events = Event::get();

        $types = DocumentType::get();
        $status = DocumentStatus::get();

        $assignCollection = $users->concat($stores)->concat($events);

        for ($i = 0; $i < 50; $i++) {
            $document = factory(Document::class)->create([
                'type_id' => $types->random()->id,
                'status_id' => $status->random()->id,
                'created_by_uuid' => $users->random()->uuid
            ]);

            $assignCollection->random()->documents()->save($document);
        }
    }
}

