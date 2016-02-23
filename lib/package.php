<?php

namespace Kirby\Plugins\PackageManager;

use C;
use F;
use Str;

class Package {

  public $name = null;

  public function __construct($dir, $manager) {
    $this->dir     = $dir;
    $this->manager = $manager;
    $this->cache   = $this->manager->cache . DS . f::name($this->dir) . '.json';

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
    return ($remote = $this->getCurrentVersion()) ? version_compare($remote, $this->version(), '>') : null;
  }


  public function getUpdateLabel() {
    if(!c::get('packages.check.updates', true)) return 'unknown';

    $update = $this->isUpdateAvailable();
    if(is_null($update)) return 'unknown';


    return $update ? 'available' : 'none';
  }

  protected function getCurrentVersion() {
    if(c::get('packages.cache', true)) {
      $this->cacheExpires();
      if($this->isCached()) {
        $json = $this->getCache();
        return isset($json['version']) ? $json['version'] : null;
      }
    }
    
    return $this->getRemoteVersion();
  }

  protected function getRemoteVersion() {
    if($repo = $this->repository()) {
      if(str::contains($repo['url'], '//github.com/')) {
        $json = $this->getRemoteJSON($repo);

        // fallback if remote package.json cannot be loaded
        if(!$json) $json = array();

        $this->setCache($json);
        return isset($json['version']) ? $json['version'] : null;
      }
    }

    return null;
  }

  protected function getRemoteJSON($repository) {
    $url = $repository['url'];
    $url = str_replace('//github.com/', '//raw.githubusercontent.com/', $url);
    $url = rtrim($url, '/') . '/master/package.json';
    return str::parse(f::read($url));
  }

  // ====================================
  //  Caching
  // ====================================

  protected function isCached() {
    return f::exists($this->cache);
  }

  protected function setCache($content) {
    return f::write($this->cache, json_encode($content));
  }

  protected function getCache() {
    return str::parse(f::read($this->cache));
  }

  protected function cacheExpires() {
    $maxAge = c::get('packages.cache.age', 60 * 60 * 24);
    if((f::modified($this->cache) + $maxAge) < time()) {
      return f::remove($this->cache);
    }
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
