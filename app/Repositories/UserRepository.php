<?php

namespace App\Repositories;

use App\Events\Models\User\UserCreated;
use App\Exceptions\GeneralJsonException;
use App\Repositories\BaseRepository;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository
{
  public function create(array $attributes)
  {
    return DB::transaction(function() use($attributes) {
      $created = User::query()->create([
        'name' => data_get($attributes, 'name'),
        'email' => data_get($attributes, 'email'),
        'password' => data_get($attributes, 'password'),
      ]);
      throw_if(!$created, GeneralJsonException::class, 'Failed to create model.');
      event(new UserCreated($created));
      return $created;
    });
  }

  /**
   * @param User $user
   * @param array $attributes
   * @return mixed
   */
  public function update($user, array $attributes)
  {
    return DB::transaction(function () use($user, $attributes) {
      $updated = $user->update([
        'name' => data_get($attributes, 'name', $user->name),
        'email' => data_get($attributes, 'email', $user->email),
        'password' => data_get($attributes, 'password', $user->password),
      ]);
      throw_if(!$updated, GeneralJsonException::class, 'Failed to update user.');
      return $user;
    });
  }

  /**
   * @param User $user
   * @return mixed
   */
  public function forceDelete($user)
  {
    return DB::transaction(function() use($user) {
      $deleted = $user->forceDelete();
      throw_if(!$deleted, GeneralJsonException::class, 'Failed to delete user.');
      return $deleted;
    });
  }
}