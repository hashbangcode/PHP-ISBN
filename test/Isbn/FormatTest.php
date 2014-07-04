<?php

use Isbn\Isbn\Format;

class FormatTest extends PHPUnit_Framework_TestCase {

  public function testCreation() {

    $format = new Format();
    $this->assertInstanceOf('Isbn\Isbn\Format', $format);
  }

  /**
   * @dataProvider isbnNumbersHyphens
   */
  public function testFormatWithHyphens($isbn, $formatedIsbn) {
    $format = new Format();
    $this->assertEquals($formatedIsbn, $format->format($isbn));
  }
  
  /**
   * @dataProvider isbnNumbersHyphens
   */
  public function testFormatWithHyphensConstant($isbn, $formattedIsbn) {
    $format = new Format();
    $this->assertEquals($formattedIsbn, $format->format($isbn, Format::ISBN_FORMAT_HYPHENS));
  }

  public function isbnNumbersHyphens() {
    return array(
      array('9780552167758', '978-0-552-16775-8'),
      array('9781743007419', '978-1-74300-741-9'),
      array('9780857633026', '978-0-85763-302-6'),
      array('-9-7-8-0-8-5-7-6-3-3-0-2-6-', '978-0-85763-302-6'),
      array('- 9- 7- 8- 0 - 8 -5 -7-6-3-3  - 0- 2-   6-', '978-0-85763-302-6'),      
    );
  }

  /**
   * @dataProvider isbnNumbersSpaces
   */
  public function testFormatWithSpaces($isbn, $formattedIsbn) {
    $format = new Format();
    $this->assertEquals($formattedIsbn, $format->format($isbn, Format::ISBN_FORMAT_SPACES));
  }  
  
  public function isbnNumbersSpaces() {
    return array(
      array('9780552167758', '978 0 552 16775 8'),
      array('9781743007419', '978 1 74300 741 9'),
      array('9780857633026', '978 0 85763 302 6'),
      array('-9-7-8-0-8-5-7-6-3-3-0-2-6-', '978 0 85763 302 6'),
      array('- 9- 7- 8- 0 - 8 -5 -7-6-3-3  - 0- 2-   6-', '978 0 85763 302 6'),      
    );
  }
}
