<?php

namespace Bruder\Model;

use Bruder\Bruder;
use Illuminate\Support\Collection;

class Project extends Bruder
{

  /**
   * @var array
   */
  protected $fillable = [
    "name",
    "url",
    "logo",
  ];

  /**
   * @return Collection<Log>
   */
  public function logs()
  {
    return $this->hasMany(Log::class);
  }
}
