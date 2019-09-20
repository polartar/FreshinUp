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
use SquareConnect\Model\Card;
use SquareConnect\Model\Order;
use SquareConnect\Model\OrderLineItem;
use SquareConnect\Model\Tender;
use SquareConnect\Model\TenderCardDetails;
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

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetOrdersMethod()
    {
        $company = factory(Company::class)->create(['name' => 'test', 'square_access_token' => 'test']);
        $supplier = \App\Models\Foodfleet\Company::find($company->id);
        $store = factory(Store::class)->create([
            'name' => 'test',
            'supplier_uuid' => $supplier->uuid,
            'square_id' => 'test'
        ]);
        $event = factory(Event::class)->create();

        $importJob = new ImportSquare($supplier);
        $reflection = new \ReflectionClass(get_class($importJob));
        $methodInitializeApi = $reflection->getMethod('initializeSquareApi');
        $methodInitializeApi->setAccessible(true);
        $methodInitializeApi->invokeArgs($importJob, []);
        $method = $reflection->getMethod('getOrders');
        $method->setAccessible(true);
        try {
            $method->invokeArgs($importJob, ['event' => $event, 'store' => $store]);
        } catch (\Exception $e) {
            $this->assertStringContainsString(
                '[HTTP/1.1 401 Unauthorized] {"errors": [{"code": "UNAUTHORIZED","detail": ' .
                '"This request could not be authorized.","category": "AUTHENTICATION_ERROR"}]}',
                $e->getMessage()
            );
        }
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetCustomerMethod()
    {
        $company = factory(Company::class)->create(['name' => 'test', 'square_access_token' => 'test']);
        $supplier = \App\Models\Foodfleet\Company::find($company->id);
        $order = new Order();
        $order->setCustomerId('123');

        $importJob = new ImportSquare($supplier);
        $reflection = new \ReflectionClass(get_class($importJob));
        $methodInitializeApi = $reflection->getMethod('initializeSquareApi');
        $methodInitializeApi->setAccessible(true);
        $methodInitializeApi->invokeArgs($importJob, []);
        $method = $reflection->getMethod('getCustomer');
        $method->setAccessible(true);
        try {
            $method->invokeArgs($importJob, ['order' => $order]);
        } catch (\Exception $e) {
            $this->assertStringContainsString(
                '[HTTP/1.1 401 Unauthorized] {"errors":[{"category":"AUTHENTICATION_ERROR",' .
                '"code":"UNAUTHORIZED","detail":' .
                '"This request could not be authorized."}]}',
                $e->getMessage()
            );
        }

        $order->setCustomerId(null);
        $this->assertNull($method->invokeArgs($importJob, ['order' => $order]));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetCategoryMethod()
    {
        $company = factory(Company::class)->create(['name' => 'test', 'square_access_token' => 'test']);
        $supplier = \App\Models\Foodfleet\Company::find($company->id);
        $lineItem = new OrderLineItem();
        $lineItem->setCatalogObjectId('123');

        $importJob = new ImportSquare($supplier);
        $reflection = new \ReflectionClass(get_class($importJob));
        $methodInitializeApi = $reflection->getMethod('initializeSquareApi');
        $methodInitializeApi->setAccessible(true);
        $methodInitializeApi->invokeArgs($importJob, []);
        $method = $reflection->getMethod('getCategory');
        $method->setAccessible(true);
        try {
            $method->invokeArgs($importJob, ['lineItem' => $lineItem]);
        } catch (\Exception $e) {
            $this->assertStringContainsString(
                '[HTTP/1.1 500 Internal Server Error] {"errors":[{"category":"API_ERROR",' .
                '"code":"INTERNAL_SERVER_ERROR","detail":"We were unable to authorize this request due' .
                ' to an internal error."}]}',
                $e->getMessage()
            );
        }

        $lineItem->setCatalogObjectId(null);
        $this->assertNull($method->invokeArgs($importJob, ['lineItem' => $lineItem]));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetPaymentType()
    {
        $company = factory(Company::class)->create(['name' => 'test', 'square_access_token' => 'test']);
        $supplier = \App\Models\Foodfleet\Company::find($company->id);
        $tender = new Tender();
        $tender->setType('CASH');

        $importJob = new ImportSquare($supplier);
        $reflection = new \ReflectionClass(get_class($importJob));
        $method = $reflection->getMethod('getPaymentType');
        $method->setAccessible(true);
        $paymentType = $method->invokeArgs($importJob, ['tender' => $tender]);
        $this->assertEquals('CASH', $paymentType->name);

        $tender->setType('CARD');
        $this->assertNull($method->invokeArgs($importJob, ['tender' => $tender]));

        $tender->setType('CARD');
        $cardDetails = new TenderCardDetails();
        $card = new Card();
        $card->setCardBrand('VISA');
        $cardDetails->setCard($card);
        $tender->setCardDetails($cardDetails);
        $paymentType = $method->invokeArgs($importJob, ['tender' => $tender]);
        $this->assertEquals('VISA', $paymentType->name);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetDevice()
    {
        $company = factory(Company::class)->create(['name' => 'test', 'square_access_token' => 'test']);
        $supplier = \App\Models\Foodfleet\Company::find($company->id);
        $tender = new Tender();
        $tender->setPaymentId('test');
        $store = factory(Store::class)->create([
            'name' => 'test',
            'supplier_uuid' => $supplier->uuid,
            'square_id' => 'test'
        ]);

        $importJob = new ImportSquare($supplier);
        $reflection = new \ReflectionClass(get_class($importJob));
        $methodInitializeApi = $reflection->getMethod('initializeSquareApi');
        $methodInitializeApi->setAccessible(true);
        $methodInitializeApi->invokeArgs($importJob, []);
        $method = $reflection->getMethod('getDevice');
        $method->setAccessible(true);
        try {
            $method->invokeArgs($importJob, ['tender' => $tender, 'store' => $store]);
        } catch (\Exception $e) {
            $this->assertStringContainsString(
                '[HTTP/1.1 301 Moved Permanently] ',
                $e->getMessage()
            );
        }

        $tender->setPaymentId(null);
        $this->assertNull($method->invokeArgs($importJob, ['tender' => $tender, 'store' => $store]));
    }
}
