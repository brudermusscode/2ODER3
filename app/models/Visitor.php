<?php

namespace Bruder\Model;

use Bruder\Application\Cookie;
use Bruder\Application\Exception as ApplicationException;
use Bruder\Application\Logger;
use Bruder\Application\Session;
use Bruder\Bruder;
use Bruder\Http\Request;
use Bruder\Trait\IsClient;
use Bruder\Utils\Utils;
use DateTime;
use Exception;

class Visitor extends Bruder
{
  # Includes Relations for Comments, Views, …
  use IsClient;

  /**
   * @var array
   */
  protected $fillable = [
    "uuid",
    "agent",
    "nickname",
    "color",
    "ip",
  ];

  /**
   * The unique identifier for any Viisitor to determine a
   * revisit.
   */
  public static string $uuid_cookie = "visitor-uuid";

  /**
   * Name of the Identifier cookie, which has to be set before
   * a new Visitor will be created.
   */
  public static string $identifier_cookie = "visitor-identifier";

  /**
   * Various predefined color HEX codes, which Nicknames and
   * User Identities will be shown in.
   */
  public static array $colors = [
    "#fff158",
    "#9c3fff",
    "#f154fc",
    "#ff27c9",
    "#ff2386",
    "#40f65e",
    "#3ff0be",
    "#26cdeb",
    "#2391ff",
  ];

  /**
   * With this, there are 625 possible name combinations. We add
   * numbers to the end, up to 9990 and get a possibility of
   * 6.243.750 different name combinations!
   */
  protected static array $name = [
    "prefix" => [
      "Holy",
      "Grace",
      "Faith",
      "Psalm",
      "Cross",
      "Lion",
      "Mercy",
      "Chosen",
      "Saint",
      "Spirit",
      "Kingdom",
      "Blessed",
      "Hope",
      "Living",
      "Prayer",
      "Heavenly",
      "Truth",
      "Gospel",
      "Shepherd",
      "Alpha",
      "Covenant",
      "Glory",
      "Redeemed",
      "Divine",
      "Eternal"
    ],
    "suffix" => [
      "Walker",
      "Keeper",
      "Runner",
      "Seeker",
      "Soul",
      "Flame",
      "Knight",
      "Pilgrim",
      "Beacon",
      "Servant",
      "Light",
      "Warrior",
      "Voice",
      "Path",
      "Witness",
      "Disciple",
      "Messenger",
      "Builder",
      "Watcher",
      "Nomad",
      "Follower",
      "Guardian",
      "Singer",
      "Traveler",
      "Believer"
    ]
  ];

  /**
   * @return string
   */
  public function new()
  {

    $Visitor = self::make();

    # ? Agent
    $Visitor->agent = Request::maybe_human();

    # Check, if the visitor might be a real human or, if it is a
    # bot. In case of bots, we do not want to create a new
    # Visitor but return the bare instance.
    if (!$Visitor->agent) {
      self::clean_up();

      return error("Possibly 1 Bot lol.");
    }

    # ? Universally unique identifier
    $Visitor->uuid = Utils::create_uuid();
    while (Visitor::where("uuid", $Visitor->uuid)->exists())
      $Visitor->uuid = Utils::create_uuid();

    # ? Nickname
    $Visitor?->set_unique_name();

    # ? Remote address
    $Visitor->ip = Request::get_remote_address();

    # ? Color
    $Visitor->color = self::$colors[array_rand(self::$colors)];

    # # Save it!
    $Visitor->save();

    return $Visitor;
  }

  /**
   * Checks for a Visitor being set through a UUID saved in a
   * cookie. It will instantly return null if no UUID cookie is
   * set. Some prevention for database spamming.
   *
   * @return ?Visitor
   */
  public static function revisiting()
  {

    $cookie_uuid = Cookie::get("visitor-uuid");

    if (!$cookie_uuid) return null;

    /**
     * @var ?Visitor
     */
    $Visitor = Visitor::where("uuid", $cookie_uuid)
      ->first();

    # Set any missing values for a possible instance.
    $Visitor?->set_unique_name();
    $Visitor?->set_color();

    # In case there is a uuid saved as a cookie, but no Visitor
    # relating to it, we can delete relations.
    if (!$Visitor) {
      self::clean_up();
    }

    return $Visitor;
  }

