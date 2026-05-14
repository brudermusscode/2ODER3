<?php


require_once dirname($_SERVER["DOCUMENT_ROOT"]) . "/config/get_requirements.php";

# Authorize the client to take action here.
authorize(exit_as: BOOLER);

# Begin output.
ob_start(); ?>

<popup-close>
  <mi>sign_language</mi>
</popup-close>

<popup-container>
  <popup-container__content p42 posrel style=z-index:2; elevated std>
    <form request="project:create" reload responder=simple
      fl fldircol gap enctype="multipart/form-data">

      <div>
        <p text smol ttup>Hallo I bims, 1 neuem</p>
        <div fl alic gap=smol>
          <p text wide bold>Projenkt</p>
          <div has-tooltip=bottom circled>
            <mi mid color=secondary curpo>help</mi>
            <div ttooltip text semibold>Verwendeter Jargon: Vong</div>
          </div>
        </div>
      </div>

      <div fl fldircol gap=smol+>
        <input crazy type=text placeholder="Name" name=name />
        <input crazy type=text placeholder="GitHub oder Link" name=url />
      </div>

      <div fl fldircol gap=smol mt24>
        <p text smol bold ttup>Identität</p>
        <media-select smol trigger-file-input>
          <mi color=quadro>add_photo_alternate</mi>
          <input type=file accept="image/*" name=file hidden />
        </media-select>
      </div>

      <div fl alic jucend gap=smol+>
        <mbutton submit-closest mid background=red icon-only>
          <mi>done</mi>
        </mbutton>
      </div>

    </form>
  </popup-container__content>
</popup-container>


<?php

die($Request->success(data: ob_get_clean()));
