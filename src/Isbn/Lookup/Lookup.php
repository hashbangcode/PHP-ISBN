<?php
namespace Isbn\Isbn\Lookup;

class Lookup {
  
  /**
   *
   * @var \Isbn\Isbn\Lookup\Service\ServiceInterface
   */
  protected $service;
  
  public function __construct(\Isbn\Isbn\Lookup\Service\ServiceInterface $service) {
    $this->service = $service;
  }
  
  /**
   * 
   * @param type $isbn
   * @return boolean
   */
  public function getBook($isbn) {
    $books = $this->service->getMetadataFromIsbn($isbn);
    
    if ($books === false) {
      return false;
    }

    return array_pop($books);
  }
}
