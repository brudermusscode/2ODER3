<?php

namespace Bruder\Model;

use Bruder\Bruder;
use Illuminate\Support\Collection;

class View extends Bruder
{

  /**
   * @var array
   */
  protected $fillable = [];

  /**
   * @return Visitor
   */
  public function visitor()
  {
    return $this->belongsTo(Visitor::class);
  }

  /**
   * @return Log
   */
  public function log()
  {
    return $this->belongsTo(Log::class);
  }
}
