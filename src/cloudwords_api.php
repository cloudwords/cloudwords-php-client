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

/**
 * Client interface for the Cloudwords API. For more details and best practices please refer to the
 * Cloudwords API documentation found at http://www.cloudwords.com/developers.
 *
 * This API is primarily designed to:
 *
 * - Create and update a Cloudwords project
 * - Upload and download of a project's source content and reference materials
 * - Request bids for a project
 * - Download translated content (both by individual language and via the aggragated "master deliverable" that contains all deliverables across all languages)
 * 
 * @author Douglas Kim <doug@cloudwords.com>
 * @since 1.0
 */
interface CloudwordsAPI {

  /**
   * Get the list of open projects in Cloudwords.
   * 
   * @return array The list of open customer project resources
   * @throws CloudwordsApiException
   */
  public function get_open_projects();

  /**
   * Get the list of closed projects in Cloudwords.
   * 
   * @return array The list of closed customer project resources
   * @throws CloudwordsApiException
   */
  public function get_closed_projects();

  /**
   * Get the Cloudwords project associated with the unique Cloudwords project id.
   * 
   * @param integer $project_id The project id
   *
   * @return CloudwordsProject The project resource
   * @throws CloudwordsApiException
   */
  public function get_project($project_id);

  /**
   * Create a new Cloudwords project.
   * 
   * @param array $project The project resource to be created
   *
   * @return CloudwordsProject The newly created project resource
   * @throws CloudwordsApiException
   */
  public function create_project($project);

  /**
   * Update the project specified in the input project id with the content represented by the input. 
   * Please note that there are various restrictions as to what and when a project can be updated in 
   * Cloudwords. Please refer to API documentation for more detail.
   * 
   * @param array $project The project to update
   *
   * @return CloudwordsProject The updated project resource
   * @throws CloudwordsApiException
   */
  public function update_project($project);

  /**
   * Upload the source material for a given Cloudwords project. The provided file must be a zip file.
   * <p>
   * Please note that this project must already exists in Cloudwords, and the project must be in a state 
   * where source material can be uploaded. If source material for this project already exists, then this 
   * operation will overwrite the source material associated with the project. Please refer to the API 
   * documentation for more information.
   * </p>
   * 
   * @param integer $project_id The id of the project to upload source for
   * @param string $zip_file The zip file to be uploaded for this project
   *
   * @return CloudwordsFile The uploaded source material for a Cloudwords file resource
   * @throws CloudwordsApiException
   */
  public function upload_project_source($project_id, $zip_file);

  /**
   * Get the project's source material for a Cloudwords file resource for the specified Cloudwords project id. 
   * The metadata will contain a contentPath for downloading the actual binary file.
   * 
   * @param integer $project_id The id of the project to return source materials for
   *
   * @return CloudwordsFile The project source for the Cloudwords file resource
   * @throws CloudwordsApiException
   */
  public function get_project_source($project_id);

  /**
   * Download the source material binary for the specified project, provided that a source material exists for the project.
   * 
   * @param integer $project_id The id of project to download source for
   *
   * @return resource The binary content of the file
   * @throws CloudwordsApiException
   */
  public function download_source_file($project_id);

  /**
   * Upload and add a new reference material for a given Cloudwords project. The provided file must be a zip file.
   * <p>
   * Please note that this project must already exists in Cloudwords, and the project must be in a state 
   * where reference material can be uploaded. Please refer to the API documentation for more information.
   * </p>
   * 
   * @param integer $project_id The id of the project to upload reference materials for
   * @param string $zip_file A zip file to be uploaded for this project
   *
   * @return CloudwordsFile The uploaded reference material for the Cloudwords file resource
   * @throws CloudwordsApiException
   */
  public function upload_project_reference($project_id, $zip_file);

  /**
   * Upload and replace project reference material for the specified Cloudwords project and specific 
   * reference material. The provided file must be a zip file.
   * <p>
   * Please note that this project must already exists in Cloudwords, and the project must be in a state where 
   * reference material can be uploaded. Please refer to the API documentation for more information.
   * </p>
   * 
   * @param integer $project_id The id of the project whose reference materials are to be updated
   * @param integer $document_id The id of the specific reference material to update
   * @param string $zip_file A zip file to be uploaded for this project
   *
   * @return CloudwordsFile The updated reference material for a Cloudwords file resource
   * @throws CloudwordsApiException
   */
  public function update_project_reference($project_id, $document_id, $zip_file);

  /**
   * Get the list of project reference material metadata for the specified Cloudwords project. Each item in the 
   * returned list will contain a contentPath for downloading the respective binary file.
   * 
   * @param integer $project_id The id of the project to return reference materials for
   *
   * @return array The list of project reference material Cloudwords file resources
   * @throws CloudwordsApiException
   */
  public function get_project_references($project_id);

  /**
   * Get the specified project reference material metadata. The metadata will contain a contentPath for 
   * downloading the actual binary file.
   * 
   * @param integer $project_id The id of the project to get reference material for
   * @param integer $document_id The id of the specific reference material to return
   *
   * @return CloudwordsFile The reference material for the Cloudwords file resource
   * @throws CloudwordsApiException
   */
  public function get_project_reference($project_id, $document_id);

