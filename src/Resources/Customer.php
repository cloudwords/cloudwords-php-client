<?php
namespace Cloudwords\Resources;

/**
 * Represents the value for customer field in Cloudwords.
 * 
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 * @since 1.0
 */
class Customer
{
    private $id;

    private $name;

    /**
     * Constructor used to create a Cloudwords project status
     *
     * - id: int The customer ID
     * - name: string The customer name
     *
     * @param array $params The parameters used to initialize a project status instance
     * @void
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

    /**
     * Get id
     * 
     * @return  int   id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Id
     * 
     * @param   int     id
     * @return  $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get name
     * 
     * @return  string  name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param   string  name
     * @return  $this
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
