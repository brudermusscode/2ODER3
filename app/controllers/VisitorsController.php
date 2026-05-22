<?php

namespace Bruder\Controller;

use Bruder\Application\Cookie;
use Bruder\Controller\Controller;
use Bruder\Model\Visitor;
use Bruder\Application\Session as SessionManager;
use Bruder\Model\User;

class VisitorsController extends Controller
{
  /**
   * @return string
   */
  public function create()
  {

    # Not authorized to create a Visitor.
    if (!Cookie::get(Visitor::$identifier_cookie))
      return error("Du bimst nicht identifizierbar");

    # Visitor is a User already?
    if (CURRENT_BRUDER instanceof User)
      return success("Du hast jetzt einen User. Voll schön 😇");

    /**
     * @var Visitor
     */
    $Visitor = Visitor::revisiting() ?? new Visitor()->new();

    # Save the Visitor in the current session for easy access.
    SessionManager::set("Visitor", $Visitor);

    # Set a cookie with the UUID to validate Visitor when revisiting.
    Cookie::set(Visitor::$uuid_cookie, $Visitor->uuid, "+1 year");

    return success("Bist kein Bot Brudi. Hab dich lieb", data: $Visitor);
  }
}
