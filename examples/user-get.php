<?php
require_once '../src/Client.php';
$baseApiUrl = 'https://api-stage.cloudwords.com';
$apiVersion = '1';
$authToken  = 'UserToken <ENTER_AUTHORIZATION_TOKEN_HERE>';

$client = new \Cloudwords\Client($baseApiUrl, $apiVersion, $authToken);
//print_r($client->getCurrentUser());
//print_r($client->getActiveUser());
//print_r($client->getAvailableFollowers());
//print_r($client->getActiveUserByDepartmentId(327));
print_r($client->getAvailableFollowersByDepartmentId(327));