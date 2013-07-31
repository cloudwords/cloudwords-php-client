<?php
namespace Cloudwords\Resources;

/**
 * Represents the abstract class in Cloudwords resources.
 * 
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 * @since 1.0
 */
abstract class AbstractResource
{
    private $path;

    /**
     * Constructor used to create a Cloudwords abstract resources
     *
     * - path: string The resource path
     *
     * @param array $params The parameters used to initialize abstrach class
     */
    public function __construct($params)
    {
        if( isset($params['path']) ) {
            $this->path = $params['path'];
        }
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }
}
