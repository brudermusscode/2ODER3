<?php

namespace Bruder\Model;

use Bruder\Bruder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
    $Client = $params->Client;

    /**
     * @var Log
     */
    $Log = $params->Log;

    /**
     * @var self
     */
    $Comment = self::make();

    # ? Log
    $Comment->log()->associate($Log);

    # ? Visitor
    $Comment->client()->associate($Client);

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
   * @return MorphTo<User|Visitor>
   */
  public function client()
  {
    return $this->morphTo("client");
  }

  /**
   * @return BelongsTo<Log>
   */
  public function log()
  {
    return $this->belongsTo(Log::class);
  }
}
