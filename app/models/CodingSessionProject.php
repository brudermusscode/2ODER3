<?php

namespace Bruder\Model;

use Bruder\Bruder;
use DateTime;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CodingSessionProject extends Bruder
{
  /**
   * @var array
   */
  protected $fillable = [
    "title",
    "stopped_at",
  ];

  /**
   * @return BelongsTo<Project>
   */
  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  /**
   * @return BelongsTo<CodingSession>
   */
  public function coding_session()
  {
    return $this->belongsTo(CodingSession::class);
  }

  /**
   * @return bool
   */
  public function stop()
  {
    if (!$this->stopped_at)
      $this->update([
        "stopped_at" => CURRENT_TIMESTAMP,
      ]);

    return true;
  }

  /**
   * @return int
   */
  public function time_elapsed()
  {
    $start_time = $this->created_at;
    $end_time = new DateTime($this->stopped_at ?? "now");

    $diff = $start_time->diff($end_time);

    return $diff->h > 0 ? $diff->h . " Std" : (
      $diff->i > 0 ? $diff->i . " Min" : $diff->s . " Sek"
    );
  }
}
