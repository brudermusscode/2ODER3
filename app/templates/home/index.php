<?php

use Illuminate\Support\Collection;
use Bruder\Model\Log;
use Bruder\Time\Time;

/**
 * @var Log
 */
$Logs = Log::orderBy("created_at", "DESC")->get();
$LatestLog = $Logs->first();

$video_path = "/data/videos";

?>


<div fl alistart gap>

  <?php if (!$Logs->count()) : ?>

    <div window pblock124 pinline42 flone fl alic jucc gap>
      <mi wider color=tertiary>deployed_code_history</mi>
      <div fl fldircol gap=smolest>
        <p text midler bold>Keine Logs</p>
        <p text smol>Noch keine Devlogs hochgeladen</p>
      </div>
    </div>

  <?php else : ?>

    <current-log fl fldircol gap>
      <video controls elevated poster="<?= "$video_path/thumbs/$LatestLog->thumb_name" ?>">
        <source src="<?= "$video_path/$LatestLog->file_name" ?>" type="video/mp4" />
      </video>

      <div fl fldircol gap=smoler pinline8>
        <p text mid bold><?= $LatestLog->name ?></p>
        <p text smolplus regular><?= $LatestLog->description ?></p>
      </div>

    </current-log>

    <more-logs>
      <?php foreach ($Logs as $Log) : ?>
        <log>
          <picture size=mid>
            <img src="/data/videos/thumbs/<?= $Log->thumb_name ?>" />
          </picture>
          <div fl fldircol pblock12 posrel flex-truncate>
            <p text bold trimt><?= $Log->name ?></p>
            <div fl alic gap=smoler>
              <p text smol regular slight>1.200 views</p>
              &middot;
              <p text smol regular slight><?= Time::ago($Log->created_at) ?></p>
            </div>
          </div>
        </log>
      <?php endforeach ?>
    </more-logs>

  <?php endif ?>

</div>