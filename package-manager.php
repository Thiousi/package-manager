<?php

require('lib/core.php');


return array(
  'title'   => 'Packages Manager',
  'options' => array(),
  'html'    => function() {
    $pm = new \Kirby\Plugins\PackageManager(panel());
    return $pm->html();
  }
);
