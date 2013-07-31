<?php
namespace Cloudwords\Resources;

require_once 'Assignee.php';
require_once 'Project.php';
require_once 'CodeAndDisplay.php';
require_once 'Language.php';

use Cloudwords\Resources\Project as Project, 
    Cloudwords\Resources\Assignee as Assignee,
    Cloudwords\Resources\Language as Language,
    Cloudwords\Resources\CodeAndDisplay as CodeAndDisplay;

/**
 * Represents task object
 * 
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 * @since 1.0
 */
class Task
{
    private $id;
    
    private $name;

    private $project;
    
    private $startDate;
    
    private $status;
    
    private $assignee;

    private $closedDate;

    private $createdDate;

    private $description;

    private $dueDate;

    private $emailReminderDay;
    
    private $targetLanguage;
    
    private $type;

    /**
     * Constructor used to create a Cloudwords bid request
     *
     * - id: string The unique id associated with this bid
     * - name: string The name of task
     * - project: array The project associated with this task
     * - startDate: string of start date
     * - status: array The task status
     * - assignee: array The assignee associated with this task
     * - closedDate: string of start date
     * - createdDate: string of created date
     * - description: string The description provided by the vendor for this bid
     * - dueDate: string created date of task
     * - emailReminderDay: string day of email reminder day from this task
     * - targetLanguage: array target language of this task
     * - type: array the task type
     * 
     * @param array $params The parameters used to initialize a bid instance
     */
    public function __construct($params)
    {
        if( isset($params['id']) ) {
            $this->id = $params['id'];
        }
        if( isset($params['name']) ) {
            $this->name = $params['name'];
        }
        if( isset($params['project']) ) {
            $this->project = new Project($params['project']);
        }
        if( isset($params['startDate']) ) {
            $this->startDate = new \DateTime($params['startDate']);
        }
        if( isset($params['assignee']) ) {
            $this->assignee = new Assignee($params['assignee']);
        }
        if( isset($params['status']) ) {
            $this->status = new CodeAndDisplay($params['status']);
        }
        if( isset($params['closedDate']) ) {
            $this->closedDate = new \DateTime($params['closedDate']);
        }
        if( isset($params['createdDate']) ) {
            $this->createdDate = new \DateTime($params['createdDate']);
        }
        if( isset($params['description']) ) {
            $this->description = $params['description'];
        }
        if( isset($params['dueDate']) ) {
            $this->dueDate = new \DateTime($params['dueDate']);
        }
        if( isset($params['emailReminderDay']) ) {
            $this->emailReminderDay = new \DateTime($params['emailReminderDay']);
        }
        if( isset($params['targetLanguage']) ) {
            $this->targetLanguage = $params['targetLanguage'];
        }
        if( isset($params['type']) ) {
            $this->type = new CodeAndDisplay($params['type']);
        }
    }

    /**
     * Get id
     * 
     * @return  int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Id
     *
     * @param   int     $id
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
     * @return  string  $name
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

    /**
     * Get Project
     * 
     * @return  \Cloudwords\Resources\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set Project
     * 
     * @param   Project $project
     * @return  $this
     */
    public function setProject($project)
    {
        $this->project = $project;
        return $this;
    }

    /**
     * Get startDate
     * 
     * @return  \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set start date
     * 
     * @param   \DateTime   $startDate
     * @return  $this
     */
    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * Get status
     * 
     * @return  CodeAndDisplay
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status
     * 
     * @param   \Cloudwords\Resources\CodeAndDisplay $status
     * @return  $this
     */
    public function setStatus(CodeAndDisplay $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get Assignee
     * 
     * @return  \Cloudwords\Resources\Assignee
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * Set Assignee
     * 
     * @param   \Cloudwords\Resources\Assignee $assignee
     * @return  $this
     */
    public function setAssignee($assignee)
    {
        $this->assignee = $assignee;
    }

    /**
     * Get closedDate
     * 
     * @return  \DateTime    closedDate
     */
    public function getClosedDate()
    {
        return $this->closedDate;
    }

    /**
     * Set closedDate
     * 
     * @param   \DateTime   $closedDate
     * @return  $this
     */
    public function setClosedDate($closedDate)
    {
        $this->closedDate = $closedDate;
    }

    /**
     * Get createdDate
     * 
     * @return  \DateTime   $createdDate
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set createdDate
     * 
     * @param   \DateTime   $createdDate
     * @return  $this
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
        return $this;
    }

    /**
     * Get description
     * 
     * @return  string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     * 
     * @param   string    description
     * @return  $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return     $this;
    }

    /**
     * Get dueDate
     * 
     * @return  \DateTime    $dueDate
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Set dueDate
     * 
     * @param   \DateTime   $dueDate
     * @return  $this
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;
        return $this;
    }

    /**
     * Get emailReminderDay
     * 
     * @return  \DateTime $emailReminderDay
     */
    public function getEmailReminderDay()
    {
        return $this->emailReminderDay;
    }

    /**
     * Set emailReminderDay
     * 
     * @param   \DateTime   $emailReminderDay
     * @return  $this
     */
    public function setEmailReminderDay($emailReminderDay)
    {
        $this->emailReminderDay = $emailReminderDay;
        return $this;
    }

    /**
     * Get targetLanguage
     * 
     * @return  \Cloudwords\Resources\Language
     */
    public function getTargetLanguage()
    {
        return $this->targetLanguage;
    }

    /**
     * Set targetLanguage
     * 
     * @param   \Cloudwords\Resources\Language    $targetLanguage
     * @return  $this
     */
    public function setTargetLanguage(Language $targetLanguage)
    {
        $this->targetLanguage = $targetLanguage;
        return $this;
    }

    /**
     * Get type
     * 
     * @return  \Cloudwords\Resources\CodeAndDisplay
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type \Cloudwords\Resources\CodeAndDisplay
     *
     * @param   \Cloudwords\Resources\CodeAndDisplay    $type
     * @return  $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
}
