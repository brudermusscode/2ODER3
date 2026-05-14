<?php

use Bruder\Model\Log;

if (!authorize(exit_as: BOOLER)) :
  include UNAVAILABLE;
else :

  /**
   * @var int
   */
  $id = filter_var($GLOBALS["route_param_id"] ?? 0, FILTER_VALIDATE_INT);

  /**
   * @var string
   */
  $sub =  filter_var($GLOBALS["route_param_sub"] ?? "", FILTER_SANITIZE_SPECIAL_CHARS);

  # Set a default for the sub page.
  if (!$sub || !in_array($sub, ["metadata", "project"]))
    $sub = "metadata";

  /**
   * @var ?Log
   */
  $Log = Log::find($id);

  if (!$Log) :
    include UNAVAILABLE;
  else :

    $file_path = __DIR__ . "/edit/_$sub.php";

    include file_exists($file_path) ? $file_path : __DIR__ . "/edit/_metadata.php";
  endif;
endif;
