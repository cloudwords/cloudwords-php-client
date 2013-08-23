<?php
/**
 * Copyright 2011, Cloudwords, Inc.
 *
 * Licensed under the API LICENSE AGREEMENT, Version 1.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *            http://www.cloudwords.com/developers/license-1.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Cloudwords;

require_once 'Exception.php';
require_once 'Resources/LanguageFile.php';
require_once 'Resources/Language.php';
require_once 'Resources/BidRequest.php';
require_once 'Resources/IntendedUse.php';
require_once 'Resources/Project.php';
require_once 'Resources/Vendor.php';
require_once 'Resources/File.php';
require_once 'Resources/Task.php';
require_once 'Resources/Bid.php';
require_once 'Resources/Department.php';
require_once 'Resources/User.php';
use Cloudwords\Exception as ApiException,
    Cloudwords\Resources\LanguageFile as LanguageFile,
    Cloudwords\Resources\Language as Language,
    Cloudwords\Resources\BidRequest as BidRequest,
    Cloudwords\Resources\IntendedUse as IntendedUse,
    Cloudwords\Resources\Department as Department,
    Cloudwords\Resources\Project as Project,
    Cloudwords\Resources\Vendor as Vendor,
    Cloudwords\Resources\Bid as Bid,
    Cloudwords\Resources\Task as Task,
    Cloudwords\Resources\User as User,
    Cloudwords\Resources\File as CloudwordsResourceFile;

// check package dependencies
if( !function_exists('curl_init') ) {
    $params = array('error_message' => 'The Cloudwords API PHP SDK requires the CURL PHP extension.');
    throw new ApiException(ApiException::DEPENDENCY_EXCEPTION, $params);
}
if( !function_exists('json_decode') ) {
    $params = array('error_message' => 'The Cloudwords API PHP SDK requires the JSON PHP extension.');
    throw new ApiException(ApiException::DEPENDENCY_EXCEPTION, $params);
}

/**
 * Basic implementation of the Cloudwords API client.
 * 
 * @author Douglas Kim <doug@cloudwords.com>
 * @since 1.0
 */
class Client
{
    /**
     * Constants
     */
    const CONTENT_TYPE_MULTIPART_FORM_DATA = 'multipart/form-data';
    const AUTHORIZATION_HEADER = 'Authorization: ';
    const CONTENT_TYPE_HEADER  = 'Content-Type: ';
    const CONTENT_TYPE_JSON = 'application/json';
    const REQUEST_TYPE_GET  = 'GET';
    const REQUEST_TYPE_POST = 'POST';
    const REQUEST_TYPE_PUT  = 'PUT';

    /**
     * Member variables
     */

    // The domain + version for connecting to the API (e.g. https://api.cloudwords.com/v1)
    private $baseUrlWithVersion;

    // The authorization token to validate identify when accessing the Cloudwords API
    private $authToken;

    // The timeout in seconds until a connection is established. A timeout value of zero is 
    // interpreted as an infinite timeout.
    private $connectionTimeout = 30;

    // The socket timeout in seconds, which is the timeout for waiting for data or, put differently, 
    // a maximum period inactivity between two consecutive data packets). A timeout value of zero is 
    // interpreted as an infinite timeout.
    private $socketTimeout = 60;

    // The max concurrent connections the client can establish against the Cloudwords API
    private $maxTotalConnections = 3;

    /**
     * Convenience constructor that provides default configuration.
     * 
     * @param string $base_api_url The base domain of the Cloudwords API
     * @param integer $version The version of the Cloudwords API to use
     * @param string $auth_token The authorization token to validate identify when accessing the Cloudwords API
     */
    public function __construct($baseApiUrl, $version, $authToken)
    {
        $this->baseUrlWithVersion = $baseApiUrl . '/' . $version;
        $this->authToken = $authToken;
    }

    /**
     * Private methods
     */
    private function init()
    {
        $conn = curl_init();
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($conn, CURLOPT_CONNECTTIMEOUT, $this->connectionTimeout);
        curl_setopt($conn, CURLOPT_TIMEOUT, $this->socketTimeout);
        curl_setopt($conn, CURLOPT_MAXCONNECTS, $this->maxTotalConnections);
        return $conn;
    }

