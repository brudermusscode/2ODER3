<?php

namespace Bruder\Controller;

use Bruder\Application\Cookie;
use Bruder\Controller\Controller;
use Bruder\Model\User;

class UsersController extends Controller
{

  /**
   * @return string
   */
  public function create()
  {

    $this->validate_params(
      strict: ["nickname", "color", "password"],
      optional: [],
    );

    $cookie_uuid = Cookie::get("user-uuid");

    /**
     * @var ?User
     */
    $User = User::where("uuid", $cookie_uuid ?? "")
      ->first();

    return $User
      ? $User->edit($this->params)
      : (new User)->new($this->params);
  }
}
