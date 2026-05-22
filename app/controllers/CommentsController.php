<?php

namespace Bruder\Controller;

use Bruder\Controller\Controller;
use Bruder\Model\Log;
use Bruder\Model\Comment;

class CommentsController extends Controller
{

  /**
   * @return string
   */
  public function create()
  {

    $this->validate_params(
      strict: ["log_id", "comment"],
      optional: [],
    );

    $this->can_interact(die: true);

    /**
     * @var ?Log
     */
    $this->params->Log =
      Log::findOrReturn($this->params->log_id, "No Log");

    return (new Comment)->new($this->params);
  }

  /**
   * @return string
   */
  public function update()
  {

    $this->validate_params(
      strict: ["id", "type", "emote"],
      optional: [],
    );

    $this->can_interact(die: true);

    return success();
  }

  /**
   * @return string
   */
  public function delete()
  {

    $this->validate_params(
      strict: ["id"],
      optional: [],
    );

    $this->can_interact(die: true);

    /**
     * @var ?Comment
     */
    $Comment = CURRENT_BRUDER->comments()
      ->where("id", $this->params->id)
      ->first();

    $Comment?->delete();

    return OK;
  }
}
