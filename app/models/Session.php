<?php

namespace Bruder\Model;

use Bruder\Application\Cookie;
use Bruder\Bruder;
use Bruder\Http\Request;
use Bruder\Utils\Utils;

class Session extends Bruder
{

  /**
   * @var array
   */
  protected $fillable = [
    "token",
    "ip",
    "agent",
  ];

  /**
   * @return bool
   */
  public function new(User $User)
  {

    $Session = new self;

    $token = Utils::random_alpha_token(32);

    $Session->token = $token;
    $Session->ip = Request::get_remote_address();
    $Session->agent = Request::maybe_human();
    $Session->user()->associate($User);
    $Session->save();

    Cookie::set("session-token", $token, "+1 year");
    Cookie::set("user-uuid", $User->uuid, "+1 year");

    if (!self::valid()) return false;

    return true;
  }

  /**
   * @return User
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  /**
   * Checks for a valid Session being set to identify a User as
   * logged in.
   *
   * @return bool
   */
  public static function valid()
  {
    $user_uuid_cookie = Cookie::get("user-uuid");
    $session_token_cookie = Cookie::get("session-token");

    if (!$user_uuid_cookie || !$session_token_cookie)
      return false;

    $Session = Session::where("token", $session_token_cookie)
      ->whereHas("user", function ($q) use ($user_uuid_cookie) {
        $q->where("uuid", $user_uuid_cookie);
      })
      ->with("user:id,uuid")
      ->first();

    if (!$Session) return false;

    return true;
  }
}
