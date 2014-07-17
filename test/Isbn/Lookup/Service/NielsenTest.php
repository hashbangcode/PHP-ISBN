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
    $this->assertInstanceOf('Isbn\Isbn\Lookup\Service\Nielsen', $Nielsen);
  }

  public function testGetaDataResults() {

    $isbn = '0312932081';
    $nielsen = new Nielsen($this->clientId, $this->password);
    $books = $nielsen->getMetadataFromIsbn($isbn);
    $this->assertTrue(is_array($books));
    $this->assertInstanceOf('Isbn\Book', $books[0]);
  }
}