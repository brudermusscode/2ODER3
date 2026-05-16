<?php

namespace Bruder\Controller;

use Bruder\Controller\Controller;
use Bruder\Model\Report;

class ReportsController extends Controller
{

  /**
   * @return string
   */
  public function create()
  {

    $this->validate_params(
      strict: ["type", "id"],
      optional: [],
    );

    $this->visitor_authorized();

    /**
     * @var ?Comment
     */
    $Reference =
      Report::valid($this->params->type, $this->params->id)
      ?? ERROR;

    # ! User has reported this already.
    if (
      CURRENT_VISITOR->reports()
      ->where([
        "reference_id" => $Reference->id,
        "type" => $this->params->type
      ])
      ->first()
    )
      return ERROR;

    # Create a new report!
    CURRENT_VISITOR->reports()
      ->create([
        "reference_id" => $Reference->id,
        "type" => $this->params->type,
      ]);

    return OK;
  }

  /**
   * @return string
   */
  public function update()
  {

    return ERROR;
  }

  /**
   * @return string
   */
  public function delete()
  {

    return ERROR;
  }
}
