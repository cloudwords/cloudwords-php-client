<?php
namespace Cloudwords\Resources;

require_once 'Vendor.php';
require_once 'User.php';

use Cloudwords\Resources\Vendor as Vendor,
    Cloudwords\Resources\User as User;

/**
 * Represents a bid provided by a vendor for a given Cloudwords project.
 * 
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 * @since 1.0
 */
class AbstractUserOrVendor extends AbstractResource
{
    private $vendor;
    
    private $customerUser;

    /**
     * Constructor used to create a Cloudwords assignee
     *
     * - vendor: array The vendor associated with this assignee
     * - customerUser: array The customerUser associated with this assignee
     *
     * @param array $params The parameters used to initialize a bid instance
     * @void
     */
    public function __construct($params)
    {
        if( isset($params['vendor']) ) {
            $this->vendor = new Vendor($params['vendor']);
        }
        
        if( isset($params['customerUser']) ) {
            $this->customerUser = new User($params['customerUser']);
        }

        parent::__construct($params);
    }
    
    /**
     * Get Vendor
     *
     * @return  \Cloudwords\Resource\Vendor
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * Set Vendor
     * 
     * @param   Vendor  $vendor
     * @return  $this 
     */
    public function setVendor(Vendor $vendor)
    {
        $this->vendor = $vendor;
        return $this;
    }

    /**
     * Get CustomerUser
     * 
     * @return  \Cloudwords\Resource\User
     */
    public function getCustomerUser()
    {
        return $this->customerUser;
    }

    /**
     * Set CustomerUser
     * 
     * @param   User    $customerUser
     * @return  $this
     */
    public function setCustomerUser(User $customerUser)
    {
        $this->customerUser = $customerUser;
        return $this;
    }
}
