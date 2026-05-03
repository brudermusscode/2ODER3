<?php

namespace Bruder\Model;

use Bruder\Bruder;

class Project extends Bruder
{

  /**
   * @var array
   */
  protected $fillable = [
    "name",
    "url",
    "logo",
  ];

  /**
   * @return
   */
}
