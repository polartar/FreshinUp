<?php

namespace App\Jobs;

use App\Helpers\SquareHelper;
use App\Models\Foodfleet\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class RevokeToken implements ShouldQueue
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
        Log::info('Revoke token for supplier ' . $supplier->name . ' id ' . $supplier->id);
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
        $accessToken = $this->supplier->square_access_token;
        $apiClient = SquareHelper::getApiClient();

        // Create an OAuth API client
        $oauthApi = new \SquareConnect\Api\OAuthApi($apiClient);
        $body = new \SquareConnect\Model\RevokeTokenRequest();

        // Set the POST body
        $body->setClientId(config('square.application_id'));
        $body->setAccessToken($accessToken);

        try {
            $oauthApi->revokeToken($body);
            $this->supplier->square_access_token = null;
            $this->supplier->square_refresh_token = null;
            $this->supplier->save();
        } catch (\Exception $e) {
            Log::error('Error revoking token for supplier ' . $this->supplier->name . ' with id ' .
                $this->supplier->id . ' on line ' . $e->getLine() . '. Message: ' . $e->getMessage());
        }
    }
}
