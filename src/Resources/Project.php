<?php
namespace Cloudwords\Resources;

require_once 'Language.php';
require_once 'Department.php';
require_once 'IntendedUse.php';
require_once 'CodeAndDisplay.php';
require_once 'User.php';

use Cloudwords\Resources\Language as Language,
    Cloudwords\Resources\Department as Department,
    Cloudwords\Resources\IntendedUse as IntendedUse,
    Cloudwords\Resources\CodeAndDisplay as CodeAndDisplay,
    Cloudwords\Resources\User as User;

/**
 * Represents a project resource in Cloudwords. A project is the central resource in Cloudwords, as it 
 * represents an initiative to translate some content. It contains information, both necessary and optional, 
 * to define a project's requirements, such as the content's source language and requested target languages 
 * to translate into.
 * 
 * @author Douglas Kim <doug@cloudwords.com>
 * @since 1.0
 */
class Project
{
    private $id;

    private $name;

    private $description;

    private $notes;

    private $poNumber;

    private $intendedUse;

    private $sourceLanguage;

    private $targetLanguages;

    private $owner;

    private $followers;

    private $status;

    private $bidDueDate;

    private $deliveryDueDate;

    private $createdDate;

    private $bidSelectDeadlineDate;

    private $amount;

    private $path;

    private $params;

    /**
     * Constructor used to create a Cloudwords project
     *
     * - id: int The project id
     * - name: string The project name
     * - description: string The project description
     * - notes: string The project notes
     * - poNumber: string The project purchase order number
     * - intendedUse: int The project intended use unique identifier
     * - sourceLanguage: string The language code for the source language
     * - targetLanguages: array The language codes for target languages
     * - department: array The project department code and display name
     * - status: array The project status code and display name
     * - owner: array The project owner code and display name
     * - followers: array The project followers
     * - bidDueDate: string The project bid due date
     * - deliveryDueDate: string The project delivery due date
     * - createdDate: string The project created date
     * - bidSelectDeadlineDate: string The project bid selection deadline date
     * - amount: int The amount or cost associated with this project
     * - path: string The api url to retrieve project metadata
     *
     * @param array $params The parameters used to initialize a project instance
     */
    public function __construct($params)
    {
        $this->params = $params;
        if( isset($params['id']) ) {
            $this->id = $params['id'];
        }
        if( isset($params['name']) ) {
            $this->name = $params['name'];
        }
        if( isset($params['description']) ) {
            $this->description = $params['description'];
        }
        if( isset($params['notes']) ) {
            $this->notes = $params['notes'];
        }
        if( isset($params['poNumber']) ) {
            $this->poNumber = $params['poNumber'];
        }
        if( isset($params['intendedUse']) ) {
            $this->intendedUse = new IntendedUse($params['intendedUse']);
        }
        if( isset($params['sourceLanguage']) ) {
            $this->sourceLanguage = new Language($params['sourceLanguage']);
        }
        if( isset($params['targetLanguages']) ) {
            $this->targetLanguages = $this->transformLanguageList($params['targetLanguages']);
        }
        if( isset($params['status']) ) {
            $this->status = new CodeAndDisplay($params['status']);
        }
        if( isset($params['department']) ) {
            $this->department = new Department($params['department']);
        }
        if( isset($params['owner']) ) {
            $this->owner = new User($params['owner']);
        }
        if( isset($params['followers']) ) {
            $this->followers = $this->transformFollowerList($params['followers']);
        }
        if( isset($params['bidDueDate']) ) {
            $this->bidDueDate = new \DateTime($params['bidDueDate']);
        }
        if( isset($params['deliveryDueDate']) ) {
            $this->deliveryDueDate = new \DateTime($params['deliveryDueDate']);
        }
        if( isset($params['createdDate']) ) {
            $this->createdDate = new \DateTime($params['createdDate']);
        }
        if( isset($params['bidSelectDeadlineDate']) ) {
            $this->bidSelectDeadlineDate = new \DateTime($params['bidSelectDeadlineDate']);
        }
        if( isset($params['amount']) ) {
            $this->amount = $params['amount'];
        }
        if( isset($params['path']) ) {
            $this->path = $params['path'];
        }
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setParams($params)
    {
        $this->params = params;
        return $this;
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

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }

    public function getPoNumber()
    {
        return $this->poNumber;
    }

    public function setPoNumber($poNumber)
    {
        $this->poNumber = $poNumber;
        return $this;
    }

    public function getIntendedUse()
    {
        return $this->intendedUse;
    }

    public function setIntendedUse($intendedUse)
    {
        $this->intendedUse = $intendedUse;
        return $this;
    }

    public function getSourceLanguage()
    {
        return $this->sourceLanguage;
    }

    public function setSourceLanguage($sourceLanguage)
    {
        $this->sourceLanguage = $sourceLanguage;
        return $this;
    }

    public function getTargetLanguages()
    {
        return $this->targetLanguages;
    }

    public function setTargetLanguages($targetLanguages)
    {
        $this->targetLanguages = $targetLanguages;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getDepartment()
    {
        return $this->department;
    }

    public function setDepartment($department)
    {
        $this->department = $department;
        return $this;
    }

    public function getFollowers()
    {
        return $this->followers;
    }

    public function setFollowers($followers)
    {
        $this->followers = $followers;
        return $this;
    }
    
    public function getOwner()
    {
        return $this->owner;
    }

    public function setOwner($owner)
    {
        $this->status = $owner;
        return $this;
    }
    
    public function getBidDueDate()
    {
        return $this->bidDueDate;
    }

    public function setBidDueDate($bidDueDate)
    {
        $this->bidDueDate = $bidDueDate;
        return $this;
    }

    public function getDeliveryDueDate()
    {
        return $this->deliveryDueDate;
    }

    public function setDeliveryDueDate($deliveryDueDate)
    {
        $this->deliveryDueDate = $deliveryDueDate;
        return $this;
    }

    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
        return $this;
    }

    public function getBidSelectDeadlineDate()
    {
        return $this->bidSelectDeadlineDate;
    }

    public function setBidSelectDeadlineDate($bidSelectDeadlineDate)
    {
        $this->bidSelectDeadlineDate = $bidSelectDeadlineDate;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    private function transformLanguageList($languageList)
    {
        $languages = array();
        foreach( $languageList as $language ) {
            $languages[] = new Language($language);
        }
        return $languages;
    }

    private function transformFollowerList($followerList)
    {
        $followers = array();
        foreach( $followerList as $follower ) {
            $followers[] = new User($follower);
        }
        return $followers;
    }
}