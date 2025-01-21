<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PostResource::collection(Post::query()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $created = DB::transaction(function() use($request) {
            $created = Post::query()->create([
                'title' => $request->title,
                'body' => $request->body,
            ]);    
            $created->users()->sync($request->user_ids);
            return $created;
        });

        return new PostResource($created);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $updated = DB::transaction(function() use($request, $post) {
            $updated = $post->update([
                'title' => $request->title ?? $post->title,
                'body' => $request->body ?? $post->body,
            ]);
            if($updated && $request->user_ids && count($request->user_ids)) {
                $post->users()->sync($request->user_ids);
            }
            return $updated;
        });
        if(!$updated) {
            return new JsonResponse([
                'errors' => [
                    'Failed to update model',
                ],
            ], 400);
        }
        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $deleted = $post->forceDelete();
        if(!$deleted) {
            return new JsonResponse([
                'errors' => [
                    'Could not delete resource',
                ],
            ], 400);
        }
    }
}
