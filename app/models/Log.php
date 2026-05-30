<?php

namespace Bruder\Model;

use Bruder\Application\Exception;
use Bruder\Application\Logger;
use Bruder\Bruder;
use Bruder\File\Upload;
use Illuminate\Support\Collection;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Media\Video;
use FFMpeg\FFProbe\DataMapping\Format;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Format as InterventionImageFormat;


class Log extends Bruder
{

  /**
   * @var array
   */
  protected $fillable = [
    "project_id",
    "src",
    "file_name",
    "name",
    "description",
    "views",
    "published_at"
  ];

  /**
   * @return Project
   */
  public function parent()
  {
    return $this->project;
  }

  /**
   * @param object $params
   * @return string
   */
  public function new(object $params)
  {

    /**
     * @var self
     */
    $Log = self::make();

    # ? Video file
    if (!$Log->upload_video($params->file))
      return error("Upload failed");

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

    # ? Project
    if (!empty($params->project_id))
      $this->project_id = Project::find($params->project_id)?->id;

    # ? Name
    if (!empty($params->name)) {
      $this->name = $params->name;
    }

    # ? Description
    if (!empty($params->name)) {
      $this->description = $params->description;
    }

    # ? Selected Thumbnail
    $this->thumb_selected = $params->thumb_selected ?? $this->thumb_selected;

    # * Save!
    $this->save();

    return success(data: $this);
  }

  /**
   * @return ?Project
   */
  public function project()
  {
    return $this->belongsTo(Project::class, "project_id");
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
   * @return ?Collection<View>
   */
  public function views()
  {
    return $this->hasMany(View::class);
  }

  public function raw_file_path()
  {
    return Upload::data_save_path(for: "videos") . "/" . $this->file_name;
  }

  /**
   * @return string
   */
  public function video_src()
  {
    return "/data/videos/{$this->file_name}";
  }

  /**
   * On file upload, it generates more than one thumb, which I
   * can choose one from to display throughout the page. This
   * creates the public path to the currently selected thumb
   * source file.
   *
   * @return string
   */
  public function current_thumb_src(string $size = "std")
  {
    $file_name = explode(".", $this->file_name)[0];

    return "/data/videos/thumbs/{$file_name}_{$this->thumb_selected}"
      . ($size === "small" ? "_350" : "")
      . ".webp";
  }

  /**
   * @return string
   */
  public function raw_file_name()
  {
    return explode(".", $this->file_name)[0];
  }

  /**
   * @return ?string
   */
  public function increase_views(Visitor|User|null $Client)
  {

    if (!$Client?->exists) return ERROR;

    /**
     * @var ?View
     */
    $LastView = $Client->views()
      ->where([
        "log_id" => $this->id,
      ])
      ->latest()
      ->first();

    # Last view has to be 5 minutes in the past to generate a
    # new one.
    if ($LastView && time() - $LastView->created_at->getTimestamp() < 300)
      return null;

    # Create it!
    $View = new View;
    $View->client()->associate($Client);
    $View->log()->associate($this);
    $View->save();

    # Increase views for easy and resource saving access.
    $this->increment("views");

    return success(data: $View->fresh());
  }

  /**
   * Programm has evolved and some things around thumbnails have
   * changed. So I need a function to recreate thumbs for all my
   * devlogs.
   *
   * @return void
   */
  public function reacreate_thumbs()
  {
    $this->save_thumbs(amount: 3);
    $this->save();
  }

  /**
   * @param int $amount
   * @return array string
   */
  public function save_thumbs(int $amount = 1)
  {

    $thumbs = [];

    try {
      for ($i = 0; $i < $amount; $i++) {

        # The filename without the extension.
        $file_name_no_ext = explode(".", $this->file_name)[0];

        $video_path = Upload::data_save_path(for: "videos");
        $thumb_path = Upload::data_save_path(for: "thumbs");
        $video_file_path = "$video_path/" . $this->file_name;
        $pre_thumb_name = "{$file_name_no_ext}_" . ($i + 1);

        /**
         * @var FFMpeg
         */
        $ffmpeg = FFMpeg::create();

        /**
         * @var Video
         */
        $video = $ffmpeg->open($video_file_path);

        /**
         * @var Format
         */
        $Format = $video->getFormat();
        $duration = $Format->get("duration"); # => 100%

        # For every iteration of $amount, we divide the $duration by
        # that $amount and multiply it by the current iteration $i.
        # This will give us a number in seconds which is always in
        # scope of the $duration and ever increasing with every
        # iteration, ensuring a different thumb.
        # (… - 0.1) because FFMpeg can't seem to get a thumb from the
        # very last frame.
        $progress = ($duration / $amount) * ($i + 1 - 0.1);

        $final_thumb_path = "$thumb_path/$pre_thumb_name.webp";

        # Generate a thumb and save it based on the length of the video.
        $video->frame(TimeCode::fromSeconds($progress))
          ->save($final_thumb_path);

        $thumbs[] = $final_thumb_path;

        # Create a 350px version of thumb.
        $ImageManager = ImageManager::usingDriver(GdDriver::class);
        $Image = $ImageManager->decodePath($final_thumb_path);
        $Image->scale(width: 350);
        $EncodedImage = $Image->encodeUsingFormat(InterventionImageFormat::WEBP, quality: 100);
        $EncodedImage->save("$thumb_path/{$pre_thumb_name}_350.webp");
      }

      # ? Thumb count
      $this->thumb_count = $amount;

      # ? Selected thumb
      $this->thumb_selected = 1;

      # # Yessss!
      return $thumbs;
    } catch (\Exception $e) {
      Logger::to_file($e);

      # Clean up all uploaded thumbs.
      if (isset($thumbs) && count($thumbs) > 0)
        foreach ($thumbs as $thumb)
          Upload::clean_up($thumb);

      return false;
    }
  }

  /**
   * @param array $file
   * @return bool
   */
  public function upload_video(array $file)
  {

    # Die if any error is set.
    Upload::error($file);

    $microtime = self::format_microtime(microtime());
    $save_path = _root() . "/public/data/videos";
    $extension = pathinfo($file["name"], PATHINFO_EXTENSION);
    $file_name = $microtime . "." . $extension;
    $final_path = $save_path . "/" . $file_name;

    try {

      # Move the temporary file to permanent.
      $moved = move_uploaded_file($file["tmp_name"], $final_path);
      if (!$moved)
        return error("Could not move file");

      # ? File name
      $this->file_name = $file_name;

      # ? Thumbs
      # If thumb upload fails, I can delete the whole video again.
      # Might want to try recreating thumbs automatically or by
      # button press.
      if (!$this->save_thumbs(amount: 3)) {
        Upload::clean_up("$save_path/$file_name");
        return false;
      }

      return true;
    } catch (\Exception $e) {
      Logger::to_file($e);

      if (isset($save_path, $file_name))
        Upload::clean_up("$save_path/$file_name");

      return false;
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
