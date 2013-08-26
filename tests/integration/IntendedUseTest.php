<?php
namespace Cloudwords\Tests\Integration;

class IntendedUseTest extends \PHPUnit_Framework_TestCase
{
    protected $client;
    
    public function setUp()
    {
        $this->client = new \Cloudwords\Client(TESTS_BASE_API_URL, TESTS_API_VERSION, TESTS_AUTH_TOKEN);
    }
    
    public function tearDown()
    {
    }

    /**
     * Test Case for all intended use 
     */
	public function testAllIntendedUsesExist()
    {
	    try {
	        $intendedUses = $this->client->getIntendedUses();
	    } catch(\Cloudwords\Exception $e) {
	        echo $e;
	    }

	    $this->assertTrue(is_array($intendedUses));
	    $this->assertAllIntendedUsesExist($intendedUses);
	}
	  
	/**
     * Assert all intended use 
     * 
     * @param   array   $languages
     */
	private function assertAllIntendedUsesExist($intendedUses)
    {
	    $this->assertNotNull($intendedUses);
	    $this->assertEquals(5, count($intendedUses));
	    for($index = 0; $index < count($intendedUses); $index++) {
  	        $intendedUse = $intendedUses[$index];
  	        $intendedUseName = $intendedUse->getName();
  	        $intendedUseId   = $intendedUse->getId();
  	        switch($index) {
  	            case 0:
  	                $this->assertEquals("Documentation", $intendedUseName);
  	                $this->assertEquals(1, $intendedUseId);
  	                break;
  	            case 1:
  	                $this->assertEquals("Marketing", $intendedUseName);
  	                $this->assertEquals(2, $intendedUseId);
  	                break;
  	            case 2:
  	                $this->assertEquals("Training", $intendedUseName);
  	                $this->assertEquals(3, $intendedUseId);
  	                break;
  	            case 3:
  	                $this->assertEquals("UI", $intendedUseName);
  	                $this->assertEquals(4, $intendedUseId);
  	                break;
  	            case 4:
  	                $this->assertEquals("Website", $intendedUseName);
  	                $this->assertEquals(5, $intendedUseId);
  	                break;
  	        }
	    }
	}
}
