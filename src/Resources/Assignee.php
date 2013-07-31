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
	
    public function getVendor()
    {
		return $this->vendor;
	}

	public function setVendor(Vendor $vendor)
	{
		$this->vendor = $vendor;
	}

	public function getProjectFollowers()
	{
		return $this->projectFollowers;
	}

	public function setProjectFollowers(ProjectFollowers $projectFollowers)
	{
		$this->projectFollowers = $projectFollowers;
	}

	public function getCustomer()
	{
		return $this->customer;
	}

	public function setCustomer(Customer $customer)
	{
		$this->customer = $customer;
	}

	public function getCustomerUser()
	{
		return $this->customerUser;
	}

	public function setCustomerUser(User $customerUser)
	{
		$this->customerUser = $customerUser;
	}
}