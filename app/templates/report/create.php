<?php

use Bruder\Controller\ReportsController;

require _root() . "/config/get_requirements.php";

/**
 * @var Request $Request
 */

$method = pathinfo(__FILE__, PATHINFO_FILENAME);

$Controller = (new ReportsController($_POST, $_FILES))->$method();

exit($Controller);
