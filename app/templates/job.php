<?php

use Bruder\Controller\JobsController;

/**
 * Authorize the client for taking action here.
 */
authorize(exit_as: JSON);

$Controller = new JobsController;
$method = filter_input(INPUT_GET, "method", FILTER_SANITIZE_SPECIAL_CHARS);

// ! Method non-existent
if (!method_exists($Controller, $method))
  die(error("Method doesn't exist"));

exit($Controller->$method());
