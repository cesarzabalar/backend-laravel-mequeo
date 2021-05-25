<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CashRegister;
use Faker\Generator as Faker;

$factory->define(CashRegister::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement(['currency', 'bills']),
        'denomination' => $faker->randomNumber(1),
        'quantity' => $faker->randomNumber(1)
    ];
});