  /**
   * Download the specified project reference material binary, provided that such reference material exists for the project.
   * 
   * @param integer $project_id The id of the project to download reference material for
   * @param integer $document_id The id of the specific reference material to download
   *
   * @return resource The file if reference file exists
   * @throws CloudwordsApiException
   */
  public function download_reference_file($project_id, $document_id);

  /**
   * Get the current master project translation file metadata, including a contentPath for downloading the actual binary file. 
   * The master project translation file contains all of the current project language(s) translations in one bundled zip. To get an
   * individual language translation file's metadata, use get_project_translated_file(int, language).
   * 
   * @param integer $project_id The id of the project to get the master file for
   *
   * @return CloudwordsFile The master translated Cloudwords file resource
   * @throws CloudwordsApiException
   */
  public function get_master_project_translated_file($project_id);

  /**
   * Get the list of current project language translation files' metadata for the specified Cloudwords project. Each metadata 
   * representation in the list will be specific to a project target language and contain a contentPath for downloading the 
   * respective binary file.
   * 
   * @param integer $project_id The id of the project to get the translated files for
   *
   * @return array The list of translated project Cloudwords language file resources
   * @throws CloudwordsApiException
   */
  public function get_project_translated_files($project_id);

  /**
   * Get the the current translated project file metadata for the specified Cloudwords project and target language. The returned 
   * metadata will contain a contentPath for downloading the actual binary files.
   * 
   * @param integer $project_id The id of the project to get the translated file for
   * @param string $language The specific language
   *
   * @return CloudwordsFile The translated project file for the specified language
   * @throws CloudwordsApiException
   */
  public function get_project_translated_file($project_id, $language);

  /**
   * Download the current master translated material binary for the specified Cloudwords project, provided that such translated 
   * material exists for the project. The master project translation file contains all of the current project language(s) 
   * translation in one bundled zip. To download an individual language translation file, use download_translated_file(int, language).
   * 
   * @param integer $project_id The id of the project to download the master translated file for
   *
   * @return resource The file if reference file exists
   * @throws CloudwordsApiException
   */
  public function download_master_translated_file($project_id);

  /**
   * Download the specified translated material binary for the specified Cloudwords project and target language, provided that such
   * translated material exists for the project.
   * 
   * @param integer $project_id The id of the project to get the translated file for
   * @param string $language The specific language
   *
   * @return resource The file if reference file exists
   * @throws CloudwordsApiException
   */
  public function download_translated_file($project_id, $language);

  /**
   * Approve the delivered language file for the specified Cloudwords project and target language. 
   * 
   * @param integer $project_id The id of the project to get the translated file for
   * @param string $language The specific language
   *
   * @return CloudwordsLanguageFile The project file for the specified language
   * @throws CloudwordsApiException
   */
  public function approve_translated_file($project_id, $language);

  /**
   * Convenience method to download the binary content of a Cloudwords file, provided that the contentPath inside the metadata is valid.
   * 
   * @param array $metadata The specific file to download content for
   *
   * @return resource The file if the file exists
   * @throws CloudwordsApiException
   */
  public function download_file_from_metadata($metadata);

  /**
   * Request bids for the specified project. In order to request a bid for a project, a source material must be 
   * already uploaded for the project, and the project must be in a state where requesting bids is allowed.
   * <p>
   * Additionally, you must either specify the list of Cloudwords preferred vendor(s) to request bids from or to 
   * allow Cloudwords to automatically find vendors that best match the project requirements, or both.
   * </p>
   * Please refer to the API documentation for more information.
   * 
   * @param integer $project_id The id of the project to request bids for
   * @param array $preferred_vendors The list of vendors to request bids from
   * @param boolean $do_let_cloudwords_choose Allow Cloudwords to find vendor that best match the project
   * @param boolean $do_auto_select_bid_from_vendor Auto select bid from vendor
   *
   * @return CloudwordsBidRequest The created bid request resource
   * @throws CloudwordsApiException
   */
  public function request_bids_for_project($project_id, $preferred_vendors, $do_let_cloudwords_choose, $do_auto_select_bid_from_vendor);

  /**
   * Get the current bid request for the specified project.
   * 
   * @param integer $project_id The id of the project to get the bid request for
   *
   * @return CloudwordsBidRequest The current bid request resource for the project if bids have already been requested for this project
   * @throws CloudwordsApiException
   */
  public function get_current_bid_request_for_project($project_id);

  /** 
   * Get the list of bids for the specified project. 
   *  
   * @param integer $project_id The id of the project to retrieve bids for
   *
   * @return array The list of bids for the specified project 
   * @throws CloudwordsException 
   */ 
  public function get_bids($project_id);

  /** 
   * Get the specified bid for the specified project. 
   *  
   * @param integer $project_id The id of the project to retrieve a bid for
   * @param integer $bid_id The id of the bid that is being selected  
   *
   * @return CloudwordsBid The specified bid for the specified project. 
   * @throws CloudwordsApiException 
   */ 
  public function get_bid($project_id, $bid_id);

