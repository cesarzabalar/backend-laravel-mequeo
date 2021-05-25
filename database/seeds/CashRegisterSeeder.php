<?php

use \Carbon\Carbon;
use Illuminate\Database\Seeder;
use \App\Models\Log;
use \App\Models\CashRegister;
use \Illuminate\Support\Facades\DB;

class CashRegisterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataCashRegister = [
            [
                'type' => 'bills',
                'denomination' => 100000,
                'quantity' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'type' => 'bills',
                'denomination' => 50000,
                'quantity' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'type' => 'bills',
                'denomination' => 20000,
                'quantity' => 8,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'type' => 'bills',
                'denomination' => 10000,
                'quantity' => 10,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'type' => 'bills',
                'denomination' => 5000,
                'quantity' => 12,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'type' => 'bills',
                'denomination' => 1000,
                'quantity' => 30,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'type' => 'currency',
                'denomination' => 500,
                'quantity' => 15,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'type' => 'currency',
                'denomination' => 200,
                'quantity' => 20,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'type' => 'currency',
                'denomination' => 100,
                'quantity' => 20,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'type' => 'currency',
                'denomination' => 50,
                'quantity' => 50,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        $totalCashRegister = 0;
        foreach ($dataCashRegister as $cashRegister){
            $totalCashRegister += $cashRegister['denomination']*$cashRegister['quantity'];
        }

        $log = factory(Log::class)->create([
            'type' => 'load',
            'total' => $totalCashRegister
        ]);

        foreach ($dataCashRegister as $cashRegister) {
            $dataCashRegister = factory(CashRegister::class)->create($cashRegister);
            DB::table('cash_register_log')
                ->insert([
                    'cash_register_id' => $dataCashRegister->id,
                    'log_id' => $log->id,
                    'cash_register_total' => $cashRegister['quantity']
                ]);
        }
    }
}
