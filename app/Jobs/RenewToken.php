<?php

namespace App\Jobs;

use App\Helpers\SquareHelper;
use App\Models\Foodfleet\Company;
use App\Models\Foodfleet\Square\Category;
use App\Models\Foodfleet\Square\Customer;
use App\Models\Foodfleet\Square\Device;
use App\Models\Foodfleet\Square\Item;
use App\Models\Foodfleet\Square\Payment;
use App\Models\Foodfleet\Square\PaymentType;
use App\Models\Foodfleet\Square\Staff;
use App\Models\Foodfleet\Square\Transaction;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class RenewToken implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $supplier;

    /**
     * Create a new job instance.
     *
     * @param Company $supplier
     * @return void
     */
    public function __construct(Company $supplier)
    {
        Log::info('Renew token for supplier ' . $supplier->name . ' id ' . $supplier->id);
        $this->supplier = $supplier;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get square refresh token for the supplier
        $refreshToken = $this->supplier->square_refresh_token;
        $apiClient = SquareHelper::getApiClient();

        // Create an OAuth API client
        $oauthApi = new \SquareConnect\Api\OAuthApi($apiClient);
        $body = new \SquareConnect\Model\ObtainTokenRequest();

        // Set the POST body
        $body->setClientId(config('square.application_id'));
        $body->setClientSecret(config('square.app_secret'));
        $body->setGrantType("refresh_token");
        $body->setRefreshToken($refreshToken);

        try {
            $result = $oauthApi->obtainToken($body);
            $this->supplier->square_access_token = $result->getAccessToken();
            $this->supplier->save();
        } catch (\Exception $e) {
            Log::error('Error renewing token for supplier ' . $this->supplier->name . ' with id ' .
                $this->supplier->id . ' on line ' . $e->getLine() . ' for fleet member. Message: ' . $e->getMessage());
        }
    }
}
