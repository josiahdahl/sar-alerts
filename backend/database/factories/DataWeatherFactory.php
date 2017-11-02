<?php

use App\DataWeather;
use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(DataWeather::class, function (Faker $faker) {
    return [
        'temperature' => $faker->randomFloat(1, -20, 40),
        'short_description' => $faker->randomElement(['fog', 'snow', 'rain']),
        'long_description' => $faker->sentence(4),
        'pressure' => $faker->randomFloat(1, 700, 1200),
        'humidity' => $faker->randomFloat(1, 10, 90),
    ];
});
