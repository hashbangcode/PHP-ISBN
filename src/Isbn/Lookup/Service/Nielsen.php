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
class Nielsen extends Service
{

  protected $clientId = '';
  protected $password = '';
  
  public function __construct($clientId, $password)
  {

    $this->clientId = $clientId;
    $this->password = $password;
    
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

    /*$books = $this->cache->get('nielsen'. $isbn);
    if (!is_null($books)) {
      return unserialize($books);
    }*/

    $books = array();

    $url = 'http://ws.nielsenbookdataonline.com/BDOLRest/RESTwebServices/BDOLrequest?clientId=' . $this->clientId . '&password=' . $this->password;

    $url .= '&from=0';
    $url .= '&to=20';    
    
    $url .= '&indexType=0';
    $url .= '&format=7';
    $url .= '&resultView=2';
    
    $url .= '&field0=1';
    $url .= '$value0='. $isbn;
    
    $this->rawData = file_get_contents($url);

    if ($this->rawData === false) {
      $this->setError('-1', 'Communication error.');
      return false;
    }

    $data = json_decode($this->rawData);
    
    print_r($data);
    
    
/*
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
*/
    $books[] = $book;

    $this->cache->set('nielsen' . $isbn, serialize($books));    
    
    return $books;
  }

}
