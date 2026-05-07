<?php

namespace Bruder\Model;

use Bruder\Bruder;
use Illuminate\Support\Collection;

class Visitor extends Bruder
{

  /**
   * @var array
   */
  protected $fillable = [
    "ip",
  ];

  /**
   * @return ?Collection<Reaction>
   */
  public function reactions()
  {
    return $this->hasMany(Reaction::class);
  }
}
