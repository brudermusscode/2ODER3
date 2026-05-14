<?php

use Illuminate\Support\Collection;
use Bruder\Model\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Format;


if (CURRENT_VISITOR->id === 1) {
  $dir = _root() . "/public/data/videos/thumbs";

  foreach (Log::all() as $L) {
    $fpath = "$dir/$L->thumb_name";

    if (!file_exists($fpath))
      continue;

    $filename_no_ext = explode(".", $L->thumb_name)[0];

    $manager = ImageManager::usingDriver(GdDriver::class);
    $image = $manager->decodePath($fpath);

    $image->scale(width: 350);

    $encoded = $image->encodeUsingFormat(Format::JPEG, quality: 100);
    $encoded->save("$dir/$filename_no_ext" . "-350.webp");
  }
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
