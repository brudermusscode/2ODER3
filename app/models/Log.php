<?php

namespace Bruder\Model;

use Bruder\Bruder;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Media\Video;
use FFMpeg\FFProbe\DataMapping\Format;

class Log extends Bruder
{

  /**
   * @var array
   */
  protected $fillable = [
    "src",
    "file_name",
    "name",
    "description",
  ];

  /**
   * @param object $params
   * @return string
   */
  public function new(object $params)
  {

    /**
     * @var Project
     */
    $Project = $params->Project;

    /**
     * @var self
     */
    $Log = self::make();

    # ? Video file
    if (!empty($params->file["tmp_name"])) {
      $upload = $Log->upload_video($params->file);
      if (!$upload) return error("Upload failed");
    }

    # ? Name
    if (!empty($params->name)) {
      $Log->name = $params->name;
    }

    # ? Description
    if (!empty($params->name)) {
      $Log->description = $params->description;
    }

    # ? Project
    $Log->project_id = $Project->id;

    # * Save!
    $Log->save();

    return success(data: $Log);
  }

  /**
   * @param object $params
   * @return string
   */
  public function edit(object $params)
  {

    return success();

    # ? Video file
    if (!empty($params->file["tmp_name"]))
      $this->upload_video($params->file);

    # ? Name
    if (!empty($params->name)) {
      $this->name = $params->name;
    }

    # ? Description
    if (!empty($params->name)) {
      $this->description = $params->description;
    }

    # * Save!
    $this->save();

    return success(data: $this);
  }

  /**
   * @return Project
   */
  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  /**
   * @return ?Collection<Reaction>
   */
  public function reactions()
  {
    return $this->hasMany(Reaction::class);
  }

  /**
   * @return ?Collection<Comment>
   */
  public function comments()
  {
    return $this->hasMany(Comment::class);
  }

  /**
   * @param array $file
   * @return bool
   */
  public function upload_video(array $file)
  {

    # Die if any error is set.
    \Bruder\File\Upload::error($file);

    $microtime = self::format_microtime(microtime());
    $save_path = _root() . "/public/data/videos";
    $thumb_path = "$save_path/thumbs";
    $thumb_name = $microtime . ".webp";
    $extension = pathinfo($file["name"], PATHINFO_EXTENSION);
    $file_name = $microtime . "." . $extension;
    $final_path = $save_path . "/" . $file_name;

    try {

      # Move the temporary file to permanent.
      $moved = move_uploaded_file($file["tmp_name"], $final_path);

      // ! Moving failed
      if (!$moved) return error("Could not move file");

      # ? File name
      $this->file_name = $file_name;

      # Create thumbnail.
      $ffmpeg = FFMpeg::create();

      /**
       * @var Video
       */
      $video = $ffmpeg->open($final_path);

      /**
       * @var Format
       */
      $Format = $video->getFormat();
      $duration = $Format->get("duration");
      $video->frame(TimeCode::fromSeconds(match (true) {
        $duration < 20 => 6,
        default => 20,
      }))
        ->save("$thumb_path/$thumb_name");

      # ? thumb_name
      $this->thumb_name = $thumb_name;

      return true;
    } catch (\Exception $e) {
      if (isset($save_path, $filename) && file_exists("$save_path/$filename"))
        unlink("$save_path/$filename");

      return error($e->getMessage());
    }
  }

  /**
   * @param string|float $microtime
   * @return string
   */
  public static function format_microtime(string|float $microtime)
  {
    $desiredLength = 13;

    /**
     * Remove any non-numeric characters.
     */
    $microtime = preg_replace("/[^0-9]/", "", $microtime);

    /**
     * Check if the string is longer than desired length.
     */
    if (strlen($microtime) > $desiredLength)
      $microtime = substr($microtime, 0, $desiredLength);
    elseif (strlen($microtime) < $desiredLength)
      $microtime = str_pad($microtime, $desiredLength, "0", STR_PAD_RIGHT);

    return $microtime;
  }
}
