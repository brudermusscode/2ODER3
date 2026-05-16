<?php

namespace Bruder\Model;

use Bruder\Application\Cookie;
use Illuminate\Support\Collection;
use Bruder\Bruder;
use Bruder\Http\Request;
use Bruder\Utils\Utils;

class User extends Bruder
{

  /**
   * @var array
   */
  protected $fillable = [
    "uuid",
    "nickname",
    "email",
    "email_verified",
    "color",
    "password",
    "agent",
    "ip",
  ];

  /**
   * @param object $params
   * @return string
   */
  public function new(object $params)
  {

    /**
     * @var User
     */
    $User = self::make();

    /**
     * ? UUID
     * While looping until the UUID is 100 % unique.
     */
    $User->uuid = Utils::create_uuid();
    while (User::where("uuid", $User->uuid)->exists())
      $User->uuid = Utils::create_uuid();

    /**
     * ? User Agent
     */
    $User->agent = Request::maybe_human();

    /**
     * ? IP Address
     */
    $User->ip = Request::get_remote_address();

    /**
     * ? Nickname
     * Something like: great_bruder-
     * Simple but effective!
     */
    $nickname = trim($params->nickname);

    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $nickname))
      return error("Bro d1 Spitzname ist broke. Nur mit abcd…, 0123… und _ oder -.");

    if (strlen($nickname) > 24 || strlen($nickname) < 2)
      return error("Bro, Name bitte zwischen 2 und 24 Zeichen!");

    if (User::where("nickname", $nickname)->first())
      return error("Sorry Bro, der Name ist schon vergeben 🥲");

    $User->nickname = $nickname;

    /**
     * ? Password
     */
    if (strlen($params->password) < 6)
      return error("Brudi, Password weniger als 6 Zeichen? 🫨");

    $User->encrypt_password($params->password);

    /**
     * ? Color
     * Users should be able to pick a custom color, not only from
     * the pre selected ones.
     */
    $color = trim($params->color);
    if (!preg_match('/^#?([a-f0-9]{6}|[a-f0-9]{3})$/i', $color))
      return error("Bro deine Farbe ist keine Farbe.");

    $User->color = $color;

    # # Save it!
    $User->save();

    # Set a cookie with the UUID to save the progress of User
    # creation.
    Cookie::set("user-uuid", $User->uuid, "+1 month");

    return success("User created!", data: $User->fresh());
  }

  /**
   * @param object $params
   * @return string
   */
  public function edit(object $params)
  {

    /**
     * ? User Agent
     */
    $this->agent = Request::maybe_human();

    /**
     * ? IP Address
     */
    $this->ip = Request::get_remote_address();

    /**
     * ? Nickname
     * Something like: great_bruder-
     * Simple but effective!
     */
    $nickname = trim($params->nickname);

    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $nickname))
      return error("Bro d1 Spitzname ist broke. Nur mit abcd…, 0123… und _ oder -.");

    if (strlen($nickname) > 24 || strlen($nickname) < 2)
      return error("Bro, Name bitte zwischen 2 und 24 Zeichen!");

    # If this user has not yet completed sign up and has claimed
    # this name already, they of course should be able to still
    # take it.
    if (
      User::where("nickname", $nickname)
      ->whereNot("uuid", $this->uuid)
      ->first()
    )
      return error("Sorry Bro, der Name ist schon vergeben 🥲");

    $this->nickname = $nickname;

    /**
     * ? Password
     */
    if (strlen($params->password) < 6)
      return error("Brudi, Password weniger als 6 Zeichen? 🫨");

    $this->encrypt_password($params->password);

    /**
     * ? Color
     * Users should be able to pick a custom color, not only from
     * the pre selected ones.
     */
    $color = trim($params->color);
    if (!preg_match('/^#?([a-f0-9]{6}|[a-f0-9]{3})$/i', $color))
      return error("Bro deine Farbe ist keine Farbe.");

    $this->color = $color;

    # # Save it!
    $this->save();

    # Set a cookie with the UUID to save the progress of User
    # creation.
    Cookie::set("user-uuid", $this->uuid, "+1 month");

    return success("User updated!", data: $this->fresh());
  }

  /**
   * @return Collection<Session>
   */
  public function sessions()
  {
    return $this->hasMany(Session::class);
  }

  /**
   * @return ?UserVerification
   */
  public function verification()
  {
    return $this->hasOne(UserVerification::class);
  }

  /**
   * @return Collection<Report>
   */
  public function reports()
  {
    return $this->hasMany(Report::class, "reference_id", "id");
  }

  /**
   * Encrypt a given password with ARGON2ID.
   *
   * @param string $password
   * @param bool $md5
   * @return bool
   */
  public function encrypt_password(string $password, bool $md5 = true)
  {

    try {

      # MD5 hash the password, if requested.
      $password = $md5 ? md5($password) : $password;

      # Create a new hash of the password using ARGON boy.
      $this->password = password_hash($password, PASSWORD_ARGON2ID);

      return true;
    } catch (\Exception) {
      return false;
    }
  }
}
