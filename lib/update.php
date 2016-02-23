<?php

namespace Kirby\Plugins\PackagesManager;

use C;
use F;
use Str;

class Update {

  public function __construct($package) {
    $this->package    = $package;
    $this->repository = $this->package->repository();
    $this->cache      = $this->package->cache;
  }

  public function isAvailable() {
    if($update = $this->getVersion()) {
      return version_compare($update, $this->package->version(), '>');
    } else {
      return null;
    }
  }


  public function getClass() {
    if(!c::get('packages.check.updates', true)) return 'unknown';

    $update = $this->isAvailable();
    if(is_null($update)) return 'unknown';


    return $update ? 'available' : 'none';
  }


  protected function getVersion() {
    if(c::get('packages.cache', true)) {

      // if is cached
      $key = $this->package->pid;
      if($this->cache->exists($key)) {
        $json = $this->cache->get($key);
        return isset($json['version']) ? $json['version'] : null;
      }
    }

    // fallback: load version from repository
    return $this->loadVersion();
  }


  protected function loadVersion() {
    if($this->hasRepository()) {
      $json  = $this->loadJSON();

      $key   = $this->package->pid;
      $value = $json ? $json : array();
      $alive = c::get('packages.cache.age', 60 * 24) + rand(0, 15);

      if(c::get('packages.cache', true)) {
          $this->cache->set($key, $value, $alive);
      }

      return isset($value['version']) ? $value['version'] : null;
    } else {
      return null;
    }
  }

  protected function loadJSON() {
    $url = $this->repository['url'];
    $url = str_replace('//github.com/', '//raw.githubusercontent.com/', $url);
    $url = rtrim($url, '/') . '/master/package.json';

    return str::parse(f::read($url));
  }

  public function hasRepository() {
    return !is_null($this->repository) and str::contains($this->repository['url'], '//github.com/');
  }

}
