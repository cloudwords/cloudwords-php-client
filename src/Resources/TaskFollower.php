<?php
namespace Cloudwords\Resources;

require_once 'AbstractUserOrVendor.php';

use Cloudwords\Resources\AbstractUserOrVendor as AbstractUserOrVendor;

/**
 * Represents a follower for a task.
 * 
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 * @since 1.0
 */
class TaskFollower extends AbstractUserOrVendor
{
    /**
     * Constructor used to create a Cloudwords TaskFollower
     *
     * @see	   AbstractUserOrVendor
     * @param array $params The parameters used to initialize a task follower instance
     * @void
     */
    public function __construct($params)
    {
        parent::__construct($params);
    }
}
