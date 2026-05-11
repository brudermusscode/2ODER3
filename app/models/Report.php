<?php

namespace Bruder\Model;

use Bruder\Bruder;
use Illuminate\Support\Collection;

class Report extends Bruder
{

  /**
   * @var array
   */
  protected $fillable = [
    "reference_id",
    "type",
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
   * @return Visitor
   */
  public function visitor()
  {
    return $this->belongsTo(Visitor::class);
  }

  /**
   * @return ?Comment
   */
  public function comment()
  {
    return $this->belongsTo(Comment::class, "reference_id", "id");
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
