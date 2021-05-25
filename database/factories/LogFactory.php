<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Log;
use Faker\Generator as Faker;

$factory->define(Log::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement(['load', 'entry', 'egress']),
        'total' => $faker->randomElement([100000,50000,20000,10000,5000,1000,500,200,100,50])
    ];
});
