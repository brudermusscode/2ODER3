<?php

namespace Bruder\Model;

use Bruder\Bruder;

class Reaction extends Bruder
{

  /**
   * @var array
   */
  protected $fillable = [
    "type",
    "emote",
  ];

  public static array $valid_reaction_emotes = ["😃", "🤣", "😍", "🤯", "😭", "🤡", "🤬",];
  public static array $valid_reaction_types = [
    "emote",
  ];

  /**
   * @param object $params
   * @return string
   */
  public function new(object $params)
  {

    /**
     * @var Visitor
     */
    $Visitor = $params->Visitor;

    /**
     * @var Log
     */
    $Log = $params->Log;

    /**
     * @var self
     */
    $Reaction = self::make();

    # ? Emote
    if (!in_array($params->emote, self::$valid_reaction_emotes))
      return error("Invalid Reaction emote");

    $Reaction->emote = $params->emote;

    # ? Type
    if (!in_array($params->type, self::$valid_reaction_types))
      return error("Invalid Reaction type");

    $Reaction->type = $params->type;
    $Reaction->log_id = $Log->id;
    $Reaction->visitor_id = $Visitor->id;
    $Reaction->save();

    # For the frontend manipulation, I include the reaction
    # partial and set some attributes to let it look correctly.
    ob_start();
    $Reaction->reacted = true;
    $Reaction->count = $Log->reactions()
      ->where([
        "emote" => $params->emote,
        "type" => $params->type,
      ])
      ->count();
    $SelectedLog = $Log;
    include TEMPLATE . "/reaction/_reaction.php";

    return success(data: [
      "HTML" => ob_get_clean(),
      "Object" => $Reaction,
    ]);
  }

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
