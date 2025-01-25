<?php

namespace Tests\Unit;

use App\Exceptions\GeneralJsonException;
use App\Http\Resources\PostResource;
use App\Repositories\PostRepository;
use Tests\TestCase;
use App\Models\Post;

class PostRepositoryTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_create(): void
    {
        $repository = $this->app->make(\App\Repositories\PostRepository::class);
        $payload = [
            'title' => 'TEST',
            'body' => [],
        ];
        $result = $repository->create($payload);
        $this->assertSame($payload['title'], $result->title, 'Post created does not have the same title');
    }

    /**
     * Update post
     */
    public function test_update()
    {
        $repository = $this->app->make(PostRepository::class);
        $test_post = Post::factory(1)->create()[0];
        
        $payload = [
            'title' => 'TEST UPDATED',
        ];

        $updated = $repository->update($test_post, $payload);
        $this->assertSame($payload['title'], $updated->title, 'Post updated does not have the same title');
    }

    public function test_delete_will_throw_exception_when_delete_non_existing_post() {
        $repository = $this->app->make(PostRepository::class);
        $test_post = Post::factory(1)->make()->first();
        $this->expectException(GeneralJsonException::class);
        $repository->forceDelete($test_post);
    }

    public function test_delete()
    {
        $repository = $this->app->make(PostRepository::class);
        $test_post = Post::factory(1)->create()[0];
        $repository->forceDelete($test_post);
        $found = Post::query()->find($test_post->id);
        $this->assertSame(null, $found, 'Post is not deleted');
    }
}
