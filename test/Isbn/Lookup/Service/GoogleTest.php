<?php

use Isbn\Isbn\Lookup\Service\Google;

class GoogleTest extends PHPUnit_Framework_TestCase {

  protected function setUp() {
  }

  public function testCreation() {
    $google = new Google();
    $this->assertInstanceOf('Isbn\Isbn\Lookup\Service\Google', $google);
  }

  public function testGetaDataResults() {
    $isbn = '9781405268424';
    $google = new Google();
    $books = $google->getMetadataFromIsbn($isbn);
    $this->assertTrue(is_array($books));
    $this->assertInstanceOf('Isbn\Book', $books[0]);
  }
}