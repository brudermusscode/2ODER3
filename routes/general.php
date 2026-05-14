<?php

/**
 * General routes like the home and error pages.
 */

use Bruder\Application\Router;
use Bruder\Model\Project;

/**
 * @var Router $Router
 */

$Router->get("/not-found", "error/404", title: "Bruder, was geht jetzt?");
$Router->get("/", "home/index", title: APP_NAME . " - Bruder! Geil!");

# ? Logs
$Router->get("/get/log/comments", "log/_comments", return: "JSON");
$Router->get("/get/log/reactions", "log/_reactions", return: "JSON");
$Router->get("/log/new", "log/new", title: "Neuen Devlog erstellen");
$Router->get("/log/:id/edit", "log/edit", title: "Devlog bearbeiten");
$Router->get("/log/:id/edit/:sub", "log/edit", title: "Devlog bearbeiten");
$Router->post("/log/create", "log/create", return: "JSON");
$Router->post("/log/update", "log/update", return: "JSON");
$Router->post("/log/delete", "log/delete", return: "JSON");

# ? Projects
$Router->post("/project/create", "project/create", return: "JSON");
$Router->get("/project/new", "project/new", title: "Neung Projenkt erstelln");
$Router->get(
  "/project/:id",
  "project/one",
  constraints: ["id" => "\d+",],
  title: function ($params) {
    $Project = Project::find($params["id"]);
    return $Project ? "⌞Projekt\\{$Project->name}⌝" : "Keine Ahnung, Bruder.";
  }
);
$Router->get(
  "/project/:id/log/:log_id",
  "project/one",
  constraints: [
    "id" => "\d+",
    "log_id" => "\d+"
  ],
  title: function ($params) {
    $Project = Project::find($params["id"]);
    $Log = $Project->logs()->where("id", $params["log_id"])->first();
    return $Project && $Log?->name
      ? "{$Log->name} · ⌞Projekt\\{$Project->name}⌝"
      : (
        $Project ? "Devlog · ⌞Projekt\\{$Project->name}⌝" : "Keine Ahnung, Bruder."
      );
  }
);

# ? Reactions
$Router->post("/reaction/create", "reaction/create", return: "JSON");
$Router->post("/reaction/delete", "reaction/delete", return: "JSON");

# ? Comments
$Router->post("/comment/create", "comment/create", return: "JSON");
$Router->post("/comment/delete", "comment/delete", return: "JSON");

# ? Report
$Router->get("/report/new", "report/new", return: "JSON");
$Router->post("/report/create", "report/create", return: "JSON");
