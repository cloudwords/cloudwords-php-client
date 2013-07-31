<?php
namespace Cloudwords\Resources;

require_once 'AbstractResource.php';

use Cloudwords\Resources\AbstractResource as AbstractResource;

/**
 * Represents the value for a project followers field in Cloudwords.
 * 
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 * @since 1.0
 */
class ProjectFollowers extends AbstractResource
{
    private $projectId;

    /**
     * Constructor used to create a Cloudwords project followers
     *
     * - projectId: int The projectId
     *
     * @param array $params The parameters used to initialize a project status instance
     */
    public function __construct($params)
    {
        if( isset($params['projectId']) ) {
            $this->projectId = $params['projectId'];
        }
    }

    /**
     * Get projectId
     * 
     * @return	int     $projectId
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * Set projectId
     * 
     * @param	int     $projectId
     * @return	$this
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;
        return $this;
    }
}
