<?php
namespace Cloudwords\Resources;

require_once 'Customer.php';
require_once 'ProjectFollowers.php';
require_once 'AbstractUserOrVendor.php';

use Cloudwords\Resources\Customer as Customer,
    Cloudwords\Resources\ProjectFollowers as ProjectFollowers,
    Cloudwords\Resources\AbstractUserOrVendor as AbstractUserOrVendor;

/**
 * Represents an assignee for a task.
 * 
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 * @since 1.0
 */
class Assignee extends AbstractUserOrVendor
{
    private $projectFollowers;
    
    private $customer;
    
    /**
     * Constructor used to create a Cloudwords assignee
     *
     * - projectFollowers: array The project followers associated with this assignee
     * - customer: array The customer associated with this assignee
     *
     * @param array $params The parameters used to initialize an assignee instance
     * @void
     */
    public function __construct($params)
    {
        if( isset($params['projectFollowers']) ) {
            $this->projectFollowers = new ProjectFollowers($params['projectFollowers']);
        }
        
        if( isset($params['customer']) ) {
            $this->customer = new Customer($params['customer']);
        }
        
        parent::__construct($params);
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
}
