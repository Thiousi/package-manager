
<?php foreach($packages as $type => $items) : ?>
  <h1><?= $type ?></h1>
  <ul class="nav nav-list sidebar-list">
    <?php foreach($items as $package) : ?>
      <li><?= $package->name() ?></li>
    <?php endforeach ?>
  </ul>
<?php endforeach ?>
