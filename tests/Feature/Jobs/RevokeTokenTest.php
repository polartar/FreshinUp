<?php

namespace Tests\Feature\Jobs;

use App\Jobs\RevokeToken;
use FreshinUp\FreshBusForms\Models\Company\Company;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RevokeTokenTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRevokeWithoutSquareAccessToken()
    {
        $supplier = factory(Company::class)->create(['name' => 'test']);

        $importJob = new RevokeToken(\App\Models\Foodfleet\Company::find($supplier->id));
        $importJob->handle();

        // Retrieve the records from the Monolog TestHandler
        $records = app('log')
            ->getHandlers()[0]
            ->getRecords();
        $this->assertCount(2, $records);
        $this->assertEquals(
            'Revoke token for supplier test id 1',
            $records[0]['message']
        );
        $this->assertStringContainsString(
            '"message": "one of either \'access_token\' or \'merchant_id\' is required"',
            $records[1]['message']
        );
        $this->assertStringContainsString(
            '"type": "bad_request.missing_parameter"',
            $records[1]['message']
        );

        $this->assertDatabaseHas('companies', [
            'id' => $supplier->id,
            'square_access_token' => null
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRevokeWithSquareAccessTokenButWrong()
    {
        $supplier = factory(Company::class)->create([
            'name' => 'test',
            'square_access_token' => 'not_a_valid_token',
        ]);

        $importJob = new RevokeToken(\App\Models\Foodfleet\Company::find($supplier->id));
        $importJob->handle();

        // Retrieve the records from the Monolog TestHandler
        $records = app('log')
            ->getHandlers()[0]
            ->getRecords();
        $this->assertCount(2, $records);
        $this->assertEquals(
            'Revoke token for supplier test id 1',
            $records[0]['message']
        );
        $this->assertStringContainsString(
            '"message": "access token not found"',
            $records[1]['message']
        );
        $this->assertStringContainsString(
            '"type": "not_found"',
            $records[1]['message']
        );

        $this->assertDatabaseHas('companies', [
            'id' => $supplier->id,
            'square_access_token' => 'not_a_valid_token'
        ]);
    }
}
