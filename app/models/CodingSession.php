<?php

namespace Bruder\Model;

use Bruder\Bruder;
use DateTime;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
  public function current_project()
  {
    return $this->belongsTo(Project::class, "project_id");
  }

  /**
   * @return HasMany<CodingSessionProject>
   */
  public function projects()
  {
    return $this->hasMany(CodingSessionProject::class);
  }

  /**
   * Set this instance to be finished. Should not be able to be worked
   * on anymore.
   *
   * @return bool
   */
  public function finish()
  {

    # Update all Coding Session Project history entries to be stopped.
    $this->projects()->each(fn($CSP) => $CSP->stop());

    # Set this instance to be finished.
    return $this->update([
      "finished_at" => CURRENT_TIMESTAMP,
    ]);
  }

  /**
   * @return object
   */
  public function time_elapsed()
  {
    return $this->created_at->diff(new DateTime($this->finished_at));
  }
}
