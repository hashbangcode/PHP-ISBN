<?php

use Isbn\Isbn\Translate;

class TranslateTest extends PHPUnit_Framework_TestCase {

  /**
   * @dataProvider isbnNumbersProvider
   */
  public function testTo13($isbn13, $isbn10) {
    $translate = new Translate();
    $this->assertEquals($isbn13, $translate->to13($isbn10));
  }

  /**
   * @dataProvider isbnNumbersProvider
   */
  public function testTo10($isbn13, $isbn10) {
    $translate = new Translate();
    $this->assertEquals($isbn10, $translate->to10($isbn13));
  }

  /**
   * @dataProvider isbnNumbersProvider
   */
  public function test10To13To10($isbn13, $isbn10) {
    $translate = new Translate();
    $this->assertEquals($isbn13, $translate->to13($translate->to10($isbn13)));
  }

  public function isbnNumbersProvider() {
    return array(
      array('9780552167758', '0552167754'),
      array('9781743007419', '1743007418'),
      array('9780857633026', '0857633023'),
      array('9780752488110', '0752488112'),
      array('9780141352763', '0141352760'),
      array('9780415831109', '0415831105'),
      array('9780007574568', '0007574568'),
      array('9780141341569', '0141341564'),
      array('9780241970256', '0241970253'),
      array('9780007461776', '0007461771'),
      array('9780755355938', '0755355938'),
      array('9780099556039', '0099556030'),
      array('9781471125843', '147112584X'),
      array('9781409145974', '1409145972'),
      array('9780349139630', '0349139636'),
      array('9780099571353', '0099571358'),
      array('9780241003503', '0241003504'),
      array('9781471125874', '1471125874'),
      array('9780718178765', '0718178769'),
      array('9781449356569', '1449356567')
    );
  }

}
