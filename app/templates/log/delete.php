<?php

use Bruder\Controller\LogsController;

require _root() . "/config/get_requirements.php";

/**
 * @var Request $Request
 */

$Controller = (new LogsController($_POST))->delete();

exit($Controller);
