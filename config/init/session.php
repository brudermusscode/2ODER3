<?php

use Bruder\Application\Session as SessionManager;
use Bruder\Http\Request;
use Bruder\Model\Project;
use Bruder\Model\Visitor;

/**
 * Initialize a new session.
 */
new SessionManager;


# Set the CurrentProject to 1, the very first one if the user has
# none set in their session.
if (!SessionManager::get("CurrentProject") || !SessionManager::get("CurrentProject")?->exists())
  SessionManager::set(
    "CurrentProject",
    Project::with(["logs" => function ($q) {
      $q->orderBy("created_at", "DESC");
    }])->find(1)
  );

$CurrentProject = SessionManager::get("CurrentProject");

global $CurrentProject;


# Check if the Visitor is revisiting or a new comer.
$remote_address = Request::get_remote_address();
$CurrentVisitor = SessionManager::get("Visitor");

if (!$CurrentVisitor) {

  /**
   * Find the Visitor based on their remote address or create a
   * new one.
   *
   * @var Visitor
   */
  $CurrentVisitor = Visitor::where("ip", $remote_address)->first()
    ?? Visitor::create([
      "ip" => $remote_address,
      "color" => array_rand(Visitor::$colors),
    ]);
}

/**
 * @var Visitor $CurrentVisitor
 */

# Set various things for a unique visitor.
$CurrentVisitor->set_unique_name();
$CurrentVisitor->set_color();

# Add the Visitor Instance to their Session.
SessionManager::set("Visitor", $CurrentVisitor->fresh());

/**
 * Touch the visitors profile every 5 minutes to create something
 * like "last seen". Using DateTime objects = S000 Nice! 😆
 */
$CurrentVisitorLastSeen = new DateTime($CurrentVisitor->updated_at);
$CurrentTime = new DateTime("now");
if ($CurrentTime->diff($CurrentVisitorLastSeen)->i <= 5)
  $CurrentVisitor->touch();

global $CurrentVisitor;
