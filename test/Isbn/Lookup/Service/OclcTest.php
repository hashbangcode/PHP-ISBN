<?php

use Isbn\Isbn\Lookup\Service\Oclc;

require_once('ServiceTestCase.php');

class OclcTest extends ServiceTestCase {

  protected function setUp() {
  }

  public function testCreation() {
    $oclc = new Oclc();
    $this->assertInstanceOf('Isbn\Isbn\Lookup\Service\Oclc', $oclc);
  }

  public function testGetaDataResults() {
    $isbn = '9781405268424';
    $oclc = new Oclc();
    $books = $oclc->getMetadataFromIsbn($isbn);
    
    $this->assertTrue(is_array($books));
    $this->assertInstanceOf('Isbn\Book', $books[0]);
    $this->assertEquals($isbn, $books[0]->getIsbn());    
  }
}