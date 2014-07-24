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
  
  public function testGetaDataResultsCacheDirChange() {

    $isbn = '9780552167758';
    
    // Set the new cache directory location
    $settings = \Isbn\Settings::getInstance();
    $settings->setCacheDirectory('testcache');
        
    // Fill the cache with something (run a service)
    $google = new Google();
    $books = $google->getMetadataFromIsbn($isbn);
    
    // Check that the data we have from our service call is good.
    $this->assertTrue(is_array($books));
    $this->assertInstanceOf('Isbn\Book', $books[0]);
    $this->assertEquals($isbn, $books[0]->getIsbn());
    
    // Assert directory exists.
    $this->assertTrue(file_exists('testcache'));
    
    // Remove the new cache location.
    unlink('testcache/google9780552167758');
    rmdir('testcache');
  }  
}