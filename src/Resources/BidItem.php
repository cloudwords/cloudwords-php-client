<?php
namespace Cloudwords\Resources;

require_once 'BidItemTask.php';
require_once 'Language.php';

use Cloudwords\Resources\Language as Language,
    Cloudwords\Resources\BidItemTask as BidItemTask;

/**
 * Represents a bid item provided by a vendor for a given Cloudwords bid item
 * 
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 * @since 1.0
 */
class BidItem
{
    private $id;

    private $language;

    private $isLanguageRemoved;

    private $bidItemTasks;

    /**
     * Constructor used to create a Cloudwords bid item request
     *
     * - id: string The unique id associated with this bid item
     * - language: array The bid language
     * - isLanguageRemoved: boolean Option
     * - bidItemTasks: array The bid item task associated with this bid item
     *
     * @param array $params The parameters used to initialize a bid instance
     */
    public function __construct($params)
    {
        if( isset($params['id']) ) {
            $this->id = $params['id'];
        }
        if( isset($params['language']) ) {
            $this->language = new Language($params['language']);
        }
        if( isset($params['isLanguageRemoved']) ) {
            $this->isLanguageRemoved = $params['isLanguageRemoved'];
        }
        if( isset($params['bidItemTasks']) ) {
            $this->bidItemTasks = $this->transformBidItemTasks($params['bidItemTasks']);
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
    
    public function getLanguage()
    {
        return $this->language;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    public function getIsLanguageRemoved()
    {
        return $this->isLanguageRemoved;
    }

    public function setIsLanguageRemoved($isLanguageRemoved)
    {
        $this->isLanguageRemoved = $isLanguageRemoved;
        return $this;
    }
    
    private function transformBidItemTasks($bidItemTasksList)
    {
        $bidItemTasks = array();
        foreach( $bidItemTasksList as $bidItemTask ) {
            $bidItemsTasks[] = new BidItemTask($bidItemTask);
        }
        
        return $bidItemsTasks;
    }
}
