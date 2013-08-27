<?php
namespace Cloudwords\Tests\Integration;

class ProjectTaskTest extends \PHPUnit_Framework_TestCase
{
    protected $client;
    
    protected $params;
    
    public function setUp()
    {
        $this->client = new \Cloudwords\Client(TESTS_BASE_API_URL, TESTS_API_VERSION, TESTS_AUTH_TOKEN);
        $this->params = array('name' => 'Project Task Test Case',
                              'description' => 'This is just for unit testing Project Task PHP Client',
                              'type' => 'custom',
                              'assignee'  => array('customerUser' => array('id' => TESTS_CUSTOMERUSER_ID)),
                              'followers' => array(array('customerUser' => array('id' => TESTS_CUSTOMERUSER_ID)),
                                                    array('vendor' => array('id' => TESTS_VENDOR_ID))
                                                   ),
                              'targetLanguage' => array('code' => 'de'),
                              'startDate' => TESTS_TASK_STARTDATE,
                              'dueDate' => TESTS_TASK_DUEDATE,
                              'emailReminderDay' => 5
                             );
    }
    
    public function tearDown()
    {
        
    }

    /**
     * Test Case for Get Project Tasks
     */
    public function testGetProjectTasks()
    {
        try {
            $projectTasks = $this->client->getProjectTasks(TESTS_PROJECT_ID);
            $this->assertTrue(is_array($projectTasks));
            if (empty($projectTasks))
                return;
            
            // check the first project task
            $this->assertTask($projectTasks[0], TESTS_PROJECT_ID);
        } catch (\Cloudwords\Exception $e) {
            $this->fail($e->getMessage());
        }
        
        return;
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
     * Test Case for Create Project Task
     * 
     * @return	int	$taskId
     */
    public function testCreateProjectTask()
    {
        $projectTask = $this->client->createProjectTask(TESTS_PROJECT_ID, $this->params);
        $this->assertTask($projectTask, TESTS_PROJECT_ID, $this->params);
        return $projectTask->getId();
    }
    
    /**
     * Test Case for Update Project Task
     * 
     * @depends	testCreateProjectTask
     * @return int	$taskId
     */
    public function testUpdateProjectTask($taskId)
    {
        $params = $this->params;
        $params['name'] .= ' - REVISED';
        $params['description'] .= ' - REVISED';
        $projectTask = $this->client->updateProjectTask(TESTS_PROJECT_ID, $taskId, $params);
        $this->assertTask($projectTask, TESTS_PROJECT_ID, $params);
        return $projectTask->getId();
    }

    /**
     * Test Case for Upload Task Attachment
     * 
     * @depends	testUpdateProjectTask
     * @return int	$taskId
     */
    public function testUploadTaskAttachment($taskId)
    {
        $taskAttachment = $this->client->uploadTaskAttachment(TESTS_PROJECT_ID, $taskId, TESTS_TASK_ATTACHMENT_PATH);
        $this->assertTaskAttachment($taskAttachment);
        return $taskId;
    }

    /**
     * Test Case for Get Task Attachment
     * 
     * @depends	testUploadTaskAttachment
     */
    public function testGetTaskAttachment($taskId)
    {
        $taskAttachment = $this->client->getTaskAttachment(TESTS_PROJECT_ID, $taskId);
        $this->assertTaskAttachment($taskAttachment);
    }
    

    /**
     * Test Case for Get All Project Tasks By Department Id
     */
    public function testGetAllProjectTasksByDepartmentId()
    {
        $projectTasks = $this->client->getAllProjectTasksByDepartmentId(TESTS_DEPARTMENT_ID);
        if (empty($projectTasks))
            return;
        
        // check the first project task
        $this->assertTask($projectTasks[0]);
    }
    
    /**
     * Test Case for Get All Project Task With Status Open By Department Id 
     */
    public function testGetgetAllProjectTasksWithStatusOpenByDepartmentId()
    {
        $projectTasks = $this->client->getAllProjectTasksWithStatusByDepartmentId(TESTS_DEPARTMENT_ID, 'open');
        if (empty($projectTasks))
            return;
        
        // check the first project task
        $this->assertTask($projectTasks[0]);
    }
    
    /**
     * Test Case for Get All Project Task With Status Closed By Department Id 
     */
    public function testGetgetAllProjectTasksWithStatusClosedByDepartmentId()
    {
        $projectTasks = $this->client->getAllProjectTasksWithStatusByDepartmentId(TESTS_DEPARTMENT_ID, 'closed');
        if (empty($projectTasks))
            return;
        
        // check the first project task
        $this->assertTask($projectTasks[0]);
    }
    
    /**
     * Test Case for Get All Project Task With Status Rejected By Department Id 
     */
    public function testGetgetAllProjectTasksWithStatusRejectedByDepartmentId()
    {
        $projectTasks = $this->client->getAllProjectTasksWithStatusByDepartmentId(TESTS_DEPARTMENT_ID, 'rejected');
        if (empty($projectTasks))
            return;
        
        // check the first project task
        $this->assertTask($projectTasks[0]);
    }
    
    /**
     * Test Case for Get All Project Task With Status Cancelled By Department Id 
     */
    public function testGetgetAllProjectTasksWithStatusCancelledByDepartmentId()
    {
        $projectTasks = $this->client->getAllProjectTasksWithStatusByDepartmentId(TESTS_DEPARTMENT_ID, 'cancelled');
        if (empty($projectTasks))
            return;
        
        // check the first project task
        $this->assertTask($projectTasks[0]);
    }
    
    /**
     * Assert Task Object 
     * 
     * @param	\Cloudwords\Resources\Task $projectTask
     * @param	int		projectId
     * @param	array	params
     * @void
     */
    private function assertTask(\Cloudwords\Resources\Task $projectTask, $projectId = null, $params = null)
    {
        $this->assertTrue($projectTask instanceof \Cloudwords\Resources\Task);
        $this->assertTrue(is_int($projectTask->getId()));
            $this->assertTrue($projectTask->getType() instanceof \Cloudwords\Resources\CodeAndDisplay);
            $this->assertTrue($projectTask->getStatus() instanceof \Cloudwords\Resources\CodeAndDisplay);
        if ($projectTask->getProject() !== null && $projectId !== null) {
            $this->assertTrue($projectTask->getProject() instanceof \Cloudwords\Resources\Project);
            $this->assertEquals($projectId, $projectTask->getProject()->getId());
        }
        
        // Compare Task object with request params
        if ($params !== null) {
            $pTtargetLanguage = $projectTask->getTargetLanguage();
            $pTFollowers = $projectTask->getFollowers();
            $startDate = new \DateTime($params['startDate']);
            $dueDate = new \DateTime($params['dueDate']);
            $this->assertEquals($projectTask->getName(), $params['name']);
            $this->assertEquals($projectTask->getDescription(), $params['description']);
            $this->assertEquals($projectTask->getEmailReminderDay(), $params['emailReminderDay']);
            $this->assertEquals($projectTask->getStartDate(), $startDate);
            $this->assertEquals($projectTask->getDueDate(), $dueDate);
            $this->assertEquals($projectTask->getType()->getCode(), $params['type']);
            $this->assertEquals($projectTask->getAssignee()->getCustomerUser()->getId(),
                                $params['assignee']['customerUser']['id']);
            $this->assertEquals($pTtargetLanguage['languageCode'], $params['targetLanguage']['code']);
//            $this->assertEquals($pTFollowers[0]->getCustomerUser()->getId(), $params['followers'][0]['customerUser']['id']);
//            $this->assertEquals($pTFollowers[1]->getVendor()->getId(), $params['followers'][1]['vendor']['id']);
        }
    }

    /**
     * Assert Task Attachment Object 
     * 
     * @param	\Cloudwords\Resources\File $taskAttachment
     * @void
     */
    private function assertTaskAttachment($taskAttachment)
    {
        $this->assertTrue($taskAttachment instanceof \Cloudwords\Resources\File);
        $this->assertTrue(is_int($taskAttachment->getId()));   
        $this->assertEquals($taskAttachment->getFilename(), basename(TESTS_TASK_ATTACHMENT_PATH));
        $this->assertEquals($taskAttachment->getFileContents(), TESTS_TASK_ATTACHMENT_CONTENT);
    }
}