    private function close($conn)
    {
        curl_close($conn);
    }

    private function get($url, $acceptContentType, $bodyContentType)
    {
        $conn = $this->init();
        curl_setopt($conn, CURLOPT_URL, $url);
        curl_setopt($conn, CURLOPT_HTTPHEADER, $this->getHeaders($bodyContentType));
        $response = $data = $this->execute($conn, $url, self::REQUEST_TYPE_GET);
        $data = $this->getRequestData($response, $acceptContentType);
        $this->close($conn);
        return $data;
    }

    private function post($url, $params, $acceptContentType, $bodyContentType)
    {
        $conn = $this->init();
        curl_setopt($conn, CURLOPT_URL, $url);
        curl_setopt($conn, CURLOPT_HTTPHEADER, $this->getHeaders($bodyContentType));
        curl_setopt($conn, CURLOPT_POST, count($params));
        curl_setopt($conn, CURLOPT_POSTFIELDS, $bodyContentType == self::CONTENT_TYPE_JSON ? json_encode($params) : $params);
        $response = $this->execute($conn, $url, self::REQUEST_TYPE_POST);
        $data = $this->getRequestData($response, $acceptContentType);
        $this->close($conn);
        return $data;
    }

    private function put($url, $params, $acceptContentType, $bodyContentType)
    {
        $conn = $this->init();
        curl_setopt($conn, CURLOPT_URL, $url);
        curl_setopt($conn, CURLOPT_HTTPHEADER, $this->getHeaders($bodyContentType));
        curl_setopt($conn, CURLOPT_CUSTOMREQUEST, self::REQUEST_TYPE_PUT);
        curl_setopt($conn, CURLOPT_POSTFIELDS, $bodyContentType == self::CONTENT_TYPE_JSON ? json_encode($params) : $params);
        $response = $this->execute($conn, $url, self::REQUEST_TYPE_PUT);
        $data = $this->getRequestData($response, $acceptContentType);
        $this->close($conn);
        return $data;
    }

    private function getHeaders($bodyContentType)
    {
        $headers = array();
        $headers[] = self::AUTHORIZATION_HEADER . $this->authToken;
        if( $bodyContentType == self::CONTENT_TYPE_JSON ) {
            $headers[] = self::CONTENT_TYPE_HEADER . self::CONTENT_TYPE_JSON;
        } else if( $bodyContentType == self::CONTENT_TYPE_MULTIPART_FORM_DATA ) {
            $headers[] = self::CONTENT_TYPE_HEADER . self::CONTENT_TYPE_MULTIPART_FORM_DATA;
        }
        return $headers;
    }

    private function getRequestData($data, $contentType)
    {
        if( $contentType == self::CONTENT_TYPE_JSON ) {
            return json_decode($data, true);
        } else if( $contentType == self::CONTENT_TYPE_MULTIPART_FORM_DATA ) {
            return $data;
        } else {
            $params = array('content_type' => $contentType);
            throw new ApiException(ApiException::UNSUPPORTED_CONTENT_TYPE_EXCEPTION, $params);
        }
    }

    private function execute($conn, $url, $requestType)
    {
        $data = curl_exec($conn);
        if( curl_error($conn) != '' ) {
            $params = array('error_message' => curl_error($conn));
            throw new ApiException(ApiException::REQUEST_EXCEPTION, $params);
        }

        $http_status_code = curl_getinfo($conn, CURLINFO_HTTP_CODE);
        if( $http_status_code === 200 || $http_status_code === 201) {
            return $data;
        } else {
            $error_response = json_decode($data);
            $params = array('http_status_code' => $http_status_code,
                            'request_type'  => $requestType,
                            'request_url'   => $url,
                            'error_message' => $error_response->{'error'}
                           );
            throw new ApiException(ApiException::API_EXCEPTION, $params);
        }
    }

    /**
     * Public methods
     */

    public function getBaseUrlWithVersion()
    {
        return $this->baseUrlWithVersion;
    }

    public function getAuthToken()
    {
        return $this->authToken;
    }

    public function getConnectionTimeout()
    {
        return $this->connectionTimeout;
    }

    public function getSocketTimeout()
    {
        return $this->socketTimeout;
    }

