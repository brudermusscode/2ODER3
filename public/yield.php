<?php

use Bruder\Application\Cookie;
use Bruder\Model\Project;

/**
 * Require the main initialization file.
 */
require_once dirname(__DIR__) . "/config/init.php";

/**
 * Sanitizes the output for non DEV environments. Looks so cool
 * when going to the source code!
 */
if (PROD)
  ob_start("sanitize_output");

?>

<!DOCTYPE html>
<html lang=en>

<?php

/**
 * Create the correct canocial for google by removing the query string.
 */
$canonical = explode("?", $_SERVER["REQUEST_URI"]);
$canonical = HOME_URL . ($canonical[0] ?? "");

?>

<head>
  <link rel="canonical" href="<?= $canonical ?>" />
  <link rel="home" href="<?php echo HOME_URL; ?>" />
  <link rel="icon" type="image/x-icon" href="/favicon.svg" />
  <link rel="apple-touch-icon" href="/favicon.svg" />

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf_token" content="<?php echo $csrf_token; ?>" />

  <!--- Tell IE to render webpage for edge --->
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="application-name" content="<?php echo APP_NAME; ?>">

  <title><?= CURRENT_PAGE_TITLE ?></title>

  <?php

  /**
   * Include SEO stuff from the environment only if activated.
   */
  if (_env("SEO_ACTIVATED")) : ?>
    <meta name="keywords" content="<?php echo _env("SEO_KEYWORDS"); ?>" />
    <meta name="description" content="<?php echo $og->desc ?? _env("SEO_DESCRIPTION"); ?>" />
  <?php endif;

  /**
   * Any js and css file.
   */
  include TEMPLATE . "/global/_yield-requires.php"; ?>
</head>

<body toggled="true" initialized="false" mobile="false" style="background-image: url(/colors.svg);">

  <ajax-response></ajax-response>

  <background-blur></background-blur>

  <header fl alic jucsb>
    <div fl alic gap=smol+>
      <logo>
        <picture>
          <img src="/logo.svg" />
        </picture>
      </logo>
      <p text smol ttup><strong>DEV</strong>Log</p>

      <div style="height:24px;width:3px;" rounded background=slight-light></div>

      <?php

      $Projects = Project::orderBy("created_at", "DESC")->get();

      ?>

      <option-select>
        <mi stdplus color=tertiary>deployed_code</mi>
        <current-option><?= $CurrentProject->name ?></current-option>
        <mi midler>arrow_drop_down</mi>
        <options data-action="project:get">
          <p pinline20 pt8 pb6 text smoler ttup semibold color=tertiary>Meine Projekte</p>
          <?php foreach ($Projects as $Project) : ?>
            <option data-id=<?= $Project->id ?> <?= $CurrentProject->is($Project) ? "active" : "" ?>>
              <p text><?= $Project->name ?></p>
              <mi></mi>
            </option>
          <?php endforeach ?>
        </options>
      </option-select>
    </div>
    <div fl alic gap=smol>
      <a href="https://github.com/brudermusscode" extern target="_blank">
        <mbutton size=std icon-only background=slighter-light>
          <img src="/assets/images/github-white.svg" />
        </mbutton>
      </a>

      <div style="height:24px;width:3px;" rounded background=slight-light minline12></div>

      <?php if (Cookie::get("__admin_key") === _env("WEB_ADMIN_KEY")) : ?>
        <mbutton request-get="log:new" background=green color=dark size=std icon-only>
          <mi>arrow_upload_ready</mi>
        </mbutton>
      <?php endif; ?>
    </div>
  </header>

  <?php

  /**
   * + Page loader
   */
  include TEMPLATE . "/global/_page-loader.html";

  /**
   * Where all the dynamic content change magic happens! Include
   * the current page's template. You should not add anything
   * inside the <main></main> as it will be deleted when clicking
   * on a new page.
   */
  echo <<<HTML
    <main>
      $_INCLUDE_TEMPLATE
    </main>
  HTML; ?>

</body>

</html>

<?php

include TEMPLATE . "/global/_yield-end.php";
