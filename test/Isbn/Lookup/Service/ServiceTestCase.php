<?php

abstract class ServiceTestCase extends PHPUnit_Framework_TestCase {

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
      array('9780670919635')
    );
  }
}