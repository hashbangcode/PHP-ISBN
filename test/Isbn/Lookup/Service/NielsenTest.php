<?php

use Isbn\Isbn\Lookup\Service\Nielsen;

require_once('ServiceTestCase.php');

class NielsenTest extends ServiceTestCase {

  protected $clientId;
  protected $password;

  protected function setUp() {
    $this->clientId = $GLOBALS['nielsenClientId'];
    $this->password = $GLOBALS['nielsenPassword'];
  }

  public function testCreation() {
    $nielsen = new Nielsen($this->clientId, $this->password);
    $this->assertInstanceOf('Isbn\Isbn\Lookup\Service\Nielsen', $nielsen);
  }

  public function testGetaDataResults() {

    $isbn = '9781405268424';
    $nielsen = new Nielsen($this->clientId, $this->password);
    
    $books = $nielsen->getMetadataFromIsbn($isbn);

    $this->assertTrue(is_array($books));
    $this->assertInstanceOf('Isbn\Book', $books[0]);
    $this->assertEquals($isbn, $books[0]->getIsbn());
  }
}