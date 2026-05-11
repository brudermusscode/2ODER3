<?php

namespace Bruder\Model;

use Bruder\Bruder;

class Comment extends Bruder
{

  /**
   * @var array
   */
  protected $fillable = [
    "comment",
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
    $Comment = self::make();

    # ? Log
    $Comment->log_id = $Log->id;

    # ? Visitor
    $Comment->visitor_id = $Visitor->id;

    # ? Comment
    $Comment->comment = $params->comment ?: die(error("Comment is empty"));

    $Comment->save();

    # Begin output buffer and include a comment partial.
    ob_start();
    include TEMPLATE . "/comment/_comment.php";

    return success(data: [
      "HTML" => ob_get_clean(),
      "Object" => $Comment,
    ]);
  }

  /**
   * @param object $params
   * @return string
   */
  public function edit(object $params)
  {
    return success(data: $this);
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

  /**
   * @return Collection<Report>
   */
  public function reports()
  {
    return $this->hasMany(Report::class, "reference_id", "id");
  }
}
