<?php
namespace Cloudwords\Tests\Integration;

class ProjectTest extends \PHPUnit_Framework_TestCase
{
    const PROJECT_TO_REQUEST_BIDS_FROM_CLOUDWORDS_ID = 3;
    
    const PROJECT_TO_REQUEST_BIDS_FROM_PREFERRED_VENDORS_ID = 4;
    
    const PROJECT_TO_REQUEST_BIDS_FROM_PREFERRED_VENDORS_AUTOSELECT_ID = 5;
    
    protected $client;
    
    protected $params;
    
    private $projectToRequestBidsFromCloudwords;
    
    private $projectToRequestBidsFromPreferredVendors;
  
    private $projectToRequestBidsFromPreferredVendorsWithAutoSelection;
    
    public function setUp()
    {
        $this->client = new \Cloudwords\Client(TESTS_BASE_API_URL, TESTS_API_VERSION, TESTS_AUTH_TOKEN);
        $this->projectToRequestBidsFromCloudwords = $this->createProjectToRequestBidsFromCloudwords();
        $this->projectToRequestBidsFromPreferredVendors = $this->createProjectToRequestBidsFromPreferredVendors();
        $this->projectToRequestBidsFromPreferredVendorsWithAutoSelection = 
            $this->createProjectToRequestBidsFromPreferredVendorsWithAutoSelection();
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
     * @expectedException \Cloudwords\Exception
     *
     * This test should throw an exception because project metadata cannot be retrieved until source materials have been uploaded
     */
    public function testProjectMetadataRetrievalFailure()
    {
        $this->client->getProjectSource($this->projectToRequestBidsFromCloudwords->getId());
    }

    /**
     * @expectedException \Cloudwords\Exception
     *
     * This test should throw an exception because files need to be uploaded before they can be downloaded
     */
    public function testProjectFileDownloadFailure()
    {
        $this->client->downloadSourceFile($this->projectToRequestBidsFromCloudwords->getId());
    }

    /**
     * @expectedException \Cloudwords\Exception
     *
     * This test should throw an exception because files need to be provided for uploading
     */
    public function testProjectFileUploadFailure()
    {
        $this->client->uploadProjectSource($this->projectToRequestBidsFromCloudwords->getId(), "some_non_existent_file.zip");
    }
  
    /**
     * Test case for checking Open Projects exist
     */
    public function testOpenProjectsExist()
    {
        try {
            $projects = $this->client->getOpenProjects();
        } catch(\Cloudwords\Exception $e ) {
            echo $e;
        }
        
        $this->assertTrue(is_array($projects));
        $this->assertFalse(empty($projects));
    }

    /**
     * Test case for checking Closed Projects exist
     */
    public function testNoClosedProjectsExist()
    {
        try {
            $projects = $this->client->getClosedProjects();
        } catch(\Cloudwords\Exception $e) {
            echo $e;
        }
        
        $this->assertTrue(is_array($projects));
        $this->assertTrue(empty($projects));
    }
    
    /**
     * Test case for creating project successfully
     */
    public function testProjectCreatedSuccessfully()
    {
	    try {
	        $project = $this->client->createProject($this->projectToRequestBidsFromCloudwords->getParams());
	    } catch(\Cloudwords\Exception $e) {
	        echo $e->getErrorMessage();
	    }
        
	    $expectedState = array('name' => 'Test Project',
	                           'description' => 'Test Description',
	                           'notes' => 'Test Notes',
	                           'projectStatusCode' => 'configured_project_details',
	                           'projectStatusDisplay' => 'Configured Project Name',
	                           'poNumber' => '123456',
	                           'amount' => 0,
	                           'sourceLanguageCode' => 'en',
	                           'sourceLanguageDisplay' => 'English',
	                           'targetLanguageCodes' => array('de', 'fr'),
	                           'targetLanguageDisplay' => array('German', 'French'),
	                           'intendedUseCode' => TESTS_INTENDED_USE_ID,
	                           'intendedUseDisplay' => 'Documentation',
	                           'bidDueDate' => '2050-01-01T00:00:00.000+0000',
	                           'deliveryDueDate' => '2051-01-01T00:00:00.000+0000'
                              );
	    $this->assertProjectMetadata($project, $expectedState);
	    return $project->getId();
    }

    /**
     * Test case for getting open projects
     */
    public function testOpenProjectsExists()
    {
        try {
          $projects = $this->client->getOpenProjects();
        } catch (\Cloudwords\Exception $e ) {
          echo $e->getErrorMessage();
        }

        $this->assertNotNull($projects);
        $this->assertFalse(empty($projects));
    }
    

    /**
     * Test case for retrieving a project successfully
     * 
     * @depends	testProjectCreatedSuccessfully
     */
    public function testProjectRetrievedSuccessfully($projectId)
    {
        try {
            $project = $this->client->getProject($projectId);
        } catch(\Cloudwords\Exception $e) {
            echo $e->getErrorMessage();
        }

        $expectedState = array('name' => 'Test Project',
	                           'description' => 'Test Description',
	                           'notes' => 'Test Notes',
	                           'projectStatusCode' => 'configured_project_details',
	                           'projectStatusDisplay' => 'Configured Project Name',
	                           'poNumber' => '123456',
	                           'amount' => 0,
	                           'sourceLanguageCode' => 'en',
	                           'sourceLanguageDisplay' => 'English',
	                           'targetLanguageCodes' => array('de', 'fr'),
	                           'targetLanguageDisplay' => array('German', 'French'),
	                           'intendedUseCode' => TESTS_INTENDED_USE_ID,
	                           'intendedUseDisplay' => 'Documentation',
	                           'bidDueDate' => '2050-01-01T00:00:00.000+0000',
	                           'deliveryDueDate' => '2051-01-01T00:00:00.000+0000'
                              );
        $this->assertProjectMetadata($project, $expectedState);
        return $project->getId();
    }


    /**
     * Test case for updating a project successfully
     * 
     * @depends	testProjectRetrievedSuccessfully
     */
    public function testProjectUpdatedSuccessfully($projectId)
    {
        $updatedName = 'Test Project Updated Name';
        $updatedDescription = 'Test Description Updated';
        $updatedNotes = 'Test Notes Updated';
        $updatedIntendedUseCode = TESTS_INTENDED_USE_ID_UPDATED;
        $updatedIntendedUseDisplay = 'Marketing';
        $params = array('id' => $projectId,
                        'name' => $updatedName,
                        'description' => $updatedDescription,
                        'notes' => $updatedNotes,
                        'poNumber' => '123456',
                        'sourceLanguage' => 'en',
                        'targetLanguages' => array('de', 'fr'),
                        'intendedUse' => $updatedIntendedUseCode,
                        'bidDueDate' => '2050-01-01T00:00:00.000+0000',
                        'deliveryDueDate' => '2051-01-01T00:00:00.000+0000'
                       );
        $expectedState = array('name' => $updatedName,
                               'description' => $updatedDescription,
                               'notes' => $updatedNotes,
                               'projectStatusCode' => 'configured_project_details',
                               'projectStatusDisplay' => 'Configured Project Name',
                               'poNumber' => '123456',
                               'amount' => 0,
                               'sourceLanguageCode' => 'en',
                               'sourceLanguageDisplay' => 'English',
                               'targetLanguageCodes' => array('de', 'fr'),
                               'targetLanguageDisplay' => array('German', 'French'),
                               'intendedUseCode' => $updatedIntendedUseCode,
                               'intendedUseDisplay' => $updatedIntendedUseDisplay,
                               'bidDueDate' => '2050-01-01T00:00:00.000+0000',
                               'deliveryDueDate' => '2051-01-01T00:00:00.000+0000'
                              );
        
        try {
            $project = $this->client->updateProject($params);
        } catch(\Cloudwords\Exception $e) {
            echo $e->getErrorMessage();
        }
        
        $this->assertProjectMetadata($project, $expectedState);
    }
    
    /**
     * Test case for uploading a project source file successfully
     *
     * @depends	testProjectRetrievedSuccessfully
     */
    public function testUploadProjectSourceFileSuccessful($projectId)
    {
        try {
            $metadata = $this->client->uploadProjectSource($projectId, TESTS_TASK_ATTACHMENT_PATH );
        } catch(\Cloudwords\Exception $e) {
            echo $e->getErrorMessage();
        }

        $expectedState = array('filename' => basename(TESTS_TASK_ATTACHMENT_PATH),
                               'sizeInKilobytes' => TESTS_TASK_ATTACHEMENT_SIZE_KB,
                               'fileContents' => TESTS_TASK_ATTACHMENT_CONTENT,
                               'contentPath'  => TESTS_BASE_API_URL . '/' . TESTS_API_VERSION
                                              .  '/project/' . $projectId . '/file/source/content',
                               'path' => TESTS_BASE_API_URL . '/' . TESTS_API_VERSION
                                      .  '/project/' . $projectId . '/file/source.json');
        $this->assertProjectSourceMetadata($metadata, $expectedState);
    }
  
    /**
     * Test case for getting project source metadata 
     *
     * @depends	testProjectRetrievedSuccessfully
     */
    public function testGetProjectSourceMetadata($projectId)
    {
        try {
            $metadata = $this->client->getProjectSource($projectId);
        } catch(\Cloudwords\Exception $e) {
            echo $e->getMessage();
        }

        $expectedState = array('filename' => basename(TESTS_TASK_ATTACHMENT_PATH),
                               'sizeInKilobytes' => TESTS_TASK_ATTACHEMENT_SIZE_KB,
                               'fileContents' => TESTS_TASK_ATTACHMENT_CONTENT,
                               'contentPath'  => TESTS_BASE_API_URL . '/' . TESTS_API_VERSION . '/project/' . $projectId . '/file/source/content',
                               'path' =>  TESTS_BASE_API_URL . '/' . TESTS_API_VERSION  . '/project/' . $projectId . '/file/source.json');
        $this->assertProjectSourceMetadata($metadata, $expectedState);
    }

    /**
     * Test Case for downloading source file
     *  
     * @depends	testProjectRetrievedSuccessfully
     */
    public function testDownloadSourceFileSuccessful($projectId)
    {
        try {
            $source = $this->client->downloadSourceFile($projectId);
        } catch (\Cloudwords\Exception $e) {
            echo $e->getErrorMessage();
        }
    
        $sourceFile = TESTS_DOWNLOADED_SOURCE_FILE_PATH . '/' . time() . '.zip';
        file_put_contents($sourceFile, $source);
        $this->assertFileExists($sourceFile);
        $this->assertTrue(filesize($sourceFile) > 0);
    }
    

    /**
     * Test case for uploading a project reference successfully
     *
     * @depends	testProjectRetrievedSuccessfully
     */
    public function testUploadProjectReferenceFileSuccessful($projectId)
    {
	    try {
	        $reference = $this->client->uploadProjectReference($projectId, TESTS_TASK_ATTACHMENT_PATH);
	    } catch(\Cloudwords\Exception $e) {
	        echo $e->getErrorMessage();
	    }

        $expectedState = array('filename' => basename(TESTS_TASK_ATTACHMENT_PATH),
                               'sizeInKilobytes' => TESTS_TASK_ATTACHEMENT_SIZE_KB,
                               'fileContents' => TESTS_TASK_ATTACHMENT_CONTENT,
                               'contentPath'  => TESTS_BASE_API_URL . '/' . TESTS_API_VERSION . '/project/'
                                              .  $projectId . '/file/reference/' . $reference->getId() . '/content',
                               'path' => TESTS_BASE_API_URL . '/' . TESTS_API_VERSION  . '/project/'
                                      .  $projectId . '/file/reference/' . $reference->getId() . '.json'
                              );
	    $this->assertProjectReferenceMetadata($reference, $expectedState);
    }

    /**
     * Test case for getting a project reference
     *
     * @depends	testProjectRetrievedSuccessfully
     */
    public function testGetProjectReferences($projectId)
    {
	    try {
            $references = $this->client->getProjectReferences($projectId);
	    } catch(\Cloudwords\Exception $e) {
            echo $e->getErrorMessage();
	    }

        $return = array($projectId);
        if (count($references) > 0) {
	        $expectedState = array('filename' => basename(TESTS_TASK_ATTACHMENT_PATH),
	                               'sizeInKilobytes' => TESTS_TASK_ATTACHEMENT_SIZE_KB,
	                               'fileContents' => TESTS_TASK_ATTACHMENT_CONTENT,
	                               'contentPath'  => TESTS_BASE_API_URL . '/' . TESTS_API_VERSION . '/project/'
	                                              .  $projectId . '/file/reference/' . $references[0]->getId() . '/content',
	                               'path' => TESTS_BASE_API_URL . '/' . TESTS_API_VERSION  . '/project/'
	                                      .  $projectId . '/file/reference/' . $references[0]->getId() . '.json'
	                              );
		    $this->assertProjectReferenceMetadata($references[0], $expectedState);
            $return[] = $references[0]->getId();
        }

        return $return;
    }

    /**
     * Test case for getting a project reference
     *
     * @depends	testGetProjectReferences
     */
    public function testGetProjectReference($params)
    {
        // check documentId in params[1]
        if (! isset($params[1])) {
            return;
        } else {
            // extract params
            list($projectId, $documentId) = $params;
        }

	    try {
	        $reference = $this->client->getProjectReference($projectId, $documentId);
	    } catch(\Cloudwords\Exception $e) {
	        echo $e->getErrorMessage();
	    }

	    $expectedState = array('filename' => basename(TESTS_TASK_ATTACHMENT_PATH),
	                           'sizeInKilobytes' => TESTS_TASK_ATTACHEMENT_SIZE_KB,
	                           'fileContents' => TESTS_TASK_ATTACHMENT_CONTENT,
	                           'contentPath'  => TESTS_BASE_API_URL . '/' . TESTS_API_VERSION . '/project/'
	                                          .  $projectId . '/file/reference/' . $reference->getId() . '/content',
	                           'path' => TESTS_BASE_API_URL . '/' . TESTS_API_VERSION  . '/project/'
	                                  .  $projectId . '/file/reference/' . $reference->getId() . '.json'
	                          );
	    $this->assertProjectReferenceMetadata($reference, $expectedState);

        return $params;
    }

    /**
     * Test case for downloading project reference file
     *
     * @depends	testGetProjectReference
     */
    public function testDownloadReferenceFile($params)
    {
        list($projectId, $documentId) = $params;
	    try {
	        $reference = $this->client->downloadReferenceFile($projectId, $documentId);
	    } catch(\Cloudwords\Exception $e) {
	        echo $e->getErrorMessage();
	    }

        $referenceFile = TESTS_DOWNLOADED_REFERENCE_FILE_PATH . '/' . time() . '.zip';
	    file_put_contents($referenceFile, $reference);
	    $this->assertFileExists($referenceFile);
	    $this->assertTrue(filesize($referenceFile) > 0);
    }
    
    /**
     * Request bid for project
     * 
     * @depends	testProjectRetrievedSuccessfully
     */
    public function testRequestBidsForProjectFromCloudwords($projectId)
    {
        $preferredVendors = array(array('id' => TESTS_VENDOR_ID));
        $doLetCloudwordsChoose = false;
        $doAutoSelectBidFromVendor = false;
        
        try {
            $bidRequest = $this->client->requestBidsForProject($projectId, $preferredVendors, $doLetCloudwordsChoose, $doAutoSelectBidFromVendor);
        } catch(\Cloudwords\Exception $e) {
            echo $e->getErrorMessage(), PHP_EOL;
        }
        
        $this->assertTrue($bidRequest instanceof \Cloudwords\Resources\BidRequest);
        $this->assertFalse($bidRequest->getDoLetCloudwordsChoose());
        $this->assertFalse($bidRequest->getDoAutoSelectBidFromVendor());
        $this->assertTrue(is_array($bidRequest->getPreferredVendors()));
        $this->assertEquals($bidRequest->getPath(), TESTS_BASE_API_URL . '/' . TESTS_API_VERSION . '/project/'
                                                  . $projectId . '/bid-request/current.json');
    }

    /**
     * Test case for updating project reference file
     *
     * @depends	testGetProjectReference
     */
    public function testUpdateProjectReferenceSuccessful($params)
    {
        list($projectId, $documentId) = $params;
	    try {
	        $reference = $this->client->updateProjectReference($projectId, $documentId, TESTS_TASK_ATTACHMENT_PATH);
	    } catch(\Cloudwords\Exception $e) {
	        echo $e->getErrorMessage();
	    }

	    $expectedState = array('filename' => basename(TESTS_TASK_ATTACHMENT_PATH),
	                           'sizeInKilobytes' => TESTS_TASK_ATTACHEMENT_SIZE_KB,
	                           'fileContents' => TESTS_TASK_ATTACHMENT_CONTENT,
	                           'contentPath'  => TESTS_BASE_API_URL . '/' . TESTS_API_VERSION . '/project/'
	                                          .  $projectId . '/file/reference/' . $reference->getId() . '/content',
	                           'path' => TESTS_BASE_API_URL . '/' . TESTS_API_VERSION  . '/project/'
	                                  .  $projectId . '/file/reference/' . $reference->getId() . '.json'
	                          );
	    $this->assertProjectReferenceMetadata($reference, $expectedState);
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
            $this->assertTrue(is_int($project->getDepartment()->getId()));
            $this->assertTrue(is_string($project->getDepartment()->getName()));
        }
    }
    
