<?php

use Illuminate\Support\Collection;
use Bruder\Model\Log;
use Bruder\Model\Project;

?>

<div fl fldircol gap pb142>

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

        <a fl alic gap=smol href="/project/<?= $Project->id ?>">
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

  <?php endforeach ?>

</div>