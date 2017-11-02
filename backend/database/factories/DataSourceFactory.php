<?php

use App\DataSource;
use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(DataSource::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'description' => $faker->sentences(),
        'url' => $faker->url,
    ];
});