  /** 
   * Select the winning bid for the specified project. 
   *  
   * @param integer $project_id The id of the project to select a winning bid for 
   * @param integer $bid_id The id of the bid that is being selected  
   *
   * @return CloudwordsBid The winning bid for the project  
   * @throws CloudwordsApiException 
   */ 
  public function select_winning_bid($project_id, $bid_id);

  /**
   * Get the list of preferred vendors currently configured in Cloudwords.
   * 
   * @return array The list of preferred vendor resources
   * @throws CloudwordsApiException
   */
  public function get_preferred_vendors();

  /**
   * Get the list of source languages currently configured in Cloudwords.
   * 
   * @return array The list of customer source language resources
   * @throws CloudwordsApiException
   */
  public function get_source_languages();

  /**
   * Get the list of target languages currently configured in Cloudwords.
   * 
   * @return array The list of customer target language resources
   * @throws CloudwordsApiException
   */
  public function get_target_languages();

  /**
   * Get the list of target intended uses currently configured in Cloudwords.
   * 
   * @return array The list of customer intended use resources
   * @throws CloudwordsApiException
   */
  public function get_intended_uses();

  /**
   * Get the Cloudwords vendor by the unique Cloudwords vendor id.
   * 
   * @param integer $vendor_id The id of the vendor
   *
   * @return CloudwordsVendor The specified vendor
   * @throws CloudwordsApiException
   */
  public function get_vendor($vendor_id);
}

/**
 * Exception thrown when a call to the Cloudwords API returns an exception.
 *
 * @author Douglas Kim <doug@cloudwords.com>
 * @since 1.0
 */
class CloudwordsApiException extends Exception {

  const API_EXCEPTION = 'api_exception';
  const UNSUPPORTED_CONTENT_TYPE_EXCEPTION = 'unsupported_content_type_exception';
  const REQUEST_EXCEPTION = 'request_exception';
  const DEPENDENCY_EXCEPTION = 'dependency_exception';

  private $exception_type;

  private $http_status_code;

  private $request_type;

  private $request_url;

  private $error_message;

  private $content_type;

  public function __construct($exception_type, $params) {
    $this->exception_type = $exception_type;
    if( $exception_type == self::API_EXCEPTION ) {
      $this->http_status_code = $params['http_status_code'];
      $this->request_type = $params['request_type'];
      $this->request_url = $params['request_url'];
      $this->error_message = $params['error_message'];
    } else if( $exception_type == self::UNSUPPORTED_CONTENT_TYPE_EXCEPTION ) {
      $this->content_type = $params['content_type'];
    } else if( $exception_type == self::REQUEST_EXCEPTION ) {
      $this->error_message = $params['error_message'];
    } else if( $exception_type == self::DEPENDENCY_EXCEPTION ) {
      $this->error_message = $params['error_message'];
    }
  }

  public function getExceptionType() {
    return $this->exception_type;
  }

  public function getHttpStatusCode() {
    return $this->http_status_code;
  }

  public function getRequestType() {
    return $this->request_type;
  }

  public function getRequestUrl() {
    return $this->request_url;
  }

  public function getErrorMessage() {
    return $this->error_message;
  }

  public function getContentType() {
    return $this->content_type;
  }

  public function __toString() {
    if( $this->exception_type == self::API_EXCEPTION) {
      return "Received HTTP status code " . $this->http_status_code . " from " . $this->request_type . " request at " . $this->request_url . "\n" . 
             "Error: " . $this->error_message . "\n";
    } else if( $this->exception_type == self::UNSUPPORTED_CONTENT_TYPE_EXCEPTION ) {
      return "Unsupported content type '" . $this->content_type . "'\n";
    } else if( $this->exception_type == self::REQUEST_EXCEPTION ) {
      return "Malformed request : " . $this->error_message . "\n";
    } else if( $this->exception_type == self::DEPENDENCY_EXCEPTION ) {
      return $this->error_message . "\n";
    }
  }

}

/**
 * Represents a bid provided by a vendor for a given Cloudwords project.
 * 
 * @author Douglas Kim <doug@cloudwords.com>
 * @since 1.0
 */
class CloudwordsBid {

  private $id;
  private $vendor;
  private $status;
  private $description;
  private $amount;
  private $bidItems;

  /**
   * Constructor used to create a Cloudwords bid request
   *
   * - id: string The unique id associated with this bid
   * - vendor: array The vendor associated with this bid
   * - status: array The bid status
   * - description: string The description provided by the vendor for this bid
   * - amount: string The total amount of this bid
   * - bidItems: array The bidItem associated with this bid
   *
   * @param array $params The parameters used to initialize a bid instance
   */
  public function __construct($params) {
    if( isset($params['id']) ) {
      $this->id = $params['id'];
    }
    if( isset($params['vendor']) ) {
      $this->vendor = new CloudwordsVendor($params['vendor']);
    }
    if( isset($params['status']) ) {
      $this->status = new CloudwordsBidStatus($params['status']);
    }
    if( isset($params['description']) ) {
      $this->description = $params['description'];
    }
    if( isset($params['amount']) ) {
      $this->amount = $params['amount'];
    }
    if( isset($params['bidItems']) ) {
      $this->bidItems = $this->transformBidItems($params['bidItems']);
    }
  }

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function getVendor() {
    return $this->vendor;
  }