    public function getMaxTotalConnections()
    {
        return $this->maxTotalConnections;
    }

    public function getOpenProjects()
    {
        $openProjects = array();
        $projectsMetadata = $this->get($this->baseUrlWithVersion . '/project/open.json',
                                       self::CONTENT_TYPE_JSON, self::CONTENT_TYPE_JSON
                                      );
        foreach( $projectsMetadata as $projectMetadata )
            $openProjects[] = new Project($projectMetadata);

        return $openProjects;
    }

    public function getClosedProjects()
    {
        $closedProjects = array();
        $projectsMetadata = $this->get($this->baseUrlWithVersion . '/project/closed.json',
                                       self::CONTENT_TYPE_JSON, self::CONTENT_TYPE_JSON
                                      );
        foreach( $projectsMetadata as $projectMetadata )
            $closedProjects[] = new Project($projectMetadata);

        return $closedProjects;
    }

    public function getProject($projectId)
    {
        $projectMetadata = $this->get($this->baseUrlWithVersion . '/project/' . $projectId . '.json',
                                      self::CONTENT_TYPE_JSON, self::CONTENT_TYPE_JSON
                                     );
        return new Project($projectMetadata);
    }

    public function createProject($params)
    {
        $projectMetadata = $this->post($this->baseUrlWithVersion . '/project', $params,
                                       self::CONTENT_TYPE_JSON, self::CONTENT_TYPE_JSON
                                      );
        return new Project($projectMetadata);
    }

    public function updateProject($params)
    {
        $projectMetadata = $this->put($this->baseUrlWithVersion . '/project/' . $params['id'], $params,
                                      self::CONTENT_TYPE_JSON, self::CONTENT_TYPE_JSON
                                     );
        return new Project($projectMetadata);
    }

    public function uploadProjectSource($projectId, $zipFile)
    {
        $params = array('file' => '@' . $zipFile);
        $sourceMetadata = $this->put($this->baseUrlWithVersion . '/project/' . $projectId . '/file/source',
                                      $params, self::CONTENT_TYPE_JSON,
                                      self::CONTENT_TYPE_MULTIPART_FORM_DATA
                                     );
        return new CloudwordsResourceFile($sourceMetadata);
    }

    public function getProjectSource($projectId)
    {
        $sourceMetadata = $this->get($this->baseUrlWithVersion . '/project/' . $projectId . '/file/source.json',
                                     self::CONTENT_TYPE_JSON,
                                     self::CONTENT_TYPE_JSON);
        return new CloudwordsResourceFile($sourceMetadata);
    }

    public function downloadSourceFile($projectId)
    {
        $sourceMetadata = $this->getProjectSource($projectId);
        return $this->downloadFileFromMetadata($sourceMetadata);
    }

    public function uploadProjectReference($projectId, $zipFile)
    {
        $params = array('file' => '@' . $zipFile);
        $referenceMetadata = $this->post($this->baseUrlWithVersion . '/project/' . $projectId . '/file/reference',
                                         $params,
                                         self::CONTENT_TYPE_JSON,
                                         self::CONTENT_TYPE_MULTIPART_FORM_DATA
                                        );
        return new CloudwordsResourceFile($referenceMetadata);
    }

    public function updateProjectReference($projectId, $documentId, $zipFile)
    {
        $params = array('file' => '@' . $zipFile);
        $referenceMetadata = $this->put($this->baseUrlWithVersion . '/project/' . $projectId . '/file/reference/' . $documentId,
                                        $params,
                                        self::CONTENT_TYPE_JSON,
                                        self::CONTENT_TYPE_MULTIPART_FORM_DATA
                                       );
        return new CloudwordsResourceFile($referenceMetadata);
    }

    public function getProjectReferences($projectId)
    {
        $projectReferences = array();
        $referencesMetadata = $this->get($this->baseUrlWithVersion . '/project/' . $projectId . '/file/reference.json',
                                         self::CONTENT_TYPE_JSON,
                                         self::CONTENT_TYPE_JSON
                                        );
        foreach( $referencesMetadata as $referenceMetadata )
            $projectReferences[] = new CloudwordsResourceFile($referenceMetadata);

        return $projectReferences;
    }

