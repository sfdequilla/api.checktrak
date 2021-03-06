<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Payee;
use Faker\Generator as Faker;

$factory->define(Payee::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'code' => $faker->unique()->word,
        'company_id' => $faker->numberBetween($min = 1, $max = 6),
        // 'company_id' => 1,
        'payee_group_id' => $faker->numberBetween($min = 1, $max = 5),
    ];
});
