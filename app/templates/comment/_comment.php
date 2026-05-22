<?php

use Bruder\Model\Project;
use Bruder\Model\Log;
use Bruder\Model\Comment;
use Bruder\Model\Report;
use Bruder\Model\Visitor;
use Bruder\Model\User;
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
 * @var Visitor|User
 */
$Client = $Comment->client;

?>

<comment animation=fade-in rounded=mid fl alistart gap=smol background=slighterer-light>
  <div fl gap=smol flex-truncate <?= !$show_larger ? "pl12 pr24 pblock12" : "p24" ?>>
    <div fl alic jucc circled color=light
      style="height:1.6em;min-width:1.6em;max-width:1.8em;margin-top:1px;border:1px solid rgba(255,255,255,.32);z-index:1;background:<?= $Client->color ?>">
      <p text semibold smol ttup><?= substr($Client->nickname, 0, 2) ?></p>
    </div>
    <div fl fldircol gap=smoler flex-truncate>
      <p text smolplus word-wrap lh1>
        <span style="color:<?= $Client->color ?>" bold><?= $Client->nickname ?></span>
        <?= strlen($Comment->comment) <= 250 ? $Comment->comment : substr($Comment->comment, 0, 250) . "…" ?>
      </p>
      <div fl alic gap=smol>
        <p text smoler slight><?= Time::ago($Comment->created_at) ?></p>

        <?php if ($show_tools) : ?>
          <tools fl alic gap=smol>
            <p text smoler slight">&middot;</p>
            <?php if ($Client->is(CURRENT_BRUDER)) : ?>
              <p text smoler color=green fl alic gap=smoler>
                <mi midler mt1>arrow_selector_tool</mi> Ändern
              </p>
              <p text smoler color=red fl alic gap=smoler
                data-action="comment:delete"
                data-id="<?= $Comment->id ?>">
                <mi mid style="margin-top:1px;">close</mi> Löschen
              </p>
            <?php else : ?>
              <?php if (LOGGED) :

                /**
                 * @var ?Report
                 */
                $Report = CURRENT_BRUDER->reports()
                  ->where([
                    "reference_id" => $Comment->id,
                    "reference_type" => "comment",
                  ])->first();

                # * Report comment
                if (!$Report) : ?>
                  <p text smoler color=red fl alic gap=smoler
                    request-get="report:new"
                    data-reference-id="<?= $Comment->id ?>"
                    data-reference-type="comment">
                    <mi mid>flag_circle</mi> Polizei!
                  </p>
                <?php endif ?>
              <?php endif ?>
            <?php endif ?>
          </tools>
        <?php endif ?>
      </div>
    </div>
  </div>
</comment>