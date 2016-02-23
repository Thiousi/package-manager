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

  public $sources = array(
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
      'packages' => $this->sources(),
      'assets'   => $this->assets(),
    ));
  }

  protected function sources() {
    $sources = array();
    foreach($this->sources as $source) {
      $sources[$source] = $this->packages(str::lower($source));
    }
    return $sources;
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
