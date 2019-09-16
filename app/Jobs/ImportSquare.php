<?php

namespace App\Jobs;

use App\Helpers\SquareHelper;
use FreshinUp\FreshBusForms\Models\Company\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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

        $paymentsApi = new \SquareConnect\Api\PaymentsApi($apiClient);
        $paymentsApi->setApiClient($apiClient);


        $paymentList = $paymentsApi->listPayments();
        dd($paymentList->getPayments());
    }
}
