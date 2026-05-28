<?php

use Illuminate\Support\Collection;
use Bruder\Model\Project;

?>

<sidebar fl fldircol jucsb gap pl12>
  <div fl fldircol gap ovauto pb100>
    <div fl fldircol alic>
      <a fl fldircol alic jucc pinline20 href="/">
        <logo>
          <picture circled>
            <img src="/logo.png" />
          </picture>
        </logo>
      </a>
      <p smol ttup z style="text-shadow:0 -1px 3px black;">
        <strong>2</strong>oder<strong>3</strong>
      </p>
    </div>

    <?php if (!LOGGED) : ?>
      <div pinline12>
        <mbutton request-get="user:new" stdplus background=secondary color=secondary-text has-icon=left>
          <mi>crowdsource</mi>
          Hierbleiben
        </mbutton>
      </div>
    <?php else : ?>
      <div pinline12>
        <mbutton tag stdplus style="background:<?= CURRENT_BRUDER->color ?>;">
          <p text smolplus semibold><?= CURRENT_BRUDER->nickname; ?></p>
        </mbutton>
      </div>
    <?php endif ?>

    <?php

    /**
     * @var Collection<Project>
     */
    $Projects = Project::withCount("logs")
      ->orderBy("created_at", "DESC")
      ->get();

    ?>

    <div pinline12 fl fldircol gap=smoler>
      <div fl alic gap=smol pinline10 posrel mb12>
        <p text smoler ttup semibold color=tertiary>Projekte</p>
        <div style="min-height:3px;" flone rounded background=slight-light
          minline6></div>
      </div>
      <?php foreach ($Projects as $Project) : ?>
        <a hoverable pl10 pr6 pblock12 rounded=mid fl alic gap=smol
          page=project href="/project/<?= $Project->name ?>">
          <mi stdplus color=tertiary>deployed_code</mi>
          <p><?= $Project->name; ?></p>
        </a>
      <?php endforeach; ?>

      <?php if (authorized()) : ?>
        <mbutton request-get="project:new" stdplus mt12 has-icon=left background=tertiary color=tertiary-text rounded=mid fl alic gap=smol>
          <mi>add</mi>
          <p style="font-size:16px;"><semi-strong>Neue Projenkt</semi-strong></p>
          </a>
        <?php endif; ?>
    </div>


    <div style="min-height:3px;" flone rounded background=slight-light
      minline24></div>

    <div pinline12>
      <a href="https://github.com/brudermusscode" extern target="_blank">
        <mbutton stdplus has-icon=left background=slight-light>
          <img style="height:24px;" src="/assets/images/github-white.svg" />
          <p text style="font-size:14px;">@brudermusscode</p>
        </mbutton>
      </a>
    </div>
  </div>

  <!--- Bottom --->
  <div style="bottom:12px;" posabs pb24 pinline12 fl alic gap=smoler>
    <?php if (authorized()) : ?>
      <a href="/log/new">
        <mbutton stdplus image-as-background icon-only>
          <picture>
            <img src="/assets/images/upload-compressed.png" />
          </picture>
        </mbutton>
      </a>

      <div style="min-width:3px;height:38px;" minline6 rounded background=slight-light></div>
    <?php endif; ?>

    <?php if (!LOGGED) : ?>
      <a href="/get-back">
        <mbutton stdplus z image-as-background icon-only has-tooltip=right>
          <picture>
            <img src="/assets/images/login-compressed.png" />
          </picture>
        </mbutton>
      </a>
    <?php else : ?>
      <mbutton stdplus request="session:delete" shadow-submit full-reload responder=simple
        background=red icon-only has-tooltip="right">
        <mi>folded_hands</mi>
        <div ttooltip>Ausloggääähn</div>
      </mbutton>
    <?php endif; ?>
  </div>
</sidebar>