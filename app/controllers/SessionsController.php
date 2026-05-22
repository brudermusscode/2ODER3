<?php

namespace Bruder\Controller;

use Bruder\Model\Session;
use Bruder\Model\User;
use Bruder\Model\Visitor;

class SessionsController extends Controller
{

  /**
   * @return string
   */
  public function create()
  {

    $this->validate_params(
      strict: ["nickname", "password"],
      optional: [],
    );

    # Already logged in?
    if (LOGGED)
      return error("Du bist schon eingeloggt jooonge");

    /**
     * @var ?User
     */
    $User = User::where("nickname", $this->params->nickname)
      ->where("email_verified", 1)
      ->whereNotNull("email")
      ->first();

    # Nickname or password wrung?
    if (!$User || !$User->verify_password($this->params->password))
      return error("Äh, da haste dich vertippt ⌨️");

    # Create a new Session!
    new Session()->new($User);

    # Delete everything related to a Visitor.
    Visitor::clean_up();

    return success();
  }

  /**
   * @return string
   */
  public function delete()
  {

    if (LOGGED)
      # This uses $this to set some params to invalidate a real
      # Session but it's not necessary to destroy one. So we can
      # just call this method on a brand new instance.
      new Session()->cancel();

    return success("Tschöö mit ö!");
  }
}
