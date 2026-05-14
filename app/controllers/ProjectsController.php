<?php

namespace Bruder\Controller;

use Bruder\Controller\Controller;
use Bruder\Model\Log;
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

    # Find video file and delete it.
    if (file_exists($Log->raw_file_path())) {
      unlink($Log->raw_file_path());

      for ($i = 1; $i <= $Log->thumb_count; $i++) {
        $file_path = $Log->save_path("thumbs") . "/" . $Log->raw_file_name() . "_$i";
        file_exists($file_path . ".webp") ? unlink($file_path . ".webp") : null;
        file_exists($file_path . "_350.webp") ? unlink($file_path . "_350.webp") : null;
      }
    }

    $Log->delete();

    return success();
  }
}
