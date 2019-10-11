<?php

namespace Tests\Feature\Commands;

use App\Jobs\RevokeToken;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Store;
use Carbon\Carbon;
use FreshinUp\FreshBusForms\Models\Company\Company;
use FreshinUp\FreshBusForms\Models\Company\CompanyType;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

class RevokeTokensTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRevokeeTokensWithTwoSupplierWithTokenSet()
    {
        Queue::fake();
        $suppliers = factory(Company::class, 2)->create(['name' => 'test', 'square_access_token' => 'test']);
        $store1 = factory(Store::class)->create(['supplier_uuid' => $suppliers->first()->uuid]);
        $store2 = factory(Store::class)->create(['supplier_uuid' => $suppliers->last()->uuid]);
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        $event = factory(Event::class)->create(['end_at' => Carbon::now()->subDays(3)->toDateTimeString()]);
        $event->stores()->sync([$store1->uuid, $store2->uuid]);

        Artisan::call('revoke:square');

        Queue::assertPushed(RevokeToken::class, 2);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRevokeeTokensWithTwoSupplierWithoutTokenSet()
    {
        Queue::fake();
        factory(Company::class, 2)->create(['name' => 'test']);

        Artisan::call('revoke:square');

        Queue::assertPushed(RevokeToken::class, 0);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRevokeeTokensWithTokenSetButStillHasEventRunning()
    {
        Queue::fake();
        $suppliers = factory(Company::class, 2)->create(['name' => 'test', 'square_access_token' => 'test']);
        $store1 = factory(Store::class)->create(['supplier_uuid' => $suppliers->first()->uuid]);
        $store2 = factory(Store::class)->create(['supplier_uuid' => $suppliers->last()->uuid]);
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        $event = factory(Event::class)->create(['end_at' => Carbon::now()->addDays(3)->toDateTimeString()]);
        $event->stores()->sync([$store1->uuid, $store2->uuid]);

        Artisan::call('revoke:square');

        Queue::assertPushed(RevokeToken::class, 0);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRevokeTokenToASpecificSupplier()
    {
        Queue::fake();
        $supplier = factory(Company::class)->create(['name' => 'test', 'square_refresh_token' => 'test']);

        Artisan::call('revoke:square --supplier=' . $supplier->id);

        Queue::assertPushed(RevokeToken::class, 1);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRevokeTokenReturnsExceptionIfNoSupplierIsFound()
    {
        Queue::fake();

        try {
            Artisan::call('revoke:square --supplier=not_an_id');
        } catch (\Exception $e) {
            $this->assertStringContainsString(
                'No query results for model [App\Models\Foodfleet\Company] not_an_id',
                $e->getMessage()
            );
            Queue::assertPushed(RevokeToken::class, 0);
        }
    }
}
