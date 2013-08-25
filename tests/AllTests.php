<?php
namespace Cloudwords\Tests;

/*
 * This file is meant to be run through a php command line, not called
 * directly through the web browser. To run these tests from the command line:
 * # cd /path/to/cloudwords-api-php-client/
 * # phpunit AllTests.php
 */

require_once 'integration/AllTests.php';

class AllTests
{
    public static function suite()
    {
        $suite = new \PHPUnit_Framework_TestSuite();
        $suite->setName('All Cloudwords API PHP Client tests');
        $suite->addTestSuite(Integration\AllTests::suite());
        return $suite;
    }
}