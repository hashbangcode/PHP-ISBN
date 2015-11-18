<?php
namespace Isbn\Isbn\Lookup\Service;

use Gregwar\Cache\Cache;

abstract class Service implements ServiceInterface {
  protected $errors;
  protected $rawData;

  protected $cache;

  public function __construct() {

    $settings = \Isbn\Settings::getInstance();

    $this->cache = new Cache;
    $this->cache->setCacheDirectory($settings->getCacheDirectory());
    $this->cache->setPrefixSize(0);
  }


  /**
   * Store an error for later retrival.
   *
   * @param string $code The error code.
   * @param string $message The error message.
   */
  public function setError($code, $message, $information = FALSE) {
    $error = array(
      'code' => $code,
      'message' => $message,
    );

    if ($information !== FALSE) {
      $error['informaiton'] = $information;
    }

    $this->errors[] = $error;
  }

  /**
   * Returns the errors property.
   *
   * @return array The error property.
   */
  public function getErrors() {
    return $this->errors;
  }

  /**
   * Returns the raw data from the last request.
   *
   * @return string The raw data.
   */
  public function getRawData() {
    return $this->rawData;
  }
}
