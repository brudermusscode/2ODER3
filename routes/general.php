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
$Router->get("/fire/job", "job", return: "JSON");
$Router->get("/get-back", "login", title: "Wieder da? \\ " . APP_NAME);

# Just for intitializing the current visitor.
$Router->post("/visitor/create", "visitor/create", return: "JSON");

# ? Sessions
$Router->post("/session/create", "session/create", return: "JSON");
$Router->post("/session/delete", "session/delete", return: "JSON");

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
  "log/one",
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

# ? Reports
$Router->get("/report/new", "report/new", return: "JSON");
$Router->post("/report/create", "report/create", return: "JSON");

# ? Users
$Router->get("/user/new", "user/new", return: "JSON");
$Router->get("/user/email", "user/email", return: "JSON");
$Router->get("/user/code", "user/code", return: "JSON");
$Router->post("/user/create", "user/create", return: "JSON");
$Router->post("/user/update", "user/update", return: "JSON");

# ? UserVerifications
$Router->post("/user/verification/create", "user/verification/create", return: "JSON");
$Router->post("/user/verification/update", "user/verification/update", return: "JSON");
