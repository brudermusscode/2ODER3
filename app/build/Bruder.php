<?php

namespace Bruder;

use Bruder\Trait\ProcessesRequests;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Bruder extends Model
{
  use ProcessesRequests;

  protected string $route_key = "id";

  public function __call($method, $parameters)
  {

    if ($method === 'link')
      return $this->build_link($parameters[0] ?? null, $parameters[1] ?? false);

    return parent::__call($method, $parameters);
  }

  public static function __callStatic($method, $parameters)
  {
    if ($method === 'link')
      return new static()->build_link($parameters[0] ?? null, $parameters[1] ?? false);

    return parent::__callStatic($method, $parameters);
  }

  /**
   * Returns the url to the given action for this instance.
   *
   * @param ?string $action
   * @param bool $get
   * @return string
   * @throws \Exception
   */
  public function build_link(
    ?string $action = null,
    bool $get = false
  ) {

    $key_actions = ["update", "edit", "delete"];
    $singular = Str::singular($this->getTable());
    $base = str_replace("_", "-", $singular);
    $seperator = !$get ? "/" : ":";

    # Add a slash at the beginning for normal urls.
    $base = !$get ? "/$base" : $base;

    if (in_array($action, $key_actions) && !$this->exists)
      throw new \Exception("Cannot accept action »{$action}« as instance of class " . get_class($this) . " doesn't exist.");

    if (method_exists($this, "parent") && $this->parent())
      $base = $this->parent()->link(get: $get) . $base;

    return $base . $seperator .
      match ($action) {
        "new" => "new",
        "create" => "create",
        "update" => $this->{$this->route_key} . $seperator . "update",
        "edit" => $this->{$this->route_key} . $seperator . "edit",
        "delete" => $this->{$this->route_key} . $seperator . "delete",
        default => $this->{$this->route_key},
      };
  }

  /**
   * @return ?object
   */
  public function data()
  {
    return $this->exists ? (object) $this->getAttributes() : null;
  }

  /**
   * @return string
   */
  public function current_timestamp()
  {
    return date("Y-m-d H:i:s", time());
  }

  /**
   * Path has to be appended with a trailing slash /.
   *
   * @param string $path
   * @return string
   */
  public function template(string $path)
  {
    return _root() . "/app/templates$path";
  }

  /** ,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,, */
  /** ,,,,,,,,,,,,,,,,,,, DATABASE INTERACTIONS ,,,,,,,,,,,,,,,,,,,, */
  /** ,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,, */

  /**
   * @return void
   * @throws \Throwable
   */
  public function db_transaction()
  {
    return $this->getConnection()
      ->beginTransaction();
  }

  /**
   * @return void
   * @throws \Throwable
   */
  public function db_commit()
  {
    return $this->getConnection()
      ->commit();
  }

  /**
   * @return void
   * @throws \Throwable
   */
  public function db_rollback()
  {
    return $this->getConnection()
      ->rollBack();
  }

  /**
   * @return
   */
  // TODO: Make this static & non-static
  public static function findOrReturn(mixed $id = null, ?string $die_message = null)
  {
    return static::find($id)
      ?? die((new self)->error($die_message ?? "<strong>Model Instance not found.</strong>"));
  }

  /**
   * Dies when it finds a model instance.
   */
  public static function findAndReturn(mixed $id = null, ?string $die_message = null)
  {
    return static::find($id)
      ? die((new self)->error($die_message ?? "<strong>Model Instance not found.</strong>"))
      : null;
  }
}
