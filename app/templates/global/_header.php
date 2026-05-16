<?php

use Illuminate\Support\Collection;
use Bruder\Model\Project;

?>

<header fl alic jucsb scroll-manipulated>
  <div fl alic gap z>
    <a fl alic gap=smol href="/">
      <logo>
        <picture>
          <img src="/logo.svg" />
        </picture>
      </logo>
      <p text smol ttup><strong>DEV</strong>Log</p>
    </a>

    <div style="height:24px;width:3px;" rounded background=slight-light></div>

    <?php

    /**
     * @var Collection<Project>
     */
    $Projects = Project::orderBy("created_at", "DESC")->get();

    ?>

    <option-select>
      <mi stdplus color=tertiary>deployed_code</mi>
      <current-option>
        <?php if (isset($Project)) : ?>
          <?= $Project->name ?>
        <?php else : ?>
          Projekt wählen
        <?php endif ?>
      </current-option>
      <mi midler>arrow_drop_down</mi>
      <options data-action="project:get">
        <p pinline20 pt8 pb6 text smoler ttup semibold color=tertiary>Meine Projekte</p>
        <?php foreach ($Projects as $Project) : ?>
          <option data-id=<?= $Project->id ?>
            <?= CURRENT_PAGE === "project" && isset($_GET["id"]) && $_GET["id"] === $Project->id
              ? "active"
              : "" ?>>
            <p text><?= $Project->name ?></p>
            <mi></mi>
          </option>
        <?php endforeach ?>
      </options>
    </option-select>
  </div>

  <div fl alic gap=smol+ z>
    <a href="https://github.com/brudermusscode" extern target="_blank">
      <mbutton std icon-only background=slighter-light>
        <img src="/assets/images/github-white.svg" />
      </mbutton>
    </a>

    <div style="height:24px;width:3px;" rounded background=slight-light minline12></div>

    <mbutton request-get="user:new" stdplus background=secondary color=secondary-text has-icon=left>
      <mi>crowdsource</mi>
      Beitreten
    </mbutton>

    <?php if (authorized()) : ?>
      <a href="/log/new">
        <mbutton std background=green color=dark icon-only>
          <mi>arrow_upload_ready</mi>
        </mbutton>
      </a>
    <?php endif; ?>
  </div>
</header>