    public function getProjectReference($projectId, $documentId)
    {
        $fileMetadata = $this->get($this->baseUrlWithVersion . '/project/' . $projectId . '/file/reference/' . $documentId . '.json',
                                   self::CONTENT_TYPE_JSON,
                                   self::CONTENT_TYPE_JSON
                                  );
        return new CloudwordsResourceFile($fileMetadata);
    }

    public function downloadReferenceFile($projectId, $documentId)
    {
        $fileMetadata = $this->getProjectReference($projectId, $documentId);
        return $this->downloadFileFromMetadata($fileMetadata);
    }

    public function getMasterProjectTranslatedFile($projectId)
    {
        $fileMetadata = $this->get($this->baseUrlWithVersion . '/project/' . $projectId . '/file/translated.json',
                                   self::CONTENT_TYPE_JSON,
                                   self::CONTENT_TYPE_JSON
                                  );
        return new CloudwordsResourceFile($fileMetadata);
    }

    public function downloadMasterTranslatedFile($projectId)
    {
        $fileMetadata = $this->getMasterProjectTranslatedFile($projectId);
        return $this->downloadFileFromMetadata($fileMetadata);
    }

    public function getProjectTranslatedFiles($projectId)
    {
        $projectTranslatedFiles = array();
        $filesMetadata = $this->get($this->baseUrlWithVersion . '/project/' . $projectId . '/file/translated/language.json',
                                    self::CONTENT_TYPE_JSON,
                                    self::CONTENT_TYPE_JSON
                                   );
        foreach( $filesMetadata as $fileMetadata ) 
            $projectTranslatedFiles[] = new CloudwordsResourceFile($fileMetadata);

        return $projectTranslatedFiles;
    }

    public function getProjectTranslatedFile($projectId, $language)
    {
        $fileMetadata = $this->get($this->baseUrlWithVersion . '/project/' . $projectId . '/file/translated/language/' . $language . '.json',
                                   self::CONTENT_TYPE_JSON,
                                   self::CONTENT_TYPE_JSON
                                  );
        return new CloudwordsResourceFile($fileMetadata);
    }

    public function downloadTranslatedFile($projectId, $language)
    {
        $fileMetadata = $this->getProjectTranslatedFile($projectId, $language);
        return $this->downloadFileFromMetadata($fileMetadata);
    }

    public function approveTranslatedFile($projectId, $language)
    {
        $params = array('status' => 'approved');
        $fileMetadata = $this->put($this->baseUrlWithVersion . '/project/' . $projectId . '/file/translated/language/' . $language,
                                   $params,
                                   self::CONTENT_TYPE_JSON,
                                   self::CONTENT_TYPE_JSON
                                  );
        return new LanguageFile($fileMetadata);
    }

    public function downloadFileFromMetadata($metadata)
    {
        if( !is_null($metadata) && !is_null($metadata->getContentPath()) ) {
            return $this->get($metadata->getContentPath(),
                              self::CONTENT_TYPE_MULTIPART_FORM_DATA,
                              self::CONTENT_TYPE_JSON
                             );
        }
        return NULL;
    }

    public function requestBidsForProject($projectId, $preferredVendors, $doLetCloudwordsChoose,
                                             $doAutoSelectBidFromVendor)
    {
        $params = array('preferredVendors' => $preferredVendors,
                        'doLetCloudwordsChoose' => $doLetCloudwordsChoose,
                        'doAutoSelectBidFromVendor' => $doAutoSelectBidFromVendor
                       );
        $bidRequest = $this->post($this->baseUrlWithVersion . '/project/' . $projectId . '/bid-request', $params,
                                  self::CONTENT_TYPE_JSON,
                                  self::CONTENT_TYPE_JSON
                                 );
        return new BidRequest($bidRequest);
    }

    public function getCurrentBidRequestForProject($projectId)
    {
        $bidRequest = $this->get($this->baseUrlWithVersion . '/project/' . $projectId . '/bid-request/current.json',
                                 self::CONTENT_TYPE_JSON,
                                 self::CONTENT_TYPE_JSON
                                );
        return new BidRequest($bidRequest);
    }

