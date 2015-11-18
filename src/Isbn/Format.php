<?php

namespace Isbn\Isbn;

class Format {

  const ISBN_FORMAT_HYPHENS = 'ISBN_FORMAT_HYPHENS';
  const ISBN_FORMAT_SPACES = 'ISBN_FORMAT_SPACES';

  private $isbn;
  private $isbnSplit = Array();

  public function format($isbn, $format = self::ISBN_FORMAT_HYPHENS) {
    switch ($format) {
      case self::ISBN_FORMAT_HYPHENS:
        $isbn = $this->normalise($isbn);
        return $this->addHyphens($isbn);
        break;
      case self::ISBN_FORMAT_SPACES:
        $isbn = $this->normalise($isbn);
        return $this->addSpaces($isbn);
        break;
    }
  }

  public function addSpaces($isbn) {
    $this->isbn = $isbn;
    $this->isbnSplit = Array();

    if (strlen($this->isbn) == 13) {
      $this->isbnSplit[0] = substr($this->isbn, 0, 3);
    }
    $this->getRegistrationGroupElement();
    $this->getRegistrantElement();
    $this->getPublicationElement();
    $this->getCheckDigit();
    return implode(' ', $this->isbnSplit);
  }

  private function getPublicationElement() {
    $this->isbnSplit[3] = substr($this->isbn, $this->parsed(), -1);
  }

  private function getCheckDigit() {
    $this->isbnSplit[4] = substr($this->isbn, -1);
  }

  public function normalise($isbn) {
    $isbn = trim($isbn);

    $x_is_present = false;
    
    if (strtolower(substr($isbn, -1)) == 'x') {
      $x_is_present = true;
    }

    $isbn = preg_replace('/[^0-9]/', '', $isbn);

    return $isbn . (($x_is_present === true && strlen($isbn) < 13) ? 'X' : '');
  }

  public function addHyphens($isbn) {
    $this->isbn = $isbn;
    $this->isbnSplit = Array();

    if (strlen($this->isbn) == 13) {
      $this->isbnSplit[0] = substr($this->isbn, 0, 3);
    }

    $this->getRegistrationGroupElement();
    $this->getRegistrantElement();
    $this->getPublicationElement();
    $this->getCheckDigit();
    return implode('-', $this->isbnSplit);
  }

  private function range($min, $max, $chars, $p) {
    if (!$chars) {
      return false;
    }
    $val = substr($this->isbn, $this->parsed($p), $chars);
    $min = substr($min, 0, $chars);
    $max = substr($max, 0, $chars);
    if ($val >= $min AND $val <= $max) {
      $this->isbnSplit[$p] = $val;
      return true;
    } else {
      return false;
    }
  }

  private function parsed($now = null) {
    $chars = 0;
    foreach ($this->isbnSplit as $key => $split) {
      if (!isSet($now) OR $key < $now) {
        $chars = $chars + strlen($split);
      }
    }
    return $chars;
  }

  private function getRegistrationGroupElement() {
    if (!isSet($this->isbnSplit[0]) OR $this->isbnSplit[0] == '978') {
      $this->range(0000000, 5999999, 1, 1);
      $this->range(6000000, 6499999, 3, 1);
      $this->range(6500000, 6999999, 0, 1);
      $this->range(7000000, 7999999, 1, 1);
      $this->range(8000000, 9499999, 2, 1);
      $this->range(9500000, 9899999, 3, 1);
      $this->range(9900000, 9989999, 4, 1);
      $this->range(9990000, 9999999, 5, 1);
    }
    if (isSet($this->isbnSplit[0]) AND $this->isbnSplit[0] == '979') {
      $this->range(0000000, 0999999, 0, 1);
      $this->range(1000000, 1199999, 2, 1);
      $this->range(1200000, 9999999, 0, 1);
    }
    if (isSet($this->isbnSplit[1])) {
      return true;
    }

    return false;
  }

