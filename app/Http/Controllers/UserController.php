<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Repositories\UserRepository;

/**
 * @group Users
 */
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @queryParam page_size int Size per page. Defaults to 20. Example: 20.
     * @queryParam page int Page to view.
     * 
     * @apiResourceCollection App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     * 
     * @return ResourceCollection
     */
    public function index(Request $request)
    {
        return UserResource::collection(User::paginate($request->page_size ?? 20));
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @bodyParam name string required Name of the user. Example: John Doe
     * @bodyParam email string required Email of the user. Example: doe@doe.com
     * @param \Illuminate\Http\Request $request
     * @return UserResource
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     */
    public function store(StoreUserRequest $request, UserRepository $repository)
    {
        return new UserResource($repository->create($request->only(['name', 'email', 'password'])));
    }

    /**
     * Display the specified resource.
     * 
     * @urlParam User int required User user
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     * @bodyParam name string Name of the user. Example: John Doe
     * @bodyParam email string Email of the user. Example: doe@doe.com
     * @param \Illuminate\Http\Request $request
     * @return UserResource
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     */
    public function update(Request $request, User $user, UserRepository $repository)
    {
        return new UserResource($repository->update($user, $request->only(['name', 'email', 'password'])));
    }

    /**
     * Remove the specified resource from storage.
     * @response 200 { 
     *   "data": "success"
     * }
     */
    public function destroy(User $user, UserRepository $repository)
    {
        $repository->forceDelete($user);
        return new \Illuminate\Http\JsonResponse([
            'data' => 'success',
        ]);
    }
}
