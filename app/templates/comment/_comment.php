<?php

use Bruder\Model\Project;
use Bruder\Model\Log;
use Bruder\Model\Comment;

/**
 * @var Project $Project
 * @var Log $SelectedLog
 * @var Comment $Comment
 */

?>

<comment animation=fade-in rounded=mid fl alistart gap=smol>
  <div fl alic jucc circled background=primary color=light
    style="height:2.4em;width:2.4em;margin-right:-24px;margin-top:1px;border:1px solid rgba(255,255,255,.32);z-index:1;">
    <p text semibold smol ttup><?= substr($Comment->visitor->nickname, 0, 2) ?></p>
  </div>
  <div fl fldircol gap=smoler flex-truncate window pinline24 pblock12 word-wrap>
    <p text smolplus>
      <span color=primary bold><?= $Comment->visitor->nickname ?></span>
      <?= strlen($Comment->comment) <= 250 ? $Comment->comment : substr($Comment->comment, 0, 250) . "…" ?>
    </p>
  </div>
</comment>