  public function setVendor($vendor) {
    $this->vendor = $vendor;
  }

  public function getStatus() {
    return $this->status;
  }

  public function setStatus($status) {
    $this->status = $status;
  }

  public function getDescription() {
    return $this->description;
  }

  public function setDescription($description) {
    $this->description = $description;
  }

  public function getAmount() {
    return $this->amount;
  }

  public function setAmount($amount) {
    $this->amount = $amount;
  }
  
  private function transformBidItems($bidItemList) {
    $bidItems = array();
    foreach( $bidItemList as $bidItem ) {
      $bidItems[] = new CloudwordsBidItem($bidItem);
    }
    return $bidItems;
  }
}

/**
 * Represents a request for bids to translate the source content for a given Cloudwords
 * project from the specified source language to the specified target languages. A bid
 * request can specify what vendors to request bids from, can let Cloudwords choose the 
 * best vendors automatically, or both.
 * 
 * @author Douglas Kim <doug@cloudwords.com>
 * @since 1.0
 */
class CloudwordsBidRequest {

  private $createdDate;
  private $preferredVendors;
  private $doLetCloudwordsChoose;
  private $doAutoSelectBidFromVendor;
  private $path;
  private $winningBidId;

  /**
   * Constructor used to create a Cloudwords bid request
   *
   * - createdDate: string The bid request created date
   * - preferredVendors: array The list of preferred vendors
   * - doLetCloudwordsChoose: boolean Option to allow cloudwords to submit the bid request to a random selection of vendors
   * - path: string The api url to retrieve bid request metadata
   *
   * @param array $params The parameters used to submit a bid request
   */
  public function __construct($params) {
    if( isset($params['createdDate']) ) {
      $this->createdDate = $params['createdDate'];
    }
    if( isset($params['preferredVendors']) ) {
      $this->preferredVendors = $this->transformVendorList($params['preferredVendors']);
    }
    if( isset($params['doLetCloudwordsChoose']) ) {
      $this->doLetCloudwordsChoose = $params['doLetCloudwordsChoose'];
    }
    if( isset($params['doAutoSelectBidFromVendor']) ) {
      $this->doAutoSelectBidFromVendor = $params['doAutoSelectBidFromVendor'];
    }
    if( isset($params['path']) ) {
      $this->path = $params['path'];
    }
    if( isset($params['winningBidId']) ) {
      $this->winningBidId = $params['winningBidId'];
    }
  }

  public function getCreatedDate() {
    return $this->createdDate;
  }

  public function setCreatedDate($createdDate) {
    $this->createdDate = $createdDate;
  }

  public function getPreferredVendors() {
    return $this->preferredVendors;
  }

  public function setPreferredVendors($preferredVendors) {
    $this->preferredVendors = $preferredVendors;
  }

  public function getDoLetCloudwordsChoose() {
    return $this->doLetCloudwordsChoose;
  }

  public function setDoLetCloudwordsChoose($doLetCloudwordsChoose) {
    $this->doLetCloudwordsChoose = $doLetCloudwordsChoose;
  }

  public function getDoAutoSelectBidFromVendor() {
    return $this->doAutoSelectBidFromVendor;
  }

  public function setDoAutoSelectBidFromVendor($doAutoSelectBidFromVendor) {
    $this->doAutoSelectBidFromVendor = $doAutoSelectBidFromVendor;
  }

  public function getPath() {
    return $this->path;
  }

  public function setPath($path) {
    $this->path = $path;
  }

  public function getWinningBidId() {
    return $this->winningBidId;
  }

  public function setWinningBidId($winningBidId) {
    $this->winningBidId = $winningBidId;
  }

  private function transformVendorList($vendorList) {
    $vendors = array();
    foreach( $vendorList as $vendor ) {
      $vendors[] = new CloudwordsVendor($vendor);
    }
    return $vendors;
  }

}

/**
 * Represents the value for the bid status field in Cloudwords.
 * 
 * @author Douglas Kim <doug@cloudwords.com>
 * @since 1.0
 */
class CloudwordsBidStatus {

  private $display;
  private $code;

  /**
   * Constructor used to create a Cloudwords bid status
   *
   * - display: string The user-friendly display representation of this bid status field
   * - code: string The code for bid status field
   *
   * @param array $params The parameters used to initialize a bid status instance
   */
  public function __construct($params) {
    if( isset($params['display']) ) {
      $this->display = $params['display'];
    }
    if( isset($params['code']) ) {
      $this->code = $params['code'];
    }
  }

  public function getDisplay() {
    return $this->display;
  }

  public function setDisplay($display) {
    $this->display = $display;
  }

  public function getCode() {
    return $this->code;
  }

  public function setCode($code) {
    $this->code = $code;
  }

}

/**
 * Represents a bid item provided by a vendor for a given Cloudwords bid item
 * 
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 * @since 1.0
 */
class CloudwordsBidItem {
  private $id;
  private $language;
  private $isLanguageRemoved;
  private $bidItemTasks;

