<?php
namespace Cloudwords\Resources;

/**
 * Represents the value for the bid status field in Cloudwords.
 * 
 * @author Douglas Kim <doug@cloudwords.com>
 * @since 1.0
 */
class CodeAndDisplay
{
    private $display;

    private $code;

    /**
     * Constructor used to create a Cloudwords bid status
     *
     * - display: string The user-friendly display representation of this bid status field
     * - code: string The code for bid status field
     *
     * @param array $params The parameters used to initialize a bid status instance
     */
    public function __construct($params)
    {
        if( isset($params['display']) ) {
            $this->display = $params['display'];
        }

        if( isset($params['code']) ) {
            $this->code = $params['code'];
        }
    }

    public function getDisplay()
    {
        return $this->display;
    }

    public function setDisplay($display)
    {
        $this->display = $display;
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }
}
