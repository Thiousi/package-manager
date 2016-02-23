<?php

namespace Kirby\Plugins;

require('package.php');

use C;
use Dir;
use F;
use Tpl;

class PackageManager {

  public $options = array();

  public function __construct($panel) {
    $this->panel = $panel;
    $this->kirby = $this->panel->kirby();
    $this->root  = __DIR__ . DS . '..';
  }

  public function html() {
    return tpl::load($this->root . DS . 'templates' . DS . 'widget.php', array(
      'packages' => array(
        'Plugins' => $this->packages('plugins'),
        'Fields'  => $this->packages('fields'),
        'Widgets' => $this->packages('widgets')
      ),
      'assets'  => $this->assets(),
    ));
  }

  protected function packages($source) {
    $packages = array();
    $root     = $this->kirby->roots()->{$source}();

    foreach(dir::read($root) as $package) {
      if(f::extension($package) != 'php' and f::extension($package) != '') continue;

      array_push($packages, new PackageManager\Package($root . DS . $package));
    }

    return $packages;
  }

  protected function assets() {
    $root = $this->root . DS . 'assets';
    $css  = tpl::load($root . DS . 'css' . DS . 'widget.css');
    $js   = tpl::load($root . DS . 'js'  . DS . 'widget.js');

    return array(
      'css' => $css,
      'js'  => $js
    );
  }
}
