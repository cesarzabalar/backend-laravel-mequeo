<?php

namespace Tests\Feature;

use App\Models\CashRegister;
use App\Services\Interfaces\CashRegisterServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Mockery;
use Tests\TestCase;

class CashRegisterControllerTest extends TestCase
{
    use WithoutMiddleware, RefreshDatabase;

    /**
     * This test endpoint create load balance cash register error
     *
     * @return void
     */
    public function testCreateBaseCashRegisterValidationError(): void
    {
        $response = $this->post(route('cash.add'), [], ['Accept' => 'application/json']);
        $response->assertStatus(422);
        $response->assertJson(
            [
                'message' => 'The given data was invalid.',
                'errors' => [
                    'type' => [
                        'The type field is required.'
                    ],
                    'denomination' => [
                        'The denomination field is required.'
                    ],
                    'denomination' => [
                        'The denomination field is required.'
                    ]
                ]
            ]
        );
    }

    /**
     * This test endpoint create load balance cash register successful
     *
     * @return void
     */
    public function testCreateBaseCashRegisterSuccess(): void
    {
        $data = [
            'type' => 'bills',
            'denomination' => 10000,
            'quantity' => 2
        ];
        $response = $this->post(route('cash.add'), $data, ['Accept' => 'application/json']);

        $response->assertStatus(201);
        $response->assertJson([
            'message' => __('cash_register.create_success'),
            'success' => true,
            'data' => []
        ]);
    }

    /**
     * This test endpoint empty cash register
     *
     * @return void
     */
    public function testGetStatusCashRegisterEmpty(): void
    {
        $response = $this->get(route('cash.status'), ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => __('cash_register.status_register'),
            'success' => true,
            'data' => [
                'total_cash_register' => 0,
                'inventory' => []
            ]
        ]);
    }


    /**
     * This test endpoint get balance cash register succesful
     *
     * @return void
     */
    public function testGetStatusCashRegisterSuccess(): void
    {
        $cashRegisterFactory = factory(CashRegister::class)->create();
        $response = $this->get(route('cash.status'), ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => __('cash_register.status_register'),
            'success' => true,
            'data' => [
                'total_cash_register' => $cashRegisterFactory->denomination * $cashRegisterFactory->quantity,
                'inventory' => [
                    $cashRegisterFactory->type => [
                        [
                            'denomination' => $cashRegisterFactory->denomination,
                            'quantity' => $cashRegisterFactory->quantity
                        ]
                    ]
                ]
            ]
        ]);
    }

    /**
     * This test endpoint create load balance with error exceptions
     *
     * @return void
     */
    public function testCreateBaseCashRegisterError(): void
    {
        $data = [
            'type' => 'bills',
            'denomination' => 10000,
            'quantity' => 2
        ];
        $expected = [
            'message' => 'An error has occured',
            'success' => false,
            'data' => []
        ];
        $cashRegisterServiceMock = Mockery::mock(CashRegisterServiceInterface::class);
        $cashRegisterServiceMock->shouldReceive('createBaseCashRegister')
            ->with($data)
            ->once()->andReturn($expected)->getMock();

        $this->app->instance(CashRegisterServiceInterface::class, $cashRegisterServiceMock);
        $response = $this->post(route('cash.add'), $data, ['Accept' => 'application/json']);

        $response->assertStatus(500);
        $response->assertJson($expected);
    }


    /**
     * This test endpoint set empty the cash register with errors
     *
     * @return void
     */
    public function testSetEmptyCashRegisterError(): void
    {
        $response = $this->get(route('cash.empty'), ['Accept' => 'application/json']);

        $response->assertStatus(500);
        $response->assertJson([
            'message' => __('cash_register.system_error'),
            'success' => false,
            'data' => []
        ]);
    }

    /**
     * This test endpoint set empty the cash register succesful
     *
     * @return void
     */
    public function testSetEmptyCashRegisterSuccess(): void
    {
        $this->artisan('db:seed --class=CashRegisterSeeder');
        $response = $this->get(route('cash.empty'), ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => __('cash_register.empty_cash_register_success'),
            'success' => true,
            'data' => []
        ]);
    }

}
