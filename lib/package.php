<?php

namespace Kirby\Plugins\PackageManager;

use F;
use Str;

class Package {

  public $name = null;

  public function __construct($dir) {
    $this->dir  = $dir;

    if($this->json = $this->readJSON()) {
      $this->name = $this->json['name'];
    } else {
      $this->name = f::name($this->dir);
    }
  }

  protected function readJSON() {
    $file = $this->dir . DS . 'package.json';

    if(!f::exists($file)) return false;


    return str::parse(f::read($file));
  }

  public function __call($method, $arguments) {
    if(isset($this->{$method})) {
      return $this->{$method};
    } else if(isset($this->json[$method])) {
      return $this->json[$method];
    } else {
      return null;
    }
  }

}
