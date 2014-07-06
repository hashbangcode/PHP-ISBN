<?php

namespace Isbn\Isbn\Lookup\Service;

/**
 * Class OpenLibrary
 *
 * Uses the OpenLibrary API to find information about a book.
 *
 * As the OpenLibrary database is user generated it has gaps in the data, and currently doesn't find much with
 * ISBN numbers.
 *
 * @package Isbn\Isbn\Lookup\Service
 */
class OpenLibrary extends Service
{

  public function __construct()
  {

    parent::__construct();
  }

  /**
   *
   * @param type $isbn
   * @return boolean|\Isbn\Book
   */
  public function getMetadataFromIsbn($isbn)
  {

    // Reset errors.
    $this->errors = array();

    $books = array();

    $url = 'https://openlibrary.org/api/books?bibkeys=ISBN:' . $isbn . '&format=json&jscmd=data';

    $this->rawData = file_get_contents($url);

    if ($this->rawData === false) {
      $this->setError('-1', 'Communication error.');
      return false;
    }

    $data = json_decode($this->rawData);

    $parameter = 'ISBN:' . $isbn;

    if (!isset($data->$parameter)) {
      return false;
    }
    $data = $data->$parameter;

    $book = new \Isbn\Book($data->title, $isbn);
    $book->setPublisher($data->publishers[0]->name);
    $book->setPublicationDate($data->publish_date);
    $book->setAuthor($data->authors[0]->name);
    $book->setPages($data->number_of_pages);
    $book->setImageLarge($data->cover->large);
    $book->setImageMedium($data->cover->medium);
    $book->setImageSmall($data->cover->medium);

    $books[] = $book;

    return $books;
  }

}
