<?php

namespace Bruder\Controller;

use Bruder\Application\Cookie;
use Bruder\Controller\Controller;
use Bruder\Model\User;
use Bruder\Model\UserVerification;

class UserVerificationsController extends Controller
{

  /**
   * @return string
   */
  public function create()
  {

    $this->validate_params(
      strict: ["email", "uuid"],
      optional: [],
    );

    $cookie_uuid = Cookie::get("user-uuid");

    # ! UUIDs not matching.
    if ($cookie_uuid !== $this->params->uuid)
      return error("IDs passen nicht zusammen Brudi.");

    /**
     * @var ?User
     */
    $this->params->User = User::with("verification")
      ->where("uuid", $this->params->uuid)
      ->first();

    # ! User with this UUID is not existing.
    if (!$this->params->User)
      return error("Das musst du wohl nochmal versuchen.");

    return (new UserVerification)->new($this->params);
  }

  /**
   * This method is just used to validate the code the user
   * entered in a try to create or sign in a User.
   *
   * @return string
   */
  public function update()
  {

    $this->validate_params(
      strict: ["code", "email", "token"],
      optional: [],
    );

    /**
     * @var UserVerification
     */
    $UserVerification = UserVerification::with("user.sessions")
      ->where([
        "code" => $this->params->code,
        "email" => $this->params->email,
        "token" => $this->params->token
      ])
      ->first();

    if (!$UserVerification)
      return error("Ne man, da stimmt was nicht.");

    return $UserVerification->edit($this->params);
  }
}
