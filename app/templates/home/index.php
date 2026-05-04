<?php

use Illuminate\Support\Collection;
use Bruder\Model\Log;


/**
 * @var Project
 */
$Project = $GLOBALS["CurrentProject"];


/**
 * @var Collection<Log>
 */
$Logs = $Project->logs;

/**
 * @var Log
 */
$SelectedLog = $Logs->first();

include TEMPLATE . "/project/_content.php";
