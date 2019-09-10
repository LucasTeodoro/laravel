<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\CastMember;
use Faker\Generator as Faker;

$factory->define(CastMember::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName,
        'type' => rand(1, 10) % 2 == 0 ? 2 : 1
    ];
});
