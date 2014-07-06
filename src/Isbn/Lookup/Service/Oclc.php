<?php

namespace Isbn\Isbn\Lookup\Service;

/**
 * Class Oclc
 *
 * Uses the worldcat API lookup service to find information about a book.
 *
 * @package Isbn\Isbn\Lookup\Service
 */
class Oclc extends Service {


  public function __construct() {

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

    $books = array();

    $url = 'http://xisbn.worldcat.org/webservices/xid/isbn/' . $isbn . '?method=getMetadata&format=xml&fl=*';

    $this->rawData = file_get_contents($url);

    if ($this->rawData === false) {
      $this->setError('-1', 'Communication error.');
      return false;
    }

    $xml = new \DOMDocument();
    $xml->loadXML($this->rawData);

    $rsp = $xml->getElementsByTagName('rsp');

    //var_dump($metadata);

    foreach ($rsp as $item) {

      if ($item->getAttribute('stat') != 'ok') {
        return false;
      }

      $isbn_results = $item->getElementsByTagName('isbn');

      foreach ($isbn_results as $isbn_result) {
      $book = new \Isbn\Book($isbn_result->getAttribute('title'), $isbn);
      $book->setPublisher($isbn_result->getAttribute('publisher'));
      $book->setPublicationDate($isbn_result->getAttribute('year'));
      $book->setAuthor($isbn_result->getAttribute('author'));

      $books[] = $book;
      }
    }

    return $books;
  }

}
