<?php

namespace Tests\Feature\Unit\Models\Event;

use App\Jobs\ImportSquare;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventTag;
use App\Models\Foodfleet\Store;
use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\Square\Transaction;
use FreshinUp\FreshBusForms\Models\Company\Company;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImportSquareTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testImportWithoutStoresWithSquareId()
    {
        $supplier = factory(Company::class)->create(['name' => 'test']);

        $importJob = new ImportSquare(\App\Models\Foodfleet\Company::find($supplier->id));
        $importJob->handle();

        // Retrieve the records from the Monolog TestHandler
        $records = app('log')
            ->getHandlers()[0]
            ->getRecords();
        $this->assertCount(2, $records);
        $this->assertEquals(
            'Import constructor for supplier test id 1',
            $records[0]['message']
        );
        $this->assertEquals(
            'Start import: 0 stores with square id',
            $records[1]['message']
        );
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testImportWithStoresWithSquareIdButSquareTokenNotSet()
    {
        $company = factory(Company::class)->create(['name' => 'test']);
        $supplier = \App\Models\Foodfleet\Company::find($company->id);
        factory(Store::class)->create(['name' => 'test', 'supplier_uuid' => $supplier->uuid, 'square_id' => 'test']);

        $importJob = new ImportSquare($supplier);
        $importJob->handle();

        // Retrieve the records from the Monolog TestHandler
        $records = app('log')
            ->getHandlers()[0]
            ->getRecords();
        $this->assertCount(4, $records);
        $this->assertEquals(
            'Import constructor for supplier test id 1',
            $records[0]['message']
        );
        $this->assertEquals(
            'Start import: 1 stores with square id',
            $records[1]['message']
        );
        $this->assertEquals(
            'Store test id 1',
            $records[2]['message']
        );
        $this->assertStringContainsString(
            'Message: [HTTP/1.1 400 Bad Request] {"errors": [{"code": "INVALID_FORM_VALUE","detail": ' .
            '"Invalid input supplied for field: location_id","category": "INVALID_REQUEST_ERROR"}]}',
            $records[3]['message']
        );
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testImportWithStoresWithSquareIdAndSquareTokenSetButNotValid()
    {
        $company = factory(Company::class)->create(['name' => 'test', 'square_access_token' => 'test']);
        $supplier = \App\Models\Foodfleet\Company::find($company->id);
        factory(Store::class)->create(['name' => 'test', 'supplier_uuid' => $supplier->uuid, 'square_id' => 'test']);

        $importJob = new ImportSquare($supplier);
        $importJob->handle();

        // Retrieve the records from the Monolog TestHandler
        $records = app('log')
            ->getHandlers()[0]
            ->getRecords();
        $this->assertCount(4, $records);
        $this->assertEquals(
            'Import constructor for supplier test id 1',
            $records[0]['message']
        );
        $this->assertEquals(
            'Start import: 1 stores with square id',
            $records[1]['message']
        );
        $this->assertEquals(
            'Store test id 1',
            $records[2]['message']
        );
        $this->assertStringContainsString(
            'Message: [HTTP/1.1 401 Unauthorized] {"errors": [{"code": "UNAUTHORIZED","detail": ' .
            '"This request could not be authorized.","category": "AUTHENTICATION_ERROR"}]}',
            $records[3]['message']
        );
    }
}
