<?php
namespace Cloudwords\Resources;

require_once 'LanguageStatus.php';
require_once 'Language.php';

use Cloudwords\Resources\Language as Language,
    Cloudwords\Resources\Status as Status;

/**
 * Represents the metadata around a translated file for a given project and language stored in Cloudwords.
 * 
 */
class LanguageFile extends File
{
    private $lang;

    private $status;

    /**
     * Constructor used to create a Cloudwords language file resource
     *
     * - lang: string The target language associated with this file
     * - status: array The status of the language file
     *
     * @param array $params The parameters used to initialize a language file instance
     */
    public function __construct($params)
    {
        parent::__construct($params);
        if( isset($params['lang']) ) {
            $this->lang = new Language($params['lang']);
        }

        if( isset($params['status']) ) {
            $this->status = new LanguageStatus($params['status']);
        }
    }

    public function getLang()
    {
        return $this->lang;
    }

    public function setLang($lang)
    {
        $this->lang = $lang;
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
}
