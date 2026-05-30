 <?php

  use Illuminate\Support\Collection;
  use Bruder\Model\CodingSession;
  use Bruder\Model\CodingSessionProject;

  /**
   * @var ?CodingSession
   */
  $CodingSession = CodingSession::with("current_project")
    ->with("projects")
    ->whereNull("finished_at")
    ->latest()
    ->first();

  if ($CodingSession) : ?>
   <div fl fldircol gap=smol>
     <coding-session background=hover-dark rounded=mid>
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
         <p text tac semibold mblock32 pinline32><?= $CodingSession->title ?></p>
       <?php endif; ?>


       <?php

        /**
         * @var Collection<CodingSessionProject>
         */
        $CSHistory = $CodingSession->projects;

        if ($CSHistory->count() > 1) : ?>
         <div fl fldircol gap=smol>
           <p text smoler ttup pinline32>In dieser Session</p>
           <div in-this-session fl alic gap=smol ovauto pinline32 pb32 no-scrollbars>
             <?php foreach ($CSHistory as $CSProject) : ?>
               <div flone p24 pl42
                 background=<?= $CSProject->stopped_at ? "slight-light" : "slighter-green" ?>
                 rounded ovhid posrel style="min-width:200px;">
                 <mi style="font-size:72px;bottom:-18px;left:-12px;z-index:-1;" color=<?= $CSProject->stopped_at ? "tertiary" : "light" ?> slighter posabs>folder_data</mi>
                 <?php if ($CSProject->stopped_at) : ?>
                   <p text smolplus semibold>
                     <?= $CSProject->project->name ?></p>
                   <p text smoler><?= $CSProject->time_elapsed() ?></p>
                 <?php else : ?>
                   <p text smolplus bold>
                     <?= $CSProject->project->name ?></p>
                   <p text smoler color=tertiary>Aktuell dabei</p>
                 <?php endif; ?>
               </div>
               <?php if ($CSProject->stopped_at) : ?>
                 <mi>arrow_right</mi>
               <?php endif; ?>
             <?php endforeach; ?>
           </div>
         </div>
       <?php elseif (!$CodingSession->title) : ?>
         <div pblock42></div>
       <?php endif; ?>
     </coding-session>
   </div>
 <?php endif; ?>