  /**
   * Constructor used to create a Cloudwords bid item request
   *
   * - id: string The unique id associated with this bid item
   * - language: array The bid language
   * - isLanguageRemoved: boolean Option
   * - bidItemTasks: array The bid item task associated with this bid item
   *
   * @param array $params The parameters used to initialize a bid instance
   */
  public function __construct($params) {
    if( isset($params['id']) ) {
      $this->id = $params['id'];
    }
    if( isset($params['language']) ) {
      $this->language = new CloudwordsLanguage($params['language']);
    }
    if( isset($params['isLanguageRemoved']) ) {
      $this->isLanguageRemoved = $params['isLanguageRemoved'];
    }
  	if( isset($params['bidItemTasks']) ) {
      $this->bidItemTasks = $this->transformBidItemTasks($params['bidItemTasks']);
    }
  }

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = $id;
  }
  
  public function getLanguage() {
    return $this->language;
  }

  public function setLanguage($language) {
  	$this->language = $language;
  }

  public function getIsLanguageRemoved() {
	return $this->isLanguageRemoved;
  }

  public function setIsLanguageRemoved($isLanguageRemoved) {
	$this->isLanguageRemoved = $isLanguageRemoved;
  }
  
  private function transformBidItemTasks($bidItemTasksList) {
    $bidItemTasks = array();
    foreach( $bidItemTasksList as $bidItemTask ) {
      $bidItemsTasks[] = new CloudwordsBidItemTask($bidItemTask);
    }
    
    return $bidItemsTasks;
  }
}

/**
 * Represents a bid item task provided by a vendor for a given Cloudwords bid item
 * 
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 * @since 1.0
 */
class CloudwordsBidItemTask {
  private $id;
  private $attributes;
  private $cost;
  private $projectTaskType;

  /**
   * Constructor used to create a Cloudwords bid item request
   *
   * - id: string The unique id associated with this bid item
   * - attribute: array The bid language
   * - projectTaskType: array The task type associated with this bid item task
   * - cost: string The cost of task
   *
   * @param array $params The parameters used to initialize a bid instance
   */
  public function __construct($params) {
    if( isset($params['id']) ) {
      $this->id = $params['id'];
    }
    if( isset($params['attributes']) ) {
      $this->attributes = $params['attributes'];
    }
    if( isset($params['cost']) ) {
      $this->cost = $params['cost'];
    }
  	if( isset($params['projectTaskType']) ) {
      $this->projectTaskType = new CloudwordsProjectTaskType($params['projectTaskType']);
    }
  }

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = $id;
  }
  
  public function getAttributes() {
	return $this->attributes;
  }

  public function setAttributes($attributes) {
	$this->attributes = $attributes;
  }

  public function getCost() {
	return $this->cost;
  }

  public function setCost($cost) {
  	$this->cost = $cost;
  }

  public function getProjectTaskType() {
	return $this->projectTaskType;
  }

  public function setProjectTaskType($projectTaskType) {
  	$this->projectTaskType = $projectTaskType;
  }
}

/**
 * Represents the metadata around a file stored in Cloudwords. The type of file could be the source
 * content for a project, the reference material for a project, etc.

 * @author Douglas Kim <doug@cloudwords.com>
 * @since 1.0
 */
class CloudwordsFile {

  private $id;
  private $filename;
  private $lang;
  private $contentPath;
  private $sizeInKilobytes;
  private $fileContents;
  private $createdDate;
  private $path;

  /**
   * Constructor used to create a Cloudwords file resource
   *
   * - id: int The file resource id
   * - filename: string The filename of the file resource
   * - lang: array The language of the file resource that contains a display name and language code
   * - contentPath: string The content path to the file resource
   * - sizeInKilobytes: int The file resource size in kilobytes
   * - fileContents: string The file contents containing within the file resource
   * - createdDate: string The file resource created date
   * - path: string The api url to retrieve file resource metadata
   *
   * @param array $params The parameters used to initialize a project instance
   */
  public function __construct($params) {
    if( isset($params['id']) ) {
      $this->id = $params['id'];
    }
    if( isset($params['filename']) ) {
      $this->filename = $params['filename'];
    }
    if( isset($params['lang']) ) {
      $this->lang = new CloudwordsLanguage($params['lang']);
    }
    if( isset($params['contentPath']) ) {
      $this->contentPath = $params['contentPath'];
    }
    if( isset($params['sizeInKilobytes']) ) {
      $this->sizeInKilobytes = $params['sizeInKilobytes'];
    }
    if( isset($params['fileContents']) ) {
      $this->fileContents = $params['fileContents'];
    }
    if( isset($params['createdDate']) ) {
      $this->createdDate = $params['createdDate'];
    }
    if( isset($params['path']) ) {
      $this->path = $params['path'];
    }
  }

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function getFilename() {
    return $this->filename;
  }

  public function setFilename($filename) {
    $this->filename = $filename;
  }

  public function getLang() {
    return $this->lang;
  }

  public function setLang($lang) {
    $this->lang = $lang;
  }

  public function getContentPath() {
    return $this->contentPath;
  }

  public function setContentPath($contentPath) {
    $this->contentPath = $contentPath;
  }

  public function getSizeInKilobytes() {
    return $this->sizeInKilobytes;
  }