    public function getBids($projectId)
    {
        $bids = array();
        $bidsMetadata = $this->get($this->baseUrlWithVersion . '/project/' . $projectId . '/bid.json',
                                   self::CONTENT_TYPE_JSON,
                                   self::CONTENT_TYPE_JSON
                                  );
        foreach( $bidsMetadata as $bidMetadata )
            $bids[] = new Bid($bidMetadata);

        return $bids;
    }

    public function getBid($projectId, $bidId)
    {
        $bidMetadata = $this->get($this->baseUrlWithVersion . '/project/' . $projectId . '/bid/' . $bidId . '.json',
                                  self::CONTENT_TYPE_JSON,
                                  self::CONTENT_TYPE_JSON
                                 );
        return new Bid($bidMetadata);
    }

    public function selectWinningBid($projectId, $bidId)
    {
        $params = array('winningBidId' => $bidId);
        $bidRequest = $this->put($this->baseUrlWithVersion . '/project/' . $projectId . '/bid-request',
                                 $params,
                                 self::CONTENT_TYPE_JSON,
                                 self::CONTENT_TYPE_JSON
                                );
        return new BidRequest($bidRequest);
    }

    public function getPreferredVendors()
    {
        $preferredVendors = array();
        $vendors = $this->get($this->baseUrlWithVersion . '/vendor/preferred.json',
                              self::CONTENT_TYPE_JSON,
                              self::CONTENT_TYPE_JSON
                             );
        foreach( $vendors as $vendor )
            $preferredVendors[] = new Vendor($vendor);

        return $preferredVendors;
    }

    public function getSourceLanguages()
    {
        $sourceLanguages = array();
        $languages = $this->get($this->baseUrlWithVersion . '/org/settings/project/language/source.json',
                                self::CONTENT_TYPE_JSON,
                                self::CONTENT_TYPE_JSON
                               );
        foreach( $languages as $language )
            $sourceLanguages[] = new Language($language);

        return $sourceLanguages;
    }

    public function getTargetLanguages()
    {
        $targetLanguages = array();
        $languages = $this->get($this->baseUrlWithVersion . '/org/settings/project/language/target.json',
                                self::CONTENT_TYPE_JSON,
                                self::CONTENT_TYPE_JSON
                               );
        foreach( $languages as $language )
            $targetLanguages[] = new Language($language);

        return $targetLanguages;
    }

    public function getIntendedUses()
    {
        $intendedUses = array();
        $uses = $this->get($this->baseUrlWithVersion . '/org/settings/project/intended-use.json',
                           self::CONTENT_TYPE_JSON,
                           self::CONTENT_TYPE_JSON
                          );
        foreach( $uses as $intendedUse )
            $intendedUses[] = new IntendedUse($intendedUse);

        return $intendedUses;
    }

    public function getVendor($vendorId)
    {
        $vendor = $this->get($this->baseUrlWithVersion . '/vendor/' . $vendorId . '.json',
                             self::CONTENT_TYPE_JSON,
                             self::CONTENT_TYPE_JSON
                            );
        return new Vendor($vendor);
    }
    
    /**
     * Get the list of all tasks for the specified project. 
     * 
     * @param   int     projectId
     * @return  array
     */
    public function getProjectTasks($projectId)
    {
        $tasksList = array();
        $tasksMetadata = $this->get($this->baseUrlWithVersion . '/project/' . $projectId . '/task.json',
                                    self::CONTENT_TYPE_JSON,
                                    self::CONTENT_TYPE_JSON
                                   );
        foreach( $tasksMetadata as $taskMetadata )
            $tasksList[] = new Task($taskMetadata);

        return $tasksList;
    }
    
    /**
     * Get the list of all tasks for the specified project with a given status
     * 
     * @param   int     projectId
     * @param   string  status code
     * @return  array
     */
    public function getProjectTasksByStatus($projectId, $status)
    {
        $tasksList = array();
        $tasksMetadata = $this->get($this->baseUrlWithVersion . '/project/' . $projectId . '/task/'
                                  . 'status/' . $status . '.json',
                                    self::CONTENT_TYPE_JSON,
                                    self::CONTENT_TYPE_JSON
                                   );
        foreach( $tasksMetadata as $taskMetadata )
            $tasksList[] = new Task($taskMetadata);

        return $tasksList;
    }

