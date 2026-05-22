<?php

use Bruder\Application\Cookie;
use Bruder\Model\User;
use Bruder\Model\UserVerification;

require_once dirname($_SERVER["DOCUMENT_ROOT"]) . "/config/get_requirements.php";

/**
 * @var string
 */
$email = filter_input(INPUT_GET, "email", FILTER_VALIDATE_EMAIL);

/**
 * @var string
 */
$tolkien = filter_input(INPUT_GET, "tolkien", FILTER_SANITIZE_SPECIAL_CHARS);

$UserVerification = UserVerification::where([
  "email" => $email,
  "token" => $tolkien,
])
  ->first();

# No UserVerification exists?
if (!$UserVerification)
  exit(error("Nein."));

# The pre uuid cookie for the User.
$uuid = Cookie::get(User::$pre_uuid_cookie);

/**
 * Find a User with the set user pre uuid and the user_id of
 * the UserVerification.
 * @var ?User
 */
$User = User::where([
  "uuid" => $uuid,
  "id" => $UserVerification->user_id
])->first();

# No User with this UUID exists?
if (!$User)
  exit(error("Nein. Was ist das für eine UUID? 🙂‍↔️"));

ob_start(); ?>

<popup-close>
  <mi>sign_language</mi>
</popup-close>

<popup-container>
  <popup-container__content p42 posrel style=z-index:2; elevated std>
    <form data-action="user:verification:update"
      fl fldircol gap>

      <div>
        <p text smol ttup>Hab ich da</p>
        <p text wide bold>1 Mail gehört?</p>
      </div>

      <div fl fldircol gap=smol+>
        <div input has-icon=left>
          <mi color=secondary>password_2</mi>
          <input crazy type=text placeholder="Code" name=code <?= DEV ? "value=" . $UserVerification->code : "" ?> />
        </div>
      </div>

      <p text std>Eine Mail haben wir an <strong><?= $email; ?></strong> gesendet. Guck auch in d1 Spam 🙂</p>

      <input type=hidden name=email value="<?= $email ?>" />
      <input type=hidden name=token value="<?= $tolkien ?>" />
      <input type=hidden name=uuid value="<?= $uuid ?>" />

      <div fl alic jucsb gap=smol+ mt12 pl12>
        <div smol class=spinner>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div></div>
        </div>

        <mbutton submit-closest mid background=tertiary color=tertiary-text icon-only>
          <mi>fingerprint</mi>
        </mbutton>
      </div>

    </form>
  </popup-container__content>
</popup-container>


<?php

die($Request->success(data: ob_get_clean()));
