<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Events;

use App\User;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class TmpMediaTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUploadFile()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        Storage::fake('tmp');

        $data = $this
            ->json('post', "/api/foodfleet/tmp-media", [
                'file' => UploadedFile::fake()->create('document.pdf', 100)
            ])
            ->assertStatus(200);
    }
}