  public function setSizeInKilobytes($sizeInKilobytes) {
    $this->sizeInKilobytes = $sizeInKilobytes;
  }

  public function getFileContents() {
    return $this->fileContents;
  }

  public function setFileContents($fileContents) {
    $this->fileContents = $fileContents;
  }

  public function getCreatedDate() {
    return $this->createdDate;
  }

  public function setCreatedDate($createdDate) {
    $this->createdDate = $createdDate;
  }

  public function getPath() {
    return $this->path;
  }

  public function setPath($path) {
    $this->path = $path;
  }

}

/**
 * Represents the metadata around a translated file for a given project and language stored in Cloudwords.
 * 
 */
class CloudwordsLanguageFile extends CloudwordsFile {

  private $lang;
  private $status;

  /**
   * Constructor used to create a Cloudwords language file resource
   *
   * - lang: string The target language associated with this file
   * - status: array The status of the language file
   *
   * @param array $params The parameters used to initialize a language file instance
   */
  public function __construct($params) {
    parent::__construct($params);
    if( isset($params['lang']) ) {
      $this->lang = new CloudwordsLanguage($params['lang']);
    }
    if( isset($params['status']) ) {
      $this->status = new CloudwordsLanguageStatus($params['status']);
    }
  }

  public function getLang() {
    return $this->lang;
  }

  public function setLang($lang) {
    $this->lang = $lang;
  }

  public function getStatus() {
    return $this->status;
  }

  public function setStatus($status) {
    $this->status = $status;
  }

}

/**
 * Represents a project's intended use use in Cloudwords. An intended use represents what 
 * purpose a given project's content is intended for (e.g. Website, Product, Legal, etc.).
 * 
 * @author Douglas Kim <doug@cloudwords.com>
 * @since 1.0
 */
class CloudwordsIntendedUse {

  private $id;
  private $name;

  /**
   * Constructor used to create a Cloudwords intended use
   *
   * - id: int The intended use id
   * - name: string The intended use name
   *
   * @param array $params The parameters used to initialize an intended use instance
   */
  public function __construct($params) {
    if( isset($params['id']) ) {
      $this->id = $params['id'];
    }
    if( isset($params['name']) ) {
      $this->name = $params['name'];
    }
  }

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function getName() {
    return $this->name;
  }

  public function setName($name) {
    $this->name = $name;
  }

}

/**
 * Represents a language resource in Cloudwords.
 * 
 * @author Douglas Kim <doug@cloudwords.com>
 * @since 1.0
 */
class CloudwordsLanguage {

  private $display;
  private $languageCode;

  /**
   * Constructor used to create a Cloudwords language
   *
   * - display: string The language display name
   * - languageCode: string The language code
   *
   * @param array $params The parameters used to initialize a language instance
   */
  public function __construct($params) {
    if( isset($params['display']) ) {
      $this->display = $params['display'];
    }
    if( isset($params['languageCode']) ) {
      $this->languageCode = $params['languageCode'];
    }
  }

  public function getDisplay() {
    return $this->display;
  }

  public function setDisplay($display) {
    $this->display = $display;
  }

  public function getLanguageCode() {
    return $this->languageCode;
  }

  public function setLanguageCode($languageCode) {
    $this->languageCode = $languageCode;
  }

}

/**
 * Represents the value for the language file status field in Cloudwords.
 * 
 * @author Douglas Kim <doug@cloudwords.com>
 * @since 1.0
 */
class CloudwordsLanguageStatus {

  private $display;
  private $code;

  /**
   * Constructor used to create a Cloudwords language status
   *
   * - display: int The language status display name
   * - code: string The language status internal code
   *
   * @param array $params The parameters used to initialize a language status instance
   */
  public function __construct($params) {
    if( isset($params['display']) ) {
      $this->display = $params['display'];
    }
    if( isset($params['code']) ) {
      $this->code = $params['code'];
    }
  }

  public function getDisplay() {
    return $this->display;
  }

  public function setDisplay($display) {
    $this->display = $display;
  }

  public function getCode() {
    return $this->code;
  }

  public function setCode($code) {
    $this->code = $code;
  }

}

/**
 * Represents a project resource in Cloudwords. A project is the central resource in Cloudwords, as it 
 * represents an initiative to translate some content. It contains information, both necessary and optional, 
 * to define a project's requirements, such as the content's source language and requested target languages 
 * to translate into.
 * 
 * @author Douglas Kim <doug@cloudwords.com>
 * @since 1.0
 */
class CloudwordsProject {

  private $id;
  private $name;
  private $description;
  private $notes;
  private $poNumber;
  private $intendedUse;
  private $sourceLanguage;
  private $targetLanguages;
  private $owner;
  private $followers;
  private $status;
  private $bidDueDate;
  private $deliveryDueDate;
  private $createdDate;
  private $bidSelectDeadlineDate;
  private $amount;
  private $path;
  private $params;
  
