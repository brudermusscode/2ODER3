<?php

require_once dirname($_SERVER["DOCUMENT_ROOT"]) . "/config/get_requirements.php";

/**
 * Begin output buffer.
 */
ob_start();

?>

<popup-close>
  <mi>sign_language</mi>
</popup-close>


<popup-container>
  <popup-container__content p4 posrel style=z-index:2; elevated stdplus>
    <form request="log:create" reload responder=simple
      fl fldircol gap=mid
      enctype="multipart/form-data">

      <input type=hidden name=project_id value=1 />

      <media-select trigger-file-input fl jucc>
        <mi color=quadro>video_camera_back_add</mi>
        <input type=file accept="video/*" name=file hidden />
      </media-select>

      <div pinline38 pb38 pt12 fl fldircol gap=mid>
        <div fl fldircol gap=smol>
          <p text smol ttup bold>Metadaten</p>
          <div fl fldircol gap>
            <input tabindex="1" crazy autofocus type=text name=name placeholder="Name" />
            <textarea tabindex="2" crazy placeholder="Beschreibuuuung" name=description></textarea>
          </div>
        </div>

        <div fl jucend alic>
          <mbutton tabindex="3" material size=mid icon-only background=tertiary color=tertiary-text submit-closest>
            <mi>arrow_upload_progress</mi>
          </mbutton>
        </div>
      </div>
    </form>
  </popup-container__content>
</popup-container>


<?php

die($Request->success(data: ob_get_clean()));
