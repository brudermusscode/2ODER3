<?php

use Illuminate\Support\Collection;
use Bruder\Model\Log;
use Bruder\Time\Time;
use Bruder\Model\Project;

$id = filter_var($GLOBALS["route_param_id"] ?? 0, FILTER_VALIDATE_INT);
$log_id = filter_var($GLOBALS["route_param_log_id"] ?? 0, FILTER_VALIDATE_INT);

/**
 * @var ?Project
 */
$Project = Project::with(["logs" => function ($q) {
  $q->orderBy("created_at", "DESC");
}])->find($id);

if (!$Project) :
  include UNAVAILABLE;
else :

  /**
   * @var Collection<Log>
   */
  $Logs = $Project->logs;

  /**
   * @var Log
   */
  $SelectedLog = $Logs->where("id", $log_id)->first() ?? $Logs->first();

  $video_path = "/data/videos";

?>


  <div fl alistart gap>

    <?php if (!$Logs->count()) : ?>

      <div window pblock124 pinline42 flone fl alic jucc gap>
        <mi wider color=tertiary>deployed_code_history</mi>
        <div fl fldircol gap=smolest>
          <p text midler bold>Keine Logs</p>
          <p text smol>Für <?= $Project->name ?> habe ich noch keinen Devlog hochgeladen</p>
        </div>
      </div>

    <?php else : ?>

      <current-log fl fldircol gap>
        <video controls elevated poster="<?= "$video_path/thumbs/$SelectedLog->thumb_name" ?>">
          <source src="<?= "$video_path/$SelectedLog->file_name" ?>" type="video/mp4" />
        </video>

        <div fl fldircol gap=smoler pinline8>
          <p text mid bold><?= $SelectedLog->name ?></p>
          <p text smolplus regular><?= $SelectedLog->description ?></p>
        </div>

      </current-log>

      <more-logs>
        <?php foreach ($Logs as $Log) : ?>
          <a href="/project/<?= $Project->id ?>/log/<?= $Log->id ?>">
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
          </a>
        <?php endforeach ?>
      </more-logs>

    <?php endif ?>

  </div>

<?php endif;
