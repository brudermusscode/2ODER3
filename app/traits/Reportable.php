<?php

namespace Bruder\Trait;

use Bruder\Model\Report;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reportable
{

  /**
   * @return Model
   */
  private function model() {}

  /**
   * @return HasMany<Report>
   */
  public function reports()
  {
    return $this->model()->morphMany(Report::class, "reference");
  }
}
