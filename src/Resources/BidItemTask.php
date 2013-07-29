<?php
namespace Cloudwords\Resources;

require_once 'ProjectTaskType.php';

use Cloudwords\Resources\ProjectTaskType as ProjectTaskType;

/**
 * Represents a bid item task provided by a vendor for a given Cloudwords bid item
 * 
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 * @since 1.0
 */
class BidItemTask
{
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
    public function __construct($params)
    {
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
            $this->projectTaskType = new ProjectTaskType($params['projectTaskType']);
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setCost($cost)
    {
        $this->cost = $cost;
        return $this;
    }

    public function getProjectTaskType()
    {
        return $this->projectTaskType;
    }

    public function setProjectTaskType($projectTaskType)
    {
        $this->projectTaskType = $projectTaskType;
        return $this;
    }
}
