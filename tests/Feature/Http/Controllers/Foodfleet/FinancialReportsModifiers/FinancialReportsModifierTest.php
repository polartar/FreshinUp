<?php


namespace Tests\Feature\Http\Controllers\CSM\SellStatuses;

use App\Models\CSM\Modifier;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;

class FinancialReportsModifierTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testGetList()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this
            ->json('get', "/api/csm/financial-reports-modifiers")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data',
            ])
            ->json('data');


        $this->assertEmpty($data);


        factory(Modifier::class, 5)->create();

        $data = $this
            ->json('get', "/api/csm/financial-reports-modifiers")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
    }
}
