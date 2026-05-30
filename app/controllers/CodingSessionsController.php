<?php

namespace Bruder\Controller;

use Bruder\Controller\Controller;
use Bruder\Model\CodingSession;
use Bruder\Model\CodingSessionProject;
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

    $title = $this->params->title ?: null;

    # Create a new CodingSession.
    $CodingSession = CodingSession::make();
    $CodingSession->title = $title;
    $CodingSession->current_project()->associate($Project);
    $CodingSession->save();

    # Create a new CosingSessionProject
    $CSProject = $CodingSession->projects()->make();
    $CSProject->title = $title;
    $CSProject->project()->associate($Project);
    $CSProject->save();

    # Update all other session to be finished.
    CodingSession::whereNot("id", $CodingSession->id)
      ->get()
      ?->each(fn($CS) => $CS->finish());

    return success(data: $CodingSession);
  }

  /**
   * @return string
   */
  public function update()
  {

    $this->validate_params(
      strict: ["id"],
      optional: ["title", "finish", "project_id"],
    );

    $this->authorize();

    /**
     * @var ?CodingSession
     */
    $CodingSession = CodingSession::findOrReturn($this->params->id);

    # Finish and return?
    if (!empty($this->params->finish) && $CodingSession->finish())
      return success("Coding Session done Bruder!");

    /**
     * @var ?Project
     */
    $Project = Project::findOrReturn($this->params->project_id);

    $title = $this->params->title ?: null;

    $CodingSession->title = $title;
    $CodingSession->current_project()->associate($Project);
    $CodingSession->save();

    /**
     * @var ?CodingSessionProject
     */
    $LatestCSProject = $CodingSession->projects()
      ->latest()
      ->first();

    if (!$LatestCSProject->project->is($Project)) {

      # Create a new CodingSessionProject and stop all previous.
      $CodingSession->projects()->each(fn($CSP) => $CSP->stop());
      $CSProject = $CodingSession->projects()->make();
      $CSProject->title = $title;
      $CSProject->project()->associate($Project);

      $CSProject->save();
    } else

      # Update the latest Coding Session Project.
      $LatestCSProject->update([
        "title" => $title,
      ]);

    return success(data: $CodingSession);
  }
}
