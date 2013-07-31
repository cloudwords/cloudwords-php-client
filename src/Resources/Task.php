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
 * Represents project task for the specified project.
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
	}

	public function getProject()
	{
		return $this->project;
	}

	public function setProject($project)
	{
		$this->project = $project;
	}

	public function getStartDate()
	{
		return $this->startDate;
	}

	public function setStartDate($startDate)
	{
		$this->startDate = $startDate;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}

	public function getAssignee()
	{
		return $this->assignee;
	}

	public function setAssignee($assignee)
	{
		$this->assignee = $assignee;
	}

	public function getClosedDate()
	{
		return $this->closedDate;
	}

	public function setClosedDate($closedDate)
	{
		$this->closedDate = $closedDate;
	}

	public function getCreatedDate()
	{
		return $this->createdDate;
	}

	public function setCreatedDate($createdDate)
	{
		$this->createdDate = $createdDate;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function setDescription($description)
	{
		$this->description = $description;
	}

	public function getDueDate()
	{
		return $this->dueDate;
	}

	public function setDueDate($dueDate)
	{
		$this->dueDate = $dueDate;
	}

	public function getEmailReminderDay()
	{
		return $this->emailReminderDay;
	}

	public function setEmailReminderDay($emailReminderDay)
	{
		$this->emailReminderDay = $emailReminderDay;
	}

	public function getTargetLanguage()
	{
		return $this->targetLanguage;
	}

	public function setTargetLanguage($targetLanguage)
	{
		$this->targetLanguage = $targetLanguage;
	}

	public function getType()
	{
		return $this->type;
	}

	public function setType($type)
	{
		$this->type = $type;
	}
}