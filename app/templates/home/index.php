<?php

use Illuminate\Support\Collection;
use Bruder\Model\Log;
use Bruder\Model\Project;

?>

<div fl fldircol gap=mid>

  <div style="position:sticky;top:24px;" z color=primary window pblock38 pinline42>
    <p text tac style="text-align: justify;">
      <strong fl alic gap=smol mb8>
        <mi>waving_hand</mi>
        Diese Seite ist aktuell noch im Aufbau.
      </strong>
      Unten findest du einige Dinge, die ich plane zu implementieren. Grundidee ist, Dir den Fortschritt meiner Projekte zu präsentieren und Dir die Möglichkeit zu geben, persönlich daran teilzuhaben.
    </p>
  </div>

  <div fl fldircol gap>
    <p text smol bold ttup color=tertiary pinline12>Geplante Features</p>

    <div fl jucsb flex-wrap>
      <div background=hover-dark rounded=stdplus pblock42 tac slight mb12 style="flex-basis:49.6%;">
        <span color=tertiary>Live-Tracking vong aktuellem Projekt</span>
      </div>

      <div background=hover-dark rounded=stdplus pblock42 tac slight mb12 style="flex-basis:49.6%;">
        <span color=tertiary>Neuster Devlog</span>
      </div>

      <div background=hover-dark rounded=stdplus pblock42 tac slight mb12 style="flex-basis:49.6%;">
        <span color=tertiary>Bible Daily</span>
      </div>

      <div background=hover-dark rounded=stdplus pblock42 tac slight mb12 style="flex-basis:49.6%;">
        <span color=tertiary>Twitch Stream</span>
      </div>

      <div background=hover-dark rounded=stdplus pblock42 tac slight mb12 style="flex-basis:49.6%;">
        <span color=tertiary>Trending Projekte</span>
      </div>
    </div>
  </div>

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
</div>