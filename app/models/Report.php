<?php

namespace Bruder\Model;

use Bruder\Bruder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Bruder
{

  /**
   * @var array
   */
  protected $fillable = [
    "reference_id",
    "reference_type",
  ];

  protected static array $types = [
    "comment" => Comment::class,
  ];

  /**
   * @param object $params
   * @return string
   */
  public function new(object $params) {}

  /**
   * @return MorphTo<Comment>
   */
  public function reference()
  {
    return $this->morphTo();
  }

  /**
   * @return HasOne<User>
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  /**
   * Returns the class of the type reference itself which can then
   * be used to validate further properties.
   *
   * @param string $type
   * @return ?Comment
   */
  public static function valid(string $type, int $id)
  {
    $Class = self::$types[$type] ?? null;

    if (!$Class) return null;

    return $Class::find($id);
  }
}
