<?php

use Isbn\Isbn\Lookup\Service\Google;

require_once('ServiceTestCase.php');

class GoogleTest extends ServiceTestCase {

  
  protected function setUp() {
  }

  public function testCreation() {
    
    $google = new Google();
    $this->assertInstanceOf('Isbn\Isbn\Lookup\Service\Google', $google);
  }

  /**
   * @dataProvider isbnNumbersProvider
   */
  public function testGetaDataResults($isbn) {

    $google = new Google();
    $books = $google->getMetadataFromIsbn($isbn);
        
    $this->assertTrue(is_array($books));
    $this->assertInstanceOf('Isbn\Book', $books[0]);
    $this->assertEquals($isbn, $books[0]->getIsbn());
  }
}