<?php
namespace Cloudwords\Tests\Integration;

class DepartmentTest extends \PHPUnit_Framework_TestCase
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
     * Test Case for Get Departments 
     */
    public function testGetDepartments()
    {
        $departments = $this->client->getDepartments();
        $this->assertTrue(is_array($departments));
        if (count($departments) > 0) {
            foreach ($departments as $department)
                $this->assertDepartment($department);
        }
    }
    
    /**
     * Test Case for Create Department
     */
    public function testCreateDepartment()
    {
       $department = $this->client->createDepartment($this->params);
       $this->assertDepartment($department);
       $this->assertEquals($this->params['name'], $department->getName()); 
    }
    
    /**
     * Assert Department object
     * 
     * @param \Cloudwords\Resources\Department $department
     */
    private function assertDepartment(\Cloudwords\Resources\Department $department)
    {
        $this->assertTrue($department instanceof \Cloudwords\Resources\Department);
        $this->assertTrue(is_int($department->getId()));
        $this->assertTrue(is_string($department->getName()));
    }
}
