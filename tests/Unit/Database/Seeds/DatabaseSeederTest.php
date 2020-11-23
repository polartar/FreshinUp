<?php

namespace App\Tests\Unit\Database\Seeds;

use App\Enums\DocumentTemplateStatus;
use App\Models\Foodfleet\Document\Template\Template;
use DocumentTemplateSeeder;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    public function testTemplateSeeder()
    {
        // DatabaseSeeder is supposed to call DocumentTemplateSeeder like below
        $this->seed(DocumentTemplateSeeder::class);

        $this->assertDatabaseHas('document_templates', [
            'title' => Template::CLIENT_EVENT_AGREEMENT,
            'status_id' => DocumentTemplateStatus::PUBLISHED
        ]);
    }
}
