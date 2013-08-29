<?php
namespace Cloudwords\Resources;

require_once 'Language.php';
require_once 'CodeAndDisplay.php';

use Cloudwords\Resources\Language as Language,
    Cloudwords\Resources\CodeAndDisplay as CodeAndDisplay;

/**
 * Represents the metadata around a file stored in Cloudwords. The type of file could be the source
 * content for a project, the reference material for a project, etc.

 * @author Douglas Kim <doug@cloudwords.com>
 * @since 1.0
 */
class File
{
    private $id;

    private $filename;

    private $lang;

    private $contentPath;

    private $sizeInKilobytes;

    private $fileContents;

    private $createdDate;

    private $status;
    
    private $path;

    /**
     * Constructor used to create a Cloudwords file resource
     *
     * - id: int The file resource id
     * - status: CodeAndDisplay object
     * - filename: string The filename of the file resource
     * - lang: array The language of the file resource that contains a display name and language code
     * - contentPath: string The content path to the file resource
     * - sizeInKilobytes: int The file resource size in kilobytes
     * - fileContents: string The file contents containing within the file resource
     * - createdDate: string The file resource created date
     * - path: string The api url to retrieve file resource metadata
     *
     * @param array $params The parameters used to initialize a project instance
     */
    public function __construct($params)
    {
        if( isset($params['id']) ) {
            $this->id = $params['id'];
        }
        if( isset($params['status']) ) {
            $this->status = new CodeAndDisplay($params['status']);
        }
        if( isset($params['filename']) ) {
            $this->filename = $params['filename'];
        }
        if( isset($params['lang']) ) {
            $this->lang = new Language($params['lang']);
        }
        if( isset($params['contentPath']) ) {
            $this->contentPath = $params['contentPath'];
        }
        if( isset($params['sizeInKilobytes']) ) {
            $this->sizeInKilobytes = $params['sizeInKilobytes'];
        }
        if( isset($params['fileContents']) ) {
            $this->fileContents = $params['fileContents'];
        }
        if( isset($params['createdDate']) ) {
            $this->createdDate = new \DateTime($params['createdDate']);
        }
        if( isset($params['path']) ) {
            $this->path = $params['path'];
        }
    }

    /**
     * Get id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     * 
     * @param	int		id
     * @return	$this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get filename
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set filename
     * 
     * @param	string	filename
     * @return	$this
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * Get lang
     */    
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Set language
     * 
     * @param	\Cloudwords\Resources\Language	$lang
     * @return	$this
     */
    public function setLang(Language $lang)
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * Get content path
     */
    public function getContentPath()
    {
        return $this->contentPath;
    }

    /**
     * Set contentPath
     * 
     * @param	string	$contentPath
     */
    public function setContentPath($contentPath)
    {
        $this->contentPath = $contentPath;
        return $this;
    }

    /**
     * Get size in kilobytes
     */
    public function getSizeInKilobytes()
    {
        return $this->sizeInKilobytes;
    }

    /**
     * Set sizeInKilobytes
     * 
     * @param	int	$sizeInKilobytes
     */
    public function setSizeInKilobytes($sizeInKilobytes)
    {
        $this->sizeInKilobytes = $sizeInKilobytes;
        return $this;
    }

    /**
     * Get fileContents
     */
    public function getFileContents()
    {
        return $this->fileContents;
    }

    /**
     * Set fileContents
     * 
     * @param	string	$fileContents
     * @return	$this
     */
    public function setFileContents($fileContents)
    {
        $this->fileContents = $fileContents;
        return $this;
    }

    /**
     * Get createdDate
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set created date
     * 
     * @param	\DateTime	$createdDate
     */
    public function setCreatedDate(\DateTime $createdDate)
    {
        $this->createdDate = $createdDate;
        return $this;
    }

    /**
     * Get path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set path
     * @param 	string $path
     * @return	$this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }
    
	/**
     * Get status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status
     * 
     * @param	\Cloudwords\Resources\CodeAndDisplay	$status
     * @return	$this
     */
    public function setStatus(CodeAndDisplay $status)
    {
        $this->status = $status;
        return $this;
    }
}
