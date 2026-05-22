<?php

namespace Bruder\Controller;

use Bruder\Application\Cookie;
use Bruder\Controller\Controller;
use Bruder\Model\User;
use Bruder\Model\UserVerification;
use Bruder\Model\Visitor;

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

    $cookie_uuid = Cookie::get(User::$pre_uuid_cookie);

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
      strict: ["code", "email", "token", "uuid"],
      optional: [],
    );

    # A Visitor has to be set till here. The edit() method will
    # call a method that transformes the currently set Visitor
    # into a User, which will fail otherwise.
    if (!($this->params->Client instanceof Visitor))
      return error("Brudi, du bist k1 Besucher… Wie ist das möglich? 🫨");

    $uuid = $this->params->uuid;

    /**
     * Get a UserVerification with a corresponding User that shares
     * the same UUID as given from params.
     * @var UserVerification
     */
    $UserVerification = UserVerification::whereHas("user", function ($q) use ($uuid) {
      $q->where("uuid", $uuid);
    })
      ->with("user.sessions")
      ->where([
        "code" => $this->params->code,
        "email" => $this->params->email,
        "token" => $this->params->token
      ])
      ->first();

    if (!$UserVerification)
      return error("Ne man, da stimmt was nicht. Entweder gibts keinen User mit dieser UUID oder keine UserVerification. 🙂‍↔️");

    return $UserVerification->edit($this->params);
  }
}