    /**
     * Create project object to request bids from cloudwords 
     */
    private function createProjectToRequestBidsFromCloudwords()
    {
        $params = array('id' => self::PROJECT_TO_REQUEST_BIDS_FROM_CLOUDWORDS_ID,
                        'name' => 'Test Project',
                        'description' => 'Test Description',
                        'notes' => 'Test Notes',
                    	'poNumber' => '123456',
                        'intendedUse' => TESTS_INTENDED_USE_ID,
                        'sourceLanguage' => 'en',
                        'targetLanguages' => array('fr', 'de'),
                        'bidDueDate' => '2050-01-01T00:00:00.000+0000',
                        'deliveryDueDate' => '2051-01-01T00:00:00.000+0000'
                       );
        return new \Cloudwords\Resources\Project($params);
    }

    /**
     * Create project object to request bids from preferred vendor with auto selection
     */
    private function createProjectToRequestBidsFromPreferredVendorsWithAutoSelection()
    {
        $params = array('id' => self::PROJECT_TO_REQUEST_BIDS_FROM_PREFERRED_VENDORS_AUTOSELECT_ID,
                        'name' => 'Project In Bid Out To Preferred Vendors Autoselect',
                        'description' => 'Test Description',
                        'notes' => 'Test Notes',
                        'poNumber' => '123456',
                        'intendedUse' => TESTS_INTENDED_USE_ID,
                        'sourceLanguage' => 'en',
                    	'targetLanguages' => array('es'),
                    	'bidDueDate' => '2050-01-01T00:00:00.000+0000',
                    	'deliveryDueDate' => '2051-01-01T00:00:00.000+0000');
        return new \Cloudwords\Resources\Project($params);
    }

