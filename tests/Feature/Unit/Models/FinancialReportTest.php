<?php

namespace Tests\Feature\Unit\Models\FinancialReport;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\FinancialModifier;
use App\Models\Foodfleet\FinancialReport;
use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\Square\Customer;
use App\Models\Foodfleet\Square\Device;
use App\Models\Foodfleet\Square\Item;
use App\Models\Foodfleet\Square\Payment;
use App\Models\Foodfleet\Square\PaymentType;
use App\Models\Foodfleet\Square\Transaction;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FinancialReportTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testModel()
    {
        $user = factory(User::class)->create();
        $modifier1 = factory(FinancialModifier::class)->create();
        $modifier2 = factory(FinancialModifier::class)->create();

        $financialReport = factory(FinancialReport::class)->create();
        $financialReport->user()->associate($user);
        $financialReport->modifier1()->associate($modifier1);
        $financialReport->modifier2()->associate($modifier2);
        $financialReport->save();

        $this->assertDatabaseHas('financial_reports', [
            'id' => $financialReport->id,
            'user_id' => $user->id,
            'modifier_1_id' => $modifier1->id,
            'modifier_2_id' => $modifier2->id
        ]);
    }
}
