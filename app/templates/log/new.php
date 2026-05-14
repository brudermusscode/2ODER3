<?php

/**
 * Authorize the client to view this page.
 */
# TODO: Authorization should be in the router as a seperate layer.
if (!authorize(exit_as: BOOLER)) :
  include UNAVAILABLE;
else :

?>

  <form data-action="log:create" enctype="multipart/form-data">
    <content log-new fl fldircol gap=mid>

      <div fl fldircol gap=smol+>
        <p text bold smol ttup pinline24>Video-Datei auswählen</p>

        <div posrel h100 w100>
          <media-select trigger-file-input>
            <mi color=quadro>video_camera_back_add</mi>
            <input type=file accept="video/*" name=file hidden />
          </media-select>

          <mbutton tabindex="1" wide icon-only background=tertiary color=tertiary-text submit-closest
            style="position:absolute;bottom:32px;right:32px;" z>
            <mi>arrow_upload_progress</mi>
          </mbutton>
        </div>
      </div>

      <input type=hidden name=__admin_key value="<?= _env("WEB_ADMIN_KEY") ?>" />

      <div>
        <progress-bar>
          <progress-track></progress-track>
        </progress-bar>

        <div processing-message fl alic gap jucc>
          <div smol class="spinner">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
          </div>

          <p text smol ttup bold>Wird verarbeitet bruder</p>
        </div>
      </div>
    </content>
  </form>

<?php endif;