    /**
     * Get a specified task for the specified project.
     * 
     * @param   int projectId
     * @param   int taskId
     * @return  Cloudwords\Resources\Task
     */
    public function getProjectTask($projectId, $taskId)
    {
        $taskList = array();
        $taskMetadata = $this->get($this->baseUrlWithVersion . '/project/' . $projectId . '/task/'
                                 . $taskId . '.json',
                                   self::CONTENT_TYPE_JSON,
                                   self::CONTENT_TYPE_JSON
                                  );
        $task = new Task($taskMetadata);
        return $task;
    }
    
    /**
     * Get the list of all tasks across all projects
     */
    public function getAllProjectTasks()
    {
        $tasksList = array();
        $tasksMetadata = $this->get($this->baseUrlWithVersion . '/task.json',
                                    self::CONTENT_TYPE_JSON,
                                    self::CONTENT_TYPE_JSON
                                   );
        foreach( $tasksMetadata as $taskMetadata )
            $tasksList[] = new Task($taskMetadata);

        return $tasksList;
    }
    
   /**
    * Get the list of all tasks across all projects with the specified status. 
    */
    public function getAllProjectTasksByStatus($status)
    {
        $tasksList = array();
        $tasksMetadata = $this->get($this->baseUrlWithVersion . '/task/status/' . $status . '.json',
                                    self::CONTENT_TYPE_JSON,
                                    self::CONTENT_TYPE_JSON
                                   );
        foreach( $tasksMetadata as $taskMetadata )
            $tasksList[] = new Task($taskMetadata);

        return $tasksList;
    }
    
    /**
     * Create new task for sepecified project
     */
    public function createProjectTask($projectId, $params)
    {
        $taskMetadata = $this->post($this->baseUrlWithVersion . '/project/' . $projectId . '/task',
                                    $params,
                                    self::CONTENT_TYPE_JSON,
                                    self::CONTENT_TYPE_JSON
                                   );
        return new Task($taskMetadata);
    }

    /**
     * Update task for sepecified project 
     */
    public function updateProjectTask($projectId, $taskId, $params)
    {
        $taskMetadata = $this->put($this->baseUrlWithVersion . '/project/' . $projectId . '/task/' . $taskId,
                                    $params,
                                    self::CONTENT_TYPE_JSON,
                                    self::CONTENT_TYPE_JSON
                                   );
        return new Task($taskMetadata);
    }
    
    /**
     * Upload an attachment for the task.
     */
    public function uploadTaskAttachment($projectId, $taskId, $fileUpload)
    {
        $url = $this->baseUrlWithVersion . '/project/' . $projectId . '/task/' . $taskId . '/file/attachment/content';
        $param = array('file' => '@' . $fileUpload);
        $fileMetadata = $this->put($url, $param, self::CONTENT_TYPE_JSON, self::CONTENT_TYPE_MULTIPART_FORM_DATA);
        return new CloudwordsResourceFile($fileMetadata);
    }
    
    /**
     * Get the attachment for the specified task.
     */
    public function getTaskAttachment($projectId, $taskId)
    {
        $fileMetadata = $this->get($this->baseUrlWithVersion . '/project/' . $projectId . '/task/'
                                 . $taskId . '/file/attachment.json',
                                   self::CONTENT_TYPE_JSON,
                                   self::CONTENT_TYPE_JSON
                                  );
        return new CloudwordsResourceFile($fileMetadata);
    }
    
    /**
     * Get list of department
     */
    public function getDepartments()
    {
        $departmentList = array();
        $departmentsMetadata = $this->get($this->baseUrlWithVersion . '/department.json',
                                          self::CONTENT_TYPE_JSON,
                                          self::CONTENT_TYPE_JSON
                                         );
        foreach( $departmentsMetadata as $departmentMetadata )
            $departmentList[] = new Department($departmentMetadata);

        return $departmentList;
    }
    
    /**
     * Create new department
     */
    public function createDepartment($params)
    {
        $departmentMetadata = $this->post($this->baseUrlWithVersion . '/department', $params,
                                       self::CONTENT_TYPE_JSON, self::CONTENT_TYPE_JSON
                                      );
        return new Department($departmentMetadata);
    }

