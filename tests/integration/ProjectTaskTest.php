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
     * Test Case for Get Project Tasks
     */
    public function testGetProjectTasks()
    {
        $projectTasks = $this->client->getProjectTasks(TESTS_PROJECT_ID);
        $this->assertTrue(is_array($projectTasks));
        if (empty($projectTasks))
            return;
        
        // check the first project task
        $this->assertTask($projectTasks[0], TESTS_PROJECT_ID);
    }
    
    /**
     * Test Case for Get Project Tasks With Status 'open'
     */
    public function testGetProjectTasksWithStatusOpen()
    {
        $projectTasks = $this->client->getProjectTasksByStatus(TESTS_PROJECT_ID, 'open');
        $this->assertTrue(is_array($projectTasks));
        if (empty($projectTasks))
            return;
        
        // check the first project task
        $this->assertTask($projectTasks[0], TESTS_PROJECT_ID);
    }
    
    /**
     * Test Case for Get Project Tasks With Status 'closed'
     */
    public function testGetProjectTasksWithStatusClosed()
    {
        $projectTasks = $this->client->getProjectTasksByStatus(TESTS_PROJECT_ID, 'closed');
        $this->assertTrue(is_array($projectTasks));
        if (empty($projectTasks))
            return;
        
        // check the first project task
        $this->assertTask($projectTasks[0], TESTS_PROJECT_ID);
    }

    /**
     * Test Case for Get Project Tasks With Status 'rejected'
     */
    public function testGetProjectTasksWithStatusRejected()
    {
        $projectTasks = $this->client->getProjectTasksByStatus(TESTS_PROJECT_ID, 'rejected');
        $this->assertTrue(is_array($projectTasks));
        if (empty($projectTasks))
            return;
        
        // check the first project task
        $this->assertTask($projectTasks[0], TESTS_PROJECT_ID);
    }
    
    /**
     * Test Case for Get Project Tasks With Status 'cancelled'
     */
    public function testGetProjectTasksWithStatusCancelled()
    {
        $projectTasks = $this->client->getProjectTasksByStatus(TESTS_PROJECT_ID, 'cancelled');
        $this->assertTrue(is_array($projectTasks));
        if (empty($projectTasks))
            return;
        
        // check the first project task
        $this->assertTask($projectTasks[0], TESTS_PROJECT_ID);
    }
    
    /**
     * Test Case for Get Project Task
     */
    public function testGetProjectTask()
    {
        $projectTasks = $this->client->getProjectTask(TESTS_PROJECT_ID, TESTS_TASK_ID);
        // check the first project task
        $this->assertTask($projectTasks, TESTS_PROJECT_ID);
    }
    
    /**
     * Test Case for Get All Project Tasks
     */
    public function testGetAllProjectTasks()
    {
        $allProjectTasks = $this->client->getAllProjectTasks();
        $this->assertTrue(is_array($allProjectTasks));
        if (empty($allProjectTasks))
            return;
        
        // check the first project task
        $this->assertTask($allProjectTasks[0]);
    }
    
    /**
     * Test Case for Get All Project Tasks With Status Open
     */
    public function testGetAllProjectTasksWithStatusOpen()
    {
        $projectTasks = $this->client->getAllProjectTasksByStatus('open');
        $this->assertTrue(is_array($projectTasks));
        if (empty($projectTasks))
            return;
        
        // check the first project task
        $this->assertTask($projectTasks[0]);   
    }
    
	/**
     * Test Case for Get All Project Tasks With Status Closed
     */
    public function testGetAllProjectTasksWithStatusClosed()
    {
        $projectTasks = $this->client->getAllProjectTasksByStatus('closed');
        $this->assertTrue(is_array($projectTasks));
        if (empty($projectTasks))
            return;
        
        // check the first project task
        $this->assertTask($projectTasks[0]);   
    }
    
    /**
     * Test Case for Get All Project Tasks With Status Rejected
     */
    public function testGetAllProjectTasksWithStatusRejected()
    {
        $projectTasks = $this->client->getAllProjectTasksByStatus('rejected');
        $this->assertTrue(is_array($projectTasks));
        if (empty($projectTasks))
            return;
        
        // check the first project task
        $this->assertTask($projectTasks[0]);   
    }
    
    /**
     * Test Case for Get All Project Tasks With Status Cancelled
     */
    public function testGetAllProjectTasksWithStatusCancelled()
    {
        $projectTasks = $this->client->getAllProjectTasksByStatus('cancelled');
        $this->assertTrue(is_array($projectTasks));
        if (empty($projectTasks))
            return;
        
        // check the first project task
        $this->assertTask($projectTasks[0]);   
    }
    
    /**
     * Assert Task Object 
     * 
     * @param	\Cloudwords\Resources\Task $projectTask
     * @param	int	projectId
     */
    private function assertTask(\Cloudwords\Resources\Task $projectTask, $projectId = null)
    {
        $this->assertTrue($projectTask instanceof \Cloudwords\Resources\Task);
        $this->assertTrue(is_int($projectTask->getId()));
        $this->assertTrue($projectTask->getType() instanceof \Cloudwords\Resources\CodeAndDisplay);
        $this->assertTrue($projectTask->getStatus() instanceof \Cloudwords\Resources\CodeAndDisplay);
        if ($projectTask->getProject() !== null && $projectId !== null) {
            $this->assertTrue($projectTask->getProject() instanceof \Cloudwords\Resources\Project);
            $this->assertEquals($projectId, $projectTask->getProject()->getId());
        }
    }
}