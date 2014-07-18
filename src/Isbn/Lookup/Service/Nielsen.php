<?php

namespace Isbn\Isbn\Lookup\Service;

/**
 * Class Nielsen
 *
 * Uses the Nielsen API to find information about a book.
 *
 * Notes on Nielsen.
 *
 * @package Isbn\Isbn\Lookup\Service
 */
class Nielsen extends Service {

  protected $clientId = '';
  protected $password = '';

  public function __construct($clientId, $password) {

    $this->clientId = $clientId;
    $this->password = $password;

    parent::__construct();
  }

  /**
   *
   * @param type $isbn
   * @return boolean|\Isbn\Book
   */
  public function getMetadataFromIsbn($isbn) {
    // Reset errors.
    $this->errors = array();

    $books = $this->cache->get('nielsen' . $isbn);
    if (!is_null($books)) {
      return unserialize($books);
    }

    $books = array();

    $url = 'http://ws.nielsenbookdataonline.com/BDOLRest/RESTwebServices/BDOLrequest?clientId=' . $this->clientId . '&password=' . $this->password;

    $url .= '&from=0';
    $url .= '&to=20';

    $url .= '&indexType=2'; // 2 large images, 3 medium images, 4 small images
    $url .= '&format=7'; // 7 == xml, 8 == csv
    $url .= '&resultView=1'; // 0 == short, 1 == medium, 2 == long
    // ISBN field == 1
    $url .= '&field0=1';
    $url .= '&value0=' . $isbn;
    $url .= '&logic0=0'; // 0 == value must be present

    $this->rawData = file_get_contents($url);

    if ($this->rawData === false) {
      $this->setError('-1', 'Communication error.');
      return false;
    }

    $xml = new \DOMDocument();
    $xml->loadXML($this->rawData);

    $resultCode = $xml->getElementsByTagName('resultCode');

    switch ($resultCode->item(0)->nodeValue) {
      case '00':
        // No error, continue.
        break;
      case '01':
        $this->setError($resultCode, 'SERVICE_UNAVAILABLE');
        return false;
      case '02':
        $this->setError($resultCode, 'INVALID_LOGON');
        return false;
      case '03':
        $this->setError($resultCode, 'SERVER_ERROR');
        return false;
      case '08':
        $this->setError($resultCode, 'PRODUCT_FORMAT_NOT_AS_REQUESTED');
        return false;
      case '50':
        $this->setError($resultCode, 'LIMITS_EXCEEDED');
        return false;
    }

    $imageData = $xml->getElementsByTagName('data');

    $book = new \Isbn\Book('', $isbn);
    $book->setImageLarge($imageData->item(0)->nodeValue);

    $books[] = $book;

    $this->cache->set('nielsen' . $isbn, serialize($books));

    return $books;
  }

}
