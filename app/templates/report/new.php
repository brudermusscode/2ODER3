<?php

use Bruder\Model\Report;
use Bruder\Model\Comment;

require_once dirname($_SERVER["DOCUMENT_ROOT"]) . "/config/get_requirements.php";

/**
 * @var int
 */
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

/**
 * @var ?string
 */
$type = filter_input(INPUT_GET, "type", FILTER_SANITIZE_SPECIAL_CHARS);

/**
 * @var ?Comment
 */
$Reference = Report::valid($type, $id) ?? die(ERROR);

# Begin output.
ob_start(); ?>

<popup-close>
  <mi>sign_language</mi>
</popup-close>

<popup-container>
  <popup-container__content p42 posrel style=z-index:2; elevated std>
    <form request="report:create" reload responder=simple
      fl fldircol gap>

      <input type=hidden name=type value=<?= $type ?> />
      <input type=hidden name=id value=<?= $id ?> />

      <div>
        <p text smol ttup slight>Gar kein Bock auf diese</p>
        <p text wide bold>
          <?= match ($type) {
            "comment" => "Bemerkung",
          }; ?>
        </p>
      </div>

      <?php if (($Reference instanceof Comment)) :

        /**
         * @var Comment
         */
        $Comment = $Reference;
        $show_tools = false;
        $show_larger = true;

        include TEMPLATE . "/comment/_comment_fldircol.php";
      endif ?>

      <p text slight>
        Ich bin dir sehr dankbar für deine Hilfe, diese Community freundlich zu halten 🥰</p>

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
