<?php

use Bruder\Model\CodingSession;
use Bruder\Model\Project;

?>

<div fl fldircol gap=mid>

  <?php

  /**
   * @var ?CodingSession
   */
  $CodingSession = CodingSession::whereNull("finished_at")->latest()->first();

  if ($CodingSession) : ?>

    <coding-session p24 pb48 background=hover-dark rounded=mid fl fldircol gap>
      <?php

      $session_start = $CodingSession->created_at;
      $current_time = new DateTime("now");

      $time_elapsed = $current_time->diff($session_start);

      ?>

      <div fl jucsb alistart>
        <div fl alic gap=smol>
          <mi stdplus pinline12 pblock10 rounded=std background=slighter-light>terminal_2</mi>
          <div fl fldircol gap=smoler>
            <p text ttup smoler>Aktive Coding Session</p>
            <p text stdplus bold><?= $CodingSession->project->name ?></p>
          </div>
        </div>

        <?php if (authorized()) : ?>
          <a href="<?= $CodingSession->link("edit") ?>">
            <mbutton std background=slighter-light has-icon=left>
              <mi>undo</mi>
              Projekt wechseln
            </mbutton>
          </a>
        <?php endif; ?>
      </div>

      <div fl alistart jucc gap=smolest>
        <div fl fldircol alic style=width:120px;>
          <p h text widester bold color=primary>
            <?= $time_elapsed->h < 10 ? "0" . $time_elapsed->h : $time_elapsed->h; ?></p>
          <p text ttup slight style="font-size:14px;">Stunden</p>
        </div>
        <p text widester slight>&middot;</p>
        <div fl fldircol alic style="width:120px;">
          <p i text widester bold color=primary>
            <?= $time_elapsed->i < 10 ? "0" . $time_elapsed->i : $time_elapsed->i ?></p>
          <p text ttup slight style="font-size:14px;">Minuten</p>
        </div>
        <p text widester slight>&middot;</p>
        <div fl fldircol alic style="width:120px;">
          <p s text widester bold color=primary>
            <?= strlen($time_elapsed->s) < 2 ? "0" . $time_elapsed->s : $time_elapsed->s ?></p>
          <p text ttup slight style="font-size:14px;">Sekunden</p>
        </div>
      </div>

      <?php if ($CodingSession->title) : ?>
        <p text tac bold mt12><?= $CodingSession->title ?></p>
      <?php endif; ?>
    </coding-session>
  <?php endif; ?>


  <div fl fldircol gap>
    <p text smol bold ttup color=tertiary pinline12>Aktuellste Devlogs</p>

    <?php foreach (
      Project::with(["logs" => function ($q) {
        $q->limit(4)
          ->orderBy("created_at", "DESC");
      }])
        ->withCount("logs")
        ->get() as $Project
    ) : ?>

      <div>
        <div pb12 fl alic jucsb>
          <div fl alic gap=smol>
            <div circled window style="height:38px;width:38px;" fl alic jucc>
              <mi color=tertiary midler>deployed_code</mi>
            </div>

            <p text mid bold><?= $Project->name ?></p>
          </div>

          <a fl alic gap=smol href="<?= $Project->logs->first()?->link() ?? $Project->link() ?>">
            <div background=quadro color=quadro-text rounded=smolplus pinline10 pblock6>
              <p text smol semibold ttup>
                <strong><?= $Project->logs_count ?></strong> logs
              </p>
            </div>

            <mi>arrow_forward</mi>
          </a>
        </div>

        <div fl alic gap=smol>
          <?php

          if (!$Project->logs_count) : ?>

            <div w100 rounded background=hover-dark pblock62 tac>
              <p text bold smol ttup>Noch Keine Logs &nbsp; 🦕</p>
            </div>

            <?php else :

            /**
             * @var Collection<Log>
             */
            $Logs = $Project->logs;

            foreach ($Logs as $key => $Log) : ?>
              <div style="flex-basis:25%;max-width:25%;">
                <?php include TEMPLATE . "/log/_log.php" ?>
              </div>
          <?php endforeach;

            unset($Logs);

          endif;
          ?>
        </div>
      </div>

    <?php endforeach ?>
  </div>


  <?php

  $features = [
    "Live-Tracking vong aktuellem Projekt" => 1,
    "Neuster Devlog" => 0,
    "Bible Daily" => 0,
    "Twitch Stream" => 0,
    "Trending Projekte" => 0,
  ];

  ?>

  <div fl fldircol gap>
    <p text smol bold ttup color=tertiary pinline12>Feature Tracking</p>

    <div fl jucsb flex-wrap>
      <?php foreach ($features as $feature => $done) : ?>
        <div background=hover-dark rounded=stdplus pblock42 tac slight mb12
          style="flex-basis:49.6%;">
          <p fl alic gap=smol jucc>
            <?php if ($done) : ?>
              <mi color=green midler>done</mi>
            <?php endif; ?>
            <span <?= $done ? "color=green" : "" ?>><?= $feature; ?></span>
          </p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>



  <div style="position:sticky;top:36px;" z window p32>
    <p text tac style="text-align: justify;">
      <strong fl alic gap=smol mb8>
        <mi>waving_hand</mi>
        Diese Seite ist aktuell noch im Aufbau.
      </strong>
      Unten findest du einige Dinge, die ich plane zu implementieren. Grundidee ist, Dir den Fortschritt meiner Projekte zu präsentieren und Dir die Möglichkeit zu geben, persönlich daran teilzuhaben.
    </p>
  </div>
</div>