<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use App\Repositories\PostRepository;
use Illuminate\Support\Facades\URL;

/**
 * @group Posts
 * 
 * API to manage users resource.
 */
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {        
        return PostResource::collection(Post::query()->paginate($request->page_size ?? 10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request, PostRepository $repository)
    {
        return new PostResource($repository->create($request->only(['title', 'body', 'user_ids'])));
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
    public function update(UpdatePostRequest $request, Post $post, PostRepository $repository)
    {
        $updated = $repository->update($post, $request->only(['title', 'body', 'user_ids']));
        if(!$updated) {
            return new JsonResponse([
                'errors' => [
                    'Failed to update model',
                ],
            ], 400);
        }
        return new PostResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, PostRepository $repository)
    {
        $deleted = $repository->forceDelete($post);
        if(!$deleted) {
            return new JsonResponse([
                'errors' => [
                    'Could not delete resource',
                ],
            ], 400);
        } else {
            return new JsonResponse([
                'data' => 'Successfully deleted the post',
            ]);
        }
    }

    /**
     * Share a specified post with an expiring URL
     */
    public function share(Post $post)
    {
        $url = URL::temporarySignedRoute('shared.post', now()->addDays(30), [
            'post' => $post->id,
        ]);

        return new JsonResponse([
            'data' => $url,
        ]);
    }
}
