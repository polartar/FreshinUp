<?php

namespace App\Jobs;

use App\Helpers\SquareHelper;
use App\Http\Resources\Foodfleet\Square\Payment;
use App\Models\Foodfleet\Company;
use App\Models\Foodfleet\Square\Customer;
use App\Models\Foodfleet\Square\PaymentType;
use App\Models\Foodfleet\Square\Staff;
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

    private $contractor;

    /**
     * Create a new job instance.
     *
     * @param Company $contractor
     * @return void
     */
    public function __construct(Company $contractor)
    {
        $this->contractor = $contractor;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $accessToken = $this->contractor->square_access_token;
        $apiClient = SquareHelper::getApiClient($accessToken);
        $fleetMembers = $this->contractor->fleetMembers()->whereNotNull('square_id')->get();

        $employeesApi = new \SquareConnect\Api\EmployeesApi($apiClient);
        $employeesApi->setApiClient($apiClient);
        $paymentsApi = new \SquareConnect\Api\PaymentsApi($apiClient);
        $paymentsApi->setApiClient($apiClient);
        $customersApi = new \SquareConnect\Api\CustomersApi($apiClient);
        $customersApi->setApiClient($apiClient);
        foreach ($fleetMembers as $fleetMember) {

            // Staffs
            $employeeList = $employeesApi->listEmployees($fleetMember->square_id);
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
            $fleetMember->staffs()->sync($staffUuids);

            // Payments
            $events = $fleetMember->events;
            foreach ($events as $event) {
                $beginTime = Carbon::parse($event->start_at)->toIso8601ZuluString();
                $endTime = Carbon::parse($event->end_at)->toIso8601ZuluString();
                $paymentList = $paymentsApi->listPayments($beginTime, $endTime, null, null, $fleetMember->square_id);
                $payments = $paymentList->getPayments();
                if ($payments) {
                    foreach ($payments as $payment) {
                        $sourceType = $payment->getSourceType();
                        $paymentType = PaymentType::firstOrCreate(['name' => $sourceType]);
                        $customerId = $payment->getCustomerId();
                        $customer = $customersApi->retrieveCustomer($customerId)->getCustomer();
                        $customerModel = Customer::updateOrCreate([
                            'square_id' => $customer->getId()
                        ], [
                            'square_id' => $customer->getId(),
                            'given_name' => $customer->getGivenName(),
                            'family_name' => $customer->getFamilyName(),
                            'reference_id' => $customer->getReferenceId()
                        ]);

                        Payment::updateOrCreate([
                            'square_id' => $payment->getId()
                        ], [
                            'square_id' => $payment->getId(),
                            'customer_uuid' => $customerModel->uuid,
                            'payment_type_uuid' => $paymentType->uuid,
                            'event_uuid' => $event->uuid,
                            'amount_money' => $payment->getAmountMoney()->getAmount(),
                            'tip_money' => $payment->getTipMoney()->getAmount(),
                            'total_money' => $payment->getTotalMoney()->getAmount(),
                            'app_fee_money' => $payment->getAppFeeMoney()->getAmount(),
                            'refunded_money' => $payment->getRefundedMoney()->getAmount(),
                            'square_created_at' => Carbon::parse($payment->getCreatedAt())->toDateTimeString(),
                            'square_updated_at' => Carbon::parse($payment->getUpdatedAt())->toDateTimeString()
                        ]);
                    }
                }
            }
        }
    }
}
