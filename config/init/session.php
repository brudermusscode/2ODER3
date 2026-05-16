<?php

use Bruder\Application\Session as SessionManager;
use Bruder\Application\Cookie;

/**
 * Initialize a new session.
 */
new SessionManager;

# Setthe auth token for dev environment.
if (_env("ENVIRONMENT") === "dev") Cookie::set("__admin_key", _env("WEB_ADMIN_KEY"), "+2 Months");
