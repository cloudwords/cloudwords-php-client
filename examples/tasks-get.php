<?php
require_once '../src/Client.php';
$baseApiUrl = 'https://api-stage.cloudwords.com';
$apiVersion = '1';
$authToken  = 'UserToken <ENTER_AUTHORIZATION_TOKEN_HERE>';
$client = new \Cloudwords\Client($baseApiUrl, $apiVersion, $authToken);
// get project task for specified project
//print_r($client->getProjectTasks(10173));

// get project task for specified project with specified status
//print_r($client->getProjectTasksByStatus(10173, 'open'));
//print_r($client->getProjectTasksByStatus(10173, 'closed'));
//print_r($client->getProjectTasksByStatus(10173, 'rejected'));
//print_r($client->getProjectTasksByStatus(10173, 'cancelled'));

// get all project task accross all projects
//print_r($client->getAllProjectTasks());

// get all project task accross all projects with specified status
//print_r($client->getAllProjectTasksByStatus('open'));
//print_r($client->getAllProjectTasksByStatus('closed'));
//print_r($client->getAllProjectTasksByStatus('rejected'));
//print_r($client->getAllProjectTasksByStatus('cancelled'));
//print_r($client->getAllProjectTasksByDepartmentId(327));
//print_r($client->getAllProjectTasksWithStatusByDepartmentId(327, 'open'));
//print_r($client->getAllProjectTasksWithStatusByDepartmentId(327, 'closed'));
//print_r($client->getAllProjectTasksWithStatusByDepartmentId(327, 'rejected'));
//print_r($client->getAllProjectTasksWithStatusByDepartmentId(327, 'cancelled'));