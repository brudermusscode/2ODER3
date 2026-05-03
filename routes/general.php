<?php

/**
 * General routes like the home and error pages.
 */

use Bruder\Application\Router;

/**
 * @var Router $Router
 */

$Router->get("/not-found", "error/404", title: "Bruder, was geht jetzt?");
$Router->get("/", "home/index", title: APP_NAME . " - Bruder! Geil!");

# ? Logs
$Router->get("/log/new", "log/new", return: "JSON");
$Router->get("/log/edit", "log/edit", return: "JSON");
$Router->post("/log/create", "log/create", return: "JSON");
$Router->post("/log/update", "log/update", return: "JSON");
$Router->post("/log/delete", "log/delete", return: "JSON");
