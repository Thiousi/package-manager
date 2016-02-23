<?php

require('lib/core.php');


return array(
  'title'   => 'Packages Manager',
  'options' => array(),
  'html'    => function() {
    $pm = new \Kirby\Plugins\PackagesManager(panel());
    return $pm->html();
  }
);
