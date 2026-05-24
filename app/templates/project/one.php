<?php

use Illuminate\Database\Eloquent\Collection;
use Bruder\Model\Comment;
use Bruder\Model\Log;
use Bruder\Model\Project;
use Bruder\Time\Time;

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
  ->withCount("logs")
  ->find($id);

if (!$Project) :
  include UNAVAILABLE;
else :

  /**
   * @var Collection<Log>
   */
  $Logs = $Project->logs;

?>

<?php endif;
