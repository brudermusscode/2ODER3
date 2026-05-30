 <?php

  use Illuminate\Support\Collection;
  use Bruder\Model\CodingSession;
  use Bruder\Model\CodingSessionProject;
  use Bruder\Time\Time;

  /**
   * @var ?CodingSession
   */
  $CodingSession = CodingSession::with("current_project")
    ->with("projects")
    ->latest()
    ->first();

  if (!$CodingSession->finished_at) : ?>
   <coding-session active background=hover-dark rounded=mid>
     <?php

      $session_start = $CodingSession->created_at;
      $current_time = new DateTime("now");

      $time_elapsed = $current_time->diff($session_start);

      ?>

     <div fl jucsb alistart p32>
       <div fl alic gap=smol>
         <div fl fldircol gap=smoler>
           <p text smoler ttup>Aktive coding session</p>
           <div fl alic gap=smoler>
             <mi midler color=tertiary>deployed_code</mi>
             <p text midler bold><?= $CodingSession->current_project->name ?></p>
           </div>
         </div>
       </div>

       <?php if (authorized()) : ?>
         <div fl alic gap=smol>
           <a href="<?= $CodingSession->link("edit") ?>">
             <mbutton std background=slighter-light has-icon=left>
               <mi>undo</mi>
               Projekt wechseln
             </mbutton>
           </a>

           <mbutton icon-only color=secondary background=slight-light
             request="<?= $CodingSession->link("update", true) ?>" shadow-submit reload
             data-id="<?= $CodingSession->id ?>"
             data-finish=1>
             <mi>commit</mi>
           </mbutton>
         </div>
       <?php endif; ?>
     </div>

     <div fl alistart jucc gap=smolest>
       <div fl fldircol alic style=width:120px;>
         <p h text widester bold color=primary>
           <?= $time_elapsed->h < 10 ? "0" . $time_elapsed->h : $time_elapsed->h; ?></p>
         <p text ttup slight style="font-size:14px;">Stunden</p>
       </div>
       <p text widester slight>&middot;</p>
       <div fl fldircol alic style="width:120px;">
         <p i text widester bold color=primary>
           <?= $time_elapsed->i < 10 ? "0" . $time_elapsed->i : $time_elapsed->i ?></p>
         <p text ttup slight style="font-size:14px;">Minuten</p>
       </div>
       <p text widester slight>&middot;</p>
       <div fl fldircol alic style="width:120px;">
         <p s text widester bold color=primary>
           <?= strlen($time_elapsed->s) < 2 ? "0" . $time_elapsed->s : $time_elapsed->s ?></p>
         <p text ttup slight style="font-size:14px;">Sekunden</p>
       </div>
     </div>

     <?php if ($CodingSession->title) : ?>
       <p text tac semibold mblock32 mb42 pinline32><?= $CodingSession->title ?></p>
     <?php endif; ?>

     <?php

      # + Coding Session History.
      include __DIR__ . "/_coding-session-history.php"; ?>

   </coding-session>
 <?php else : ?>
   <coding-session background=hover-dark rounded=mid>
     <div pinline42 pblock32 pt42>
       <div fl alic gap=smol text smol>
         <p semibold>Letzte Coding Session</p>
         &middot;
         <p color=primary><?= Time::ago($CodingSession->finished_at); ?></p>
       </div>

       <?php $elapsed = $CodingSession->time_elapsed(); ?>
       <div fl alic gap=smol>
         <p text wider bold>
           <?= ($elapsed->h < 10 ? "0" . $elapsed->h : $elapsed->h) . " &middot; "; ?></p>
         <p text wider bold>
           <?= ($elapsed->i < 10 ? "0" . $elapsed->i : $elapsed->i) . " &middot; "; ?></p>
         <p text wider bold>
           <?= ($elapsed->s < 10 ? "0" . $elapsed->s : $elapsed->s); ?></p>
       </div>
     </div>

     <?php

      # + Coding Session History.
      include __DIR__ . "/_coding-session-history.php"; ?>
   </coding-session>
 <?php endif; ?>