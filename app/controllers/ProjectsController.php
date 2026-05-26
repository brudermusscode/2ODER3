<?php

namespace Bruder\Controller;

use Bruder\Application\Logger;
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
  public function update()
  {

    $this->validate_params(
      strict: ["id", "name", "url"],
      optional: ["file"],
    );

    $this->authorize();

    /**
     * @var ?Project
     */
    $Project = Project::findOrReturn($this->params->id);

    return $Project->edit($this->params);
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
     * @var ?Project
     */
    $Project = Project::findOrReturn($this->params->id);

    $Project->db_transaction();

    try {
      # ? Logs
      $Project->logs()->each(function ($Log) {
        $Log->comments()->delete();
        $Log->reactions()->delete();
        $Log->views()->delete();
        $Log->delete();
      });

      # ? Project
      $Project->delete();
      $Project->db_commit();

      return OK;
    } catch (\Throwable $e) {
      Logger::to_file($e);
      $Project->db_rollback();

      return ERROR;
    }
  }
}