  /**
   * Cleans up all relations to a Visitor, including a possible
   * database entry.
   *
   * @return void
   */
  public static function clean_up()
  {
    $uuid_cookie = Cookie::get(self::$uuid_cookie);

    # Delete a possible Visitor database entry.
    if ($uuid_cookie)
      Visitor::where("uuid", $uuid_cookie)
        ->delete();

    # Remove cookies and session data.
    Cookie::delete(self::$uuid_cookie);
    Cookie::delete(self::$identifier_cookie);
    Session::remove("Visitor");
  }

  /**
   * @param User $User
   * @return bool
   */
  public function transform_into(User $User)
  {
    try {
      $this->db_transaction();

      # Update all relations.
      foreach ($this->model_relations as $r) {
        $this->$r()->update([
          "client_id" => $User->id,
          "client_type" => $User::class,
        ]);
      }

      $this->db_commit();

      # Everything transformed? Wonderful! Time to clean
      # up all Visitor relations.
      self::clean_up();

      # Remove user pre uuid cookie. The actual uuid cookie
      # will be set through Sessino creation.
      Cookie::delete(User::$pre_uuid_cookie);

      return true;
    } catch (\Throwable $e) {
      Logger::to_file($e);

      $this->db_rollback();
      return false;
    }
  }

  /**
   * @return void
   */
  public function update_last_seen()
  {
    if (!$this->exists) return;

    $CurrentVisitorLastSeen = $this->updated_at->getTimestamp();
    $CurrentTime = new DateTime("now")->getTimestamp();

    if ($CurrentTime - $CurrentVisitorLastSeen <= 5)
      $this->touch();
  }

  /**
   * Checks for a Visitor and all it's relations to be set.
   *
   * @return bool
   */
  public static function authorized()
  {
    $uuid_cookie = Cookie::get(self::$uuid_cookie);

    return $uuid_cookie
      && Cookie::get(self::$identifier_cookie)
      && Session::get("Visitor", allow_empty: false)
      && Visitor::where("uuid", $uuid_cookie)->first();
  }

  /**
   * @return true
   */
  public function set_color()
  {

    # Nothing to do if there is a color already.
    if ($this->color && !is_numeric($this->color)) return true;

    $this->color = self::$colors[array_rand(self::$colors)];
    $this->save();

    return true;
  }

  /**
   * @return bool
   */
  public function set_unique_name()
  {

    # Nothing to do when there's a nickname already.
    if ($this->nickname) return true;

    # No valid name by now, add a number at the end.
    if (!$this->while_exists_by_nickname(return_at_count: 625))
      if (!$this->while_exists_by_nickname(add_number: true, return_at_count: (625 * 9990))) {
        ApplicationException::log(new Exception("No names left! Create new ones!"), "Visitor::create");
        return false;
      }

    # # Save it!
    $this->save();

    return true;
  }

  /**
   * Checks the uniqueness of the nickname using a while loop and
   * fetching every randomly generated name from the database. If
   * one unique has been found, returns true. Else false.
   *
   * @param bool $add_number
   * @param int $return_at_count
   * @return bool
   */
  public function while_exists_by_nickname(bool $add_number = false, int $return_at_count = -1)
  {
    $count = 0;
    $this->nickname = self::build_random_name($add_number);

    while (Visitor::where("nickname", $this->nickname)->exists()) {
      if ($count === $return_at_count)
        return false;

      $this->nickname = self::build_random_name($add_number);
      $count++;
    }

    return true;
  }

  /**
   * @param bool $add_number
   * @return string
   */
  public static function build_random_name(bool $add_number = false)
  {
    return self::$name["prefix"][array_rand(self::$name["prefix"])]
      . self::$name["suffix"][array_rand(self::$name["suffix"])]
      . ($add_number ? rand(10, 9999) : "");
  }
}
