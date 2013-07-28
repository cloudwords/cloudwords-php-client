<?php
namespace Cloudwords\Resources;

use Vendor;

/**
 * Represents a request for bids to translate the source content for a given Cloudwords
 * project from the specified source language to the specified target languages. A bid
 * request can specify what vendors to request bids from, can let Cloudwords choose the 
 * best vendors automatically, or both.
 * 
 * @author Douglas Kim <doug@cloudwords.com>
 * @since 1.0
 */
class BidRequest 
{
    private $createdDate;

    private $preferredVendors;

    private $doLetCloudwordsChoose;

    private $doAutoSelectBidFromVendor;

    private $path;

    private $winningBidId;

    /**
     * Constructor used to create a Cloudwords bid request
     *
     * - createdDate: string The bid request created date
     * - preferredVendors: array The list of preferred vendors
     * - doLetCloudwordsChoose: boolean Option to allow cloudwords to submit the bid request to a random selection of vendors
     * - path: string The api url to retrieve bid request metadata
     *
     * @param array $params The parameters used to submit a bid request
     */
    public function __construct($params) 
    {
        if( isset($params['createdDate']) ) {
            $this->createdDate = $params['createdDate'];
        }
        if( isset($params['preferredVendors']) ) {
            $this->preferredVendors = $this->transformVendorList($params['preferredVendors']);
        }
        if( isset($params['doLetCloudwordsChoose']) ) {
            $this->doLetCloudwordsChoose = $params['doLetCloudwordsChoose'];
        }
        if( isset($params['doAutoSelectBidFromVendor']) ) {
            $this->doAutoSelectBidFromVendor = $params['doAutoSelectBidFromVendor'];
        }
        if( isset($params['path']) ) {
            $this->path = $params['path'];
        }
        if( isset($params['winningBidId']) ) {
            $this->winningBidId = $params['winningBidId'];
        }
    }

    public function getCreatedDate() 
    {
        return $this->createdDate;
    }

    public function setCreatedDate($createdDate) 
    {
        $this->createdDate = $createdDate;
        return $this;
    }

    public function getPreferredVendors() 
    {
        return $this->preferredVendors;
    }

    public function setPreferredVendors($preferredVendors) 
    {
        $this->preferredVendors = $preferredVendors;
        return $this;
    }

    public function getDoLetCloudwordsChoose() 
    {
        return $this->doLetCloudwordsChoose;
    }

    public function setDoLetCloudwordsChoose($doLetCloudwordsChoose) 
    {
        $this->doLetCloudwordsChoose = $doLetCloudwordsChoose;
        return $this;
    }

    public function getDoAutoSelectBidFromVendor() 
    {
        return $this->doAutoSelectBidFromVendor;
    }

    public function setDoAutoSelectBidFromVendor($doAutoSelectBidFromVendor) 
    {
        $this->doAutoSelectBidFromVendor = $doAutoSelectBidFromVendor;
        return $this;
    }

    public function getPath() 
    {
        return $this->path;
    }

    public function setPath($path) 
    {
        $this->path = $path;
        return $this;
    }

    public function getWinningBidId() 
    {
        return $this->winningBidId;
    }

    public function setWinningBidId($winningBidId) 
    {
        $this->winningBidId = $winningBidId;
        return $this;
    }

    private function transformVendorList($vendorList) 
    {
        $vendors = array();
        foreach( $vendorList as $vendor ) {
            $vendors[] = new Vendor($vendor);
        }
        return $vendors;
    }
}
