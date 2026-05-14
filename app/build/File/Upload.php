<?php

namespace Bruder\File;

class Upload
{

  /**
   * Dies on any upload error.
   *
   * @param array $file
   * @param bool $json_encoded
   * @return bool|die
   */
  public static function error(array $file, bool $json_encoded = true)
  {
    $error = match ($file["error"]) {
      default => false,
      UPLOAD_ERR_OK => false,
      UPLOAD_ERR_INI_SIZE => "Datei zu groß (php.ini)",
      UPLOAD_ERR_FORM_SIZE => "Datei zu groß (HTML-Form)",
      UPLOAD_ERR_PARTIAL => "Upload unvollständig",
      UPLOAD_ERR_NO_FILE => "Keine Datei hochgeladen",
      UPLOAD_ERR_NO_TMP_DIR => "Temp-Ordner fehlt",
      UPLOAD_ERR_CANT_WRITE => "Kann Datei nicht schreiben",
      UPLOAD_ERR_EXTENSION => "Upload durch Extension gestoppt",
    };

    return $error ? die(error($error, json_encoded: $json_encoded)) : false;
  }

  /**
   * @return string
   */
  public static function data_save_path(string $for = "videos")
  {
    $path = _root() . "/public/data" . match ($for) {
      "identities",
      "videos" => "/",
      "thumbs" => "/videos/",
      default => "",
    }
      . $for;

    if (!is_dir($path))
      mkdir($path, 0777);

    return $path;
  }

  /**
   * Checks if a given file exists and deletes it.
   *
   * @param array|string $paths
   * @return void
   */
  public static function clean_up(array|string $paths)
  {
    if (is_string($paths))
      $paths = [$paths];

    foreach ($paths as $path)
      if (file_exists($path))
        unlink($path);
  }
}