  private function getRegistrantElement() {
    if (isSet($this->isbnSplit[0])) {
      $soFar = implode('-', $this->isbnSplit);
    } else {
      $soFar = "978-" . $this->isbnSplit[1];
    }
    switch ($soFar) {
      case '978-0':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 6999999, 3, 2);
        $this->range(7000000, 8499999, 4, 2);
        $this->range(8500000, 8999999, 5, 2);
        $this->range(9000000, 9499999, 6, 2);
        $this->range(9500000, 9999999, 7, 2);
        break;
      case '978-1':
        $this->range(0000000, 0999999, 2, 2);
        $this->range(1000000, 3999999, 3, 2);
        $this->range(4000000, 5499999, 4, 2);
        $this->range(5500000, 8697999, 5, 2);
        $this->range(8698000, 9989999, 6, 2);
        $this->range(9990000, 9999999, 7, 2);
        break;
      case '978-2':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 3499999, 3, 2);
        $this->range(3500000, 3999999, 5, 2);
        $this->range(4000000, 6999999, 3, 2);
        $this->range(7000000, 8399999, 4, 2);
        $this->range(8400000, 8999999, 5, 2);
        $this->range(9000000, 9499999, 6, 2);
        $this->range(9500000, 9999999, 7, 2);
        break;
      case '978-3':
        $this->range(0000000, 0299999, 2, 2);
        $this->range(0300000, 0339999, 3, 2);
        $this->range(0340000, 0369999, 4, 2);
        $this->range(0370000, 0399999, 5, 2);
        $this->range(0400000, 1999999, 2, 2);
        $this->range(2000000, 6999999, 3, 2);
        $this->range(7000000, 8499999, 4, 2);
        $this->range(8500000, 8999999, 5, 2);
        $this->range(9000000, 9499999, 6, 2);
        $this->range(9500000, 9539999, 7, 2);
        $this->range(9540000, 9699999, 5, 2);
        $this->range(9700000, 9899999, 7, 2);
        $this->range(9900000, 9949999, 5, 2);
        $this->range(9950000, 9999999, 5, 2);
        break;
      case '978-4':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 6999999, 3, 2);
        $this->range(7000000, 8499999, 4, 2);
        $this->range(8500000, 8999999, 5, 2);
        $this->range(9000000, 9499999, 6, 2);
        $this->range(9500000, 9999999, 7, 2);
        break;
      case '978-5':
        $this->range(0000000, 0049999, 5, 2);
        $this->range(0050000, 0099999, 4, 2);
        $this->range(0100000, 1999999, 2, 2);
        $this->range(2000000, 4209999, 3, 2);
        $this->range(4210000, 4299999, 4, 2);
        $this->range(4300000, 4309999, 3, 2);
        $this->range(4310000, 4399999, 4, 2);
        $this->range(4400000, 4409999, 3, 2);
        $this->range(4410000, 4499999, 4, 2);
        $this->range(4500000, 6999999, 3, 2);
        $this->range(7000000, 8499999, 4, 2);
        $this->range(8500000, 8999999, 5, 2);
        $this->range(9000000, 9099999, 6, 2);
        $this->range(9100000, 9199999, 5, 2);
        $this->range(9200000, 9299999, 4, 2);
        $this->range(9300000, 9499999, 5, 2);
        $this->range(9500000, 9500999, 7, 2);
        $this->range(9501000, 9799999, 4, 2);
        $this->range(9800000, 9899999, 5, 2);
        $this->range(9900000, 9909999, 7, 2);
        $this->range(9910000, 9999999, 4, 2);
        break;
      case '978-600':
        $this->range(0000000, 0999999, 2, 2);
        $this->range(1000000, 4999999, 3, 2);
        $this->range(5000000, 8999999, 4, 2);
        $this->range(9000000, 9999999, 5, 2);
        break;
      case '978-601':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 6999999, 3, 2);
        $this->range(7000000, 7999999, 4, 2);
        $this->range(8000000, 8499999, 5, 2);
        $this->range(8500000, 9999999, 2, 2);
        break;
      case '978-602':
        $this->range(0000000, 1499999, 2, 2);
        $this->range(1500000, 1699999, 4, 2);
        $this->range(1700000, 1799999, 5, 2);
        $this->range(1800000, 1899999, 5, 2);
        $this->range(1900000, 1999999, 5, 2);
        $this->range(2000000, 7499999, 3, 2);
        $this->range(7500000, 7999999, 4, 2);
        $this->range(8000000, 9499999, 4, 2);
        $this->range(9500000, 9999999, 5, 2);
        break;
      case '978-603':
        $this->range(0000000, 0499999, 2, 2);
        $this->range(0500000, 4999999, 2, 2);
        $this->range(5000000, 7999999, 3, 2);
        $this->range(8000000, 8999999, 4, 2);
        $this->range(9000000, 9999999, 5, 2);
        break;
      case '978-604':
        $this->range(0000000, 4999999, 1, 2);
        $this->range(5000000, 8999999, 2, 2);
        $this->range(9000000, 9799999, 3, 2);
        $this->range(9800000, 9999999, 4, 2);
        break;
      case '978-605':
        $this->range(0000000, 0099999, 0, 2);
        $this->range(0100000, 0999999, 2, 2);
        $this->range(1000000, 3999999, 3, 2);
        $this->range(4000000, 5999999, 4, 2);
        $this->range(6000000, 8999999, 5, 2);
        $this->range(9000000, 9999999, 2, 2);
        break;
      case '978-606':
        $this->range(0000000, 0999999, 1, 2);
        $this->range(1000000, 4999999, 2, 2);
        $this->range(5000000, 7999999, 3, 2);
        $this->range(8000000, 9199999, 4, 2);
        $this->range(9200000, 9999999, 5, 2);
        break;
      case '978-607':
        $this->range(0000000, 3999999, 2, 2);
        $this->range(4000000, 7499999, 3, 2);
        $this->range(7500000, 9499999, 4, 2);
        $this->range(9500000, 9999999, 5, 2);
        break;
      case '978-608':
        $this->range(0000000, 0999999, 1, 2);
        $this->range(1000000, 1999999, 2, 2);
        $this->range(2000000, 4499999, 3, 2);
        $this->range(4500000, 6499999, 4, 2);
        $this->range(6500000, 6999999, 5, 2);
        $this->range(7000000, 9999999, 1, 2);
        break;
      case '978-609':
        $this->range(0000000, 3999999, 2, 2);
        $this->range(4000000, 7999999, 3, 2);
        $this->range(8000000, 9499999, 4, 2);
        $this->range(9500000, 9999999, 5, 2);
        break;
      case '978-611':
        $this->range(0000000, 9999999, 0, 2);
        break;
      case '978-612':
        $this->range(0000000, 2999999, 2, 2);
        $this->range(3000000, 3999999, 3, 2);
        $this->range(4000000, 4499999, 4, 2);
        $this->range(4500000, 4999999, 5, 2);
        $this->range(5000000, 9999999, 2, 2);
        break;
      case '978-613':
        $this->range(0000000, 9999999, 1, 2);
        break;
      case '978-614':
        $this->range(0000000, 3999999, 2, 2);
        $this->range(4000000, 7999999, 3, 2);
        $this->range(8000000, 9499999, 4, 2);
        $this->range(9500000, 9999999, 5, 2);
        break;
      case '978-615':
        $this->range(0000000, 0999999, 2, 2);
        $this->range(1000000, 4999999, 3, 2);
        $this->range(5000000, 7999999, 4, 2);
        $this->range(8000000, 8999999, 5, 2);
        $this->range(9000000, 9999999, 0, 2);
        break;
      case '978-616':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 6999999, 3, 2);
        $this->range(7000000, 8999999, 4, 2);
        $this->range(9000000, 9999999, 5, 2);
        break;
      case '978-617':
        $this->range(0000000, 4999999, 2, 2);
        $this->range(5000000, 6999999, 3, 2);
        $this->range(7000000, 8999999, 4, 2);
        $this->range(9000000, 9999999, 5, 2);
        break;
      case '978-618':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 4999999, 3, 2);
        $this->range(5000000, 7999999, 4, 2);
        $this->range(8000000, 9999999, 5, 2);
        break;
      case '978-619':
        $this->range(0000000, 1499999, 2, 2);
        $this->range(1500000, 6999999, 3, 2);
        $this->range(7000000, 8999999, 4, 2);
        $this->range(9000000, 9999999, 5, 2);
        break;
      case '978-620':
        $this->range(0000000, 9999999, 1, 2);
        break;
      case '978-621':
        $this->range(0000000, 2999999, 2, 2);
        $this->range(3000000, 3999999, 0, 2);
        $this->range(4000000, 5999999, 3, 2);
        $this->range(6000000, 7999999, 0, 2);
        $this->range(8000000, 8999999, 4, 2);
        $this->range(9000000, 9499999, 0, 2);
        $this->range(9500000, 9999999, 5, 2);
        break;
      case '978-7':
        $this->range(0000000, 0999999, 2, 2);
        $this->range(1000000, 4999999, 3, 2);
        $this->range(5000000, 7999999, 4, 2);
        $this->range(8000000, 8999999, 5, 2);
        $this->range(9000000, 9999999, 6, 2);
        break;
      case '978-80':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 6999999, 3, 2);
        $this->range(7000000, 8499999, 4, 2);
        $this->range(8500000, 8999999, 5, 2);
        $this->range(9000000, 9999999, 6, 2);
        break;
      case '978-81':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 6999999, 3, 2);
        $this->range(7000000, 8499999, 4, 2);
        $this->range(8500000, 8999999, 5, 2);
        $this->range(9000000, 9999999, 6, 2);
        break;
      case '978-82':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 6999999, 3, 2);
        $this->range(7000000, 8999999, 4, 2);
        $this->range(9000000, 9899999, 5, 2);
        $this->range(9900000, 9999999, 6, 2);
        break;
      case '978-83':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 5999999, 3, 2);
        $this->range(6000000, 6999999, 5, 2);
        $this->range(7000000, 8499999, 4, 2);
        $this->range(8500000, 8999999, 5, 2);
        $this->range(9000000, 9999999, 6, 2);
        break;
      case '978-84':
        $this->range(0000000, 1399999, 2, 2);
        $this->range(1400000, 1499999, 3, 2);
        $this->range(1500000, 1999999, 5, 2);
        $this->range(2000000, 6999999, 3, 2);
        $this->range(7000000, 8499999, 4, 2);
        $this->range(8500000, 8999999, 5, 2);
        $this->range(9000000, 9199999, 4, 2);
        $this->range(9200000, 9239999, 6, 2);
        $this->range(9240000, 9299999, 5, 2);
        $this->range(9300000, 9499999, 6, 2);
        $this->range(9500000, 9699999, 5, 2);
        $this->range(9700000, 9999999, 4, 2);
        break;
      case '978-85':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 5999999, 3, 2);
        $this->range(6000000, 6999999, 5, 2);
        $this->range(7000000, 8499999, 4, 2);
        $this->range(8500000, 8999999, 5, 2);
        $this->range(9000000, 9799999, 6, 2);
        $this->range(9800000, 9999999, 5, 2);
        break;
      case '978-86':
        $this->range(0000000, 2999999, 2, 2);
        $this->range(3000000, 5999999, 3, 2);
        $this->range(6000000, 7999999, 4, 2);
        $this->range(8000000, 8999999, 5, 2);
        $this->range(9000000, 9999999, 6, 2);
        break;
      case '978-87':
        $this->range(0000000, 2999999, 2, 2);
        $this->range(3000000, 3999999, 0, 2);
        $this->range(4000000, 6499999, 3, 2);
        $this->range(6500000, 6999999, 0, 2);
        $this->range(7000000, 7999999, 4, 2);
        $this->range(8000000, 8499999, 0, 2);
        $this->range(8500000, 9499999, 5, 2);
        $this->range(9500000, 9699999, 0, 2);
        $this->range(9700000, 9999999, 6, 2);
        break;
      case '978-88':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 5999999, 3, 2);
        $this->range(6000000, 8499999, 4, 2);
        $this->range(8500000, 8999999, 5, 2);
        $this->range(9000000, 9099999, 6, 2);
        $this->range(9100000, 9299999, 3, 2);
        $this->range(9300000, 9499999, 0, 2);
        $this->range(9500000, 9999999, 5, 2);
        break;
      case '978-89':
        $this->range(0000000, 2499999, 2, 2);
        $this->range(2500000, 5499999, 3, 2);
        $this->range(5500000, 8499999, 4, 2);
        $this->range(8500000, 9499999, 5, 2);
        $this->range(9500000, 9699999, 6, 2);
        $this->range(9700000, 9899999, 5, 2);
        $this->range(9900000, 9999999, 3, 2);
        break;
      case '978-90':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 4999999, 3, 2);
        $this->range(5000000, 6999999, 4, 2);
        $this->range(7000000, 7999999, 5, 2);
        $this->range(8000000, 8499999, 6, 2);
        $this->range(8500000, 8999999, 4, 2);
        $this->range(9000000, 9099999, 2, 2);
        $this->range(9100000, 9399999, 0, 2);
        $this->range(9400000, 9499999, 2, 2);
        $this->range(9500000, 9999999, 0, 2);
        break;
      case '978-91':
        $this->range(0000000, 1999999, 1, 2);
        $this->range(2000000, 4999999, 2, 2);
        $this->range(5000000, 6499999, 3, 2);
        $this->range(6500000, 6999999, 0, 2);
        $this->range(7000000, 7999999, 4, 2);
        $this->range(8000000, 8499999, 0, 2);
        $this->range(8500000, 9499999, 5, 2);
        $this->range(9500000, 9699999, 0, 2);
        $this->range(9700000, 9999999, 6, 2);
        break;
      case '978-92':
        $this->range(0000000, 5999999, 1, 2);
        $this->range(6000000, 7999999, 2, 2);
        $this->range(8000000, 8999999, 3, 2);
        $this->range(9000000, 9499999, 4, 2);
        $this->range(9500000, 9899999, 5, 2);
        $this->range(9900000, 9999999, 6, 2);
        break;
      case '978-93':
        $this->range(0000000, 0999999, 2, 2);
        $this->range(1000000, 4999999, 3, 2);
        $this->range(5000000, 7999999, 4, 2);
        $this->range(8000000, 9499999, 5, 2);
        $this->range(9500000, 9999999, 6, 2);
        break;
      case '978-94':
        $this->range(0000000, 5999999, 3, 2);
        $this->range(6000000, 8999999, 4, 2);
        $this->range(9000000, 9999999, 5, 2);
        break;
      case '978-950':
        $this->range(0000000, 4999999, 2, 2);
        $this->range(5000000, 8999999, 3, 2);
        $this->range(9000000, 9899999, 4, 2);
        $this->range(9900000, 9999999, 5, 2);
        break;
      case '978-951':
        $this->range(0000000, 1999999, 1, 2);
        $this->range(2000000, 5499999, 2, 2);
        $this->range(5500000, 8899999, 3, 2);
        $this->range(8900000, 9499999, 4, 2);
        $this->range(9500000, 9999999, 5, 2);
        break;
      case '978-952':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 4999999, 3, 2);
        $this->range(5000000, 5999999, 4, 2);
        $this->range(6000000, 6599999, 2, 2);
        $this->range(6600000, 6699999, 4, 2);
        $this->range(6700000, 6999999, 5, 2);
        $this->range(7000000, 7999999, 4, 2);
        $this->range(8000000, 9499999, 2, 2);
        $this->range(9500000, 9899999, 4, 2);
        $this->range(9900000, 9999999, 5, 2);
        break;
      case '978-953':
        $this->range(0000000, 0999999, 1, 2);
        $this->range(1000000, 1499999, 2, 2);
        $this->range(1500000, 5099999, 3, 2);
        $this->range(5100000, 5499999, 2, 2);
        $this->range(5500000, 5999999, 5, 2);
        $this->range(6000000, 9499999, 4, 2);
        $this->range(9500000, 9999999, 5, 2);
        break;
      case '978-954':
        $this->range(0000000, 2899999, 2, 2);
        $this->range(2900000, 2999999, 4, 2);
        $this->range(3000000, 7999999, 3, 2);
        $this->range(8000000, 8999999, 4, 2);
        $this->range(9000000, 9299999, 5, 2);
        $this->range(9300000, 9999999, 4, 2);
        break;
      case '978-955':
        $this->range(0000000, 1999999, 4, 2);
        $this->range(2000000, 4399999, 2, 2);
        $this->range(4400000, 4499999, 5, 2);
        $this->range(4500000, 4999999, 4, 2);
        $this->range(5000000, 5499999, 5, 2);
        $this->range(5500000, 7999999, 3, 2);
        $this->range(8000000, 9499999, 4, 2);
        $this->range(9500000, 9999999, 5, 2);
        break;
      case '978-956':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 6999999, 3, 2);
        $this->range(7000000, 9999999, 4, 2);
        break;
      case '978-957':
        $this->range(0000000, 0299999, 2, 2);
        $this->range(0300000, 0499999, 4, 2);
        $this->range(0500000, 1999999, 2, 2);
        $this->range(2000000, 2099999, 4, 2);
        $this->range(2100000, 2799999, 2, 2);
        $this->range(2800000, 3099999, 5, 2);
        $this->range(3100000, 4399999, 2, 2);
        $this->range(4400000, 8199999, 3, 2);
        $this->range(8200000, 9699999, 4, 2);
        $this->range(9700000, 9999999, 5, 2);
        break;
      case '978-958':
        $this->range(0000000, 5699999, 2, 2);
        $this->range(5700000, 5999999, 5, 2);
        $this->range(6000000, 7999999, 3, 2);
        $this->range(8000000, 9499999, 4, 2);
        $this->range(9500000, 9999999, 5, 2);
        break;
      case '978-959':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 6999999, 3, 2);
        $this->range(7000000, 8499999, 4, 2);
        $this->range(8500000, 9999999, 5, 2);
        break;
      case '978-960':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 6599999, 3, 2);
        $this->range(6600000, 6899999, 4, 2);
        $this->range(6900000, 6999999, 3, 2);
        $this->range(7000000, 8499999, 4, 2);
        $this->range(8500000, 9299999, 5, 2);
        $this->range(9300000, 9399999, 2, 2);
        $this->range(9400000, 9799999, 4, 2);
        $this->range(9800000, 9999999, 5, 2);
        break;
      case '978-961':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 5999999, 3, 2);
        $this->range(6000000, 8999999, 4, 2);
        $this->range(9000000, 9499999, 5, 2);
        $this->range(9500000, 9999999, 0, 2);
        break;
      case '978-962':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 6999999, 3, 2);
        $this->range(7000000, 8499999, 4, 2);
        $this->range(8500000, 8699999, 5, 2);
        $this->range(8700000, 8999999, 4, 2);
        $this->range(9000000, 9999999, 3, 2);
        break;
      case '978-963':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 6999999, 3, 2);
        $this->range(7000000, 8499999, 4, 2);
        $this->range(8500000, 8999999, 5, 2);
        $this->range(9000000, 9999999, 4, 2);
        break;
      case '978-964':
        $this->range(0000000, 1499999, 2, 2);
        $this->range(1500000, 2499999, 3, 2);
        $this->range(2500000, 2999999, 4, 2);
        $this->range(3000000, 5499999, 3, 2);
        $this->range(5500000, 8999999, 4, 2);
        $this->range(9000000, 9699999, 5, 2);
        $this->range(9700000, 9899999, 3, 2);
        $this->range(9900000, 9999999, 4, 2);
        break;
      case '978-965':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 5999999, 3, 2);
        $this->range(6000000, 6999999, 0, 2);
        $this->range(7000000, 7999999, 4, 2);
        $this->range(8000000, 8999999, 0, 2);
        $this->range(9000000, 9999999, 5, 2);
        break;
      case '978-966':
        $this->range(0000000, 1299999, 2, 2);
        $this->range(1300000, 1399999, 3, 2);
        $this->range(1400000, 1499999, 2, 2);
        $this->range(1500000, 1699999, 4, 2);
        $this->range(1700000, 1999999, 3, 2);
        $this->range(2000000, 2789999, 4, 2);
        $this->range(2790000, 2899999, 3, 2);
        $this->range(2900000, 2999999, 4, 2);
        $this->range(3000000, 6999999, 3, 2);
        $this->range(7000000, 8999999, 4, 2);
        $this->range(9000000, 9099999, 5, 2);
        $this->range(9100000, 9499999, 3, 2);
        $this->range(9500000, 9799999, 5, 2);
        $this->range(9800000, 9999999, 3, 2);
        break;
      case '978-967':
        $this->range(0000000, 0099999, 2, 2);
        $this->range(0100000, 0999999, 4, 2);
        $this->range(1000000, 1999999, 5, 2);
        $this->range(2000000, 2999999, 0, 2);
        $this->range(3000000, 4999999, 3, 2);
        $this->range(5000000, 5999999, 4, 2);
        $this->range(6000000, 8999999, 2, 2);
        $this->range(9000000, 9899999, 3, 2);
        $this->range(9900000, 9989999, 4, 2);
        $this->range(9990000, 9999999, 5, 2);
        break;
      case '978-968':
        $this->range(0100000, 3999999, 2, 2);
        $this->range(4000000, 4999999, 3, 2);
        $this->range(5000000, 7999999, 4, 2);
        $this->range(8000000, 8999999, 3, 2);
        $this->range(9000000, 9999999, 4, 2);
        break;
      case '978-969':
        $this->range(0000000, 1999999, 1, 2);
        $this->range(2000000, 3999999, 2, 2);
        $this->range(4000000, 7999999, 3, 2);
        $this->range(8000000, 9999999, 4, 2);
        break;
      case '978-970':
        $this->range(0100000, 5999999, 2, 2);
        $this->range(6000000, 8999999, 3, 2);
        $this->range(9000000, 9099999, 4, 2);
        $this->range(9100000, 9699999, 5, 2);
        $this->range(9700000, 9999999, 4, 2);
        break;
      case '978-971':
        $this->range(0000000, 0159999, 3, 2);
        $this->range(0160000, 0199999, 4, 2);
        $this->range(0200000, 0299999, 2, 2);
        $this->range(0300000, 0599999, 4, 2);
        $this->range(0600000, 0999999, 2, 2);
        $this->range(1000000, 4999999, 2, 2);
        $this->range(5000000, 8499999, 3, 2);
        $this->range(8500000, 9099999, 4, 2);
        $this->range(9100000, 9599999, 5, 2);
        $this->range(9600000, 9699999, 4, 2);
        $this->range(9700000, 9899999, 2, 2);
        $this->range(9900000, 9999999, 4, 2);
        break;
      case '978-972':
        $this->range(0000000, 1999999, 1, 2);
        $this->range(2000000, 5499999, 2, 2);
        $this->range(5500000, 7999999, 3, 2);
        $this->range(8000000, 9499999, 4, 2);
        $this->range(9500000, 9999999, 5, 2);
        break;
      case '978-973':
        $this->range(0000000, 0999999, 1, 2);
        $this->range(1000000, 1699999, 3, 2);
        $this->range(1700000, 1999999, 4, 2);
        $this->range(2000000, 5499999, 2, 2);
        $this->range(5500000, 7599999, 3, 2);
        $this->range(7600000, 8499999, 4, 2);
        $this->range(8500000, 8899999, 5, 2);
        $this->range(8900000, 9499999, 4, 2);
        $this->range(9500000, 9999999, 5, 2);
        break;
      case '978-974':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 6999999, 3, 2);
        $this->range(7000000, 8499999, 4, 2);
        $this->range(8500000, 8999999, 5, 2);
        $this->range(9000000, 9499999, 5, 2);
        $this->range(9500000, 9999999, 4, 2);
        break;
      case '978-975':
        $this->range(0000000, 0199999, 5, 2);
        $this->range(0200000, 2499999, 2, 2);
        $this->range(2500000, 5999999, 3, 2);
        $this->range(6000000, 9199999, 4, 2);
        $this->range(9200000, 9899999, 5, 2);
        $this->range(9900000, 9999999, 3, 2);
        break;
      case '978-976':
        $this->range(0000000, 3999999, 1, 2);
        $this->range(4000000, 5999999, 2, 2);
        $this->range(6000000, 7999999, 3, 2);
        $this->range(8000000, 9499999, 4, 2);
        $this->range(9500000, 9999999, 5, 2);
        break;
      case '978-977':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 4999999, 3, 2);
        $this->range(5000000, 6999999, 4, 2);
        $this->range(7000000, 8499999, 3, 2);
        $this->range(8500000, 8999999, 5, 2);
        $this->range(9000000, 9999999, 2, 2);
        break;
      case '978-978':
        $this->range(0000000, 1999999, 3, 2);
        $this->range(2000000, 2999999, 4, 2);
        $this->range(3000000, 7999999, 5, 2);
        $this->range(8000000, 8999999, 4, 2);
        $this->range(9000000, 9999999, 3, 2);
        break;
      case '978-979':
        $this->range(0000000, 0999999, 3, 2);
        $this->range(1000000, 1499999, 4, 2);
        $this->range(1500000, 1999999, 5, 2);
        $this->range(2000000, 2999999, 2, 2);
        $this->range(3000000, 3999999, 4, 2);
        $this->range(4000000, 7999999, 3, 2);
        $this->range(8000000, 9499999, 4, 2);
        $this->range(9500000, 9999999, 5, 2);
        break;
      case '978-980':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 5999999, 3, 2);
        $this->range(6000000, 9999999, 4, 2);
        break;
      case '978-981':
        $this->range(0000000, 1199999, 2, 2);
        $this->range(1200000, 1999999, 0, 2);
        $this->range(2000000, 2899999, 3, 2);
        $this->range(2900000, 2999999, 3, 2);
        $this->range(3000000, 3099999, 4, 2);
        $this->range(3100000, 3999999, 3, 2);
        $this->range(4000000, 9999999, 4, 2);
        break;
      case '978-982':
        $this->range(0000000, 0999999, 2, 2);
        $this->range(1000000, 6999999, 3, 2);
        $this->range(7000000, 8999999, 2, 2);
        $this->range(9000000, 9799999, 4, 2);
        $this->range(9800000, 9999999, 5, 2);
        break;
      case '978-983':
        $this->range(0000000, 0199999, 2, 2);
        $this->range(0200000, 1999999, 3, 2);
        $this->range(2000000, 3999999, 4, 2);
        $this->range(4000000, 4499999, 5, 2);
        $this->range(4500000, 4999999, 2, 2);
        $this->range(5000000, 7999999, 2, 2);
        $this->range(8000000, 8999999, 3, 2);
        $this->range(9000000, 9899999, 4, 2);
        $this->range(9900000, 9999999, 5, 2);
        break;
      case '978-984':
        $this->range(0000000, 3999999, 2, 2);
        $this->range(4000000, 7999999, 3, 2);
        $this->range(8000000, 8999999, 4, 2);
        $this->range(9000000, 9999999, 5, 2);
        break;
      case '978-985':
        $this->range(0000000, 3999999, 2, 2);
        $this->range(4000000, 5999999, 3, 2);
        $this->range(6000000, 8999999, 4, 2);
        $this->range(9000000, 9999999, 5, 2);
        break;
      case '978-986':
        $this->range(0000000, 1199999, 2, 2);
        $this->range(1200000, 5599999, 3, 2);
        $this->range(5600000, 7999999, 4, 2);
        $this->range(8000000, 9999999, 5, 2);
        break;
      case '978-987':
        $this->range(0000000, 0999999, 2, 2);
        $this->range(1000000, 1999999, 4, 2);
        $this->range(2000000, 2999999, 5, 2);
        $this->range(3000000, 4999999, 2, 2);
        $this->range(5000000, 8999999, 3, 2);
        $this->range(9000000, 9499999, 4, 2);
        $this->range(9500000, 9999999, 5, 2);
        break;
      case '978-988':
        $this->range(0000000, 1199999, 2, 2);
        $this->range(1200000, 1499999, 5, 2);
        $this->range(1500000, 1699999, 5, 2);
        $this->range(1700000, 1999999, 5, 2);
        $this->range(2000000, 7999999, 3, 2);
        $this->range(8000000, 9699999, 4, 2);
        $this->range(9700000, 9999999, 5, 2);
        break;
      case '978-989':
        $this->range(0000000, 1999999, 1, 2);
        $this->range(2000000, 5499999, 2, 2);
        $this->range(5500000, 7999999, 3, 2);
        $this->range(8000000, 9499999, 4, 2);
        $this->range(9500000, 9999999, 5, 2);
        break;
      case '978-9927':
        $this->range(0000000, 0999999, 2, 2);
        $this->range(1000000, 3999999, 3, 2);
        $this->range(4000000, 4999999, 4, 2);
        $this->range(5000000, 9999999, 0, 2);
        break;
      case '978-9928':
        $this->range(0000000, 0999999, 2, 2);
        $this->range(1000000, 3999999, 3, 2);
        $this->range(4000000, 4999999, 4, 2);
        $this->range(5000000, 9999999, 0, 2);
        break;
      case '978-9929':
        $this->range(0000000, 3999999, 1, 2);
        $this->range(4000000, 5499999, 2, 2);
        $this->range(5500000, 7999999, 3, 2);
        $this->range(8000000, 9999999, 4, 2);
        break;
      case '978-9930':
        $this->range(0000000, 4999999, 2, 2);
        $this->range(5000000, 9399999, 3, 2);
        $this->range(9400000, 9999999, 4, 2);
        break;
      case '978-9931':
        $this->range(0000000, 2999999, 2, 2);
        $this->range(3000000, 8999999, 3, 2);
        $this->range(9000000, 9999999, 4, 2);
        break;
      case '978-9932':
        $this->range(0000000, 3999999, 2, 2);
        $this->range(4000000, 8499999, 3, 2);
        $this->range(8500000, 9999999, 4, 2);
        break;
      case '978-9933':
        $this->range(0000000, 0999999, 1, 2);
        $this->range(1000000, 3999999, 2, 2);
        $this->range(4000000, 8999999, 3, 2);
        $this->range(9000000, 9999999, 4, 2);
        break;
      case '978-9934':
        $this->range(0000000, 0999999, 1, 2);
        $this->range(1000000, 4999999, 2, 2);
        $this->range(5000000, 7999999, 3, 2);
        $this->range(8000000, 9999999, 4, 2);
        break;
      case '978-9935':
        $this->range(0000000, 0999999, 1, 2);
        $this->range(1000000, 3999999, 2, 2);
        $this->range(4000000, 8999999, 3, 2);
        $this->range(9000000, 9999999, 4, 2);
        break;
      case '978-9936':
        $this->range(0000000, 1999999, 1, 2);
        $this->range(2000000, 3999999, 2, 2);
        $this->range(4000000, 7999999, 3, 2);
        $this->range(8000000, 9999999, 4, 2);
        break;
      case '978-9937':
        $this->range(0000000, 2999999, 1, 2);
        $this->range(3000000, 4999999, 2, 2);
        $this->range(5000000, 7999999, 3, 2);
        $this->range(8000000, 9999999, 4, 2);
        break;
      case '978-9938':
        $this->range(0000000, 7999999, 2, 2);
        $this->range(8000000, 9499999, 3, 2);
        $this->range(9500000, 9999999, 4, 2);
        break;
      case '978-9939':
        $this->range(0000000, 4999999, 1, 2);
        $this->range(5000000, 7999999, 2, 2);
        $this->range(8000000, 8999999, 3, 2);
        $this->range(9000000, 9999999, 4, 2);
        break;
      case '978-9940':
        $this->range(0000000, 1999999, 1, 2);
        $this->range(2000000, 4999999, 2, 2);
        $this->range(5000000, 8999999, 3, 2);
        $this->range(9000000, 9999999, 4, 2);
        break;
      case '978-9941':
        $this->range(0000000, 0999999, 1, 2);
        $this->range(1000000, 3999999, 2, 2);
        $this->range(4000000, 8999999, 3, 2);
        $this->range(9000000, 9999999, 4, 2);
        break;
      case '978-9942':
        $this->range(0000000, 8999999, 2, 2);
        $this->range(9000000, 9849999, 3, 2);
        $this->range(9850000, 9999999, 4, 2);
        break;
      case '978-9943':
        $this->range(0000000, 2999999, 2, 2);
        $this->range(3000000, 3999999, 3, 2);
        $this->range(4000000, 9999999, 4, 2);
        break;
      case '978-9944':
        $this->range(0000000, 0999999, 4, 2);
        $this->range(1000000, 4999999, 3, 2);
        $this->range(5000000, 5999999, 4, 2);
        $this->range(6000000, 6999999, 2, 2);
        $this->range(7000000, 7999999, 3, 2);
        $this->range(8000000, 8999999, 2, 2);
        $this->range(9000000, 9999999, 3, 2);
        break;
      case '978-9945':
        $this->range(0000000, 0099999, 2, 2);
        $this->range(0100000, 0799999, 3, 2);
        $this->range(0800000, 3999999, 2, 2);
        $this->range(4000000, 5699999, 3, 2);
        $this->range(5700000, 5799999, 2, 2);
        $this->range(5800000, 8499999, 3, 2);
        $this->range(8500000, 9999999, 4, 2);
        break;
      case '978-9946':
        $this->range(0000000, 1999999, 1, 2);
        $this->range(2000000, 3999999, 2, 2);
        $this->range(4000000, 8999999, 3, 2);
        $this->range(9000000, 9999999, 4, 2);
        break;
      case '978-9947':
        $this->range(0000000, 1999999, 1, 2);
        $this->range(2000000, 7999999, 2, 2);
        $this->range(8000000, 9999999, 3, 2);
        break;
      case '978-9948':
        $this->range(0000000, 3999999, 2, 2);
        $this->range(4000000, 8499999, 3, 2);
        $this->range(8500000, 9999999, 4, 2);
        break;
      case '978-9949':
        $this->range(0000000, 0999999, 1, 2);
        $this->range(1000000, 3999999, 2, 2);
        $this->range(4000000, 8999999, 3, 2);
        $this->range(9000000, 9999999, 4, 2);
        break;
      case '978-9950':
        $this->range(0000000, 2999999, 2, 2);
        $this->range(3000000, 8499999, 3, 2);
        $this->range(8500000, 9999999, 4, 2);
        break;
      case '978-9951':
        $this->range(0000000, 3999999, 2, 2);
        $this->range(4000000, 8499999, 3, 2);
        $this->range(8500000, 9999999, 4, 2);
        break;
      case '978-9952':
        $this->range(0000000, 1999999, 1, 2);
        $this->range(2000000, 3999999, 2, 2);
        $this->range(4000000, 7999999, 3, 2);
        $this->range(8000000, 9999999, 4, 2);
        break;
      case '978-9953':
        $this->range(0000000, 0999999, 1, 2);
        $this->range(1000000, 3999999, 2, 2);
        $this->range(4000000, 5999999, 3, 2);
        $this->range(6000000, 8999999, 2, 2);
        $this->range(9000000, 9999999, 4, 2);
        break;
      case '978-9954':
        $this->range(0000000, 1999999, 1, 2);
        $this->range(2000000, 3999999, 2, 2);
        $this->range(4000000, 7999999, 3, 2);
        $this->range(8000000, 9999999, 4, 2);
        break;
      case '978-9955':
        $this->range(0000000, 3999999, 2, 2);
        $this->range(4000000, 9299999, 3, 2);
        $this->range(9300000, 9999999, 4, 2);
        break;
      case '978-9956':
        $this->range(0000000, 0999999, 1, 2);
        $this->range(1000000, 3999999, 2, 2);
        $this->range(4000000, 8999999, 3, 2);
        $this->range(9000000, 9999999, 4, 2);
        break;
      case '978-9957':
        $this->range(0000000, 3999999, 2, 2);
        $this->range(4000000, 6999999, 3, 2);
        $this->range(7000000, 8499999, 2, 2);
        $this->range(8500000, 8799999, 4, 2);
        $this->range(8800000, 9999999, 2, 2);
        break;
      case '978-9958':
        $this->range(0000000, 0399999, 2, 2);
        $this->range(0400000, 0899999, 3, 2);
        $this->range(0900000, 0999999, 4, 2);
        $this->range(1000000, 1899999, 2, 2);
        $this->range(1900000, 1999999, 4, 2);
        $this->range(2000000, 4999999, 2, 2);
        $this->range(5000000, 8999999, 3, 2);
        $this->range(9000000, 9999999, 4, 2);
        break;
      case '978-9959':
        $this->range(0000000, 1999999, 1, 2);
        $this->range(2000000, 7999999, 2, 2);
        $this->range(8000000, 9499999, 3, 2);
        $this->range(9500000, 9699999, 4, 2);
        $this->range(9700000, 9799999, 3, 2);
        $this->range(9800000, 9999999, 2, 2);
        break;
      case '978-9960':
        $this->range(0000000, 5999999, 2, 2);
        $this->range(6000000, 8999999, 3, 2);
        $this->range(9000000, 9999999, 4, 2);
        break;
      case '978-9961':
        $this->range(0000000, 2999999, 1, 2);
        $this->range(3000000, 6999999, 2, 2);
        $this->range(7000000, 9499999, 3, 2);
        $this->range(9500000, 9999999, 4, 2);
        break;
      case '978-9962':
        $this->range(0000000, 5499999, 2, 2);
        $this->range(5500000, 5599999, 4, 2);
        $this->range(5600000, 5999999, 2, 2);
        $this->range(6000000, 8499999, 3, 2);
        $this->range(8500000, 9999999, 4, 2);
        break;
      case '978-9963':
        $this->range(0000000, 1999999, 1, 2);
        $this->range(2000000, 2499999, 2, 2);
        $this->range(2500000, 2799999, 3, 2);
        $this->range(2800000, 2999999, 4, 2);
        $this->range(3000000, 5499999, 2, 2);
        $this->range(5500000, 7349999, 3, 2);
        $this->range(7350000, 7499999, 4, 2);
        $this->range(7500000, 9999999, 4, 2);
        break;
      case '978-9964':
        $this->range(0000000, 6999999, 1, 2);
        $this->range(7000000, 9499999, 2, 2);
        $this->range(9500000, 9999999, 3, 2);
        break;
      case '978-9965':
        $this->range(0000000, 3999999, 2, 2);
        $this->range(4000000, 8999999, 3, 2);
        $this->range(9000000, 9999999, 4, 2);
        break;
      case '978-9966':
        $this->range(0000000, 1499999, 3, 2);
        $this->range(1500000, 1999999, 4, 2);
        $this->range(2000000, 6999999, 2, 2);
        $this->range(7000000, 7499999, 4, 2);
        $this->range(7500000, 9599999, 3, 2);
        $this->range(9600000, 9999999, 4, 2);
        break;
      case '978-9967':
        $this->range(0000000, 3999999, 2, 2);
        $this->range(4000000, 8999999, 3, 2);
        $this->range(9000000, 9999999, 4, 2);
        break;
      case '978-9968':
        $this->range(0000000, 4999999, 2, 2);
        $this->range(5000000, 9399999, 3, 2);
        $this->range(9400000, 9999999, 4, 2);
        break;
      case '978-9970':
        $this->range(0000000, 3999999, 2, 2);
        $this->range(4000000, 8999999, 3, 2);
        $this->range(9000000, 9999999, 4, 2);
        break;
      case '978-9971':
        $this->range(0000000, 5999999, 1, 2);
        $this->range(6000000, 8999999, 2, 2);
        $this->range(9000000, 9899999, 3, 2);
        $this->range(9900000, 9999999, 4, 2);
        break;
      case '978-9972':
        $this->range(0000000, 0999999, 2, 2);
        $this->range(1000000, 1999999, 1, 2);
        $this->range(2000000, 2499999, 3, 2);
        $this->range(2500000, 2999999, 4, 2);
        $this->range(3000000, 5999999, 2, 2);
        $this->range(6000000, 8999999, 3, 2);
        $this->range(9000000, 9999999, 4, 2);
        break;
      case '978-9973':
        $this->range(0000000, 0599999, 2, 2);
        $this->range(0600000, 0899999, 3, 2);
        $this->range(0900000, 0999999, 4, 2);
        $this->range(1000000, 6999999, 2, 2);
        $this->range(7000000, 9699999, 3, 2);
        $this->range(9700000, 9999999, 4, 2);
        break;
      case '978-9974':
        $this->range(0000000, 2999999, 1, 2);
        $this->range(3000000, 5499999, 2, 2);
        $this->range(5500000, 7499999, 3, 2);
        $this->range(7500000, 9499999, 4, 2);
        $this->range(9500000, 9999999, 2, 2);
        break;
      case '978-9975':
        $this->range(0000000, 0999999, 1, 2);
        $this->range(1000000, 3999999, 3, 2);
        $this->range(4000000, 4499999, 4, 2);
        $this->range(4500000, 8999999, 2, 2);
        $this->range(9000000, 9499999, 3, 2);
        $this->range(9500000, 9999999, 4, 2);
        break;
      case '978-9976':
        $this->range(0000000, 5999999, 1, 2);
        $this->range(6000000, 8999999, 2, 2);
        $this->range(9000000, 9899999, 3, 2);
        $this->range(9900000, 9999999, 4, 2);
        break;
      case '978-9977':
        $this->range(0000000, 8999999, 2, 2);
        $this->range(9000000, 9899999, 3, 2);
        $this->range(9900000, 9999999, 4, 2);
        break;
      case '978-9978':
        $this->range(0000000, 2999999, 2, 2);
        $this->range(3000000, 3999999, 3, 2);
        $this->range(4000000, 9499999, 2, 2);
        $this->range(9500000, 9899999, 3, 2);
        $this->range(9900000, 9999999, 4, 2);
        break;
      case '978-9979':
        $this->range(0000000, 4999999, 1, 2);
        $this->range(5000000, 6499999, 2, 2);
        $this->range(6500000, 6599999, 3, 2);
        $this->range(6600000, 7599999, 2, 2);
        $this->range(7600000, 8999999, 3, 2);
        $this->range(9000000, 9999999, 4, 2);
        break;
      case '978-9980':
        $this->range(0000000, 3999999, 1, 2);
        $this->range(4000000, 8999999, 2, 2);
        $this->range(9000000, 9899999, 3, 2);
        $this->range(9900000, 9999999, 4, 2);
        break;
      case '978-9981':
        $this->range(0000000, 0999999, 2, 2);
        $this->range(1000000, 1599999, 3, 2);
        $this->range(1600000, 1999999, 4, 2);
        $this->range(2000000, 7999999, 2, 2);
        $this->range(8000000, 9499999, 3, 2);
        $this->range(9500000, 9999999, 4, 2);
        break;
      case '978-9982':
        $this->range(0000000, 7999999, 2, 2);
        $this->range(8000000, 9899999, 3, 2);
        $this->range(9900000, 9999999, 4, 2);
        break;
      case '978-9983':
        $this->range(0000000, 7999999, 0, 2);
        $this->range(8000000, 9499999, 2, 2);
        $this->range(9500000, 9899999, 3, 2);
        $this->range(9900000, 9999999, 4, 2);
        break;
      case '978-9984':
        $this->range(0000000, 4999999, 2, 2);
        $this->range(5000000, 8999999, 3, 2);
        $this->range(9000000, 9999999, 4, 2);
        break;
      case '978-9985':
        $this->range(0000000, 4999999, 1, 2);
        $this->range(5000000, 7999999, 2, 2);
        $this->range(8000000, 8999999, 3, 2);
        $this->range(9000000, 9999999, 4, 2);
        break;
      case '978-9986':
        $this->range(0000000, 3999999, 2, 2);
        $this->range(4000000, 8999999, 3, 2);
        $this->range(9000000, 9399999, 4, 2);
        $this->range(9400000, 9699999, 3, 2);
        $this->range(9700000, 9999999, 2, 2);
        break;
      case '978-9987':
        $this->range(0000000, 3999999, 2, 2);
        $this->range(4000000, 8799999, 3, 2);
        $this->range(8800000, 9999999, 4, 2);
        break;
      case '978-9988':
        $this->range(0000000, 2999999, 1, 2);
        $this->range(3000000, 5499999, 2, 2);
        $this->range(5500000, 7499999, 3, 2);
        $this->range(7500000, 9999999, 4, 2);
        break;
      case '978-9989':
        $this->range(0000000, 0999999, 1, 2);
        $this->range(1000000, 1999999, 3, 2);
        $this->range(2000000, 2999999, 4, 2);
        $this->range(3000000, 5999999, 2, 2);
        $this->range(6000000, 9499999, 3, 2);
        $this->range(9500000, 9999999, 4, 2);
        break;
      case '978-99901':
        $this->range(0000000, 4999999, 2, 2);
        $this->range(5000000, 7999999, 3, 2);
        $this->range(8000000, 9999999, 2, 2);
        break;
      case '978-99902':
        $this->range(0000000, 9999999, 0, 2);
        break;
      case '978-99903':
        $this->range(0000000, 1999999, 1, 2);
        $this->range(2000000, 8999999, 2, 2);
        $this->range(9000000, 9999999, 3, 2);
        break;
      case '978-99904':
        $this->range(0000000, 5999999, 1, 2);
        $this->range(6000000, 8999999, 2, 2);
        $this->range(9000000, 9999999, 3, 2);
        break;
      case '978-99905':
        $this->range(0000000, 3999999, 1, 2);
        $this->range(4000000, 7999999, 2, 2);
        $this->range(8000000, 9999999, 3, 2);
        break;
      case '978-99906':
        $this->range(0000000, 2999999, 1, 2);
        $this->range(3000000, 5999999, 2, 2);
        $this->range(6000000, 6999999, 3, 2);
        $this->range(7000000, 8999999, 2, 2);
        $this->range(9000000, 9499999, 2, 2);
        $this->range(9500000, 9999999, 3, 2);
        break;
      case '978-99908':
        $this->range(0000000, 0999999, 1, 2);
        $this->range(1000000, 8999999, 2, 2);
        $this->range(9000000, 9999999, 3, 2);
        break;
      case '978-99909':
        $this->range(0000000, 3999999, 1, 2);
        $this->range(4000000, 9499999, 2, 2);
        $this->range(9500000, 9999999, 3, 2);
        break;
      case '978-99910':
        $this->range(0000000, 2999999, 1, 2);
        $this->range(3000000, 8999999, 2, 2);
        $this->range(9000000, 9999999, 3, 2);
        break;
      case '978-99911':
        $this->range(0000000, 5999999, 2, 2);
        $this->range(6000000, 9999999, 3, 2);
        break;
      case '978-99912':
        $this->range(0000000, 3999999, 1, 2);
        $this->range(4000000, 5999999, 3, 2);
        $this->range(6000000, 8999999, 2, 2);
        $this->range(9000000, 9999999, 3, 2);
        break;
      case '978-99913':
        $this->range(0000000, 2999999, 1, 2);
        $this->range(3000000, 3599999, 2, 2);
        $this->range(3600000, 5999999, 0, 2);
        $this->range(6000000, 6049999, 3, 2);
        $this->range(6050000, 9999999, 0, 2);
        break;
      case '978-99914':
        $this->range(0000000, 4999999, 1, 2);
        $this->range(5000000, 8999999, 2, 2);
        $this->range(9000000, 9999999, 3, 2);
        break;
      case '978-99915':
        $this->range(0000000, 4999999, 1, 2);
        $this->range(5000000, 7999999, 2, 2);
        $this->range(8000000, 9999999, 3, 2);
        break;
      case '978-99916':
        $this->range(0000000, 2999999, 1, 2);
        $this->range(3000000, 6999999, 2, 2);
        $this->range(7000000, 9999999, 3, 2);
        break;
      case '978-99917':
        $this->range(0000000, 2999999, 1, 2);
        $this->range(3000000, 8999999, 2, 2);
        $this->range(9000000, 9999999, 3, 2);
        break;
      case '978-99918':
        $this->range(0000000, 3999999, 1, 2);
        $this->range(4000000, 7999999, 2, 2);
        $this->range(8000000, 9999999, 3, 2);
        break;
      case '978-99919':
        $this->range(0000000, 2999999, 1, 2);
        $this->range(3000000, 3999999, 3, 2);
        $this->range(4000000, 6999999, 2, 2);
        $this->range(7000000, 7999999, 2, 2);
        $this->range(8000000, 8499999, 3, 2);
        $this->range(8500000, 8999999, 3, 2);
        $this->range(9000000, 9999999, 3, 2);
        break;
      case '978-99920':
        $this->range(0000000, 4999999, 1, 2);
        $this->range(5000000, 8999999, 2, 2);
        $this->range(9000000, 9999999, 3, 2);
        break;
      case '978-99921':
        $this->range(0000000, 1999999, 1, 2);
        $this->range(2000000, 6999999, 2, 2);
        $this->range(7000000, 7999999, 3, 2);
        $this->range(8000000, 8999999, 1, 2);
        $this->range(9000000, 9999999, 2, 2);
        break;
      case '978-99922':
        $this->range(0000000, 3999999, 1, 2);
        $this->range(4000000, 6999999, 2, 2);
        $this->range(7000000, 9999999, 3, 2);
        break;
      case '978-99923':
        $this->range(0000000, 1999999, 1, 2);
        $this->range(2000000, 7999999, 2, 2);
        $this->range(8000000, 9999999, 3, 2);
        break;
      case '978-99924':
        $this->range(0000000, 1999999, 1, 2);
        $this->range(2000000, 7999999, 2, 2);
        $this->range(8000000, 9999999, 3, 2);
        break;
      case '978-99925':
        $this->range(0000000, 3999999, 1, 2);
        $this->range(4000000, 7999999, 2, 2);
        $this->range(8000000, 9999999, 3, 2);
        break;
      case '978-99926':
        $this->range(0000000, 0999999, 1, 2);
        $this->range(1000000, 5999999, 2, 2);
        $this->range(6000000, 8999999, 3, 2);
        $this->range(9000000, 9999999, 2, 2);
        break;
      case '978-99927':
        $this->range(0000000, 2999999, 1, 2);
        $this->range(3000000, 5999999, 2, 2);
        $this->range(6000000, 9999999, 3, 2);
        break;
      case '978-99928':
        $this->range(0000000, 0999999, 1, 2);
        $this->range(1000000, 7999999, 2, 2);
        $this->range(8000000, 9999999, 3, 2);
        break;
      case '978-99929':
        $this->range(0000000, 4999999, 1, 2);
        $this->range(5000000, 7999999, 2, 2);
        $this->range(8000000, 9999999, 3, 2);
        break;
      case '978-99930':
        $this->range(0000000, 4999999, 1, 2);
        $this->range(5000000, 7999999, 2, 2);
        $this->range(8000000, 9999999, 3, 2);
        break;
      case '978-99931':
        $this->range(0000000, 4999999, 1, 2);
        $this->range(5000000, 7999999, 2, 2);
        $this->range(8000000, 9999999, 3, 2);
        break;
      case '978-99932':
        $this->range(0000000, 0999999, 1, 2);
        $this->range(1000000, 5999999, 2, 2);
        $this->range(6000000, 6999999, 3, 2);
        $this->range(7000000, 7999999, 1, 2);
        $this->range(8000000, 9999999, 2, 2);
        break;
      case '978-99933':
        $this->range(0000000, 2999999, 1, 2);
        $this->range(3000000, 5999999, 2, 2);
        $this->range(6000000, 9999999, 3, 2);
        break;
      case '978-99934':
        $this->range(0000000, 1999999, 1, 2);
        $this->range(2000000, 7999999, 2, 2);
        $this->range(8000000, 9999999, 3, 2);
        break;
      case '978-99935':
        $this->range(0000000, 2999999, 1, 2);
        $this->range(3000000, 5999999, 2, 2);
        $this->range(6000000, 6999999, 3, 2);
        $this->range(7000000, 8999999, 1, 2);
        $this->range(9000000, 9999999, 2, 2);
        break;
      case '978-99936':
        $this->range(0000000, 0999999, 1, 2);
        $this->range(1000000, 5999999, 2, 2);
        $this->range(6000000, 9999999, 3, 2);
        break;
      case '978-99937':
        $this->range(0000000, 1999999, 1, 2);
        $this->range(2000000, 5999999, 2, 2);
        $this->range(6000000, 9999999, 3, 2);
        break;
      case '978-99938':
        $this->range(0000000, 1999999, 1, 2);
        $this->range(2000000, 5999999, 2, 2);
        $this->range(6000000, 8999999, 3, 2);
        $this->range(9000000, 9999999, 2, 2);
        break;
      case '978-99939':
        $this->range(0000000, 5999999, 1, 2);
        $this->range(6000000, 8999999, 2, 2);
        $this->range(9000000, 9999999, 3, 2);
        break;
      case '978-99940':
        $this->range(0000000, 0999999, 1, 2);
        $this->range(1000000, 6999999, 2, 2);
        $this->range(7000000, 9999999, 3, 2);
        break;
      case '978-99941':
        $this->range(0000000, 2999999, 1, 2);
        $this->range(3000000, 7999999, 2, 2);
        $this->range(8000000, 9999999, 3, 2);
        break;
      case '978-99942':
        $this->range(0000000, 4999999, 1, 2);
        $this->range(5000000, 7999999, 2, 2);
        $this->range(8000000, 9999999, 3, 2);
        break;
      case '978-99943':
        $this->range(0000000, 2999999, 1, 2);
        $this->range(3000000, 5999999, 2, 2);
        $this->range(6000000, 9999999, 3, 2);
        break;
      case '978-99944':
        $this->range(0000000, 4999999, 1, 2);
        $this->range(5000000, 7999999, 2, 2);
        $this->range(8000000, 9999999, 3, 2);
        break;
      case '978-99945':
        $this->range(0000000, 5999999, 1, 2);
        $this->range(6000000, 8999999, 2, 2);
        $this->range(9000000, 9999999, 3, 2);
        break;
      case '978-99946':
        $this->range(0000000, 2999999, 1, 2);
        $this->range(3000000, 5999999, 2, 2);
        $this->range(6000000, 9999999, 3, 2);
        break;
      case '978-99947':
        $this->range(0000000, 2999999, 1, 2);
        $this->range(3000000, 6999999, 2, 2);
        $this->range(7000000, 9599999, 3, 2);
        $this->range(9600000, 9999999, 2, 2);
        break;
      case '978-99948':
        $this->range(0000000, 4999999, 1, 2);
        $this->range(5000000, 7999999, 2, 2);
        $this->range(8000000, 9999999, 3, 2);
        break;
      case '978-99949':
        $this->range(0000000, 1999999, 1, 2);
        $this->range(2000000, 8999999, 2, 2);
        $this->range(9000000, 9999999, 3, 2);
        break;
      case '978-99950':
        $this->range(0000000, 4999999, 1, 2);
        $this->range(5000000, 7999999, 2, 2);
        $this->range(8000000, 9999999, 3, 2);
        break;
      case '978-99951':
        $this->range(0000000, 9999999, 0, 2);
        break;
      case '978-99952':
        $this->range(0000000, 4999999, 1, 2);
        $this->range(5000000, 7999999, 2, 2);
        $this->range(8000000, 9999999, 3, 2);
        break;
      case '978-99953':
        $this->range(0000000, 2999999, 1, 2);
        $this->range(3000000, 7999999, 2, 2);
        $this->range(8000000, 9399999, 3, 2);
        $this->range(9400000, 9999999, 2, 2);
        break;
      case '978-99954':
        $this->range(0000000, 2999999, 1, 2);
        $this->range(3000000, 6999999, 2, 2);
        $this->range(7000000, 8799999, 3, 2);
        $this->range(8800000, 9999999, 2, 2);
        break;
      case '978-99955':
        $this->range(0000000, 0999999, 1, 2);
        $this->range(1000000, 1999999, 2, 2);
        $this->range(2000000, 5999999, 2, 2);
        $this->range(6000000, 7999999, 3, 2);
        $this->range(8000000, 8999999, 2, 2);
        $this->range(9000000, 9999999, 2, 2);
        break;
      case '978-99956':
        $this->range(0000000, 5999999, 2, 2);
        $this->range(6000000, 8599999, 3, 2);
        $this->range(8600000, 9999999, 2, 2);
        break;
      case '978-99957':
        $this->range(0000000, 1999999, 1, 2);
        $this->range(2000000, 7999999, 2, 2);
        $this->range(8000000, 9999999, 3, 2);
        break;
      case '978-99958':
        $this->range(0000000, 4999999, 1, 2);
        $this->range(5000000, 9499999, 2, 2);
        $this->range(9500000, 9999999, 3, 2);
        break;
      case '978-99959':
        $this->range(0000000, 2999999, 1, 2);
        $this->range(3000000, 5999999, 2, 2);
        $this->range(6000000, 9999999, 3, 2);
        break;
      case '978-99960':
        $this->range(0000000, 0999999, 1, 2);
        $this->range(1000000, 9499999, 2, 2);
        $this->range(9500000, 9999999, 3, 2);
        break;
      case '978-99961':
        $this->range(0000000, 3999999, 1, 2);
        $this->range(4000000, 8999999, 2, 2);
        $this->range(9000000, 9999999, 3, 2);
        break;
      case '978-99962':
        $this->range(0000000, 4999999, 1, 2);
        $this->range(5000000, 7999999, 2, 2);
        $this->range(8000000, 9999999, 3, 2);
        break;
      case '978-99963':
        $this->range(0000000, 4999999, 2, 2);
        $this->range(5000000, 9999999, 3, 2);
        break;
      case '978-99964':
        $this->range(0000000, 1999999, 1, 2);
        $this->range(2000000, 7999999, 2, 2);
        $this->range(8000000, 9999999, 3, 2);
        break;
      case '978-99965':
        $this->range(0000000, 3999999, 1, 2);
        $this->range(4000000, 7999999, 2, 2);
        $this->range(8000000, 9999999, 3, 2);
        break;
      case '978-99966':
        $this->range(0000000, 2999999, 1, 2);
        $this->range(3000000, 6999999, 2, 2);
        $this->range(7000000, 7999999, 3, 2);
        $this->range(8000000, 8999999, 0, 2);
        $this->range(9000000, 9999999, 0, 2);
        break;
      case '978-99967':
        $this->range(0000000, 1999999, 1, 2);
        $this->range(2000000, 5999999, 2, 2);
        $this->range(6000000, 8999999, 3, 2);
        $this->range(9000000, 9999999, 0, 2);
        break;
      case '978-99968':
        $this->range(0000000, 3999999, 1, 2);
        $this->range(4000000, 5999999, 3, 2);
        $this->range(6000000, 8999999, 2, 2);
        $this->range(9000000, 9999999, 3, 2);
        break;
      case '978-99969':
        $this->range(0000000, 4999999, 1, 2);
        $this->range(5000000, 7999999, 2, 2);
        $this->range(8000000, 9999999, 3, 2);
        break;
      case '978-99970':
        $this->range(0000000, 4999999, 1, 2);
        $this->range(5000000, 8999999, 2, 2);
        $this->range(9000000, 9999999, 3, 2);
        break;
      case '978-99971':
        $this->range(0000000, 5999999, 1, 2);
        $this->range(6000000, 8499999, 2, 2);
        $this->range(8500000, 9999999, 3, 2);
        break;
      case '978-99972':
        $this->range(0000000, 4999999, 1, 2);
        $this->range(5000000, 8999999, 2, 2);
        $this->range(9000000, 9999999, 3, 2);
        break;
      case '978-99973':
        $this->range(0000000, 3999999, 1, 2);
        $this->range(4000000, 7999999, 2, 2);
        $this->range(8000000, 9999999, 3, 2);
        break;
      case '979-10':
        $this->range(0000000, 1999999, 2, 2);
        $this->range(2000000, 6999999, 3, 2);
        $this->range(7000000, 8999999, 4, 2);
        $this->range(9000000, 9759999, 5, 2);
        $this->range(9760000, 9999999, 6, 2);
        break;
      case '979-11':
        $this->range(0000000, 2499999, 2, 2);
        $this->range(2500000, 5499999, 3, 2);
        $this->range(5500000, 8499999, 4, 2);
        $this->range(8500000, 9499999, 5, 2);
        $this->range(9500000, 9999999, 6, 2);
        break;
    }
  }
}
