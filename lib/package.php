<?php

namespace Kirby\Plugins\PackagesManager;

use C;
use F;
use Str;

class Package {

  public $name = null;

  public function __construct($dir, $source, $manager) {
    $this->pid     = $source . '/' . f::name($dir);
    $this->dir     = $dir;
    $this->cache   = $manager->cache;


    if($this->json = $this->json()) {
      $this->name = $this->json['name'];
    } else {
      $this->name = f::name($this->dir);
    }
  }

  public function update() {
    return new Update($this);
  }


  protected function json() {
    $file = $this->dir . DS . 'package.json';

    if(!f::exists($file)) return false;

    return str::parse(f::read($file));
  }



  // ====================================
  //  Magic getter
  // ====================================

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
