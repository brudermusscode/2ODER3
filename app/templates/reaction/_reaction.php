<?php

use Bruder\Model\Log;
use Bruder\Model\Reaction;

/**
 * @var Log $SelectedLog
 * @var Reaction $Reaction
 */

?>

<outer-reaction fl alic
  <?= $Reaction->current_bruder_has_reacted
    ? 'data-action="reaction:delete" active'
    : 'data-action="reaction:create"' ?>
  data-id="<?= $Reaction->id ?>"
  data-log-id="<?= $SelectedLog->id ?>"
  data-emote="<?= $Reaction->emote ?>"
  data-type="emote"
  contains-reaction="<?= $Reaction->emote ?>">
  <reaction>
    <?= $Reaction->emote ?>
  </reaction>
  <reaction-count disbl text><?= $Reaction->count ?></reaction-count>
</outer-reaction>