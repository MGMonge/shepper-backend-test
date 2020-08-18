<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Location;
use Faker\Generator as Faker;

$factory->define(Location::class, function (Faker $faker) {
    return [
        'title'     => $faker->sentence,
        'label'     => sprintf('%s, %s', $faker->city, $faker->countryCode),
        'latitude'  => $faker->latitude,
        'longitude' => $faker->longitude,
        'radius'    => $faker->randomFloat(2, 0.5, 50),
    ];
});