<?php

namespace Isbn;

class Settings {

  /**
   * Returns the *Singleton* instance of this class.
   *
   * @staticvar Singleton $instance The *Singleton* instances of this class.
   *
   * @return Singleton The *Singleton* instance.
   */
  public static function getInstance() {
    static $instance = null;
    if (null === $instance) {
      $instance = new static();
    }

    return $instance;
  }

  /**
   * Protected constructor to prevent creating a new instance of the
   * *Singleton* via the `new` operator from outside of this class.
   */
  protected function __construct() {
    
  }

  /**
   * Private clone method to prevent cloning of the instance of the
   * *Singleton* instance.
   *
   * @return void
   */
  private function __clone() {
    
  }

  /**
   * Private unserialize method to prevent unserializing of the *Singleton*
   * instance.
   *
   * @return void
   */
  private function __wakeup() {
    
  }

  /**
   * The current cache directory.
   *
   * @var string The 
   */
  protected $cacheDirectory = 'isbncache';

  /**
   * Gets the cache directory, used by the Cache classes to store the cache in 
   * a particular location.
   * 
   * @return string The current cache directory setting.
   */
  public function getCacheDirectory() {
    return $this->cacheDirectory;
  }

  /**
   * Sets the cache directory.
   *
   * @param string $cacheDirectory
   */
  public function setCacheDirectory($cacheDirectory) {
    $this->cacheDirectory = $cacheDirectory;
  }

}
