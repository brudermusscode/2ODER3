<?php

use Bruder\Time\Time;
use Bruder\Model\Log;

/**
 * @var Log $SelectedLog
 */

$video_path = "/data/videos";

?>

<div fl alistart gap=smol+>

  <?php if (!$Logs->count()) : ?>

    <div window pblock124 pinline42 flone fl alic jucc gap>
      <mi wider color=tertiary>deployed_code_history</mi>
      <div fl fldircol gap=smolest>
        <p text midler bold>Keine Logs</p>
        <p text smol>Für <?= $Project->name ?> habe ich noch keinen Devlog hochgeladen</p>
      </div>
    </div>

  <?php else : ?>

    <current-log fl fldircol>
      <video controls elevated poster="<?= "$video_path/thumbs/$SelectedLog->thumb_name" ?>">
        <source src="<?= "$video_path/$SelectedLog->file_name" ?>" type="video/mp4" />
      </video>

      <div fl alic jucsb gap=smol pinline12 pblock18>
        <div fl alic gap=smol>
          <p text smol slight color=primary>Vor <?= Time::ago($SelectedLog->created_at) ?></p>
          &middot;
          <p text smol slight>? Aufrufe</p>
        </div>


        <reactions-container fl alic gap=smol>
          <active-reactions fl alic gap=smoler>
            <?php

            $Reactions = $SelectedLog->reactions()
              ->selectRaw("*, COUNT(*) as count")
              ->groupBy("emote")
              ->withExists([
                'visitor as reacted' => fn($q) => $q->where('visitors.id', CURRENT_VISITOR->id)
              ])
              ->get();

            foreach ($Reactions as $Reaction) :
              include TEMPLATE . "/reaction/_reaction.php";
            endforeach ?>
          </active-reactions>

          <reactions>
            <mi>add_reaction</mi>
            <reactions-choose
              data-action="reaction:create"
              data-log-id="<?= $SelectedLog->id ?>"
              data-type="emote">
              <reaction>😃</reaction>
              <reaction>🤣</reaction>
              <reaction>😍</reaction>
              <reaction>🤯</reaction>
              <reaction>😭</reaction>
              <reaction>🤡</reaction>
              <reaction>🤬</reaction>
            </reactions-choose>
          </reactions>

        </reactions-container>

      </div>

      <div fl fldircol gap pinline12>
        <p text mid bold><?= $SelectedLog->name ?? "Ohne Nameee" ?></p>
        <div fl fldircol gap=smol>
          <p text smoler ttup bold slight>Beschreibung</p>
          <p text smolplus regular><?= $SelectedLog->description ?? "Nichts Beschreibung 😭" ?></p>
        </div>
      </div>

    </current-log>

    <more-logs>
      <p text smoler ttup bold pinline12 mb8 slight>Devlogs zu <?= $Project->name ?></p>
      <?php foreach ($Logs as $Log) : ?>
        <a href="/project/<?= $Project->id ?>/log/<?= $Log->id ?>">
          <log>
            <picture size=mid>
              <img src="/data/videos/thumbs/<?= $Log->thumb_name ?>" />
            </picture>
            <div fl fldircol pblock12 posrel flex-truncate>
              <p text smolplus semibold trimt><?= $Log->name ?? "Kein Titel" ?></p>
              <div fl alic gap=smoler>
                <p text smoler regular slight><?= Time::ago($Log->created_at) ?></p>
                &middot;
                <p text smoler regular slight>Bald viele Aufrufe</p>
              </div>
            </div>
          </log>
        </a>
      <?php endforeach ?>
    </more-logs>

  <?php endif ?>

</div>