  /**
   * Constructor used to create a Cloudwords project
   *
   * - id: int The project id
   * - name: string The project name
   * - description: string The project description
   * - notes: string The project notes
   * - poNumber: string The project purchase order number
   * - intendedUse: int The project intended use unique identifier
   * - sourceLanguage: string The language code for the source language
   * - targetLanguages: array The language codes for target languages
   * - department: array The project department code and display name
   * - status: array The project status code and display name
   * - owner: array The project owner code and display name
   * - followers: array The project followers
   * - bidDueDate: string The project bid due date
   * - deliveryDueDate: string The project delivery due date
   * - createdDate: string The project created date
   * - bidSelectDeadlineDate: string The project bid selection deadline date
   * - amount: int The amount or cost associated with this project
   * - path: string The api url to retrieve project metadata
   *
   * @param array $params The parameters used to initialize a project instance
   */
  public function __construct($params) {
    $this->params = $params;
    if( isset($params['id']) ) {
      $this->id = $params['id'];
    }
    if( isset($params['name']) ) {
      $this->name = $params['name'];
    }
    if( isset($params['description']) ) {
      $this->description = $params['description'];
    }
    if( isset($params['notes']) ) {
      $this->notes = $params['notes'];
    }
    if( isset($params['poNumber']) ) {
      $this->poNumber = $params['poNumber'];
    }
    if( isset($params['intendedUse']) ) {
      $this->intendedUse = new CloudwordsIntendedUse($params['intendedUse']);
    }
    if( isset($params['sourceLanguage']) ) {
      $this->sourceLanguage = new CloudwordsLanguage($params['sourceLanguage']);
    }
    if( isset($params['targetLanguages']) ) {
      $this->targetLanguages = $this->transformLanguageList($params['targetLanguages']);
    }
    if( isset($params['status']) ) {
      $this->status = new CloudwordsProjectStatus($params['status']);
    }
    if( isset($params['department']) ) {
      $this->department = new CloudwordsDepartment($params['department']);
    }
    if( isset($params['owner']) ) {
      $this->owner = new CloudwordsUser($params['owner']);
    }
    if( isset($params['followers']) ) {
      $this->followers = $this->transformFollowerList($params['followers']);
    }
    if( isset($params['bidDueDate']) ) {
      $this->bidDueDate = $params['bidDueDate'];
    }
    if( isset($params['deliveryDueDate']) ) {
      $this->deliveryDueDate = $params['deliveryDueDate'];
    }
    if( isset($params['createdDate']) ) {
      $this->createdDate = $params['createdDate'];
    }
    if( isset($params['bidSelectDeadlineDate']) ) {
      $this->bidSelectDeadlineDate = $params['bidSelectDeadlineDate'];
    }
    if( isset($params['amount']) ) {
      $this->amount = $params['amount'];
    }
    if( isset($params['path']) ) {
      $this->path = $params['path'];
    }
  }

  public function getParams() {
    return $this->params;
  }

  public function setParams($params) {
    $this->params = params;
  }

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function getName() {
    return $this->name;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function getDescription() {
    return $this->description;
  }

  public function setDescription($description) {
    $this->description = $description;
  }

  public function getNotes() {
    return $this->notes;
  }

  public function setNotes($notes) {
    $this->notes = $notes;
  }

  public function getPoNumber() {
    return $this->poNumber;
  }

  public function setPoNumber($poNumber) {
    $this->poNumber = $poNumber;
  }

  public function getIntendedUse() {
    return $this->intendedUse;
  }

  public function setIntendedUse($intendedUse) {
    $this->intendedUse = $intendedUse;
  }

  public function getSourceLanguage() {
    return $this->sourceLanguage;
  }

  public function setSourceLanguage($sourceLanguage) {
    $this->sourceLanguage = $sourceLanguage;
  }

  public function getTargetLanguages() {
    return $this->targetLanguages;
  }

  public function setTargetLanguages($targetLanguages) {
    $this->targetLanguages = $targetLanguages;
  }

  public function getStatus() {
    return $this->status;
  }

  public function setStatus($status) {
    $this->status = $status;
  }

  public function getDepartment() {
    return $this->department;
  }

  public function setDepartment($department) {
    $this->department = $department;
  }

  public function getFollowers() {
    return $this->followers;
  }

  public function setFollowers($followers) {
    $this->followers = $followers;
  }
  
  public function getOwner() {
    return $this->owner;
  }

  public function setOwner($owner) {
    $this->status = $owner;
  }
  
  public function getBidDueDate() {
    return $this->bidDueDate;
  }

  public function setBidDueDate($bidDueDate) {
    $this->bidDueDate = $bidDueDate;
  }

  public function getDeliveryDueDate() {
    return $this->deliveryDueDate;
  }

  public function setDeliveryDueDate($deliveryDueDate) {
    $this->deliveryDueDate = $deliveryDueDate;
  }

  public function getCreatedDate() {
    return $this->createdDate;
  }

  public function setCreatedDate($createdDate) {
    $this->createdDate = $createdDate;
  }

  public function getBidSelectDeadlineDate() {
    return $this->bidSelectDeadlineDate;
  }

  public function setBidSelectDeadlineDate($bidSelectDeadlineDate) {
    $this->bidSelectDeadlineDate = $bidSelectDeadlineDate;
  }

  public function getAmount() {
    return $this->amount;
  }

  public function setAmount($amount) {
    $this->amount = $amount;
  }

  public function getPath() {
    return $this->path;
  }

  public function setPath($path) {
    $this->path = $path;
  }

  private function transformLanguageList($languageList) {
    $languages = array();
    foreach( $languageList as $language ) {
      $languages[] = new CloudwordsLanguage($language);
    }
    return $languages;
  }

  private function transformFollowerList($followerList) {
    $followers = array();
    foreach( $followerList as $follower ) {
      $followers[] = new CloudwordsUser($follower);
    }
    return $followers;
  }
}

/**
 * Represents the value for a project status field in Cloudwords.
 * 
 * @author Douglas Kim <doug@cloudwords.com>
 * @since 1.0
 */
class CloudwordsProjectStatus {

