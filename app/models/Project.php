<?php

namespace Bruder\Model;

use Bruder\Application\Logger;
use Bruder\Bruder;
use Bruder\File\Upload;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Format;

class Project extends Bruder
{

  /**
   * @var array
   */
  protected $fillable = [
    "name",
    "url",
    "identity",
  ];

  protected string $route_key = "name";

  /**
   * @param object $params
   * @return string
   */
  public function new(object $params)
  {

    $Project = self::make();

    /**
     * ? Name
     */
    $serialized_name = trim($params->name);
    if (!$serialized_name) return error("Falscher Name");

    $Project->name = $params->name;

    /**
     * ? URL
     */
    if (!filter_var($params->url, FILTER_VALIDATE_URL))
      return error("Falsche URL");

    $Project->url = $params->url;

    /**
     * ? Identity
     */
    if (!empty($params->file["tmp_name"]))
      $Project->upload_identity($params->file, Upload::data_save_path(for: "identities"));

    $Project->save();

    return success("Alles gut m8");
  }

  /**
   * @param object $params
   * @return string
   */
  public function edit(object $params)
  {

    # ? Name
    $serialized_name = trim($params->name);
    if (!$serialized_name) return error("Falscher Name");

    $this->name = $params->name;

    # ? URL
    if (!filter_var($params->url, FILTER_VALIDATE_URL))
      return error("Falsche URL");

    $this->url = $params->url;

    # ? Identitiy
    if (!empty($params->file["tmp_name"]))
      $this->upload_identity($params->file, Upload::data_save_path(for: "identities"));

    $this->save();

    return success("Alles gut m8");
  }

  /**
   * @return HasMany<Log>
   */
  public function logs()
  {
    return $this->hasMany(Log::class);
  }

  /**
   * @return HasMany<CodingSession>
   */
  public function coding_sessions()
  {
    return $this->hasMany(CodingSession::class);
  }

  /**
   * @param array $file
   * @return bool
   */
  public function upload_identity(array $file, ?string $save_path = null)
  {

    # Die if any error is set.
    Upload::error($file);

    $microtime = Log::format_microtime(microtime());
    $save_path = $save_path ?: Upload::data_save_path(for: "");
    $file_name = "$microtime.webp";
    $final_path = "$save_path/$file_name";

    try {

      $ImageManager = ImageManager::usingDriver(GdDriver::class);
      $Image = $ImageManager->decodePath($file["tmp_name"]);
      // $Image->scale(width: 600);
      $EncodedImage = $Image->encodeUsingFormat(Format::WEBP, quality: 100);
      $EncodedImage->save($final_path);

      $this->identity = $file_name;

      return null;
    } catch (\Exception $e) {
      Logger::to_file($e);

      if (isset($final_path))
        Upload::clean_up($final_path);

      die(error($e->getMessage()));
    }
  }

  /**
   * @return string
   */
  public function identity()
  {
    return "/data/identities/" . $this->identity;
  }
}
