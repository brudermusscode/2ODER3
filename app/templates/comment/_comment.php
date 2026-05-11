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
 * @var bool
 */
$show_tools ??= true;

/**
 * @var bool
 */
$show_larger ??= false;

/**
 * @var Visitor
 */
$Visitor = $Comment->visitor;

?>

<comment animation=fade-in rounded=mid fl alistart gap=smol background=slighterer-light>
  <div fl gap=smol flex-truncate <?= !$show_larger ? "pl12 pr24 pblock12" : "p24" ?>>
    <div fl alic jucc circled color=light
      style="height:1.6em;min-width:1.6em;max-width:1.8em;margin-top:1px;border:1px solid rgba(255,255,255,.32);z-index:1;background:<?= $Visitor->color ?>">
      <p text semibold smol ttup><?= substr($Comment->visitor->nickname, 0, 2) ?></p>
    </div>
    <div fl fldircol gap=smoler flex-truncate>
      <p text smolplus word-wrap lh1>
        <span style="color:<?= $Visitor->color ?>" bold><?= $Comment->visitor->nickname ?></span>
        <?= strlen($Comment->comment) <= 250 ? $Comment->comment : substr($Comment->comment, 0, 250) . "…" ?>
      </p>
      <div fl jucsb alic gap=smol>
        <p text smoler slight><?= Time::ago($Comment->created_at) ?></p>

        <?php if ($show_tools) : ?>
          <tools fl alic gap=smol>
            <?php if ($Visitor->is(CURRENT_VISITOR)) : ?>
              <p text smoler color=green fl alic gap=smoler>
                <mi midler>edit</mi> Ändern
              </p>
              <p text smoler color=red fl alic gap=smoler
                data-action="comment:delete"
                data-id="<?= $Comment->id ?>">
                <mi mid>close</mi> Löschen
              </p>
            <?php else : ?>
              <?php

              /**
               * @var ?Report
               */
              $Report = CURRENT_VISITOR->reports()
                ->where([
                  "reference_id" => $Comment->id,
                  "type" => "comment",
                ])->first();

              # * Report comment
              if (!$Report) : ?>
                <p text smoler color=red fl alic gap=smoler
                  request-get="report:new" data-type="comment" data-id="<?= $Comment->id ?>">
                  <mi mid>flag_circle</mi> Polizei!
                </p>
              <?php endif ?>
            <?php endif ?>
          </tools>
        <?php endif ?>
      </div>
    </div>
  </div>
</comment>