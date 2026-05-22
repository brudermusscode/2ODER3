<?php

namespace Bruder\Model;

use Bruder\Application\Cookie;
use Bruder\Bruder;
use Bruder\Http\Request;
use Bruder\Trait\IsClient;
use Bruder\Utils\Utils;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Bruder
{
  # Includes Relations for Comments, Views, …
  use IsClient;

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
   * The pre defined uuid cookie name for creation process of
   * a User instance.
   */
  public static string $pre_uuid_cookie = "user-pre-uuid";

  /**
   * The actual identifier cookie name for an existing User.
   */
  public static string $uuid_cookie = "user-uuid";

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
    while (User::where("uuid", $User->uuid)->exists()) {
      $User->uuid = Utils::create_uuid();
    }

    # ? User Agent
    $User->agent = Request::maybe_human();

    # ? IP Addess
    $User->ip = Request::get_remote_address();

    /**
     * ? Nickname
     * Something like: great_bruder-
     * Simple but effective!
     */
    $nickname = trim($params->nickname);

    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $nickname)) {
      return error(
        "Bro d1 Spitzname ist broke. Nur mit abcd…, 0123… und _ oder -.",
      );
    }

    if (strlen($nickname) > 24 || strlen($nickname) < 2) {
      return error("Bro, Name bitte zwischen 2 und 24 Zeichen!");
    }

    if (User::where("nickname", $nickname)->first()) {
      return error("Sorry Bro, der Name ist schon vergeben 🥲");
    }

    $User->nickname = $nickname;

    /**
     * ? Password
     */
    if (strlen($params->password) < 6) {
      return error("Brudi, Password weniger als 6 Zeichen? 🫨");
    }

    $User->encrypt_password($params->password);

    /**
     * ? Color
     * Users should be able to pick a custom color, not only from
     * the pre selected ones.
     */
    $color = trim($params->color);
    if (!preg_match('/^#?([a-f0-9]{6}|[a-f0-9]{3})$/i', $color)) {
      return error("Bro deine Farbe ist keine Farbe.");
    }

    $User->color = $color;

    # ? Save it!
    $User->save();

    # Set a cookie with the UUID to save the progress of User
    # creation.
    Cookie::set(self::$pre_uuid_cookie, $User->uuid, "+1 month");

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

    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $nickname)) {
      return error(
        "Bro d1 Spitzname ist broke. Nur mit abcd…, 0123… und _ oder -.",
      );
    }

    if (strlen($nickname) > 24 || strlen($nickname) < 2) {
      return error("Bro, Name bitte zwischen 2 und 24 Zeichen!");
    }

    # If this user has not yet completed sign up and has claimed
    # this name already, they of course should be able to still
    # take it.
    if (
      User::where("nickname", $nickname)->whereNot("uuid", $this->uuid)->first()
    ) {
      return error("Sorry Bro, der Name ist schon vergeben 🥲");
    }

    $this->nickname = $nickname;

    /**
     * ? Password
     */
    if (strlen($params->password) < 6) {
      return error("Brudi, Password weniger als 6 Zeichen? 🫨");
    }

    $this->encrypt_password($params->password);

    /**
     * ? Color
     * Users should be able to pick a custom color, not only from
     * the pre selected ones.
     */
    $color = trim($params->color);
    if (!preg_match('/^#?([a-f0-9]{6}|[a-f0-9]{3})$/i', $color)) {
      return error("Bro deine Farbe ist keine Farbe.");
    }

    $this->color = $color;

    # ? Save it!
    $this->save();

    # A cookie with the uuid is saved when creating the user. This function
    # is only called, when a cookie had been set in the first place.

    return success("User updated!", data: $this->fresh());
  }

  /**
   * Only Users should be able to report interactions, since
   * Visitors can also interact with various things and are
   * the most vulnerable to get goofy.
   *
   * @return HasMany<Report>
   */
  public function reports()
  {
    return $this->hasMany(Report::class);
  }

  /**
   * @return HasMany<Session>
   */
  public function sessions()
  {
    return $this->hasMany(Session::class);
  }

  /**
   * @return HasOne<UserVerification>
   */
  public function verification()
  {
    return $this->hasOne(UserVerification::class);
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

  /**
   * @param string $password_plaintext
   * @return bool
   */
  public function verify_password(string $password_plaintext, bool $md5 = true)
  {
    $password = $md5 ? md5($password_plaintext) : $password_plaintext;

    return password_verify($password, $this->password);
  }
}
