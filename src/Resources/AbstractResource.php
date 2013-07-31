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

    /**
     * Get path
     * 
     * @return  string  $path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set path
     * 
     * @param   string  $path
     * @return  $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }
}
