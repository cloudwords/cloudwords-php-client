<?php
namespace Cloudwords\Tests\Integration;

require_once 'DepartmentTest.php';
require_once 'ProjectTaskTest.php';
require_once 'ProjectTest.php';
require_once 'UserTest.php';
require_once 'LanguageTest.php';
require_once 'VendorTest.php';

class AllTests
{
    public static function suite()
    {
        $suite = new \PHPUnit_Framework_TestSuite();
        $suite->setName('All Cloudwords API PHP Client Resource Objects Tests');
        $suite->addTestSuite('\Cloudwords\Tests\Integration\LanguageTest');
        $suite->addTestSuite('\Cloudwords\Tests\Integration\VendorTest');
        $suite->addTestSuite('\Cloudwords\Tests\Integration\ProjectTest');
        $suite->addTestSuite('\Cloudwords\Tests\Integration\UserTest');
        $suite->addTestSuite('\Cloudwords\Tests\Integration\DepartmentTest');
        $suite->addTestSuite('\Cloudwords\Tests\Integration\ProjectTaskTest');
        return $suite;
    }
}
