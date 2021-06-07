<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\CastMember;
use Faker\Generator as Faker;

$factory->define(CastMember::class, function (Faker $faker) {
    $types = CastMember::typeOptions();
    return [
        'name' => $faker->firstName,
        'type' => $types[array_rand(CastMember::typeOptions())]
    ];
});
