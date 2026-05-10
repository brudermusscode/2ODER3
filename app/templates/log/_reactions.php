<?php

use Illuminate\Support\Collection;
use Bruder\Model\Visitor;
use Bruder\Model\Log;

/**
 * @var Visitor CURRENT_VISITOR
 */

/**
 * @var int
 */
$log_id = filter_input(INPUT_GET, "log_id", FILTER_VALIDATE_INT);

/**
 * @var ?Log
 */
$SelectedLog = Log::with("comments.visitor")
  ->where("id", $log_id)
  ->first();

if (!$SelectedLog)
  exit(error("Kein Log"));

/**
 * @var Collection<Log>
 */
$Reactions = $SelectedLog->reactions()
  ->selectRaw("*, COUNT(*) as count")
  ->selectRaw(
    'MAX(CASE WHEN visitor_id = ? THEN 1 ELSE 0 END) as current_visitor_has_reacted',
    [CURRENT_VISITOR->id]
  )
  ->groupBy("emote")
  ->get();

# Begin la oútput buffér
ob_start();

foreach ($Reactions as $Reaction) :
  include TEMPLATE . "/reaction/_reaction.php";
endforeach;

exit(success(data: ob_get_clean()));
