<?php

use Bruder\Model\Log;
use Bruder\Model\Reaction;

/**
 * @var Log $SelectedLog
 * @var Reaction $Reaction
 */

?>

<outer-reaction fl alic
  <?= $Reaction->reacted
    ? 'data-action="reaction:delete" active'
    : 'data-action="reaction:create"' ?>
  data-id="<?= $Reaction->id ?>"
  data-log-id="<?= $SelectedLog->id ?>"
  contains-reaction="<?= $Reaction->emote ?>"
  data-type="emote">
  <reaction>
    <?= $Reaction->emote ?>
  </reaction>
  <reaction-count disbl text><?= $Reaction->count ?></reaction-count>
</outer-reaction>