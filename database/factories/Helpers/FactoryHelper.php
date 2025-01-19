<?php

namespace Database\Factories\Helpers;

class FactoryHelper
{
  /**
   * This function will get a random model id from the database.
   * @param string | HasFactory $model
   */
  public static function getRandomModelId(string $model)
  {
    $count = $model::query()->count();
    if($count > 0) {
        $id = rand(1, $count);
    } else {
        $id = $model::factory()->create()->id;
    }
    return $id;
  }
}