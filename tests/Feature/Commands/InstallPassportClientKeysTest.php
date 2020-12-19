<?php


namespace Tests\Feature\Commands;

use App\Jobs\ImportSquare;
use App\Models\Foodfleet\Event;
use App\User;
use Carbon\Carbon;
use FreshinUp\FreshBusForms\Models\Company\Company;
use FreshinUp\FreshBusForms\Models\Company\CompanyType;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use League\CLImate\Settings\Art;
use Symfony\Component\Console\Output\BufferedOutput;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Laravel\Passport\Passport;

class InstallPassportClientKeysTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * @test
     * @group login
     */
    public function testUserCannotLoginIfVueSPAClientCredentialsNotProperlySet()
    {
        //Given
        $email = 'command@user.com';
        $password = 'password';

        // there is an existing user
        $user = factory(User::class)->create([
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        //There exists wrong SPA Passport Credentials
        config(['services.vue_client.id' => 20]);
        config(['services.vue_client.secret' => "SomeWrongSecret"]);

        $payload = [
            'email' => $email,
            'password' => $password,
        ];

        $response = $this->postJson(route('apilogin'), $payload);

        $response->assertStatus(401);

        $this->assertStringContainsString('Unauthorized', $response->json('status'));
    }

    /**
     * @test
     * @group login
     */
    public function testCanGeneratePassportClientCredentialsAndUpdateEnvironmentFile()
    {
        //Given
        $email = 'command@user.com';
        $password = 'password';

        // there is an existing user
        $user = factory(User::class)->create([
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        //attempt to login manually
        $old_keys = config('services.vue_client');

        Artisan::call("passport-client:update-env");
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        $client = DB::table('oauth_clients')->latest()->first();

        $client_credentials = [
            'id' => (int) $client->id,
            'secret' => $client->secret,
        ];

        $new_keys = config('services.vue_client');

        $this->assertNotSame($old_keys, $new_keys);
        $this->assertSame($client_credentials, $new_keys);

        $payload = [
            'email' => $email,
            'password' => $password,
        ];

        $response = $this->postJson(route('apilogin'), $payload);

        $response->assertStatus(200);

        $this->assertStringContainsString('success', $response->json('status'));
    }
}
