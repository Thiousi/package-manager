<?php

namespace Kirby\Plugins;

require('update.php');
require('package.php');

use C;
use Cache;
use Dir;
use F;
use Str;
use Tpl;


class PackagesManager {

  public $types = array(
    'Plugins',
    'Fields',
    'Tags',
    'Widgets'
  );

  public function __construct($panel) {
    $this->panel  = $panel;
    $this->kirby  = $this->panel->kirby();
    $this->root   = __DIR__ . DS . '..';
    $this->cache  = cache::setup('file', array(
      'root' => $this->root . DS . 'cache')
    );
  }

  public function html() {
    return tpl::load($this->root . DS . 'templates' . DS . 'widget.php', array(
      'packages' => $this->types(),
      'assets'   => $this->assets(),
    ));
  }

  protected function types() {
    $packages = array();
    foreach($this->types as $type) {
      $packages[$type] = $this->packages(str::lower($type));
    }
    return $packages;
  }

  protected function packages($source) {
    $packages = array();
    $root     = $this->kirby->roots()->{$source}();

    foreach(dir::read($root) as $package) {
      if(f::extension($package) != 'php' and f::extension($package) != '') continue;

      array_push($packages, new PackagesManager\Package($root . DS . $package, $source, $this));
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
