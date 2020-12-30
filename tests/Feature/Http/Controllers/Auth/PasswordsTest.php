<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Companies;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PasswordResetNotification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class PasswordsTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    const USER_ORIGINAL_PASSWORD = 'secret';

    /**
     * Testing submitting the password reset request with an invalid
     * email address.
     * @test
     * @group password
     */
    public function testSubmitingInvalidEmailToPasswordResetsFails()
    {
        $response = $this->postJson('api/password/forgot', [
            'email' => str_random(),
        ])->assertStatus(422)->assertJsonStructure([
            'message', 'errors' => [
                'email',
            ],
        ])->json();

        $this->assertStringContainsString("valid email", $response['errors']['email'][0]);
    }

    /**
     * Testing submitting the password reset request with an email
     * address not in the database.
     * @test
     * @group password
     */
    public function testSubmitingNonExistentEmailToPasswordResetsFails()
    {

        $response = $this->postJson('api/password/forgot', [
            'email' => $this->faker->unique()->safeEmail,
        ])->assertStatus(401)->assertJsonStructure([
            'message', 'status',
        ])->json();

        $this->assertStringContainsString(
            "User may not be allowed to access or may not be found.",
            $response['message']
        );
    }

    /**
     * Testing submitting a password reset request.
     * @test
     * @group password
     */
    public function testSubmitingValidEmailToPasswordResetsWork()
    {
        $user = factory(User::class)->create([
            'email' => 'valid@email.com',
            'password' => Hash::make(self::USER_ORIGINAL_PASSWORD),
        ]);

        Notification::fake();

        $response = $this->postJson('api/password/forgot', [
            'email' => $user->email,
        ])->assertStatus(201)->assertJsonStructure([
            'message', 'status',
        ])->json();

        $this->assertStringContainsString("link was sent", $response['message']);

        Notification::assertSentTo($user, PasswordResetNotification::class);
    }

    /**
     * Testing submitting the password reset page with an invalid
     * email address.
     * @test
     * @group password
     */
    public function testSubmitingPasswordResetWithInvalidEmailFails()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make(self::USER_ORIGINAL_PASSWORD),
        ]);

        $token = Password::broker()->createToken($user);

        $password = str_random();

        //Valid token will allow the user get to the reset view page

        $response = $this->postJson('api/password/reset', [
            'email' => $this->faker->safeEmail,
            'token' => $token,
            'password' => $password,
            'password_confirmation' => $password,
        ])->assertStatus(401)->assertJsonStructure([
            'message', 'status',
        ])->json();

        $this->assertStringContainsString(trans('passwords.user'), $response['message']);
    }

    /**
     * Testing submitting the password reset page with a password
     * that doesn't match the password confirmation.
     * @test
     * @group password
     */
    public function testSubmitPasswordResetPasswordMismatch()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make(self::USER_ORIGINAL_PASSWORD),
        ]);

        $token = Password::broker()->createToken($user);

        $password = str_random();

        //Valid token will allow the user get to the reset view page

        $response = $this->postJson('api/password/reset', [
            'email' => $user->email,
            'token' => $token,
            'password' => $password,
            'password_confirmation' => $password . 'longer',
        ])->assertStatus(422)->assertJsonStructure([
            'message', 'errors',
        ])->json();

        $this->assertStringContainsString(
            "The password confirmation does not match.",
            $response['errors']['password'][0]
        );
    }

    /**
     * Testing submitting the password reset page with an invalid
     * email address.
     * @test
     * @group password
     */
    public function testSubmitingPasswordResetWorks()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make(self::USER_ORIGINAL_PASSWORD),
        ]);

        $token = Password::broker()->createToken($user);

        $password = "new_strong_password";

        //Valid token will allow the user get to the reset view page

        $response = $this->postJson('api/password/reset', [
            'email' => $user->email,
            'token' => $token,
            'password' => $password,
            'password_confirmation' => $password,
        ])->assertStatus(200)->assertJsonStructure([
            'message', 'status',
        ])->json();

        $this->assertStringContainsString(trans('passwords.reset'), $response['message']);

        $user->refresh();

        $this->assertTrue(Hash::check($password, $user->password));
    }
}
