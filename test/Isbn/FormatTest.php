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

  /**
   * @dataProvider isbnNomaliseData
   */
  public function testNormaliseIsbn($string, $isbn) {
      $format = new Format();
      $this->assertEquals($isbn, $format->normalise($string));
  }

  public function isbnNomaliseData() {
    return array(
      array(' 9780552167758 ', '9780552167758'),
      array(' 9a7a8a0a5a5a2a1a6a7a7a5a8 ', '9780552167758'),
      array('String 9781743007419 String', '9781743007419'),
      array('ISBN: 9780857633026', '9780857633026'),
      array('x9x7x8x0x8x5x7x6x3x3x0x2x6x', '9780857633026'),
      array('147112584X', '147112584X'),
      array('1x4x7x1x12584X', '147112584X'),
      array('ISBN:1x4x7x1x12584X', '147112584X'),
      array('<isbn>9780007594139 </isbn>', '9780007594139') // This string has a non-breaking whitespace character as well.
    );
  }
}