    /**
     * Gets the user resource for the user matching the API token in use
     */
    public function getCurrentUser()
    {
        $userMetadata = $this->get($this->baseUrlWithVersion . '/user/current.json',
                                   self::CONTENT_TYPE_JSON,
                                   self::CONTENT_TYPE_JSON
                                  );
        $user = new User($userMetadata);
        return $user;
    }
    
    /**
     * Lists all active users
     */
    public function getActiveUser()
    {
        $userList = array();
        $usersMetadata = $this->get($this->baseUrlWithVersion . '/user.json',
                                    self::CONTENT_TYPE_JSON,
                                    self::CONTENT_TYPE_JSON
                                   );
        foreach ($usersMetadata as $userMetadata) {
            $userList[] = new User($userMetadata); 
        }

        return $userList;
    }
    
    /**
     * Lists the users who are available to follow projects.
     */
    public function getAvailableFollowers()
    {
        $followerList = array();
        $usersMetadata = $this->get($this->baseUrlWithVersion . '/follower/available.json',
                                    self::CONTENT_TYPE_JSON,
                                    self::CONTENT_TYPE_JSON
                                   );
        foreach ($usersMetadata as $userMetadata) {
            $followerList[] = new User($userMetadata); 
        }

        return $followerList;
    }
    
    /**
     * Get Open Project filtered by Department Id
     */
    public function getOpenProjectsByDepartmentId($departmentId)
    {
        $projectList = array();
        $projectsMetadata = $this->filterByDepartment($departmentId, 'project/open.json');
        foreach ($projectsMetadata as $projectMetadata) {
            $projectList[] = new Project($projectMetadata);
        }
        
        return $projectList;
    }

	/**
     * Get Closed Project filtered by Department Id
     */
    public function getClosedProjectsByDepartmentId($departmentId)
    {
        $projectList = array();
        $projectsMetadata = $this->filterByDepartment($departmentId, 'project/closed.json');
        foreach ($projectsMetadata as $projectMetadata) {
            $projectList[] = new Project($projectMetadata);
        }
        
        return $projectList;
    }
    
    /**
     * Get All Project tasks filtered by Department Id 
     */
    public function getAllProjectTasksByDepartmentId($departmentId)
    {
        $tasksList = array();
        $tasksMetadata = $this->filterByDepartment($departmentId, 'task.json');
        foreach( $tasksMetadata as $taskMetadata )
            $tasksList[] = new Task($taskMetadata);

        return $tasksList;
    }

    /**
     * Get All Project tasks with status filtered by Department Id 
     */
    public function getAllProjectTasksWithStatusByDepartmentId($departmentId, $status)
    {
        $tasksList = array();
        $tasksMetadata = $this->filterByDepartment($departmentId, 'task/status/' . $status . '.json');
        foreach( $tasksMetadata as $taskMetadata )
            $tasksList[] = new Task($taskMetadata);

        return $tasksList;
    }
    
    /**
     * Lists all active users filtered by Department Id
     */
    public function getActiveUserByDepartmentId($departmentId)
    {
        $userList = array();
        $usersMetadata = $this->filterByDepartment($departmentId, 'user.json');
        foreach ($usersMetadata as $userMetadata) {
            $userList[] = new User($userMetadata); 
        }

        return $userList;
    }
    
    /**
     * Lists the users who are available to follow projects filtered by departmentId
     */
    public function getAvailableFollowersByDepartmentId($departmentId)
    {
        $followerList  = array();
        $usersMetadata = $this->filterByDepartment($departmentId, 'follower/available.json');
        foreach ($usersMetadata as $userMetadata) {
            $followerList[] = new User($userMetadata); 
        }

        return $followerList;
    }
    
    /**
     * Retrieve resource filter by Department Id 
     */
    protected function filterByDepartment($departmentId, $callingPath)
    {
        $metadata = $this->get($this->baseUrlWithVersion . '/department/' . $departmentId . '/' . $callingPath,
                               self::CONTENT_TYPE_JSON,
                               self::CONTENT_TYPE_JSON
                              );
        return $metadata;
    }
}