    /**
     * Create project object to request bids from preferred vendor
     */
    private function createProjectToRequestBidsFromPreferredVendors()
    {
        $params = array('id' => self::PROJECT_TO_REQUEST_BIDS_FROM_PREFERRED_VENDORS_ID,
                        'name' => 'Project In Bid Out To Preferred Vendors',
                        'description' => 'Test Description',
                        'notes' => 'Test Notes',
                        'poNumber' => '123456',
                        'intendedUse' => TESTS_INTENDED_USE_ID,
                        'sourceLanguage' => 'en',
                        'targetLanguages' => array('es'),
                        'bidDueDate' => '2050-01-01T00:00:00.000+0000',
                        'deliveryDueDate' => '2051-01-01T00:00:00.000+0000'
    	               );
        return new \Cloudwords\Resources\Project($params);
    }
    
    /**
     *  
     * @param \Cloudwords\Resource\Project	$project
     * @param array	 $expectedState
     */
    private function assertProjectMetadata($project, $expectedState)
    {
	    $this->assertNotNull($project);
	    $this->assertEquals($expectedState['name'], $project->getName());
	    $this->assertEquals($expectedState['description'], $project->getDescription());
	    $this->assertEquals($expectedState['notes'], $project->getNotes());
	    $this->assertEquals($expectedState['projectStatusCode'], $project->getStatus()->getCode());
	    $this->assertEquals($expectedState['projectStatusDisplay'], $project->getStatus()->getDisplay());
	    $this->assertEquals($expectedState['poNumber'], $project->getPoNumber());
	    $this->assertEquals($expectedState['amount'], $project->getAmount());
	    $this->assertEquals($expectedState['sourceLanguageCode'], $project->getSourceLanguage()->getLanguageCode());
	    $this->assertEquals($expectedState['sourceLanguageDisplay'], $project->getSourceLanguage()->getDisplay());
	    $targetLanguages = $project->getTargetLanguages();
	    $this->assertTrue(is_array($targetLanguages));
	    $this->assertEquals(2, count($targetLanguages));
	    $language = $targetLanguages[0];
	    $this->assertEquals($expectedState['targetLanguageCodes'][0], $language->getLanguageCode());
	    $this->assertEquals($expectedState['targetLanguageDisplay'][0], $language->getDisplay());
	    $language = $targetLanguages[1];
	    $this->assertEquals($expectedState['targetLanguageCodes'][1], $language->getLanguageCode());
	    $this->assertEquals($expectedState['targetLanguageDisplay'][1], $language->getDisplay());
	    $this->assertEquals($expectedState['intendedUseCode'], $project->getIntendedUse()->getId());
	    $this->assertEquals($expectedState['intendedUseDisplay'], $project->getIntendedUse()->getName());
	    $projectDetailsUrl = TESTS_BASE_API_URL . '/' . TESTS_API_VERSION . '/project/' . $project->getId() . '.json';
	    $this->assertEquals($projectDetailsUrl, $project->getPath());
    }

