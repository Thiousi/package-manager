<?php

namespace Kirby\Plugins;

require('package.php');

use Dir;
use F;
use Tpl;

class PackageManager {

  public $title   = 'Bundle Manager';
  public $options = array();

  public function __construct($panel) {
    $this->panel = $panel;
    $this->kirby = $this->panel->kirby();
    $this->root  = __DIR__ . DS . '..';
  }

  public function title() {
    return $this->title;
  }

  public function options() {
    return $this->options;
  }

  public function html() {
    return tpl::load($this->root . DS . 'templates' . DS . 'widget.php', array(
      'packages' => array(
        'Plugins' => $this->packages('plugins'),
        'Fields'  => $this->packages('fields'),
        'Widgets' => $this->packages('widgets')
      )
    ));
  }

  protected function packages($source) {
    $packages = array();
    $root     = $this->kirby->roots()->{$source}();

    foreach(dir::read($root) as $package) {
      if(f::extension($package) != 'php' and f::extension($package) != '') {
        continue;
      }

      array_push($packages, new PackageManager\Package($root . DS . $package));
    }

    return $packages;
  }
}
