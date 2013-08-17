<?php
namespace Cloudwords\Tests;

class ProjectTaskTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->client = new \Cloudwords\Client(TESTS_BASE_API_URL, TESTS_API_VERSION, TESTS_AUTH_TOKEN);
    }
    
    public function tearDown()
    {
        
    }

    /**
     * Unit Test for Get Project Tasks
     */
    public function testGetProjectTasks()
    {
        $projectTasks = $this->client->getProjectTasks(TESTS_PROJECT_ID);
        $this->assertTrue(is_array($projectTasks));
        if (empty($projectTasks))
            return;
        
        // check project task
        $this->assertProjectTask($projectTasks[0]);
    }
    
    /**
     * Assert Task Object 
     * 
     * @param \Cloudwords\Resources\Task $projectTask
     */
    private function assertProjectTask(\Cloudwords\Resources\Task $projectTask)
    {
        $this->assertTrue($projectTask instanceof \Cloudwords\Resources\Task);
        $this->assertTrue(is_int($projectTask->getId()));
        $this->assertTrue($projectTask->getProject() instanceof \Cloudwords\Resources\Project);
        $this->assertEquals(TESTS_PROJECT_ID, $projectTask->getProject()->getId());
    }
}