<?php

namespace Bruder\Controller;

use Bruder\Controller\Controller;
use Bruder\Model\Comment;
use Bruder\Model\Report;

class ReportsController extends Controller
{

  /**
   * @return string
   */
  public function create()
  {

    $this->validate_params(
      strict: ["reference_id", "reference_type",],
      optional: [],
    );

    $this->can_interact(die: true);

    /**
     * @var ?Comment
     */
    $Reference =
      Report::valid($this->params->reference_type, $this->params->reference_id)
      ?? ERROR;

    # User has reported this already?
    if (
      CURRENT_BRUDER->reports()
      ->where([
        "reference_id" => $Reference->id,
        "reference_type" => $this->params->reference_type
      ])
      ->first()
    )
      return ERROR;

    # Create a new report!
    CURRENT_BRUDER->reports()
      ->create([
        "reference_id" => $Reference->id,
        "reference_type" => $this->params->reference_type,
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
