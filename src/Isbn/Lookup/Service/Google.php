<?php

namespace Isbn\Isbn\Lookup\Service;

/**
 * Class Google
 *
 * Uses the Google Books API to find information about a book.
 *
 * @package Isbn\Isbn\Lookup\Service
 */
class Google extends Service {

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

    $url = 'https://www.googleapis.com/books/v1/volumes?q=isbn:' . $isbn;

    $this->rawData = file_get_contents($url);

    if ($this->rawData === false) {
      $this->setError('-1', 'Communication error.');
      return false;
    }

    $data = json_decode($this->rawData);

    if (!isset($data->items)) {
      return false;
    }

    foreach ($data->items as $item) {

      $book = new \Isbn\Book($item->volumeInfo->title . ': ' . $item->volumeInfo->subtitle, $isbn);
      //$book->setPublisher();
      $book->setPublicationDate($item->volumeInfo->publishedDate);
      $book->setAuthor($item->volumeInfo->authors[0]);
      $book->setPages($item->volumeInfo->pageCount);
      //$book->setImageLarge();
      //$book->setImageMedium();
      //$book->setImageSmall();

      $books[] = $book;
    }

    return $books;
  }

}
