<?php

use Isbn\Isbn\Lookup\Service\Amazon;

require_once('ServiceTestCase.php');

class AmazonTest extends ServiceTestCase {

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
    $amazon = new Amazon($this->amazonAccessKeyID, $this->amazonSecretKey, $this->amazonAssociateTag);
    $this->assertInstanceOf('Isbn\Isbn\Lookup\Service\Amazon', $amazon);
  }

  public function testGetaDataResults() {
    $isbn = '9781405268424';
    $amazon = new Amazon($this->amazonAccessKeyID, $this->amazonSecretKey, $this->amazonAssociateTag);
    $books = $amazon->getMetadataFromIsbn($isbn);
    $this->assertTrue(is_array($books));
    $this->assertInstanceOf('Isbn\Book', $books[0]);
  }
  
  public function testServiceError() {
    $isbn = 'ds9f86asdofyhlasdfyo8sdy7f';
    $amazon = new Amazon($this->amazonAccessKeyID, $this->amazonSecretKey, $this->amazonAssociateTag);
    $book = $amazon->getMetadataFromIsbn($isbn);
    $this->assertFalse($book);
    $errors = $amazon->getErrors();
    $this->assertEquals('AWS.InvalidParameterValue', $errors[0]['code']);
  }
}