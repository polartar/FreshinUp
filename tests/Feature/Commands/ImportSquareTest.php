<?php

namespace Tests\Feature\Commands;

use App\Jobs\ImportSquare;
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
    public function testReneweTokensWithTwoSupplierWithTokenSet()
    {
        Queue::fake();
        $companyType = new CompanyType(['name' => 'Supplier', 'key_id' => 'supplier']);
        $companyType->save();
        $suppliers = factory(Company::class, 2)->create(['name' => 'test', 'square_access_token' => 'test']);
        foreach ($suppliers as $supplier) {
            $supplier->company_types()->sync([$companyType->id]);
        }

        Artisan::call('import:square');

        Queue::assertPushed(ImportSquare::class, 2);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testReneweTokensWithTwoSupplierWithoutTokenSet()
    {
        Queue::fake();
        $companyType = new CompanyType(['name' => 'Supplier', 'key_id' => 'supplier']);
        $companyType->save();
        $suppliers = factory(Company::class, 2)->create(['name' => 'test']);
        foreach ($suppliers as $supplier) {
            $supplier->company_types()->sync([$companyType->id]);
        }

        Artisan::call('import:square');

        Queue::assertPushed(ImportSquare::class, 0);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testReneweTokensWithTokenSetButNotSupplier()
    {
        Queue::fake();
        factory(Company::class, 2)->create(['name' => 'test', 'square_access_token' => 'test']);

        Artisan::call('import:square');

        Queue::assertPushed(ImportSquare::class, 0);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRenewTokenToASpecificSupplier()
    {
        Queue::fake();
        $supplier = factory(Company::class)->create(['name' => 'test', 'square_access_token' => 'test']);

        Artisan::call('import:square --supplier=' . $supplier->id);

        Queue::assertPushed(ImportSquare::class, 1);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRenewTokenReturnsExceptionIfNoSupplierIsFound()
    {
        Queue::fake();

        try {
            Artisan::call('import:square --supplier=not_an_id');
        } catch (\Exception $e) {
            $this->assertStringContainsString(
                'No query results for model [App\Models\Foodfleet\Company] not_an_id',
                $e->getMessage()
            );
        }

        Queue::assertPushed(ImportSquare::class, 0);
    }
}
