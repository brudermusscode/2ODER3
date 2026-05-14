<?php

use Bruder\File\Upload;
use Illuminate\Support\Collection;
use Bruder\Model\Log;


if (CURRENT_VISITOR->id === 1) {

  # Delete all old thumbs
  $thumbs = glob(Upload::data_save_path(for: "thumbs") . "/*");
  foreach ($thumbs as $thumb)
    unlink($thumb);

  # Create new thumbs.
  foreach (Log::all() as $Log) {
    $Log->reacreate_thumbs();
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
