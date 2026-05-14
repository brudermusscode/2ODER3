<?php

use Bruder\Model\Project;

if (CURRENT_PAGE === "project") {
  $id = filter_var($GLOBALS["route_param_id"], FILTER_VALIDATE_INT) ?? 0;
  $Project = Project::find($id);
}
