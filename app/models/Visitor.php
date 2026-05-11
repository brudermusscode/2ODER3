<?php

namespace Bruder\Model;

use Bruder\Application\Exception as ApplicationException;
use Bruder\Bruder;
use Illuminate\Support\Collection;
use Exception;

class Visitor extends Bruder
{

  /**
   * @var array
   */
  protected $fillable = [
    "nickname",
    "color",
    "ip",
  ];

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
   * @return ?Collection<Reaction>
   */
  public function reactions()
  {
    return $this->hasMany(Reaction::class);
  }

  /**
   * @return ?Collection<View>
   */
  public function views()
  {
    return $this->hasMany(View::class);
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
