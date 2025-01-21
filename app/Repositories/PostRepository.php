<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Post;
use App\Exceptions\GeneralJsonException;

class PostRepository extends BaseRepository
{
  public function create(array $attributes)
  {
    return DB::transaction(function() use($attributes) {
       $created = Post::query()->create([
            'title' => data_get($attributes, 'title', 'Untitled'),
            'body' => data_get($attributes, 'body', []),
        ]);
        throw_if(!$created, GeneralJsonException::class, 'Failed to create post');        
        return $created;
    });    
  }

  public function update($post, array $attributes)
  {
    return DB::transaction(function() use($post, $attributes) {
        $updated = $post->update([
            'title' => data_get($attributes, 'title', $post->title),
            'body' => data_get($attributes, 'body', $post->body),
        ]);
        throw_if(!$updated, GeneralJsonException::class, 'Failed to update post');
        return $post;
    });
  }

  public function forceDelete($post)
  {
    return DB::transaction(function() use($post) {
      $deleted = $post->forceDelete();
      throw_if(!$deleted, GeneralJsonException::class, 'Failed to delete. ');
      return $deleted;      
    });
  }
}