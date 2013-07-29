<?php
namespace Cloudwords\Resources;

require_once 'BidStatus.php';
require_once 'BidItem.php';
require_once 'Vendor.php';

use Cloudwords\Resources\BidItem as BidItem,
    Cloudwords\Resources\BidStatus as BidStatus,
    Cloudwords\Resources\Vendor as Vendor;

/**
 * Represents a bid provided by a vendor for a given Cloudwords project.
 * 
 * @author Douglas Kim <doug@cloudwords.com>
 * @since 1.0
 */
class Bid
{
    private $id;

    private $vendor;

    private $status;

    private $description;

    private $amount;

    private $bidItems;

    /**
     * Constructor used to create a Cloudwords bid request
     *
     * - id: string The unique id associated with this bid
     * - vendor: array The vendor associated with this bid
     * - status: array The bid status
     * - description: string The description provided by the vendor for this bid
     * - amount: string The total amount of this bid
     * - bidItems: array The bidItem associated with this bid
     *
     * @param array $params The parameters used to initialize a bid instance
     */
    public function __construct($params)
    {
        if( isset($params['id']) ) {
            $this->id = $params['id'];
        }
        if( isset($params['vendor']) ) {
            $this->vendor = new Vendor($params['vendor']);
        }
        if( isset($params['status']) ) {
            $this->status = new BidStatus($params['status']);
        }
        if( isset($params['description']) ) {
            $this->description = $params['description'];
        }
        if( isset($params['amount']) ) {
            $this->amount = $params['amount'];
        }
        if( isset($params['bidItems']) ) {
            $this->bidItems = $this->transformBidItems($params['bidItems']);
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

    public function getVendor()
    {
        return $this->vendor;
    }

    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
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

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }
    
    private function transformBidItems($bidItemList)
    {
        $bidItems = array();
        foreach( $bidItemList as $bidItem ) {
            $bidItems[] = new BidItem($bidItem);
        }
        return $bidItems;
    }
}
