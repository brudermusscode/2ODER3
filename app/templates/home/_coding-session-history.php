<?php

use Illuminate\Support\Collection;
use Bruder\Model\CodingSessionProject;

/**
 * @var Collection<CodingSessionProject>
 */
$CSHistory = $CodingSession->projects;

if ($CSHistory->count() > 1) : ?>
  <div fl fldircol gap=smol>
    <?php if (!$CodingSession->finished_at) : ?>
      <p text smol ttup pinline32 slight>In dieser Session</p>
    <?php endif; ?>
    <div in-this-session fl alic gap=smol ovauto pinline32 pb32 no-scrollbars>
      <?php foreach ($CSHistory as $key => $CSProject) : ?>
        <div flone p24 pl42
          background=<?= $CSProject->stopped_at ? "slight-light" : "slighter-green" ?>
          rounded ovhid posrel style="min-width:200px;">
          <mi style="font-size:72px;bottom:-18px;left:-12px;z-index:-1;" color=<?= $CSProject->stopped_at ? "tertiary" : "light" ?> slighter posabs>folder_data</mi>
          <?php if ($CSProject->stopped_at) : ?>
            <p text smolplus semibold>
              <?= $CSProject->project->name ?></p>
            <p text smoler>Für <?= $CSProject->time_elapsed() ?></p>
          <?php else : ?>
            <p text smolplus bold>
              <?= $CSProject->project->name ?></p>
            <p text smoler color=tertiary>Aktuell dabei</p>
          <?php endif; ?>
        </div>

        <?php if ($key <= $CSHistory->count() - 2) : ?>
          <mi>arrow_right</mi>
        <?php endif; ?>

      <?php endforeach; ?>
    </div>
  </div>
<?php elseif (!$CodingSession->title) : ?>
  <div pblock42></div>
<?php endif; ?>