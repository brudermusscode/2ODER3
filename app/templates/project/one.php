<?php

use Bruder\Model\Project;
use Bruder\Model\Log;
use Illuminate\Database\Eloquent\Collection;

/**
 * @var string
 */
$name = filter_var($GLOBALS["route_param_name"] ?? 0, FILTER_SANITIZE_SPECIAL_CHARS);

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
  ->where("name", $name)
  ->first();

if (!$Project) :
  include UNAVAILABLE;
else :

  /**
   * @var Collection<Log>
   */
  $Logs = $Project->logs;

?>

  <div landing fl fldircol alic>
    <?php if (authorized()) : ?>
      <div fl jucend alic w100 style="margin-bottom:-56px;">
        <a href="<?= $Project->link("edit"); ?>">
          <mbutton mid icon-only background=green>
            <mi>edit</mi>
          </mbutton>
        </a>
      </div>
    <?php endif; ?>

    <div title fl fldircol alic jucc gap=smol+ posrel>
      <mi slighter posabs color=tertiary>deployed_code</mi>
      <p text widester bold tac><?= $Project->name ?></p>

      <div fl alic gap=smol>
        <a href="<?= $Project->logs->first()->link() ?>">
          <mbutton stdplus background=primary has-icon="left">
            <mi>books_movies_and_music</mi>
            <span color=tertiary><strong><?= $Project->logs_count; ?></strong></span>
            Logs ansehen
          </mbutton>
        </a>

        <a href="<?= $Project->url ?>" extern target="_blank">
          <mbutton stdplus has-icon="left" background=light color=dark>
            <img style="height:30.7px;" src="/assets/images/github.svg" loaded="true">
            <p text style="font-size:14px;">GitHub</p>
          </mbutton>
        </a>
      </div>
    </div>

    <div identity>
      <img src="<?= $Project->identity() ?>" style="width:100%;" />
    </div>
  </div>

<?php endif;
