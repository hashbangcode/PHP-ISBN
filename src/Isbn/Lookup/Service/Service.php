<?php
namespace Isbn\Isbn\Lookup\Service;

abstract class Service implements ServiceInterface {
  protected $errors;
  protected $rawData;

  public function __construct() {
    
  }


  /**
   * Store an error for later retrival.
   *
   * @param string $code The error code.
   * @param string $message The error message.
   */
  public function setError($code, $message) {
    $this->errors[] = array(
      'code' => $code,
      'message' => $message
    );
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
