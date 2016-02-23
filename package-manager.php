<?php

require('lib/core.php');

$pm = new \Kirby\Plugins\PackageManager(panel());

return array(
  'title'   => $pm->title(),
  'options' => $pm->options(),
  'html'    => function() use($pm) {
    return $pm->html();
  }
);
