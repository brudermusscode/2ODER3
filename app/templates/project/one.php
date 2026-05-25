<?php



use Bruder\Model\Project;
use Bruder\Model\Log;
use Bruder\Time\Time;
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

  $owner = 'brudermusscode';
  $repo  = $Project->name;
  $branch = 'deploy';

  $url = "https://api.github.com/repos/$owner/$repo/commits";

  $options = [
    "http" => [
      "method" => "GET",
      "header" => [
        "User-Agent: MyWebsite",
        "Accept: application/vnd.github+json"
      ]
    ]
  ];

  $context = stream_context_create($options);
  $response = @file_get_contents($url, false, $context);
  $date = $response ? json_decode($response)[0]?->commit->author->date : null;

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

        <?php if ($Project->logs_count) : ?>
          <a href="<?= $Project->logs->first()->link() ?>">
            <mbutton stdplus background=primary has-icon="left">
              <mi>books_movies_and_music</mi>
              <span color=tertiary><strong><?= $Project->logs_count; ?></strong></span>
              Logs ansehen
            </mbutton>
          </a>
        <?php else : ?>
          <mbutton stdplus disabled background=primary has-icon="left">
            <mi>books_movies_and_music</mi>
            Keinö Logs
          </mbutton>
        <?php endif; ?>

        <a href="<?= $Project->url ?>" extern target="_blank">
          <mbutton stdplus has-icon="left" background=light color=dark>
            <img style="height:30.7px;" src="/assets/images/github.svg" loaded="true">
            <div fl fldircol>
              <p text style="font-size:16px;" semibold><?= $Project->name ?></p>
              <p style="font-size:12px;">
                <?= $date ? "Letzter Commit &middot; <span color=primary>" . Time::ago($date) . "</span>" : "Kein Commit" ?></p>
            </div>
          </mbutton>
        </a>
      </div>
    </div>

    <div identity>
      <img src="<?= $Project->identity() ?>" style="width:100%;" />
    </div>
  </div>

<?php endif;
