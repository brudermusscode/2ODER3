<?php

use Bruder\Model\Project;
use Bruder\Model\Log;
use Bruder\Model\Comment;
use Bruder\Model\Visitor;
use Bruder\Time\Time;

/**
 * @var Project $Project
 * @var Log $SelectedLog
 * @var Comment $Comment
 */

/**
 * @var Visitor
 */
$Visitor = $Comment->visitor;

?>

<comment animation=fade-in rounded=mid fl alistart gap=smol background=slighterer-light>
  <div fl gap=smol flex-truncate pinline12 pblock12 word-wrap>
    <div fl alic jucc circled color=light
      style="height:1.6em;min-width:1.6em;max-width:1.8em;margin-top:1px;border:1px solid rgba(255,255,255,.32);z-index:1;background:<?= $Visitor->color ?>">
      <p text semibold smol ttup><?= substr($Comment->visitor->nickname, 0, 2) ?></p>
    </div>
    <div fl fldircol gap=smoler>
      <p text smolplus>
        <span style="color:<?= $Visitor->color ?>" bold><?= $Comment->visitor->nickname ?></span>
        <?= strlen($Comment->comment) <= 250 ? $Comment->comment : substr($Comment->comment, 0, 250) . "…" ?>
      </p>
      <p text smoler slight><?= Time::ago($Comment->created_at) ?></p>
    </div>
  </div>
</comment>