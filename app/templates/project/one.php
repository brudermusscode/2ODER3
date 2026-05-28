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
   * @param string $owner
   * @param string $repository
   * @return ?object
   */
  function get_commits(string $owner, string $repository)
  {
    $url = "https://api.github.com/repos/$owner/$repository/commits";

    $options = [
      "http" => [
        "method" => "GET",
        "header" => [
          "User-Agent: mitjesus.dev",
          "Accept: application/vnd.github+json"
        ]
      ]
    ];

    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);

    return $response ? json_decode($response) : null;
  }

  $commits = get_commits(owner: "brudermusscode", repository: $Project->name,);

  /**
   * @var Collection<Log>
   */
  $Logs = $Project->logs;

?>

  <div landing fl fldircol alic>
    <div fl jucsb alic w100 style="margin-bottom:-56px;">
      <a href="/">
        <mbutton mid icon-only background=light color=dark>
          <mi>arrow_back</mi>
        </mbutton>
      </a>

      <?php if (authorized()) : ?>
        <a href="<?= $Project->link("edit"); ?>">
          <mbutton mid icon-only background=green color=dark>
            <mi>arrow_selector_tool</mi>
          </mbutton>
        </a>
      <?php endif; ?>
    </div>

    <div title fl fldircol alic jucc gap=smol+ posrel>
      <mi posabs color=tertiary>deployed_code</mi>
      <div style="line-height:.9;">
        <p text widester bold tac><?= $Project->name ?></p>
      </div>
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
                <?php
                $last_commit_date = $commits[0]->commit->author->date ?? null;
                echo $last_commit_date
                  ? "Letzter Commit &middot; <span color=primary>" . Time::ago($last_commit_date) . "</span>"
                  : "Nichts commited"
                ?>
              </p>
            </div>
          </mbutton>
        </a>
      </div>
    </div>

    <div identity>
      <picture>
        <img src="<?= $Project->identity() ?>" style="width:100%;" />
      </picture>
    </div>
  </div>

<?php endif;
