<?php

/**
 * @var string $video_src
 * @var string $video_thumb
 * @var bool $show_cinema_mode
 */

$video_src ??= "";
$video_thumb ??= "";
$show_cinema_mode ??= true;

?>

<video-wrapper <?= !$show_cinema_mode ? "prevent-cinema-mode" : "" ?>>
  <video poster="<?= $video_thumb ?>">
    <source src="<?= $video_src ?>" type="video/mp4" />
  </video>

  <video-top-toolbar>
    <volume>
      <mi></mi>
      <contains>
        <volume-track></volume-track>
      </contains>
    </volume>

    <?php if ($show_cinema_mode) : ?>
      <cinema-mode>
        <mi>aspect_ratio</mi>
      </cinema-mode>
    <?php endif ?>

    <fullscreen>
      <mi>open_in_full</mi>
    </fullscreen>
  </video-top-toolbar>

  <video-toggle>
    <mi></mi>
  </video-toggle>

  <video-toolbar>
    <buffer>
      <div smol primary class="spinner">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div>
    </buffer>
    <duration-track-wrapper>
      <contains>
        <duration-track></duration-track>
      </contains>
    </duration-track-wrapper>
  </video-toolbar>
</video-wrapper>