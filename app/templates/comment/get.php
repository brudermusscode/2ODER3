<?php

use Bruder\Model\Log;
use Bruder\Model\Comment;

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

ob_start();

foreach ($SelectedLog->comments->sortByDesc("created_at") as $Comment) : ?>
  <comment rounded=mid fl alistart gap=smol>
    <div fl alic jucc circled background=secondary color=light
      style="height:2.4em;width:2.4em;margin-right:-18px;margin-top:1px;">
      <p text semibold smol ttup>ha</p>
    </div>
    <div fl fldircol gap=smoler flone window pinline24 pblock12>
      <p text smolplus>
        <span color=primary bold><?= $Comment->visitor->nickname ?></span>
        <?= $Comment->comment ?>
      </p>
    </div>
  </comment>
<?php endforeach;

exit(success(data: ob_get_clean()));
