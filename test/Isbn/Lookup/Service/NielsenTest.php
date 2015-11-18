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

  /**
   * @dataProvider isbnNumbersProvider
   */
  public function testGetaDataResults($isbn) {

    $nielsen = new Nielsen($this->clientId, $this->password);

    $books = $nielsen->getMetadataFromIsbn($isbn);

    if ($books == FALSE) {
        print_r($nielsen->getErrors());
    }

    $this->assertTrue(is_array($books));
    $this->assertInstanceOf('Isbn\Book', $books[0]);
    $this->assertEquals($isbn, $books[0]->getIsbn());

    // Optionally save the image data out (to the cache directory)
    $books[0]->convertBase64Image('./cache/images');
  }

    public function testGetaSingleDataResults() {

      $isbn = '9781408863114';

      $nielsen = new Nielsen($this->clientId, $this->password);

      $books = $nielsen->getMetadataFromIsbn($isbn);

      if ($books == FALSE) {
          print_r($nielsen->getErrors());
      }

      $this->assertTrue(is_array($books));
      $this->assertInstanceOf('Isbn\Book', $books[0]);
      $this->assertEquals($isbn, $books[0]->getIsbn());

      // Optionally save the image data out (to the cache directory)
      $books[0]->convertBase64Image('./cache/images');
    }
}
