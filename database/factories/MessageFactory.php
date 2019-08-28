<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Message;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {
    return [
        'email' => $faker->email,
        'texte' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
        'ad_id' => rand(1, 10),
    ];
});
