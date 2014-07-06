<?php

namespace Isbn\Isbn\Lookup\Service;

/**
 * Class Amazon
 *
 * Uses the Amazon associate API to find information about a book.
 *
 * Perhaps the most complete ISBN lookup service available.
 *
 * @package Isbn\Isbn\Lookup\Service
 */
class Amazon extends Service {

  protected $amazonAccessKeyID;
  protected $amazonSecretKey;
  protected $amazonAssociateTag;

  public function __construct($amazonAccessKeyID, $amazonSecretKey, $amazonAssociateTag) {

    $this->amazonAccessKeyID = $amazonAccessKeyID;
    $this->amazonSecretKey = $amazonSecretKey;
    $this->amazonAssociateTag = $amazonAssociateTag;

    parent::__construct();
  }

  /**
   *
   * @param type $isbn
   * @return boolean|\Isbn\Book
   */
  public function getMetadataFromIsbn($isbn) {

    // Reset errors.
    $this->errors = array();

    $books = array();


    $host = 'ecs.amazonaws.com';
    $path = '/onca/xml';

    $args = array(
      'AssociateTag' => $this->amazonAssociateTag,
      'AWSAccessKeyId' => $this->amazonAccessKeyID,
      'IdType' => 'ISBN',
      'ItemId' => $isbn,
      'Operation' => 'ItemLookup',
      'ResponseGroup' => 'Medium',
      'SearchIndex' => 'Books',
      'Service' => 'AWSECommerceService',
      'Timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
      'Version' => '2009-01-06'
    );

    ksort($args);
    $parts = array();
    foreach (array_keys($args) as $key) {
      $parts[] = $key . "=" . $args[$key];
    }

    // Construct the string to sign
    $stringToSign = "GET\n" . $host . "\n" . $path . "\n" . implode("&", $parts);
    $stringToSign = str_replace('+', '%20', $stringToSign);
    $stringToSign = str_replace(':', '%3A', $stringToSign);
    $stringToSign = str_replace(';', urlencode(';'), $stringToSign);

    // Sign the request
    $signature = hash_hmac("sha256", $stringToSign, $this->amazonSecretKey, TRUE);

    // Base64 encode the signature and make it URL safe
    $signature = base64_encode($signature);
    $signature = str_replace('+', '%2B', $signature);
    $signature = str_replace('=', '%3D', $signature);

    // Construct the URL
    $url = 'http://' . $host . $path . '?' . implode("&", $parts) . "&Signature=" . $signature;

    $this->rawData = file_get_contents($url);

    if ($this->rawData === false) {
      $this->setError('-1', 'Communication error.');
      return false;
    }

    $xml = new \DOMDocument();
    $xml->loadXML($this->rawData);

    $items = $xml->getElementsByTagName('Item');

    if ($items->length == 0) {
      // If there are no Item nodes then no book was found. Store errors and return FALSE.
      $errors = $xml->getElementsByTagName('Errors');

      if (count($errors) == 0) {
        $this->setError('-1', 'No Items or Errors found in response.');
        return false;
      }

      foreach ($errors as $errorNodes) {
        $this->setError($xml->getElementsByTagName('Code')->item(0)->nodeValue, $xml->getElementsByTagName('Message')->item(0)->nodeValue);
      }

      return false;
    }

    foreach ($items as $node) {
      if ($node->hasChildNodes()) {
        foreach ($node->childNodes as $childNode) {

          switch ($childNode->nodeName) {
            case 'SmallImage':
              $imageSmall = array();
              foreach ($childNode->childNodes as $imageNode) {
                $imageSmall[strtolower($imageNode->nodeName)] = $imageNode->nodeValue;
              }
              break;

            case 'MediumImage':
              $imageMedium = array();
              foreach ($childNode->childNodes as $imageNode) {
                $imageMedium[strtolower($imageNode->nodeName)] = $imageNode->nodeValue;
              }
              break;

            case 'LargeImage':
              $imageLarge = array();
              foreach ($childNode->childNodes as $imageNode) {
                $imageLarge[strtolower($imageNode->nodeName)] = $imageNode->nodeValue;
              }
              break;

            case 'ItemAttributes':
              foreach ($childNode->childNodes as $itemAttributes) {
                //echo 'ChildChild:' . $itemAttributes->nodeName . PHP_EOL;

                switch ($itemAttributes->nodeName) {
                  case 'EAN':
                    $ean = $itemAttributes->nodeValue;
                    break;
                  case 'ItemDimensions':
                    $dimensions = array();
                    foreach ($itemAttributes->childNodes as $itemDimensions) {
                      $dimensions[strtolower($itemDimensions->nodeName)] = $itemDimensions->nodeValue;
                    }
                    break;

                  case 'PublicationDate':
                    $publicationDate = $itemAttributes->nodeValue;
                    break;

                  case 'Publisher':
                    $publisher = $itemAttributes->nodeValue;
                    break;

                  case 'Title':
                    $title = $itemAttributes->nodeValue;
                    break;


                  case 'Creator':
                    // Deliberate fall through.
                  case 'Author':
                    $author = $itemAttributes->nodeValue;
                    break;

                  case 'NumberOfPages':
                    $pages = $itemAttributes->nodeValue;
                    break;

                  case 'Studio':
                  case 'Binding':
                  case 'ProductTypeName':
                  default:
                    break;
                }
              }

              break;

            case 'ImageSets':
            // Deliberate fall through.
            case 'SalesRank':
            // Deliberate fall through.
            case 'ASIN':
            // Deliberate fall through.
            case 'ItemLinks':
            // Deliberate fall through.
            case 'OfferSummary':
            // Deliberate fall through.
            case 'DetailPageURL':
            // Deliberate fall through.
            case 'EditorialReviews':
            // Deliberate fall through.
            default:
              break;
          }
        }

        $book = new \Isbn\Book($title, $isbn);
        $book->setPublisher($publisher);
        $book->setPublicationDate($publicationDate);
        $book->setAuthor($author);
        $book->setPages($pages);
        $book->setEan($ean);
        $book->setDimensions($dimensions);
        $book->setImageLarge($imageLarge);
        $book->setImageMedium($imageMedium);
        $book->setImageSmall($imageSmall);

        $books[] = $book;
      }
    }


    return $books;
  }

}
