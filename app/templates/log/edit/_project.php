<?php

use Bruder\Model\Log;
use Bruder\Model\Project;
use Bruder\Time\Time;

/**
 * @var int $id
 * @var string $sub
 * @var Log $Log
 */

$log_link = "/log/" . $id . "/edit/project";

?>

<form data-action="log:finalize">
  <content log-new fl fldircol gap=smol+>
    <p text bold smol slight ttup pinline24>Projekt nicht vergessen</p>


    <add-new request-get="project:new">
      <mi>deployed_code</mi>
      <div fl alic jucsb flone pr18 pb12>
        <p text midplus semibold>Neues erstellen</p>
        <mi mid semibold>orders</mi>
      </div>
    </add-new>

    <div fl alic jucc pblock12 w100>
      <mi mid semibold slighter>expand_circle_down</mi>
    </div>

    <choose-option fl fldircol gap=smol+>
      <?php foreach (Project::with("logs")->get() as $Project) :

        $ProjectLogs = $Project->logs;
        $ProjectLatestLog = $Project->logs()->latest()->first() ?? null;

      ?>
        <coption
          <?= $Log->project?->is($Project) ? "active" : "" ?>
          data-value="<?= $Project->id ?>" background=slight-light rounded p42>
          <div fl fldircol>
            <p text midler bold><?= $Project->name ?></p>
            <div fl alic gap=smol>
              <?php if ($ProjectLogs->count()) : ?>
                <p text smol slight><?= $ProjectLogs->count() ?> logs</p>
                <p text smol slight>
                  Letzter Log &middot; <?= Time::ago($ProjectLatestLog->created_at) ?></p>
              <?php else : ?>
                <p text smol slight>Keine Logs</p>
              <?php endif ?>
            </div>
          </div>
        </coption>
      <?php endforeach ?>

      <input type=hidden name=project_id value />
    </choose-option>

    <input type=hidden name=id value=<?= $Log->id ?> />
    <input type=hidden name=__admin_key value="<?= _env("WEB_ADMIN_KEY") ?>" />

    <div z style="position:fixed;left:2.4em;top:50%;translate:0 -50%;">
      <a href="/log/<?= $id ?>/edit/metadata">
        <mbutton has-tooltip=right wide tabindex="3" icon-only background=tertiary color=tertiary-text>
          <mi>art_track</mi>
          <div ttooltip text semibold>Metadaten bearbeiten</div>
        </mbutton>
      </a>
    </div>

    <div z style="position:fixed;right:2.4em;top:50%;translate:0 -50%;">
      <mbutton wide tabindex="3" icon-only background=green color=dark submit-closest>
        <mi>done_all</mi>
      </mbutton>
    </div>
  </content>
</form>