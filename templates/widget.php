<?php if(!empty($assets['css'])) : ?>
  <style><?= $assets['css'] ?></style>
<?php endif ?>


<?php foreach($packages as $type => $items) : ?>
  <?php if(!empty($items)) : ?>
    <h1 class="packages-heading"><?= $type ?></h1>
    <ul class="nav packages-list">
      <?php foreach($items as $package) : ?>
        <li>
          <?php if($repo = $package->repository()) : ?>
            <a href="<?= $repo['url'] ?>">
          <?php endif ?>

          <?= $package->name() ?>

          <?php if($version = $package->version()) : ?>
            <span class="package-version package-version--<?= $package->getUpdateLabel() ?>"><?= $version ?></span>
          <?php endif ?>

          <?php if($repo = $package->repository()) : ?>
            </a>
          <?php endif ?>
        </li>
      <?php endforeach ?>
    </ul>
  <?php endif ?>
<?php endforeach ?>


<?php if(!empty($assets['js'])) : ?>
  <script><?= $assets['js'] ?></script>
<?php endif ?>
