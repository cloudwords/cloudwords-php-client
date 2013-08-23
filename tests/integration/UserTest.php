<?php
namespace Cloudwords\Tests;

class UserTest extends \PHPUnit_Framework_TestCase
{
    protected $client;
    
    protected $params;
    
    public function setUp()
    {
        $this->client = new \Cloudwords\Client(TESTS_BASE_API_URL, TESTS_API_VERSION, TESTS_AUTH_TOKEN);
        $this->params = array('name' => 'New Department Test Case');
    }
    
    public function tearDown()
    {
        
    }

    /**
     * Test Case for Get Current User 
     */
    public function testGetCurrentUser()
    {
        $user = $this->client->getCurrentUser();
        $this->assertUser($user);
        $this->assertEquals($user->getId(), TESTS_CURRENT_USER_ID);
    }
    
    /**
     * Test Case for Get Active User
     */
    public function testGetActiveUser()
    {
        $users = $this->client->getActiveUser();
        $this->assertTrue(is_array($users));
        if (count($users) > 0) {
            foreach ($users as $user)
                $this->assertUser($user);
        }        
    }
    
    /**
     * Assert User object
     * 
     * @param \Cloudwords\Resources\User	$user
     */
    private function assertUser(\Cloudwords\Resources\User $user)
    {
        $this->assertTrue($user instanceof \Cloudwords\Resources\User);
        $this->assertTrue(is_int($user->getId()));
        $this->assertTrue(is_string($user->getFirstName()));
        $this->assertTrue(is_string($user->getLastName()));
    }
}