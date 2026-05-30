<?php

namespace Bruder\Model;

use Bruder\Bruder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CodingSession extends Bruder
{
  /**
   * @var array
   */
  protected $fillable = [
    "finished_at"
  ];

  /**
   * @param object $params
   * @return string
   */
  public function new(object $params)
  {

    return success();
  }

  /**
   * @return BelongsTo<Project>
   */
  public function project()
  {
    return $this->belongsTo(Project::class);
  }
}
