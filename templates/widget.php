<?php if(!empty($assets['css'])) : ?>
  <style><?= $assets['css'] ?></style>
<?php endif ?>


<?php foreach($packages as $type => $items) : ?>
  <h1><?= $type ?></h1>
  <ul class="">
    <?php foreach($items as $package) : ?>
      <li>
        <?php if($repo = $package->repository()) : ?>
          <a href="<?= $repo['url'] ?>">
        <?php endif ?>

        <?= $package->name() ?>

        <?php if($version = $package->version()) : ?>
          <em class="package-version update-<?= $package->updateStatus() ?>"><?= $version ?></em>
        <?php endif ?>

        <?php if($repo = $package->repository()) : ?>
          </a>
        <?php endif ?>
      </li>
    <?php endforeach ?>
  </ul>
<?php endforeach ?>


<?php if(!empty($assets['js'])) : ?>
  <script><?= $assets['js'] ?></script>
<?php endif ?>
