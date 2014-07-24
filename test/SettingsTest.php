<?php

use Isbn\Settings;

class SettingsTest extends PHPUnit_Framework_TestCase {

  public function testCreation() {
    $settings = Settings::getInstance();

    $this->assertInstanceOf('Isbn\Settings', $settings);
  }
  
  public function testCacheDirectoryGetDefault() {
    $settings = Settings::getInstance();
        
    $this->assertEquals('cache', $settings->getCacheDirectory());
  }  
  
  public function testCacheDirectorySetting() {
    $settings = Settings::getInstance();
    
    $settings->setCacheDirectory('testcache');
    
    $this->assertEquals('testcache', $settings->getCacheDirectory());
    
    unset($settigns);
    
    $settings2 = Settings::getInstance();
    $this->assertEquals('testcache', $settings2->getCacheDirectory());
  }
}
