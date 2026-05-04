<?php

use Bruder\Application\Cookie;
use Bruder\Application\Session as SessionManager;
use Bruder\Model\Project;

/**
 * Initialize a new session.
 */
new SessionManager;

/**
 * @var Project
 */
$CurrentProject = Project::find(Cookie::get("__project_id") ?? 1) ?? Project::find(1);
