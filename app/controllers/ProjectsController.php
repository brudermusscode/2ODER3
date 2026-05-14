<?php

namespace Bruder\Controller;

use Bruder\Controller\Controller;
use Bruder\Model\Project;

class ProjectsController extends Controller
{

  /**
   * @return string
   */
  public function create()
  {

    $this->validate_params(
      strict: ["name", "url", "file"],
      optional: [],
    );

    $this->authorize();

    return (new Project)->new($this->params);
  }

  /**
   * @return string
   */
  // TODO: update()
  public function update()
  {

    $this->validate_params(
      strict: ["id"],
      optional: ["name", "url", "file"],
    );

    $this->authorize();

    return success();

    // return $Project->edit($this->params);
  }

  /**
   * @return string
   */
  // TODO: delete()
  public function delete()
  {

    $this->validate_params(
      strict: ["id"],
      optional: [],
    );

    $this->authorize();

    return success();
  }
}
