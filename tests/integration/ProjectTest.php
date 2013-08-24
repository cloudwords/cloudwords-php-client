<?php
namespace Cloudwords\Tests;

class ProjectTest extends \PHPUnit_Framework_TestCase
{
    protected $client;
    
    protected $params;
    
    public function setUp()
    {
        $this->client = new \Cloudwords\Client(TESTS_BASE_API_URL, TESTS_API_VERSION, TESTS_AUTH_TOKEN);
    }
    
    public function tearDown()
    {
        
    }

    /**
     * Test Case for Get Project
     */
    public function testGetProject()
    {
        $project = $this->client->getProject(TESTS_PROJECT_ID);
        $this->assertProject($project);
    }
    
    /**
     * Test Case for Get Open Projects Filter By Department ID 
     */
    public function testGetOpenProjectsByDepartmentID()
    {
        $projects = $this->client->getOpenProjectsByDepartmentId(TESTS_DEPARTMENT_ID);
        $this->assertTrue(is_array($projects));
        if (empty($projects))
            return;
        
        // check the first project
        $this->assertProject($projects[0]);
    }
    
	/**
     * Test Case for Get Closed Projects Filter By Department ID 
     */
    public function testGetClosedProjectsByDepartmentID()
    {
        $projects = $this->client->getClosedProjectsByDepartmentId(TESTS_DEPARTMENT_ID);
        $this->assertTrue(is_array($projects));
        if (empty($projects))
            return;
        
        // check the first project
        $this->assertProject($projects[0]);
    }
    
    /**
     * Assert Project Object
     * 
     * @param \Cloudwords\Resources\Project $project
     */
    private function assertProject(\Cloudwords\Resources\Project $project)
    {
        $this->assertTrue($project instanceof \Cloudwords\Resources\Project);
        $this->assertTrue(is_int($project->getId()));
        $this->assertTrue(is_string($project->getName()));
        $this->assertTrue(is_string($project->getNotes()));
        $this->assertTrue($project->getIntendedUse() instanceof \Cloudwords\Resources\IntendedUse);
        $this->assertTrue($project->getSourceLanguage() instanceof \Cloudwords\Resources\Language);
        $this->assertTrue(is_array($project->getTargetLanguages()));
        $this->assertTrue($project->getOwner() instanceof  \Cloudwords\Resources\User);
        $this->assertTrue(is_array($project->getFollowers()));
        $this->assertTrue($project->getStatus() instanceof \Cloudwords\Resources\CodeAndDisplay);
        $this->assertTrue($project->getBidDueDate() instanceof \DateTime);
        $this->assertTrue($project->getDeliveryDueDate() instanceof \DateTime);
        $this->assertTrue($project->getCreatedDate() instanceof \DateTime);
        $this->assertTrue($project->getBidSelectDeadlineDate() instanceof \DateTime);
        $this->assertTrue(is_int($project->getAmount()));
        if ($project->getDepartment() instanceof \Cloudwords\Resources\Department) {
            $this->assertEquals($project->getDepartment()->getId(), TESTS_DEPARTMENT_ID);
            $this->assertTrue(is_string($project->getDepartment()->getName()));
        }
    }
}