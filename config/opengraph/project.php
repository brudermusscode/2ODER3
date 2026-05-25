<?php

use Bruder\Model\Project;

if (CURRENT_PAGE === "project") {
  $name = filter_var($GLOBALS["route_param_name"], FILTER_VALIDATE_INT) ?? 0;
  $Project = Project::where("name", $name)->first();
}
