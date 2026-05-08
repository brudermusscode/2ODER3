<?php

use Bruder\Controller\CommentsController;

require _root() . "/config/get_requirements.php";

/**
 * @var Request $Request
 */

$method = pathinfo(__FILE__, PATHINFO_FILENAME);

$Controller = (new CommentsController($_POST, $_FILES))->$method();

exit($Controller);
