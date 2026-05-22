<?php

use Bruder\Application\Session;
use Illuminate\Support\Collection;
use Bruder\Model\Visitor;
use Bruder\Model\Log;
use Bruder\Model\User;

/**
 * @var Visitor CURRENT_BRUDER
 */

/**
 * @var int
 */
$log_id = filter_input(INPUT_GET, "log_id", FILTER_VALIDATE_INT);

/**
 * @var ?Log
 */
$SelectedLog = Log::with("comments.client")
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
    'MAX(CASE WHEN client_id = ? and client_type = ? THEN 1 ELSE 0 END) as current_bruder_has_reacted',
    [CURRENT_BRUDER?->id, get_class(CURRENT_BRUDER)]
  )
  ->groupBy("emote")
  ->get();

# Begin la oútput buffér
ob_start();

foreach ($Reactions as $Reaction) :
  include TEMPLATE . "/reaction/_reaction.php";
endforeach;

exit(success(data: ob_get_clean()));
