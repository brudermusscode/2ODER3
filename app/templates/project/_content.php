<?php

use Bruder\Time\Time;
use Bruder\Model\Log;
use Bruder\Model\Comment;

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
        <p text smol>Für ⌞<?= $Project->name ?>⌝ hab' ich noch nichts hochgeladen</p>
      </div>
    </div>

  <?php else : ?>

    <current-log fl fldircol <?= DEV ? "flone" : "" ?>>
      <video-wrapper>
        <video poster="<?= "$video_path/thumbs/$SelectedLog->thumb_name" ?>">
          <source src="<?= "$video_path/$SelectedLog->file_name" ?>" type="video/mp4" />
        </video>

        <video-top-toolbar>
          <volume>
            <mi></mi>
            <contains>
              <volume-track></volume-track>
            </contains>
          </volume>

          <cinema-mode>
            <mi>aspect_ratio</mi>
          </cinema-mode>

          <fullscreen>
            <mi>open_in_full</mi>
          </fullscreen>
        </video-top-toolbar>

        <video-toggle>
          <mi></mi>
        </video-toggle>

        <video-toolbar>
          <buffer>
            <div smol primary class="spinner">
              <div></div>
              <div></div>
              <div></div>
              <div></div>
              <div></div>
              <div></div>
            </div>
          </buffer>
          <vt-duration-track-wrapper>
            <contains>
              <vt-duration-track></vt-duration-track>
            </contains>
          </vt-duration-track-wrapper>
        </video-toolbar>
      </video-wrapper>

      <div fl fldircol gap=mid>
        <div fl fldircol gap=smol+>
          <div fl alic jucsb gap=smol pinline32 pt18>
            <div fl alic gap=smol>
              <p text smol slight color=primary>Vor <?= Time::ago($SelectedLog->created_at) ?></p>
              &middot;

              <?php

              $views = $SelectedLog->views;

              ?>
              <p text smol slight><?= $views ?> Aufruf<?= $views > 1 || $views < 1 ? "e" : "" ?></p>
            </div>


            <reactions-container fl alic gap=smol>
              <active-reactions fl alic gap=smoler>
                <get-content from="/get/log/reactions?log_id=<?= $SelectedLog->id ?>">
                  <div fl alic gap=smoler>
                    <div style="height:48px;width:66px;" rounded background=slighter-light></div>
                    <div style="height:48px;width:66px;" rounded background=slighter-light></div>
                    <div style="height:48px;width:66px;" rounded background=slighter-light></div>
                    <div style="height:48px;width:66px;" rounded background=slighter-light></div>
                  </div>
                </get-content>
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

          <div fl fldircol gap p32 window>
            <p text mid bold><?= $SelectedLog->name ?? "Ohne Nameee" ?></p>
            <div fl fldircol gap=smol>
              <p text smoler ttup bold slight>Beschreibung</p>
              <p text smolplus regular><?= $SelectedLog->description ?? "Nichts Beschreibung 😭" ?></p>
            </div>
          </div>
        </div>

        <!-- <div pinline12>
          <div style="height:6px;" rounded background=slighter-light></div>
        </div> -->

        <!-- <section colored style="background: url(/colors.svg);" p32 rounded=wide fl fldircol gap></section> -->

        <div fl fldircol gap=smol+>
          <p text smoler ttup bold pinline12 mb8 slight>Möööhr Devlogs zu ⌞<?= $Project->name ?>⌝</p>
          <more-logs fl gap=smol flex-wrap>
            <?php foreach ($Logs as $Log) : ?>
              <a href="/project/<?= $Project->id ?>/log/<?= $Log->id ?>">
                <log fl fldircol gap=smol flex-truncate>
                  <picture size=mid>
                    <img src="/data/videos/thumbs/<?= $Log->thumb_name ?>" />
                    <div count>#<?= $Log->id ?></div>
                  </picture>
                  <div fl fldircol lh1 pb8 pt2 pinline12 posrel flex-truncate>
                    <p text smolplus semibold trimt style=margin-bottom:-2px;><?= $Log->name ?? "Kein Titel" ?></p>
                    <div fl alic gap=smoler>
                      <p text smoler regular slight><?= Time::ago($Log->created_at) ?></p>
                      &middot;

                      <?php

                      $views = $Log->views;

                      ?>
                      <p text smoler regular slight><?= $views ?> Aufruf<?= $views > 1 || $views < 1 ? "e" : "" ?></p>
                    </div>
                  </div>
                </log>
              </a>
            <?php endforeach ?>
          </more-logs>
        </div>
      </div>
    </current-log>

    <?php

    /**
     * @var ?Collection<Comment>
     */
    $Comments = $SelectedLog->comments->sortByDesc("created_at");

    ?>

    <comments <?= $Comments->count() ? "" : "is-empty" ?> fl fldircol jucsb gap=smol>
      <get-content from="/get/log/comments?log_id=<?= $SelectedLog->id ?>" fl alistretch>
        <div background=slight-dark rounded=mid flone>
          <?php include TEMPLATE . "/global/_loader.html" ?>
        </div>
      </get-content>
    </comments>

  <?php endif;  ?>

</div>