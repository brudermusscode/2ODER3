<?php

use Bruder\Model\CodingSession;
use Bruder\Model\Project;
use Bruder\Time\Time;

/**
 * @var int
 */
$id = filter_var($GLOBALS["route_param_id"] ?? 0, FILTER_VALIDATE_INT);

/**
 * @var ?CodingSession
 */
$CodingSession = CodingSession::find($id);

if (!authorized() || !$CodingSession) :
  include UNAVAILABLE;
else :

?>

  <form request="<?= $CodingSession->link("update", true) ?>" redirect="/" responder=simple>
    <content pblock36 miauto midler fl fldircol jucc gap=mid>

      <div fl fldircol gap=smol+>
        <input-title color=tertiary>Projenkt wählem</input-title>

        <choose-option fl fldircol gap=smol+>
          <?php foreach (Project::all() as $Project) :

            $ProjectLogs = $Project->logs;
            $ProjectLatestLog = $Project->logs()->latest()->first() ?? null;

          ?>
            <coption background=slight-light rounded p42
              <?= $CodingSession->project_id === $Project->id ? "active" : "" ?>
              data-value="<?= $Project->id ?>">
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

          <input type=hidden name=project_id value=<?= $CodingSession->project_id ?> />
        </choose-option>
      </div>

      <div fl fldircol gap=smol>
        <input-title color=tertiary>Worum gehts heute Bruder?</input-title>
        <textarea crazy auto-resize name=title placeholder="Session-System bauen…" rows=1><?= $CodingSession->title ?></textarea>
      </div>

      <input type=hidden name=id value=<?= $CodingSession->id; ?> />

      <div fl jucend>
        <mbutton submit-closest wide icon-only background=primary color=primary-text>
          <mi>play_arrow</mi>
        </mbutton>
      </div>

    </content>
  </form>

<?php endif;
