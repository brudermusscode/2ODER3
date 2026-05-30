<?php

namespace Bruder\Controller;

use Bruder\Controller\Controller;
use Bruder\Model\CodingSession;
use Bruder\Model\Project;

class CodingSessionsController extends Controller
{

  /**
   * @return string
   */
  public function create()
  {

    $this->validate_params(
      strict: ["project_id", "title"],
      optional: [],
    );

    $this->authorize();

    /**
     * @var ?Project
     */
    $Project = Project::findOrReturn($this->params->project_id);

    $CodingSession = CodingSession::make();
    $CodingSession->title = $this->params->title;
    $CodingSession->project()->associate($Project);
    $CodingSession->save();

    return success(data: $CodingSession);
  }

  /**
   * @return string
   */
  public function update()
  {

    $this->validate_params(
      strict: ["id", "project_id"],
      optional: ["title", "finished"],
    );

    /**
     * @var ?CodingSession
     */
    $CodingSession = CodingSession::findOrReturn($this->params->id);

    /**
     * @var ?Project
     */
    $Project = Project::findOrReturn($this->params->project_id);

    $CodingSession->title = $this->params->title;
    $CodingSession->project()->associate($Project);
    $CodingSession->finished_at = $this->params->finished ?? false ? date("Y-m-d H:i:s") : null;
    $CodingSession->save();

    return success(data: $CodingSession);
  }
}
