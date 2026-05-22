<?php

namespace Bruder\Model;

use Bruder\Application\Cookie;
use Bruder\Application\Session as ApplicationSession;
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
   * The token cookie name for validating a session.
   */
  public static string $token_cookie = "session-token";

  /**
   * Indicates, if the session instance is valid at the moment.
   */
  public bool $valid = false;

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

    # Set cookies for persisting the session.
    Cookie::set(self::$token_cookie, $token, "+1 year");
    Cookie::set(User::$uuid_cookie, $User->uuid, "+1 year");

    # Set the User to the php session for easy access.
    ApplicationSession::set("User", $User);

    # Make the Session valid!
    $Session->validate();

    # Needless but I want it anyways 🫶.
    if (!$Session->valid)
      return false;

    return true;
  }

  /**
   * Determines, if a valid Session is currently active in the
   * Clients browser (by cookies) and sets this instance to
   * in/valid based on it.
   *
   * @return self
   */
  public function init()
  {
    $user_uuid_cookie = Cookie::get(User::$uuid_cookie);
    $session_token_cookie = Cookie::get("session-token");

    # Return null if any necessary relation is falsy.
    if (!$user_uuid_cookie || !$session_token_cookie)
      return $this->cancel();

    /**
     * @var ?self
     */
    $Session = Session::where("token", $session_token_cookie)
      ->whereHas("user", function ($q) use ($user_uuid_cookie) {
        $q->where("uuid", $user_uuid_cookie);
      })
      ->with("user")
      ->first();

    # No Session found!
    if (!$Session)
      # Calling invalidate() on "$this" specifically, because it's removing
      # left over cookies that could still be set from old sessions. Just
      # makes sure that everything is cleanymeany. 😃
      return $this->invalidate();

    return $Session->validate();
  }

  /**
   * Sets the current instance to valid.
   *
   * @return self
   */
  public function validate()
  {
    $this->valid = !0;

    return $this;
  }

  /**
   * Sets the current instance to invalid.
   *
   * @return null
   */
  public function invalidate()
  {
    return $this->cancel();
  }

  /**
   * Removes all session relatives.
   *
   * @return void
   */
  public function cancel()
  {
    Cookie::delete(User::$uuid_cookie);
    Cookie::delete("session-token");

    $this->valid = false;

    return $this;
  }

  /**
   * @return User
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
