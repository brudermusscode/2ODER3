<?php

namespace Bruder\Model;

use Bruder\Bruder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class View extends Bruder
{
  /**
   * @var array
   */
  protected $fillable = [];

  /**
   * @return MorphTo<User|Visitor>
   */
  public function client()
  {
    return $this->morphTo();
  }

  /**
   * @return BelongsTo<Log>
   */
  public function log()
  {
    return $this->belongsTo(Log::class);
  }
}
