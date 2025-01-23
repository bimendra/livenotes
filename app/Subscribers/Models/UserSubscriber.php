<?php

namespace App\Subscribers\Models;

use App\Events\Models\user\UserCreated;
use App\Listeners\SendWelcomeEmail;
use Illuminate\Contracts\Events\Dispatcher;

class UserSubscriber
{
  public function subscribe(Dispatcher $events)
  {
    $events->listen(UserCreated::class, SendWelcomeEmail::class);
  }
}