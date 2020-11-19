<?php

namespace App\Tests\Unit\Database\Seeds;

use App\Enums\DocumentTemplateStatus;
use DocumentTemplateSeeder;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    public function testTemplateSeeder()
    {
        // DatabaseSeeder is supposed to call DocumentTemplateSeeder like below
        $this->seed(DocumentTemplateSeeder::class);

        $this->assertDatabaseHas('document_templates', [
            'title' => 'Client event agreement',
            'status_id' => DocumentTemplateStatus::PUBLISHED
        ]);
    }
}
