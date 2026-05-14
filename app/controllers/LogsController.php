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
      strict: ["file"],
      optional: [],
    );

    $this->authorize();

    return (new Log)->new($this->params);
  }

  /**
   * @return string
   */
  public function update()
  {

    $this->validate_params(
      strict: ["id"],
      optional: ["project_id", "name", "description", "file", "thumb_selected"],
    );

    $this->authorize();

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

    $this->validate_params(
      strict: ["id"],
      optional: [],
    );

    $this->authorize();

    /**
     * @var ?Log
     */
    $Log = Log::findOrReturn($this->params->id, "No log");
    $Log->delete();

    return success();
  }
}
