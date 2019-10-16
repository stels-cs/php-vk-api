<?php

class StatTest extends PHPUnit_Framework_TestCase
{
    public function testUsersGet()
    {
          \Hs\StatCollector::sum("test_run", 1);
          $this->assertNull(\Hs\StatCollector::$lastError);
    }
}