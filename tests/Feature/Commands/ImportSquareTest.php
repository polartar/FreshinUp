<?php

namespace Tests\Feature\Commands;

use App\Jobs\ImportSquare;
use App\Models\Foodfleet\Event;
use Carbon\Carbon;
use FreshinUp\FreshBusForms\Models\Company\Company;
use FreshinUp\FreshBusForms\Models\Company\CompanyType;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

class ImportSquareTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testThatOnlyInsideRangeEventAreRunned()
    {
        Queue::fake();
        Carbon::setTestNow(Carbon::create(2019, 5, 21, 12));
        factory(Event::class, 2)->create(['start_at' => Carbon::now()->toDateTimeString()]);
        factory(Event::class, 2)->create(['start_at' => Carbon::now()->subDays(2)->toDateTimeString()]);

        Artisan::call('import:square');

        Queue::assertPushed(ImportSquare::class, 2);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRenewTokenToASpecificEvent()
    {
        Queue::fake();
        $event = factory(Event::class)->create();

        Artisan::call('import:square --event=' . $event->id);

        Queue::assertPushed(ImportSquare::class, 1);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRenewTokenReturnsExceptionIfNoEventIsFound()
    {
        Queue::fake();

        try {
            Artisan::call('import:square --event=not_an_id');
        } catch (\Exception $e) {
            $this->assertStringContainsString(
                'No query results for model [App\Models\Foodfleet\Event] not_an_id',
                $e->getMessage()
            );
        }

        Queue::assertPushed(ImportSquare::class, 0);
    }
}
