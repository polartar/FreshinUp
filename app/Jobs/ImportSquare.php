<?php

namespace App\Jobs;

use App\Helpers\SquareHelper;
use App\Models\Foodfleet\Company;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Square\Category;
use App\Models\Foodfleet\Square\Customer;
use App\Models\Foodfleet\Square\Device;
use App\Models\Foodfleet\Square\Item;
use App\Models\Foodfleet\Square\Payment;
use App\Models\Foodfleet\Square\PaymentType;
use App\Models\Foodfleet\Square\Staff;
use App\Models\Foodfleet\Square\Transaction;
use App\Models\Foodfleet\Store;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;

class ImportSquare implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;
    protected $employeesApi;
    protected $ordersApi;
    protected $customersApi;
    protected $catalogsApi;
    protected $v1TransactionApi;

    /**
     * Create a new job instance.
     *
     * @param Event $event
     * @return void
     */
    public function __construct(Event $event)
    {
        Log::info('Import constructor for event ' . $event->name . ' id ' . $event->id);
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $stores = $this->event->stores()->whereNotNull('square_id')
            ->whereHas('supplier', function (Builder $query) {
                $query->whereNotNull('square_access_token');
            })->get();

        Log::info('Start import: ' . $stores->count() . ' stores with square id');
        foreach ($stores as $store) {
            try {
                Log::info('Store ' . $store->name . ' id ' . $store->id);

                $this->initializeSquareApi($store->supplier);

                // Staffs
                $employeeList = $this->employeesApi->listEmployees($store->square_id);
                $employees = $employeeList->getEmployees();
                $this->updateOrCreateStaffs($employees, $store);

                // Transactions
                $orders = $this->getOrders($this->event, $store);
                if ($orders) {
                    foreach ($orders as $order) {
                        // Customer
                        $customer = $this->getCustomer($order);

                        // Transaction
                        $transaction = $this->getTransaction($order, $this->event, $store, $customer);

                        // Items
                        $lineItems = $order->getLineItems();
                        foreach ($lineItems as $lineItem) {
                            // Item Category
                            $category = $this->getCategory($lineItem);
                            $item = $this->getItem($lineItem, $category);
                            $this->attachItem($transaction, $item, $lineItem);
                        }

                        // Payments
                        $tenders = $order->getTenders();
                        foreach ($tenders as $tender) {
                            // Payment Type
                            $paymentType = $this->getPaymentType($tender);

                            // Device
                            $deviceModel = $this->getDevice($tender, $store);

                            // Payment
                            $this->createPayment($tender, $paymentType, $transaction, $deviceModel);
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error importing square data for event ' . $this->event->name . ' with id ' .
                    $this->event->id . ' on line ' . $e->getLine() . ' for fleet member ' . ($store->name ?? '') .
                    ' with id ' . $store->id . '. Message: ' . $e->getMessage());
            }
        }
    }

    /**
     * Retrieve orders for the specified event and store
     *
     * @param $event
     * @param $store
     * @return \SquareConnect\Model\Order[]|null
     * @throws \SquareConnect\ApiException
     */
    protected function getOrders($event, $store)
    {
        // Set sort option
        $sort = new \SquareConnect\Model\SearchOrdersSort();
        $sort->setSortField('CREATED_AT');
        $sort->setSortOrder('ASC');

        // Set filter for event start and end in order to retrieve only related orders
        $beginTime = Carbon::parse($event->start_at)->toIso8601ZuluString();
        $endTime = Carbon::parse($event->end_at)->toIso8601ZuluString();
        $timeRange = new \SquareConnect\Model\TimeRange();
        $timeRange->setStartAt($beginTime);
        $timeRange->setEndAt($endTime);
        $timeFilter = new \SquareConnect\Model\SearchOrdersDateTimeFilter();
        $timeFilter->setCreatedAt($timeRange);
        $filter = new \SquareConnect\Model\SearchOrdersFilter();
        $filter->setDateTimeFilter($timeFilter);

        // Set query
        $query = new \SquareConnect\Model\SearchOrdersQuery();
        $query->setFilter($filter);
        $query->setSort($sort);

        // Set body
        $body = new \SquareConnect\Model\SearchOrdersRequest();
        $body->setQuery($query);
        $body->setLocationIds([$store->square_id]);

        // Run query and retrieve orders
        $orderList = $this->ordersApi->searchOrders($body);
        $orders = $orderList->getOrders();
        return $orders;
    }

    /**
     * Retrieve customer for the specified order
     *
     * @param \SquareConnect\Model\Order $order
     * @return Customer|null
     * @throws \SquareConnect\ApiException
     */
    protected function getCustomer(\SquareConnect\Model\Order $order)
    {
        $customerId = $order->getCustomerId();
        $customerModel = null;
        if ($customerId) {
            $customer = $this->customersApi->retrieveCustomer($customerId)->getCustomer();
            $customerModel = Customer::updateOrCreate([
                'square_id' => $customer->getId()
            ], [
                'square_id' => $customer->getId(),
                'given_name' => $customer->getGivenName(),
                'family_name' => $customer->getFamilyName(),
                'reference_id' => $customer->getReferenceId()
            ]);
        }
        return $customerModel;
    }

    /**
     * Retrieve category for the specified line item
     *
     * @param \SquareConnect\Model\OrderLineItem $lineItem
     * @return Category|null
     * @throws \SquareConnect\ApiException
     */
    protected function getCategory(\SquareConnect\Model\OrderLineItem $lineItem)
    {
        $categoryModel = null;
        $catalogObjectId = $lineItem->getCatalogObjectId();
        if ($catalogObjectId) {
            $catalogObject = $this->catalogsApi
                ->retrieveCatalogObject($catalogObjectId, true)
                ->getObject();
            $name = $catalogObject->getCategoryData() ?
                $catalogObject->getCategoryData()->getName() : null;
            if ($name) {
                $categoryModel = Category::updateOrCreate([
                    'name' => $name,
                    'supplier_uuid' => $this->supplier->uuid
                ], [
                    'name' => $name,
                    'supplier_uuid' => $this->supplier->uuid
                ]);
            }
        }
        return $categoryModel;
    }

    /**
     * Retrieve payment type for the specified tender
     *
     * @param \SquareConnect\Model\Tender $tender
     * @return PaymentType|null
     */
    protected function getPaymentType(\SquareConnect\Model\Tender $tender)
    {
        $paymentType = null;
        $type = $tender->getType();
        if ($type == 'CASH') {
            $paymentType = PaymentType::firstOrCreate(['name' => 'CASH']);
        } else {
            $cardDetails = $tender->getCardDetails();
            if ($cardDetails && $cardDetails->getCard() && $cardDetails->getCard()->getCardBrand()) {
                $type = $cardDetails->getCard()->getCardBrand();
                $paymentType = PaymentType::firstOrCreate(['name' => $type]);
            }
        }
        return $paymentType;
    }

    /**
     * Retrieve device for specified tender
     *
     * @param \SquareConnect\Model\Tender $tender
     * @param $store
     * @return Device|null
     * @throws \SquareConnect\ApiException
     */
    protected function getDevice(\SquareConnect\Model\Tender $tender, $store)
    {
        $deviceModel = null;
        $paymentId = $tender->getPaymentId();
        if ($paymentId) {
            $result = $this->v1TransactionApi->retrievePayment($store->square_id, $paymentId);
            $device = $result->getDevice();

            $deviceModel = Device::updateOrCreate([
                'square_id' => $device->getId()
            ], [
                'square_id' => $device->getId(),
                'name' => $device->getName(),
            ]);
        }
        return $deviceModel;
    }

    /**
     * Initialize square api properties
     *
     * @param Company $supplier
     */
    protected function initializeSquareApi(Company $supplier): void
    {
        // Get square access token for the supplier
        $accessToken = $supplier->square_access_token;
        $apiClient = SquareHelper::getApiClient($accessToken);

        // Initialize Api class for the square resources
        $this->employeesApi = new \SquareConnect\Api\EmployeesApi($apiClient);
        $this->ordersApi = new \SquareConnect\Api\OrdersApi($apiClient);
        $this->customersApi = new \SquareConnect\Api\CustomersApi($apiClient);
        $this->catalogsApi = new \SquareConnect\Api\CatalogApi($apiClient);
        $this->v1TransactionApi = new \SquareConnect\Api\V1TransactionsApi($apiClient);
    }

    /**
     * Update or create staffs
     *
     * @param $store
     * @param $employees
     * @return void
     */
    protected function updateOrCreateStaffs($employees, $store): void
    {
        $staffUuids = [];
        foreach ($employees as $employee) {
            $staff = Staff::updateOrCreate([
                'square_id' => $employee->getId()
            ], [
                'square_id' => $employee->getId(),
                'email' => $employee->getEmail(),
                'first_name' => $employee->getFirstName(),
                'last_name' => $employee->getLastName()
            ]);
            $staffUuids[] = $staff->uuid;
        }
        $store->staffs()->sync($staffUuids);
    }

    /**
     * Create transaction
     *
     * @param \SquareConnect\Model\Order $order
     * @param $event
     * @param Store $store
     * @param Customer|null $customer
     * @return mixed
     */
    protected function getTransaction(\SquareConnect\Model\Order $order, $event, $store, ?Customer $customer)
    {
        return Transaction::updateOrCreate([
            'square_id' => $order->getId()
        ], [
            'square_id' => $order->getId(),
            'event_uuid' => $event->uuid,
            'store_uuid' => $store->uuid,
            'customer_uuid' => $customer ? $customer->uuid : null,
            'total_money' => $order->getTotalMoney() ? $order->getTotalMoney()->getAmount() : null,
            'total_tax_money' => $order->getTotalTaxMoney() ?
                $order->getTotalTaxMoney()->getAmount() : null,
            'total_discount_money' => $order->getTotalDiscountMoney() ?
                $order->getTotalDiscountMoney()->getAmount() : null,
            'total_service_charge_money' => $order->getTotalServiceChargeMoney() ?
                $order->getTotalServiceChargeMoney()->getAmount() : null,
            'square_created_at' => $order->getCreatedAt() ?
                Carbon::parse($order->getCreatedAt())->toDateTimeString() : null,
            'square_updated_at' => $order->getUpdatedAt() ?
                Carbon::parse($order->getUpdatedAt())->toDateTimeString() : null
        ]);
    }

    /**
     * Create item
     *
     * @param \SquareConnect\Model\OrderLineItem $lineItem
     * @param Category|null $category
     * @return mixed
     */
    protected function getItem(\SquareConnect\Model\OrderLineItem $lineItem, ?Category $category)
    {
        $item = Item::updateOrCreate([
            'square_id' => $lineItem->getUid(),
        ], [
            'square_id' => $lineItem->getUid(),
            'name' => $lineItem->getName(),
            'category_uuid' => $category ? $category->uuid : null
        ]);
        return $item;
    }

    /**
     * Create payment
     *
     * @param \SquareConnect\Model\Tender $tender
     * @param PaymentType|null $paymentType
     * @param $transaction
     * @param Device|null $deviceModel
     */
    protected function createPayment(
        \SquareConnect\Model\Tender $tender,
        ?PaymentType $paymentType,
        $transaction,
        ?Device $deviceModel
    ): void {
        Payment::updateOrCreate([
            'square_id' => $tender->getId()
        ], [
            'square_id' => $tender->getId(),
            'payment_type_uuid' => $paymentType ? $paymentType->uuid : null,
            'transaction_uuid' => $transaction->uuid,
            'device_uuid' => $deviceModel ? $deviceModel->uuid : null,
            'amount_money' => $tender->getAmountMoney() ?
                $tender->getAmountMoney()->getAmount() : null,
            'tip_money' => $tender->getTipMoney() ? $tender->getTipMoney()->getAmount() : null,
            'processing_fee_money' => $tender->getProcessingFeeMoney() ?
                $tender->getProcessingFeeMoney()->getAmount() : null,
            'square_created_at' => $tender->getCreatedAt() ?
                Carbon::parse($tender->getCreatedAt())->toDateTimeString() : null
        ]);
    }

    /**
     * Attach an item to a transaction
     *
     * @param $transaction
     * @param $item
     * @param \SquareConnect\Model\OrderLineItem $lineItem
     */
    protected function attachItem($transaction, $item, \SquareConnect\Model\OrderLineItem $lineItem): void
    {
        $transaction->items()->attach($item->uuid, [
            'quantity' => $lineItem->getQuantity(),
            "total_money" => $lineItem->getTotalMoney() ?
                $lineItem->getTotalMoney()->getAmount() : null,
            "total_tax_money" => $lineItem->getTotalTaxMoney() ?
                $lineItem->getTotalTaxMoney()->getAmount() : null,
            "total_discount_money" => $lineItem->getTotalDiscountMoney() ?
                $lineItem->getTotalDiscountMoney()->getAmount() : null
        ]);
    }
}
