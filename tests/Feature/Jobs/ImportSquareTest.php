<?php

namespace Tests\Feature\Jobs;

use App\Jobs\ImportSquare;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Square\Category;
use App\Models\Foodfleet\Square\Customer;
use App\Models\Foodfleet\Square\Device;
use App\Models\Foodfleet\Square\Item;
use App\Models\Foodfleet\Square\PaymentType;
use App\Models\Foodfleet\Square\Staff;
use App\Models\Foodfleet\Square\Transaction;
use App\Models\Foodfleet\Store;
use FreshinUp\FreshBusForms\Models\Company\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use SquareConnect\Model\Card;
use SquareConnect\Model\Employee;
use SquareConnect\Model\Order;
use SquareConnect\Model\OrderLineItem;
use SquareConnect\Model\Tender;
use SquareConnect\Model\TenderCardDetails;
use Tests\TestCase;

class ImportSquareTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testImportWithoutStoresWithSquareId()
    {
        $event = factory(Event::class)->create(['name' => 'test']);

        $importJob = new ImportSquare(\App\Models\Foodfleet\Event::find($event->id));
        $importJob->handle();

        // Retrieve the records from the Monolog TestHandler
        $records = app('log')
            ->getHandlers()[0]
            ->getRecords();
        $this->assertCount(2, $records);
        $this->assertEquals(
            'Import constructor for event test id 1',
            $records[0]['message']
        );
        $this->assertEquals(
            'Start import: 0 stores with square id',
            $records[1]['message']
        );
    }

    public function testImportWithStoresWithSquareIdButSquareTokenNotSet()
    {
        $company = factory(Company::class)->create();
        $supplier = \App\Models\Foodfleet\Company::find($company->id);
        $event = factory(Event::class)->create(['name' => 'test']);
        $store = factory(Store::class)->create([
            'name' => 'test',
            'supplier_uuid' => $supplier->uuid,
            'square_id' => 'test'
        ]);
        $event->stores()->sync($store->uuid);

        $importJob = new ImportSquare(\App\Models\Foodfleet\Event::find($event->id));
        $importJob->handle();

        // Retrieve the records from the Monolog TestHandler
        $records = app('log')
            ->getHandlers()[0]
            ->getRecords();
        $this->assertCount(2, $records);
        $this->assertEquals(
            'Import constructor for event test id 1',
            $records[0]['message']
        );
        $this->assertEquals(
            'Start import: 0 stores with square id',
            $records[1]['message']
        );
    }

    public function testImportWithStoresWithSquareIdButSquareTokenNotValid()
    {
        $company = factory(Company::class)->create(['square_access_token' => 'not_valid']);
        $supplier = \App\Models\Foodfleet\Company::find($company->id);
        $event = factory(Event::class)->create(['name' => 'test']);
        $store = factory(Store::class)->create([
            'name' => 'test',
            'supplier_uuid' => $supplier->uuid,
            'square_id' => 'test'
        ]);
        $event->stores()->sync($store->uuid);


        $importJob = new ImportSquare($event);
        $importJob->handle();

        // Retrieve the records from the Monolog TestHandler
        $records = app('log')
            ->getHandlers()[0]
            ->getRecords();
        $this->assertCount(4, $records);
        $this->assertEquals(
            'Import constructor for event test id 1',
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
            'Error importing square data for event test with id 1 on line 236 for fleet member test'
            .' with id 1. Message: [HTTP/2 401] {"errors": [{"code": "UNAUTHORIZED","detail": '
            .'"The `Authorization` http header of your request was malformed. The header value is '
            .'expected to be of the format \"Bearer TOKEN\" (without quotation marks), where TOKEN is to be'
            .' replaced with your access token (e.g. \"Bearer ABC123def456GHI789jkl0\"). For more information,'
            .' see https://docs.connect.squareup.com/api/connect/v2/#requestandresponseheaders. If you are'
            .' seeing this error message while using one of our officially supported SDKs, please report this'
            .' to developers@squareup.com.","category": "AUTHENTICATION_ERROR"}]}',
            $records[3]['message']
        );
    }

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
        $event->stores()->sync($store->uuid);

        $importJob = new ImportSquare($event);
        $reflection = new \ReflectionClass(get_class($importJob));
        $methodInitializeApi = $reflection->getMethod('initializeSquareApi');
        $methodInitializeApi->setAccessible(true);
        $methodInitializeApi->invokeArgs($importJob, ['supplier' => $supplier]);
        $method = $reflection->getMethod('getOrders');
        $method->setAccessible(true);
        try {
            $method->invokeArgs($importJob, ['event' => $event, 'store' => $store]);
        } catch (\Exception $e) {
            $this->assertStringContainsString(
                '{"errors": [{"code": "UNAUTHORIZED","detail": "This request could'
                .' not be authorized.","category": "AUTHENTICATION_ERROR"}]}',
                $e->getMessage()
            );
        }
    }

    public function testGetCustomerMethod()
    {
        $company = factory(Company::class)->create(['name' => 'test', 'square_access_token' => 'test']);
        $supplier = \App\Models\Foodfleet\Company::find($company->id);
        $order = new Order();
        $order->setCustomerId('123');
        $event = factory(Event::class)->create();

        $importJob = new ImportSquare($event);
        $reflection = new \ReflectionClass(get_class($importJob));
        $methodInitializeApi = $reflection->getMethod('initializeSquareApi');
        $methodInitializeApi->setAccessible(true);
        $methodInitializeApi->invokeArgs($importJob, ['supplier' => $supplier]);
        $method = $reflection->getMethod('getCustomer');
        $method->setAccessible(true);
        try {
            $method->invokeArgs($importJob, ['order' => $order]);
        } catch (\Exception $e) {
            $this->assertStringContainsString(
                '{"errors":[{"category":"AUTHENTICATION_ERROR",'.
                '"code":"UNAUTHORIZED","detail":'.
                '"This request could not be authorized."}]}',
                $e->getMessage()
            );
        }

        $order->setCustomerId(null);
        $this->assertNull($method->invokeArgs($importJob, ['order' => $order]));
    }

    public function testGetCategoryMethod()
    {
        $company = factory(Company::class)->create(['name' => 'test', 'square_access_token' => 'test']);
        $supplier = \App\Models\Foodfleet\Company::find($company->id);
        $lineItem = new OrderLineItem();
        $lineItem->setCatalogObjectId('123');
        $event = factory(Event::class)->create();

        $importJob = new ImportSquare($event);
        $reflection = new \ReflectionClass(get_class($importJob));
        $methodInitializeApi = $reflection->getMethod('initializeSquareApi');
        $methodInitializeApi->setAccessible(true);
        $methodInitializeApi->invokeArgs($importJob, ['supplier' => $supplier]);
        $method = $reflection->getMethod('getCategory');
        $method->setAccessible(true);
        try {
            $method->invokeArgs($importJob, ['lineItem' => $lineItem]);
        } catch (\Exception $e) {
            $this->assertStringContainsString(
                '{"errors":[{"category":"AUTHENTICATION_ERROR","code":"UNAUTHORIZED","detail":'
                .'"The `Authorization` http header of your request was malformed. The header'
                .' value is expected to be of the format \"Bearer TOKEN\" (without quotation marks),'
                .' where TOKEN is to be replaced with your access token (e.g. \"Bearer ABC123def456GHI789jkl0\").'
                .' For more information, see https://developer.squareup.com/docs/build-basics/using-rest-api#__set-the-headers__.'
                .' If you are seeing this error message while using one of our officially'
                .' supported SDKs, please report this to developers@squareup.com."}]}',
                $e->getMessage()
            );
        }

        $lineItem->setCatalogObjectId(null);
        $this->assertNull($method->invokeArgs($importJob, ['lineItem' => $lineItem]));
    }

    public function testGetPaymentType()
    {
        $tender = new Tender();
        $tender->setType('CASH');
        $event = factory(Event::class)->create();

        $importJob = new ImportSquare($event);
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
        $event = factory(Event::class)->create();

        $importJob = new ImportSquare($event);
        $reflection = new \ReflectionClass(get_class($importJob));
        $methodInitializeApi = $reflection->getMethod('initializeSquareApi');
        $methodInitializeApi->setAccessible(true);
        $methodInitializeApi->invokeArgs($importJob, ['supplier' => $supplier]);
        $method = $reflection->getMethod('getDevice');
        $method->setAccessible(true);
        try {
            $method->invokeArgs($importJob, ['tender' => $tender, 'store' => $store]);
        } catch (\Exception $e) {
            $this->assertRegExp(
                '/HTTP\/(1.1|2) 301/',
                $e->getMessage()
            );
        }

        $tender->setPaymentId(null);
        $this->assertNull($method->invokeArgs($importJob, ['tender' => $tender, 'store' => $store]));
    }

    public function testUpdateOrCreateStaffs()
    {
        $company = factory(Company::class)->create(['name' => 'test', 'square_access_token' => 'test']);
        $supplier = \App\Models\Foodfleet\Company::find($company->id);
        $store = factory(Store::class)->create();
        $employee = new Employee();
        $employee->setId('test');
        $employee->setEmail('test@test.it');
        $employee->setFirstName('test');
        $employee->setLastName('test');
        $event = factory(Event::class)->create();

        $importJob = new ImportSquare($event);
        $reflection = new \ReflectionClass(get_class($importJob));
        $method = $reflection->getMethod('updateOrCreateStaffs');
        $method->setAccessible(true);
        $method->invokeArgs($importJob, ['employees' => [], 'store' => $store]);

        $this->assertDatabaseMissing('staffs', [
            'square_id' => 'test',
            'email' => 'test@test.it',
            'first_name' => 'test',
            'last_name' => 'test'
        ]);

        $method->invokeArgs($importJob, ['employees' => [$employee], 'store' => $store]);

        $this->assertDatabaseHas('staffs', [
            'square_id' => 'test',
            'email' => 'test@test.it',
            'first_name' => 'test',
            'last_name' => 'test'
        ]);
        $staff = Staff::all()->first();

        $this->assertDatabaseHas('stores_staffs', [
            'staff_uuid' => $staff->uuid,
            'store_uuid' => $store->uuid
        ]);

        $employee->setEmail('test2@test.it');
        $employee->setFirstName('test2');
        $employee->setLastName('test2');

        $method->invokeArgs($importJob, ['employees' => [$employee], 'store' => $store]);

        $this->assertDatabaseHas('staffs', [
            'uuid' => $staff->uuid,
            'square_id' => 'test',
            'email' => 'test2@test.it',
            'first_name' => 'test2',
            'last_name' => 'test2'
        ]);
    }

    public function testGetTransaction()
    {
        $order = new Order();
        $order->setId('123');
        $event = factory(Event::class)->create();
        $store = factory(Store::class)->create();
        $customer = factory(Customer::class)->create();

        $importJob = new ImportSquare($event);
        $reflection = new \ReflectionClass(get_class($importJob));
        $method = $reflection->getMethod('getTransaction');
        $method->setAccessible(true);
        $order->setCustomerId(null);
        $method->invokeArgs($importJob, [
            'order' => $order,
            'event' => $event,
            'store' => $store,
            'customer' => null
        ]);

        $this->assertDatabaseHas('transactions', [
            'square_id' => '123',
            'event_uuid' => $event->uuid,
            'store_uuid' => $store->uuid,
            'customer_uuid' => null
        ]);

        $method->invokeArgs($importJob, [
            'order' => $order,
            'event' => $event,
            'store' => $store,
            'customer' => $customer
        ]);

        $this->assertDatabaseHas('transactions', [
            'square_id' => '123',
            'event_uuid' => $event->uuid,
            'store_uuid' => $store->uuid,
            'customer_uuid' => $customer->uuid
        ]);
    }

    public function testGetItem()
    {
        $orderLineItem = new OrderLineItem();
        $orderLineItem->setUid('123');
        $orderLineItem->setName('test');
        $category = factory(Category::class)->create();
        $event = factory(Event::class)->create();

        $importJob = new ImportSquare($event);
        $reflection = new \ReflectionClass(get_class($importJob));
        $method = $reflection->getMethod('getItem');
        $method->setAccessible(true);
        $method->invokeArgs($importJob, [
            'lineItem' => $orderLineItem,
            'category' => null
        ]);

        $this->assertDatabaseHas('items', [
            'square_id' => '123',
            'name' => 'test',
            'category_uuid' => null,
        ]);

        $method->invokeArgs($importJob, [
            'lineItem' => $orderLineItem,
            'category' => $category
        ]);

        $this->assertDatabaseHas('items', [
            'square_id' => '123',
            'category_uuid' => $category->uuid,
        ]);
    }

    public function testCreatePayment()
    {
        $tender = new Tender();
        $tender->setId('123');
        $paymentType = factory(PaymentType::class)->create();
        $transaction = factory(Transaction::class)->create();
        $device = factory(Device::class)->create();
        $event = factory(Event::class)->create();

        $importJob = new ImportSquare($event);
        $reflection = new \ReflectionClass(get_class($importJob));
        $method = $reflection->getMethod('createPayment');
        $method->setAccessible(true);
        $method->invokeArgs($importJob, [
            'tender' => $tender,
            'paymentType' => null,
            'transaction' => $transaction,
            'device' => null
        ]);

        $this->assertDatabaseHas('payments', [
            'square_id' => '123',
            'payment_type_uuid' => null,
            'transaction_uuid' => $transaction->uuid,
            'device_uuid' => null,
        ]);

        $method->invokeArgs($importJob, [
            'tender' => $tender,
            'paymentType' => $paymentType,
            'transaction' => $transaction,
            'device' => $device
        ]);

        $this->assertDatabaseHas('payments', [
            'square_id' => '123',
            'payment_type_uuid' => $paymentType->uuid,
            'transaction_uuid' => $transaction->uuid,
            'device_uuid' => $device->uuid,
        ]);
    }

    public function testAttachItem()
    {
        $orderLineItem = new OrderLineItem();
        $orderLineItem->setUid('123');
        $orderLineItem->setName('test');
        $orderLineItem->setQuantity(100);
        $item = factory(Item::class)->create();
        $transaction = factory(Transaction::class)->create();
        $event = factory(Event::class)->create();

        $importJob = new ImportSquare($event);
        $reflection = new \ReflectionClass(get_class($importJob));
        $method = $reflection->getMethod('attachItem');
        $method->setAccessible(true);
        $method->invokeArgs($importJob, [
            'transaction' => $transaction,
            'item' => $item,
            'lineItem' => $orderLineItem,
        ]);

        $this->assertDatabaseHas('transactions_items', [
            'transaction_uuid' => $transaction->uuid,
            'item_uuid' => $item->uuid,
            'quantity' => 100
        ]);
    }
}
