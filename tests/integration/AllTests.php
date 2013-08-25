<?php
namespace Cloudwords\Tests\Integration;

require_once 'DepartmentTest.php';
require_once 'ProjectTaskTest.php';
require_once 'ProjectTest.php';
require_once 'UserTest.php';

class AllTests
{
    public static function suite()
    {
        $suite = new \PHPUnit_Framework_TestSuite();
        $suite->setName('All Cloudwords API PHP Client Resource Objects Tests');
        $suite->addTestSuite('\Cloudwords\Tests\Integration\UserTest');
        $suite->addTestSuite('\Cloudwords\Tests\Integration\DepartmentTest');
        $suite->addTestSuite('\Cloudwords\Tests\Integration\ProjectTaskTest');
        $suite->addTestSuite('\Cloudwords\Tests\Integration\ProjectTest');
        return $suite;
    }
}