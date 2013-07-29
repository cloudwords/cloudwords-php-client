<?php
namespace Cloudwords;

/**
 * Exception thrown when a call to the Cloudwords API returns an exception.
 *
 * @author Douglas Kim <doug@cloudwords.com>
 * @since 1.0
 */
class Exception extends \Exception
{
    const API_EXCEPTION = 'api_exception';
    const UNSUPPORTED_CONTENT_TYPE_EXCEPTION = 'unsupported_content_type_exception';
    const REQUEST_EXCEPTION = 'request_exception';
    const DEPENDENCY_EXCEPTION = 'dependency_exception';

    private $exceptionType;

    private $httpStatusCode;

    private $requestType;

    private $requestUrl;

    private $errorMessage;

    private $contentType;

    public function __construct($exceptionType, $params)
    {
        $this->exceptionType = $exceptionType;
        if( $exceptionType == self::API_EXCEPTION ) {
            $this->httpStatusCode = $params['http_status_code'];
            $this->requestType  = $params['request_type'];
            $this->requestUrl   = $params['request_url'];
            $this->errorMessage = $params['error_message'];
        } else if( $exceptionType == self::UNSUPPORTED_CONTENT_TYPE_EXCEPTION ) {
            $this->contentType = $params['content_type'];
        } else if( $exceptionType == self::REQUEST_EXCEPTION ) {
            $this->errorMessage = $params['error_message'];
        } else if( $exceptionType == self::DEPENDENCY_EXCEPTION ) {
            $this->errorMessage = $params['errorMessage'];
        }
    }

    public function getExceptionType()
    {
        return $this->exceptionType;
    }

    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    public function getRequestType()
    {
        return $this->requestType;
    }

    public function getRequestUrl()
    {
        return $this->requestUrl;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    public function __toString()
    {
        if( $this->exceptionType == self::API_EXCEPTION) {
            return "Received HTTP status code " . $this->httpStatusCode . " from " . $this->requestType . " request at " . $this->requestUrl . "\n" . 
                         "Error: " . $this->errorMessage . "\n";
        } else if( $this->exceptionType == self::UNSUPPORTED_CONTENT_TYPE_EXCEPTION ) {
            return "Unsupported content type '" . $this->contentType . "'\n";
        } else if( $this->exceptionType == self::REQUEST_EXCEPTION ) {
            return "Malformed request : " . $this->errorMessage . "\n";
        } else if( $this->exceptionType == self::DEPENDENCY_EXCEPTION ) {
            return $this->errorMessage . "\n";
        }
    }
}
