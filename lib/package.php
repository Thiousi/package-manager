<?php

namespace Kirby\Plugins\PackageManager;

use C;
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


  // ====================================
  //  Version checks
  // ====================================

  public function isUpdateAvailable() {
    return ($remote = $this->getRemoteVersion()) ? version_compare($remote, $this->version(), '>') : null;
  }


  public function getUpdateLabel() {
    if(!c::get('packages.check.updates', true)) return 'unknown';

    $update = $this->isUpdateAvailable();
    if(is_null($update)) return 'unknown';


    return $update ? 'available' : 'none';
  }

  protected function getRemoteVersion() {
    if($repo = $this->repository()) {
      if(str::contains($repo['url'], '//github.com/')) {
        $url  = str_replace('//github.com/', '//raw.githubusercontent.com/', $repo['url']);
        $url  = rtrim($url, '/') . '/master/package.json';
        $json = str::parse(f::read($url));

        return isset($json['version']) ? $json['version'] : null;
      }
    }

    return null;
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
