<?php

use Isbn\Isbn\Lookup\Service\OpenLibrary;

require_once('ServiceTestCase.php');

class OpenLibraryTest extends ServiceTestCase {

  protected function setUp() {
  }

  public function testCreation() {
    $openLibrary = new OpenLibrary();
    $this->assertInstanceOf('Isbn\Isbn\Lookup\Service\OpenLibrary', $openLibrary);
  }

  public function testGetaDataResults() {

    $isbn = '0312932081';
    $openLibrary = new OpenLibrary();
    $books = $openLibrary->getMetadataFromIsbn($isbn);
    $this->assertTrue(is_array($books));
    $this->assertInstanceOf('Isbn\Book', $books[0]);
  }
}