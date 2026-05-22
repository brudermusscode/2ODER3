<?php

use Bruder\Model\Project;
use Bruder\Model\Log;
use Bruder\Model\Comment;
use Bruder\Model\User;
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
 * @var Visitor|User
 */
$Client = $Comment->client;

?>

<comment animation=fade-in fl alistart gap=smol background=slighterer-light
  style="border-radius:28px 28px 22px 22px;">
  <div fl gap=smol flex-truncate pl18 pr24 pblock18>

    <div fl fldircol gap=smol+ flex-truncate>

      <div fl jucsb alic gap=smol>
        <div fl alic gap=smol>
          <div fl alic jucc circled color=light
            style="height:36px;min-width:36px;max-width:1.8em;margin-top:1px;border:1px solid rgba(255,255,255,.32);z-index:1;background:<?= $Client->color ?>">
            <p text semibold smol ttup><?= substr($Client->nickname, 0, 2) ?></p>
          </div>
          <div>
            <p text smolplus semibold word-wrap lh1 style="color:<?= $Client->color ?>">
              <?= $Client->nickname ?></p>
            <p text smol slight><?= Time::ago($Comment->created_at) ?></p>
          </div>
        </div>

      </div>

      <p text smolplus>
        <?= strlen($Comment->comment) <= 250 ? $Comment->comment : substr($Comment->comment, 0, 250) . "…" ?></p>

      <?php if ($show_tools) : ?>
        <div fl jucsb alic gap=smol>
          <tools fl alic gap=smol>
            <?php if (!$Client->is(CURRENT_BRUDER)) : ?>
              <p text smoler color=green fl alic gap=smoler>
                <mi midler>edit</mi> Ändern
              </p>
              <p text smoler color=red fl alic gap=smoler
                data-action="comment:delete"
                data-id="<?= $Comment->id ?>">
                <mi mid>close</mi> Löschen
              </p>
            <?php else : ?>
              <p text smoler color=red fl alic gap=smoler
                request-get="report:new" data-type="comment" data-id="<?= $Comment->id ?>">
                <mi mid>flag_circle</mi> Polizei!
              </p>
            <?php endif ?>
          </tools>
        </div>
      <?php endif ?>

    </div>
  </div>
</comment>