<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\TruncateTable;

class PostSeeder extends Seeder
{
    use TruncateTable;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->truncate('posts');
        \App\Models\Post::factory(10)->untitled()->create()
            ->each(function($post) {
                $post->users()->sync([\Database\Factories\Helpers\FactoryHelper::getRandomModelId(\App\Models\User::class)]);
            });
    }
}
