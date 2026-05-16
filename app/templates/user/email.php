<?php

use Bruder\Application\Cookie;
use Bruder\Model\User;

require_once dirname($_SERVER["DOCUMENT_ROOT"]) . "/config/get_requirements.php";

/**
 * @var string
 */
$tolkien = filter_input(INPUT_GET, "tolkien", FILTER_SANITIZE_SPECIAL_CHARS);

# ! No User with this UUID exists.
if (!User::where("uuid", $tolkien)->first())
  exit(error("Nein."));

$cookie_uuid = Cookie::get("user-uuid");

# ! UUIDs not matching.
if ($cookie_uuid !== $tolkien)
  exit(error("Da ist was falsch mit den IDs."));

# Begin output.
ob_start(); ?>

<popup-close>
  <mi>sign_language</mi>
</popup-close>

<popup-container>
  <popup-container__content p42 posrel style=z-index:2; elevated std>
    <form data-action="user:verification:create"
      fl fldircol gap>

      <div>
        <p text smol ttup>Es muss noch</p>
        <p text wide bold>1 Schritt sein</p>
      </div>

      <div fl fldircol gap=smol+>
        <div input has-icon=left>
          <mi color=secondary>alternate_email</mi>
          <input crazy type=text placeholder="E-Mail Adresse" name=email />
        </div>
      </div>

      <p text>
        Gib deine E-Mail an und du kriegst 1 Code zugeschickt, damit ich weiß, dass du real bimst.</p>

      <input type=hidden name=uuid value="<?= $tolkien ?>" />

      <div fl alic jucend gap=smol+ mt12>
        <mbutton submit-closest mid background=tertiary color=tertiary-text icon-only>
          <mi>arrow_forward</mi>
        </mbutton>
      </div>

    </form>
  </popup-container__content>
</popup-container>


<?php

die($Request->success(data: ob_get_clean()));