    /**
     * Assert project source metadata
     */
    private function assertProjectSourceMetadata($metadata, $expectedState)
    {
	    $this->assertNotNull($metadata);
	    $this->assertEquals($expectedState['filename'], $metadata->getFilename());
	    $this->assertEquals($expectedState['sizeInKilobytes'], $metadata->getSizeInKilobytes());
	    $this->assertTrue(strstr($metadata->getFileContents(), $expectedState['fileContents']) != '');
	    $this->assertEquals($expectedState['contentPath'], $metadata->getContentPath());
	    $this->assertEquals($expectedState['path'], $metadata->getPath());
    }

    /**
     * Assert project reference metadata
     */
    private function assertProjectReferenceMetadata($metadata, $expectedState)
    {
	    $this->assertNotNull($metadata);
	    $this->assertEquals($expectedState['filename'], $metadata->getFilename());
	    $this->assertEquals($expectedState['sizeInKilobytes'], $metadata->getSizeInKilobytes());
	    $this->assertTrue(strstr($metadata->getFileContents(), $expectedState['fileContents']) != '');
	    $this->assertEquals($expectedState['contentPath'], $metadata->getContentPath());
	    $this->assertEquals($expectedState['path'], $metadata->getPath());
    }

    /**
     * Assert project approved translated metadata
     */
    private function assertProjectApprovedTranslatedMetadata($metadata, $expectedState)
    {
	    $this->assertNotNull($metadata);
	    $this->assertEquals($expectedState['filename'], $metadata->getFilename());
	    $this->assertEquals($expectedState['sizeInKilobytes'], $metadata->getSizeInKilobytes());
	    $this->assertTrue(strstr($metadata->getFileContents(), $expectedState['fileContents']) != '');
	    $this->assertEquals($expectedState['contentPath'], $metadata->getContentPath());
	    $this->assertEquals($expectedState['path'], $metadata->getPath());
	    $this->assertEquals($expectedState['languageDisplay'], $metadata->getLang()->getDisplay());
	    $this->assertEquals($expectedState['languageCode'], $metadata->getLang()->getLanguageCode());
	    $this->assertEquals($expectedState['languageStatusDisplay'], $metadata->getStatus()->getDisplay());
	    $this->assertEquals($expectedState['languageStatusCode'], $metadata->getStatus()->getCode());
    }

    /**
     * Assert project translated metadata
     */
    private function assertProjectTranslatedMetadata($metadata, $expectedState)
    {
        $this->assertNotNull($metadata);
        $this->assertEquals($expectedState['filename'], $metadata->getFilename());
        $this->assertEquals($expectedState['sizeInKilobytes'], $metadata->getSizeInKilobytes());
        $this->assertTrue(strstr($metadata->getFileContents(), $expectedState['fileContents']) != '');
        $this->assertEquals($expectedState['contentPath'], $metadata->getContentPath());
        $this->assertEquals($expectedState['path'], $metadata->getPath());
        $this->assertEquals($expectedState['languageDisplay'], $metadata->getLang()->getDisplay());
        $this->assertEquals($expectedState['languageCode'], $metadata->getLang()->getLanguageCode());
    }
}
