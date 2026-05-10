<?php

use Bruder\Model\Log;

/**
 * @var int
 */
$log_id = filter_input(INPUT_GET, "log_id", FILTER_VALIDATE_INT);

/**
 * @var ?Log
 */
$SelectedLog = Log::with("comments.visitor")
  ->where("id", $log_id)
  ->first();

if (!$SelectedLog)
  exit(error("Kein Log"));

ob_start(); ?>

<comments-top-bar fl alic gap=smol pinline12>
  <p text smoler ttup bold slight>Bemerkungen</p>
</comments-top-bar>

<div none fl alic jucc window>
  <div>
    <div fl fldircol alic>
      <div fl alic style="margin-left:-24px;margin-bottom:-8px;">
        <div style="height:2.8em;width:2.8em;margin-right:-18px;" background=slighter-light circled></div>
        <div window pblock32 rounded style="max-width:182px;width:182px;"></div>
      </div>
      <div fl alic style="margin-left:24px;">
        <div style="height:2.8em;width:2.8em;margin-right:-18px;" background=slighter-light circled></div>
        <div window pblock28 rounded style="max-width:182px;width:182px;"></div>
      </div>
    </div>
    <p text slight semibold smol ttup tac mt24>Sei der Erste!</p>
  </div>
</div>

<?php

foreach ($SelectedLog->comments->sortByDesc("created_at") as $Comment) :
  include TEMPLATE . "/comment/_comment.php";
endforeach;

?>

<composer-contain>
  <composer>
    <form data-action="comment:create" fl alic gap=smol+>
      <div fl aliend flone gap=smol+ pr10>
        <textarea auto-resize rows=1 flone name=comment placeholder="Was denkst du?"></textarea>
        <mbutton mb10 size=std background=green icon-only submit-closest>
          <mi>keyboard_return</mi>
        </mbutton>
      </div>

      <input type=hidden name=log_id value=<?= $SelectedLog->id ?> />
    </form>
  </composer>
</composer-contain>

<!--
<script>
  $(function() {
    let comment_container = document.find("comments");
    let comments = comment_container.find_all("comment");

    console.log(comments);

    let timer = 100;
    comments.forEach((comment) => {
      setTimeout(() => {
        comment.activate();
      }, timer);
      timer += 40;
    });
  });
</script> -->

<?php exit(success(data: ob_get_clean()));