  private $display;
  private $code; 

  /**
   * Constructor used to create a Cloudwords project status
   *
   * - display: int The project status display name
   * - code: string The project status internal code
   *
   * @param array $params The parameters used to initialize a project status instance
   */
  public function __construct($params) {
    if( isset($params['display']) ) {
      $this->display = $params['display'];
    }
    if( isset($params['code']) ) {
      $this->code = $params['code'];
    }
  }

  public function getDisplay() {
    return $this->display;
  }

  public function setDisplay($display) {
    $this->display = $display;
  }

  public function getCode() {
    return $this->code;
  }

  public function setCode($code) {
    $this->code = $code;
  }

}

/**
 * Represents a Vendor work item for a Project
 * 
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 * @since 1.0
 */
class CloudwordsProjectTaskType {

  private $display;
  private $parentType;
  private $type; 

  /**
   * Constructor used to create a Cloudwords project status
   *
   * - display: string The project task type display name
   * - parentType: string The project task type parent
   * - type: string The project task type
   *
   * @param array $params The parameters used to initialize a project status instance
   */
  public function __construct($params) {
    if( isset($params['display']) ) {
      $this->display = $params['display'];
    }
    if( isset($params['parentType']) ) {
      $this->parentType = $params['parentType'];
    }
    if( isset($params['type']) ) {
      $this->type = $params['type'];
    }
  }

  public function getDisplay() {
    return $this->display;
  }

  public function setDisplay($display) {
    $this->display = $display;
  }
  
  public function getParentType() {
	return $this->parentType;
  }

  public function setParentType($parentType) {
	$this->parentType = $parentType;
  }

  public function getType() {
	return $this->type;
  }

  public function setType($type) {
	$this->type = $type;
  }
}

/**
 * Represents the value for a project owner field in Cloudwords.
 * 
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 * @since 1.0
 */
class CloudwordsUser {

  private $id;
  private $firstName;
  private $lastName;

  /**
   * Constructor used to create a Cloudwords project status
   *
   * - id: int The project status id name
   * - firstName: string The project status internal firstName
   * - lastName: string The project status internal lastName
   *
   * @param array $params The parameters used to initialize a project status instance
   */
  public function __construct($params) {
    if( isset($params['id']) ) {
      $this->id = $params['id'];
    }
    if( isset($params['firstName']) ) {
      $this->firstName = $params['firstName'];
    }
    if( isset($params['lastName']) ) {
      $this->lastName = $params['lastName'];
    }
  }

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function getFirstName() {
    return $this->firstName;
  }

  public function setFirstName($firstName) {
    $this->firstName = $firstName;
  }


  public function getLastName() {
    return $this->lastName;
  }

  public function setLastName($lastName) {
    $this->lastName = $lastName;
  }
}

/**
 * Represents a vendor resource in Cloudwords. A vendor is assigned to a Cloudwords
 * project in order to provide the necessary translation services.
 * 
 * @author Douglas Kim <doug@cloudwords.com>
 * @since 1.0
 */
class CloudwordsVendor {

  private $id;
  private $name;
  private $path;

  /**
   * Constructor used to create a Cloudwords vendor
   *
   * - id: int The vendor id
   * - name: string The vendor name
   * - path: string The api url to retrieve vendor metadata
   *
   * @param array $params The parameters used to initialize a vendor instance
   */
  public function __construct($params) {
    if( isset($params['id']) ) {
      $this->id = $params['id'];
    }
    if( isset($params['name']) ) {
      $this->name = $params['name'];
    }
    if( isset($params['path']) ) {
      $this->path = $params['path'];
    }
  }

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function getName() {
    return $this->name;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function getPath() {
    return $this->path;
  }

  public function setPath($path) {
    $this->path = $path;
  }

}

/**
 * Represents a department resource in Cloudwords. A department is assigned to a Cloudwords
 * project in order to provide the necessary translation services.
 * 
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 * @since 1.0
 */
class CloudwordsDepartment {

  private $id;
  private $name;

  /**
   * Constructor used to create a Cloudwords vendor
   *
   * - id: int The vendor id
   * - name: string The vendor name
   *
   * @param array $params The parameters used to initialize a vendor instance
   */
  public function __construct($params) {
    if( isset($params['id']) ) {
      $this->id = $params['id'];
    }
    if( isset($params['name']) ) {
      $this->name = $params['name'];
    }
  }

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function getName() {
    return $this->name;
  }

  public function setName($name) {
    $this->name = $name;
  }
}