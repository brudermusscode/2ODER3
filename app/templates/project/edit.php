<?php

use Bruder\Model\Project;

/**
 * @var string
 */
$name = filter_var($GLOBALS["route_param_name"] ?? "", FILTER_SANITIZE_SPECIAL_CHARS);

/**
 * @var ?Project
 */
$Project = Project::where("name", $name)->first();

if (!$Project || !authorized()) :
  include UNAVAILABLE;
else : ?>

  <content mid miauto pb62 pt12>
    <form request="project:update" redirect="<?= $Project->link() ?>" responder=simple
      fl fldircol gap=mid>

      <div>
        <input-title color=tertiary>Identität</input-title>
        <media-select trigger-file-input <?= $Project->identity ? "filled" : ""; ?>>
          <?php if ($Project->identity) : ?>
            <img src="<?= $Project->identity() ?>" />
          <?php endif; ?>
          <mi color=quadro>video_camera_back_add</mi>
          <input type=file accept="image/*" name=file hidden />
        </media-select>
      </div>

      <div>
        <input-title color=tertiary>Heißung</input-title>
        <input crazy w100 name=name placeholder="Name" value="<?= $Project->name ?>" />
      </div>

      <div>
        <input-title color=tertiary>Open Source URL</input-title>
        <input crazy w100 name=url placeholder="GitHub/…" value="<?= $Project->url ?>" />
      </div>

      <input type=hidden name=id value=<?= $Project->id ?> />

      <div fl jucend alic gap=smol>
        <mbutton request="project:delete" shadow-submit data-id="<?= $Project->id ?>"
          mid icon-only background=slight-light color=light>
          <mi>delete_forever</mi>
        </mbutton>
        <div divide-horiz w100 minline24></div>
        <mbutton submit-closest wide icon-only background=green color=dark>
          <mi>publish</mi>
        </mbutton>
      </div>
    </form>
  </content>

<?php endif;
