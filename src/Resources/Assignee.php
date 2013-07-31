<?php
namespace Cloudwords\Resources;

require_once 'Customer.php';
require_once 'ProjectFollowers.php';
require_once 'Vendor.php';
require_once 'User.php';

use Cloudwords\Resources\Customer as Customer,
    Cloudwords\Resources\ProjectFollowers as ProjectFollowers,
    Cloudwords\Resources\Vendor as Vendor,
    Cloudwords\Resources\User as User;

/**
 * Represents a bid provided by a vendor for a given Cloudwords project.
 * 
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 * @since 1.0
 */
class Assignee extends AbstractResource
{
    private $vendor;
    
    private $projectFollowers;
    
    private $customer;
    
    private $customerUser;

    /**
     * Constructor used to create a Cloudwords assignee
     *
     * - vendor: array The vendor associated with this assignee
     * - projectFollowers: array The project followers associated with this assignee
     * - customer: array The customer associated with this assignee
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
        
        if( isset($params['projectFollowers']) ) {
            $this->projectFollowers = new ProjectFollowers($params['projectFollowers']);
        }
        
        if( isset($params['customer']) ) {
            $this->customer = new Customer($params['customer']);
        }
        
        if( isset($params['customerUser']) ) {
            $this->customerUser = new User($params['customerUser']);
        }
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
     * Get ProjectFollowers
     * 
     * @return  \Cloudwords\Resource\ProjectFollowers
     */
    public function getProjectFollowers()
    {
        return $this->projectFollowers;
    }

    /**
     * Set ProjectFollowers
     * 
     * @param   \Cloudwords\Resource\ProjectFollowers   $projectFollowers
     * @return  $this
     */
    public function setProjectFollowers(ProjectFollowers $projectFollowers)
    {
        $this->projectFollowers = $projectFollowers;
    }

    /**
     * Get Customer
     * 
     * @return  \Cloudwords\Resource\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set Customer
     * 
     * @param   \Cloudwords\Resource\ProjectFollowers   $projectFollowers
     * @return  $this
     */
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
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
