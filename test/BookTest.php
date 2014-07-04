<?php

use Isbn\Book;

class BookTest extends PHPUnit_Framework_TestCase {

  public function testCreation() {
    $book = new Book('title', 'publisher');

    $this->assertInstanceOf('Isbn\Book', $book);
  }
}
