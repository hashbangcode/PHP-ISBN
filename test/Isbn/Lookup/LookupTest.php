<?php

use Isbn\Isbn\Lookup;

class LookupTest extends PHPUnit_Framework_TestCase {

  protected $amazonAccessKeyID;
  protected $amazonSecretKey;
  protected $amazonAssociateTag;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp() {
    // Read config variables.
    $this->amazonAccessKeyID = $GLOBALS['amazonAccessKeyID'];
    $this->amazonSecretKey = $GLOBALS['amazonSecretKey'];
    $this->amazonAssociateTag = $GLOBALS['amazonAssociateTag'];
  }  
  
  public function testCreation() {

    $service = new Lookup\Service\Amazon($this->amazonAccessKeyID, $this->amazonSecretKey, $this->amazonAssociateTag);
    $this->assertInstanceOf('Isbn\Isbn\Lookup\Service\ServiceInterface', $service);

    $lookup = new Lookup\Lookup($service);

    $this->assertInstanceOf('Isbn\Isbn\Lookup\Lookup', $lookup);
  }
  
  public function testBookLookup() {
    $service = new Lookup\Service\Amazon($this->amazonAccessKeyID, $this->amazonSecretKey, $this->amazonAssociateTag);
    $lookup = new Lookup\Lookup($service);

    $book = $lookup->getBook('9781405251426');
    $this->assertInstanceOf('Isbn\Book', $book);
  }
}
