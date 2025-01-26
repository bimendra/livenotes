<?php

namespace Tests\Feature\Api\V1\Post;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Post;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $posts = Post::factory(10)->create();
        $postIds = $posts->map(fn($post) => $post->id);

        $response = $this->json('get', '/api/v1/posts');
        $response->assertStatus(200);
 
        $data = $response->json('data');
        collect($data)->each(fn($post) => $this->assertTrue(in_array($post['id'], $postIds->toArray())));
    }

    public function test_show()
    {
        $post = Post::factory()->create();
        $response = $this->json('get', "/api/v1/posts/{$post->id}");
        $response->assertStatus(200);
        $this->assertSame($post->id, $response->json('data.id'), 'Post creation failed');
    }

    public function test_create()
    {
        $post = Post::factory()->make();        
        $response = $this->json('post', '/api/v1/posts/', $post->toArray());
        $result = $response->assertStatus(201)->json('data');      
        $result = collect($result)->only(array_keys($post->getAttributes()));
        $result->each(function($value, $field) use($post) {
            $this->assertSame($value, data_get($post, $field), 'Post creation failed');
        });
    }

    public function test_update()
    {
        $post = Post::factory()->create();
        $response = $this->json('put', "/api/v1/posts/{$post->id}", ['title' => 'TEST 01']);
        $this->assertSame('TEST 01', $response->json('data.title'), 'Post update failed');
    }
}
