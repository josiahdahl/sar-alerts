<?php

use App\DataTide;
use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(DataTide::class, function (Faker $faker) {
    static $data_source;

    return [
        'data_source_id' => $data_source ?: $data_source = $faker->numberBetween(1, 2),
        'time' => $faker->dateTime('now', 'PDT'),
        'height' => $faker->randomFloat(1, 0, 4),
        'high_low' => $faker->randomElement(['high', 'low']),
        'unit' => $faker->randomElement(['m', 'ft']),
    ];
});
