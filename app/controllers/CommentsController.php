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

    return NEIN;

    $this->validate_params(
      strict: ["id", "type", "emote"],
      optional: [],
    );

    return $Reaction->edit($this->params);
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

    /**
     * @var ?Comment
     */
    $Comment = CURRENT_VISITOR->comments()
      ->where("id", $this->params->id)
      ->first();

    $Comment?->delete();

    return OK;
  }
}
