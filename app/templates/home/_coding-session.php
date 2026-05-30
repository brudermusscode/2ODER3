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
   <coding-session active background=hover-dark rounded=mid fl fldircol jucsb>
     <?php

      $session_start = $CodingSession->created_at;
      $current_time = new DateTime("now");

      $time_elapsed = $current_time->diff($session_start);

      ?>

     <!--- Heading --->
     <div fl jucsb alistart pinline32 pt32>
       <div fl alic gap=smol>
         <div fl fldircol gap=smoler>
           <p text smol ttup color=tertiary>coding session</p>
           <div fl alic gap=smol>
             <mi mid color=tertiary>deployed_code</mi>
             <p text mid bold><?= $CodingSession->current_project->name ?></p>
           </div>
         </div>
       </div>

       <div fl alic gap=smol+>
         <?php if (authorized()) : ?>
           <div fl alic gap=smol>
             <a href="<?= $CodingSession->link("edit") ?>">
               <mbutton stdplus background=slighter-light has-icon=left>
                 <mi>folder_data</mi>
                 Andere Projenkt
               </mbutton>
             </a>

             <mbutton stdplus icon-only background=primary
               request="<?= $CodingSession->link("update", true) ?>" shadow-submit reload
               data-id="<?= $CodingSession->id ?>"
               data-finish=1>
               <mi>close</mi>
             </mbutton>
           </div>

           <div divide-vert rounded></div>
         <?php endif; ?>

         <mbutton tag has-icon=left stdplus background=green>
           <mi>planner_review</mi>
           <strong>Aktiv</strong>
         </mbutton>
       </div>
     </div>


     <!--- Timer + Title --->
     <div mt32>
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
         <div fl jucc mblock32 mb32>
           <div rounded p12 pinline18 background=slighterer-light>
             <p text tac semibold><?= $CodingSession->title ?></p>
           </div>
         </div>
       <?php endif; ?>
     </div>

     <!--- History --->
     <div>
       <?php

        # + Coding Session History.
        include __DIR__ . "/_coding-session-history.php"; ?>
     </div>
   </coding-session>
 <?php else : ?>

   <div fl fldircol gap=smol+>
     <div fl alic gap=smol pinline12>
       <p text smol bold ttup color=tertiary>Letzte Coding Session</p>
       &middot;
       <p><?= Time::ago($CodingSession->finished_at); ?></p>
     </div>

     <coding-session background=hover-dark rounded=mid>
       <div pinline42 pblock32>

         <?php $elapsed = $CodingSession->time_elapsed(); ?>
         <div fl alic gap=smol mt8>
           <div lh1 fl gap=smol>
             <p text wider bold>
               <?= ($elapsed->h < 10 ? "0" . $elapsed->h : $elapsed->h); ?></p>
             <p text smoler ttup slight style=rotate:90deg;margin-left:-42px;>Stunden</p>
           </div>
           <p text wider>&middot;</p>
           <div lh1 fl gap=smol>
             <p text wider bold>
               <?= ($elapsed->i < 10 ? "0" . $elapsed->i : $elapsed->i); ?></p>
             <p text smoler ttup slight style=rotate:90deg;margin-left:-42px;>Minuten</p>
           </div>
           <p text wider>&middot;</p>
           <div lh1 fl gap=smol>
             <p text wider bold>
               <?= ($elapsed->s < 10 ? "0" . $elapsed->s : $elapsed->s); ?></p>
             <p text smoler ttup slight style=rotate:90deg;margin-left:-42px;>Sekunde</p>
           </div>
         </div>
       </div>

       <?php

        # + Coding Session History.
        include __DIR__ . "/_coding-session-history.php"; ?>
     </coding-session>
   </div>
 <?php endif; ?>