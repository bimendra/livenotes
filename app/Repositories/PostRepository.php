<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Post;

class PostRepository extends BaseRepository
{
  public function create(array $attributes)
  {
    return DB::transaction(function() use($attributes) {
       $created = Post::query()->create([
            'title' => data_get($attributes, 'title', 'Untitled'),
            'body' => data_get($attributes, 'body', []),
        ]);

        $created->users()->sync(data_get($attributes, 'user_ids', []));
        
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

        if(!$updated) {
            throw new \Exception('Failed to update post');            
        } else {
          $post->users()->sync(data_get($attributes, 'user_ids', []));
          return $post;
        }
    });
  }

  public function forceDelete($post)
  {
    return DB::transaction(function() use($post) {
      $deleted = $post->forceDelete();
      if(!$deleted) {
        throw new \Exception('Cannot delete post.');
      } else {
        return $deleted;
      }      
    });
  }
}