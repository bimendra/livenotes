<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Database\Factories\Helpers\FactoryHelper;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        

        return [
            'body' => [],
            'user_id' => FactoryHelper::getRandomModelId(\App\Models\User::class),
            'post_id' => FactoryHelper::getRandomModelId(\App\Models\Post::class),
        ];
    }
}
