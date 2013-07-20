<?php

/*
 * Copyright 2011, Cloudwords, Inc.
 *
 * Licensed under the API LICENSE AGREEMENT, Version 1.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.cloudwords.com/developers/license-1.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

require_once "bootstrap.php";

class CloudwordsClientTest extends PHPUnit_Framework_TestCase {

  const BASE_API_URL = 'http://localhost:8100';
  const API_VERSION = '1';
  const AUTHENTICATION_TOKEN = 'UserToken 3f922fe48eeef471a7aaaa2409ad152f9c22a7f24f946d10ae5d4e035d7d3baf';
  const DATABASE_NAME = 'cw_php_client';

  const PROJECT_WITH_SUBMITTED_BIDS_ID = 1;
  const PROJECT_WITH_TRANSLATED_MATERIALS_ID = 2;
  const PROJECT_TO_REQUEST_BIDS_FROM_CLOUDWORDS_ID = 3;
  const PROJECT_TO_REQUEST_BIDS_FROM_PREFERRED_VENDORS_ID = 4;
  const PROJECT_TO_REQUEST_BIDS_FROM_PREFERRED_VENDORS_AUTOSELECT_ID = 5;

  private $client;
  private $projectToRequestBidsFromCloudwords;
  private $projectToRequestBidsFromPreferredVendors;
  private $projectToRequestBidsFromPreferredVendorsWithAutoSelection;

  /**
   * Setup functions
   */

  // function used for setup once prior to running all tests
  public static function setUpBeforeClass() {
    self::initializeDatabaseViaSystemCall();
  }

  // function used for clean up once after running all tests
  public static function tearDownAfterClass() {}

  // function used for setup prior to every test run
  public function setUp() {
    $this->client = new CloudwordsClient(self::BASE_API_URL, self::API_VERSION, self::AUTHENTICATION_TOKEN);
    $this->initializeTestBed();
  }

  // function used for clean up after every test run
  public function tearDown() {
    $this->client = NULL;
  }

  // function to import static data into the database that contains a single provisioned customer and vendor
  private static function initializeDatabaseViaSystemCall() {
    $command = 'mysql -u root ' . self::DATABASE_NAME . ' < ' . DATA_DIR . '/' . self::DATABASE_NAME . '.sql';
    system($command);
  }

  // function to create test projects
  private function initializeTestBed() {
    $this->projectToRequestBidsFromCloudwords = $this->createProjectToRequestBidsFromCloudwords();
    $this->projectToRequestBidsFromPreferredVendors = $this->createProjectToRequestBidsFromPreferredVendors();
    $this->projectToRequestBidsFromPreferredVendorsWithAutoSelection = $this->createProjectToRequestBidsFromPreferredVendorsWithAutoSelection();
  }

  private function createProjectToRequestBidsFromCloudwords() {
    $params = array('id' => self::PROJECT_TO_REQUEST_BIDS_FROM_CLOUDWORDS_ID,
                    'name' => 'Test Project',
                    'description' => 'Test Description',
                    'notes' => 'Test Notes',
                    'poNumber' => '123456',
                    'intendedUse' => 1,
                    'sourceLanguage' => 'en',
                    'targetLanguages' => array('fr', 'de'),
                    'bidDueDate' => '2050-01-01T00:00:00.000+0000',
                    'deliveryDueDate' => '2051-01-01T00:00:00.000+0000');
    return new CloudwordsProject($params);
  }

  private function createProjectToRequestBidsFromPreferredVendorsWithAutoSelection() {
    $params = array('id' => self::PROJECT_TO_REQUEST_BIDS_FROM_PREFERRED_VENDORS_AUTOSELECT_ID,
                    'name' => 'Project In Bid Out To Preferred Vendors Autoselect',
                    'description' => 'Test Description',
                    'notes' => 'Test Notes',
                    'poNumber' => '123456',
                    'intendedUse' => 1,
                    'sourceLanguage' => 'en',
                    'targetLanguages' => array('es'),
                    'bidDueDate' => '2050-01-01T00:00:00.000+0000',
                    'deliveryDueDate' => '2051-01-01T00:00:00.000+0000');
    return new CloudwordsProject($params);
  }

  private function createProjectToRequestBidsFromPreferredVendors() {
    $params = array('id' => self::PROJECT_TO_REQUEST_BIDS_FROM_PREFERRED_VENDORS_ID,
                    'name' => 'Project In Bid Out To Preferred Vendors',
                    'description' => 'Test Description',
                    'notes' => 'Test Notes',
                    'poNumber' => '123456',
                    'intendedUse' => 1,
                    'sourceLanguage' => 'en',
                    'targetLanguages' => array('es'),
                    'bidDueDate' => '2050-01-01T00:00:00.000+0000',
                    'deliveryDueDate' => '2051-01-01T00:00:00.000+0000');
    return new CloudwordsProject($params);
  }

  /**
   * Test functions
   */

  public function testClientInitialized() {
    $this->assertNotNull($this->client);
    $this->assertEquals(self::BASE_API_URL . '/' . self::API_VERSION, $this->client->getBaseUrlWithVersion());
    $this->assertEquals(self::AUTHENTICATION_TOKEN, $this->client->getAuthToken());
    $this->assertEquals(30, $this->client->getConnectionTimeout());
    $this->assertEquals(60, $this->client->getSocketTimeout());
    $this->assertEquals(3, $this->client->getMaxTotalConnections());
  }

  /**
   * @expectedException CloudwordsApiException
   *
   * This test should throw an exception because project metadata cannot be retrieved until source materials have been uploaded
   */
  public function testProjectMetadataRetrievalFailure() {
    $this->client->get_project_source($this->projectToRequestBidsFromCloudwords->getId());
  }

  /**
   * @expectedException CloudwordsApiException
   *
   * This test should throw an exception because files need to be uploaded before they can be downloaded
   */
  public function testProjectFileDownloadFailure() {
    $this->client->download_source_file($this->projectToRequestBidsFromCloudwords->getId());
  }

  /**
   * @expectedException CloudwordsApiException
   *
   * This test should throw an exception because files need to be provided for uploading
   */
  public function testProjectFileUploadFailure() {
    $this->client->upload_project_source($this->projectToRequestBidsFromCloudwords->getId(), "some_non_existent_file.zip");
  }

  public function testOpenProjectsExist() {
    try {
      $projects = $this->client->get_open_projects();
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $this->assertTrue(is_array($projects));
    $this->assertFalse(empty($projects));
  }

  public function testNoClosedProjectsExist() {
    try {
      $projects = $this->client->get_closed_projects();
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $this->assertTrue(is_array($projects));
    $this->assertTrue(empty($projects));
  }

  public function testAllSourceLanguagesExist() {
    try {
      $source_languages = $this->client->get_source_languages();
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $this->assertTrue(is_array($source_languages));
    $this->assertAllLanguagesExist($source_languages);
  }

  public function testAllTargetLanguagesExist() {
    try {
      $target_languages = $this->client->get_target_languages();
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $this->assertTrue(is_array($target_languages));
    $this->assertAllLanguagesExist($target_languages);
  }

  public function testAllIntendedUsesExist() {
    try {
      $intended_uses = $this->client->get_intended_uses();
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $this->assertTrue(is_array($intended_uses));
    $this->assertAllIntendedUsesExist($intended_uses);
  }

  public function testProjectCreatedSuccessfully() {
    try {
      $project = $this->client->create_project($this->projectToRequestBidsFromCloudwords->getParams());
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $expected_state = array('id' => 3,
                            'name' => 'Test Project',
                            'description' => 'Test Description',
                            'notes' => 'Test Notes',
                            'projectStatusCode' => 'configured_project_details',
                            'projectStatusDisplay' => 'Configured Project Details',
                            'poNumber' => '123456',
                            'amount' => 0,
                            'sourceLanguageCode' => 'en',
                            'sourceLanguageDisplay' => 'English',
                            'targetLanguageCodes' => array('de', 'fr'),
                            'targetLanguageDisplay' => array('German', 'French'),
                            'intendedUseCode' => 1,
                            'intendedUseDisplay' => 'Documentation',
                            'bidDueDate' => '2050-01-01T00:00:00.000+0000',
                            'deliveryDueDate' => '2051-01-01T00:00:00.000+0000');
    $this->assertProjectMetadata($project, $expected_state);
  }

  public function testOpenProjectsExists() {
    try {
      $projects = $this->client->get_open_projects();
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $this->assertNotNull($projects);
    $this->assertFalse(empty($projects));
  }

  public function testProjectRetrievedSuccessfully() {
    try {
      $project = $this->client->get_project($this->projectToRequestBidsFromCloudwords->getId());
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $expected_state = array('id' => 3,
                            'name' => 'Test Project',
                            'description' => 'Test Description',
                            'notes' => 'Test Notes',
                            'projectStatusCode' => 'configured_project_details',
                            'projectStatusDisplay' => 'Configured Project Details',
                            'poNumber' => '123456',
                            'amount' => 0,
                            'sourceLanguageCode' => 'en',
                            'sourceLanguageDisplay' => 'English',
                            'targetLanguageCodes' => array('de', 'fr'),
                            'targetLanguageDisplay' => array('German', 'French'),
                            'intendedUseCode' => 1,
                            'intendedUseDisplay' => 'Documentation',
                            'bidDueDate' => '2050-01-01T00:00:00.000+0000',
                            'deliveryDueDate' => '2051-01-01T00:00:00.000+0000');
    $this->assertProjectMetadata($project, $expected_state);
  }

  public function testProjectUpdatedSuccessfully() {
    $updated_name = 'Test Project Updated Name';
    $updated_description = 'Test Description Updated';
    $updated_notes = 'Test Notes Updated';
    $updated_intended_use_code = 2;
    $updated_intended_use_display = 'Marketing';
    $params = array('id' => $this->projectToRequestBidsFromCloudwords->getId(),
                    'name' => $updated_name,
                    'description' => $updated_description,
                    'notes' => $updated_notes,
                    'poNumber' => '123456',
                    'sourceLanguage' => 'en',
                    'targetLanguages' => array('de', 'fr'),
                    'intendedUse' => $updated_intended_use_code,
                    'bidDueDate' => '2050-01-01T00:00:00.000+0000',
                    'deliveryDueDate' => '2051-01-01T00:00:00.000+0000');
    $expected_state = array('id' => 3,
                            'name' => $updated_name,
                            'description' => $updated_description,
                            'notes' => $updated_notes,
                            'projectStatusCode' => 'configured_project_details',
                            'projectStatusDisplay' => 'Configured Project Details',
                            'poNumber' => '123456',
                            'amount' => 0,
                            'sourceLanguageCode' => 'en',
                            'sourceLanguageDisplay' => 'English',
                            'targetLanguageCodes' => array('de', 'fr'),
                            'targetLanguageDisplay' => array('German', 'French'),
                            'intendedUseCode' => $updated_intended_use_code,
                            'intendedUseDisplay' => $updated_intended_use_display,
                            'bidDueDate' => '2050-01-01T00:00:00.000+0000',
                            'deliveryDueDate' => '2051-01-01T00:00:00.000+0000');
    try {
      $project = $this->client->update_project($params);
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $this->assertProjectMetadata($project, $expected_state);
  }

  public function testUploadProjectSourceFileSuccessful() {
    $zip_filename = 'sample_zip_file.zip';
    $zip_file = DATA_DIR . '/' . $zip_filename;
    try {
      $metadata = $this->client->upload_project_source($this->projectToRequestBidsFromCloudwords->getId(), $zip_file);
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $expected_state = array('id' => 5,
                            'filename' => $zip_filename,
                            'sizeInKilobytes' => 8,
                            'fileContents' => 'inner_dir_file.txt',
                            'contentPath' => self::BASE_API_URL . '/' . self::API_VERSION . '/project/' . $this->projectToRequestBidsFromCloudwords->getId() . '/file/source/content',
                            'path' => self::BASE_API_URL . '/' . self::API_VERSION . '/project/' . $this->projectToRequestBidsFromCloudwords->getId() . '/file/source.json');
    $this->assertProjectSourceMetadata($metadata, $expected_state);
  }

  public function testGetProjectMetadata() {
    try {
      $metadata = $this->client->get_project_source($this->projectToRequestBidsFromCloudwords->getId());
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $expected_state = array('id' => 5,
                            'filename' => 'sample_zip_file.zip',
                            'sizeInKilobytes' => 8,
                            'fileContents' => 'inner_dir_file.txt',
                            'contentPath' => self::BASE_API_URL . '/' . self::API_VERSION . '/project/' . $this->projectToRequestBidsFromCloudwords->getId() . '/file/source/content',
                            'path' => self::BASE_API_URL . '/' . self::API_VERSION . '/project/' . $this->projectToRequestBidsFromCloudwords->getId() . '/file/source.json');
    $this->assertProjectSourceMetadata($metadata, $expected_state);
  }

  public function testDownloadSourceFileSuccessful() {
    try {
      $source = $this->client->download_source_file($this->projectToRequestBidsFromCloudwords->getId());
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $source_file = DATA_DIR . '/source_file.zip';
    file_put_contents($source_file, $source);
    $this->assertFileExists($source_file);
    $this->assertTrue(filesize($source_file) > 0);
  }

  public function testUploadProjectReferenceFileSuccessful() {
    $zip_filename = 'zip_docs_with_more_than_1000_characters.zip';
    $zip_file = DATA_DIR . '/' . $zip_filename;
    try {
      $reference = $this->client->upload_project_reference($this->projectToRequestBidsFromCloudwords->getId(), $zip_file);
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $expected_state = array('id' => 6,
                            'filename' => $zip_filename,
                            'sizeInKilobytes' => 15,
                            'fileContents' => 'splunk_queries.txt',
                            'contentPath' => self::BASE_API_URL . '/' . self::API_VERSION . '/project/' . $this->projectToRequestBidsFromCloudwords->getId() . '/file/reference/' . $reference->getId() . '/content',
                            'path' => self::BASE_API_URL . '/' . self::API_VERSION . '/project/' . $this->projectToRequestBidsFromCloudwords->getId() . '/file/reference/' . $reference->getId() . '.json');
    $this->assertProjectReferenceMetadata($reference, $expected_state);
  }

  public function testGetProjectReferences() {
    try {
      $references = $this->client->get_project_references($this->projectToRequestBidsFromCloudwords->getId());
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $expected_state = array('id' => 6,
                            'filename' => 'zip_docs_with_more_than_1000_characters.zip',
                            'sizeInKilobytes' => 15,
                            'fileContents' => 'splunk_queries',
                            'contentPath' => self::BASE_API_URL . '/' . self::API_VERSION . '/project/' . $this->projectToRequestBidsFromCloudwords->getId() . '/file/reference/' . $references[0]->getId() . '/content',
                            'path' => self::BASE_API_URL . '/' . self::API_VERSION . '/project/' . $this->projectToRequestBidsFromCloudwords->getId() . '/file/reference/' . $references[0]->getId() . '.json');
    $this->assertProjectReferenceMetadata($references[0], $expected_state);
  }

  public function testGetProjectReference() {
    $document_id = 6;
    try {
      $reference = $this->client->get_project_reference($this->projectToRequestBidsFromCloudwords->getId(), $document_id);
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $expected_state = array('id' => $document_id,
                            'filename' => 'zip_docs_with_more_than_1000_characters.zip',
                            'sizeInKilobytes' => 15,
                            'fileContents' => 'splunk_queries',
                            'contentPath' => self::BASE_API_URL . '/' . self::API_VERSION . '/project/' . $this->projectToRequestBidsFromCloudwords->getId() . '/file/reference/' . $reference->getId() . '/content',
                            'path' => self::BASE_API_URL . '/' . self::API_VERSION . '/project/' . $this->projectToRequestBidsFromCloudwords->getId() . '/file/reference/' . $reference->getId() . '.json');
    $this->assertProjectReferenceMetadata($reference, $expected_state);
  }

  public function testDownloadReferenceFile() {
    $document_id = 6;
    try {
      $reference = $this->client->download_reference_file($this->projectToRequestBidsFromCloudwords->getId(), $document_id);
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $reference_file = DATA_DIR . '/reference_file.zip';
    file_put_contents($reference_file, $reference);
    $this->assertFileExists($reference_file);
    $this->assertTrue(filesize($reference_file) > 0);
  }

  public function testUpdateProjectReferenceSuccessful() {
    $document_id = 6;
    $zip_filename = 'windows_characters.zip';
    $zip_file = DATA_DIR . '/' . $zip_filename;
    try {
      $reference = $this->client->update_project_reference($this->projectToRequestBidsFromCloudwords->getId(), $document_id, $zip_file);
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $expected_state = array('id' => $document_id,
                            'filename' => $zip_filename,
                            'sizeInKilobytes' => 1,
                            'fileContents' => 'reembolso.txt',
                            'contentPath' => self::BASE_API_URL . '/' . self::API_VERSION . '/project/' . $this->projectToRequestBidsFromCloudwords->getId() . '/file/reference/' . $reference->getId() . '/content',
                            'path' => self::BASE_API_URL . '/' . self::API_VERSION . '/project/' . $this->projectToRequestBidsFromCloudwords->getId() . '/file/reference/' . $reference->getId() . '.json');
    $this->assertProjectReferenceMetadata($reference, $expected_state);
  }

  public function testGetVendor() {
    $vendor_id = 3001;
    try {
      $vendor = $this->client->get_vendor($vendor_id);
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $expected_state = array('id' => 3001, 
                            'name' => 'Vendor 0001', 
                            'path' => self::BASE_API_URL . '/' . self::API_VERSION . '/vendor/' . $vendor_id . '.json');
    $this->assertVendorMetadata($vendor, $expected_state);
  }

  public function testGetPreferredVendors() {
    $preferred_vendor_id = 3001;
    try {
      $vendors = $this->client->get_preferred_vendors();
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $expected_state = array('id' => 3001, 
                            'name' => 'Vendor 0001', 
                            'path' => self::BASE_API_URL . '/' . self::API_VERSION . '/vendor/' . $preferred_vendor_id . '.json');
    $this->assertVendorMetadata($vendors[0], $expected_state);
  }

  public function testRequestBidsForProjectFromCloudwords() {
    $preferred_vendors = array();
    $do_let_cloudwords_choose = true;
    $do_auto_select_bid_from_vendor = false;
    try {
      $bid_request = $this->client->request_bids_for_project($this->projectToRequestBidsFromCloudwords->getId(), $preferred_vendors, $do_let_cloudwords_choose, $do_auto_select_bid_from_vendor);
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $this->assertTrue($bid_request->getDoLetCloudwordsChoose());
    $this->assertFalse($bid_request->getDoAutoSelectBidFromVendor());
    $preferred_vendors = $bid_request->getPreferredVendors();
    $this->assertTrue(empty($preferred_vendors));
    $this->assertEquals(self::BASE_API_URL . '/' . self::API_VERSION . '/project/' . $this->projectToRequestBidsFromCloudwords->getId() . '/bid-request/current.json', $bid_request->getPath());
  }

  public function testGetSubmittedBid() {
    $project_id = self::PROJECT_WITH_SUBMITTED_BIDS_ID;
    $bid_id = 1;
    try {
      $submitted_bid = $this->client->get_bid($project_id, $bid_id);
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $vendor_id = 3002;
    $expected_state = array('id' => $bid_id,
                            'vendor_id' => $vendor_id,
                            'vendor_name' => 'Vendor 0002',
                            'path' => self::BASE_API_URL . '/' . self::API_VERSION . '/vendor/' . $vendor_id . '.json',
                            'bid_status_code' => 'submitted',
                            'bid_status_display' => 'Vendor Submitted',
                            'bid_description' => '',
                            'bid_amount' => 2991);
    $this->assertBidMetadata($submitted_bid, $expected_state);
  }

  public function testGetAllSubmittedBids() {
    $project_id = self::PROJECT_WITH_SUBMITTED_BIDS_ID;
    try {
      $submitted_bids = $this->client->get_bids($project_id);
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $bid_id = 2;
    $vendor_id = 3001;
    $submitted_bid = $submitted_bids[0];
    $expected_state = array('id' => $bid_id,
                            'vendor_id' => $vendor_id,
                            'vendor_name' => 'Vendor 0001',
                            'path' => self::BASE_API_URL . '/' . self::API_VERSION . '/vendor/' . $vendor_id . '.json',
                            'bid_status_code' => 'submitted',
                            'bid_status_display' => 'Vendor Submitted',
                            'bid_description' => '',
                            'bid_amount' => 2991);
    $this->assertBidMetadata($submitted_bid, $expected_state);
    $bid_id = 1;
    $vendor_id = 3002;
    $submitted_bid = $submitted_bids[1];
    $expected_state = array('id' => $bid_id,
                            'vendor_id' => $vendor_id,
                            'vendor_name' => 'Vendor 0002',
                            'path' => self::BASE_API_URL . '/' . self::API_VERSION . '/vendor/' . $vendor_id . '.json',
                            'bid_status_code' => 'submitted',
                            'bid_status_display' => 'Vendor Submitted',
                            'bid_description' => '',
                            'bid_amount' => 2991);
    $this->assertBidMetadata($submitted_bid, $expected_state);
  }

  public function testSelectWinningBid() {
    $project_id = self::PROJECT_WITH_SUBMITTED_BIDS_ID;
    $bid_id = 2;
    try {
      $winning_bid = $this->client->select_winning_bid($project_id, $bid_id);
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $vendor_id = 3001;
    $expected_state = array('winning_bid_id' => $bid_id,
                            'do_let_cloudwords_choose' => true,
                            'do_auto_select_bid_from_vendor' => false,
                            'path' => self::BASE_API_URL . '/' . self::API_VERSION . '/project/' . $project_id . '/bid-request/current.json',
                            'vendor_id' => $vendor_id,
                            'vendor_name' => 'Vendor 0001',
                            'vendor_path' => self::BASE_API_URL . '/' . self::API_VERSION . '/vendor/' . $vendor_id . '.json');
    $this->assertBidRequestMetadata($winning_bid, $expected_state);
  }

  public function testGetCurrentBidRequestForProject() {
    try {
      $bid_request = $this->client->get_current_bid_request_for_project($this->projectToRequestBidsFromCloudwords->getId());
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $expected_state = array('do_let_cloudwords_choose' => true,
                            'do_auto_select_bid_from_vendor' => false,
                            'path' => self::BASE_API_URL . '/' . self::API_VERSION . '/project/' . $this->projectToRequestBidsFromCloudwords->getId() . '/bid-request/current.json');
    $this->assertBidRequestMetadata($bid_request, $expected_state);
  }

  public function testRequestBidsForProjectFromPreferredVendors() {
    try {
      // create new project
      $project = $this->client->create_project($this->projectToRequestBidsFromPreferredVendors->getParams());

      // upload source materials
      $zip_file = DATA_DIR . '/' . 'sample_zip_file.zip';
      $source_file = $this->client->upload_project_source($project->getId(), $zip_file);

      // request bids for project from preferred vendors
      $preferred_vendors = array(3001);
      $do_let_cloudwords_choose = false;
      $do_auto_select_bid_from_vendor = false;
      $bid_request = $this->client->request_bids_for_project($this->projectToRequestBidsFromPreferredVendors->getId(), $preferred_vendors, $do_let_cloudwords_choose, $do_auto_select_bid_from_vendor);

      $preferred_vendor_id = 3001;
      $expected_state = array('do_let_cloudwords_choose' => false,
                              'do_auto_select_bid_from_vendor' => false,
                              'path' => self::BASE_API_URL . '/' . self::API_VERSION . '/project/' . $project->getId() . '/bid-request/current.json',
                              'vendor_id' => $preferred_vendor_id,
                              'vendor_name' => 'Vendor 0001',
                              'vendor_path' => self::BASE_API_URL . '/' . self::API_VERSION . '/vendor/' . $preferred_vendor_id . '.json');
      $this->assertBidRequestMetadata($bid_request, $expected_state);

    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
  }

  public function testRequestBidsForProjectFromPreferredVendorWithAutoSelectionEnabled() {
    try {
      // create new project
      $project = $this->client->create_project($this->projectToRequestBidsFromPreferredVendorsWithAutoSelection->getParams());

      // upload source materials
      $zip_file = DATA_DIR . '/' . 'sample_zip_file.zip';
      $source_file = $this->client->upload_project_source($project->getId(), $zip_file);

      // request bids for project from preferred vendors with auto selection
      $preferred_vendors = array(3001);
      $do_let_cloudwords_choose = false;
      $do_auto_select_bid_from_vendor = true;
      $bid_request = $this->client->request_bids_for_project($this->projectToRequestBidsFromPreferredVendorsWithAutoSelection->getId(), $preferred_vendors, $do_let_cloudwords_choose, $do_auto_select_bid_from_vendor);

      $preferred_vendor_id = 3001;
      $expected_state = array('do_let_cloudwords_choose' => false,
                              'do_auto_select_bid_from_vendor' => true,
                              'path' => self::BASE_API_URL . '/' . self::API_VERSION . '/project/' . $this->projectToRequestBidsFromPreferredVendorsWithAutoSelection->getId() . '/bid-request/current.json',
                              'vendor_id' => $preferred_vendor_id,
                              'vendor_name' => 'Vendor 0001',
                              'vendor_path' => self::BASE_API_URL . '/' . self::API_VERSION . '/vendor/' . $preferred_vendor_id . '.json');
      $this->assertBidRequestMetadata($bid_request, $expected_state);

    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
  }

  public function testApproveTranslatedMaterials() {
    $project_id = self::PROJECT_WITH_TRANSLATED_MATERIALS_ID;
    $language = 'es';
    try {
      $translated_file_metadata = $this->client->approve_translated_file($project_id, $language);
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $expected_state = array('id' => 4,
                            'filename' => 'spanish_website.zip',
                            'sizeInKilobytes' => 1,
                            'fileContents' => 'node-zbmwkc-11.xml',
                            'contentPath' => self::BASE_API_URL . '/' . self::API_VERSION . '/project/' . $project_id . '/file/translated/language/' . $language . '/content',
                            'path' => self::BASE_API_URL . '/' . self::API_VERSION . '/project/' . $project_id . '/file/translated/language/' . $language . '.json',
                            'languageDisplay' => 'Spanish',
                            'languageCode' => 'es',
                            'languageStatusDisplay' => 'Approved',
                            'languageStatusCode' => 'approved');
    $this->assertProjectApprovedTranslatedMetadata($translated_file_metadata, $expected_state);
  }

  public function testGetProjectTranslatedFiles() {
    $project_id = self::PROJECT_WITH_TRANSLATED_MATERIALS_ID;
    $language = 'es';
    try {
      $translated_files_metadata = $this->client->get_project_translated_files($project_id);
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $expected_state = array('id' => 4,
                            'filename' => 'spanish_website.zip',
                            'sizeInKilobytes' => 1,
                            'fileContents' => 'node-zbmwkc-11.xml',
                            'contentPath' => self::BASE_API_URL . '/' . self::API_VERSION . '/project/' . $project_id . '/file/translated/language/' . $language . '/content',
                            'path' => self::BASE_API_URL . '/' . self::API_VERSION . '/project/' . $project_id . '/file/translated/language/' . $language . '.json',
                            'languageDisplay' => 'Spanish',
                            'languageCode' => 'es');
    $this->assertProjectTranslatedMetadata($translated_files_metadata[0], $expected_state);
  }

  public function testGetProjectTranslatedFile() {
    $project_id = self::PROJECT_WITH_TRANSLATED_MATERIALS_ID;
    $language = 'es';
    try {
      $translated_file_metadata = $this->client->get_project_translated_file($project_id, $language);
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $expected_state = array('id' => 4,
                            'filename' => 'spanish_website.zip',
                            'sizeInKilobytes' => 1,
                            'fileContents' => 'node-zbmwkc-11.xml',
                            'contentPath' => self::BASE_API_URL . '/' . self::API_VERSION . '/project/' . $project_id . '/file/translated/language/' . $language . '/content',
                            'path' => self::BASE_API_URL . '/' . self::API_VERSION . '/project/' . $project_id . '/file/translated/language/' . $language . '.json',
                            'languageDisplay' => 'Spanish',
                            'languageCode' => 'es');
    $this->assertProjectTranslatedMetadata($translated_file_metadata, $expected_state);
  }

  public function testDownloadTranslatedFile() {
    $project_id = self::PROJECT_WITH_TRANSLATED_MATERIALS_ID;
    $language = 'es';
    try {
      $translated = $this->client->download_translated_file($project_id, $language);
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $translated_file = DATA_DIR . '/translated_file.zip';
    file_put_contents($translated_file, $translated);
    $this->assertFileExists($translated_file);
    $this->assertTrue(filesize($translated_file) > 0);
  }

  // TODO wait for a master translated file to get created and verify one can be downloaded
  /*
  public function testGetMasterProjectTranslatedFile() {
    $translated_file = $client->get_master_project_translated_file($this->projectToRequestBidsFromCloudwords->getId());
  }

  public function testDownloadMasterTranslatedFile() {
    $project = $client->download_master_translated_file($this->projectToRequestBidsFromCloudwords->getId());
  }
  */

  // TODO create a closed project and verify that one exists
  /*
  public function testClosedProjectsExists() {
    try {
      $projects = $this->client->get_closed_projects();
    } catch( CloudwordsApiException $e ) {
      echo $e;
    }
    $this->assertNotNull($projects);
    $this->assertFalse(empty($projects));
  }
  */

  /**
   * Assert utility functions
   */

  private function assertAllLanguagesExist($languages) {
    $this->assertNotNull($languages);
    $this->assertEquals(36, count($languages));
    for( $index = 0; $index < count($languages); $index++ ) {
      $language = $languages[$index];
      $language_display = $language->getDisplay();
      $language_code = $language->getLanguageCode();
      switch( $index ) {
        case 0:
          $this->assertEquals("Arabic", $language_display);
          $this->assertEquals("ar", $language_code);
          break;
        case 1:
          $this->assertEquals("Bulgarian", $language_display);
          $this->assertEquals("bg", $language_code);
          break;
        case 2:
          $this->assertEquals("Czech", $language_display);
          $this->assertEquals("cs", $language_code);
          break;
        case 3:
          $this->assertEquals("Danish", $language_display);
          $this->assertEquals("da", $language_code);
          break;
        case 4:
          $this->assertEquals("German", $language_display);
          $this->assertEquals("de", $language_code);
          break;
        case 5:
          $this->assertEquals("Greek", $language_display);
          $this->assertEquals("el", $language_code);
          break;
        case 6:
          $this->assertEquals("English", $language_display);
          $this->assertEquals("en", $language_code);
          break;
        case 7:
          $this->assertEquals("English (UK)", $language_display);
          $this->assertEquals("en-gb", $language_code);
          break;
        case 8:
          $this->assertEquals("English (US)", $language_display);
          $this->assertEquals("en-us", $language_code);
          break;
        case 9:
          $this->assertEquals("Spanish", $language_display);
          $this->assertEquals("es", $language_code);
          break;
        case 10:
          $this->assertEquals("Spanish (Spain)", $language_display);
          $this->assertEquals("es-es", $language_code);
          break;
        case 11:
          $this->assertEquals("Spanish (Mexico)", $language_display);
          $this->assertEquals("es-mx", $language_code);
          break;
        case 12:
          $this->assertEquals("Estonian", $language_display);
          $this->assertEquals("et", $language_code);
          break;
        case 13:
          $this->assertEquals("Finnish", $language_display);
          $this->assertEquals("fi", $language_code);
          break;
        case 14:
          $this->assertEquals("French", $language_display);
          $this->assertEquals("fr", $language_code);
          break;
        case 15:
          $this->assertEquals("French (Canada)", $language_display);
          $this->assertEquals("fr-ca", $language_code);
          break;
        case 16:
          $this->assertEquals("French (France)", $language_display);
          $this->assertEquals("fr-fr", $language_code);
          break;
        case 17:
          $this->assertEquals("Hungarian", $language_display);
          $this->assertEquals("hu", $language_code);
          break;
        case 18:
          $this->assertEquals("Italian", $language_display);
          $this->assertEquals("it", $language_code);
          break;
        case 19:
          $this->assertEquals("Japanese", $language_display);
          $this->assertEquals("ja", $language_code);
          break;
        case 20:
          $this->assertEquals("Korean", $language_display);
          $this->assertEquals("ko", $language_code);
          break;
        case 21:
          $this->assertEquals("Lithuanian", $language_display);
          $this->assertEquals("lt", $language_code);
          break;
        case 22:
          $this->assertEquals("Latvian", $language_display);
          $this->assertEquals("lv", $language_code);
          break;
        case 23:
          $this->assertEquals("Dutch", $language_display);
          $this->assertEquals("nl", $language_code);
          break;
        case 24:
          $this->assertEquals("Norwegian", $language_display);
          $this->assertEquals("no", $language_code);
          break;
        case 25:
          $this->assertEquals("Polish", $language_display);
          $this->assertEquals("pl", $language_code);
          break;
        case 26:
          $this->assertEquals("Portuguese", $language_display);
          $this->assertEquals("pt", $language_code);
          break;
        case 27:
          $this->assertEquals("Portuguese (Brazil)", $language_display);
          $this->assertEquals("pt-br", $language_code);
          break;
        case 28:
          $this->assertEquals("Portuguese (Portugal)", $language_display);
          $this->assertEquals("pt-pt", $language_code);
          break;
        case 29:
          $this->assertEquals("Romanian (Romania)", $language_display);
          $this->assertEquals("ro", $language_code);
          break;
        case 30:
          $this->assertEquals("Russian (Russia)", $language_display);
          $this->assertEquals("ru", $language_code);
          break;
        case 31:
          $this->assertEquals("Swedish", $language_display);
          $this->assertEquals("sv", $language_code);
          break;
        case 32:
          $this->assertEquals("Thai", $language_display);
          $this->assertEquals("th", $language_code);
          break;
        case 33:
          $this->assertEquals("Turkish", $language_display);
          $this->assertEquals("tr", $language_code);
          break;
        case 34:
          $this->assertEquals("Chinese (Simplified)", $language_display);
          $this->assertEquals("zh-cn", $language_code);
          break;
        case 35:
          $this->assertEquals("Chinese (Traditional)", $language_display);
          $this->assertEquals("zh-tw", $language_code);
          break;
      }
    }
  }

  private function assertAllIntendedUsesExist($intended_uses) {
    $this->assertNotNull($intended_uses);
    $this->assertEquals(5, count($intended_uses));
    for( $index = 0; $index < count($intended_uses); $index++ ) {
      $intended_use = $intended_uses[$index];
      $intended_use_name = $intended_use->getName();
      $intended_use_id = $intended_use->getId();
      switch( $index ) {
        case 0:
          $this->assertEquals("Documentation", $intended_use_name);
          $this->assertEquals(1, $intended_use_id);
          break;
        case 1:
          $this->assertEquals("Marketing", $intended_use_name);
          $this->assertEquals(2, $intended_use_id);
          break;
        case 2:
          $this->assertEquals("Training", $intended_use_name);
          $this->assertEquals(3, $intended_use_id);
          break;
        case 3:
          $this->assertEquals("UI", $intended_use_name);
          $this->assertEquals(4, $intended_use_id);
          break;
        case 4:
          $this->assertEquals("Website", $intended_use_name);
          $this->assertEquals(5, $intended_use_id);
          break;
      }
    }
  }

  private function assertProjectMetadata($project, $expected_state) {
    $this->assertNotNull($project);
    $this->assertEquals($expected_state['id'], $project->getId());
    $this->assertEquals($expected_state['name'], $project->getName());
    $this->assertEquals($expected_state['description'], $project->getDescription());
    $this->assertEquals($expected_state['notes'], $project->getNotes());
    $this->assertEquals($expected_state['projectStatusCode'], $project->getStatus()->getCode());
    $this->assertEquals($expected_state['projectStatusDisplay'], $project->getStatus()->getDisplay());
    $this->assertEquals($expected_state['poNumber'], $project->getPoNumber());
    $this->assertEquals($expected_state['amount'], $project->getAmount());
    $this->assertEquals($expected_state['sourceLanguageCode'], $project->getSourceLanguage()->getLanguageCode());
    $this->assertEquals($expected_state['sourceLanguageDisplay'], $project->getSourceLanguage()->getDisplay());
    $target_languages = $project->getTargetLanguages();
    $this->assertTrue(is_array($target_languages));
    $this->assertEquals(2, count($target_languages));
    $language = $target_languages[0];
    $this->assertEquals($expected_state['targetLanguageCodes'][0], $language->getLanguageCode());
    $this->assertEquals($expected_state['targetLanguageDisplay'][0], $language->getDisplay());
    $language = $target_languages[1];
    $this->assertEquals($expected_state['targetLanguageCodes'][1], $language->getLanguageCode());
    $this->assertEquals($expected_state['targetLanguageDisplay'][1], $language->getDisplay());
    $this->assertEquals($expected_state['intendedUseCode'], $project->getIntendedUse()->getId());
    $this->assertEquals($expected_state['intendedUseDisplay'], $project->getIntendedUse()->getName());
    $project_details_url = self::BASE_API_URL . '/' . self::API_VERSION . '/project/' . $project->getId() . '.json';
    $this->assertEquals($project_details_url, $project->getPath());
  }

  private function assertProjectSourceMetadata($metadata, $expected_state) {
    $this->assertNotNull($metadata);
    $this->assertEquals($expected_state['id'], $metadata->getId());
    $this->assertEquals($expected_state['filename'], $metadata->getFilename());
    $this->assertEquals($expected_state['sizeInKilobytes'], $metadata->getSizeInKilobytes());
    $this->assertTrue(strstr($metadata->getFileContents(), $expected_state['fileContents']) != '');
    $this->assertEquals($expected_state['contentPath'], $metadata->getContentPath());
    $this->assertEquals($expected_state['path'], $metadata->getPath());
  }

  private function assertProjectReferenceMetadata($metadata, $expected_state) {
    $this->assertNotNull($metadata);
    $this->assertEquals($expected_state['id'], $metadata->getId());
    $this->assertEquals($expected_state['filename'], $metadata->getFilename());
    $this->assertEquals($expected_state['sizeInKilobytes'], $metadata->getSizeInKilobytes());
    $this->assertTrue(strstr($metadata->getFileContents(), $expected_state['fileContents']) != '');
    $this->assertEquals($expected_state['contentPath'], $metadata->getContentPath());
    $this->assertEquals($expected_state['path'], $metadata->getPath());
  }

  private function assertProjectApprovedTranslatedMetadata($metadata, $expected_state) {
    $this->assertNotNull($metadata);
    $this->assertEquals($expected_state['id'], $metadata->getId());
    $this->assertEquals($expected_state['filename'], $metadata->getFilename());
    $this->assertEquals($expected_state['sizeInKilobytes'], $metadata->getSizeInKilobytes());
    $this->assertTrue(strstr($metadata->getFileContents(), $expected_state['fileContents']) != '');
    $this->assertEquals($expected_state['contentPath'], $metadata->getContentPath());
    $this->assertEquals($expected_state['path'], $metadata->getPath());
    $this->assertEquals($expected_state['languageDisplay'], $metadata->getLang()->getDisplay());
    $this->assertEquals($expected_state['languageCode'], $metadata->getLang()->getLanguageCode());
    $this->assertEquals($expected_state['languageStatusDisplay'], $metadata->getStatus()->getDisplay());
    $this->assertEquals($expected_state['languageStatusCode'], $metadata->getStatus()->getCode());
  }

  private function assertProjectTranslatedMetadata($metadata, $expected_state) {
    $this->assertNotNull($metadata);
    $this->assertEquals($expected_state['id'], $metadata->getId());
    $this->assertEquals($expected_state['filename'], $metadata->getFilename());
    $this->assertEquals($expected_state['sizeInKilobytes'], $metadata->getSizeInKilobytes());
    $this->assertTrue(strstr($metadata->getFileContents(), $expected_state['fileContents']) != '');
    $this->assertEquals($expected_state['contentPath'], $metadata->getContentPath());
    $this->assertEquals($expected_state['path'], $metadata->getPath());
    $this->assertEquals($expected_state['languageDisplay'], $metadata->getLang()->getDisplay());
    $this->assertEquals($expected_state['languageCode'], $metadata->getLang()->getLanguageCode());
  }

  private function assertVendorMetadata($metadata, $expected_state) {
    $this->assertEquals($expected_state['id'], $metadata->getId());
    $this->assertEquals($expected_state['name'], $metadata->getName());
    $this->assertEquals($expected_state['path'], $metadata->getPath());
  }

  private function assertBidMetadata($metadata, $expected_state) {
    $this->assertNotNull($metadata);
    $this->assertEquals($expected_state['id'], $metadata->getId());
    $this->assertEquals($expected_state['vendor_id'], $metadata->getVendor()->getId());
    $this->assertEquals($expected_state['vendor_name'], $metadata->getVendor()->getName());
    $this->assertEquals($expected_state['path'], $metadata->getVendor()->getPath());
    $this->assertEquals($expected_state['bid_status_code'], $metadata->getStatus()->getCode());
    $this->assertEquals($expected_state['bid_status_display'], $metadata->getStatus()->getDisplay());
    $this->assertEquals($expected_state['bid_description'], $metadata->getDescription());
    $this->assertEquals($expected_state['bid_amount'], $metadata->getAmount());
  }

  private function assertBidRequestMetadata($metadata, $expected_state) {
    $this->assertEquals($expected_state['do_let_cloudwords_choose'], $metadata->getDoLetCloudwordsChoose());
    $this->assertEquals($expected_state['do_auto_select_bid_from_vendor'], $metadata->getDoAutoSelectBidFromVendor());
    $this->assertEquals($expected_state['path'], $metadata->getPath());
    if( $metadata->getPreferredVendors() ) {
      $preferred_vendors = $metadata->getPreferredVendors();
      $preferred_vendor = $preferred_vendors[0];
      $this->assertEquals($expected_state['vendor_id'], $preferred_vendor->getId());
      $this->assertEquals($expected_state['vendor_name'], $preferred_vendor->getName());
      $this->assertEquals($expected_state['vendor_path'], $preferred_vendor->getPath());
    }
    if( $metadata->getWinningBidId() ) {
      $this->assertEquals($expected_state['winning_bid_id'], $metadata->getWinningBidId());
    }
  }

  /**
   * Private utility methods
   */

  private function startsWith($haystack, $needle) {
    return strncmp($haystack, $needle, strlen($needle)) == 0;
  }

  private function endsWith($haystack, $needle) {
   return $this->startsWith(strrev($haystack), strrev($needle));
  }

}

