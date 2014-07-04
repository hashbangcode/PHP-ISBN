<?php

namespace Isbn\Isbn;

class Validate {

  public function valid($isbn) {
    if (strlen($isbn) == 13) {
      return $this->validateIsbn13($isbn);
    }

    if (strlen($isbn) == 10) {
      return $this->validateIsbn10($isbn);
    }

    return false;
  }

  public function identify($isbn) {
    if ($this->isbn10($isbn)) {
      return 10;
    }

    if ($this->isbn13($isbn)) {
      return 13;
    }

    return false;
  }

  protected function is10($isbn) {
    if (strlen($isbn) == 10) {
      return true;
    }

    return false;
  }

  protected function is13($isbn) {

    if (strlen($isbn) == 13) {
      return true;
    }

    return false;
  }

  public function validateIsbn10($isbn) {

    if (strlen($isbn) != 10) {
      return false;
    }

    if (!preg_match("/\d{9}[0-9xX]/i", $isbn)) {
      return false;
    }

    $check = 0;
    for ($i = 0; $i < 10; $i++) {
      if ($isbn[$i] == "X") {
        $check += 10 * intval(10 - $i);
      } else {
        $check += intval($isbn[$i]) * intval(10 - $i);
      }
    }
    return $check % 11 == 0;
  }

  public function validateIsbn13($isbn) {

    if (strlen($isbn) != 13) {
      return false;
    }

    if (!preg_match("/\d{13}/i", $isbn)) {
      return false;
    }

    $check = 0;
    for ($i = 0; $i < 13; $i+=2) {
      $check += substr($isbn, $i, 1);
    }

    for ($i = 1; $i < 12; $i+=2) {
      $check += 3 * substr($isbn, $i, 1);
    }

    return $check % 10 == 0;
  }

}
