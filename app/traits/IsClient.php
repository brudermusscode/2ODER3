<?php

namespace Bruder\Trait;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Bruder\Model\View;
use Bruder\Model\Comment;
use Bruder\Model\Reaction;

trait IsClient
{

  # Set up an array with all the relation function names
  # as values, so we can easily iterate through them. Useful
  # for transforming a Visitor into a User.
  public array $model_relations = [
    "comments",
    "reactions",
    "views"
  ];

  /**
   * @var array
   */
  protected $attributes = [
    "nickname" => "$: &niemand",
    "color" => "#fc2a90",
  ];

  /**
   * Intelephense doesn't understand that this trait belongs to
   * Eloquent Model's so it shows hasMany() etc. with suqiggles.
   * We need to add this so it can resolve the relations.
   *
   * @return Model
   */
  private function model()
  {
    return $this;
  }

  /**
   * @return MorphMany<Comment>
   */
  public function comments()
  {
    return $this->model()->morphMany(Comment::class, "client");
  }

  /**
   * @return MorphMany<Reaction>
   */
  public function reactions()
  {
    return $this->model()->morphMany(Reaction::class, "client");
  }

  /**
   * @return MorphMany<View>
   */
  public function views()
  {
    return $this->model()->morphMany(View::class, "client");
  }
}
