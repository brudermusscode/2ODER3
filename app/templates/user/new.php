<?php

use Bruder\Model\Visitor;

require_once dirname($_SERVER["DOCUMENT_ROOT"]) . "/config/get_requirements.php";

# Begin output.
ob_start(); ?>

<popup-close>
  <mi>sign_language</mi>
</popup-close>

<popup-container>
  <popup-container__content p42 posrel style=z-index:2; elevated std>
    <form data-action="user:create"
      fl fldircol gap>

      <div>
        <p text smol ttup>Hier, um zu</p>
        <div fl alic gap=smol>
          <p text wide bold>bleiben?</p>
        </div>
      </div>

      <div fl fldircol gap=smol+>
        <div input has-icon=left>
          <mi color=secondary>sticker</mi>
          <input tabindex=1 autofocus crazy type=text placeholder="Spitzname" name=nickname value="<?= CURRENT_VISITOR->nickname ?? "" ?>" />
        </div>
        <div input has-icon=left>
          <mi color=secondary>lock</mi>
          <input tabindex=2 crazy type=password autocomplete=new-password placeholder="Password" name=password />
        </div>
      </div>

      <div fl fldircol gap=smol+ mt12>
        <div>
          <p text smol bold ttup>Farbe vong Account</p>
          <p text smolplus slight>Bemerkungen und so werden so angezeigt</p>
        </div>
        <choose-option fl alic jucsb gap=smol flex-wrap>
          <?php

          $tabindex = 3;

          foreach (Visitor::$colors as $key => $color) : ?>
            <coption
              tabindex="<?php echo $tabindex;
                        $tabindex++; ?>"
              data-value="<?= $color ?>"
              <?= CURRENT_VISITOR?->color === $color ? "active" : "" ?>
              pinline18 pblock18 circled style="max-width:48px;background:<?= $color ?>;">
            </coption>
          <?php endforeach ?>
          <input type=hidden name=color value="<?= CURRENT_VISITOR?->color ?? "" ?>" />
        </choose-option>
      </div>


      <div fl alic jucsb gap=smol+ mt12>
        <p text smol>
          <a href="/legal/privacy">Datenschutzerklärung</a> nicht vergessen. Damit erklärst du dich einverstanden, wenn du unten draufdrückst. Hammer, dass du beitreten willst!
        </p>
        <div background="slighter-light" rounded pblock22 style="min-width:4px;"></div>
        <mbutton tabindex="<?= $tabindex++; ?>" submit-closest mid background=green icon-only>
          <mi>done</mi>
        </mbutton>
      </div>

    </form>
  </popup-container__content>
</popup-container>


<?php

die($Request->success(data: ob_get_clean()));
