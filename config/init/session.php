<?php

use Bruder\Application\Session as SessionManager;
use Bruder\Application\Cookie;
use Bruder\Model\Session;

/**
 * Initialize a new PHP session object.
 */
new SessionManager;

/**
 * Initialize a new database-driven Session belonging to a User.
 */
$Session = (new Session)->init();

# Setthe auth token for dev environment.
if (_env("ENVIRONMENT") === "dev") Cookie::set("__admin_key", _env("WEB_ADMIN_KEY"), "+2 Months");
