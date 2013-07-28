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
require_once 'Resources/Project.php';
use Cloudwords\Exception as ApiException,
    Cloudwords\Resources\Project as Project;

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
    const AUTHORIZATION_HEADER = 'Authorization: ';
    const CONTENT_TYPE_HEADER = 'Content-Type: ';
    const CONTENT_TYPE_JSON = 'application/json';
    const CONTENT_TYPE_MULTIPART_FORM_DATA = 'multipart/form-data';
    const REQUEST_TYPE_GET = 'GET';
    const REQUEST_TYPE_POST = 'POST';
    const REQUEST_TYPE_PUT = 'PUT';

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
        $data = $this->get_request_data($response, $acceptContentType);
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
        $data = $this->get_request_data($response, $acceptContentType);
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
        $data = $this->get_request_data($response, $acceptContentType);
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

    private function get_request_data($data, $contentType)
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
        $projectsMetadata = $this->get($this->baseUrlWithVersion . '/project/open.json', self::CONTENT_TYPE_JSON, self::CONTENT_TYPE_JSON);
        foreach( $projectsMetadata as $projectMetadata ) {
            $openProjects[] = new Project($projectMetadata);
        }

        return $openProjects;
    }
}
