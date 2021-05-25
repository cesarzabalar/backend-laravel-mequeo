<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use WithoutMiddleware, RefreshDatabase;

    /**
     * Test for params to register
     *
     * @return void
     */
    public function testRequiredForRegistration(): void
    {
        $response = $this->post(route('auth.signup'), [], ['Accept' => 'application/json']);
        $response->assertStatus(422);
        $response->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "name" => ["The name field is required."],
                "email" => ["The email field is required."],
                "password" => ["The password field is required."]
            ]

        ]);
    }

    /**
     * Test repeat password for register
     *
     * @return void
     */
    public function testRepeatPassword(): void
    {
        $userData = [
            "name" => "John Doe",
            "email" => "doe@example.com",
            "password" => "demo12345"
        ];

        $response = $this->post(route('auth.signup'), $userData, ['Accept'=>'application/json']);
        $response->assertStatus(422);
        $response->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "password" => ["The password confirmation does not match."]
            ]
        ]);

    }

    /**
     * Test user succesfully registration
     *
     * @return void
     */
    public function testSuccessfulRegistration(): void
    {
        $userData = [
            "name" => "John Doe",
            "email" => "doe@example.com",
            "password" => "demo12345",
            "password_confirmation" => "demo12345"
        ];

        $response = $this->post(route('auth.signup'), $userData, ['Accept' => 'application/json']);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            "user" => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at'
            ],
            "message"
        ]);
    }

    /**
     * This test case login
     */
    public function testLoginSuccess(): void
    {
        $this->artisan('passport:install');
        $userFactory = factory(User::class)->create();

        $data = [
            'email' => $userFactory->email,
            'password' => 'password',
            'remember_me' => true
        ];

        $response = $this->post(route('auth.login'), $data, ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJsonStructure(['access_token', 'token_type', 'expires_at']);
    }

    /**
     * This case is login Unauthorized
     */
    public function testLoginUnauthorized(): void
    {
        $userFactory = factory(User::class)->create();

        $response = $this->post(route('auth.login'), [
            'email' => $userFactory->email,
            'password' => '_fake',
            'remember_me' => true
        ], [
            'Accept' => 'application/json'
        ]);


        $response->assertStatus(401);
        $response->assertJson(['message' => __('auth.unauthorized')]);
    }
}
