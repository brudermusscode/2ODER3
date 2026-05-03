<?php

use Bruder\Controller\LogsController;

require _root() . "/config/get_requirements.php";

/**
 * @var Request $Request
 */

$method = pathinfo(__FILE__, PATHINFO_FILENAME);

$Controller = (new LogsController($_POST, $_FILES))->$method();

exit($Controller);
