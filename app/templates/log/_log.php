<?php

use Bruder\Time\Time;
use Bruder\Model\Project;
use Bruder\Model\Log;

/**
 * @var Project $Project
 * @var Log $Log
 * @var int $key
 */

?>

<a href="<?= $Log->link() ?>">
  <log fl fldircol gap=smol flex-truncate>
    <picture size=mid>
      <img src="<?= $Log->current_thumb_src(size: "small") ?>" />
      <div count>#<?= $Project->logs_count - $key ?></div>
    </picture>
    <div fl fldircol lh1 pb8 pt2 pinline12 posrel flex-truncate>
      <p text smolplus semibold trimt style=margin-bottom:-2px;><?= $Log->name ?? "Kein Titel" ?></p>
      <div fl alic gap=smoler>
        <p text smoler regular slight><?= Time::ago($Log->created_at) ?></p>
        &middot;

        <?php

        $views = $Log->views;

        ?>
        <p text smoler regular slight><?= $views ?> Aufruf<?= $views > 1 || $views < 1 ? "e" : "" ?></p>
      </div>
    </div>
  </log>
</a>