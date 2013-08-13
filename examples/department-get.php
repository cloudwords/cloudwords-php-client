<?php
require_once '../src/Client.php';
$baseApiUrl = 'https://api-stage.cloudwords.com';
$apiVersion = '1';
$authToken  = 'UserToken <ENTER_AUTHORIZATION_TOKEN_HERE>';
$authToken  = 'UserToken ada9c21adf246e616a1fd52c81bf830db4e7ea5358aecfe78f19165772639dff';
$client = new \Cloudwords\Client($baseApiUrl, $apiVersion, $authToken);
//print_r($client->getDepartment());
print_r($client->getDepartmentByFilter(327, 'project/open.json'));
