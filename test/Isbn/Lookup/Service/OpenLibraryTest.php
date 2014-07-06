<?php

use Isbn\Isbn\Lookup\Service\OpenLibrary;

class OpenLibraryTest extends PHPUnit_Framework_TestCase {

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