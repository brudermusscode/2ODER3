<?php

use Illuminate\Database\Eloquent\Collection;
use Bruder\Model\Comment;
use Bruder\Model\Log;
use Bruder\Model\Project;
use Bruder\Time\Time;

/**
 * @var int
 */
$id = filter_var($GLOBALS["route_param_id"] ?? 0, FILTER_VALIDATE_INT);

/**
 * @var int
 */
$log_id = filter_var($GLOBALS["route_param_log_id"] ?? 0, FILTER_VALIDATE_INT);

/**
 * @var ?Project
 */
$Project = Project::with(["logs" => function ($q) {
  $q->orderBy("created_at", "DESC");
}])
  ->withCount("logs")
  ->find($id);

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

  # Inscrease Log views by one.
  $SelectedLog?->increase_views(CURRENT_BRUDER);

  // include __DIR__ . "/_content.php";

  $video_path = "/data/videos";

?>

  <content log>

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
        <?php

        /**
         * @var Log
         */
        $Log = $SelectedLog;

        /**
         * + Video wrapper
         */
        $video_src = $Log->video_src();
        $video_thumb = $Log->current_thumb_src();
        include TEMPLATE . "/global/_video-wrapper.php" ?>

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

              <div fl alic gap=smol+>
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

                <?php

                /**
                 * + Editing tools.
                 */
                if (authorized()) : ?>
                  <div background=slighter-light rounded pblock22 style=width:4px;></div>
                  <a href="/log/<?= $SelectedLog->id ?>/edit">
                    <mbutton stdplus background=secondary color=secondary-text icon-only>
                      <mi>edit</mi>
                    </mbutton>
                  </a>
                <?php endif ?>
              </div>
            </div>

            <div fl fldircol gap p32 window>
              <p text mid bold><?= $SelectedLog->name ?? "<span slight>Kein Name</span>" ?></p>
              <div fl fldircol gap=smol>
                <p text smoler ttup bold slight>Beschreibung</p>
                <p text smolplus regular><?= $SelectedLog->description ?? "<span slight>Nichts Beschreibung 😭</span>" ?></p>
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
              <?php

              /**
               * + Logs
               */
              foreach ($Logs as $key => $Log) :
                include TEMPLATE . "/log/_log.php";
              endforeach ?>
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

      <comments panel=comments fl fldircol jucsb alistretch jucstretch gap=smol
        <?= $Comments->count() ? "" : "is-empty" ?>>
        <get-content from="/get/log/comments?log_id=<?= $SelectedLog->id ?>" fl alistretch>
          <loading flone>
            <?php include TEMPLATE . "/global/_loader.html" ?>
          </loading>
        </get-content>
      </comments>

    <?php endif;  ?>
  </content>

<?php endif;
