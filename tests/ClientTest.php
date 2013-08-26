<?php
namespace Cloudwords\Tests;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->client = new \Cloudwords\Client(TESTS_BASE_API_URL, TESTS_API_VERSION, TESTS_AUTH_TOKEN);
    }
    
    public function tearDown()
    {
        
    }

    /**
     * Test Case for Client object initializing 
     */
    public function testInitialized()
    {
        $this->assertNotNull($this->client);
        $this->assertEquals(TESTS_BASE_API_URL . '/' . TESTS_API_VERSION, $this->client->getBaseUrlWithVersion());
        $this->assertEquals(TESTS_AUTH_TOKEN, $this->client->getAuthToken());
        $this->assertEquals(30, $this->client->getConnectionTimeout());
        $this->assertEquals(60, $this->client->getSocketTimeout());
        $this->assertEquals(3, $this->client->getMaxTotalConnections());
    }
}
