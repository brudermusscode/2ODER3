<?php

use Bruder\Model\Log;

/**
 * @var int $id
 * @var string $sub
 * @var Log $Log
 */

$log_link = "/log/" . $id . "/edit/project";

?>

<content log-new mid>
  <form request="log:update" redirect="<?= $log_link ?>" responder=simple
    fl fldircol gap=mid>

    <?php

    $video_src = $Log->video_src();
    $video_thumb = $Log->current_thumb_src();
    $show_cinema_mode = false;
    include TEMPLATE . "/global/_video-wrapper.php";

    ?>

    <choose-option thumbnails fl fldircol gap=smol+>
      <input-title color=tertiary>Thumbnail</input-title>
      <div fl alic gap=smol+>
        <?php for ($i = 1; $i <= $Log->thumb_count; $i++) : ?>
          <coption rounded ovhid
            data-value="<?= $i ?>"
            <?= $i === $Log->thumb_selected ? "active" : "" ?>>
            <picture>
              <img
                src="/data/videos/thumbs/<?= $Log->raw_file_name() . "_" . ($i) . ".webp" ?>" />
            </picture>
          </coption>
        <?php endfor ?>
      </div>

      <input type=hidden name=thumb_selected value=<?= $Log->thumb_selected ?> />
    </choose-option>

    <div w100>
      <input-title color=tertiary>Name</input-title>
      <input tabindex="1" autocomplete="off" crazy autofocus type=text name=name placeholder="Name" value="<?= $Log->name ?? "" ?>" />
    </div>

    <div w100>
      <input-title color=tertiary>Beschreibung</input-title>
      <textarea tabindex="2" auto-resize rows=3 crazy placeholder="Beschreibuuuung" name=description><?= $Log->description ?? "" ?></textarea>
    </div>

    <input type=hidden name=id value=<?= $Log->id ?> />
    <input type=hidden name=__admin_key value="<?= _env("WEB_ADMIN_KEY") ?>" />

    <div fl alic>
      <mbutton mid tabindex="3" icon-only background=slight-light
        data-action="log:delete"
        data-id="<?= $id ?>">
        <mi>delete_forever</mi>
      </mbutton>

      <div minline24 divide-horiz w100></div>

      <mbutton has-tooltip=left wide tabindex="3" icon-only background=tertiary color=tertiary-text submit-closest>
        <mi>deployed_code</mi>
        <div ttooltip text semibold>Projekt zuordnen</div>
      </mbutton>
    </div>
  </form>
</content>