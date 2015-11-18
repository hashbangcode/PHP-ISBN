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

  protected $apiKey = false;

  public function __construct() {

    parent::__construct();
  }

  /**
   * Sets the API key for use in the Google API lookup. When the API key is used
   * the data returned will include user-specific information, such as
   * purchased status.
   *
   * @param string $apiKey The API key to be used.
   */
  public function setApiKey($apiKey) {
    $this->apiKey = $apiKey;
  }

  /**
   *
   * @param type $isbn
   * @return boolean|\Isbn\Book
   */
  public function getMetadataFromIsbn($isbn) {

    // Reset errors.
    $this->errors = array();

    $books = $this->cache->get('google'. $isbn);
    if (!is_null($books)) {
      return unserialize($books);
    }

    $books = array();

    $url = 'https://www.googleapis.com/books/v1/volumes?q=isbn:' . $isbn;

    if ($this->apiKey !== false) {
      $url .= '&key=' . $this->apiKey;
    }

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

      if (isset($item->volumeInfo->subtitle)) {
          $title = $item->volumeInfo->title . ': ' . $item->volumeInfo->subtitle;
      } else {
        $title = $item->volumeInfo->title;
      }

      $book = new \Isbn\Book($title, $isbn);

      if (isset($item->volumeInfo->publisher)) {
        $book->setPublisher($item->volumeInfo->publisher);
      }

      if (isset($item->volumeInfo->publishedDate)) {
        $book->setPublicationDate($item->volumeInfo->publishedDate);
      }

      if (isset($item->volumeInfo->authors)) {
        foreach ($item->volumeInfo->authors as $author) {
          $book->setAuthor($author);
        }
      }

      if (isset($item->volumeInfo->pageCount)) {
        $book->setPages($item->volumeInfo->pageCount);
      }

      if (isset($item->volumeInfo->imageLinks)) {
        $book->setImageSmall($item->volumeInfo->imageLinks->thumbnail);
      }

      $books[] = $book;
    }

    $this->cache->set('google' . $isbn, serialize($books));

    return $books;
  }

}
