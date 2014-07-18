<?php

namespace Isbn;

class Book {

  public $imageSmall;
  public $imageMedium;
  public $imageLarge;
  public $authors = array();
  public $ean;
  public $isbn;
  public $dimensions = array(
    'height' => 0,
    'length' => 0,
    'width' => 0,
    'weight' => 0
  );
  public $pages = 0;
  public $publicationDate;
  public $publisher;
  public $title;

  public function getImageSmall() {
    return $this->imageSmall;
  }

  public function getImageMedium() {
    return $this->imageMedium;
  }

  public function getImageLarge() {
    return $this->imageLarge;
  }

  public function getAuthors() {
    return $this->authors;
  }

  public function getEan() {
    return $this->ean;
  }

  public function getIsbn() {
    return $this->isbn;
  }

  public function getDimensions() {
    return $this->dimensions;
  }

  public function getPages() {
    return $this->pages;
  }

  public function getPublicationDate() {
    return $this->publicationDate;
  }

  public function getPublisher() {
    return $this->publisher;
  }

  public function getTitle() {
    return $this->title;
  }

  public function setImageSmall($imageSmall) {
    $this->imageSmall = $imageSmall;
  }

  public function setImageMedium($imageMedium) {
    $this->imageMedium = $imageMedium;
  }

  public function setImageLarge($imageLarge) {
    $this->imageLarge = $imageLarge;
  }

  public function setAuthor($author) {
    $this->authors[] = $author;
  }

  public function setAuthors($authors) {
    $this->authors = $authors;
  }

  public function setEan($ean) {
    $this->ean = $ean;
  }

  public function setIsbn($isbn) {
    $this->isbn = $isbn;
  }

  public function setDimensions($dimensions) {
    $this->dimensions = $dimensions;
  }

  public function setPages($pages) {
    $this->pages = $pages;
  }

  public function setPublicationDate($publicationDate) {
    $this->publicationDate = $publicationDate;
  }

  public function setPublisher($publisher) {
    $this->publisher = $publisher;
  }

  public function setTitle($title) {
    $this->title = $title;
  }

  public function __construct($title, $isbn = '') {
    $this->setTitle($title);

    if ($isbn !== '') {
      $this->setIsbn($isbn);
    }
  }

  /**
   * Convert base64 encoded image into a real image.
   *
   * @param string $directory The directory to save the image to.
   */
  public function convertBase64Image($directory) {
    $ifp = fopen($directory . '/' . $this->isbn . '.jpg', "wb"); 
    
    $base64String = $this->getImageLarge()['base64'];
    
    fwrite($ifp, base64_decode($base64String));
    fclose($ifp);
  }
  
  public function printDimensions() {
    $output = '';

    $dimensions = $this->getDimensions();

    $output .= "\t" . 'Height: ' . $dimensions['height'] . PHP_EOL;
    $output .= "\t" . 'Length: ' . $dimensions['length'] . PHP_EOL;
    $output .= "\t" . 'Width: ' . $dimensions['width'] . PHP_EOL;
    $output .= "\t" . 'Weight: ' . $dimensions['weight'];
    return $output;
  }

  public function printImageAttributes($property) {
    $output = '';
    $output .= "\t" . 'URL: ' . (isset($property['url']) ? $property['url'] : '') . PHP_EOL;
    $output .= "\t" . 'Height: ' . (isset($property['height']) ? $property['height'] : '') . PHP_EOL;
    $output .= "\t" . 'Width: ' . (isset($property['width']) ? $property['width'] : '');
    return $output;
  }

  public function __toString() {
    $output = '';
    $output .= 'BOOK:' . PHP_EOL;
    $output .= 'Title: ' . $this->getTitle() . PHP_EOL;

    $output .= 'Author' . ((count($this->authors) > 1) ? 's' : '') . ': ' . implode(', ', $this->getAuthors()) . PHP_EOL;

    $output .= 'Publisher: ' . $this->getPublisher() . PHP_EOL;
    $output .= 'Publication Date: ' . $this->getPublicationDate() . PHP_EOL;
    $output .= 'ISBN: ' . $this->getIsbn() . PHP_EOL;
    $output .= 'Pages: ' . $this->getPages() . PHP_EOL;
    $output .= 'Dimentions: ' . PHP_EOL . $this->printDimensions() . PHP_EOL;
    $output .= 'Large Image: ' . PHP_EOL . $this->printImageAttributes($this->getImageLarge()) . PHP_EOL;
    $output .= 'Medium Image: ' . PHP_EOL . $this->printImageAttributes($this->getImageMedium()) . PHP_EOL;
    $output .= 'Small Image: ' . PHP_EOL . $this->printImageAttributes($this->getImageSmall()) . PHP_EOL;
    return $output;
  }
}
