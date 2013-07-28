<?php
namespace Cloudwords\Resources;

/**
 * Represents a Vendor work item for a Project
 * 
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 * @since 1.0
 */
class ProjectTaskType
{
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
    public function __construct($params)
    {
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

    public function getDisplay()
    {
        return $this->display;
    }

    public function setDisplay($display)
    {
        $this->display = $display;
        return $this;
    }
    
    public function getParentType()
    {
	    return $this->parentType;
    }

    public function setParentType($parentType)
    {
	    $this->parentType = $parentType;
        return $this;
    }

    public function getType()
    {
	    return $this->type;
    }

    public function setType($type)
    {
	    $this->type = $type;
        return $this;
    }
}
