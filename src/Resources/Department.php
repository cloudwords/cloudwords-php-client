<?php
namespace Cloudwords\Resources;

/**
 * Represents a department resource in Cloudwords. A department is assigned to a Cloudwords
 * project in order to provide the necessary translation services.
 * 
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 * @since 1.0
 */
class Department
{
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
    public function __construct($params)
    {
        if( isset($params['id']) ) {
            $this->id = $params['id'];
        }
        if( isset($params['name']) ) {
            $this->name = $params['name'];
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

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}
