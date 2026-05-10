<?php

namespace Bruder\Controller;

use Bruder\Controller\Controller;
use Bruder\Model\Log;
use Bruder\Model\Reaction;

class ReactionsController extends Controller
{

  /**
   * @return string
   */
  public function create()
  {

    $this->validate_params(
      strict: ["log_id", "type", "emote"],
      optional: [],
    );

    /**
     * @var ?Reaction
     */
    $Reaction = CURRENT_VISITOR->reactions()
      ->where([
        "log_id" => $this->params->log_id,
        "emote" => $this->params->emote,
        "type" => $this->params->type,
      ])->first();

    # Rebuild params and delete the Reaction if one exists.
    if ($Reaction) {
      $this->params = ["id" => $Reaction->id];
      $log_id = $Reaction->log_id;
      $Reaction->delete();

      return success(data: ["Object" => ["log_id" => $log_id]]);
    }

    /**
     * @var ?Log
     */
    $this->params->Log =
      Log::findOrReturn($this->params->log_id, "No Log");

    return (new Reaction)->new($this->params);
  }

  /**
   * @return string
   */
  public function update()
  {

    return error();

    $this->validate_params(
      strict: ["id", "type", "emote"],
      optional: [],
    );

    /**
     * @var ?Log
     */
    $Reaction = Reaction::findOrReturn($this->params->id, "No Reaction");

    return $Reaction->edit($this->params);
  }

  /**
   * @return string
   */
  public function delete()
  {

    $this->validate_params(
      strict: ["log_id", "emote"],
      optional: [],
    );

    // pdie($this->params);

    /**
     * Delete the reaction in one run or return an error, if no
     * reaction exists here with the given id.
     */
    $Reaction = CURRENT_VISITOR->reactions()
      ->where([
        "log_id" => $this->params->log_id,
        "emote" => $this->params->emote,
      ])
      ->first();

    // pdie($Reaction);

    if (!$Reaction)
      return error("Kein Log");

    $log_id = $Reaction->log_id;
    $Reaction->delete();

    return success(data: ["Object" => ["log_id" => $log_id]]);
  }
}
