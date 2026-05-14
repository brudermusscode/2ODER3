<?php

require _root() . "/config/get_requirements.php";

/**
 * @var Request $Request
 */

exit(\Bruder\Controller\Controller::call(__FILE__, __DIR__));
