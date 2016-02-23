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

  public function __call($method, $arguments) {
    if(isset($this->{$method})) {
      return $this->{$method};
    } else if(isset($this->json[$method])) {
      return $this->json[$method];
    } else {
      return null;
    }
  }

  public function isUpdateAvailable() {
    if($repo = $this->repository()) {
      $url  = rtrim(str_replace('//github.com/', '//raw.githubusercontent.com/', $repo['url']), '/') . '/master/package.json';
      $json = str::parse(f::read($url));

      if(version_compare($json['version'], $this->version(), '>')) {
        return true;
      }
    }

    return false;
  }

  protected function readJSON() {
    $file = $this->dir . DS . 'package.json';

    if(!f::exists($file)) return false;

    return str::parse(f::read($file));
  }

}
