<?php


namespace Tests\Feature\Http\Controllers\Foodfleet\FinancialModifiers;

use App\Models\Foodfleet\FinancialModifier as Modifier;
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
            ->json('get', "/api/foodfleet/financial-modifiers")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data',
            ])
            ->json('data');
        $this->assertEmpty($data);


        factory(Modifier::class, 5)->create();
        $data = $this
            ->json('get', "/api/foodfleet/financial-modifiers")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');
        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
    }
}
