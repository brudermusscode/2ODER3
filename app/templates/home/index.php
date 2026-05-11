<?php

use Illuminate\Support\Collection;
use Bruder\Model\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Format;

/**
 * Encode all images as WebP and decrease quality for faster
 * loading times.
 */
if (CURRENT_VISITOR->id === 1 && 1 === 2)
  foreach (Log::all() as $L) {
    $fpath = _root() . "/public/data/videos/thumbs/" . $L->thumb_name;

    if (!file_exists($fpath))
      continue;

    $manager = ImageManager::usingDriver(GdDriver::class);
    $image = $manager->decodePath($fpath);

    $encoded = $image->encodeUsingFormat(Format::WEBP, quality: 80);
    $encoded->save(_root() . "/public/data/videos/thumbs/" . $L->thumb_name);
  }

/**
 * @var Project
 */
$Project = $GLOBALS["CurrentProject"];

/**
 * @var Collection<Log>
 */
$Logs = $Project->fresh()->logs->sortByDesc("created_at");

/**
 * @var Log
 */
$SelectedLog = $Logs->first();



include TEMPLATE . "/project/_content.php";
