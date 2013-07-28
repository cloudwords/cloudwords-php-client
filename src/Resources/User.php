<?php
namespace Cloudwords\Resources;

/**
 * Represents the value for a project owner field in Cloudwords.
 * 
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 * @since 1.0
 */
class User
{
    private $id;

    private $firstName;

    private $lastName;

    /**
     * Constructor used to create a Cloudwords project status
     *
     * - id: int The project status id name
     * - firstName: string The project status internal firstName
     * - lastName: string The project status internal lastName
     *
     * @param array $params The parameters used to initialize a project status instance
     */
    public function __construct($params)
    {
        if( isset($params['id']) ) {
            $this->id = $params['id'];
        }

        if( isset($params['firstName']) ) {
            $this->firstName = $params['firstName'];
        }

        if( isset($params['lastName']) ) {
            $this->lastName = $params['lastName'];
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }
}
