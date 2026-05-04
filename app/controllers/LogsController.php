<?php

namespace Bruder\Controller;

use Bruder\Controller\Controller;
use Bruder\Model\Log;
use Bruder\Model\Project;

class LogsController extends Controller
{

  /**
   * @return string
   */
  public function create()
  {

    $this->validate_params(
      strict: ["project_id"],
      optional: ["file", "name", "description",],
    );

    $this->authorize();

    /**
     * @var ?Project
     */
    $this->params->Project =
      Project::findOrReturn($this->params->project_id, "No project");

    return (new Log)->new($this->params);
  }

  /**
   * @return string
   */
  public function update()
  {

    authorize();

    $this->validate_params(
      strict: ["id"],
      optional: ["name", "description", "file"],
    );

    /**
     * @var ?Log
     */
    $Log = Log::findOrReturn($this->params->id, "No log");

    return $Log->edit($this->params);
  }

  /**
   * @return string
   */
  public function delete()
  {

    authorize();

    $this->validate_params(
      strict: ["id"],
      optional: [],
    );

    /**
     * @var ?Log
     */
    $Log = Log::findOrReturn($this->params->id, "No log");
    $Log->delete();

    return success();
  }
}
