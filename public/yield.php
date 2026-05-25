<?php

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

<body toggled="true" initialized="false" mobile="false"
  style="background-image:url(/bg-colors.png);">

  <ajax-response></ajax-response>
  <background-blur></background-blur>

  <?php

  /**
   * + Header
   */
  include TEMPLATE . "/global/_header.php";

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

  <?php if (!DEV && 1 === 2) : ?>
    <div window rounded fl alic gap=smol color=primary
      style="position:fixed;bottom:24px;left:24px;height:56px;padding-inline:18px 24px;z-index:110;">
      <mi midler>deployed_code</mi>
      <p text smolplus>Webseite befindet sich noch im Aufbau</p>
    </div>
  <?php endif ?>

</body>

</html>

<?php

include TEMPLATE . "/global/_yield-end.php";
