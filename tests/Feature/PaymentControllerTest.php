<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Mockery;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use WithoutMiddleware, RefreshDatabase;

    /**
     * This test case successful in endpoint create payment succesful
     */
    public function testCreatePaymentSuccess(): void
    {
        $data = ['total_product' => 50000, 'total_cash' => 10000];
        $this->artisan('db:seed --class=CashRegisterSeeder');
        $response = $this->post(route('payment.create'), $data, ['Accept' => 'application/json']);

        $response->assertStatus(201);
        $this->assertDatabaseHas('payments', $data);
        $this->assertDatabaseHas('logs', ['type' => 'entry', 'total' => $data['total_cash']]);
    }

    /**
     * This test case successful in endpoint create payment with error fields
     */
    public function testCreatePaymentValidationFail(): void
    {
        $data = ['total_product' => 20, 'total_cash' => 30];
        $response = $this->post(route('payment.create'), $data, ['Accept' => 'application/json']);

        $response->assertStatus(422);
        $response->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "total_product" => [
                    "The total product must be at least 50."
                ],
                "total_cash" => [
                    "The total cash must be at least 50."
                ]
            ]
        ]);
    }

    /**
     * This test case error on endpoint create payment with error
     */
    public function testCreatePaymentError(): void
    {
        $data = ['total_product' => 50000, 'total_cash' => 10000];
        $paymentRepositoryMock = Mockery::mock(PaymentRepositoryInterface::class);

        $paymentRepositoryMock->shouldReceive('createPayment')
            ->withArgs($data)
            ->andThrow(new \Exception('error'))
            ->getMock();

        $this->app->instance(PaymentRepositoryInterface::class, $paymentRepositoryMock);

        $response = $this->post(route('payment.create'), $data, ['Accept' => 'application/json']);

        $response->assertStatus(500);
    }
}
