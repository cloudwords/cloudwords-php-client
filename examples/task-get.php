<?php
require_once '../src/Client.php';
$baseApiUrl = 'https://api-stage.cloudwords.com';
$apiVersion = '1';
$authToken  = 'UserToken <ENTER_AUTHORIZATION_TOKEN_HERE>';
$client = new \Cloudwords\Client($baseApiUrl, $apiVersion, $authToken);
//print_r($client->getProjectTask(10173, 29097));
$projectId = 10173;
//$taskId = 29537;
//$taskId = 29585;
//$taskId = 29587;
$taskId = 29589;
//print_r($client->getProjectTask($projectId, $taskId));
print_r($client->getTaskAttachment($projectId, $taskId));