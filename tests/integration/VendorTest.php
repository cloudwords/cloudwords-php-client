<?php
namespace Cloudwords\Tests\Integration;

class VendorTest extends \PHPUnit_Framework_TestCase
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
     * Test case for getting vendor
     */
    public function testGetVendor()
    {
	    try {
	      $vendor = $this->client->getVendor(TESTS_VENDOR_ID);
	    } catch(\Cloudwords\Exception $e) {
	      echo $e->getErrorMessage();
	    }

	    $expectedState = array('id' => TESTS_VENDOR_ID, 
	                           'path' => TESTS_BASE_API_URL . '/' . TESTS_API_VERSION
                                      . '/vendor/' . TESTS_VENDOR_ID . '.json');
	    $this->assertEquals($expectedState['id'], $vendor->getId());
        $this->assertEquals($expectedState['path'], $vendor->getPath());
    }

    /**
     * Test case for getting preferred vendors
     */
    public function testGetPreferredVendors()
    {
	    try {
	        $vendors = $this->client->getPreferredVendors();
	    } catch(\Cloudwords\Exception $e) {
	        echo $e->getErrorMessage();
	    }

	    if (count($vendors) > 0) {
	        foreach ($vendors as $vendor) {
                $path = TESTS_BASE_API_URL . '/' . TESTS_API_VERSION . '/vendor/' . $vendor->getId() . '.json';
                $this->assertTrue(is_int($vendor->getId()));
                $this->assertTrue(is_string($vendor->getName()));
                $this->assertTrue(is_string($vendor->getPath()));
	        }
	    }
    }
    
    /**
     * Assert vendor object metadata
     * 
     * @param \Cloudwords\Resource\Vendor	$metadata
     * @param array		$expectedState
     */
    private function assertVendorMetadata($metadata, $expectedState)
    {
        $this->assertEquals($expectedState['id'], $metadata->getId());
        $this->assertEquals($expectedState['path'], $metadata->getPath());
    }
}
