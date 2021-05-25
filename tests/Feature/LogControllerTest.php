<?php

namespace Tests\Feature;

use App\Models\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class LogControllerTest extends TestCase
{
    use WithoutMiddleware, RefreshDatabase;

    /**
     * This test is case getLogs success
     */
    public function testGetLogsSuccess(): void
    {
        $this->artisan('db:seed --class=CashRegisterSeeder');

        $response = $this->get(route('logs.list'), ['Accept' => 'application/json']);

        $response->assertStatus(200);
    }

    /**
     * This test is case getLogs Error
     */
    public function testGetLogsError(): void
    {
        $response = $this->get(route('logs.list'), ['Accept' => 'application/json']);

        $response->assertStatus(500);
    }

    /**
     * This case is endpoint get status by date Success
     */
    public function testGetStatusByDateSuccess(): void
    {
        $length = 5;
        $logsEntryFactory = factory(Log::class, $length)->create(['created_at' => '2021-05-23 01:00:00', 'type' => 'entry'])->toArray();
        $logsEgressFactory = factory(Log::class, $length)->create(['created_at' => '2021-05-23 01:00:00', 'type' => 'egress'])->toArray();
        $totalRegister = 0;

        for ($i = 0; $length > $i; $i++){
            $totalRegister += $logsEntryFactory[$i]['total'];
            $totalRegister -= $logsEgressFactory[$i]['total'];
        }

        $response = $this->get(route('logs.listByDate',
            ['date' => '2021-05-24 23:59:59']),
            ['Accept' => 'application/json']
        );

        $response->assertStatus(200);
        $response->assertJson([
            'message' => '',
            'success' => true,
            'data' => [
                'total_cash_register' => $totalRegister
            ]
        ]);
    }

    /**
     * This case is endpoint get status by date error
     */
    public function testGetStatusByDateError(): void
    {
        $response = $this->get(route('logs.listByDate',
            ['date' => '2021-04-22 10:01:01']),
            ['Accept' => 'application/json']
        );

        $response->assertStatus(500);
        $response->assertJson([
            'message' => __('cash_register.no_logs'),
            'success' => false,
            'data' => []
        ]);
    }
}
