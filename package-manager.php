<?php

require('lib/core.php');

if(class_exists('\\Kirby\\Plugins\\PackageManager')) {
  $pm = new \Kirby\Plugins\PackageManager(panel());

  return array(
    'title'   => $pm->title(),
    'options' => $pm->options(),
    'html'    => function() use($pm) {
      return $pm->html();
    }
  );
}
