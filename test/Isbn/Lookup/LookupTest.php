<?php

use Isbn\Isbn\Lookup;

class LookupTest extends PHPUnit_Framework_TestCase {

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
  
  public function testAmazonCreation() {

    $service = new Lookup\Service\Amazon($this->amazonAccessKeyID, $this->amazonSecretKey, $this->amazonAssociateTag);
    $this->assertInstanceOf('Isbn\Isbn\Lookup\Service\ServiceInterface', $service);

    $lookup = new Lookup\Lookup($service);

    $this->assertInstanceOf('Isbn\Isbn\Lookup\Lookup', $lookup);
  }
  
  public function testAmazonBookLookup($isbn) {    
    $service = new Lookup\Service\Amazon($this->amazonAccessKeyID, $this->amazonSecretKey, $this->amazonAssociateTag);
    $lookup = new Lookup\Lookup($service);

    $book = $lookup->getBook($isbn);
    $this->assertInstanceOf('Isbn\Book', $book);
  }
  
  /**
   * @dataProvider isbnNumbersProvider
   */  
  public function testGoogleBookLookup($isbn) {    
    $service = new Lookup\Service\Google();
    $lookup = new Lookup\Lookup($service);

    $book = $lookup->getBook($isbn);
    $this->assertInstanceOf('Isbn\Book', $book);
  }  
  
  /**
   * @dataProvider isbnNumbersProvider
   */
  public function isbnNumbersProvider() {
    return array(
      array('9780552167758'),
      array('9781743007419'),
      array('9780752488110'),
      array('9781848317260'),
      array('9781781855898'),
      array('9780007424832'),
      array('9781444000177'),
      array('9780723292098'),
      array('9780723288589'),
      array('9780857534057'),
      array('9780099580867'),
      array('9780670919635'),
      array('9781607069461'),
      array('9780440870692'),
      array('9781405235242'),
      array('9781405235174'),
      array('9781405235709'),
      array('9781405235266'),
      array('9781405235648'),
      array('9781405235259'),
      array('9780141340067'),
      array('9781405235617'),
      array('9781471129483'),
      array('9781405918237'),
      array('9780091947309'),
      array('9780857831026'),
      array('9781408836453'),
      array('9780749958763'),
      array('9781408842454'),
      array('9780224087889'),
      array('9780857053091'),
      array('9781447256779'),
      array('9780718177058'),
      array('9781444786323'),
      array('9780099585152'),
      array('9780755378494')
    );
  }  
}
