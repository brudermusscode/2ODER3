<?php

use Illuminate\Support\Collection;
use Bruder\Model\Project;
use Bruder\Model\Log;

?>

<content fl fldircol gap=mid+>

  <?php

  # + Coding Session.
  include __DIR__ . "/_coding-session.php" ?>

  <div fl fldircol gap=smol+>
    <p text smol bold ttup color=tertiary pinline12>Aktuellste Devlogs</p>

    <?php foreach (
      Project::with(["logs" => function ($q) {
        $q->limit(4)
          ->orderBy("created_at", "DESC");
      }])
        ->withCount("logs")
        ->get() as $Project
    ) : ?>

      <?php if ($Project->logs_count) : ?>
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

            ?>
          </div>
        </div>
      <?php endif; ?>
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

  <div fl fldircol gap=smol+>
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