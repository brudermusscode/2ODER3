<?php

namespace Bruder\Controller;

use Bruder\Application\Logger;
use Bruder\File\Upload;
use Bruder\Model\Log;

class JobsController extends Controller
{

  /**
   * @return string
   */
  public function recreate_thumbs()
  {

    $this->authorize();

    try {
      # Delete all old thumbs
      $thumbs = glob(Upload::data_save_path(for: "thumbs") . "/*");
      foreach ($thumbs as $thumb)
        unlink($thumb);

      # Create new thumbs.
      foreach (Log::all() as $Log) {
        $Log->reacreate_thumbs();
      }

      return success("Job complete");
    } catch (\Exception $e) {
      Logger::to_file($e);

      return error($e->getMessage());
    }
  }
}
