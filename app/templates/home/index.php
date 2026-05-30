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

    <?php

    # + All latest devlogs.
    include __DIR__ . "/_latest-devlogs-all.php"; ?>
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

    <div style="position:sticky;top:36px;" z window p24>
      <p text fl alic jucc gap=smol+ tac color=primary>
        <mi>waving_hand</mi>
        Diese Seite ist noch laaange nicht fertig.
      </p>
    </div>

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



</content>