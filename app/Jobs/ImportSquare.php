<?php

namespace App\Jobs;

use App\Helpers\SquareHelper;
use App\Http\Resources\Foodfleet\Square\Payment;
use App\Models\Foodfleet\Company;
use App\Models\Foodfleet\Square\Category;
use App\Models\Foodfleet\Square\Customer;
use App\Models\Foodfleet\Square\Device;
use App\Models\Foodfleet\Square\Item;
use App\Models\Foodfleet\Square\PaymentType;
use App\Models\Foodfleet\Square\Staff;
use App\Models\Foodfleet\Square\Transaction;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use SquareConnect\Api\CustomersApi;

class ImportSquare implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $supplier;

    /**
     * Create a new job instance.
     *
     * @param Company $contractor
     * @return void
     */
    public function __construct(Company $contractor)
    {
        $this->supplier = $contractor;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $accessToken = $this->supplier->square_access_token;
        $apiClient = SquareHelper::getApiClient($accessToken);
        $stores = $this->supplier->stores()->whereNotNull('square_id')->get();

        $employeesApi = new \SquareConnect\Api\EmployeesApi($apiClient);
        $employeesApi->setApiClient($apiClient);
        $ordersApi = new \SquareConnect\Api\OrdersApi($apiClient);
        $ordersApi->setApiClient($apiClient);
        $customersApi = new \SquareConnect\Api\CustomersApi($apiClient);
        $customersApi->setApiClient($apiClient);
        $catalogsApi = new \SquareConnect\Api\CatalogApi();
        $catalogsApi->setApiClient($apiClient);
        $v1TransactionApi = new \SquareConnect\Api\V1TransactionsApi();
        $v1TransactionApi->setApiClient($apiClient);
        foreach ($stores as $store) {
            // Staffs
            $employeeList = $employeesApi->listEmployees($store->square_id);
            $employees = $employeeList->getEmployees();
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

            // Transactions
            $events = $store->events;
            foreach ($events as $event) {
                $beginTime = Carbon::parse($event->start_at)->toIso8601ZuluString();
                $endTime = Carbon::parse($event->end_at)->toIso8601ZuluString();
                $body = new \SquareConnect\Model\SearchOrdersRequest();
                $query = new \SquareConnect\Model\SearchOrdersQuery();
                $filter = new \SquareConnect\Model\SearchOrdersFilter();
                $timeFilter = new \SquareConnect\Model\SearchOrdersDateTimeFilter();
                $timeRange = new \SquareConnect\Model\TimeRange();
                $sort = new \SquareConnect\Model\SearchOrdersSort();
                $sort->setSortField('CREATED_AT');
                $sort->setSortOrder('ASC');
                $timeRange->setStartAt($beginTime);
                $timeRange->setEndAt($endTime);
                $timeFilter->setCreatedAt($timeRange);
                $filter->setDateTimeFilter($timeFilter);
                $query->setFilter($filter);
                $query->setSort($sort);
                $body->setQuery($query);
                $body->setLocationIds([$store->square_id]);
                $orderList = $ordersApi->searchOrders($body);
                $orders = $orderList->getOrders();
                if ($orders) {
                    foreach ($orders as $order) {
                        $customerId = $order->getCustomerId();
                        $customerModel = null;
                        if ($customerId) {
                            $customer = $customersApi->retrieveCustomer($customerId)->getCustomer();
                            $customerModel = Customer::updateOrCreate([
                                'square_id' => $customer->getId()
                            ], [
                                'square_id' => $customer->getId(),
                                'given_name' => $customer->getGivenName(),
                                'family_name' => $customer->getFamilyName(),
                                'reference_id' => $customer->getReferenceId()
                            ]);
                        }

                        $transaction = Transaction::updateOrCreate([
                            'square_id' => $order->getId()
                        ], [
                            'square_id' => $order->getId(),
                            'event_uuid' => $event->uuid,
                            'customer_uuid' => $customerModel ? $customerModel->uuid : null,
                            'total_money' => $order->getTotalMoney()->getAmount(),
                            'total_tax_money' => $order->getTotalTaxMoney()->getAmount(),
                            'total_discount_money' => $order->getTotalDiscountMoney()->getAmount(),
                            'total_service_charge_money' => $order->getTotalServiceChargeMoney()->getAmount(),
                            'square_created_at' => Carbon::parse($order->getCreatedAt())->toDateTimeString(),
                            'square_updated_at' => Carbon::parse($order->getUpdatedAt())->toDateTimeString()
                        ]);

                        // Items
                        $lineItems = $order->getLineItems();
                        foreach ($lineItems as $lineItem) {
                            $catalogObjectId = $lineItem->getCatalogObjectId();
                            $categoryModel = null;
                            if ($catalogObjectId) {
                                $catalogOnject = $catalogsApi
                                    ->retrieveCatalogObject($catalogObjectId, true)
                                    ->getObject();
                                $name = $catalogOnject->getCategoryData()->getName();
                                $categoryModel = Category::updateOrCreate([
                                    'name' => $name,
                                    'supplier_uuid' => $this->supplier->uuid
                                ], [
                                    'name' => $name,
                                    'supplier_uuid' => $this->supplier->uuid
                                ]);
                            }

                            $item = Item::updateOrCreate([
                                'square_id' => $lineItem->getUid(),
                            ], [
                                'square_id' => $lineItem->getUid(),
                                'name' => $lineItem->getName(),
                                'category_uuid' => $categoryModel ? $categoryModel->uuid : null
                            ]);

                            $transaction->items()->attach($item->uuid, [
                                'quantity' => $lineItem->getQuantity(),
                                "total_money" => $lineItem->getTotalMoney()->getAmount(),
                                "total_tax_money" => $lineItem->getTotalTaxMoney()->getAmount(),
                                "total_discount_money" => $lineItem->getTotalDiscountMoney()->getAmount(),
                            ]);
                        }

                        // Payments
                        $tenders = $order->getTenders();
                        foreach ($tenders as $tender) {
                            // Payment Type
                            $type = $tender->getType();
                            $paymentType = null;
                            if ($type == 'CARD') {
                                $paymentType = PaymentType::firstOrCreate(['name' => 'CARD']);
                            } else {
                                $cardDetails = $tender->getCardDetails();
                                if ($cardDetails) {
                                    $type = $cardDetails->getCard()->getCardBrand();
                                    $paymentType = PaymentType::firstOrCreate(['name' => $type]);
                                }
                            }

                            // Device
                            $paymentId = $tender->getPaymentId();
                            $deviceModel = null;
                            if ($paymentId) {
                                $result = $v1TransactionApi->retrievePayment($store->square_id, $paymentId);
                                $device = $result->getDevice();

                                $deviceModel = Device::updateOrCreate([
                                    'square_id' => $device->getId()
                                ], [
                                    'square_id' => $device->getId(),
                                    'square_id' => $device->getName(),
                                ]);
                            }
                            
                            Payment::updateOrCreate([
                                'square_id' => $tender->getId()
                            ], [
                                'square_id' => $tender->getId(),
                                'payment_type_uuid' => $paymentType ? $paymentType->uuid : null,
                                'transaction_uuid' => $transaction->uuid,
                                'device_uuid' => $deviceModel ? $deviceModel->uuid : null,
                                'amount_money' => $tender->getAmountMoney()->getAmount(),
                                'tip_money' => $tender->getTipMoney()->getAmount(),
                                'processing_fee_money' => $tender->getProcessingFeeMoney()->getAmount(),
                                'square_created_at' => Carbon::parse($tender->getCreatedAt())->toDateTimeString(),
                            ]);

                        }


                    }
                }
            }
        }
    }
}
