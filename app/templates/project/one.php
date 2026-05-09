<?php

use Illuminate\Support\Collection;
use Bruder\Model\Log;
use Bruder\Model\Project;
use Bruder\Model\Visitor;

/**
 * @var int
 */
$id = filter_var($GLOBALS["route_param_id"] ?? 0, FILTER_VALIDATE_INT);

/**
 * @var int
 */
$log_id = filter_var($GLOBALS["route_param_log_id"] ?? 0, FILTER_VALIDATE_INT);

/**
 * @var ?Project
 */
$Project = Project::with(["logs" => function ($q) {
  $q->orderBy("created_at", "DESC");
}])
  ->find($id);

if (!$Project) :
  include UNAVAILABLE;
else :

  /**
   * @var Collection<Log>
   */
  $Logs = $Project->logs;

  /**
   * @var Log
   */
  $SelectedLog = $Logs->where("id", $log_id)->first() ?? $Logs->first();

  # Inscrease Log views by one.
  $SelectedLog->increase_views(CURRENT_VISITOR);

  include __DIR__ . "/_content.php";

endif;
