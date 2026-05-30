<?php

use Bruder\Model\CodingSession;
use Bruder\Model\Log;
use Illuminate\Support\Collection;
use Bruder\Model\Project;
use Bruder\Model\User;

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
      <div style="line-height:.9;" fl fldircol alic z>
        <p text stdplus ttup style="text-shadow:0 0 1px rgba(0,0,0,.82);">
          <strong>2</strong>oder<strong>3</strong>
        </p>
        <p text smoler ttup>development</p>
      </div>
    </div>

    <?php if (!LOGGED) : ?>
      <div pinline12>
        <mbutton request-get="<?= User::link("new", true) ?>" stdplus background=secondary color=secondary-text has-icon=left>
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
      <div fl alic pinline10 posrel mb12>
        <p text smoler ttup semibold color=tertiary>Projekte</p>
        <div style="min-height:3px;" flone rounded background=slight-light ml12></div>
      </div>

      <?php

      # + Include projects banner.
      foreach ($Projects as $Project) : ?>
        <a hoverable pl10 pr6 pblock6 rounded=mid fl alic gap=smol
          page=project href="<?= $Project->link() ?>">
          <mi stdplus color=tertiary>deployed_code</mi>
          <div>
            <p text std semibold><?= $Project->name; ?></p>
          </div>
        </a>
      <?php endforeach; ?>

      <?php

      # ! Only authorized.
      if (authorized()) : ?>
        <mbutton request-get="<?= Project::link("new", true) ?>" stdplus mt12 has-icon=left background=tertiary color=tertiary-text rounded=mid>
          <mi>add</mi>
          <p style="font-size:16px;"><semi-strong>Neue Projenkt</semi-strong></p>
        </mbutton>
      <?php endif; ?>


    </div>


    <div style="min-height:3px;" flone rounded background=slight-light minline24></div>

    <div pinline12 fl fldircol gap=smoler>
      <a href="https://github.com/brudermusscode" extern target="_blank">
        <mbutton stdplus has-icon=left background=slight-light>
          <img style="height:24px;" src="/assets/images/github-white.svg" />
          <p text style="font-size:14px;">@brudermusscode</p>
        </mbutton>
      </a>

      <?php

      # ! Only authorized.
      if (authorized()) : ?>
        <div mt24 fl fldircol gap=smoler>
          <div fl alic mb6 pinline10>
            <p text smoler ttup slight>Erstellen</p>
            <div style="min-height:3px;" flone rounded background=slight-light ml12></div>
          </div>

          <a href="<?= CodingSession::link("new") ?>">
            <mbutton stdplus has-icon=left background=primary>
              <mi>terminal_2</mi>
              <span style="font-size:16px;">Coding Session</span>
            </mbutton>
          </a>

          <a href="<?= Log::link("new") ?>">
            <mbutton stdplus has-icon=left background=green color=dark>
              <mi>arrow_upload_ready</mi>
              <span style="font-size:16px;">Neuer Devlog</span>
            </mbutton>
          </a>
        </div>
      <?php endif; ?>

    </div>
  </div>

  <!--- Bottom --->
  <div style="bottom:12px;" posabs pb24 pinline12 fl alic gap=smoler>
    <?php if (!LOGGED) : ?>
      <a href="/get-back">
        <mbutton stdplus z icon-only has-tooltip=right background=light color=dark>
          <mi>login</mi>
        </mbutton>
      </a>
    <?php else : ?>
      <mbutton stdplus request="session:delete" shadow-submit full-reload responder=simple
        image-as-background icon-only has-tooltip="right">
        <picture>
          <img src="/assets/images/logout-compressed.png" />
        </picture>
        <div ttooltip>Amen!</div>
      </mbutton>
    <?php endif; ?>
  </div>
</sidebar>